<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;


/**
 * Abstract parent class for Controllers
 * 
 * Some of the methods have been made static in Contao 3 and can be used in
 * non-object context as well.
 * 
 * Usage:
 * 
 *     echo Controller::getTheme();
 * 
 * Inside a controller:
 * 
 *     public function generate()
 *     {
 *         return $this->getArticle(2);
 *     }
 * 
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
abstract class Controller extends \System
{

	/**
	 * Return the current theme as string
	 * 
	 * @return string The name of the theme
	 */
	public static function getTheme()
	{
		$theme = $GLOBALS['TL_CONFIG']['backendTheme'];

		if ($theme != '' && $theme != 'default' && is_dir(TL_ROOT . '/system/themes/' . $theme))
		{
			return $theme;
		}

		return 'default';
	}


	/**
	 * Find a particular template file and return its path
	 * 
	 * @param string $strTemplate The name of the template
	 * @param string $strFormat   The file extension
	 * 
	 * @return string The path to the template file
	 * 
	 * @throws \Exception If $strFormat is unknown
	 */
	public static function getTemplate($strTemplate, $strFormat='html5')
	{
		$arrAllowed = trimsplit(',', $GLOBALS['TL_CONFIG']['templateFiles']);
		array_push($arrAllowed, 'html5'); // see #3398

		if (!in_array($strFormat, $arrAllowed))
		{
			throw new \Exception("Invalid output format $strFormat");
		}

		$strTemplate = basename($strTemplate);

		// Check for a theme folder
		if (TL_MODE == 'FE')
		{
			global $objPage;
			$strCustom = str_replace('../', '', $objPage->templateGroup);

			if ($strCustom != '')
			{
				return \TemplateLoader::getPath($strTemplate, $strFormat, $strCustom);
			}
		}

		return \TemplateLoader::getPath($strTemplate, $strFormat);
	}


	/**
	 * Return all template files of a particular group as array
	 * 
	 * @param string $strPrefix The template name prefix (e.g. "ce_")
	 * 
	 * @return array An array of template names
	 */
	public static function getTemplateGroup($strPrefix)
	{
		$arrTemplates = array();

		// Get the default templates
		foreach (\TemplateLoader::getPrefixedFiles($strPrefix) as $strTemplate)
		{
			$arrTemplates[$strTemplate] = $strTemplate;
		}

		// Add the customized templates
		foreach (glob(TL_ROOT . '/templates/' . $strPrefix . '*') as $strFile)
		{
			$strTemplate = basename($strFile, strrchr($strFile, '.'));

			if (!isset($arrTemplates[$strTemplate]))
			{
				$arrTemplates[$strTemplate] = $strTemplate;
			}
		}

		// Wrap into a try-catch block (see #5210)
		try
		{
			$objTheme = \ThemeModel::findAll(array('order'=>'name'));
		}
		catch (\Exception $e)
		{
			$objTheme = null;
		}

		// Add the theme templates
		if ($objTheme !== null)
		{
			while ($objTheme->next())
			{
				if ($objTheme->templates != '')
				{
					foreach (glob(TL_ROOT . '/' . $objTheme->templates . '/' . $strPrefix . '*') as $strFile)
					{
						$strTemplate = basename($strFile, strrchr($strFile, '.'));

						if (!isset($arrTemplates[$strTemplate]))
						{
							$arrTemplates[$strTemplate] = $strTemplate . ' (' . sprintf($GLOBALS['TL_LANG']['MSC']['templatesTheme'], $objTheme->name) . ')';
						}
					}
				}
			}
		}

		return $arrTemplates;
	}


	/**
	 * Generate a front end module and return it as string
	 * 
	 * @param mixed  $intId     A module ID or a Model object
	 * @param string $strColumn The name of the column
	 * 
	 * @return string The module HTML markup
	 */
	protected function getFrontendModule($intId, $strColumn='main')
	{
		if (!is_object($intId) && !strlen($intId))
		{
			return '';
		}

		global $objPage;

		// Articles
		if ($intId == 0)
		{
			// Show a particular article only
			if ($objPage->type == 'regular' && \Input::get('articles'))
			{
				list($strSection, $strArticle) = explode(':', \Input::get('articles'));

				if ($strArticle === null)
				{
					$strArticle = $strSection;
					$strSection = 'main';
				}

				if ($strSection == $strColumn)
				{
					$strBuffer = $this->getArticle($strArticle);

					// Send a 404 header if the article does not exist
					if ($strBuffer === false)
					{
						// Do not index the page
						$objPage->noSearch = 1;
						$objPage->cache = 0;

						header('HTTP/1.1 404 Not Found');
						return '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['invalidPage'], $strArticle) . '</p>';
					}

					return $strBuffer;
				}
			}

			// HOOK: trigger the article_raster_designer extension
			if (in_array('article_raster_designer', $this->Config->getActiveModules()))
			{
				return \RasterDesigner::load($objPage->id, $strColumn);
			}

			// Show all articles (no else block here, see #4740)
			$objArticles = \ArticleModel::findPublishedByPidAndColumn($objPage->id, $strColumn);

			if ($objArticles === null)
			{
				return '';
			}

			$return = '';
			$blnMultiMode = ($objArticles->count() > 1);

			while ($objArticles->next())
			{
				$return .= $this->getArticle($objArticles, $blnMultiMode, false, $strColumn);
			}

			return $return;
		}

		// Other modules
		else
		{
			if (is_object($intId))
			{
				$objRow = $intId;
			}
			else
			{
				$objRow = \ModuleModel::findByPk($intId);
			}

			if ($objRow === null)
			{
				return '';
			}

			// Show to guests only
			if ($objRow->guests && FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN && !$objRow->protected)
			{
				return '';
			}

			// Protected element
			if (!BE_USER_LOGGED_IN && $objRow->protected)
			{
				if (!FE_USER_LOGGED_IN)
				{
					return '';
				}

				$this->import('FrontendUser', 'User');
				$groups = deserialize($objRow->groups);

				if (!is_array($groups) || empty($groups) || !count(array_intersect($groups, $this->User->groups)))
				{
					return '';
				}
			}

			$strClass = $this->findFrontendModule($objRow->type);

			// Return if the class does not exist
			if (!class_exists($strClass))
			{
				$this->log('Module class "'.$GLOBALS['FE_MOD'][$objRow->type].'" (module "'.$objRow->type.'") does not exist', 'Controller getFrontendModule()', TL_ERROR);
				return '';
			}

			$objRow->typePrefix = 'mod_';
			$objModule = new $strClass($objRow, $strColumn);
			$strBuffer = $objModule->generate();

			// HOOK: add custom logic
			if (isset($GLOBALS['TL_HOOKS']['getFrontendModule']) && is_array($GLOBALS['TL_HOOKS']['getFrontendModule']))
			{
				foreach ($GLOBALS['TL_HOOKS']['getFrontendModule'] as $callback)
				{
					$this->import($callback[0]);
					$strBuffer = $this->$callback[0]->$callback[1]($objRow, $strBuffer, $objModule);
				}
			}

			// Disable indexing if protected
			if ($objModule->protected && !preg_match('/^\s*<!-- indexer::stop/', $strBuffer))
			{
				$strBuffer = "\n<!-- indexer::stop -->". $strBuffer ."<!-- indexer::continue -->\n";
			}

			return $strBuffer;
		}
	}


	/**
	 * Generate an article and return it as string
	 * 
	 * @param mixed   $varId          The article ID or a Model object
	 * @param boolean $blnMultiMode   If true, only teasers will be shown
	 * @param boolean $blnIsInsertTag If true, there will be no page relation
	 * @param string  $strColumn      The name of the column
	 * 
	 * @return string|boolean The article HTML markup or false 
	 */
	protected function getArticle($varId, $blnMultiMode=false, $blnIsInsertTag=false, $strColumn='main')
	{
		global $objPage;

		if (is_object($varId))
		{
			$objRow = $varId;
		}
		else
		{
			if (!$varId)
			{
				return '';
			}

			$objRow = \ArticleModel::findByIdOrAliasAndPid($varId, (!$blnIsInsertTag ? $objPage->id : null));
		}

		// Return if the article does not exist
		if ($objRow === null)
		{
			return false;
		}

		// Print the article as PDF
		if (\Input::get('pdf') == $objRow->id)
		{
			// Backwards compatibility
			if ($objRow->printable == 1)
			{
				$this->printArticleAsPdf($objRow);
			}
			elseif ($objRow->printable != '')
			{
				$options = deserialize($objRow->printable);

				if (is_array($options) && in_array('pdf', $options))
				{
					$this->printArticleAsPdf($objRow);
				}
			}
		}

		$objRow->headline = $objRow->title;
		$objRow->multiMode = $blnMultiMode;

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getArticle']) && is_array($GLOBALS['TL_HOOKS']['getArticle']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getArticle'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($objRow);
			}
		}

		$objArticle = new \ModuleArticle($objRow, $strColumn);
		return $objArticle->generate($blnIsInsertTag);
	}


	/**
	 * Generate a content element and return it as string
	 * 
	 * @param mixed $intId A content element ID or a Model object
	 * 
	 * @return string The content element HTML markup
	 */
	protected function getContentElement($intId)
	{
		if (is_object($intId))
		{
			$objRow = $intId;
		}
		else
		{
			if (!strlen($intId) || $intId < 1)
			{
				return '';
			}

			$objRow = \ContentModel::findByPk($intId);

			if ($objRow === null)
			{
				return '';
			}
		}

		// Show to guests only
		if ($objRow->guests && FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN && !$objRow->protected)
		{
			return '';
		}

		// Protected the element
		if ($objRow->protected && !BE_USER_LOGGED_IN)
		{
			if (!FE_USER_LOGGED_IN)
			{
				return '';
			}

			$this->import('FrontendUser', 'User');
			$groups = deserialize($objRow->groups);

			if (!is_array($groups) || count($groups) < 1 || count(array_intersect($groups, $this->User->groups)) < 1)
			{
				return '';
			}
		}

		// Remove the spacing in the back end preview
		if (TL_MODE == 'BE')
		{
			$objRow->space = null;
		}

		$strClass = $this->findContentElement($objRow->type);

		// Return if the class does not exist
		if (!class_exists($strClass))
		{
			$this->log('Content element class "'.$strClass.'" (content element "'.$objRow->type.'") does not exist', 'Controller getContentElement()', TL_ERROR);
			return '';
		}

		$objRow->typePrefix = 'ce_';
		$objElement = new $strClass($objRow);
		$strBuffer = $objElement->generate();

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getContentElement']) && is_array($GLOBALS['TL_HOOKS']['getContentElement']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getContentElement'] as $callback)
			{
				$this->import($callback[0]);
				$strBuffer = $this->$callback[0]->$callback[1]($objRow, $strBuffer, $objElement);
			}
		}

		// Disable indexing if protected
		if ($objElement->protected && !preg_match('/^\s*<!-- indexer::stop/', $strBuffer))
		{
			$strBuffer = "\n<!-- indexer::stop -->". $strBuffer ."<!-- indexer::continue -->\n";
		}

		return $strBuffer;
	}


	/**
	 * Generate a form and return it as string
	 * 
	 * @param mixed $varId A form ID or a Model object
	 * 
	 * @return string The form HTML markup
	 */
	protected function getForm($varId)
	{
		if (is_object($varId))
		{
			$objRow = $varId;
		}
		else
		{
			if ($varId == '')
			{
				return '';
			}

			$objRow = \FormModel::findByIdOrAlias($varId);

			if ($objRow === null)
			{
				return '';
			}
		}

		$objRow->typePrefix = 'ce_';
		$objRow->form = $objRow->id;
		$objElement = new \Form($objRow);
		$strBuffer = $objElement->generate();

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getForm']) && is_array($GLOBALS['TL_HOOKS']['getForm']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getForm'] as $callback)
			{
				$this->import($callback[0]);
				$strBuffer = $this->$callback[0]->$callback[1]($objRow, $strBuffer, $objElement);
			}
		}

		return $strBuffer;
	}


	/**
	 * Get the details of a page including inherited parameters
	 * 
	 * @param mixed $intId A page ID or a Model object
	 * 
	 * @return \Model|null The page model or null
	 */
	public static function getPageDetails($intId)
	{
		if (is_object($intId))
		{
			// Always return a Model (see #4428)
			$objPage = ($intId instanceof \Model\Collection) ? $intId->current() : $intId;

			$intId = $objPage->id;
			$strKey = __METHOD__ . '-' . $objPage->id;

			if (\Cache::has($strKey))
			{
				return \Cache::get($strKey);
			}
		}
		else
		{
			if (!strlen($intId) || $intId < 1)
			{
				return null;
			}

			$strKey = __METHOD__ . '-' . $intId;

			if (\Cache::has($strKey))
			{
				return \Cache::get($strKey);
			}

			$objPage = \PageModel::findByPk($intId);

			if ($objPage === null)
			{
				return null;
			}
		}

		// Set some default values
		$objPage->protected = (boolean) $objPage->protected;
		$objPage->groups = $objPage->protected ? deserialize($objPage->groups) : false;
		$objPage->layout = $objPage->includeLayout ? $objPage->layout : false;
		$objPage->mobileLayout = $objPage->includeLayout ? $objPage->mobileLayout : false;
		$objPage->cache = $objPage->includeCache ? $objPage->cache : false;

		$pid = $objPage->pid;
		$type = $objPage->type;
		$alias = $objPage->alias;
		$name = $objPage->title;
		$title = $objPage->pageTitle ?: $objPage->title;
		$folderUrl = basename($objPage->alias);
		$palias = '';
		$pname = '';
		$ptitle = '';
		$trail = array($intId, $pid);

		// Inherit the settings
		if ($objPage->type == 'root')
		{
			$objParentPage = $objPage; // see #4610
		}
		else
		{
			// Load all parent pages
			$objParentPage = \PageModel::findParentsById($pid);

			if ($objParentPage !== null)
			{
				while ($objParentPage->next() && $pid > 0 && $type != 'root')
				{
					$pid = $objParentPage->pid;
					$type = $objParentPage->type;

					// Parent title
					if ($ptitle == '')
					{
						$palias = $objParentPage->alias;
						$pname = $objParentPage->title;
						$ptitle = $objParentPage->pageTitle ?: $objParentPage->title;
					}

					// Page title
					if ($type != 'root')
					{
						$alias = $objParentPage->alias;
						$name = $objParentPage->title;
						$title = $objParentPage->pageTitle ?: $objParentPage->title;
						$folderUrl = basename($alias) . '/' . $folderUrl;
						$trail[] = $objParentPage->pid;
					}

					// Cache
					if ($objParentPage->includeCache && $objPage->cache === false)
					{
						$objPage->cache = $objParentPage->cache;
					}

					// Layout
					if ($objParentPage->includeLayout)
					{
						if ($objPage->layout === false)
						{
							$objPage->layout = $objParentPage->layout;
						}
						if ($objPage->mobileLayout === false)
						{
							$objPage->mobileLayout = $objParentPage->mobileLayout;
						}
					}

					// Protection
					if ($objParentPage->protected && $objPage->protected === false)
					{
						$objPage->protected = true;
						$objPage->groups = deserialize($objParentPage->groups);
					}
				}
			}

			// Set the titles
			$objPage->mainAlias = $alias;
			$objPage->mainTitle = $name;
			$objPage->mainPageTitle = $title;
			$objPage->parentAlias = $palias;
			$objPage->parentTitle = $pname;
			$objPage->parentPageTitle = $ptitle;
			$objPage->folderUrl = $folderUrl;
		}

		// Set the root ID and title
		if ($objParentPage !== null && $objParentPage->type == 'root')
		{
			$objPage->rootId = $objParentPage->id;
			$objPage->rootTitle = $objParentPage->pageTitle ?: $objParentPage->title;
			$objPage->domain = $objParentPage->dns;
			$objPage->rootLanguage = $objParentPage->language;
			$objPage->language = $objParentPage->language;
			$objPage->staticFiles = $objParentPage->staticFiles;
			$objPage->staticPlugins = $objParentPage->staticPlugins;
			$objPage->dateFormat = $objParentPage->dateFormat;
			$objPage->timeFormat = $objParentPage->timeFormat;
			$objPage->datimFormat = $objParentPage->datimFormat;
			$objPage->adminEmail = $objParentPage->adminEmail;

			// Store whether the root page has been published
			$time = time();
			$objPage->rootIsPublic = ($objParentPage->published && ($objParentPage->start == '' || $objParentPage->start < $time) && ($objParentPage->stop == '' || $objParentPage->stop > $time));
			$objPage->rootIsFallback = ($objParentPage->fallback != '');
		}

		// No root page found
		elseif (TL_MODE == 'FE' && $objPage->type != 'root')
		{
			header('HTTP/1.1 404 Not Found');
			\System::log('Page ID "'. $objPage->id .'" does not belong to a root page', 'Controller getPageDetails()', TL_ERROR);
			die('No root page found');
		}

		$objPage->trail = array_reverse($trail);

		// Remove insert tags from all titles (see #2853)
		$objPage->title = strip_insert_tags($objPage->title);
		$objPage->pageTitle = strip_insert_tags($objPage->pageTitle);
		$objPage->parentTitle = strip_insert_tags($objPage->parentTitle);
		$objPage->parentPageTitle = strip_insert_tags($objPage->parentPageTitle);
		$objPage->mainTitle = strip_insert_tags($objPage->mainTitle);
		$objPage->mainPageTitle = strip_insert_tags($objPage->mainPageTitle);
		$objPage->rootTitle = strip_insert_tags($objPage->rootTitle);

		// Do not cache protected pages
		if ($objPage->protected)
		{
			$objPage->cache = 0;
		}

		\Cache::set($strKey, $objPage);
		return $objPage;
	}


	/**
	 * Return all page sections as array
	 * 
	 * @return array An array of active page sections
	 */
	public static function getPageSections()
	{
		return array_merge(array('header', 'left', 'right', 'main', 'footer'), trimsplit(',', $GLOBALS['TL_CONFIG']['customSections']));
	}


	/**
	 * Return the back end themes as array
	 * 
	 * @return array An array of available back end themes
	 */
	public static function getBackendThemes()
	{
		$arrReturn = array();
		$arrThemes = scan(TL_ROOT . '/system/themes');

		foreach ($arrThemes as $strTheme)
		{
			if (strncmp($strTheme, '.', 1) === 0 || !is_dir(TL_ROOT . '/system/themes/' . $strTheme))
			{
				continue;
			}

			$arrReturn[$strTheme] = $strTheme;
		}

		return $arrReturn;
	}


	/**
	 * Return the timezones as array
	 * 
	 * @return array An array of timezones
	 */
	public static function getTimeZones()
	{
		$arrReturn = array();
		$timezones = array();

		require TL_ROOT . '/system/config/timezones.php';

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
	 * Return the languages for the TinyMCE spellchecker
	 * 
	 * @return string The TinyMCE spellchecker language string
	 */
	protected function getSpellcheckerString()
	{
		$this->loadLanguageFile('languages');

		$return = array();
		$langs = scan(TL_ROOT . '/system/modules/core/languages');
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
	 * Calculate the page status icon name based on the page parameters
	 * 
	 * @param object A page object
	 * 
	 * @return string The status icon name
	 */
	public static function getPageStatusIcon($objPage)
	{
		$sub = 0;
		$image = $objPage->type.'.gif';

		// Page not published or not active
		if (!$objPage->published || $objPage->start && $objPage->start > time() || $objPage->stop && $objPage->stop < time())
		{
			$sub += 1;
		}

		// Page hidden from menu
		if ($objPage->hide && !in_array($objPage->type, array('redirect', 'forward', 'root', 'error_403', 'error_404')))
		{
			$sub += 2;
		}

		// Page protected
		if ($objPage->protected && !in_array($objPage->type, array('root', 'error_403', 'error_404')))
		{
			$sub += 4;
		}

		// Get the image name
		if ($sub > 0)
		{
			$image = $objPage->type.'_'.$sub.'.gif';
		}

		return $image;
	}


	/**
	 * Print an article as PDF and stream it to the browser
	 * 
	 * @param object $objArticle An article object
	 */
	protected function printArticleAsPdf($objArticle)
	{
		$objArticle->headline = $objArticle->title;
		$objArticle->printable = false;

		// Generate article
		$objArticle = new \ModuleArticle($objArticle);
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
			'@<br( /)?><div class="mod_article@',
			'@href="([^"]+)(pdf=[0-9]*(&|&amp;)?)([^"]*)"@'
		);

		$arrReplace = array
		(
			'<u>$1</u>',
			'<br>$1',
			'<br>$1',
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
		require_once TL_ROOT . '/system/config/tcpdf.php';
		require_once TL_ROOT . '/system/vendor/tcpdf/tcpdf.php';

		// Create new PDF document
		$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);

		// Set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor(PDF_AUTHOR);
		$pdf->SetTitle($objArticle->title);
		$pdf->SetSubject($objArticle->title);
		$pdf->SetKeywords($objArticle->keywords);

		// Prevent font subsetting (huge speed improvement)
		$pdf->setFontSubsetting(false);

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
	 * 
	 * @param string  $strBuffer The text with the tags to be replaced
	 * @param boolean $blnCache  If false, non-cacheable tags will be replaced
	 * 
	 * @return string The text with the replaced tags
	 */
	protected function replaceInsertTags($strBuffer, $blnCache=true)
	{
		global $objPage;

		// Preserve insert tags
		if ($GLOBALS['TL_CONFIG']['disableInsertTags'])
		{
			return \String::restoreBasicEntities($strBuffer);
		}

		$tags = preg_split('/\{\{(([^\{\}]*|(?R))*)\}\}/', $strBuffer, -1, PREG_SPLIT_DELIM_CAPTURE);

		$strBuffer = '';
		static $arrCache = array();

		for ($_rit=0; $_rit<count($tags); $_rit+=3)
		{
			$strBuffer .= $tags[$_rit];
			$strTag = $tags[$_rit+1];

			// Skip empty tags
			if ($strTag == '')
			{
				continue;
			}

			// Run the replacement again if there are more tags (see #4402)
			if (strpos($strTag, '{{') !== false)
			{
				$strTag = $this->replaceInsertTags($strTag, $blnCache);
			}

			// Load the value from cache
			if (isset($arrCache[$strTag]))
			{
				$strBuffer .= $arrCache[$strTag];
				continue;
			}

			$elements = explode('::', $strTag);

			// Skip certain elements if the output will be cached
			if ($blnCache)
			{
				if ($elements[0] == 'date' || $elements[0] == 'ua' || $elements[0] == 'post' || $elements[0] == 'file' || $elements[1] == 'back' || $elements[1] == 'referer' || $elements[0] == 'request_token' || strncmp($elements[0], 'cache_', 6) === 0)
				{
					$strBuffer .= '{{' . $strTag . '}}';
					continue;
				}
			}

			$arrCache[$strTag] = '';

			// Replace the tag
			switch (strtolower($elements[0]))
			{
				// Date
				case 'date':
					$arrCache[$strTag] = $this->parseDate($elements[1] ?: $GLOBALS['TL_CONFIG']['dateFormat']);
					break;

				// Accessibility tags
				case 'lang':
					if ($elements[1] == '')
					{
						$arrCache[$strTag] = '</span>';
					}
					elseif ($objPage->outputFormat == 'xhtml')
					{
						$arrCache[$strTag] = '<span lang="' . $elements[1] . '" xml:lang="' . $elements[1] . '">';
					}
					else
					{
						$arrCache[$strTag] = $arrCache[$strTag] = '<span lang="' . $elements[1] . '">';
					}
					break;

				// E-mail addresses
				case 'email':
				case 'email_open':
				case 'email_url':
					if ($elements[1] == '')
					{
						$arrCache[$strTag] = '';
						break;
					}

					$strEmail = \String::encodeEmail($elements[1]);

					// Replace the tag
					switch (strtolower($elements[0]))
					{
						case 'email':
							$arrCache[$strTag] = '<a href="&#109;&#97;&#105;&#108;&#116;&#111;&#58;' . $strEmail . '" class="email">' . preg_replace('/\?.*$/', '', $strEmail) . '</a>';
							break;

						case 'email_open':
							$arrCache[$strTag] = '<a href="&#109;&#97;&#105;&#108;&#116;&#111;&#58;' . $strEmail . '" class="email">';
							break;

						case 'email_url':
							$arrCache[$strTag] = $strEmail;
							break;
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

						// Convert special characters (see #1890)
						$arrCache[$strTag] = specialchars($arrCache[$strTag]);
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

						$strName = $strTitle;
					}

					// External links
					elseif (strncmp($elements[1], 'http://', 7) === 0 || strncmp($elements[1], 'https://', 8) === 0)
					{
						$strUrl = $elements[1];
						$strTitle = $elements[1];
						$strName = str_replace(array('http://', 'https://'), '', $elements[1]);
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

						$objNextPage = \PageModel::findByIdOrAlias($elements[1]);

						if ($objNextPage === null)
						{
							break;
						}

						// Page type specific settings (thanks to Andreas Schempp)
						switch ($objNextPage->type)
						{
							case 'redirect':
								$strUrl = $objNextPage->url;

								if (strncasecmp($strUrl, 'mailto:', 7) === 0)
								{
									$strUrl = \String::encodeEmail($strUrl);
								}
								break;

							case 'forward':
								if ($objNextPage->jumpTo)
								{
									$objNext = $objNextPage->getRelated('jumpTo');
								}
								else
								{
									$objNext = \PageModel::findFirstPublishedRegularByPid($objNextPage->id);
								}

								if ($objNext !== null)
								{
									$strForceLang = null;

									// Check the target page language (see #4706)
									if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'])
									{
										$objNext = $this->getPageDetails($objNext); // see #3983
										$strForceLang = $objNext->language;
									}

									$strUrl = $this->generateFrontendUrl($objNext->row(), null, $strForceLang);
									break;
								}
								// DO NOT ADD A break; STATEMENT

							default:
								$strUrl = $this->generateFrontendUrl($objNextPage->row());
								break;
						}

						$strName = $objNextPage->title;
						$strTarget = $objNextPage->target ? (($objPage->outputFormat == 'xhtml') ? LINK_NEW_WINDOW : ' target="_blank"') : '';
						$strTitle = $objNextPage->pageTitle ?: $objNextPage->title;
					}

					// Replace the tag
					switch (strtolower($elements[0]))
					{
						case 'link':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s"%s>%s</a>', $strUrl, specialchars($strTitle), $strTarget, specialchars($strName));
							break;

						case 'link_open':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s"%s>', $strUrl, specialchars($strTitle), $strTarget);
							break;

						case 'link_url':
							$arrCache[$strTag] = $strUrl;
							break;

						case 'link_title':
							$arrCache[$strTag] = specialchars($strTitle);
							break;

						case 'link_target':
							$arrCache[$strTag] = $strTarget;
							break;
					}
					break;

				// Closing link tag
				case 'link_close':
					$arrCache[$strTag] = '</a>';
					break;

				// Insert article
				case 'insert_article':
					if (($strOutput = $this->getArticle($elements[1], false, true)) !== false)
					{
						$arrCache[$strTag] = $this->replaceInsertTags(ltrim($strOutput), $blnCache);
					}
					else
					{
						$arrCache[$strTag] = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['invalidPage'], $elements[1]) . '</p>';
					}
					break;

				// Insert content element
				case 'insert_content':
					$arrCache[$strTag] = $this->replaceInsertTags($this->getContentElement($elements[1]), $blnCache);
					break;

				// Insert module
				case 'insert_module':
					$arrCache[$strTag] = $this->replaceInsertTags($this->getFrontendModule($elements[1]), $blnCache);
					break;

				// Insert form
				case 'insert_form':
					$arrCache[$strTag] = $this->replaceInsertTags($this->getForm($elements[1]), $blnCache);
					break;

				// Article
				case 'article':
				case 'article_open':
				case 'article_url':
				case 'article_title':
					$objArticle = \ArticleModel::findByIdOrAlias($elements[1]);

					if ($objArticle === null)
					{
						break;
					}
					else
					{
						$strUrl = $this->generateFrontendUrl($objArticle->getRelated('pid')->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objArticle->alias)) ? $objArticle->alias : $objArticle->id));
					}

					// Replace the tag
					switch (strtolower($elements[0]))
					{
						case 'article':
							$strLink = specialchars($objArticle->title);
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">%s</a>', $strUrl, $strLink, $strLink);
							break;

						case 'article_open':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">', $strUrl, specialchars($objArticle->title));
							break;

						case 'article_url':
							$arrCache[$strTag] = $strUrl;
							break;

						case 'article_title':
							$arrCache[$strTag] = specialchars($objArticle->title);
							break;
					}
					break;

				// FAQ
				case 'faq':
				case 'faq_open':
				case 'faq_url':
				case 'faq_title':
					$objFaq = \FaqModel::findByIdOrAlias($elements[1]);

					if ($objFaq === null)
					{
						break;
					}
					else
					{
						$strUrl = $this->generateFrontendUrl($objFaq->getRelated('pid')->getRelated('jumpTo')->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/' : '/items/') . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objFaq->alias != '') ? $objFaq->alias : $objFaq->id));
					}

					// Replace the tag
					switch (strtolower($elements[0]))
					{
						case 'faq':
							$strLink = specialchars($objFaq->question);
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">%s</a>', $strUrl, $strLink, $strLink);
							break;

						case 'faq_open':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">', $strUrl, specialchars($objFaq->question));
							break;

						case 'faq_url':
							$arrCache[$strTag] = $strUrl;
							break;

						case 'faq_title':
							$arrCache[$strTag] = specialchars($objFaq->question);
							break;
					}
					break;

				// News
				case 'news':
				case 'news_open':
				case 'news_url':
				case 'news_title':
					$objNews = \NewsModel::findByIdOrAlias($elements[1]);

					if ($objNews === null)
					{
						break;
					}
					elseif ($objNews->source == 'internal')
					{
						$strUrl = $this->generateFrontendUrl($objNews->getRelated('jumpTo')->row());
					}
					elseif ($objNews->source == 'article')
					{
						$objArticle = \ArticleModel::findByPk($objNews->articleId, array('eager'=>true));
						$strUrl = $this->generateFrontendUrl($objArticle->getRelated('pid')->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objArticle->alias != '') ? $objArticle->alias : $objArticle->id));
					}
					elseif ($objNews->source == 'external')
					{
						$strUrl = $objNews->url;
					}
					else
					{
						$strUrl = $this->generateFrontendUrl($objNews->getRelated('pid')->getRelated('jumpTo')->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/' : '/items/') . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objNews->alias != '') ? $objNews->alias : $objNews->id));
					}

					// Replace the tag
					switch (strtolower($elements[0]))
					{
						case 'news':
							$strLink = specialchars($objNews->headline);
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">%s</a>', $strUrl, $strLink, $strLink);
							break;

						case 'news_open':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">', $strUrl, specialchars($objNews->headline));
							break;

						case 'news_url':
							$arrCache[$strTag] = $strUrl;
							break;

						case 'news_title':
							$arrCache[$strTag] = specialchars($objNews->headline);
							break;
					}
					break;

				// Events
				case 'event':
				case 'event_open':
				case 'event_url':
				case 'event_title':
					$objEvent = \CalendarEventsModel::findByIdOrAlias($elements[1]);

					if ($objEvent === null)
					{
						break;
					}
					elseif ($objEvent->source == 'internal')
					{
						$strUrl = $this->generateFrontendUrl($objEvent->getRelated('jumpTo')->row());
					}
					elseif ($objEvent->source == 'article')
					{
						$objArticle = \ArticleModel::findByPk($objEvent->articleId, array('eager'=>true));
						$strUrl = $this->generateFrontendUrl($objArticle->getRelated('pid')->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objArticle->alias != '') ? $objArticle->alias : $objArticle->id));
					}
					elseif ($objEvent->source == 'external')
					{
						$strUrl = $objEvent->url;
					}
					else
					{
						$strUrl = $this->generateFrontendUrl($objEvent->getRelated('pid')->getRelated('jumpTo')->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/' : '/events/') . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objEvent->alias != '') ? $objEvent->alias : $objEvent->id));
					}

					// Replace the tag
					switch (strtolower($elements[0]))
					{
						case 'event':
							$strLink = specialchars($objEvent->title);
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">%s</a>', $strUrl, $strLink, $strLink);
							break;

						case 'event_open':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">', $strUrl, specialchars($objEvent->title));
							break;

						case 'event_url':
							$arrCache[$strTag] = $strUrl;
							break;

						case 'event_title':
							$arrCache[$strTag] = specialchars($objEvent->title);
							break;
					}
					break;

				// Article teaser
				case 'article_teaser':
					$objTeaser = \ArticleModel::findByIdOrAlias($elements[1]);

					if ($objTeaser !== null)
					{
						if ($objPage->outputFormat == 'xhtml')
						{
							$arrCache[$strTag] = \String::toXhtml($this->replaceInsertTags($objTeaser->teaser), $blnCache);
						}
						else
						{
							$arrCache[$strTag] = \String::toHtml5($this->replaceInsertTags($objTeaser->teaser), $blnCache);
						}
					}
					break;

				// News teaser
				case 'news_teaser':
					$objTeaser = \NewsModel::findByIdOrAlias($elements[1]);

					if ($objTeaser !== null)
					{
						if ($objPage->outputFormat == 'xhtml')
						{
							$arrCache[$strTag] = \String::toXhtml($objTeaser->teaser);
						}
						else
						{
							$arrCache[$strTag] = \String::toHtml5($objTeaser->teaser);
						}
					}
					break;

				// Event teaser
				case 'event_teaser':
					$objTeaser = \CalendarEventsModel::findByIdOrAlias($elements[1]);

					if ($objTeaser !== null)
					{
						if ($objPage->outputFormat == 'xhtml')
						{
							$arrCache[$strTag] = \String::toXhtml($objTeaser->teaser);
						}
						else
						{
							$arrCache[$strTag] = \String::toHtml5($objTeaser->teaser);
						}
					}
					break;

				// News feed URL
				case 'news_feed':
					$objFeed = \NewsFeedModel::findByPk($elements[1]);

					if ($objFeed !== null)
					{
						$arrCache[$strTag] = $objFeed->feedBase . $objFeed->alias . '.xml';
					}
					break;

				// Calendar feed URL
				case 'calendar_feed':
					$objFeed = \CalendarFeedModel::findByPk($elements[1]);

					if ($objFeed !== null)
					{
						$arrCache[$strTag] = $objFeed->feedBase . $objFeed->alias . '.xml';
					}
					break;

				// Last update
				case 'last_update':
					$objUpdate = \Database::getInstance()->execute("SELECT MAX(tstamp) AS tc, (SELECT MAX(tstamp) FROM tl_news) AS tn, (SELECT MAX(tstamp) FROM tl_calendar_events) AS te FROM tl_content");

					if ($objUpdate->numRows)
					{
						$arrCache[$strTag] = $this->parseDate($elements[1] ?: $GLOBALS['TL_CONFIG']['datimFormat'], max($objUpdate->tc, $objUpdate->tn, $objUpdate->te));
					}
					break;

				// Version
				case 'version':
					$arrCache[$strTag] = VERSION . '.' . BUILD;
					break;

				// Request token
				case 'request_token':
					$arrCache[$strTag] = REQUEST_TOKEN;
					break;

				// POST data
				case 'post':
					$arrCache[$strTag] = \Input::post($elements[1]);
					break;

				// Mobile/desktop toggle (hide if there is no mobile layout)
				case 'toggle_view':
					if (!$objPage->mobileLayout)
					{
						$arrCache[$strTag] = '';
						break;
					}

					$strUrl = \Environment::get('request');
					$strGlue = (strpos($strUrl, '?') === false) ? '?' : '&amp;';

					if ($objPage->isMobile)
					{
						$arrCache[$strTag] = '<a href="' . $strUrl . $strGlue . 'toggle_view=desktop" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['toggleDesktop'][1]) . '">' . $GLOBALS['TL_LANG']['MSC']['toggleDesktop'][0] . '</a>';
					}
					else
					{
						$arrCache[$strTag] = '<a href="' . $strUrl . $strGlue . 'toggle_view=mobile" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['toggleMobile'][1]) . '">' . $GLOBALS['TL_LANG']['MSC']['toggleMobile'][0] . '</a>';
					}
					break;

				// Conditional tags (if)
				case 'iflng':
					if ($elements[1] != '' && $elements[1] != $objPage->language)
					{
						for ($_rit; $_rit<count($tags); $_rit+=3)
						{
							if ($tags[$_rit+1] == 'iflng')
							{
								break;
							}
						}
					}
					unset($arrCache[$strTag]);
					break;

				// Conditional tags (if not)
				case 'ifnlng':
					if ($elements[1] != '')
					{
						$langs = trimsplit(',', $elements[1]);

						if (in_array($objPage->language, $langs))
						{
							for ($_rit; $_rit<count($tags); $_rit+=3)
							{
								if ($tags[$_rit+1] == 'ifnlng')
								{
									break;
								}
							}
						}
					}
					unset($arrCache[$strTag]);
					break;

				// Environment
				case 'env':
					switch ($elements[1])
					{
						case 'host':
							$arrCache[$strTag] = \Idna::decode(\Environment::get('host'));
							break;

						case 'http_host':
							$arrCache[$strTag] = \Idna::decode(\Environment::get('httpHost'));
							break;

						case 'url':
							$arrCache[$strTag] = \Idna::decode(\Environment::get('url'));
							break;

						case 'path':
							$arrCache[$strTag] = \Idna::decode(\Environment::get('base'));
							break;

						case 'request':
							$arrCache[$strTag] = $this->getIndexFreeRequest(true);
							break;

						case 'ip':
							$arrCache[$strTag] = \Environment::get('ip');
							break;

						case 'referer':
							$arrCache[$strTag] = $this->getReferer(true);
							break;

						case 'files_url':
							$arrCache[$strTag] = TL_FILES_URL;
							break;

						case 'assets_url':
						case 'plugins_url':
						case 'script_url':
							$arrCache[$strTag] = TL_ASSETS_URL;
							break;
					}
					break;

				// Page
				case 'page':
					if ($elements[1] == 'pageTitle' && $objPage->pageTitle == '')
					{
						$elements[1] = 'title';
					}
					elseif ($elements[1] == 'parentPageTitle' && $objPage->parentPageTitle == '')
					{
						$elements[1] = 'parentTitle';
					}
					elseif ($elements[1] == 'mainPageTitle' && $objPage->mainPageTitle == '')
					{
						$elements[1] = 'mainTitle';
					}

					// Do not use specialchars() here (see #4687)
					$arrCache[$strTag] = $objPage->{$elements[1]};
					break;

				// User agent
				case 'ua':
					$ua = \Environment::get('agent');

					if ($elements[1] != '')
					{
						$arrCache[$strTag] = $ua->{$elements[1]};
					}
					else
					{
						$arrCache[$strTag] = '';
					}
					break;

				// Acronyms
				case 'acronym':
					if ($objPage->outputFormat == 'xhtml')
					{
						if ($elements[1] != '')
						{
							$arrCache[$strTag] = '<acronym title="'. $elements[1] .'">';
						}
						else
						{
							$arrCache[$strTag] = '</acronym>';
						}
						break;
					}
					// NO break;

				// Abbreviations
				case 'abbr':
					if ($elements[1] != '')
					{
						$arrCache[$strTag] = '<abbr title="'. $elements[1] .'">';
					}
					else
					{
						$arrCache[$strTag] = '</abbr>';
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
						$arrChunks = explode('?', urldecode($elements[1]), 2);
						$strSource = \String::decodeEntities($arrChunks[1]);
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

					// Handle numeric IDs (see #4805)
					if (is_numeric($strFile))
					{
						$objFile = \FilesModel::findByPk($strFile);

						if ($objFile === null)
						{
							$arrCache[$strTag] = '';
							break;
						}

						$strFile = $objFile->path;
					}
					else
					{
						// Sanitize the path
						$strFile = str_replace('../', '', $strFile);
					}

					// Check the maximum image width
					if ($GLOBALS['TL_CONFIG']['maxImageWidth'] > 0 && $width > $GLOBALS['TL_CONFIG']['maxImageWidth'])
					{
						$width = $GLOBALS['TL_CONFIG']['maxImageWidth'];
						$height = null;
					}

					// Generate the thumbnail image
					try
					{
						$src = \Image::get($strFile, $width, $height, $mode);
						$dimensions = '';

						// Add the image dimensions
						if (($imgSize = @getimagesize(TL_ROOT .'/'. rawurldecode($src))) !== false)
						{
							$dimensions = $imgSize[3];
						}

						// Generate the HTML markup
						if ($rel != '')
						{
							if (strncmp($rel, 'lightbox', 8) !== 0 || $objPage->outputFormat == 'xhtml')
							{
								$attribute = ' rel="' . $rel . '"';
							}
							else
							{
								$attribute = ' data-lightbox="' . substr($rel, 8) . '"';
							}

							$arrCache[$strTag] = '<a href="' . TL_FILES_URL . $strFile . '"' . (($alt != '') ? ' title="' . $alt . '"' : '') . $attribute . '><img src="' . TL_FILES_URL . $src . '" ' . $dimensions . ' alt="' . $alt . '"' . (($class != '') ? ' class="' . $class . '"' : '') . (($objPage->outputFormat == 'xhtml') ? ' />' : '>') . '</a>';
						}
						else
						{
							$arrCache[$strTag] = '<img src="' . TL_FILES_URL . $src . '" ' . $dimensions . ' alt="' . $alt . '"' . (($class != '') ? ' class="' . $class . '"' : '') . (($objPage->outputFormat == 'xhtml') ? ' />' : '>');
						}
					}
					catch (\Exception $e)
					{
						$arrCache[$strTag] = '';
					}
					break;

				// Files from the templates directory
				case 'file':
					$arrGet = $_GET;
					\Input::resetCache();
					$strFile = $elements[1];

					// Take arguments and add them to the $_GET array
					if (strpos($elements[1], '?') !== false)
					{
						$arrChunks = explode('?', urldecode($elements[1]));
						$strSource = \String::decodeEntities($arrChunks[1]);
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

					// Include .php, .tpl, .xhtml and .html5 files
					if (preg_match('/\.(php|tpl|xhtml|html5)$/', $strFile) && file_exists(TL_ROOT . '/templates/' . $strFile))
					{
						ob_start();
						include TL_ROOT . '/templates/' . $strFile;
						$arrCache[$strTag] = ob_get_contents();
						ob_end_clean();
					}

					$_GET = $arrGet;
					\Input::resetCache();
					break;

				// HOOK: pass unknown tags to callback functions
				default:
					if (isset($GLOBALS['TL_HOOKS']['replaceInsertTags']) && is_array($GLOBALS['TL_HOOKS']['replaceInsertTags']))
					{
						foreach ($GLOBALS['TL_HOOKS']['replaceInsertTags'] as $callback)
						{
							$this->import($callback[0]);
							$varValue = $this->$callback[0]->$callback[1]($strTag, $blnCache);

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

		return \String::restoreBasicEntities($strBuffer);
	}


	/**
	 * Replace the dynamic script tags (see #4203)
	 * 
	 * @param string $strBuffer The string with the tags to be replaced
	 * 
	 * @return string The string with the replaced tags
	 */
	public static function replaceDynamicScriptTags($strBuffer)
	{
		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['replaceDynamicScriptTags']) && is_array($GLOBALS['TL_HOOKS']['replaceDynamicScriptTags']))
		{
			foreach ($GLOBALS['TL_HOOKS']['replaceDynamicScriptTags'] as $callback)
			{
				$strBuffer = static::importStatic($callback[0])->$callback[1]($strBuffer);
			}
		}

		global $objPage;

		$arrReplace = array();
		$blnXhtml = ($objPage->outputFormat == 'xhtml');
		$strTagEnding = $blnXhtml ? ' />' : '>';
		$strScripts = '';

		// Add the internal jQuery scripts
		if (is_array($GLOBALS['TL_JQUERY']) && !empty($GLOBALS['TL_JQUERY']))
		{
			foreach (array_unique($GLOBALS['TL_JQUERY']) as $script)
			{
				$strScripts .= "\n" . trim($script) . "\n";
			}
		}

		$arrReplace['[[TL_JQUERY]]'] = $strScripts;
		$strScripts = '';

		// Add the internal MooTools scripts
		if (is_array($GLOBALS['TL_MOOTOOLS']) && !empty($GLOBALS['TL_MOOTOOLS']))
		{
			foreach (array_unique($GLOBALS['TL_MOOTOOLS']) as $script)
			{
				$strScripts .= "\n" . trim($script) . "\n";
			}
		}

		$arrReplace['[[TL_MOOTOOLS]]'] = $strScripts;
		$strScripts = '';

		// Add the syntax highlighter scripts
		if (is_array($GLOBALS['TL_HIGHLIGHTER']) && !empty($GLOBALS['TL_HIGHLIGHTER']))
		{
			$objCombiner = new \Combiner();

			foreach (array_unique($GLOBALS['TL_HIGHLIGHTER']) as $script)
			{
				$objCombiner->add($script);
			}

			$strScripts .= "\n" . '<script' . ($blnXhtml ? ' type="text/javascript"' : '') . ' src="' . $objCombiner->getCombinedFile() . '"></script>';

			if ($blnXhtml)
			{
				$strScripts .= "\n" . '<script type="text/javascript">' . "\n/* <![CDATA[ */\n" . 'SyntaxHighlighter.defaults.toolbar=false;SyntaxHighlighter.all()' . "\n/* ]]> */\n" . '</script>' . "\n";
			}
			else
			{
				$strScripts .= "\n" . '<script>SyntaxHighlighter.defaults.toolbar=false;SyntaxHighlighter.all()</script>' . "\n";
			}
		}

		$arrReplace['[[TL_HIGHLIGHTER]]'] = $strScripts;
		$strScripts = '';

		$objCombiner = new \Combiner();

		// Add the CSS framework style sheets
		if (is_array($GLOBALS['TL_FRAMEWORK_CSS']) && !empty($GLOBALS['TL_FRAMEWORK_CSS']))
		{
			foreach (array_unique($GLOBALS['TL_FRAMEWORK_CSS']) as $stylesheet)
			{
				$objCombiner->add($stylesheet);
			}
		}

		// Add the internal style sheets
		if (is_array($GLOBALS['TL_CSS']) && !empty($GLOBALS['TL_CSS']))
		{
			foreach (array_unique($GLOBALS['TL_CSS']) as $stylesheet)
			{
				list($stylesheet, $media, $mode) = explode('|', $stylesheet);

				if ($mode == 'static')
				{
					$objCombiner->add($stylesheet, filemtime(TL_ROOT . '/' . $stylesheet), $media);
				}
				else
				{
					$strScripts .= '<link' . ($blnXhtml ? ' type="text/css"' : '') . ' rel="stylesheet" href="' . static::addStaticUrlTo($stylesheet) . '"' . (($media != '' && $media != 'all') ? ' media="' . $media . '"' : '') . $strTagEnding . "\n";
				}
			}
		}

		// Add the user style sheets
		if (is_array($GLOBALS['TL_USER_CSS']) && !empty($GLOBALS['TL_USER_CSS']))
		{
			foreach (array_unique($GLOBALS['TL_USER_CSS']) as $stylesheet)
			{
				list($stylesheet, $media, $mode, $version) = explode('|', $stylesheet);

				if (!$version)
				{
					$version = filemtime(TL_ROOT . '/' . $stylesheet);
				}

				if ($mode == 'static')
				{
					$objCombiner->add($stylesheet, $version, $media);
				}
				else
				{
					$strScripts .= '<link' . ($blnXhtml ? ' type="text/css"' : '') . ' rel="stylesheet" href="' . static::addStaticUrlTo($stylesheet) . '"' . (($media != '' && $media != 'all') ? ' media="' . $media . '"' : '') . $strTagEnding . "\n";
				}
			}
		}

		// Create the aggregated style sheet
		if ($objCombiner->hasEntries())
		{
			$strScripts .= '<link' . ($blnXhtml ? ' type="text/css"' : '') . ' rel="stylesheet" href="' . $objCombiner->getCombinedFile() . '"' . $strTagEnding . "\n";
		}

		$arrReplace['[[TL_CSS]]'] = $strScripts;
		$strScripts = '';

		// Add the internal scripts
		if (is_array($GLOBALS['TL_JAVASCRIPT']) && !empty($GLOBALS['TL_JAVASCRIPT']))
		{
			$objCombiner = new \Combiner();

			foreach (array_unique($GLOBALS['TL_JAVASCRIPT']) as $javascript)
			{
				list($javascript, $mode) = explode('|', $javascript);

				if ($mode == 'static')
				{
					$objCombiner->add($javascript, filemtime(TL_ROOT . '/' . $javascript));
				}
				else
				{
					$strScripts .= '<script' . ($blnXhtml ? ' type="text/javascript"' : '') . ' src="' . static::addStaticUrlTo($javascript) . '"></script>' . "\n";
				}
			}

			// Create the aggregated script and add it before the non-static scripts (see #4890)
			if ($objCombiner->hasEntries())
			{
				$strScripts = '<script' . ($blnXhtml ? ' type="text/javascript"' : '') . ' src="' . $objCombiner->getCombinedFile() . '"></script>' . "\n" . $strScripts;
			}
		}

		// Add the internal <head> tags
		if (is_array($GLOBALS['TL_HEAD']) && !empty($GLOBALS['TL_HEAD']))
		{
			foreach (array_unique($GLOBALS['TL_HEAD']) as $head)
			{
				$strScripts .= trim($head) . "\n";
			}
		}

		$arrReplace['[[TL_HEAD]]'] = $strScripts;
		return str_replace(array_keys($arrReplace), array_values($arrReplace), $strBuffer);
	}


	/**
	 * Generate an image tag and return it as string
	 * 
	 * @param string $src        The image path
	 * @param string $alt        An optional alt attribute
	 * @param string $attributes A string of other attributes
	 * 
	 * @return string The image HTML tag
	 */
	public static function generateImage($src, $alt='', $attributes='')
	{
		$src = rawurldecode($src);

		if (strpos($src, '/') === false)
		{
			$src = 'system/themes/' . static::getTheme() . '/images/' . $src;
		}

		if (!file_exists(TL_ROOT .'/'. $src))
		{
			return '';
		}

		$size = getimagesize(TL_ROOT .'/'. $src);
		return '<img src="' . TL_FILES_URL . static::urlEncode($src) . '" ' . $size[3] . ' alt="' . specialchars($alt) . '"' . (($attributes != '') ? ' ' . $attributes : '') . '>';
	}


	/**
	 * Compile the margin format definition based on an array of values
	 * 
	 * @param array  $arrValues An array of four values and a unit
	 * @param string $strType   Either "margin" or "padding"
	 * 
	 * @return string The CSS markup
	 */
	public static function generateMargin($arrValues, $strType='margin')
	{
		$top = $arrValues['top'];
		$right = $arrValues['right'];
		$bottom = $arrValues['bottom'];
		$left = $arrValues['left'];

		// Try to shorten the definition
		if ($top != '' && $right != '' && $bottom != '' && $left != '')
		{
			if ($top == $right && $top == $bottom && $top == $left)
			{
				return $strType . ':' . $top . $arrValues['unit'] . ';';
			}
			elseif ($top == $bottom && $right == $left)
			{
				return $strType . ':' . $top . $arrValues['unit'] . ' ' . $left . $arrValues['unit'] . ';';
			}
			elseif ($top != $bottom && $right == $left)
			{
				return $strType . ':' . $top . $arrValues['unit'] . ' ' . $right . $arrValues['unit'] . ' ' . $bottom . $arrValues['unit'] . ';';
			}
			else
			{
				return $strType . ':' . $top . $arrValues['unit'] . ' ' . $right . $arrValues['unit'] . ' ' . $bottom . $arrValues['unit'] . ' ' . $left . $arrValues['unit'] . ';';
			}
		}

		$arrDir = array
		(
			'top'=>$top,
			'right'=>$right,
			'bottom'=>$bottom,
			'left'=>$left
		);

		$return = array();

		foreach ($arrDir as $k=>$v)
		{
			if ($v != '')
			{
				$return[] = $strType . '-' . $k . ':' . $v . $arrValues['unit'] . ';';
			}
		}

		return implode(' ', $return);
	}


	/**
	 * Generate an URL depending on the current rewriteURL setting
	 * 
	 * @param array  $arrRow       An array of page parameters
	 * @param string $strParams    An optional string of URL parameters
	 * @param string $strForceLang Force a certain language
	 * 
	 * @return string An URL that can be used in the front end
	 */
	public static function generateFrontendUrl(array $arrRow, $strParams=null, $strForceLang=null)
	{
		if (!$GLOBALS['TL_CONFIG']['disableAlias'])
		{
			$strLanguage = '';

			if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'])
			{
				if ($strForceLang != '')
				{
					$strLanguage = $strForceLang . '/';
				}
				elseif (isset($arrRow['language']) && $arrRow['type'] == 'root')
				{
					$strLanguage = $arrRow['language'] . '/';
				}
				elseif (TL_MODE == 'FE')
				{
					global $objPage;
					$strLanguage = $objPage->rootLanguage . '/';
				}
			}

			// Correctly handle the "index" alias (see #3961)
			if ($arrRow['alias'] == 'index' && $strParams == '')
			{
				$strUrl = ($GLOBALS['TL_CONFIG']['rewriteURL'] ? '' : 'index.php/') . $strLanguage;
			}
			else
			{
				$strUrl = ($GLOBALS['TL_CONFIG']['rewriteURL'] ? '' : 'index.php/') . $strLanguage . ($arrRow['alias'] ?: $arrRow['id']) . $strParams . $GLOBALS['TL_CONFIG']['urlSuffix'];
			}
		}
		else
		{
			$strRequest = '';

			if ($strParams != '')
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
				$strUrl = static::importStatic($callback[0])->$callback[1]($arrRow, $strParams, $strUrl);
			}
		}

		return $strUrl;
	}


	/**
	 * Convert relative URLs in href and src attributes to absolute URLs
	 * 
	 * @param string  $strContent  The text with the URLs to be converted
	 * @param string  $strBase     An optional base URL
	 * @param boolean $blnHrefOnly If true, only href attributes will be converted
	 * 
	 * @return string The text with the replaced URLs
	 */
	public static function convertRelativeUrls($strContent, $strBase='', $blnHrefOnly=false)
	{
		if ($strBase == '')
		{
			$strBase = \Environment::get('base');
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
	 * Send a file to the browser so the "save as …" dialogue opens
	 * 
	 * @param string $strFile The file path
	 */
	public static function sendFileToBrowser($strFile)
	{
		// Make sure there are no attempts to hack the file system
		if (preg_match('@^\.+@i', $strFile) || preg_match('@\.+/@i', $strFile) || preg_match('@(://)+@i', $strFile))
		{
			header('HTTP/1.1 404 Not Found');
			die('Invalid file name');
		}

		// Limit downloads to the files directory
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

		$objFile = new \File($strFile);
		$arrAllowedTypes = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

		if (!in_array($objFile->extension, $arrAllowedTypes))
		{
			header('HTTP/1.1 403 Forbidden');
			die(sprintf('File type "%s" is not allowed', $objFile->extension));
		}

		// Make sure no output buffer is active
		// @see http://ch2.php.net/manual/en/function.fpassthru.php#74080
		while (@ob_end_clean());

		// Prevent session locking (see #2804)
		session_write_close();

		// Open the "save as …" dialogue
		header('Content-Type: ' . $objFile->mime);
		header('Content-Transfer-Encoding: binary');
		header('Content-Disposition: attachment; filename="' . $objFile->basename . '"');
		header('Content-Length: ' . $objFile->filesize);
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Expires: 0');
		header('Connection: close');

		$resFile = fopen(TL_ROOT . '/' . $strFile, 'rb');
		fpassthru($resFile);
		fclose($resFile);

		// HOOK: post download callback
		if (isset($GLOBALS['TL_HOOKS']['postDownload']) && is_array($GLOBALS['TL_HOOKS']['postDownload']))
		{
			foreach ($GLOBALS['TL_HOOKS']['postDownload'] as $callback)
			{
				static::importStatic($callback[0])->$callback[1]($strFile);
			}
		}

		// Stop the script
		exit;
	}


	/**
	 * Load a set of DCA files
	 * 
	 * @param string  $strName    The table name
	 * @param boolean $blnNoCache If true, the cache will be bypassed
	 */
	protected function loadDataContainer($strName, $blnNoCache=false)
	{
		// Return if the data has been loaded already
		if (isset($GLOBALS['loadDataContainer'][$strName]) && !$blnNoCache)
		{
			return;
		}

		$strCacheFile = TL_ROOT . '/system/cache/dca/' . $strName . '.php';

		if (!$GLOBALS['TL_CONFIG']['bypassCache'] && file_exists($strCacheFile))
		{
			include $strCacheFile;
		}
		else
		{
			// Generate the cache file
			$objCacheFile = new \File('system/cache/dca/' . $strName . '.php');
			$objCacheFile->write('<?php '); // add one space to prevent the "unexpected $end" error

			// Parse all module folders
			foreach ($this->Config->getActiveModules() as $strModule)
			{
				$strFile = TL_ROOT . '/system/modules/' . $strModule . '/dca/' . $strName . '.php';

				if (file_exists($strFile))
				{
					$objCacheFile->append(static::readPhpFileWithoutTags($strFile));
					include $strFile;
				}
			}

			$objCacheFile->close();
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

		// Local configuration file
		if (file_exists(TL_ROOT . '/system/config/dcaconfig.php'))
		{
			include TL_ROOT . '/system/config/dcaconfig.php';
		}

		// Use a global cache variable to support nested calls
		$GLOBALS['loadDataContainer'][$strName] = true;
	}


	/**
	 * Convert a DCA file configuration to be used with widgets
	 * 
	 * @param array  $arrData  The field configuration array
	 * @param string $strName  The field name in the form
	 * @param mixed  $varValue The field value
	 * @param string $strField The field name in the database
	 * @param string $strTable The table name
	 * 
	 * @return array An array that can be passed to a widget
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
		$arrNew['activeRecord'] = $arrData['activeRecord'];

		// Internet Explorer does not support onchange for checkboxes and radio buttons
		if ($arrData['eval']['submitOnChange'])
		{
			if ($arrData['inputType'] == 'checkbox' || $arrData['inputType'] == 'checkboxWizard' || $arrData['inputType'] == 'radio' || $arrData['inputType'] == 'radioTable')
			{
				$arrNew['onclick'] = trim($arrNew['onclick'] . " Backend.autoSubmit('".$strTable."')");
			}
			else
			{
				$arrNew['onchange'] = trim($arrNew['onchange'] . " Backend.autoSubmit('".$strTable."')");
			}
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
			$arrNew['onclick'] = "AjaxRequest.toggleSubpalette(this, 'sub_".$strName."', '".$strField."')";
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
		elseif (isset($arrData['foreignKey']))
		{
			$arrKey = explode('.', $arrData['foreignKey'], 2);
			$objOptions = $this->Database->execute("SELECT id, " . $arrKey[1] . " AS value FROM " . $arrKey[0] . " WHERE tstamp>0 ORDER BY value");

			if ($objOptions->numRows)
			{
				$arrData['options'] = array();

				while($objOptions->next())
				{
					$arrData['options'][$objOptions->id] = $objOptions->value;
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
			$blnIsAssociative = ($arrData['eval']['isAssociative'] || array_is_assoc($arrData['options']));
			$blnUseReference = isset($arrData['reference']);

			if ($arrData['eval']['includeBlankOption'] && !$arrData['eval']['multiple'])
			{
				$strLabel = isset($arrData['eval']['blankOptionLabel']) ? $arrData['eval']['blankOptionLabel'] : '-';
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
		if ($varValue != '' && ($arrData['eval']['rgxp'] == 'date' || $arrData['eval']['rgxp'] == 'time' || $arrData['eval']['rgxp'] == 'datim'))
		{
			$objDate = new \Date($varValue);
			$arrNew['value'] = $objDate->{$arrData['eval']['rgxp']};
		}

		return $arrNew;
	}


	/**
	 * Create an initial version of a record
	 * 
	 * @param string  $strTable The table name
	 * @param integer $intId    The ID of the element to be versioned
	 */
	protected function createInitialVersion($strTable, $intId)
	{
		if (!$GLOBALS['TL_DCA'][$strTable]['config']['enableVersioning'])
		{
			return;
		}

		$objVersion = $this->Database->prepare("SELECT COUNT(*) AS count FROM tl_version WHERE fromTable=? AND pid=?")
									 ->limit(1)
									 ->executeUncached($strTable, $intId);

		if ($objVersion->count < 1)
		{
			$this->createNewVersion($strTable, $intId);
		}
	}


	/**
	 * Create a new version of a record
	 * 
	 * @param string  $strTable The table name
	 * @param integer $intId    The ID of the element to be versioned
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

		// Get the new record
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

		if ($objVersion->version !== null)
		{
			$intVersion = $objVersion->version + 1;
		}

		$strDescription = '';

		if (isset($objRecord->title))
		{
			$strDescription = $objRecord->title;
		}
		elseif (isset($objRecord->name))
		{
			$strDescription = $objRecord->name;
		}
		elseif (isset($objRecord->headline))
		{
			$strDescription = $objRecord->headline;
		}
		elseif (isset($objRecord->selector))
		{
			$strDescription = $objRecord->selector;
		}

		$strUrl = \Environment::get('request');

		// Do not save the URL if the visibility is toggled via Ajax
		if (preg_match('/&(amp;)?state=/', $strUrl))
		{
			$strUrl = '';
		}

		$this->Database->prepare("UPDATE tl_version SET active='' WHERE pid=? AND fromTable=?")
					   ->execute($intId, $strTable);

		$this->Database->prepare("INSERT INTO tl_version (pid, tstamp, version, fromTable, username, userid, description, editUrl, active, data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?)")
					   ->execute($intId, time(), $intVersion, $strTable, $this->User->username, $this->User->id, $strDescription, $strUrl, serialize($objRecord->row()));
	}


	/**
	 * Redirect to a front end page
	 * 
	 * @param integer $intPage    The page ID
	 * @param mixed   $varArticle An optional article alias
	 * @param boolean $blnReturn  If true, return the URL and don't redirect
	 * 
	 * @return string The URL of the target page
	 */
	protected function redirectToFrontendPage($intPage, $varArticle=null, $blnReturn=false)
	{
		if (($intPage = intval($intPage)) <= 0)
		{
			return '';
		}

		$strDomain = \Environment::get('base');
		$objPage = $this->getPageDetails($intPage);

		if ($objPage->domain != '')
		{
			$strDomain = (\Environment::get('ssl') ? 'https://' : 'http://') . $objPage->domain . TL_PATH . '/';
		}

		if ($varArticle !== null)
		{
			$varArticle = '/articles/' . $varArticle;
		}

		$strUrl = $strDomain . $this->generateFrontendUrl($objPage->row(), $varArticle, $objPage->language);

		if (!$blnReturn)
		{
			$this->redirect($strUrl);
		}

		return $strUrl;
	}


	/**
	 * Get the parent records of an entry and return them as string which can
	 * be used in a log message
	 * 
	 * @param string  $strTable The table name
	 * @param integer $intId    The record ID
	 * 
	 * @return string A string that can be used in a log message
	 */
	protected function getParentEntries($strTable, $intId)
	{
		// No parent table
		if (!isset($GLOBALS['TL_DCA'][$strTable]['config']['ptable']))
		{
			return '';
		}

		$arrParent = array();

		do
		{
			// Get the pid
			$objParent = $this->Database->prepare("SELECT pid FROM " . $strTable . " WHERE id=?")
										->limit(1)
										->execute($intId);

			if ($objParent->numRows < 1)
			{
				break;
			}

			// Store the parent table information
			$strTable = $GLOBALS['TL_DCA'][$strTable]['config']['ptable'];
			$intId = $objParent->pid;

			// Add the log entry
			$arrParent[] = $strTable .'.id=' . $intId;

			// Load the data container of the parent table
			$this->loadDataContainer($strTable);
		}
		while ($intId && isset($GLOBALS['TL_DCA'][$strTable]['config']['ptable']));

		if (empty($arrParent))
		{
			return '';
		}

		return ' (parent records: ' . implode(', ', $arrParent) . ')';
	}


	/**
	 * Take an array of file paths and eliminate the nested ones
	 * 
	 * @param array $arrPaths The array of file paths
	 * 
	 * @return array The file paths array without the nested paths
	 */
	protected function eliminateNestedPaths($arrPaths)
	{
		if (!is_array($arrPaths) || empty($arrPaths))
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
	 * Take an array of pages and eliminate the nested ones
	 * 
	 * @param array   $arrPages   The array of page IDs
	 * @param string  $strTable   The table name
	 * @param boolean $blnSorting True if the table has a sorting field
	 * 
	 * @return array The page IDs array without the nested IDs
	 */
	protected function eliminateNestedPages($arrPages, $strTable=null, $blnSorting=false)
	{
		if (!is_array($arrPages) || empty($arrPages))
		{
			return array();
		}

		if (!$strTable)
		{
			$strTable = 'tl_page';
		}

		// Thanks to Andreas Schempp (see #2475 and #3423)
		$arrPages = array_intersect($this->Database->getChildRecords(0, $strTable, $blnSorting), $arrPages);
		$arrPages = array_values(array_diff($arrPages, $this->Database->getChildRecords($arrPages, $strTable, $blnSorting)));

		return $arrPages;
	}


	/**
	 * Return a "selected" attribute if the current option is selected
	 * 
	 * @param string $strName  The option name
	 * @param mixed  $varValue The option value
	 * 
	 * @return string The attribute or an empty string
	 */
	public static function optionSelected($strName, $varValue)
	{
		if ($strName === '')
		{
			return '';
		}

		$attribute = ' selected';

		if (TL_MODE == 'FE')
		{
			global $objPage;

			if ($objPage->outputFormat == 'xhtml')
			{
				$attribute = ' selected="selected"';
			}
		}

		return (is_array($varValue) ? in_array($strName, $varValue) : $strName == $varValue) ? $attribute : '';
	}


	/**
	 * Return a "checked" attribute if the current option is checked
	 * 
	 * @param string $strName  The option name
	 * @param mixed  $varValue The option value
	 * 
	 * @return string The attribute or an empty string
	 */
	public static function optionChecked($strName, $varValue)
	{
		$attribute = ' checked';

		if (TL_MODE == 'FE')
		{
			global $objPage;

			if ($objPage->outputFormat == 'xhtml')
			{
				$attribute = ' checked="checked"';
			}
		}

		return (is_array($varValue) ? in_array($strName, $varValue) : $strName == $varValue) ? $attribute : '';
	}


	/**
	 * Find a content element in the TL_CTE array and return the class name
	 * 
	 * @param string $strName The content element name
	 * 
	 * @return string The class name
	 */
	public static function findContentElement($strName)
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
	 * Find a front end module in the FE_MOD array and return the class name
	 * 
	 * @param string $strName The front end module name
	 * 
	 * @return string The class name
	 */
	public static function findFrontendModule($strName)
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
	 * Remove old XML files from the share directory
	 * 
	 * @param boolean $blnReturn If true, only return the finds and don't delete
	 * 
	 * @return array An array of old XML files
	 * 
	 * @deprecated Use Automator::purgeXmlFiles() instead
	 */
	protected function removeOldFeeds($blnReturn=false)
	{
		$this->import('Automator');
		$this->Automator->purgeXmlFiles($blnReturn);
	}


	/**
	 * Add an image to a template
	 * 
	 * @param object  $objTemplate   The template object to add the image to
	 * @param array   $arrItem       The element or module as array
	 * @param integer $intMaxWidth   An optional maximum width of the image
	 * @param string  $strLightboxId An optional lightbox ID
	 */
	public static function addImageToTemplate($objTemplate, $arrItem, $intMaxWidth=null, $strLightboxId=null)
	{
		global $objPage;

		$size = deserialize($arrItem['size']);
		$imgSize = getimagesize(TL_ROOT .'/'. $arrItem['singleSRC']);

		if ($intMaxWidth === null)
		{
			$intMaxWidth = (TL_MODE == 'BE') ? 320 : $GLOBALS['TL_CONFIG']['maxImageWidth'];
		}

		// Provide an ID for single lightbox images in HTML5 (see #3742)
		if ($strLightboxId === null && $arrItem['fullsize'])
		{
			if ($objPage->outputFormat == 'xhtml')
			{
				$strLightboxId = 'lightbox';
			}
			else
			{
				$strLightboxId = 'lightbox[' . substr(md5($objTemplate->getName() . '_' . $arrItem['id']), 0, 6) . ']';
			}
		}

		// Store the original dimensions
		$objTemplate->width = $imgSize[0];
		$objTemplate->height = $imgSize[1];

		// Adjust the image size
		if ($intMaxWidth > 0 && ($size[0] > $intMaxWidth || (!$size[0] && !$size[1] && $imgSize[0] > $intMaxWidth)))
		{
			$arrMargin = deserialize($arrItem['imagemargin']);

			// Subtract margins
			if (is_array($arrMargin) && $arrMargin['unit'] == 'px')
			{
				$intMaxWidth = $intMaxWidth - $arrMargin['left'] - $arrMargin['right'];
			}

			// See #2268 (thanks to Thyon)
			$ratio = ($size[0] && $size[1]) ? $size[1] / $size[0] : $imgSize[1] / $imgSize[0];

			$size[0] = $intMaxWidth;
			$size[1] = floor($intMaxWidth * $ratio);
		}

		$src = \Image::get($arrItem['singleSRC'], $size[0], $size[1], $size[2]);

		// Image dimensions
		if (($imgSize = @getimagesize(TL_ROOT .'/'. rawurldecode($src))) !== false)
		{
			$objTemplate->arrSize = $imgSize;
			$objTemplate->imgSize = ' ' . $imgSize[3];
		}

		// Float image
		if ($arrItem['floating'] != '')
		{
			$objTemplate->floatClass = ' float_' . $arrItem['floating'];

			// Only float:left and float:right are supported (see #4758)
			if ($arrItem['floating'] == 'left' || $arrItem['floating'] == 'right')
			{
				$objTemplate->float = ' float:' . $arrItem['floating'] . ';';
			}
		}

		// Image link
		if ($arrItem['imageUrl'] != '' && TL_MODE == 'FE')
		{
			$objTemplate->href = $arrItem['imageUrl'];
			$objTemplate->attributes = '';

			if ($arrItem['fullsize'])
			{
				// Open images in the lightbox
				if (preg_match('/\.(jpe?g|gif|png)$/', $arrItem['imageUrl']))
				{
					// Do not add the TL_FILES_URL to external URLs (see #4923)
					if (strncmp($arrItem['imageUrl'], 'http://', 7) !== 0 && strncmp($arrItem['imageUrl'], 'https://', 8) !== 0)
					{
						$objTemplate->href = TL_FILES_URL . static::urlEncode($arrItem['imageUrl']);
					}

					$objTemplate->attributes = ($objPage->outputFormat == 'xhtml') ? ' rel="' . $strLightboxId . '"' : ' data-lightbox="' . substr($strLightboxId, 9, -1) . '"';
				}
				else
				{
					$objTemplate->attributes = ($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"';
				}
			}
		}

		// Fullsize view
		elseif ($arrItem['fullsize'] && TL_MODE == 'FE')
		{
			$objTemplate->href = TL_FILES_URL . static::urlEncode($arrItem['singleSRC']);
			$objTemplate->attributes = ($objPage->outputFormat == 'xhtml') ? ' rel="' . $strLightboxId . '"' : ' data-lightbox="' . substr($strLightboxId, 9, -1) . '"';
		}

		// Do not urlEncode() here because getImage() already does (see #3817)
		$objTemplate->src = TL_FILES_URL . $src;
		$objTemplate->alt = specialchars($arrItem['alt']);
		$objTemplate->title = specialchars($arrItem['title']);
		$objTemplate->linkTitle = $objTemplate->title;
		$objTemplate->fullsize = $arrItem['fullsize'] ? true : false;
		$objTemplate->addBefore = ($arrItem['floating'] != 'below');
		$objTemplate->margin = static::generateMargin(deserialize($arrItem['imagemargin']), 'padding');
		$objTemplate->caption = $arrItem['caption'];
		$objTemplate->singleSRC = $arrItem['singleSRC'];
		$objTemplate->addImage = true;
	}


	/**
	 * Add enclosures to a template
	 * 
	 * @param object $objTemplate The template object to add the enclosures to
	 * @param array  $arrItem     The element or module as array
	 */
	public static function addEnclosuresToTemplate($objTemplate, $arrItem)
	{
		$arrEnclosures = deserialize($arrItem['enclosure']);

		if (!is_array($arrEnclosures) || empty($arrEnclosures))
		{
			return;
		}

		// Check for version 3 format
		if (!is_numeric($arrEnclosures[0]))
		{
			foreach (array('details', 'answer', 'text') as $key)
			{
				if (isset($objTemplate->$key))
				{
					$objTemplate->$key = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
			}

			return;
		}

		$objFiles = \FilesModel::findMultipleByIds($arrEnclosures);

		if ($objFiles === null)
		{
			return;
		}

		$file = \Input::get('file', true);

		// Send the file to the browser
		if ($file != '')
		{
			while ($objFiles->next())
			{
				if ($file == $objFiles->path)
				{
					static::sendFileToBrowser($file);
				}
			}

			// Do not send a 404 header (see #5178)
		}

		$arrEnclosures = array();
		$allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

		// Add download links
		while ($objFiles->next())
		{
			if ($objFiles->type == 'file')
			{
				if (!in_array($objFiles->extension, $allowedDownload))
				{
					continue;
				}

				$objFile = new \File($objFiles->path);

				$arrEnclosures[] = array
				(
					'link'      => $objFiles->name,
					'filesize'  => static::getReadableSize($objFile->filesize),
					'title'     => ucfirst(str_replace('_', ' ', $objFile->filename)),
					'href'      => \Environment::get('request') . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos(\Environment::get('request'), '?') !== false) ? '&amp;' : '?') . 'file=' . static::urlEncode($objFiles->path),
					'enclosure' => $objFiles->path,
					'icon'      => TL_FILES_URL . 'system/themes/' . static::getTheme() . '/images/' . $objFile->icon,
					'mime'      => $objFile->mime
				);
			}
		}

		$objTemplate->enclosure = $arrEnclosures;
	}


	/**
	 * Set the static URL constants
	 * 
	 * @param object $objPage An optional page object
	 */
	public static function setStaticUrls($objPage=null)
	{
		if (defined('TL_FILES_URL'))
		{
			return;
		}

		global $objPage;

		$arrConstants = array
		(
			'staticFiles'   => 'TL_FILES_URL',
			'staticPlugins' => 'TL_ASSETS_URL'
		);

		foreach ($arrConstants as $strKey=>$strConstant)
		{
			$url = ($objPage !== null) ? $objPage->$strKey : $GLOBALS['TL_CONFIG'][$strKey];

			if ($url == '' || $GLOBALS['TL_CONFIG']['debugMode'])
			{
				define($strConstant, '');
			}
			else
			{
				if (\Environment::get('ssl'))
				{
					$url = str_replace('http://', 'https://', $url);
				}

				define($strConstant, $url . TL_PATH . '/');
			}
		}

		// Backwards compatibility
		define('TL_SCRIPT_URL', TL_ASSETS_URL);
		define('TL_PLUGINS_URL', TL_ASSETS_URL);
	}


	/**
	 * Add a static URL to a script
	 * 
	 * @param string $script The script path
	 * 
	 * @return string The script path with the static URL
	 */
	public static function addStaticUrlTo($script)
	{
		// The feature is not used
		if (TL_ASSETS_URL == '')
		{
			return $script;
		}

		// Absolut URLs
		if (preg_match('@^https?://@', $script))
		{
			return $script;
		}

		return TL_ASSETS_URL . $script;
	}


	/**
	 * Return true if a class exists (tries to autoload the class)
	 * 
	 * @param string $strClass The class name
	 * 
	 * @return boolean True if the class exists
	 * 
	 * @deprecated Use the PHP function class_exists() instead
	 */
	protected function classFileExists($strClass)
	{
		return class_exists($strClass);
	}


	/**
	 * Restore basic entities
	 * 
	 * @param string $strBuffer The string with the tags to be replaced
	 * 
	 * @return string The string with the original entities
	 * 
	 * @deprecated Use String::restoreBasicEntities() instead
	 */
	public static function restoreBasicEntities($strBuffer)
	{
		return \String::restoreBasicEntities($strBuffer);
	}


	/**
	 * Resize an image and crop it if necessary
	 * 
	 * @param string  $image  The image path
	 * @param integer $width  The target width
	 * @param integer $height The target height
	 * @param string  $mode   An optional resize mode
	 * 
	 * @return boolean True if the image has been resized correctly
	 * 
	 * @deprecated Use Image::resize() instead
	 */
	protected function resizeImage($image, $width, $height, $mode='')
	{
		return \Image::resize($image, $width, $height, $mode);
	}


	/**
	 * Resize an image and crop it if necessary
	 * 
	 * @param string  $image  The image path
	 * @param integer $width  The target width
	 * @param integer $height The target height
	 * @param string  $mode   An optional resize mode
	 * @param string  $target An optional target to be replaced
	 * @param boolean $force  Override existing target images
	 * 
	 * @return string|null The image path or null
	 * 
	 * @deprecated Use Image::get() instead
	 */
	protected function getImage($image, $width, $height, $mode='', $target=null, $force=false)
	{
		return \Image::get($image, $width, $height, $mode, $target, $force);
	}


	/**
	 * Return true for backwards compatibility (see #3218)
	 * 
	 * @return boolean
	 * 
	 * @deprecated Specify 'datepicker'=>true in your DCA file instead
	 */
	protected function getDatePickerString()
	{
		return true;
	}


	/**
	 * Return the installed back end languages as array
	 * 
	 * @return array An array of available back end languages
	 * 
	 * @deprecated Use System::getLanguages(true) instead
	 */
	protected function getBackendLanguages()
	{
		return $this->getLanguages(true);
	}


	/**
	 * Parse simple tokens that can be used to personalize newsletters
	 * 
	 * @param string $strBuffer The text with the tokens to be replaced
	 * @param array  $arrData   The replacement data as array
	 * 
	 * @return string The text with the replaced tokens
	 * 
	 * @deprecated Use String::parseSimpleTokens() instead
	 */
	protected function parseSimpleTokens($strBuffer, $arrData)
	{
		return \String::parseSimpleTokens($strBuffer, $arrData);
	}


	/**
	 * Return the IDs of all child records of a particular record (see #2475)
	 * 
	 * @author Andreas Schempp
	 * 
	 * @param mixed   $arrParentIds An array of parent IDs
	 * @param string  $strTable     The table name
	 * @param boolean $blnSorting   True if the table has a sorting field
	 * @param array   $arrReturn    The array to be returned
	 * @param string  $strWhere     Additional WHERE condition
	 * 
	 * @return array An array of child record IDs
	 * 
	 * @deprecated Use Database::getChildRecords() instead
	 */
	protected function getChildRecords($arrParentIds, $strTable, $blnSorting=false, $arrReturn=array(), $strWhere='')
	{
		return $this->Database->getChildRecords($arrParentIds, $strTable, $blnSorting, $arrReturn, $strWhere);
	}


	/**
	 * Return the IDs of all parent records of a particular record
	 * 
	 * @param integer $intId    The ID of the record
	 * @param string  $strTable The table name
	 * 
	 * @return array An array of parent record IDs
	 * 
	 * @deprecated Use Database::getParentRecords() instead
	 */
	protected function getParentRecords($intId, $strTable)
	{
		return $this->Database->getParentRecords($intId, $strTable);
	}
}
