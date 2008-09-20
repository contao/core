<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleSearch
 *
 * Front end module "search".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleSearch extends Module
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
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### WEBSITE SEARCH ###';

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		$this->import('Search');

		// Trigger the search module from a custom form
		if (!$_GET['keywords'] && $this->Input->post('FORM_SUBMIT') == 'tl_search')
		{
			$_GET['keywords'] = $this->Input->post('keywords');
			$_GET['query_type'] = $this->Input->post('query_type');
			$_GET['per_page'] = $this->Input->post('per_page');
		}

		$strKeywords = trim($this->Input->get('keywords'));

		// Overwrite default query_type
		if ($this->Input->get('query_type'))
		{
			$this->queryType = $this->Input->get('query_type');
		}

		$objFormTemplate = new FrontendTemplate((($this->searchType == 'advanced') ? 'mod_search_advanced' : 'mod_search_simple'));

		$objFormTemplate->queryType = $this->queryType;
		$objFormTemplate->keyword = specialchars($strKeywords);
		$objFormTemplate->search = specialchars($GLOBALS['TL_LANG']['MSC']['searchLabel']);
		$objFormTemplate->matchAll = specialchars($GLOBALS['TL_LANG']['MSC']['matchAll']);
		$objFormTemplate->matchAny = specialchars($GLOBALS['TL_LANG']['MSC']['matchAny']);
		$objFormTemplate->id = ($GLOBALS['TL_CONFIG']['disableAlias'] && $this->Input->get('id')) ? $this->Input->get('id') : false;
		$objFormTemplate->action = ampersand($this->Environment->request);

		$this->Template->form = $objFormTemplate->parse();
		$this->Template->pagination = '';
		$this->Template->results = '';

		// Execute search if there are keywords
		if (strlen($strKeywords) && $strKeywords != '*')
		{
			global $objPage;

			$arrResult = null;
			$arrPages = $this->getChildRecords($objPage->rootId, 'tl_page');

			// Return if there are no pages
			if (!is_array($arrPages) || count($arrPages) < 1)
			{
				$this->log('No searchable pages found', 'ModuleSearch compile()', TL_ERROR);
				return;
			}

			$strChecksum = md5($strKeywords.$this->Input->get('query_type').$objPage->rootId);
			$query_starttime = microtime(true);

			// Get cached result
			if (file_exists(TL_ROOT . '/system/tmp/' . $strChecksum))
			{
				$objFile = new File('system/tmp/' . $strChecksum);

				if ($objFile->mtime > time() - 1800)
				{
					$arrResult = deserialize($objFile->getContent());
				}
			}

			// Cache result
			if (is_null($arrResult))
			{
				$objFile = new File('system/tmp/' . $strChecksum);

				try
				{
					$objSearch = $this->Search->searchFor($strKeywords, ($this->Input->get('query_type') == 'or'), $arrPages);
					$arrResult = $objSearch->fetchAllAssoc();
				}
				catch (Exception $e)
				{
					$this->log('Website search failed: ' . $e->getMessage(), 'ModuleSearch compile()', TL_ERROR);
					$arrResult = array();
				}

				$objFile->write(serialize($arrResult));
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
						$v['groups'] = deserialize($v['groups']);

						if (is_array($v['groups']) && count(array_intersect($this->User->groups, $v['groups'])) < 1)
						{
							unset($arrResult[$k]);
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
				$page = $this->Input->get('page') ? $this->Input->get('page') : 1;
				$per_page = $this->Input->get('per_page') ? $this->Input->get('per_page') : $this->perPage;

				// Reset page navigator if page exceeds the lower or upper limit
				if ($page > ceil($count/$per_page) || $page < 1)
				{
					$page = 1;
				}

				$from = (($page - 1) * $per_page) + 1;
				$to = (($from + $per_page) > $count) ? $count : ($from + $per_page - 1);

				// Pagination menu
				if ($to < $count || $from > 1)
				{
					$objPagination = new Pagination($count, $per_page);
					$this->Template->pagination = $objPagination->generate("\n  ");
				}
			}

			// Get results
			for ($i=($from-1); $i<$to && $i<$count; $i++)
			{
				$strHref = $arrResult[$i]['url'];

				if (!$GLOBALS['TL_CONFIG']['rewriteURL'] && !$GLOBALS['TL_CONFIG']['disableAlias'])
				{
					$strHref = 'index.php/' . $strHref;
				}

				$objTemplate = new FrontendTemplate((strlen($this->searchTpl) ? $this->searchTpl : 'search_default'));

				$objTemplate->url = $arrResult[$i]['url'];
				$objTemplate->link = $arrResult[$i]['title'];
				$objTemplate->href = $this->Environment->base . $strHref;
				$objTemplate->title = specialchars($arrResult[$i]['title']);
				$objTemplate->class = (($i == ($from - 1)) ? 'first ' : '') . (($i == ($to - 1) || $i == ($count - 1)) ? 'last ' : '') . (($i % 2 == 0) ? 'even' : 'odd');
				$objTemplate->relevance = number_format($arrResult[$i]['relevance'] / $arrResult[0]['relevance'] * 100, 2);
				$objTemplate->filesize = $arrResult[$i]['filesize'];
				$objTemplate->matches = $arrResult[$i]['matches'];

				$arrContext = array();
				$arrMatches = trimsplit(',', $arrResult[$i]['matches']);

				// Get context
				foreach ($arrMatches as $strWord)
				{
					$arrChunks = array();
					preg_match_all('/\b.{0,'.$this->contextLength.'}\PL' . $strWord . '\PL.{0,'.$this->contextLength.'}\b/ui', $arrResult[$i]['text'], $arrChunks);

					foreach ($arrChunks[0] as $strContext)
					{
						$arrContext[] = ' ' . $strContext . ' ';
					}
				}

				// Shorten context and highlight keywords
				if (count($arrContext))
				{
					$this->import('String');

					$objTemplate->context = trim($this->String->substrHtml(implode('…', $arrContext), $this->totalLength));
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

?>