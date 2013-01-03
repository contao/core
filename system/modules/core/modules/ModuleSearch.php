<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ModuleSearch
 *
 * Front end module "search".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ModuleSearch extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_search';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### WEBSITE SEARCH ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		// Trigger the search module from a custom form
		if (!$_GET['keywords'] && \Input::post('FORM_SUBMIT') == 'tl_search')
		{
			$_GET['keywords'] = \Input::post('keywords');
			$_GET['query_type'] = \Input::post('query_type');
			$_GET['per_page'] = \Input::post('per_page');
		}

		// Remove insert tags
		$strKeywords = trim(\Input::get('keywords'));
		$strKeywords = preg_replace('/\{\{[^\}]*\}\}/', '', $strKeywords);

		// Overwrite the default query_type
		if (\Input::get('query_type'))
		{
			$this->queryType = \Input::get('query_type');
		}

		$objFormTemplate = new \FrontendTemplate((($this->searchType == 'advanced') ? 'mod_search_advanced' : 'mod_search_simple'));

		$objFormTemplate->uniqueId = $this->id;
		$objFormTemplate->queryType = $this->queryType;
		$objFormTemplate->keyword = specialchars($strKeywords);
		$objFormTemplate->keywordLabel = $GLOBALS['TL_LANG']['MSC']['keywords'];
		$objFormTemplate->optionsLabel = $GLOBALS['TL_LANG']['MSC']['options'];
		$objFormTemplate->search = specialchars($GLOBALS['TL_LANG']['MSC']['searchLabel']);
		$objFormTemplate->matchAll = specialchars($GLOBALS['TL_LANG']['MSC']['matchAll']);
		$objFormTemplate->matchAny = specialchars($GLOBALS['TL_LANG']['MSC']['matchAny']);
		$objFormTemplate->id = ($GLOBALS['TL_CONFIG']['disableAlias'] && \Input::get('id')) ? \Input::get('id') : false;
		$objFormTemplate->action = $this->getIndexFreeRequest();

		// Redirect page
		if ($this->jumpTo && ($objTarget = $this->objModel->getRelated('jumpTo')) !== null)
		{
			$objFormTemplate->action = $this->generateFrontendUrl($objTarget->row());
		}

		$this->Template->form = $objFormTemplate->parse();
		$this->Template->pagination = '';
		$this->Template->results = '';

		// Execute the search if there are keywords
		if ($strKeywords != '' && $strKeywords != '*' && !$this->jumpTo)
		{
			// Reference page
			if ($this->rootPage > 0)
			{
				$intRootId = $this->rootPage;
				$arrPages = $this->Database->getChildRecords($this->rootPage, 'tl_page');
				array_unshift($arrPages, $this->rootPage);
			}
			// Website root
			else
			{
				global $objPage;
				$intRootId = $objPage->rootId;
				$arrPages = $this->Database->getChildRecords($objPage->rootId, 'tl_page');
			}

			// Return if there are no pages
			if (!is_array($arrPages) || empty($arrPages))
			{
				$this->log('No searchable pages found', 'ModuleSearch compile()', TL_ERROR);
				return;
			}

			$arrResult = null;
			$strChecksum = md5($strKeywords.\Input::get('query_type').$intRootId.$this->fuzzy);
			$query_starttime = microtime(true);
			$strCacheFile = 'system/cache/search/' . $strChecksum . '.json';

			// Load the cached result
			if (file_exists(TL_ROOT . '/' . $strCacheFile))
			{
				$objFile = new \File($strCacheFile);

				if ($objFile->mtime > time() - 1800)
				{
					$arrResult = json_decode($objFile->getContent(), true);
				}
				else
				{
					$objFile->delete();
				}
			}

			// Cache the result
			if ($arrResult === null)
			{
				try
				{
					$objSearch = \Search::searchFor($strKeywords, (\Input::get('query_type') == 'or'), $arrPages, 0, 0, $this->fuzzy);
					$arrResult = $objSearch->fetchAllAssoc();
				}
				catch (\Exception $e)
				{
					$this->log('Website search failed: ' . $e->getMessage(), 'ModuleSearch compile()', TL_ERROR);
					$arrResult = array();
				}

				$objFile = new \File($strCacheFile);
				$objFile->write(json_encode($arrResult));
				$objFile->close();
			}

			$query_endtime = microtime(true);

			// Sort out protected pages
			if ($GLOBALS['TL_CONFIG']['indexProtected'] && !BE_USER_LOGGED_IN)
			{
				$this->import('FrontendUser', 'User');

				foreach ($arrResult as $k=>$v)
				{
					if ($v['protected'])
					{
						if (!FE_USER_LOGGED_IN)
						{
							unset($arrResult[$k]);
						}
						else
						{
							$groups = deserialize($v['groups']);

							if (!is_array($groups) || empty($groups) || !count(array_intersect($groups, $this->User->groups)))
							{
								unset($arrResult[$k]);
							}
						}
					}
				}

				$arrResult = array_values($arrResult);
			}

			$count = count($arrResult);

			// No results
			if ($count < 1)
			{
				$this->Template->header = sprintf($GLOBALS['TL_LANG']['MSC']['sEmpty'], $strKeywords);
				$this->Template->duration = substr($query_endtime-$query_starttime, 0, 6) . ' ' . $GLOBALS['TL_LANG']['MSC']['seconds'];
				return;
			}

			$from = 1;
			$to = $count;

			// Pagination
			if ($this->perPage > 0)
			{
				$id = 'page_s' . $this->id;
				$page = \Input::get($id) ?: 1;
				$per_page = \Input::get('per_page') ?: $this->perPage;

				// Do not index or cache the page if the page number is outside the range
				if ($page < 1 || $page > max(ceil($count/$per_page), 1))
				{
					global $objPage;
					$objPage->noSearch = 1;
					$objPage->cache = 0;

					// Send a 404 header
					header('HTTP/1.1 404 Not Found');
					return;
				}

				$from = (($page - 1) * $per_page) + 1;
				$to = (($from + $per_page) > $count) ? $count : ($from + $per_page - 1);

				// Pagination menu
				if ($to < $count || $from > 1)
				{
					$objPagination = new \Pagination($count, $per_page, 7, $id);
					$this->Template->pagination = $objPagination->generate("\n  ");
				}
			}

			// Get the results
			for ($i=($from-1); $i<$to && $i<$count; $i++)
			{
				$objTemplate = new \FrontendTemplate($this->searchTpl ?: 'search_default');

				$objTemplate->url = $arrResult[$i]['url'];
				$objTemplate->link = $arrResult[$i]['title'];
				$objTemplate->href = $arrResult[$i]['url'];
				$objTemplate->title = specialchars($arrResult[$i]['title']);
				$objTemplate->class = (($i == ($from - 1)) ? 'first ' : '') . (($i == ($to - 1) || $i == ($count - 1)) ? 'last ' : '') . (($i % 2 == 0) ? 'even' : 'odd');
				$objTemplate->relevance = sprintf($GLOBALS['TL_LANG']['MSC']['relevance'], number_format($arrResult[$i]['relevance'] / $arrResult[0]['relevance'] * 100, 2) . '%');
				$objTemplate->filesize = $arrResult[$i]['filesize'];
				$objTemplate->matches = $arrResult[$i]['matches'];

				$arrContext = array();
				$arrMatches = trimsplit(',', $arrResult[$i]['matches']);

				// Get the context
				foreach ($arrMatches as $strWord)
				{
					$arrChunks = array();
					preg_match_all('/\b.{0,'.$this->contextLength.'}\PL' . $strWord . '\PL.{0,'.$this->contextLength.'}\b/ui', $arrResult[$i]['text'], $arrChunks);

					foreach ($arrChunks[0] as $strContext)
					{
						$arrContext[] = ' ' . $strContext . ' ';
					}
				}

				// Shorten the context and highlight all keywords
				if (!empty($arrContext))
				{
					$objTemplate->context = trim(\String::substrHtml(implode('…', $arrContext), $this->totalLength));
					$objTemplate->context = preg_replace('/(\PL)(' . implode('|', $arrMatches) . ')(\PL)/ui', '$1<span class="highlight">$2</span>$3', $objTemplate->context);

					$objTemplate->hasContext = true;
				}

				$this->Template->results .= $objTemplate->parse();
			}

			$this->Template->header = vsprintf($GLOBALS['TL_LANG']['MSC']['sResults'], array($from, $to, $count, $strKeywords));
			$this->Template->duration = substr($query_endtime-$query_starttime, 0, 6) . ' ' . $GLOBALS['TL_LANG']['MSC']['seconds'];
		}
	}
}
