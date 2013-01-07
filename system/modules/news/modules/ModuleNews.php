<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package News
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ModuleNews
 *
 * Parent class for news modules.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    News
 */
abstract class ModuleNews extends \Module
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
		$objArchive = \NewsArchiveModel::findMultipleByIds($arrArchives);
		$arrArchives = array();

		if ($objArchive !== null)
		{
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
		}

		return $arrArchives;
	}


	/**
	 * Parse an item and return it as string
	 * @param object
	 * @param boolean
	 * @param string
	 * @return string
	 */
	protected function parseArticle($objArticle, $blnAddArchive=false, $strClass='')
	{
		global $objPage;

		$objTemplate = new \FrontendTemplate($this->news_template);
		$objTemplate->setData($objArticle->row());

		$objTemplate->class = (($objArticle->cssClass != '') ? ' ' . $objArticle->cssClass : '') . $strClass;
		$objTemplate->newsHeadline = $objArticle->headline;
		$objTemplate->subHeadline = $objArticle->subheadline;
		$objTemplate->hasSubHeadline = $objArticle->subheadline ? true : false;
		$objTemplate->linkHeadline = $this->generateLink($objArticle->headline, $objArticle, $blnAddArchive);
		$objTemplate->more = $this->generateLink($GLOBALS['TL_LANG']['MSC']['more'], $objArticle, $blnAddArchive, true);
		$objTemplate->link = $this->generateNewsUrl($objArticle, $blnAddArchive);
		$objTemplate->archive = $objArticle->getRelated('pid');
		$objTemplate->text = '';

		// Clean the RTE output
		if ($objArticle->teaser != '')
		{
			if ($objPage->outputFormat == 'xhtml')
			{
				$objArticle->teaser = \String::toXhtml($objArticle->teaser);
			}
			else
			{
				$objArticle->teaser = \String::toHtml5($objArticle->teaser);
			}

			$objTemplate->teaser = \String::encodeEmail($objArticle->teaser);
		}

		// Display the "read more" button for external/article links
		if ($objArticle->source != 'default')
		{
			$objTemplate->text = true;
		}

		// Compile the news text
		else
		{
			$objElement = \ContentModel::findPublishedByPidAndTable($objArticle->id, 'tl_news');

			if ($objElement !== null)
			{
				while ($objElement->next())
				{
					$objTemplate->text .= $this->getContentElement($objElement->id);
				}
			}
		}

		$arrMeta = $this->getMetaFields($objArticle);

		// Add the meta information
		$objTemplate->date = $arrMeta['date'];
		$objTemplate->hasMetaFields = !empty($arrMeta);
		$objTemplate->numberOfComments = $arrMeta['ccount'];
		$objTemplate->commentCount = $arrMeta['comments'];
		$objTemplate->timestamp = $objArticle->date;
		$objTemplate->author = $arrMeta['author'];
		$objTemplate->datetime = date('Y-m-d\TH:i:sP', $objArticle->date);

		$objTemplate->addImage = false;

		// Add an image
		if ($objArticle->addImage && $objArticle->singleSRC != '')
		{
			if (!is_numeric($objArticle->singleSRC))
			{
				$objTemplate->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
			}
			else
			{
				$objModel = \FilesModel::findByPk($objArticle->singleSRC);

				if ($objModel !== null && is_file(TL_ROOT . '/' . $objModel->path))
				{
					// Override the default image size
					if ($this->imgSize != '')
					{
						$size = deserialize($this->imgSize);

						if ($size[0] > 0 || $size[1] > 0)
						{
							$objArticle->size = $this->imgSize;
						}
					}

					$objArticle->singleSRC = $objModel->path;
					$this->addImageToTemplate($objTemplate, $objArticle->row());
				}
			}
		}

		$objTemplate->enclosure = array();

		// Add enclosures
		if ($objArticle->addEnclosure)
		{
			$this->addEnclosuresToTemplate($objTemplate, $objArticle->row());
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['parseArticles']) && is_array($GLOBALS['TL_HOOKS']['parseArticles']))
		{
			foreach ($GLOBALS['TL_HOOKS']['parseArticles'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($objTemplate, $objArticle->row(), $this);
			}
		}

		return $objTemplate->parse();
	}


	/**
	 * Parse one or more items and return them as array
	 * @param object
	 * @param boolean
	 * @return array
	 */
	protected function parseArticles($objArticles, $blnAddArchive=false)
	{
		$limit = $objArticles->count();

		if ($limit < 1)
		{
			return array();
		}

		$count = 0;
		$arrArticles = array();

		while ($objArticles->next())
		{
			$arrArticles[] = $this->parseArticle($objArticles, $blnAddArchive, ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even'));
		}

		return $arrArticles;
	}


	/**
	 * Return the meta fields of a news article as array
	 * @param object
	 * @return array
	 */
	protected function getMetaFields($objArticle)
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
					if (($objAuthor = $objArticle->getRelated('author')) !== null)
					{
						$return['author'] = $GLOBALS['TL_LANG']['MSC']['by'] . ' ' . $objAuthor->name;
					}
					break;

				case 'comments':
					if ($objArticle->noComments || $objArticle->source != 'default')
					{
						break;
					}
					$intTotal = \CommentsModel::countPublishedBySourceAndParent('tl_news', $objArticle->id);
					$return['ccount'] = $intTotal;
					$return['comments'] = sprintf($GLOBALS['TL_LANG']['MSC']['commentCount'], $intTotal);
					break;
			}
		}

		return $return;
	}


	/**
	 * Generate a URL and return it as string
	 * @param object
	 * @param boolean
	 * @return string
	 */
	protected function generateNewsUrl($objItem, $blnAddArchive=false)
	{
		$strCacheKey = 'id_' . $objItem->id;

		// Load the URL from cache
		if (isset(self::$arrUrlCache[$strCacheKey]))
		{
			return self::$arrUrlCache[$strCacheKey];
		}

		// Initialize the cache
		self::$arrUrlCache[$strCacheKey] = null;

		switch ($objItem->source)
		{
			// Link to an external page
			case 'external':
				if (substr($objItem->url, 0, 7) == 'mailto:')
				{
					self::$arrUrlCache[$strCacheKey] = \String::encodeEmail($objItem->url);
				}
				else
				{
					self::$arrUrlCache[$strCacheKey] = ampersand($objItem->url);
				}
				break;

			// Link to an internal page
			case 'internal':
				if (($objTarget = $objItem->getRelated('jumpTo')) !== null)
				{
					self::$arrUrlCache[$strCacheKey] = ampersand($this->generateFrontendUrl($objTarget->row()));
				}
				break;

			// Link to an article
			case 'article':
				$objArticle = \ArticleModel::findByPk($objItem->articleId, array('eager'=>true));

				if ($objArticle !== null)
				{
					self::$arrUrlCache[$strCacheKey] = ampersand($this->generateFrontendUrl($objArticle->getRelated('pid')->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objArticle->alias != '') ? $objArticle->alias : $objArticle->id)));
				}
				break;
		}

		// Link to the default page
		if (self::$arrUrlCache[$strCacheKey] === null)
		{
			$objPage = \PageModel::findByPk($objItem->getRelated('pid')->jumpTo);

			if ($objPage === null)
			{
				self::$arrUrlCache[$strCacheKey] = ampersand(\Environment::get('request'), true);
			}
			else
			{
				self::$arrUrlCache[$strCacheKey] = ampersand($this->generateFrontendUrl($objPage->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/' : '/items/') . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objItem->alias != '') ? $objItem->alias : $objItem->id)));
			}

			// Add the current archive parameter (news archive)
			if ($blnAddArchive && \Input::get('month') != '')
			{
				self::$arrUrlCache[$strCacheKey] .= ($GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' : '?') . 'month=' . \Input::get('month');
			}
		}

		return self::$arrUrlCache[$strCacheKey];
	}


	/**
	 * Generate a link and return it as string
	 * @param string
	 * @param object
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	protected function generateLink($strLink, $objArticle, $blnAddArchive=false, $blnIsReadMore=false)
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
			$objArticle->url = \String::encodeEmail($objArticle->url);
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
