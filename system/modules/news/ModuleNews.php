<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleNews
 *
 * Parent class for news modules.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
abstract class ModuleNews extends Module
{

	/**
	 * URL cache array
	 * @var array
	 */
	private static $arrUrlCache = array();


	/**
	 * Sort out protected archives
	 * @param array
	 * @return array
	 */
	protected function sortOutProtected($arrArchives)
	{
		if (BE_USER_LOGGED_IN || !is_array($arrArchives) || empty($arrArchives))
		{
			return $arrArchives;
		}

		$this->import('FrontendUser', 'User');
		$objArchive = $this->Database->execute("SELECT id, protected, groups FROM tl_news_archive WHERE id IN(" . implode(',', array_map('intval', $arrArchives)) . ")");
		$arrArchives = array();

		while ($objArchive->next())
		{
			if ($objArchive->protected)
			{
				if (!FE_USER_LOGGED_IN)
				{
					continue;
				}

				$groups = deserialize($objArchive->groups);

				if (!is_array($groups) || empty($groups) || !count(array_intersect($groups, $this->User->groups)))
				{
					continue;
				}
			}

			$arrArchives[] = $objArchive->id;
		}

		return $arrArchives;
	}


	/**
	 * Parse one or more items and return them as array
	 * @param Database_Result
	 * @param boolean
	 * @return array
	 */
	protected function parseArticles(Database_Result $objArticles, $blnAddArchive=false)
	{
		if ($objArticles->numRows < 1)
		{
			return array();
		}

		global $objPage;
		$this->import('String');

		$arrArticles = array();
		$limit = $objArticles->numRows;
		$count = 0;
		$imgSize = false;

		// Override the default image size
		if ($this->imgSize != '')
		{
			$size = deserialize($this->imgSize);

			if ($size[0] > 0 || $size[1] > 0)
			{
				$imgSize = $this->imgSize;
			}
		}

		while ($objArticles->next())
		{
			$objTemplate = new FrontendTemplate($this->news_template);
			$objTemplate->setData($objArticles->row());

			$objTemplate->count = ++$count;
			$objTemplate->class = (($objArticles->cssClass != '') ? ' ' . $objArticles->cssClass : '') . (($count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even');
			$objTemplate->newsHeadline = $objArticles->headline;
			$objTemplate->subHeadline = $objArticles->subheadline;
			$objTemplate->hasSubHeadline = $objArticles->subheadline ? true : false;
			$objTemplate->linkHeadline = $this->generateLink($objArticles->headline, $objArticles, $blnAddArchive);
			$objTemplate->more = $this->generateLink($GLOBALS['TL_LANG']['MSC']['more'], $objArticles, $blnAddArchive, true);
			$objTemplate->link = $this->generateNewsUrl($objArticles, $blnAddArchive);
			$objTemplate->archive = $objArticles->archive;

			// Clean the RTE output
			if ($objArticles->teaser != '')
			{
				if ($objPage->outputFormat == 'xhtml')
				{
					$objArticles->teaser = $this->String->toXhtml($objArticles->teaser);
				}
				else
				{
					$objArticles->teaser = $this->String->toHtml5($objArticles->teaser);
				}

				$objTemplate->teaser = $this->String->encodeEmail($objArticles->teaser);
			}

			// Display the "read more" button for external/article links
			if ($objArticles->source != 'default' && $objArticles->text == '')
			{
				$objTemplate->text = true;
			}

			// Encode e-mail addresses
			else
			{
				// Clean the RTE output
				if ($objPage->outputFormat == 'xhtml')
				{
					$objArticles->text = $this->String->toXhtml($objArticles->text);
				}
				else
				{
					$objArticles->text = $this->String->toHtml5($objArticles->text);
				}

				$objTemplate->text = $this->String->encodeEmail($objArticles->text);
			}

			$arrMeta = $this->getMetaFields($objArticles);

			// Add meta information
			$objTemplate->date = $arrMeta['date'];
			$objTemplate->hasMetaFields = !empty($arrMeta);
			$objTemplate->numberOfComments = $arrMeta['ccount'];
			$objTemplate->commentCount = $arrMeta['comments'];
			$objTemplate->timestamp = $objArticles->date;
			$objTemplate->author = $arrMeta['author'];
			$objTemplate->datetime = date('Y-m-d\TH:i:sP', $objArticles->date);

			$objTemplate->addImage = false;

			// Add an image
			if ($objArticles->addImage && is_file(TL_ROOT . '/' . $objArticles->singleSRC))
			{
				if ($imgSize)
				{
					$objArticles->size = $imgSize;
				}

				$this->addImageToTemplate($objTemplate, $objArticles->row());
			}

			$objTemplate->enclosure = array();

			// Add enclosures
			if ($objArticles->addEnclosure)
			{
				$this->addEnclosuresToTemplate($objTemplate, $objArticles->row());
			}

			// HOOK: add custom logic
			if (isset($GLOBALS['TL_HOOKS']['parseArticles']) && is_array($GLOBALS['TL_HOOKS']['parseArticles']))
			{
				foreach ($GLOBALS['TL_HOOKS']['parseArticles'] as $callback)
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($objTemplate, $objArticles->row(), $this);
				}
			}

			$arrArticles[] = $objTemplate->parse();
		}

		return $arrArticles;
	}


	/**
	 * Return the meta fields of a news article as array
	 * @param Database_Result
	 * @return array
	 */
	protected function getMetaFields(Database_Result $objArticle)
	{
		$meta = deserialize($this->news_metaFields);

		if (!is_array($meta))
		{
			return array();
		}

		global $objPage;
		$return = array();

		foreach ($meta as $field)
		{
			switch ($field)
			{
				case 'date':
					$return['date'] = $this->parseDate($objPage->datimFormat, $objArticle->date);
					break;

				case 'author':
					if ($objArticle->author != '')
					{
						$return['author'] = $GLOBALS['TL_LANG']['MSC']['by'] . ' ' . $objArticle->author;
					}
					break;

				case 'comments':
					if ($objArticle->noComments || $objArticle->source != 'default')
					{
						break;
					}

					$objComments = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_comments WHERE source='tl_news' AND parent=?" . (!BE_USER_LOGGED_IN ? " AND published=1" : ""))
												  ->execute($objArticle->id);

					if ($objComments->numRows)
					{
						$return['ccount'] = $objComments->total;
						$return['comments'] = sprintf($GLOBALS['TL_LANG']['MSC']['commentCount'], $objComments->total);
					}
					break;
			}
		}

		return $return;
	}


	/**
	 * Generate a URL and return it as string
	 * @param Database_Result
	 * @param boolean
	 * @return string
	 */
	protected function generateNewsUrl(Database_Result $objArticle, $blnAddArchive=false)
	{
		$strCacheKey = 'id_' . $objArticle->id;

		// Load URL from cache
		if (isset(self::$arrUrlCache[$strCacheKey]))
		{
			return self::$arrUrlCache[$strCacheKey];
		}

		// Initialize the cache
		self::$arrUrlCache[$strCacheKey] = '';

		switch ($objArticle->source)
		{
			// Link to an external page
			case 'external':
				$this->import('String');

				if (substr($objArticle->url, 0, 7) == 'mailto:')
				{
					self::$arrUrlCache[$strCacheKey] = $this->String->encodeEmail($objArticle->url);
				}
				else
				{
					self::$arrUrlCache[$strCacheKey] = ampersand($objArticle->url);
				}
				break;

			// Link to an internal page
			case 'internal':
				$objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
									 	  ->limit(1)
										  ->execute($objArticle->jumpTo);

				if ($objPage->numRows)
				{
					self::$arrUrlCache[$strCacheKey] = ampersand($this->generateFrontendUrl($objPage->row()));
				}
				break;

			// Link to an article
			case 'article':
				$objPage = $this->Database->prepare("SELECT a.id AS aId, a.alias AS aAlias, a.title, p.id, p.alias FROM tl_article a, tl_page p WHERE a.pid=p.id AND a.id=?")
										  ->limit(1)
										  ->execute($objArticle->articleId);

				if ($objPage->numRows)
				{
					self::$arrUrlCache[$strCacheKey] = ampersand($this->generateFrontendUrl($objPage->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objPage->aAlias != '') ? $objPage->aAlias : $objPage->aId)));
				}
				break;
		}

