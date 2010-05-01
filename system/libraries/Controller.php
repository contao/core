<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class Controller
 *
 * Provide methods to manage controllers.
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
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
		$strTemplate = basename($strTemplate);
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

		natcasesort($arrTemplates);
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
			if ($this->Input->get('articles') && $objPage->type == 'regular')
			{
				list($strSection, $strArticle) = explode(':', $this->Input->get('articles'));

				if (is_null($strArticle))
				{
					$strArticle = $strSection;
					$strSection = 'main';
				}

				if ($strSection == $strColumn)
				{
					return $this->getArticle($strArticle);
				}
			}

			// HOOK: trigger article_raster_designer extension
			elseif (in_array('article_raster_designer', $this->Config->getActiveModules()))
			{
				return RasterDesigner::load($objPage->id, $strColumn);
			}

			$time = time();

			// Show all articles of the current column
			$objArticles = $this->Database->prepare("SELECT id FROM tl_article WHERE pid=? AND inColumn=?" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : "") . " ORDER BY sorting")
										  ->execute($objPage->id, $strColumn);

			if (($count = $objArticles->numRows) < 1)
			{
				return '';
			}

			$return = '';

			while ($objArticles->next())
			{
				$return .= $this->getArticle($objArticles->id, (($count > 1) ? true : false), false, $strColumn);
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
			$groups = deserialize($objModule->groups);
	
			if (!is_array($groups) || count($groups) < 1 || count(array_intersect($groups, $this->User->groups)) < 1)
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
		$objModule = new $strClass($objModule, $strColumn);


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
	 * @param string
	 * @return string
	 */
	protected function getArticle($varId, $blnMultiMode=false, $blnIsInsertTag=false, $strColumn='main')
	{
		if (!$varId)
		{
			return '';
		}

		global $objPage;
		$this->import('Database');

		// Get article
		$objArticle = $this->Database->prepare("SELECT *, author AS authorId, (SELECT name FROM tl_user WHERE id=author) AS author FROM tl_article WHERE (id=? OR alias=?)" . (!$blnIsInsertTag ? " AND pid=?" : ""))
									 ->limit(1)
									 ->execute((is_numeric($varId) ? $varId : 0), $varId, $objPage->id);

		if ($objArticle->numRows < 1)
		{
			// Do not index the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			// Send 404 header
			header('HTTP/1.1 404 Not Found');
			return '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['invalidPage'], $varId) . '</p>';
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

		$objArticle = new ModuleArticle($objArticle, $strColumn);
		return $objArticle->generate($blnIsInsertTag);
	}


	/**
	 * Generate a content element return it as HTML string
	 * @param integer
	 * @return string
	 */
	protected function getContentElement($intId)
	{
		if (!strlen($intId) || $intId < 1)
		{
			return '';
		}

		$this->import('Database');

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
		if ($objElement->protected && !BE_USER_LOGGED_IN)
		{
			if (!FE_USER_LOGGED_IN)
			{
				return '';
			}

			$this->import('FrontendUser', 'User');
			$groups = deserialize($objElement->groups);

			if (!is_array($groups) || count($groups) < 1 || count(array_intersect($groups, $this->User->groups)) < 1)
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
		if (!strlen($intId) || $intId < 1)
		{
			return null;
		}

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
			$objPage->rootLanguage = $objParentPage->language;

			// Set admin e-mail
			if ($objParentPage->adminEmail != '')
			{
				list($GLOBALS['TL_ADMIN_NAME'], $GLOBALS['TL_ADMIN_EMAIL']) = $this->splitFriendlyName($objParentPage->adminEmail);
			}
			else
			{
				list($GLOBALS['TL_ADMIN_NAME'], $GLOBALS['TL_ADMIN_EMAIL']) = $this->splitFriendlyName($GLOBALS['TL_CONFIG']['adminEmail']);
			}
		}
		else
		{
			$objPage->rootId = 0;
			$objPage->rootTitle = $GLOBALS['TL_CONFIG']['websiteTitle'];
			$objPage->domain = '';
			$objPage->rootLanguage = $objPage->language;

			list($GLOBALS['TL_ADMIN_NAME'], $GLOBALS['TL_ADMIN_EMAIL']) = $this->splitFriendlyName($GLOBALS['TL_CONFIG']['adminEmail']);
		}

		$objPage->trail = array_reverse($trail);

		// Overwrite the global date and time format
		if ($objParentPage->numRows && $objParentPage->type == 'root')
		{
			if (strlen($objParentPage->dateFormat))
			{
				$GLOBALS['TL_CONFIG']['dateFormat'] = $objParentPage->dateFormat;
			}
			if (strlen($objParentPage->timeFormat))
			{
				$GLOBALS['TL_CONFIG']['timeFormat'] = $objParentPage->timeFormat;
			}
			if (strlen($objParentPage->datimFormat))
			{
				$GLOBALS['TL_CONFIG']['datimFormat'] = $objParentPage->datimFormat;
			}
		}

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
	 * @param boolean
	 * @return array
	 */
	protected function getLanguages($blnBeOnly=false)
	{
		$return = array();
		$languages = array();
		$arrAux = array();

		$this->loadLanguageFile('languages');
		include(TL_ROOT . '/system/config/languages.php');

		foreach ($languages as $strKey=>$strName)
		{
			$arrAux[$strKey] = strlen($GLOBALS['TL_LANG']['LNG'][$strKey]) ? utf8_romanize($GLOBALS['TL_LANG']['LNG'][$strKey]) : $strName;
		}

		asort($arrAux);
		$arrBackendLanguages = scan(TL_ROOT . '/system/modules/backend/languages');

		foreach (array_keys($arrAux) as $strKey)
		{
			if ($blnBeOnly && !in_array($strKey, $arrBackendLanguages))
			{
				continue;
			}

			$return[$strKey] = strlen($GLOBALS['TL_LANG']['LNG'][$strKey]) ? $GLOBALS['TL_LANG']['LNG'][$strKey] : $languages[$strKey];
		}

		return $return;
	}


	/**
	 * Return an array of supported back end languages
	 * @return array
	 */
	protected function getBackendLanguages()
	{
		return $this->getLanguages(true);
	}


	/**
	 * Return all counties as array
	 * @return array
	 */
	protected function getCountries()
	{
		$return = array();
		$countries = array();
		$arrAux = array();

		$this->loadLanguageFile('countries');
		include(TL_ROOT . '/system/config/countries.php');

		foreach ($countries as $strKey=>$strName)
		{
			$arrAux[$strKey] = strlen($GLOBALS['TL_LANG']['CNT'][$strKey]) ? utf8_romanize($GLOBALS['TL_LANG']['CNT'][$strKey]) : $strName;
		}

		asort($arrAux);

		foreach (array_keys($arrAux) as $strKey)
		{
			$return[$strKey] = strlen($GLOBALS['TL_LANG']['CNT'][$strKey]) ? $GLOBALS['TL_LANG']['CNT'][$strKey] : $countries[$strKey];
		}

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
	protected function resizeImage($image, $width, $height, $mode='')
	{
		return $this->getImage($image, $width, $height, $mode, $image) ? true : false;
	}


	/**
	 * Resize an image
	 * @param string
	 * @param integer
	 * @param integer
	 * @param string
	 * @param string
	 * @return string
	 */
	protected function getImage($image, $width, $height, $mode='', $target=null)
	{
		if (!strlen($image))
		{
			return null;
		}

		$image = urldecode($image);

		// Check whether the file exists
		if (!file_exists(TL_ROOT . '/' . $image))
		{
			$this->log('Image "' . $image . '" could not be found', 'Controller getImage()', TL_ERROR);
			return null;
		}

		$objFile = new File($image);
		$arrAllowedTypes = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['validImageTypes']));

		// Check the file type
		if (!in_array($objFile->extension, $arrAllowedTypes))
		{
			$this->log('Image type "' . $objFile->extension . '" was not allowed to be processed', 'Controller getImage()', TL_ERROR);
			return null;
		}

		// No resizing required
		if ($objFile->width == $width && $objFile->height == $height)
		{
			return $image;
		}

		$strCacheName = 'system/html/' . $objFile->filename . '-' . substr(md5('-w' . $width . '-h' . $height . '-' . $image . '-' . $mode . '-' . $objFile->mtime), 0, 8) . '.' . $objFile->extension;

		// Return the path of the new image if it exists already
		if (file_exists(TL_ROOT . '/' . $strCacheName))
		{
			return $strCacheName;
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getImage']) && is_array($GLOBALS['TL_HOOKS']['getImage']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getImage'] as $callback)
			{
				$this->import($callback[0]);
				$return = $this->$callback[0]->$callback[1]($image, $width, $height, $mode, $strCacheName, $objFile);

				if (is_string($return))
				{
					return $return;
				}
			}
		}

		// Return the path to the original image if the GDlib cannot handle it
		if (!extension_loaded('gd') || !$objFile->isGdImage || $objFile->width > 3000 || $objFile->height > 3000 || (!$width && !$height) || $width > 1200 || $height > 1200)
		{
			return $image;
		}

		$intPositionX = 0;
		$intPositionY = 0;
		$intWidth = $width;
		$intHeight = $height;

		// Mode-specific changes
		if ($intWidth && $intHeight)
		{
			switch ($mode)
			{
				case 'proportional':
					if ($objFile->width >= $objFile->height)
					{
						unset($height, $intHeight);
					}
					else
					{
						unset($width, $intWidth);
					}
					break;

				case 'box':
					if (ceil($objFile->height * $width / $objFile->width) <= $intHeight)
					{
						unset($height, $intHeight);
					}
					else
					{
						unset($width, $intWidth);
					}
					break;
			}
		}

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

		// Calculate the height if only the width is given
		elseif ($intWidth)
		{
			$intHeight = ceil($objFile->height * $width / $objFile->width);
			$strNewImage = imagecreatetruecolor($intWidth, $intHeight);
		}

		// Calculate the width if only the height is given
		elseif ($intHeight)
		{
			$intWidth = ceil($objFile->width * $height / $objFile->height);
			$strNewImage = imagecreatetruecolor($intWidth, $intHeight);
		}

		$arrGdinfo = gd_info();
		$strGdVersion = preg_replace('/[^0-9\.]+/', '', $arrGdinfo['GD Version']);

		switch ($objFile->extension)
		{
			case 'gif':
				if ($arrGdinfo['GIF Read Support'])
				{
					$strSourceImage = imagecreatefromgif(TL_ROOT . '/' . $image);
					$intTranspIndex = imagecolortransparent($strSourceImage);

					// Handle transparency
					if ($intTranspIndex >= 0 && $intTranspIndex < imagecolorstotal($strSourceImage))
					{
						$arrColor = imagecolorsforindex($strSourceImage, $intTranspIndex);
						$intTranspIndex = imagecolorallocate($strNewImage, $arrColor['red'], $arrColor['green'], $arrColor['blue']);
						imagefill($strNewImage, 0, 0, $intTranspIndex);
						imagecolortransparent($strNewImage, $intTranspIndex);
					}
				}
				break;

			case 'jpg':
			case 'jpeg':
				if ($arrGdinfo['JPG Support'] || $arrGdinfo['JPEG Support'])
				{
					$strSourceImage = imagecreatefromjpeg(TL_ROOT . '/' . $image);
				}
				break;

			case 'png':
				if ($arrGdinfo['PNG Support'])
				{
					$strSourceImage = imagecreatefrompng(TL_ROOT . '/' . $image);

					// Handle transparency (GDlib >= 2.0 required)
					if (version_compare($strGdVersion, '2.0', '>='))
					{
						imagealphablending($strNewImage, false);
						$intTranspIndex = imagecolorallocatealpha($strNewImage, 0, 0, 0, 127);
						imagefill($strNewImage, 0, 0, $intTranspIndex);
						imagesavealpha($strNewImage, true);
					}
				}
				break;
		}

		// The new image could not be created
		if (!$strSourceImage)
		{
			$this->log('Image "' . $image . '" could not be processed', 'Controller getImage()', TL_ERROR);
			return null;
		}

		imagecopyresampled($strNewImage, $strSourceImage, $intPositionX, $intPositionY, 0, 0, $intWidth, $intHeight, $objFile->width, $objFile->height);

		// Fallback to PNG if GIF ist not supported
		if ($objFile->extension == 'gif' && !$arrGdinfo['GIF Create Support'])
		{
			$objFile->extension = 'png';
		}

		// Create the new image
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

		// Destroy the temporary images
		imagedestroy($strSourceImage);
		imagedestroy($strNewImage);

		// Resize the original image
		if ($target)
		{
			$this->import('Files');
			$this->Files->rename($strCacheName, $target);

			return $target;
		}

		// Set the file permissions when the Safe Mode Hack is used
		if ($GLOBALS['TL_CONFIG']['useFTP'])
		{
			$this->import('Files');
			$this->Files->chmod($strCacheName, 0644);
		}

		// Return the path to new image
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
	 * Return the current languages to be used with the TinyMCE spellchecker
	 * @return string
	 */
	protected function getSpellcheckerString()
	{
		$this->loadLanguageFile('languages');

		$return = array();
		$langs = scan(TL_ROOT . '/system/modules/backend/languages');
		array_unshift($langs, $GLOBALS['TL_LANGUAGE']);

		foreach ($langs as $lang)
		{
			if (isset($GLOBALS['TL_LANG']['LNG'][$lang]))
			{
				$return[$lang] = $GLOBALS['TL_LANG']['LNG'][$lang] . '=' . $lang;
			}
		}

		return '+' . implode(',', array_unique($return));
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
		$strArticle = $this->convertRelativeUrls($strArticle, '', true);

		// Remove form elements and JavaScript links
		$arrSearch = array
		(
			'@<form.*</form>@Us',
			'@<a [^>]*href="[^"]*javascript:[^>]+>.*</a>@Us'
		);

		$strArticle = preg_replace($arrSearch, '', $strArticle);

		// HOOK: allow individual PDF routines
		if (isset($GLOBALS['TL_HOOKS']['printArticleAsPdf']) && is_array($GLOBALS['TL_HOOKS']['printArticleAsPdf']))
		{
			foreach ($GLOBALS['TL_HOOKS']['printArticleAsPdf'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($strArticle, $objArticle);
			}
		}

		// Handle line breaks in preformatted text
		$strArticle = preg_replace_callback('@(<pre.*</pre>)@Us', 'nl2br_callback', $strArticle);

		// Default PDF export using TCPDF
		$arrSearch = array
		(
			'@<span style="text-decoration: ?underline;?">(.*)</span>@Us',
			'@(<img[^>]+>)@',
			'@(<div[^>]+block[^>]+>)@',
			'@[\n\r\t]+@',
			'@<br /><div class="mod_article@',
			'@href="([^"]+)(pdf=[0-9]*(&|&amp;)?)([^"]*)"@'
		);

		$arrReplace = array
		(
			'<u>$1</u>',
			'<br />$1',
			'<br />$1',
			' ',
			'<div class="mod_article',
			'href="$1$4"'
		);

		$strArticle = preg_replace($arrSearch, $arrReplace, $strArticle);

		// TCPDF configuration
		$l['a_meta_dir'] = 'ltr';
		$l['a_meta_charset'] = $GLOBALS['TL_CONFIG']['characterSet'];
		$l['a_meta_language'] = $GLOBALS['TL_LANGUAGE'];
		$l['w_page'] = 'page';

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
		$pdf->SetFont(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN);

		// Write the HTML content
		$pdf->writeHTML($strArticle, true, 0, true, 0);

		// Close and output PDF document
		$pdf->lastPage();
		$pdf->Output(standardize(ampersand($objArticle->title, false)) . '.pdf', 'D');

		// Stop script execution
		exit;
	}


	/**
	 * Replace insert tags with their values
	 * @param string
	 * @return string
	 */
	protected function replaceInsertTags($strBuffer, $blnCache=false)
	{
		global $objPage;

		// Preserve insert tags
		if ($GLOBALS['TL_CONFIG']['disableInsertTags'])
		{
			return $this->restoreBasicEntities($strBuffer);
		}

		$tags = preg_split('/{{([^}]+)}}/', $strBuffer, -1, PREG_SPLIT_DELIM_CAPTURE);

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
			if (isset($arrCache[$strTag]))
			{
				$strBuffer .= $arrCache[$strTag];
				continue;
			}

			$elements = explode('::', $strTag);

			// Skip certain elements if the output will be cached
			if ($blnCache)
			{
				if ($elements[0] == 'date' || $elements[0] == 'file' || $elements[1] == 'back' || $elements[1] == 'referer' || strncmp($elements[0], 'cache_', 6) === 0)
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
					$arrCache[$strTag] = $this->parseDate((strlen($elements[1]) ? $elements[1] : $GLOBALS['TL_CONFIG']['dateFormat']));
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
						$arrCache[$strTag] = '<a href="&#109;&#97;&#105;&#108;&#116;&#111;&#58;' . $strEmail . '">' . preg_replace('/\?.*$/', '', $strEmail) . '</a>';
					}
					break;

				// Label tags
				case 'label':
					$keys = explode(':', $elements[1]);

					if (count($keys) < 2)
					{
						$arrCache[$strTag] = '';
						break;
					}

					$this->loadLanguageFile($keys[0]);

					if (count($keys) == 2)
					{
						$arrCache[$strTag] = $GLOBALS['TL_LANG'][$keys[0]][$keys[1]];
					}
					else
					{
						$arrCache[$strTag] = $GLOBALS['TL_LANG'][$keys[0]][$keys[1]][$keys[2]];
					}
					break;

				// Front end user
				case 'user':
					if (FE_USER_LOGGED_IN)
					{
						$this->import('FrontendUser', 'User');
						$value = $this->User->$elements[1];

						if ($value == '')
						{
							$arrCache[$strTag] = $value;
							break;
						}

						$this->loadDataContainer('tl_member');

						if ($GLOBALS['TL_DCA']['tl_member']['fields'][$elements[1]]['inputType'] == 'password')
						{
							$arrCache[$strTag] = '';
							break;
						}

						$value = deserialize($value);
						$rgxp = $GLOBALS['TL_DCA']['tl_member']['fields'][$elements[1]]['eval']['rgxp'];
						$opts = $GLOBALS['TL_DCA']['tl_member']['fields'][$elements[1]]['options'];
						$rfrc = $GLOBALS['TL_DCA']['tl_member']['fields'][$elements[1]]['reference'];

						if ($rgxp == 'date')
						{
							$arrCache[$strTag] = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $value);
						}
						elseif ($rgxp == 'time')
						{
							$arrCache[$strTag] = $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $value);
						}
						elseif ($rgxp == 'datim')
						{
							$arrCache[$strTag] = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $value);
						}
						elseif (is_array($value))
						{
							$arrCache[$strTag] = implode(', ', $value);
						}
						elseif (is_array($opts) && array_is_assoc($opts))
						{
							$arrCache[$strTag] = isset($opts[$value]) ? $opts[$value] : $value;
						}
						elseif (is_array($rfrc))
						{
							$arrCache[$strTag] = isset($rfrc[$value]) ? ((is_array($rfrc[$value])) ? $rfrc[$value][0] : $rfrc[$value]) : $value;
						}
						else
						{
							$arrCache[$strTag] = $value;
						}
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
						$objNextPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=? OR alias=?")
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
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">%s</a>', $strUrl, specialchars($strTitle, true), ampersand($strTitle));
							break;

						case 'link_open':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">', $strUrl, specialchars($strTitle, true));
							break;

						case 'link_url':
							$arrCache[$strTag] = $strUrl;
							break;

						case 'link_title':
							$arrCache[$strTag] = specialchars($strTitle, true);
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
				case 'article_open':
				case 'article_url':
				case 'article_title':
					$this->import('Database');

					$objArticle = $this->Database->prepare("SELECT a.id AS aId, a.alias AS aAlias, a.title AS title, p.id AS id, p.alias AS alias FROM tl_article a, tl_page p WHERE a.pid=p.id AND (a.id=? OR a.alias=?)")
												 ->limit(1)
												 ->execute((is_numeric($elements[1]) ? $elements[1] : 0), $elements[1]);

					if ($objArticle->numRows < 1)
					{
						break;
					}
					else
					{
						$strUrl = $this->generateFrontendUrl($objArticle->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objArticle->aAlias)) ? $objArticle->aAlias : $objArticle->aId));
					}
	
					// Replace tag
					switch (strtolower($elements[0]))
					{
						case 'article':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">%s</a>', $strUrl, specialchars($objArticle->title, true), ampersand($objArticle->title));
							break;

						case 'article_open':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">', $strUrl, specialchars($objArticle->title, true));
							break;

						case 'article_url':
							$arrCache[$strTag] = $strUrl;
							break;

						case 'article_title':
							$arrCache[$strTag] = specialchars($objArticle->title, true);
							break;
					}
					break;

				// FAQ
				case 'faq':
				case 'faq_open':
				case 'faq_url':
				case 'faq_title':
					$this->import('Database');

					$objFaq = $this->Database->prepare("SELECT f.id AS fId, f.alias AS fAlias, f.question AS question, p.id AS id, p.alias AS alias FROM tl_faq f, tl_faq_category c, tl_page p WHERE f.pid=c.id AND c.jumpTo=p.id AND (f.id=? OR f.alias=?)")
											 ->limit(1)
											 ->execute((is_numeric($elements[1]) ? $elements[1] : 0), $elements[1]);

					if ($objFaq->numRows < 1)
					{
						break;
					}
					else
					{
						$strUrl = $this->generateFrontendUrl($objFaq->row(), '/items/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objFaq->fAlias)) ? $objFaq->fAlias : $objFaq->aId));
					}
	
					// Replace tag
					switch (strtolower($elements[0]))
					{
						case 'faq':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">%s</a>', $strUrl, specialchars($objFaq->question, true), ampersand($objFaq->question));
							break;

						case 'faq_open':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">', $strUrl, specialchars($objFaq->question, true));
							break;

						case 'faq_url':
							$arrCache[$strTag] = $strUrl;
							break;

						case 'faq_title':
							$arrCache[$strTag] = specialchars($objFaq->question, true);
							break;
					}
					break;

				// News
				case 'news':
				case 'news_open':
				case 'news_url':
				case 'news_title':
					$this->import('Database');

					$objNews = $this->Database->prepare("SELECT n.id AS nId, n.alias AS nAlias, n.headline, n.source, n.url, n.articleId as aId, a.alias AS aAlias, p.id, p.alias FROM tl_news n LEFT JOIN tl_news_archive c ON n.pid=c.id LEFT JOIN tl_article a ON n.articleId=a.id LEFT JOIN tl_page p ON p.id=(CASE WHEN n.source='internal' THEN n.jumpTo WHEN n.source='article' THEN a.pid ELSE c.jumpTo END) WHERE (n.id=? OR n.alias=?)")
											  ->limit(1)
											  ->execute((is_numeric($elements[1]) ? $elements[1] : 0), $elements[1]);

					if ($objNews->numRows < 1)
					{
						break;
					}
					elseif ($objNews->source == 'internal')
					{
						$strUrl = $this->generateFrontendUrl($objNews->row());
					}
					elseif ($objNews->source == 'article')
					{
						$strUrl = $this->generateFrontendUrl($objNews->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objNews->aAlias)) ? $objNews->aAlias : $objNews->aId));
					}
					elseif ($objNews->source == 'external')
					{
						$strUrl = $objNews->url;
					}
					else
					{
						$strUrl = $this->generateFrontendUrl($objNews->row(), '/items/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objNews->nAlias)) ? $objNews->nAlias : $objNews->nId));
					}
	
					// Replace tag
					switch (strtolower($elements[0]))
					{
						case 'news':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">%s</a>', $strUrl, specialchars($objNews->headline, true), ampersand($objNews->headline));
							break;

						case 'news_open':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">', $strUrl, specialchars($objNews->headline, true));
							break;

						case 'news_url':
							$arrCache[$strTag] = $strUrl;
							break;

						case 'news_title':
							$arrCache[$strTag] = specialchars($objNews->headline, true);
							break;
					}
					break;

				// Events
				case 'event':
				case 'event_open':
				case 'event_url':
				case 'event_title':
					$this->import('Database');

					$objEvent = $this->Database->prepare("SELECT e.id AS eId, e.alias AS eAlias, e.title, e.source, e.url, e.articleId as aId, a.alias AS aAlias, p.id, p.alias FROM tl_calendar_events e LEFT JOIN tl_calendar c ON e.pid=c.id LEFT JOIN tl_article a ON e.articleId=a.id LEFT JOIN tl_page p ON p.id=(CASE WHEN e.source='internal' THEN e.jumpTo WHEN e.source='article' THEN a.pid ELSE c.jumpTo END) WHERE (e.id=? OR e.alias=?)")
											   ->limit(1)
											   ->execute((is_numeric($elements[1]) ? $elements[1] : 0), $elements[1]);

					if ($objEvent->numRows < 1)
					{
						break;
					}
					elseif ($objEvent->source == 'internal')
					{
						$strUrl = $this->generateFrontendUrl($objEvent->row());
					}
					elseif ($objEvent->source == 'article')
					{
						$strUrl = $this->generateFrontendUrl($objEvent->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objEvent->aAlias)) ? $objEvent->aAlias : $objEvent->aId));
					}
					elseif ($objEvent->source == 'external')
					{
						$strUrl = $objEvent->url;
					}
					else
					{
						$strUrl = $this->generateFrontendUrl($objEvent->row(), '/events/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objEvent->eAlias)) ? $objEvent->eAlias : $objEvent->eId));
					}

					// Replace tag
					switch (strtolower($elements[0]))
					{
						case 'event':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">%s</a>', $strUrl, specialchars($objEvent->title, true), ampersand($objEvent->title));
							break;

						case 'event_open':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">', $strUrl, specialchars($objEvent->title, true));
							break;

						case 'event_url':
							$arrCache[$strTag] = $strUrl;
							break;

						case 'event_title':
							$arrCache[$strTag] = specialchars($objEvent->title, true);
							break;
					}
					break;

				// Closing link tag
				case 'link_close':
					$arrCache[$strTag] = '</a>';
					break;

				// Article teaser
				case 'article_teaser':
					$this->import('Database');

					$objTeaser = $this->Database->prepare("SELECT teaser FROM tl_article WHERE id=? OR alias=?")
												->limit(1)
												->execute((is_numeric($elements[1]) ? $elements[1] : 0), $elements[1]);

					if ($objTeaser->numRows)
					{
						$arrCache[$strTag] = $objTeaser->teaser;
					}
					break;

				// News teaser
				case 'news_teaser':
					$this->import('Database');

					$objTeaser = $this->Database->prepare("SELECT teaser FROM tl_news WHERE id=? OR alias=?")
												->limit(1)
												->execute((is_numeric($elements[1]) ? $elements[1] : 0), $elements[1]);

					if ($objTeaser->numRows)
					{
						$arrCache[$strTag] = $objTeaser->teaser;
					}
					break;

				// Event teaser
				case 'event_teaser':
					$this->import('Database');

					$objTeaser = $this->Database->prepare("SELECT teaser FROM tl_calendar_events WHERE id=? OR alias=?")
												->limit(1)
												->execute((is_numeric($elements[1]) ? $elements[1] : 0), $elements[1]);

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
						$arrCache[$strTag] = $this->parseDate((strlen($elements[1]) ? $elements[1] : $GLOBALS['TL_CONFIG']['datimFormat']), max($objUpdate->tc, $objUpdate->tn, $objUpdate->te));
					}
					break;

				// Version
				case 'version':
					$arrCache[$strTag] = VERSION . '.' . BUILD;
					break;

				// Conditional tags
				case 'iflng':
					if (strlen($elements[1]) && $elements[1] != $objPage->language)
					{
						$_rit = $_rit + 2;
					}
					unset($arrCache[$strTag]);
					break;

				// Environment
				case 'env':
					switch ($elements[1])
					{
						case 'page_id':
							$arrCache[$strTag] = $objPage->id;
							break;

						case 'page_alias':
							$arrCache[$strTag] = $objPage->alias;
							break;

						case 'page_name':
							$arrCache[$strTag] = $objPage->title;
							break;

						case 'page_title':
							$arrCache[$strTag] = strlen($objPage->pageTitle) ? $objPage->pageTitle : $objPage->title;
							break;

						case 'page_language':
							$arrCache[$strTag] = $objPage->language;
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
							$request = $this->Environment->request;
							if ($request == 'index.php')
							{
								$arrCache[$strTag] = '';
							}
							else
							{
								$arrCache[$strTag] = ampersand($request, true);
							}
							break;

						case 'ip':
							$arrCache[$strTag] = $this->Environment->ip;
							break;

						case 'referer':
							$arrCache[$strTag] = $this->getReferer(true);
							break;
					}
					break;

				// Images
				case 'image':
					$width = null;
					$height = null;
					$alt = '';
					$class = '';
					$rel = '';
					$strFile = $elements[1];
					$mode = '';

					// Take arguments
					if (strpos($elements[1], '?') !== false)
					{
						$this->import('String');

						$arrChunks = explode('?', urldecode($elements[1]));
						$strSource = $this->String->decodeEntities($arrChunks[1]);
						$strSource = str_replace('[&]', '&', $strSource);
						$arrParams = explode('&', $strSource);

						foreach ($arrParams as $strParam)
						{
							list($key, $value) = explode('=', $strParam);

							switch ($key)
							{
								case 'width':
									$width = $value;
									break;

								case 'height':
									$height = $value;
									break;

								case 'alt':
									$alt = specialchars($value);
									break;

								case 'class':
									$class = $value;
									break;

								case 'rel':
									$rel = $value;
									break;

								case 'mode':
									$mode = $value;
									break;
							}
						}

						$strFile = $arrChunks[0];
					}

					// Sanitize path
					$strFile = str_replace('../', '', $strFile);

					// Check maximum image width
					if ($GLOBALS['TL_CONFIG']['maxImageWidth'] > 0 && $width > $GLOBALS['TL_CONFIG']['maxImageWidth'])
					{
						$width = $GLOBALS['TL_CONFIG']['maxImageWidth'];
						$height = null;
					}

					// Generate image
					if (strlen($rel))
					{
						$arrCache[$strTag] = '<a href="' . $strFile . '"' . (strlen($alt) ? ' title="' . $alt . '"' : '') . ' rel="' . $rel . '"><img src="' . $this->getImage($strFile, $width, $height, $mode) . '" alt="' . $alt . '"' . (strlen($class) ? ' class="' . $class . '"' : '') . ' /></a>';
					}
					else
					{
						$arrCache[$strTag] = '<img src="' . $this->getImage($strFile, $width, $height, $mode) . '" alt="' . $alt . '"' . (strlen($class) ? ' class="' . $class . '"' : '') . ' />';
					}
					break;

				// Files from the templates directory
				case 'file':
					$arrGet = $_GET;
					$this->import('Input');
					$this->Input->resetCache();
					$strFile = $elements[1];

					// Take arguments and add them to the $_GET array
					if (strpos($elements[1], '?') !== false)
					{
						$this->import('String');

						$arrChunks = explode('?', urldecode($elements[1]));
						$strSource = $this->String->decodeEntities($arrChunks[1]);
						$strSource = str_replace('[&]', '&', $strSource);
						$arrParams = explode('&', $strSource);

						foreach ($arrParams as $strParam)
						{
							$arrParam = explode('=', $strParam);
							$_GET[$arrParam[0]] = $arrParam[1];
						}

						$strFile = $arrChunks[0];
					}

					// Sanitize path
					$strFile = str_replace('../', '', $strFile);

					// Include file
					if (file_exists(TL_ROOT . '/templates/' . $strFile))
					{
						ob_start();
						include(TL_ROOT . '/templates/' . $strFile);
						$arrCache[$strTag] = ob_get_contents();
						ob_end_clean();
					}

					$_GET = $arrGet;
					$this->Input->resetCache();
					break;

				// HOOK: pass unknown tags to callback functions
				default:
					if (isset($GLOBALS['TL_HOOKS']['replaceInsertTags']) && is_array($GLOBALS['TL_HOOKS']['replaceInsertTags']))
					{
						foreach ($GLOBALS['TL_HOOKS']['replaceInsertTags'] as $callback)
						{
							$this->import($callback[0]);
							$varValue = $this->$callback[0]->$callback[1]($strTag);

							// Replace the tag and stop the loop
							if ($varValue !== false)
							{
								$arrCache[$strTag] = $varValue;
								break;
							}
						}
					}
					break;
			}

			$strBuffer .= $arrCache[$strTag];
		}

		return $this->restoreBasicEntities($strBuffer);
	}


	/**
	 * Restore basic entities
	 * @param string
	 * @return string
	 */
	protected function restoreBasicEntities($strBuffer)
	{
		return str_replace(array('[&]', '[lt]', '[gt]', '[nbsp]', '[-]'), array('&amp;', '&lt;', '&gt;', '&nbsp;', '&shy;'), $strBuffer);
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
		$strReturn = str_replace('?><br />', '?>', $strReturn);
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
		if (isset($GLOBALS['TL_HOOKS']['generateFrontendUrl']) && is_array($GLOBALS['TL_HOOKS']['generateFrontendUrl']))
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
	 * Convert relative URLs to absolute URLs
	 * @param string
	 * @param string
	 * @param boolean
	 * @return string
	 */
	protected function convertRelativeUrls($strContent, $strBase='', $blnHrefOnly=false)
	{
		if ($strBase == '')
		{
			$strBase = $this->Environment->base;
		}

		$search = $blnHrefOnly ? 'href' : 'href|src';
		$arrUrls = preg_split('/(('.$search.')="([^"]+)")/i', $strContent, -1, PREG_SPLIT_DELIM_CAPTURE);
		$strContent = '';

		for($i=0; $i<count($arrUrls); $i=$i+4)
		{
			$strContent .= $arrUrls[$i];

			if (!isset($arrUrls[$i+2]))
			{
				continue;
			}

			$strAttribute = $arrUrls[$i+2];
			$strUrl = $arrUrls[$i+3];

			if (!preg_match('@^(https?://|ftp://|mailto:|#)@i', $strUrl))
			{
				$strUrl = $strBase . (($strUrl != '/') ? $strUrl : '');
			}

			$strContent .= $strAttribute . '="' . $strUrl . '"';
		}

		return $strContent;
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
			header('HTTP/1.1 404 Not Found');
			die('Invalid file name');
		}

		// Limit downloads to the tl_files directory
		if (!preg_match('@^' . preg_quote($GLOBALS['TL_CONFIG']['uploadPath'], '@') . '@i', $strFile))
		{
			header('HTTP/1.1 404 Not Found');
			die('Invalid path');
		}

		// Check whether the file exists
		if (!file_exists(TL_ROOT . '/' . $strFile))
		{
			header('HTTP/1.1 404 Not Found');
			die('File not found');
		}

		$objFile = new File($strFile);
		$arrAllowedTypes = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

		if (!in_array($objFile->extension, $arrAllowedTypes))
		{
			header('HTTP/1.1 403 Forbidden');
			die(sprintf('File type "%s" is not allowed', $objFile->extension));
		}

		// Open the "save as …" dialogue
		header('Content-Type: ' . $objFile->mime);
		header('Content-Transfer-Encoding: binary');
		header('Content-Disposition: attachment; filename="' . $objFile->basename . '"');
		header('Content-Length: ' . $objFile->filesize);
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Expires: 0');

		$resFile = fopen(TL_ROOT . '/' . $strFile, 'rb');
		fpassthru($resFile);
		fclose($resFile);

		// HOOK: post download callback
		if (isset($GLOBALS['TL_HOOKS']['postDownload']) && is_array($GLOBALS['TL_HOOKS']['postDownload']))
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

		// HOOK: allow to load custom settings
		if (isset($GLOBALS['TL_HOOKS']['loadDataContainer']) && is_array($GLOBALS['TL_HOOKS']['loadDataContainer']))
		{
			foreach ($GLOBALS['TL_HOOKS']['loadDataContainer'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($strName);
			}
		}

		@include(TL_ROOT . '/system/config/dcaconfig.php');
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

		// Internet Explorer does not support onchange for checkboxes and radio buttons
		if ($arrData['inputType'] == 'checkbox' || $arrData['inputType'] == 'checkboxWizard' || $arrData['inputType'] == 'radio' || $arrData['inputType'] == 'radioTable')
		{
			$arrNew['onclick'] = $arrData['eval']['submitOnChange'] ? "Backend.autoSubmit('".$strTable."');" : '';
		}
		else
		{
			$arrNew['onchange'] = $arrData['eval']['submitOnChange'] ? "Backend.autoSubmit('".$strTable."');" : '';
		}

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
		if ($arrData['inputType'] == 'checkbox' && !isset($arrData['options']) && !isset($arrData['options_callback']) && !isset($arrData['foreignKey']))
		{
			if (TL_MODE == 'FE' && isset($arrNew['description']))
			{
				$arrNew['options'][] = array('value'=>1, 'label'=>$arrNew['description']);
			}
			else
			{
				$arrNew['options'][] = array('value'=>1, 'label'=>$arrNew['label']);
			}
		}

		// Add options
		if (is_array($arrData['options']))
		{
			$blnIsAssociative = array_is_assoc($arrData['options']);
			$blnUseReference = isset($arrData['reference']);

			if ($arrData['eval']['includeBlankOption'])
			{
				$strLabel = strlen($arrData['eval']['blankOptionLabel']) ? $arrData['eval']['blankOptionLabel'] : '-';
				$arrNew['options'][] = array('value'=>'', 'label'=>$strLabel);
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
	 * Create an initial version of a record
	 * @param mixed
	 */
	protected function createInitialVersion($strTable, $intId)
	{
		if (!$GLOBALS['TL_DCA'][$strTable]['config']['enableVersioning'])
		{
			return;
		}

		$objVersion = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_version WHERE fromTable=? AND pid=?")
									 ->limit(1)
									 ->executeUncached($strTable, $intId);

		if ($objVersion->total < 1)
		{
			$this->createNewVersion($strTable, $intId);
		}
	}


	/**
	 * Create a new version of a record
	 * @param mixed
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
									->executeUncached($intId);

		if ($objRecord->numRows < 1 || $objRecord->tstamp < 1)
		{
			return;
		}

		$intVersion = 1;
		$this->import('BackendUser', 'User');

		$objVersion = $this->Database->prepare("SELECT MAX(version) AS version FROM tl_version WHERE pid=? AND fromTable=?")
									 ->executeUncached($intId, $strTable);

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

		$objChilds = $this->Database->prepare("SELECT id, (SELECT COUNT(*) FROM " . $strTable . " t2 WHERE t1.id=t2.pid) AS hasChilds FROM " . $strTable ." t1 WHERE t1.pid=? AND t1.id!=?" . ($blnSorting ? " ORDER BY t1.sorting" : ""))
									->execute($intParentId, $intParentId);

		if ($objChilds->numRows > 0)
		{
			while ($objChilds->next())
			{
				$arrReturn[] = $objChilds->id;

				if ($objChilds->hasChilds > 0)
				{
					$arrReturn = array_merge($arrReturn, $this->getChildRecords($objChilds->id, $strTable, $blnSorting));
				}
			}
		}

		return $arrReturn;
	}


	/**
	 * Return true if a class file exists
	 * @param string
	 * @param boolean
	 */
	protected function classFileExists($strClass, $blnNoCache=false)
	{
		if (!$blnNoCache && isset($this->arrCache[$strClass]))
		{
			return $this->arrCache[$strClass];
		}

		$this->import('Config'); // see ticket #152
		$this->arrCache[$strClass] = false;

		foreach ($this->Config->getActiveModules() as $strModule)
		{
			$strFile = sprintf('%s/system/modules/%s/%s.php', TL_ROOT, $strModule, $strClass);

			if (file_exists($strFile))
			{
				$this->arrCache[$strClass] = true;
				break;
			}
		}

		return $this->arrCache[$strClass];
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
	 * @param string
	 * @return array
	 */
	protected function eliminateNestedPages($arrPages, $strTable=null)
	{
		if (!is_array($arrPages) || count($arrPages) < 1)
		{
			return array();
		}

		if (!$strTable)
		{
			$strTable = 'tl_page';
		}

		$nested = array();
		$arrPages = array_intersect($this->getChildRecords(0, $strTable), $arrPages);

		foreach ($arrPages as $page)
		{
			$nested = array_merge($nested, $this->getChildRecords($page, $strTable));
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
	 * @param boolean
	 * @return array
	 */
	protected function removeOldFeeds($blnReturn=false)
	{
		$arrFeeds = array();
		$arrModules = $this->Config->getActiveModules();

		// XML sitemaps
		$objFeeds = $this->Database->execute("SELECT sitemapName FROM tl_page WHERE type='root' AND createSitemap=1 AND sitemapName!=''");

		while ($objFeeds->next())
		{
			$arrFeeds[] = $objFeeds->sitemapName;
		}

		// Calendar module
		if (in_array('calendar', $arrModules))
		{
			$objFeeds = $this->Database->execute("SELECT id, alias FROM tl_calendar WHERE makeFeed=1 AND alias!=''");

			while ($objFeeds->next())
			{
				$arrFeeds[] = strlen($objFeeds->alias) ? $objFeeds->alias : 'calendar' . $objFeeds->id;
			}
		}

		// News module
		if (in_array('news', $arrModules))
		{
			$objFeeds = $this->Database->execute("SELECT id, alias FROM tl_news_archive WHERE makeFeed=1 AND alias!=''");

			while ($objFeeds->next())
			{
				$arrFeeds[] = strlen($objFeeds->alias) ? $objFeeds->alias : 'news' . $objFeeds->id;
			}
		}

		// HOOK: preserve third party feeds
		if (isset($GLOBALS['TL_HOOKS']['removeOldFeeds']) && is_array($GLOBALS['TL_HOOKS']['removeOldFeeds']))
		{
			foreach ($GLOBALS['TL_HOOKS']['removeOldFeeds'] as $callback)
			{
				$this->import($callback[0]);
				$arrFeeds = array_merge($arrFeeds, $this->$callback[0]->$callback[1]());
			}
		}

		// Make sure the dcaconfig.php is loaded
		@include(TL_ROOT . '/system/config/dcaconfig.php');

		// Add root files
		if (is_array($GLOBALS['TL_CONFIG']['rootFiles']))
		{
			foreach ($GLOBALS['TL_CONFIG']['rootFiles'] as $strFile)
			{
				$arrFeeds[] = str_replace('.xml', '', $strFile);
			}
		}

		// Delete old files
		if (!$blnReturn)
		{
			foreach (scan(TL_ROOT) as $file)
			{
				if (is_dir(TL_ROOT . '/' . $file))
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

		return $arrFeeds;
	}


	/**
	 * Add an image to a template
	 * @param object
	 * @param array
	 * @param integer
	 * @param string
	 */
	protected function addImageToTemplate($objTemplate, $arrItem, $intMaxWidth=false, $strLightboxId=false)
	{
		$size = deserialize($arrItem['size']);
		$imgSize = getimagesize(TL_ROOT . '/' . $arrItem['singleSRC']);

		if (!$intMaxWidth)
		{
			$intMaxWidth = (TL_MODE == 'BE') ? 320 : $GLOBALS['TL_CONFIG']['maxImageWidth'];
		}

		if (!$strLightboxId)
		{
			$strLightboxId = 'lightbox';
		}

		// Store original dimensions
		$objTemplate->width = $imgSize[0];
		$objTemplate->height = $imgSize[1];

		// Adjust image size
		if ($intMaxWidth > 0 && ($size[0] > $intMaxWidth || (!$size[0] && !$size[1] && $imgSize[0] > $intMaxWidth)))
		{
			$arrMargin = deserialize($arrItem['imagemargin']);

			// Subtract margins
			if (is_array($arrMargin) && $arrMargin['unit'] == 'px')
			{
				$intMaxWidth = $intMaxWidth - $arrMargin['left'] - $arrMargin['right'];
			}

			$size[0] = $intMaxWidth;
			$size[1] = floor($intMaxWidth * $imgSize[1] / $imgSize[0]);
		}

		$src = $this->getImage($this->urlEncode($arrItem['singleSRC']), $size[0], $size[1], $size[2]);

		// Image dimensions
		if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false)
		{
			$objTemplate->arrSize = $imgSize;
			$objTemplate->imgSize = ' ' . $imgSize[3];
		}

		// Float image
		if (in_array($arrItem['floating'], array('left', 'right')))
		{
			$objTemplate->floatClass = ' float_' . $arrItem['floating'];
			$objTemplate->float = ' float:' . $arrItem['floating'] . ';';
		}

		// Image link
		if (strlen($arrItem['imageUrl']) && TL_MODE == 'FE')
		{
			$objTemplate->href = $arrItem['imageUrl'];
			$objTemplate->attributes = $arrItem['fullsize'] ? LINK_NEW_WINDOW : '';
		}

		// Fullsize view
		elseif ($arrItem['fullsize'] && TL_MODE == 'FE')
		{
			$objTemplate->href = $this->urlEncode($arrItem['singleSRC']);
			$objTemplate->attributes = ' rel="' . $strLightboxId . '"';
		}

		$objTemplate->src = $src;
		$objTemplate->alt = specialchars($arrItem['alt']);
		$objTemplate->fullsize = $arrItem['fullsize'] ? true : false;
		$objTemplate->addBefore = ($arrItem['floating'] != 'below');
		$objTemplate->margin = $this->generateMargin(deserialize($arrItem['imagemargin']), 'padding');
		$objTemplate->caption = $arrItem['caption'];
		$objTemplate->addImage = true;
	}


	/**
	 * Add enclosures to a template
	 * @param object
	 * @param array
	 */
	protected function addEnclosuresToTemplate($objTemplate, $arrItem)
	{
		$arrEnclosure = deserialize($arrItem['enclosure'], true);
		$allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

		// Send file to the browser
		if (strlen($this->Input->get('file', true)) && in_array($this->Input->get('file', true), $arrEnclosure))
		{
			$this->sendFileToBrowser($this->Input->get('file', true));
		}

		$arrEnclosures = array();

		// Add download links
		for ($i=0; $i<count($arrEnclosure); $i++)
		{
			if (is_file(TL_ROOT . '/' . $arrEnclosure[$i]))
			{				
				$objFile = new File($arrEnclosure[$i]);

				if (!in_array($objFile->extension, $allowedDownload))
				{
					continue;
				}

				$arrEnclosures[$i]['link'] = basename($arrEnclosure[$i]);
				$arrEnclosures[$i]['filesize'] = $this->getReadableSize($objFile->filesize);
				$arrEnclosures[$i]['title'] = ucfirst(str_replace('_', ' ', $objFile->filename));
				$arrEnclosures[$i]['href'] = $this->Environment->request . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos($this->Environment->request, '?') !== false) ? '&amp;' : '?') . 'file=' . $this->urlEncode($arrEnclosure[$i]);
				$arrEnclosures[$i]['enclosure'] = $arrEnclosure[$i];
				$arrEnclosures[$i]['icon'] = 'system/themes/' . $this->getTheme() . '/images/' . $objFile->icon;
			}
		}

		$objTemplate->enclosure = $arrEnclosures;
	}
}

?>