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
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class Controller
 *
 * Provide methods to manage controllers.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
abstract class Controller extends System
{

	/**
	 * Return the current theme as string
	 * @return string
	 */
	protected function getTheme()
	{
		$theme = $GLOBALS['TL_CONFIG']['backendTheme'];

		if (strlen($theme) && is_dir(TL_ROOT . '/system/themes/' . $theme))
		{
			return $theme;
		}

		return 'default';
	}


	/**
	 * Find a particular template file and return its path
	 * @param string
	 * @return string
	 * @throws Exception
	 */
	protected function getTemplate($strTemplate)
	{
		$strFile = sprintf('%s/templates/%s.tpl', TL_ROOT, $strTemplate);

		if (!file_exists($strFile))
		{
			foreach ($this->Config->getActiveModules() as $strModule)
			{
				$strPath = sprintf('%s/system/modules/%s/templates/%s.tpl', TL_ROOT, $strModule, $strTemplate);

				if (file_exists($strPath))
				{
					$strFile = $strPath;
					break;
				}
			}
		}

		if (!file_exists($strFile))
		{
			throw new Exception(sprintf('Could not find template file "%s"', $strTemplate));
		}

		return $strFile;
	}


	/**
	 * Return all template files of a particular group as array
	 * @param string
	 * @return array
	 */
	protected function getTemplateGroup($strPrefix)
	{
		$arrTemplates = array();
		$arrFolders = array(TL_ROOT . '/templates');

		foreach ($this->Config->getActiveModules() as $strModule)
		{
			$strFolder = sprintf('%s/system/modules/%s/templates', TL_ROOT, $strModule);

			if (is_dir($strFolder))
			{
				$arrFolders[] = $strFolder;
			}
		}

		foreach ($arrFolders as $strFolder)
		{
			$arrFiles = preg_grep('/^' . preg_quote($strPrefix, '/') . '.*\.tpl$/i',  scan($strFolder));

			foreach ($arrFiles as $strTemplate)
			{
				$arrTemplates[] = basename($strTemplate, '.tpl');
			}
		}

		return array_values(array_unique($arrTemplates));
	}


	/**
	 * Generate a front end module and return it as HTML string
	 * @param integer
	 * @param string
	 * @return string
	 */
	protected function getFrontendModule($intId, $strColumn='main')
	{
		global $objPage;
		$this->import('Database');

		if (!strlen($intId))
		{
			return '';
		}

		// Articles
		if ($intId == 0)
		{
			// Show a particular article only
			if ($this->Input->get('articles'))
			{
				$strSections = strlen($this->Input->get('sections')) ? $this->Input->get('sections') : 'main';

				if ($strSections == $strColumn)
				{
					return $this->getArticle($this->Input->get('articles'));
				}
			}

			// Show all articles of the current column
			$objArticles = $this->Database->prepare("SELECT id FROM tl_article WHERE pid=? AND inColumn=? ORDER BY sorting")
										  ->execute($objPage->id, $strColumn);

			if (($count = $objArticles->numRows) < 1)
			{
				return '';
			}

			$return = '';

			while ($objArticles->next())
			{
				$return .= $this->getArticle($objArticles->id, (($count > 1) ? true : false));
			}

			return $return;
		}

		// Other modules
		$objModule = $this->Database->prepare("SELECT * FROM tl_module WHERE id=?")
									->limit(1)
									->execute($intId);

		if ($objModule->numRows < 1)
		{
			return '';
		}

		// Show to guests only
		if ($objModule->guests && FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN && !$objModule->protected)
		{
			return '';
		}

		// Protected element
		if (!BE_USER_LOGGED_IN && $objModule->protected)
		{
			if (!FE_USER_LOGGED_IN)
			{
				return '';
			}

			$this->import('FrontendUser', 'User');
			$arrGroups = deserialize($objModule->groups);
	
			if (is_array($arrGroups) && count(array_intersect($this->User->groups, $arrGroups)) < 1)
			{
				return '';
			}
		}

		$strClass = $this->findFrontendModule($objModule->type);

		if (!$this->classFileExists($strClass))
		{
			$this->log('Module class "'.$GLOBALS['FE_MOD'][$objModule->type].'" (module "'.$objModule->type.'") does not exist', 'Controller getFrontendModule()', TL_ERROR);
			return '';
		}

		$objModule->typePrefix = 'mod_';
		$objModule = new $strClass($objModule);


		$strBuffer = $objModule->generate();

		// Disable indexing if protected
		if ($objModule->protected && !preg_match('/^\s*<!-- indexer::stop/i', $strBuffer))
		{
			$strBuffer = "\n<!-- indexer::stop -->$strBuffer<!-- indexer::continue -->\n";
		}

		return $strBuffer;
	}


	/**
	 * Generate an article and return it as string
	 * @param integer
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	protected function getArticle($varId, $blnMultiMode=false, $blnIsInsertTag=false)
	{
		if (!strlen($varId))
		{
			return '';
		}

		global $objPage;
		$this->import('Database');

		// Get article
		$objArticle = $this->Database->prepare("SELECT * FROM tl_article WHERE (id=? OR alias=?)" . (!$blnIsInsertTag ? " AND pid=?" : ""))
									 ->limit(1)
									 ->execute((is_numeric($varId) ? $varId : 0), $varId, $objPage->id);

		if ($objArticle->numRows < 1)
		{
			// Do not index the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			// Send 404 header
			header('HTTP/1.0 404 Not Found');
			return '';
		}

		if (!file_exists(TL_ROOT . '/system/modules/frontend/ModuleArticle.php'))
		{
			$this->log('Class ModuleArticle does not exist', 'Controller getArticle()', TL_ERROR);
			return '';
		}

		// Print article as PDF
		if ($this->Input->get('pdf') == $objArticle->id)
		{
			$this->printArticleAsPdf($objArticle);
		}

		$objArticle->headline = $objArticle->title;
		$objArticle->multiMode = $blnMultiMode;

		$objArticle = new ModuleArticle($objArticle);
		return $objArticle->generate($blnIsInsertTag);
	}


	/**
	 * Generate a content element return it as HTML string
	 * @param integer
	 * @return string
	 */
	protected function getContentElement($intId)
	{
		$this->import('Database');

		if (!strlen($intId) || $intId < 1)
		{
			return '';
		}

		$objElement = $this->Database->prepare("SELECT * FROM tl_content WHERE id=?")
									 ->limit(1)
									 ->execute($intId);

		if ($objElement->numRows < 1)
		{
			return '';
		}

		// Show to guests only
		if ($objElement->guests && FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN && !$objElement->protected)
		{
			return '';
		}

		// Protected element
		if ($objElement->type != 'comments' && !BE_USER_LOGGED_IN && $objElement->protected)
		{
			if (!FE_USER_LOGGED_IN)
			{
				return '';
			}

			$this->import('FrontendUser', 'User');
			$arrGroups = deserialize($objElement->groups);
	
			if (is_array($arrGroups) && count(array_intersect($this->User->groups, $arrGroups)) < 1)
			{
				return '';
			}
		}

		// Remove spacing in the back end preview
		if (TL_MODE == 'BE')
		{
			$objElement->space = null;
		}

		$strClass = $this->findContentElement($objElement->type);

		if (!$this->classFileExists($strClass))
		{
			$this->log('Content element class "'.$strClass.'" (content element "'.$objElement->type.'") does not exist', 'Controller getContentElement()', TL_ERROR);
			return '';
		}

		$objElement->typePrefix = 'ce_';
		$objElement = new $strClass($objElement);

		$strBuffer = $objElement->generate();

		// Disable indexing if protected
		if ($objElement->protected && !preg_match('/^\s*<!-- indexer::stop/i', $strBuffer))
		{
			$strBuffer = "\n<!-- indexer::stop -->$strBuffer<!-- indexer::continue -->\n";
		}

		return $strBuffer;
	}