		// Link to the default page
		if (self::$arrUrlCache[$strCacheKey] == '')
		{
			$objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
								 	  ->limit(1)
									  ->execute($objArticle->parentJumpTo);

			if ($objPage->numRows)
			{
				self::$arrUrlCache[$strCacheKey] = ampersand($this->generateFrontendUrl($objPage->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/' : '/items/') . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objArticle->alias != '') ? $objArticle->alias : $objArticle->id)));
			}
			else
			{
				self::$arrUrlCache[$strCacheKey] = ampersand($this->Environment->request, true);
			}

			// Add the current archive parameter (news archive)
			if ($blnAddArchive && $this->Input->get('month') != '')
			{
				self::$arrUrlCache[$strCacheKey] .= ($GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' : '?') . 'month=' . $this->Input->get('month');
			}
		}

		return self::$arrUrlCache[$strCacheKey];
	}


	/**
	 * Generate a link and return it as string
	 * @param string
	 * @param Database_Result
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	protected function generateLink($strLink, Database_Result $objArticle, $blnAddArchive=false, $blnIsReadMore=false)
	{
		// Internal link
		if ($objArticle->source != 'external')
		{
			return sprintf('<a href="%s" title="%s">%s%s</a>',
							$this->generateNewsUrl($objArticle, $blnAddArchive),
							specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $objArticle->headline), true),
							$strLink,
							($blnIsReadMore ? ' <span class="invisible">'.$objArticle->headline.'</span>' : ''));
		}

		// Encode e-mail addresses
		if (substr($objArticle->url, 0, 7) == 'mailto:')
		{
			$this->import('String');
			$objArticle->url = $this->String->encodeEmail($objArticle->url);
		}

		// Ampersand URIs
		else
		{
			$objArticle->url = ampersand($objArticle->url);
		}

		global $objPage;

		// External link
		return sprintf('<a href="%s" title="%s"%s>%s</a>',
						$objArticle->url,
						specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['open'], $objArticle->url)),
						($objArticle->target ? (($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"') : ''),
						$strLink);
	}
}

?>