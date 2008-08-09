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
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleNews
 *
 * Parent class for news modules.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
abstract class ModuleNews extends Module
{

	/**
	 * URL cache array
	 * @var array
	 */
	private static $arrCache = array();


	/**
	 * Sort out protected archives
	 * @param array
	 * @return array
	 */
	protected function sortOutProtected($arrArchives)
	{
		if (BE_USER_LOGGED_IN)
		{
			return $arrArchives;
		}

		$this->import('FrontendUser', 'User');
		$objArchive = $this->Database->execute("SELECT id, protected, groups FROM tl_news_archive WHERE id IN(" . implode(',', $arrArchives) . ")");
		$arrArchives = array();

		while ($objArchive->next())
		{
			if ($objArchive->protected)
			{
				$groups = deserialize($objArchive->groups, true);

				if (!is_array($this->User->groups) || count($this->User->groups) < 1 || !is_array($groups) || count($groups) < 1)
				{
					continue;
				}

				if (count(array_intersect($groups, $this->User->groups)) < 1)
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
	 * @param object
	 * @param boolean
	 * @return array
	 */
	protected function parseArticles(Database_Result $objArticles, $blnAddArchive=false)
	{
		if ($objArticles->numRows < 1)
		{
			return array();
		}

		$arrArticles = array();
		$limit = $objArticles->numRows;
		$count = 0;

		while ($objArticles->next())
		{
			$objTemplate = new Template($this->news_template);
			$objTemplate->class =  ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even');

			$objTemplate->text = $objArticles->text;
			$objTemplate->teaser = $objArticles->teaser;
			$objTemplate->newsHeadline = $objArticles->headline;
			$objTemplate->subHeadline = $objArticles->subheadline;
			$objTemplate->hasSubHeadline = $objArticles->subheadline ? true : false;
			$objTemplate->linkHeadline = $this->generateLink($objArticles->headline, $objArticles, $blnAddArchive);
			$objTemplate->more = $this->generateLink($GLOBALS['TL_LANG']['MSC']['more'], $objArticles, $blnAddArchive);
			$objTemplate->link = $this->generateNewsUrl($objArticles, $blnAddArchive);
			$objTemplate->archive = $objArticles->archive;
			$objTemplate->addImage = false;

			// Add an image
			if ($objArticles->addImage && is_file(TL_ROOT . '/' . $objArticles->singleSRC))
			{
				$size = deserialize($objArticles->size);
				$src = $this->getImage($this->urlEncode($objArticles->singleSRC), $size[0], $size[1]);

				if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false)
				{
					$objTemplate->imgSize = ' ' . $imgSize[3];
				}

				$objTemplate->src = $src;
				$objTemplate->href = $objArticles->singleSRC;
				$objTemplate->alt = specialchars($objArticles->alt);
				$objTemplate->fullsize = $objArticles->fullsize ? true : false;
				$objTemplate->margin = $this->generateMargin(deserialize($objArticles->imagemargin), 'padding');
				$objTemplate->float = in_array($objArticles->floating, array('left', 'right')) ? sprintf(' float:%s;', $objArticles->floating) : '';
				$objTemplate->caption = $objArticles->caption;
				$objTemplate->addImage = true;
			}

			$arrMeta = $this->getMetaFields($objArticles);

			$objTemplate->date = $arrMeta['date'];
			$objTemplate->dateFormat = $this->news_dateFormat;
			$objTemplate->hasMetaFields = count($arrMeta) ? true : false;
			$objTemplate->numberOfComments = $arrMeta['ccount'];
			$objTemplate->commentCount = $arrMeta['comments'];
			$objTemplate->timestamp = $objArticles->date;
			$objTemplate->author = $arrMeta['author'];

			$arrEnclosures = array();

 			// Add enclosure
			if ($objArticles->addEnclosure)
			{
				$arrEnclosure = deserialize($objArticles->enclosure, true);
				$allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

				if (is_array($arrEnclosure))
				{
					// Send file to the browser
					if (strlen($this->Input->get('file')) && in_array($this->Input->get('file'), $arrEnclosure))
					{
						$this->sendFileToBrowser($this->Input->get('file'));
					}

					// Add download links
					for ($i=0; $i<count($arrEnclosure); $i++)
					{
						if (is_file(TL_ROOT . '/' . $arrEnclosure[$i]))
						{				
							$objFile = new File($arrEnclosure[$i]);

							if (in_array($objFile->extension, $allowedDownload))
							{
								$size = ' ('.number_format(($objFile->filesize/1024), 1, $GLOBALS['TL_LANG']['MSC']['decimalSeparator'], $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']).' kB)';
								$src = 'system/themes/' . $this->getTheme() . '/images/' . $objFile->icon;

								if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false)
								{
									$arrEnclosures[$i]['size'] = ' ' . $imgSize[3];
								}

								$arrEnclosures[$i]['icon'] = $src;
								$arrEnclosures[$i]['link'] = basename($arrEnclosure[$i]) . $size;
								$arrEnclosures[$i]['title'] = ucfirst(str_replace('_', ' ', $objFile->filename));
								$arrEnclosures[$i]['href'] = $this->Environment->request . (($GLOBALS['TL_CONFIG']['disableAlias'] || count($_GET)) ? '&amp;' : '?') . 'file=' . $this->urlEncode($arrEnclosure[$i]);
								$arrEnclosures[$i]['enclosure'] = $arrEnclosure[$i];
							}
						}
					}
				}
			}

			$objTemplate->enclosure = $arrEnclosures;
			$arrArticles[] = $objTemplate->parse();
		}

		return $arrArticles;
	}


	/**
	 * Return the meta fields of a news article as array
	 * @param object
	 * @return array
	 */
	private function getMetaFields(Database_Result $objArticle)
	{
		$meta = deserialize($this->news_metaFields);

		if (!is_array($meta))
		{
			return array();
		}

		$return = array();

		foreach ($meta as $field)
		{
			switch ($field)
			{
				case 'date':
					$return['date'] = date((strlen($this->news_dateFormat) ? $this->news_dateFormat : $GLOBALS['TL_CONFIG']['datimFormat']), $objArticle->date);
					break;

				case 'author':
					if (!strlen($objArticle->author))
					{
						break;
					}

					$strCacheKey = 'author_' . $objArticle->author;

					// Load from cache
					if (array_key_exists($strCacheKey, self::$arrCache))
					{
						$return['author'] = self::$arrCache[$strCacheKey];
						break;
					}

					// Select from database
					$objAuthor = $this->Database->prepare("SELECT name FROM tl_user WHERE id=?")
												->limit(1)
												->execute($objArticle->author);

					if ($objAuthor->numRows)
					{
						self::$arrCache[$strCacheKey] = $GLOBALS['TL_LANG']['MSC']['by'] . ' ' . $objAuthor->name;
						$return['author'] = self::$arrCache[$strCacheKey];
					}
					break;

				case 'comments':
					$objComments = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_news_comments WHERE pid=?" . (!BE_USER_LOGGED_IN ? " AND published=1" : ""))
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
	 * @param object
	 * @param boolean
	 * @return string
	 */
	private function generateNewsUrl(Database_Result $objArticle, $blnAddArchive=false)
	{
		$strCacheKey = 'id_' . $objArticle->id;

		// Load URL from cache
		if (array_key_exists($strCacheKey, self::$arrCache))
		{
			return self::$arrCache[$strCacheKey];
		}

		// Link to external page
		if ($objArticle->source == 'external')
		{
			$this->import('String');

			if (substr($objArticle->url, 0, 7) == 'mailto:')
			{
				$objArticle->url = 'mailto:' . $this->String->encodeEmail(substr($objArticle->url, 7));
			}

			self::$arrCache[$strCacheKey] = ampersand($objArticle->url);
		}

		// Link to internal page
		else
		{
			$strUrl = ampersand($this->Environment->request, ENCODE_AMPERSANDS);

			// Get target page
			$objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
								 	  ->limit(1)
									  ->execute((($objArticle->source == 'default') ? $objArticle->parentJumpTo : $objArticle->jumpTo));

			if ($objPage->numRows)
			{
				// Link to newsreader
				if ($objArticle->source == 'default')
				{
					$strUrl = ampersand($this->generateFrontendUrl($objPage->fetchAssoc(), '/items/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objArticle->alias)) ? $objArticle->alias : $objArticle->id)));
				}

				// Link to internal page
				else
				{
					$strUrl = ampersand($this->generateFrontendUrl($objPage->fetchAssoc()));
				}
			}

			// Add the current archive parameter (news archive)
			if ($blnAddArchive && strlen($this->Input->get('month')))
			{
				$strUrl .= ($GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' : '?') . 'month=' . $this->Input->get('month');
			}

			self::$arrCache[$strCacheKey] = $strUrl;
		}

		return self::$arrCache[$strCacheKey];
	}


	/**
	 * Generate a link and return it as string
	 * @param string
	 * @param object
	 * @param boolean
	 * @return string
	 */
	private function generateLink($strLink, Database_Result $objArticle, $blnAddArchive=false)
	{
		// Internal link
		if ($objArticle->source != 'external')
		{
			return sprintf('<a href="%s" title="%s">%s</a>',
							$this->generateNewsUrl($objArticle, $blnAddArchive),
							$GLOBALS['TL_LANG']['MSC']['readMore'],
							$strLink);
		}

		// Encode e-mail addresses
		if (substr($objArticle->url, 0, 7) == 'mailto:')
		{
			$this->import('String');
			$objArticle->url = 'mailto:' . $this->String->encodeEmail(substr($objArticle->url, 7));
		}

		// Ampersand URIs
		else
		{
			$objArticle->url = ampersand($objArticle->url);
		}

		// External link
		return sprintf('<a href="%s" title="%s"%s>%s</a>',
						$objArticle->url,
						specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['open'], $objArticle->url)),
						($objArticle->target ? LINK_NEW_WINDOW_BLUR : ''),
						$strLink);
	}
}

?>