	/**
	 * Get the details of a page including inherited parameters and return it as object
	 * @param integer
	 * @return object
	 */
	protected function getPageDetails($intId)
	{
		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
						->limit(1)
						->execute($intId);

		if ($objPage->numRows < 1)
		{
			return null;
		}

		// Set some default values
		$objPage->protected = (boolean) $objPage->protected;
		$objPage->groups = $objPage->protected ? deserialize($objPage->groups) : false;
		$objPage->layout = $objPage->includeLayout ? $objPage->layout : false;
		$objPage->cache = $objPage->includeCache ? $objPage->cache : false;

		$pid = $objPage->pid;
		$type = $objPage->type;
		$alias = $objPage->alias;
		$name = $objPage->title;
		$title = strlen($objPage->pageTitle) ? $objPage->pageTitle : $objPage->title;
		$palias = '';
		$pname = '';
		$ptitle = '';

		$trail = array($intId, $pid);

		// Inherit settings
		do
		{
			$objParentPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
											->limit(1)
											->execute($pid);

			if ($objParentPage->numRows < 1)
			{
				break;
			}

			$pid = $objParentPage->pid;
			$type = $objParentPage->type;

			// Parent title
			if (!strlen($ptitle))
			{
				$palias = $objParentPage->alias;
				$pname = $objParentPage->title;
				$ptitle = strlen($objParentPage->pageTitle) ? $objParentPage->pageTitle : $objParentPage->title;
			}

			// Page title
			if ($type != 'root')
			{
				$alias = $objParentPage->alias;
				$name = $objParentPage->title;
				$title = strlen($objParentPage->pageTitle) ? $objParentPage->pageTitle : $objParentPage->title;

				$trail[] = $objParentPage->pid;
			}

			if ($objPage->cache === false && $objParentPage->includeCache)
			{
				$objPage->cache = $objParentPage->cache;
			}

			if (!$objPage->layout && $objParentPage->includeLayout)
			{
				$objPage->layout = $objParentPage->layout;
			}

			if (!$objPage->protected && $objParentPage->protected)
			{
				$objPage->protected = true;
				$objPage->groups = deserialize($objParentPage->groups);
			}
		}
		while ($pid > 0 && $type != 'root');

		// Set titles
		$objPage->mainAlias = $alias;
		$objPage->mainTitle = $name;
		$objPage->mainPageTitle = $title;
		$objPage->parentAlias = $palias;
		$objPage->parentTitle = $pname;
		$objPage->parentPageTitle = $ptitle;

		// Set root ID and title
		if ($objParentPage->numRows && ($objParentPage->type == 'root' || $objParentPage->pid > 0))
		{
			$objPage->rootId = $objParentPage->id;
			$objPage->rootTitle = strlen($objParentPage->pageTitle) ? $objParentPage->pageTitle : $objParentPage->title;
			$objPage->domain = $objParentPage->dns;

			$GLOBALS['TL_ADMIN_EMAIL'] = strlen($objParentPage->adminEmail) ? $objParentPage->adminEmail : $GLOBALS['TL_CONFIG']['adminEmail'];
		}
		else
		{
			$objPage->rootId = 0;
			$objPage->rootTitle = $GLOBALS['TL_CONFIG']['websiteTitle'];
			$objPage->domain = '';

			$GLOBALS['TL_ADMIN_EMAIL'] = $GLOBALS['TL_CONFIG']['adminEmail'];
		}

		$objPage->trail = array_reverse($trail);

		// Do not cache protected pages
		if ($objPage->protected)
		{
			$objPage->cache = 0;
		}

		return $objPage;
	}


	/**
	 * Return all page sections as array
	 * @return array
	 */
	protected function getPageSections()
	{
		$arrSections = array('header', 'left', 'main', 'right', 'footer');
		return array_merge($arrSections, trimsplit(',', $GLOBALS['TL_CONFIG']['customSections']));
	}


	/**
	 * Return all languages as array
	 * @return array
	 */
	protected function getLanguages()
	{
		$return = array();
		$languages = array();

		$this->loadLanguageFile('languages');
		include(TL_ROOT . '/system/config/languages.php');

		foreach ($languages as $strKey=>$strName)
		{
			$return[$strKey] = strlen($GLOBALS['TL_LANG']['LNG'][$strKey]) ? $GLOBALS['TL_LANG']['LNG'][$strKey] : $strName;
		}

		$aux = array();

		foreach (array_values($return) as $lang)
		{
			$aux[] = $lang;
		}

		array_multisort($aux, SORT_ASC, $return);
		return $return;
	}


	/**
	 * Return an array of supported back end languages
	 * @return array
	 */
	protected function getBackendLanguages()
	{
		$arrReturn = array();
		$arrLanguages = $this->getLanguages();
		$arrBackendLanguages = scan(TL_ROOT . '/system/modules/backend/languages');

		foreach ($arrBackendLanguages as $language)
		{
			if (substr($language, 0, 1) == '.')
			{
				continue;
			}

			$arrReturn[$language] = $arrLanguages[$language];
		}

		return $arrReturn;
	}


	/**
	 * Return all counties as array
	 * @return array
	 */
	protected function getCountries()
	{
		$return = array();
		$countries = array();

		$this->loadLanguageFile('countries');
		include(TL_ROOT . '/system/config/countries.php');

		foreach ($countries as $strKey=>$strName)
		{
			$return[$strKey] = strlen($GLOBALS['TL_LANG']['CNT'][$strKey]) ? $GLOBALS['TL_LANG']['CNT'][$strKey] : $strName;
		}

		$aux = array();

		foreach (array_values($return) as $cntr)
		{
			$aux[] = $cntr;
		}

		array_multisort($aux, SORT_ASC, $return);
		return $return;
	}


	/**
	 * Return all timezones as array
	 * @return array
	 */
	protected function getTimeZones()
	{
		$arrReturn = array();
		$timezones = array();

		require(TL_ROOT . '/system/config/timezones.php');

		foreach ($timezones as $strGroup=>$arrTimezones)
		{
			foreach ($arrTimezones as $strTimezone)
			{
				$arrReturn[$strGroup][] = $strTimezone;
			}
		}

		return $arrReturn;
	}


	/**
	 * Resize or crop an image
	 * @param string
	 * @param integer
	 * @param integer
	 * @param string
	 * @return boolean
	 */
	protected function resizeImage($image, $width, $height)
	{
		return $this->getImage($image, $width, $height, $image) ? true : false;
	}


	/**
	 * Resize an image
	 * @param string
	 * @param integer
	 * @param integer
	 * @param string
	 * @return string
	 */
	protected function getImage($image, $width, $height, $target=null)
	{
		if (!strlen($image))
		{
			return null;
		}

		$image = urldecode($image);

		// Check whether file exists
		if (!file_exists(TL_ROOT . '/' . $image))
		{
			$this->log('Image "' . $image . '" could not be found', 'Controller getImage()', TL_ERROR);
			return null;
		}

		$objFile = new File($image);
		$arrAllowedTypes = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['validImageTypes']));

		// Check file type
		if (!in_array($objFile->extension, $arrAllowedTypes))
		{
			$this->log('Image type "' . $objFile->extension . '" was not allowed to be processed', 'Controller getImage()', TL_ERROR);
			return null;
		}

		$strCacheName = 'system/html/' . md5('w' . $width . 'h' . $height . $image) . '.' . $objFile->extension;

		// Resize original image
		if ($target)
		{
			$strCacheName = $target;
		}

		// Return the path of the new image if it exists already
		elseif (file_exists(TL_ROOT . '/' . $strCacheName))
		{
			return $strCacheName;
		}

		// Return the path to the original image if GDlib cannot handle it
		if (!extension_loaded('gd') || !$objFile->isGdImage || (!$width && !$height) || $width > 1200 || $height > 1200)
		{
			return $image;
		}

		$intPositionX = 0;
		$intPositionY = 0;
		$intWidth = $width;
		$intHeight = $height;

		// Resize width and height and crop the image if necessary
		if ($intWidth && $intHeight)
		{
			if (($intWidth * $objFile->height) != ($intHeight * $objFile->width))
			{
				$intWidth = ceil($objFile->width * $height / $objFile->height);
				$intPositionX = -intval(($intWidth - $width) / 2);

				if ($intWidth < $width)
				{
					$intWidth = $width;
					$intHeight = ceil($objFile->height * $width / $objFile->width);
					$intPositionX = 0;
					$intPositionY = -intval(($intHeight - $height) / 2);
				}
			}

			$strNewImage = imagecreatetruecolor($width, $height);
		}

		// Calculate height if only width is given
		elseif ($intWidth)
		{
			$intHeight = ceil($objFile->height * $width / $objFile->width);
			$strNewImage = imagecreatetruecolor($intWidth, $intHeight);
		}

		// Calculate width if only height is given
		elseif ($intHeight)
		{
			$intWidth = ceil($objFile->width * $height / $objFile->height);
			$strNewImage = imagecreatetruecolor($intWidth, $intHeight);
		}

		$arrGdinfo = gd_info();
		$strGdVersion = ereg_replace('[[:alpha:][:space:]()]+', '', $arrGdinfo['GD Version']);

		switch ($objFile->extension)
		{
			case 'gif':
				if ($arrGdinfo['GIF Read Support'])
				{
					$strSourceImage = imagecreatefromgif(TL_ROOT . '/' . $image);

					// Handle transparency
					$strBuffer = substr($objFile->getContent(), 0, 13);
					$intColorFlag = ord(substr($strBuffer, 10, 1)) >> 7;
					$intBackground = ord(substr($strBuffer, 11));

					if ($intColorFlag)
					{
						$strBuffer = substr($objFile->getContent(), 13, (($intBackground + 1) * 3));
						imagecolortransparent($strSourceImage, imagecolorallocate($strSourceImage, ord(substr($strBuffer, $intBackground * 3, 1)), ord(substr($strBuffer, $intBackground * 3 + 1, 1)), ord(substr($strBuffer, $intBackground * 3 + 2, 1))));
					}
				}
				break;

			case 'jpg':
			case 'jpeg':
				if ($arrGdinfo['JPG Support'])
				{
					$strSourceImage = imagecreatefromjpeg(TL_ROOT . '/' . $image);
				}
				break;

			case 'png':
				if ($arrGdinfo['PNG Support'])
				{
					$strSourceImage = imagecreatefrompng(TL_ROOT . '/' . $image);

					// Handle transparency (GDlib > 2.0.1 required)
					if (version_compare($strGdVersion, '2.0.1', '>='))
					{
						imageantialias($strNewImage, true);
						imagealphablending($strNewImage, false);
						imagesavealpha($strNewImage, true);
						imagefilledrectangle($strNewImage, $intPositionX, $intPositionY, $intWidth, $intHeight, imagecolorallocatealpha($strNewImage, 255, 255, 255, 127));
					}
				}
				break;
		}

		imagecopyresampled($strNewImage, $strSourceImage, $intPositionX, $intPositionY, 0, 0, $intWidth, $intHeight, $objFile->width, $objFile->height);

		// Fallback to PNG if GIF ist not supported
		if ($objFile->extension == 'gif' && !$arrGdinfo['GIF Create Support'])
		{
			$objFile->extension = 'png';
		}

		// Create new image
		switch ($objFile->extension)
		{
			case 'gif':
				imagegif($strNewImage, TL_ROOT . '/' . $strCacheName);
				break;

			case 'jpg':
			case 'jpeg':
				imagejpeg($strNewImage, TL_ROOT . '/' . $strCacheName, (!$GLOBALS['TL_CONFIG']['jpgQuality'] ? 80 : $GLOBALS['TL_CONFIG']['jpgQuality']));
				break;

			case 'png':
				imagepng($strNewImage, TL_ROOT . '/' . $strCacheName);
				break;
		}

		// Destroy temporary images
		imagedestroy($strSourceImage);
		imagedestroy($strNewImage);

		// Return path to new image
		return $strCacheName;
	}


	/**
	 * Return the current date format to be used with the date picker
	 * @return string
	 */
	protected function getDatePickerString()
	{
		if (preg_match('/[AaBbCcEefGgHhIiJKkLNOoPpQqRrsTtUuVvWwXxZz]+/', $GLOBALS['TL_CONFIG']['dateFormat']))
		{
			return '';
		}

		return "new Calendar({ %s: '" . $GLOBALS['TL_CONFIG']['dateFormat'] . "' }, { navigation: 2, days: ['" . implode("','", $GLOBALS['TL_LANG']['DAYS']) . "'], months: ['" . implode("','", $GLOBALS['TL_LANG']['MONTHS']) . "'], offset: ". intval($GLOBALS['TL_LANG']['MSC']['weekOffset']) . ", titleFormat: '" . $GLOBALS['TL_LANG']['MSC']['titleFormat'] . "' });";
	}


	/**
	 * Print an article as PDF and stream it to the browser
	 * @param object
	 */
	protected function printArticleAsPdf(Database_Result $objArticle)
	{
		$objArticle->headline = $objArticle->title;
		$objArticle->printable = false;

		// Generate article
		$objArticle = new ModuleArticle($objArticle);
		$strArticle = $this->replaceInsertTags($objArticle->generate());
		$strArticle = html_entity_decode($strArticle, ENT_QUOTES, $GLOBALS['TL_CONFIG']['characterSet']);

		// Replace relative image paths
		$arrImages = array();
		preg_match_all('/<img[^>]+>/i', $strArticle, $arrImages);

		foreach($arrImages[0] as $strImage)
		{
			if (preg_match('/image\.php/i', $strImage))
			{
				$strNewImage = str_replace('image.php?src=', '', $strImage);
				$strNewImage = preg_replace('/&(amp;)?width=([0-9]*)/i', '" width="$2"', $strNewImage);
				$strNewImage = preg_replace('/&(amp;)?height=([0-9]*)/i', ' height="$2', $strNewImage);

				$strArticle = str_replace($strImage, $strNewImage, $strArticle);
			}
		}

		// Replace relative links
		$arrLinks = array();
		preg_match_all('/<a[^>]+>/i', $strArticle, $arrLinks);

		foreach($arrLinks[0] as $strLink)
		{
			if (!preg_match('@http://|https://|mailto:|ftp://@i', $strLink))
			{
				$strNewLink = preg_replace('/href="([^"]+)"/i', 'href="' . $this->Environment->base .'$1"', $strLink);
				$strArticle = str_replace($strLink, $strNewLink, $strArticle);
			}
		}

		// Remove form elements
		$strArticle = preg_replace('/<form.*<\/form>/Us', '', $strArticle);
		$strArticle = preg_replace('/\?pdf=[0-9]*/i', '', $strArticle);

		// Use DOMPDF plugin
		if ($GLOBALS['TL_CONFIG']['useDompdf'])
		{
			// Add head section
			$strHtml = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
			$strHtml .= '<html xmlns="http://www.w3.org/1999/xhtml">' . "\n";
			$strHtml .= '<head>' . "\n";
			$strHtml .= '<title>' . $objArticle->title . '</title>' . "\n";
			$strHtml .= '<meta http-equiv="Content-Type" content="text/html; charset=' . $GLOBALS['TL_CONFIG']['characterSet'] . '" />' . "\n";
			$strHtml .= '<base href="' . $this->Environment->base . '" />' . "\n";

			$objStylesheet = $this->Database->execute("SELECT * FROM tl_style_sheet");

			// Add stylesheets
			while ($objStylesheet->next())
			{
				$arrMedia = deserialize($objStylesheet->media, true);

				if (in_array('print', $arrMedia) || in_array('all', $arrMedia))
				{
					$strHtml .= '<link rel="stylesheet" type="text/css" href="' . $objStylesheet->name . '.css" />' . "\n";
				}
			}

			// Convert the Euro symbol
			$strArticle = str_replace('€', '&#0128;', $strArticle);

			// Make sure there is no background
			$strHtml .= '<style type="text/css">' . "\n";
			$strHtml .= 'body { background:none; background-color:#ffffff; }' . "\n";
			$strHtml .= '</style>' . "\n";
			$strHtml .= '</head>' . "\n";
			$strHtml .= '<body>' . "\n";
			$strHtml .= $strArticle . "\n";
			$strHtml .= '</body>' . "\n";
			$strHtml .= '</html>';

			// Generate DOMPDF object
			require_once(TL_ROOT . '/plugins/dompdf/dompdf_config.inc.php');
			$dompdf = new DOMPDF();

			$dompdf->set_paper('a4');
			$dompdf->set_base_path(TL_ROOT);
			$dompdf->load_html($strHtml);
			$dompdf->render();

			$dompdf->stream(standardize(ampersand($objArticle->title, false)) . '.pdf');
		}

		// Use TCPDF plugin
		else
		{
			$arrChunks = array();
			preg_match_all('/<pre.*<\/pre>/Us', $strArticle, $arrChunks);

			// Replace linebreaks within PRE tags
			foreach ($arrChunks[0] as $strChunk)
			{
				$strArticle = str_replace($strChunk, str_replace("\n", '<br />', $strChunk), $strArticle);
			}

			// Remove linebreaks and tabs
			$strArticle = str_replace(array("\n", "\t"), '', $strArticle);
			$strArticle = preg_replace('/<span style="text-decoration: ?underline;?">(.*)<\/span>/Us', '<u>$1</u>', $strArticle);

			// TCPDF configuration
			$l['a_meta_dir'] = 'ltr';
			$l['a_meta_charset'] = $GLOBALS['TL_CONFIG']['characterSet'];
			$l['a_meta_language'] = $GLOBALS['TL_LANGUAGE'];
			$l['w_page'] = "page";

			// Include library
			require_once(TL_ROOT . '/system/config/tcpdf.php');
			require_once(TL_ROOT . '/plugins/tcpdf/tcpdf.php'); 

			// Create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); 

			// Set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor(PDF_AUTHOR);
			$pdf->SetTitle($objArticle->title);
			$pdf->SetSubject($objArticle->title);
			$pdf->SetKeywords($objArticle->keywords);

			// Remove default header/footer
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);

			// Set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

			// Set auto page breaks
			$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

			// Set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

			// Set some language-dependent strings
			$pdf->setLanguageArray($l); 

			// Initialize document and add a page
			$pdf->AliasNbPages();
			$pdf->AddPage();

			// Set font
			$pdf->SetFont(PDF_FONT_NAME_MAIN, "", PDF_FONT_SIZE_MAIN);

			// Write the HTML content
			$pdf->writeHTML($strArticle);

			// Close and output PDF document
			$pdf->lastPage();
			$pdf->Output(standardize(ampersand($objArticle->title, false)) . '.pdf', 'D');
		}

		// Stop script execution
		ob_end_clean();
		exit;
	}


	/**
	 * Replace insert tags with their values
	 * @param string
	 * @return string
	 */
	protected function replaceInsertTags($strBuffer, $blnCache=false)
	{
		if ($GLOBALS['TL_CONFIG']['disableInsertTags'])
		{
			return $strBuffer;
		}

		$tags = preg_split("/{{([^}]+)}}/", $strBuffer, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);

		$strBuffer = '';
		$arrCache = array();

		for($_rit=0; $_rit<count($tags); $_rit=$_rit+2)
		{
			$strBuffer .= $tags[$_rit];
			$strTag = $tags[$_rit+1];

			// Skip empty tags
			if (!strlen($strTag))
			{
				continue;
			}

			// Load value from cache array
			if (array_key_exists($strTag, $arrCache))
			{
				$strBuffer .= $arrCache[$strTag];
				continue;
			}

			$elements = explode('::', $strTag);

			// Skip certain elements if the output will be cached
			if ($blnCache)
			{
				if ($elements[0] == 'date' || $elements[0] == 'file' || $elements[1] == 'back' || $elements[1] == 'referer')
				{
					$strBuffer .= '{{' . $strTag . '}}';
					continue;
				}
			}

			$arrCache[$strTag] = '';

			// Replace tag
			switch (strtolower($elements[0]))
			{
				// Date
				case 'date':
					$arrCache[$strTag] = date((strlen($elements[1]) ? $elements[1] : $GLOBALS['TL_CONFIG']['dateFormat']));
					break;

				// Accessibility tags
				case 'lang':
					$arrCache[$strTag] = strlen($elements[1]) ? '<span lang="' . $elements[1] . '" xml:lang="' . $elements[1] . '">' : '</span>';
					break;

				// E-mail addresses
				case 'email':
					if (strlen($elements[1]))
					{
						$this->import('String');

						$strEmail = $this->String->encodeEmail($elements[1]);
						$arrCache[$strTag] = '<a href="mailto:' .  $strEmail . '">' .  $strEmail . '</a>';
					}
					break;

				// Front end user
				case 'user':
					if (FE_USER_LOGGED_IN)
					{
						$this->import('FrontendUser', 'User');
						$arrCache[$strTag] = $this->User->$elements[1];
					}
					break;

				// Link
				case 'link':
				case 'link_open':
				case 'link_url':
				case 'link_title':
					// Back link
					if ($elements[1] == 'back')
					{
						$strUrl = 'javascript:history.go(-1)';
						$strTitle = $GLOBALS['TL_LANG']['MSC']['goBack'];

						// No language files if the page is cached
						if (!strlen($strTitle))
						{
							$strTitle = 'Go back';
						}
					}

					// Regular link
					else
					{
						// User login page
						if ($elements[1] == 'login')
						{
							if (!FE_USER_LOGGED_IN)
							{
								break;
							}

							$this->import('FrontendUser', 'User');
							$elements[1] = $this->User->loginPage;
						}

						$this->import('Database');

						// Get target page
						$objNextPage = $this->Database->prepare("SELECT id, alias, title, pageTitle FROM tl_page WHERE id=? OR alias=?")
													  ->limit(1)
													  ->execute((is_numeric($elements[1]) ? $elements[1] : 0), $elements[1]);

						if ($objNextPage->numRows < 1)
						{
							break;
						}

						else
						{
							$strUrl = $this->generateFrontendUrl($objNextPage->row());
							$strTitle = strlen($objNextPage->pageTitle) ? $objNextPage->pageTitle : $objNextPage->title;
						}
					}

					// Replace tag
					switch (strtolower($elements[0]))
					{
						case 'link':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">%s</a>', $strUrl, specialchars($strTitle), $strTitle);
							break;

						case 'link_open':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">', $strUrl, specialchars($strTitle));
							break;

						case 'link_url':
							$arrCache[$strTag] = $strUrl;
							break;

						case 'link_title':
							$arrCache[$strTag] = specialchars($strTitle);
							break;
					}
					break;

				// Insert article
				case 'insert_article':
					$arrCache[$strTag] = $this->replaceInsertTags(ltrim($this->getArticle($elements[1], false, true)));
					break;

				// Insert content element
				case 'insert_content':
					$arrCache[$strTag] = $this->replaceInsertTags($this->getContentElement($elements[1]));
					break;

				// Insert module
				case 'insert_module':
					$arrCache[$strTag] = $this->replaceInsertTags($this->getFrontendModule($elements[1]));
					break;

				// Article
				case 'article':
					$this->import('Database');

					$objArticle = $this->Database->prepare("SELECT a.id AS aId, a.alias AS aAlias, a.title AS title, p.id AS id, p.alias AS alias FROM tl_article a, tl_page p WHERE a.pid=p.id AND (a.id=? OR a.alias=?)")
												 ->limit(1)
												 ->execute($elements[1], $elements[1]);

					if ($objArticle->numRows)
					{
						$arrCache[$strTag] = '<a href="' . $this->generateFrontendUrl($objArticle->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objArticle->aAlias)) ? $objArticle->aAlias : $objArticle->aId)) . '" title="' . specialchars($objArticle->title) . '">' . $objArticle->title . '</a>';
					}
					break;

				// FAQ
				case 'faq':
					$this->import('Database');

					$objFaq = $this->Database->prepare("SELECT f.id AS fId, f.alias AS fAlias, f.question AS question, p.id AS id, p.alias AS alias FROM tl_faq f, tl_faq_category c, tl_page p WHERE f.pid=c.id AND c.jumpTo=p.id AND (f.id=? OR f.alias=?)")
											 ->limit(1)
											 ->execute($elements[1], $elements[1]);

					if ($objFaq->numRows)
					{
						$arrCache[$strTag] = '<a href="' . $this->generateFrontendUrl($objFaq->row(), '/items/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objFaq->fAlias)) ? $objFaq->fAlias : $objFaq->aId)) . '" title="' . specialchars($objFaq->question) . '">' . $objFaq->question . '</a>';
					}
					break;

				// News
				case 'news':
					$this->import('Database');

					$objNews = $this->Database->prepare("SELECT n.id AS nId, n.alias AS nAlias, n.headline AS headline, p.id AS id, p.alias AS alias FROM tl_news n, tl_news_archive a, tl_page p WHERE n.pid=a.id AND a.jumpTo=p.id AND (n.id=? OR n.alias=?)")
											  ->limit(1)
											  ->execute($elements[1], $elements[1]);

					if ($objNews->numRows)
					{
						$arrCache[$strTag] = '<a href="' . $this->generateFrontendUrl($objNews->row(), '/items/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objNews->nAlias)) ? $objNews->nAlias : $objNews->nId)) . '" title="' . specialchars($objNews->headline) . '">' . $objNews->headline . '</a>';
					}
					break;

				// Events
				case 'event':
					$this->import('Database');

					$objEvent = $this->Database->prepare("SELECT e.id AS eId, e.alias AS eAlias, e.title AS title, p.id AS id, p.alias AS alias FROM tl_calendar_events e, tl_calendar c, tl_page p WHERE e.pid=c.id AND c.jumpTo=p.id AND (e.id=? OR e.alias=?)")
											   ->limit(1)
											   ->execute($elements[1], $elements[1]);

					if ($objEvent->numRows)
					{
						$arrCache[$strTag] = '<a href="' . $this->generateFrontendUrl($objEvent->row(), '/events/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objEvent->eAlias)) ? $objEvent->eAlias : $objEvent->eId)) . '" title="' . specialchars($objEvent->title) . '">' . $objEvent->title . '</a>';
					}
					break;

				// Article teaser
				case 'article_teaser':
					$this->import('Database');

					$objTeaser = $this->Database->prepare("SELECT teaser FROM tl_article WHERE id=?")
												->limit(1)
												->execute($elements[1]);

					if ($objTeaser->numRows)
					{
						$arrCache[$strTag] = $objTeaser->teaser;
					}
					break;

				// News teaser
				case 'news_teaser':
					$this->import('Database');

					$objTeaser = $this->Database->prepare("SELECT teaser FROM tl_news WHERE id=?")
												->limit(1)
												->execute($elements[1]);

					if ($objTeaser->numRows)
					{
						$arrCache[$strTag] = $objTeaser->teaser;
					}
					break;

				// Event teaser
				case 'event_teaser':
					$this->import('Database');

					$objTeaser = $this->Database->prepare("SELECT teaser FROM tl_calendar_events WHERE id=?")
												->limit(1)
												->execute($elements[1]);

					if ($objTeaser->numRows)
					{
						$arrCache[$strTag] = $objTeaser->teaser;
					}
					break;

				// Last update
				case 'last_update':
					$this->import('Database');
					$objUpdate = $this->Database->execute("SELECT MAX(tstamp) AS tc, (SELECT MAX(tstamp) FROM tl_news) AS tn, (SELECT MAX(tstamp) FROM tl_calendar_events) AS te FROM tl_content");

					if ($objUpdate->numRows)
					{
						$arrCache[$strTag] = date((strlen($elements[1]) ? $elements[1] : $GLOBALS['TL_CONFIG']['datimFormat']), max($objUpdate->tc, $objUpdate->tn, $objUpdate->te));
					}
					break;

				// Version
				case 'version':
					$arrCache[$strTag] = VERSION . '.' . BUILD;
					break;

				// Environment
				case 'env':
					global $objPage;

					switch ($elements[1])
					{
						case 'page_alias':
							$arrCache[$strTag] = $objPage->alias;
							break;

						case 'page_name':
							$arrCache[$strTag] = $objPage->title;
							break;

						case 'page_title':
							$arrCache[$strTag] = strlen($objPage->pageTitle) ? $objPage->pageTitle : $objPage->title;
							break;

						case 'parent_alias':
							$arrCache[$strTag] = $objPage->parentAlias;
							break;

						case 'parent_name':
							$arrCache[$strTag] = $objPage->parentTitle;
							break;

						case 'parent_title':
							$arrCache[$strTag] = $objPage->parentPageTitle;
							break;

						case 'main_alias':
							$arrCache[$strTag] = $objPage->mainAlias;
							break;

						case 'main_name':
							$arrCache[$strTag] = $objPage->mainTitle;
							break;

						case 'main_title':
							$arrCache[$strTag] = $objPage->mainPageTitle;
							break;

						case 'website_title':
							$arrCache[$strTag] = strlen($objPage->rootTitle) ? $objPage->rootTitle : $GLOBALS['TL_CONFIG']['websiteTitle'];
							break;

						case 'url':
							$arrCache[$strTag] = $this->Environment->url;
							break;

						case 'path':
							$arrCache[$strTag] = $this->Environment->base;
							break;

						case 'request':
							$arrCache[$strTag] = $this->Environment->request;
							break;

						case 'ip':
							$arrCache[$strTag] = $this->Environment->ip;
							break;

						case 'referer':
							$arrCache[$strTag] = $this->getReferer(ENCODE_AMPERSANDS);
							break;
					}
					break;

				// Files from the templates directory
				case 'file':
					$arrGet = $_GET;
					$strFile = $elements[1];

					// Take arguments and add them to the $_GET array
					if (strpos($elements[1], '?') !== false)
					{
						$this->import('String');

						$arrChunks = explode('?', urldecode($elements[1]));
						$arrParams = explode('&', $this->String->decodeEntities($arrChunks[1]));

						foreach ($arrParams as $strParam)
						{
							$arrParam = explode('=', $strParam);
							$_GET[$arrParam[0]] = $arrParam[1];
						}

						$strFile = $arrChunks[0];
					}

					// Include file
					if (file_exists(TL_ROOT . '/templates/' . $strFile))
					{
						ob_start();
						include(TL_ROOT . '/templates/' . $strFile);
						$arrCache[$strTag] = ob_get_contents();
						ob_end_clean();
					}

					$_GET = $arrGet;
					break;

				// HOOK: pass unknown tags to callback functions
				default:
					if (array_key_exists('replaceInsertTags', $GLOBALS['TL_HOOKS']) && is_array($GLOBALS['TL_HOOKS']['replaceInsertTags']))
					{
						foreach ($GLOBALS['TL_HOOKS']['replaceInsertTags'] as $callback)
						{
							$this->import($callback[0]);
							$arrCache[$strTag] = $this->$callback[0]->$callback[1]($strTag);
						}
					}
					break;
			}

			$strBuffer .= $arrCache[$strTag];
		}

		return $strBuffer;
	}


	/**
	 * Parse simple tokens that can be used to personalize newsletters
	 * @param string
	 * @param array
	 * @return string
	 * @throws Exception
	 */
	protected function parseSimpleTokens($strBuffer, $arrData)
	{
		$strReturn = '';

		// Remove any unwanted tags (especially PHP tags)
		$strBuffer = strip_tags($strBuffer, $GLOBALS['TL_CONFIG']['allowedTags']);
		$arrTags = preg_split("/{([^}]+)}/", $strBuffer, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);

		// Replace tags
		foreach ($arrTags as $strTag)
		{
			if (strncmp($strTag, 'if', 2) === 0)
			{
				$strReturn .= preg_replace('/if ([A-Za-z0-9_]+)([=!<>]+)([^;$\(\)\[\] ]+).*/i', '<?php if ($arrData[\'$1\'] $2 $3): ?>', $strTag);
			}
			elseif (strncmp($strTag, 'elseif', 6) === 0)
			{
				$strReturn .= preg_replace('/elseif ([A-Za-z0-9_]+)([=!<>]+)([^;$\(\)\[\] ]+).*/i', '<?php elseif ($arrData[\'$1\'] $2 $3): ?>', $strTag);
			}
			elseif (strncmp($strTag, 'else', 4) === 0)
			{
				$strReturn .= '<?php else: ?>';
			}
			elseif (strncmp($strTag, 'endif', 5) === 0)
			{
				$strReturn .= '<?php endif; ?>';
			}
			else
			{
				$strReturn .= $strTag;
			}
		}

		// Replace tokens
		$strReturn = preg_replace('/##([A-Za-z0-9_]+)##/i', '<?php echo $arrData[\'$1\']; ?>', $strReturn);

		// Eval the code
		ob_start();
		$blnEval = eval("?>" . $strReturn);
		$strReturn = ob_get_contents();
		ob_end_clean();

		// Throw an exception if there is an eval() error
		if ($blnEval === false)
		{
			throw new Exception("Error parsing simple tokens ($strReturn)");
		}

		// Return the evaled code
		return $strReturn;
	}


	/**
	 * Generate an image tag and return it as HTML string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	protected function generateImage($src, $alt='', $attributes='')
	{
		if (strpos($src, '/') === false)
		{
			$src = sprintf('system/themes/%s/images/%s', $this->getTheme(), $src);
		}

		if (!file_exists(TL_ROOT.'/'.$src))
		{
			return '';
		}

		$size = getimagesize(TL_ROOT.'/'.$src);

		return '<img src="'.$src.'" '.$size[3].' alt="'.specialchars($alt).'"'.(strlen($attributes) ? ' '.$attributes : '').' />';
	}


	/**
	 * Generate an URL from a tl_page record depending on the current rewriteURL setting and return it
	 * @param array
	 * @param string
	 * @return string
	 */
	protected function generateFrontendUrl($arrRow, $strParams='')
	{
		$strUrl = ($GLOBALS['TL_CONFIG']['rewriteURL'] ? '' : 'index.php/') . (strlen($arrRow['alias']) ? $arrRow['alias'] : $arrRow['id']) . $strParams . $GLOBALS['TL_CONFIG']['urlSuffix'];

		if ($GLOBALS['TL_CONFIG']['disableAlias'])
		{
			$strRequest = '';

			if ($strParams)
			{
				$arrChunks = explode('/', preg_replace('@^/@', '', $strParams));

				for ($i=0; $i<count($arrChunks); $i=($i+2))
				{
					$strRequest .= sprintf('&%s=%s', $arrChunks[$i], $arrChunks[($i+1)]);
				}
			}

			$strUrl = 'index.php?id=' . $arrRow['id'] . $strRequest;
		}

		// HOOK: add custom logic
		if (array_key_exists('generateFrontendUrl', $GLOBALS['TL_HOOKS']) && is_array($GLOBALS['TL_HOOKS']['generateFrontendUrl']))
		{
			foreach ($GLOBALS['TL_HOOKS']['generateFrontendUrl'] as $callback)
			{
				$this->import($callback[0]);
				$strUrl = $this->$callback[0]->$callback[1]($arrRow, $strParams, $strUrl);
			}
		}

		return $strUrl;
	}


	/**
	 * Send a file to the browser so the "save as" dialogue opens
	 * @param string
	 */
	protected function sendFileToBrowser($strFile)
	{
		// Make sure there are no attempts to hack the file system
		if (preg_match('@^\.+@i', $strFile) || preg_match('@\.+/@i', $strFile) || preg_match('@(://)+@i', $strFile))
		{
			header('HTTP/1.0 404 Not Found');
			die('Invalid file name');
		}

		// Limit downloads to the tl_files directory
		if (!preg_match('@^' . preg_quote($GLOBALS['TL_CONFIG']['uploadPath'], '@') . '@i', $strFile))
		{
			header('HTTP/1.0 404 Not Found');
			die('Invalid path');
		}

		// Check whether the file exists
		if (!file_exists(TL_ROOT . '/' . $strFile))
		{
			header('HTTP/1.0 404 Not Found');
			die('File not found');
		}

		$objFile = new File($strFile);
		$arrAllowedTypes = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

		if (!in_array($objFile->extension, $arrAllowedTypes))
		{
			header('HTTP/1.0 403 Forbidden');
			die(sprintf('File type "%s" is not allowed', $objFile->extension));
		}

		// Open the "save as …" dialogue
		header('Content-Type: ' . $objFile->mime);
		header('Content-Transfer-Encoding: binary');
		header('Content-Disposition: attachment; filename="'.$objFile->basename.'"');
		header('Content-Length: '.$objFile->filesize);
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); 
		header('Pragma: public');
		header('Expires: 0');

		$resFile = fopen(TL_ROOT . '/' . $strFile, 'rb');
		fpassthru($resFile);
		fclose($resFile);

		// HOOK: post download callback
		if (array_key_exists('postDownload', $GLOBALS['TL_HOOKS']) && is_array($GLOBALS['TL_HOOKS']['postDownload']))
		{
			foreach ($GLOBALS['TL_HOOKS']['postDownload'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($strFile);
			}
		}

		// Stop script
		exit;
	}


	/**
	 * Load a set of DCA files
	 */
	protected function loadDataContainer($strName)
	{
		foreach ($this->Config->getActiveModules() as $strModule)
		{
			$strFile = sprintf('%s/system/modules/%s/dca/%s.php', TL_ROOT, $strModule, $strName);

			if (file_exists($strFile))
			{
				include_once($strFile);
			}
		}

		include(TL_ROOT . '/system/config/dcaconfig.php');
	}


	/**
	 * Convert a back end DCA so it can be used with the widget class
	 * @param array
	 * @param string
	 * @param mixed
	 * @param string
	 * @param string
	 * @return array
	 */
	protected function prepareForWidget($arrData, $strName, $varValue=null, $strField='', $strTable='')
	{
		$arrNew = $arrData['eval'];

		$arrNew['id'] = $strName;
		$arrNew['name'] = $strName;
		$arrNew['strField'] = $strField;
		$arrNew['strTable'] = $strTable;
		$arrNew['label'] = (($label = is_array($arrData['label']) ? $arrData['label'][0] : $arrData['label']) != false) ? $label : $strField;
		$arrNew['description'] = $arrData['label'][1];
		$arrNew['type'] = $arrData['inputType'];

		$event = ($arrData['inputType'] == 'select') ? 'onchange' : 'onclick';

		$arrNew[$event] = $arrData['eval']['submitOnChange'] ? "Backend.autoSubmit('".$strTable."');" : '';
		$arrNew['allowHtml'] = ($arrData['eval']['allowHtml'] || strlen($arrData['eval']['rte']) || $arrData['eval']['preserveTags']) ? true : false;

		// Decode entities if HTML is allowed
		if ($arrNew['allowHtml'] || $arrData['inputType'] == 'fileTree')
		{
			$arrNew['decodeEntities'] = true;
		}

		// Add Ajax event
		if ($arrData['inputType'] == 'checkbox' && is_array($GLOBALS['TL_DCA'][$strTable]['subpalettes']) && in_array($strField, array_keys($GLOBALS['TL_DCA'][$strTable]['subpalettes'])) && $arrData['eval']['submitOnChange'])
		{
			$arrNew['onclick'] = "AjaxRequest.toggleSubpalette(this, 'sub_".$strName."', '".$strField."');";
		}

		// Options callback
		if (is_array($arrData['options_callback']))
		{
			if (!is_object($arrData['options_callback'][0]))
			{
				$this->import($arrData['options_callback'][0]);
			}

			$arrData['options'] = $this->$arrData['options_callback'][0]->$arrData['options_callback'][1]($this);
		}

		// Foreign key
		if (strlen($arrData['foreignKey']))
		{
			$arrKey = explode('.', $arrData['foreignKey']);
			$objOptions = $this->Database->execute("SELECT id, " . $arrKey[1] . " FROM " . $arrKey[0] . " ORDER BY " . $arrKey[1]);

			if ($objOptions->numRows)
			{
				$arrData['options'] = array();

				while($objOptions->next())
				{
					$arrData['options'][$objOptions->id] = $objOptions->$arrKey[1];
				}
			}
		}

		// Add default option to single checkbox 
		if ($arrData['inputType'] == 'checkbox' && !array_key_exists('options', $arrData) && !array_key_exists('options_callback', $arrData) && !array_key_exists('foreignKey', $arrData))
		{
			$arrNew['options'][] = array('value'=>1, 'label'=>$arrNew['label']);
		}

		// Add options
		if (is_array($arrData['options']))
		{
			$blnIsAssociative = array_is_assoc($arrData['options']);
			$blnUseReference = array_key_exists('reference', $arrData) ? true : false;

			if ($arrData['eval']['includeBlankOption'])
			{
				$arrNew['options'][] = array('value'=>'', 'label'=>'-');
			}

			foreach ($arrData['options'] as $k=>$v)
			{
				if (!is_array($v))
				{
					$arrNew['options'][] = array('value'=>($blnIsAssociative ? $k : $v), 'label'=>($blnUseReference ? ((($ref = (is_array($arrData['reference'][$v]) ? $arrData['reference'][$v][0] : $arrData['reference'][$v])) != false) ? $ref : $v) : $v));
					continue;
				}

				$key = $blnUseReference ? ((($ref = (is_array($arrData['reference'][$k]) ? $arrData['reference'][$k][0] : $arrData['reference'][$k])) != false) ? $ref : $k) : $k;
				$blnIsAssoc = array_is_assoc($v);

				foreach ($v as $kk=>$vv)
				{
					$arrNew['options'][$key][] = array('value'=>($blnIsAssoc ? $kk : $vv), 'label'=>($blnUseReference ? ((($ref = (is_array($arrData['reference'][$vv]) ? $arrData['reference'][$vv][0] : $arrData['reference'][$vv])) != false) ? $ref : $vv) : $vv));
				}
			}
		}

		$arrNew['value'] = deserialize($varValue);

		// Convert timestamps
		if (in_array($arrData['eval']['rgxp'], array('date', 'time', 'datim')) && strlen($varValue))
		{
			$objDate = new Date($varValue);
			$arrNew['value'] = $objDate->$arrData['eval']['rgxp'];
		}

		return $arrNew;
	}


	/**
	 * Create a new version of the current record
	 * @param mixed
	 * @throws Exception
	 */
	protected function createNewVersion($strTable, $intId)
	{
		if (!$GLOBALS['TL_DCA'][$strTable]['config']['enableVersioning'])
		{
			return;
		}

		// Delete old versions from the database
		$this->Database->prepare("DELETE FROM tl_version WHERE tstamp<?")
					   ->execute((time() - $GLOBALS['TL_CONFIG']['versionPeriod']));

		// Get new record
		$objRecord = $this->Database->prepare("SELECT * FROM " . $strTable . " WHERE id=?")
									->limit(1)
									->execute($intId);

		if ($objRecord->numRows < 1 || $objRecord->tstamp < 1)
		{
			return;
		}

		$intVersion = 1;
		$this->import('BackendUser', 'User');

		$objVersion = $this->Database->prepare("SELECT MAX(version) AS version FROM tl_version WHERE pid=? AND fromTable=?")
									 ->execute($intId, $strTable);

		if (!is_null($objVersion->version))
		{
			$intVersion = $objVersion->version + 1;
		}

		$this->Database->prepare("UPDATE tl_version SET active='' WHERE pid=? AND fromTable=?")
					   ->execute($intId, $strTable);

		$this->Database->prepare("INSERT INTO tl_version (pid, tstamp, version, fromTable, username, active, data) VALUES (?, ?, ?, ?, ?, 1, ?)")
					   ->execute($intId, time(), $intVersion, $strTable, $this->User->username, serialize($objRecord->row()));
	}


	/**
	 * Return the IDs of all child records of a particular record
	 * @param integer
	 * @param string
	 * @param string
	 * @return array
	 */
	protected function getChildRecords($intParentId, $strTable, $blnSorting=null)
	{
		$arrReturn = array();

		if (is_null($blnSorting))
		{
			$blnSorting = $this->Database->fieldExists('sorting', $strTable);
		}

		$objChilds = $this->Database->prepare("SELECT * FROM " . $strTable ." WHERE pid=? AND id!=?" . ($blnSorting ? ' ORDER BY sorting' : ''))
									->execute($intParentId, $intParentId);

		if ($objChilds->numRows > 0)
		{
			while ($objChilds->next())
			{
				$arrReturn[] = $objChilds->id;
				$arrReturn = array_merge($arrReturn, $this->getChildRecords($objChilds->id, $strTable, $blnSorting));
			}
		}

		return $arrReturn;
	}


	/**
	 * Return if a class file exists
	 * @param string
	 */
	protected function classFileExists($strClass)
	{
		foreach ($this->Config->getActiveModules() as $strModule)
		{
			$strFile = sprintf('%s/system/modules/%s/%s.php', TL_ROOT, $strModule, $strClass);

			if (file_exists($strFile))
			{
				return true;
			}
		}

		return false;
	}


	/**
	 * Take an array of paths and eliminate nested paths
	 * @param array
	 * @return array
	 */
	protected function eliminateNestedPaths($arrPaths)
	{
		if (!is_array($arrPaths) || count($arrPaths) < 1)
		{
			return array();
		}

		$nested = array();

		foreach ($arrPaths as $path)
		{
			$nested = array_merge($nested, preg_grep('/^' . preg_quote($path, '/') . '\/.+/', $arrPaths));
		}

		return array_values(array_diff($arrPaths, $nested));
	}


	/**
	 * Take an array of pages and eliminate nested pages
	 * @param array
	 * @return array
	 */
	protected function eliminateNestedPages($arrPages)
	{
		if (!is_array($arrPages) || count($arrPages) < 1)
		{
			return array();
		}

		$nested = array();
		$arrPages = array_intersect($this->getChildRecords(0, 'tl_page'), $arrPages);

		foreach ($arrPages as $page)
		{
			$nested = array_merge($nested, $this->getChildRecords($page, 'tl_page'));
		}

		return array_values(array_diff($arrPages, $nested));
	}


	/**
	 * Return a "selected" attribute if the current option is selected
	 * @param string
	 * @param mixed
	 */
	protected function optionSelected($strName, $strValue)
	{
		return (is_array($strValue) ? in_array($strName, $strValue) : $strName == $strValue) ? ' selected="selected"' : '';
	}


	/**
	 * Return a "checked" attribute if the current option is checked
	 * @param string
	 * @param mixed
	 */
	protected function optionChecked($strName, $strValue)
	{
		return (is_array($strValue) ? in_array($strName, $strValue) : $strName == $strValue) ? ' checked="checked"' : '';
	}


	/**
	 * Find a content element in the TL_CTE array and return its value
	 * @param string
	 * @return mixed
	 */
	protected function findContentElement($strName)
	{
		foreach ($GLOBALS['TL_CTE'] as $v)
		{
			foreach ($v as $kk=>$vv)
			{
				if ($kk == $strName)
				{
					return $vv;
				}
			}
		}

		return '';
	}


	/**
	 * Find a front end module in the FE_MOD array and return its value
	 * @param string
	 * @return mixed
	 */
	protected function findFrontendModule($strName)
	{
		foreach ($GLOBALS['FE_MOD'] as $v)
		{
			foreach ($v as $kk=>$vv)
			{
				if ($kk == $strName)
				{
					return $vv;
				}
			}
		}

		return '';
	}


	/**
	 * Remove old XML files from the root directory
	 */
	protected function removeOldFeeds()
	{
		$arrFeeds = array();
		$arrModules = $this->Config->getActiveModules();

		// XML sitemaps
		$objFeeds = $this->Database->execute("SELECT sitemapName FROM tl_page WHERE type='root' AND createSitemap=1");

		while ($objFeeds->next())
		{
			$arrFeeds[] = $objFeeds->sitemapName;
		}

		// Calendar module
		if (in_array('calendar', $arrModules))
		{
			$objFeeds = $this->Database->execute("SELECT id, alias FROM tl_calendar WHERE makeFeed=1");

			while ($objFeeds->next())
			{
				$arrFeeds[] = strlen($objFeeds->alias) ? $objFeeds->alias : 'calendar' . $objFeeds->id;
			}
		}

		// News module
		if (in_array('news', $arrModules))
		{
			$objFeeds = $this->Database->execute("SELECT id, alias FROM tl_news_archive WHERE makeFeed=1");

			while ($objFeeds->next())
			{
				$arrFeeds[] = strlen($objFeeds->alias) ? $objFeeds->alias : 'news' . $objFeeds->id;
			}
		}

		// HOOK: preserve third party feeds
		if (array_key_exists('removeOldFeeds', $GLOBALS['TL_HOOKS']) && is_array($GLOBALS['TL_HOOKS']['removeOldFeeds']))
		{
			foreach ($GLOBALS['TL_HOOKS']['removeOldFeeds'] as $callback)
			{
				$this->import($callback[0]);
				$arrFeeds = array_merge($arrFeeds, $this->$callback[0]->$callback[1]());
			}
		}

		// Delete old files
		foreach (scan(TL_ROOT) as $file)
		{
			if (is_dir(TL_ROOT . '/' . $file))
			{
				continue;
			}

			if (is_array($GLOBALS['TL_CONFIG']['rootFiles']) && in_array($file, $GLOBALS['TL_CONFIG']['rootFiles']))
			{
				continue;
			}

			$objFile = new File($file);

			if ($objFile->extension == 'xml' && !in_array($objFile->filename, $arrFeeds) && !preg_match('/^sitemap/i', $objFile->filename))
			{
				$objFile->delete();
			}

			$objFile->close();
		}
	}
}

?>