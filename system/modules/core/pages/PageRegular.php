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
 * Class PageRegular
 *
 * Provide methods to handle a regular front end page.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class PageRegular extends \Frontend
{

	/**
	 * Generate a regular page
	 * @param object
	 */
	public function generate($objPage)
	{
		$GLOBALS['TL_KEYWORDS'] = '';
		$GLOBALS['TL_LANGUAGE'] = $objPage->language;

		$this->loadLanguageFile('default');

		// Static URLs
		$this->setStaticUrls();

		// Get the page layout
		$objLayout = $this->getPageLayout($objPage);
		$objPage->template = $objLayout->template ?: 'fe_page';
		$objPage->templateGroup = $objLayout->getRelated('pid')->templates;

		// Store the output format
		list($strFormat, $strVariant) = explode('_', $objLayout->doctype);
		$objPage->outputFormat = $strFormat;
		$objPage->outputVariant = $strVariant;

		// Initialize the template
		$this->createTemplate($objPage, $objLayout);

		// Initialize modules and sections
		$arrCustomSections = array();
		$arrSections = array('header', 'left', 'right', 'main', 'footer');
		$arrModules = deserialize($objLayout->modules);

		// Get all modules in a single DB query
		$arrModuleIds = array_map(function($arr) { return $arr['mod']; }, $arrModules);
		$objModules = \ModuleModel::findMultipleByIds($arrModuleIds);

		if ($objModules !== null || $arrModules[0]['mod'] == 0) // see #4137
		{
			$arrMapper = array();

			// Create a mapper array in case a module is included more than once (see #4849)
			if ($objModules !== null)
			{
				while ($objModules->next())
				{
					$arrMapper[$objModules->id] = $objModules->current();
				}
			}

			foreach ($arrModules as $arrModule)
			{
				// Replace the module ID with the module model
				if ($arrModule['mod'] > 0 && isset($arrMapper[$arrModule['mod']]))
				{
					$arrModule['mod'] = $arrMapper[$arrModule['mod']];
				}

				// Generate the modules
				if (in_array($arrModule['col'], $arrSections))
				{
					// Filter active sections (see #3273)
					if ($arrModule['col'] == 'header' && $objLayout->rows != '2rwh' && $objLayout->rows != '3rw')
					{
						continue;
					}
					if ($arrModule['col'] == 'left' && $objLayout->cols != '2cll' && $objLayout->cols != '3cl')
					{
						continue;
					}
					if ($arrModule['col'] == 'right' && $objLayout->cols != '2clr' && $objLayout->cols != '3cl')
					{
						continue;
					}
					if ($arrModule['col'] == 'footer' && $objLayout->rows != '2rwf' && $objLayout->rows != '3rw')
					{
						continue;
					}

					$this->Template->$arrModule['col'] .= $this->getFrontendModule($arrModule['mod'], $arrModule['col']);
				}
				else
				{
					$arrCustomSections[$arrModule['col']] .= $this->getFrontendModule($arrModule['mod'], $arrModule['col']);
				}
			}
		}

		$this->Template->sections = $arrCustomSections;

		// HOOK: modify the page or layout object
		if (isset($GLOBALS['TL_HOOKS']['generatePage']) && is_array($GLOBALS['TL_HOOKS']['generatePage']))
		{
			foreach ($GLOBALS['TL_HOOKS']['generatePage'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($objPage, $objLayout, $this);
			}
		}

		// Set the page title and description AFTER the modules have been generated
		$this->Template->mainTitle = $objPage->rootTitle;
		$this->Template->pageTitle = $objPage->pageTitle ?: $objPage->title;

		// Meta robots tag
		$this->Template->robots = $objPage->robots ?: 'index,follow';

		// Remove shy-entities (see #2709)
		$this->Template->mainTitle = str_replace('[-]', '', $this->Template->mainTitle);
		$this->Template->pageTitle = str_replace('[-]', '', $this->Template->pageTitle);

		// Assign the title (backwards compatibility)
		$this->Template->title = $this->Template->mainTitle . ' - ' . $this->Template->pageTitle;
		$this->Template->description = str_replace(array("\n", "\r", '"'), array(' ' , '', ''), $objPage->description);

		// Body onload and body classes
		$this->Template->onload = trim($objLayout->onload);
		$this->Template->class = trim($objLayout->cssClass . ' ' . $objPage->cssClass);

		// HOOK: extension "bodyclass"
		if (in_array('bodyclass', $this->Config->getActiveModules()))
		{
			if (strlen($objPage->cssBody))
			{
				$this->Template->class .= ' ' . $objPage->cssBody;
			}
		}

		// Execute AFTER the modules have been generated and create footer scripts first
		$this->createFooterScripts($objLayout);
		$this->createHeaderScripts($objPage, $objLayout);

		// Print the template to the screen
		$this->Template->output();
	}


	/**
	 * Get a page layout and return it as database result object
	 * @param \Model
	 * @return \Model
	 */
	protected function getPageLayout($objPage)
	{
		$blnMobile = ($objPage->mobileLayout && \Environment::get('agent')->mobile);

		// Set the cookie
		if (isset($_GET['toggle_view']))
		{
			if (\Input::get('toggle_view') == 'mobile')
			{
				$this->setCookie('TL_VIEW', 'mobile', 0);
			}
			else
			{
				$this->setCookie('TL_VIEW', 'desktop', 0);
			}

			$this->redirect($this->getReferer());
		}

		// Override the autodetected value
		if (\Input::cookie('TL_VIEW') == 'mobile' && $objPage->mobileLayout)
		{
			$blnMobile = true;
		}
		elseif (\Input::cookie('TL_VIEW') == 'desktop')
		{
			$blnMobile = false;
		}

		$intId = $blnMobile ? $objPage->mobileLayout : $objPage->layout;
		$objLayout = \LayoutModel::findByPk($intId);

		// Die if there is no layout
		if ($objLayout === null)
		{
			header('HTTP/1.1 501 Not Implemented');
			$this->log('Could not find layout ID "' . $intId . '"', 'PageRegular getPageLayout()', TL_ERROR);
			die('No layout specified');
		}

		$objPage->hasJQuery = $objLayout->addJQuery;
		$objPage->hasMooTools = $objLayout->addMooTools;
		$objPage->isMobile = $blnMobile;

		return $objLayout;
	}


	/**
	 * Create a new template
	 * @param object
	 * @param object
	 */
	protected function createTemplate($objPage, $objLayout)
	{
		$blnXhtml = ($objPage->outputFormat == 'xhtml');
		$this->Template = new \FrontendTemplate($objPage->template);

		// Generate the DTD
		if ($blnXhtml)
		{
			if ($objPage->outputVariant == 'strict')
			{
				$this->Template->doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\n";
			}
			else
			{
				$this->Template->doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
			}
		}

		$strFramework = '';
		$arrFramework = deserialize($objLayout->framework);

		$this->Template->viewport = '';
		$this->Template->framework = '';

		// Generate the CSS framework
		if (is_array($arrFramework) && in_array('layout.css', $arrFramework))
		{
			$this->Template->viewport = '<meta name="viewport" content="width=device-width,initial-scale=1.0"' . ($blnXhtml ? ' />' : '>') . "\n";

			// Wrapper
			if ($objLayout->static)
			{
				$arrSize = deserialize($objLayout->width);

				if (isset($arrSize['value']) && $arrSize['value'] != '' && $arrSize['value'] >= 0)
				{
					$arrMargin = array('left'=>'0 auto 0 0', 'center'=>'0 auto', 'right'=>'0 0 0 auto');
					$strFramework .= sprintf('#wrapper{width:%s;margin:%s}', $arrSize['value'] . $arrSize['unit'], $arrMargin[$objLayout->align]);
				}
			}

			// Header
			if ($objLayout->rows == '2rwh' || $objLayout->rows == '3rw')
			{
				$arrSize = deserialize($objLayout->headerHeight);

				if (isset($arrSize['value']) && $arrSize['value'] != '' && $arrSize['value'] >= 0)
				{
					$strFramework .= sprintf('#header{height:%s}', $arrSize['value'] . $arrSize['unit']);
				}
			}

			$strContainer = '';

			// Left column
			if ($objLayout->cols == '2cll' || $objLayout->cols == '3cl')
			{
				$arrSize = deserialize($objLayout->widthLeft);

				if (isset($arrSize['value']) && $arrSize['value'] != '' && $arrSize['value'] >= 0)
				{
					$strFramework .= sprintf('#left{width:%s;right:%s}', $arrSize['value'] . $arrSize['unit'], $arrSize['value'] . $arrSize['unit']);
					$strContainer .= sprintf('padding-left:%s;', $arrSize['value'] . $arrSize['unit']);
				}
			}

			// Right column
			if ($objLayout->cols == '2clr' || $objLayout->cols == '3cl')
			{
				$arrSize = deserialize($objLayout->widthRight);

				if (isset($arrSize['value']) && $arrSize['value'] != '' && $arrSize['value'] >= 0)
				{
					$strFramework .= sprintf('#right{width:%s}', $arrSize['value'] . $arrSize['unit']);
					$strContainer .= sprintf('padding-right:%s;', $arrSize['value'] . $arrSize['unit']);
				}
			}

			// Main column
			if ($strContainer != '')
			{
				$strFramework .= sprintf('#container{%s}', substr($strContainer, 0, -1));
			}

			// Footer
			if ($objLayout->rows == '2rwf' || $objLayout->rows == '3rw')
			{
				$arrSize = deserialize($objLayout->footerHeight);

				if (isset($arrSize['value']) && $arrSize['value'] != '' && $arrSize['value'] >= 0)
				{
					$strFramework .= sprintf('#footer{height:%s}', $arrSize['value'] . $arrSize['unit']);
				}
			}

			// Add the layout specific CSS
			if ($strFramework != '')
			{
				if ($blnXhtml)
				{
					$this->Template->framework = '<style type="text/css">' . "\n/* <![CDATA[ */\n" . $strFramework . "\n/* ]]> */\n</style>\n";
				}
				else
				{
					$this->Template->framework = '<style>' . $strFramework . "</style>\n";
				}
			}
		}

		$this->Template->mooScripts = '';

		// jQuery scripts
		if ($objLayout->addJQuery)
		{
			if ($objLayout->jSource == 'j_googleapis' || $objLayout->jSource == 'j_fallback')
			{
				$protocol = \Environment::get('ssl') ? 'https://' : 'http://';
				$this->Template->mooScripts .= '<script' . ($blnXhtml ? ' type="text/javascript"' : '') . ' src="' . $protocol . 'ajax.googleapis.com/ajax/libs/jquery/' . JQUERY . '/jquery.min.js"></script>' . "\n";

				// Local fallback (thanks to DyaGa)
				if ($objLayout->jSource == 'j_fallback')
				{
					if ($blnXhtml)
					{
						$this->Template->mooScripts .= '<script type="text/javascript">' . "\n/* <![CDATA[ */\n" . 'window.jQuery || document.write(\'<script src="' . TL_ASSETS_URL . 'assets/jquery/core/' . JQUERY . '/jquery.min.js">\x3C/script>\')' . "\n/* ]]> */\n" . '</script>' . "\n";
					}
					else
					{
						$this->Template->mooScripts .= '<script>window.jQuery || document.write(\'<script src="' . TL_ASSETS_URL . 'assets/jquery/core/' . JQUERY . '/jquery.min.js">\x3C/script>\')</script>' . "\n";
					}
				}
			}
			else
			{
				$GLOBALS['TL_JAVASCRIPT'][] = 'assets/jquery/core/' . JQUERY . '/jquery.min.js|static';
			}
		}

		// MooTools scripts
		if ($objLayout->addMooTools)
		{
			if ($objLayout->mooSource == 'moo_googleapis' || $objLayout->mooSource == 'moo_fallback')
			{
				$protocol = \Environment::get('ssl') ? 'https://' : 'http://';
				$this->Template->mooScripts .= '<script' . ($blnXhtml ? ' type="text/javascript"' : '') . ' src="' . $protocol . 'ajax.googleapis.com/ajax/libs/mootools/' . MOOTOOLS . '/mootools-yui-compressed.js"></script>' . "\n";

				// Local fallback (thanks to DyaGa)
				if ($objLayout->mooSource == 'moo_fallback')
				{
					if ($blnXhtml)
					{
						$this->Template->mooScripts .= '<script type="text/javascript">' . "\n/* <![CDATA[ */\n" . 'window.MooTools || document.write(\'<script src="' . TL_ASSETS_URL . 'assets/mootools/core/' . MOOTOOLS . '/mootools-core.js">\x3C/script>\')' . "\n/* ]]> */\n" . '</script>' . "\n";
					}
					else
					{
						$this->Template->mooScripts .= '<script>window.MooTools || document.write(\'<script src="' . TL_ASSETS_URL . 'assets/mootools/core/' . MOOTOOLS . '/mootools-core.js">\x3C/script>\')</script>' . "\n";
					}
				}

				$GLOBALS['TL_JAVASCRIPT'][] = 'assets/mootools/core/' . MOOTOOLS . '/mootools-more.js|static';
				$GLOBALS['TL_JAVASCRIPT'][] = 'assets/mootools/core/' . MOOTOOLS . '/mootools-mobile.js|static';
			}
			else
			{
				$GLOBALS['TL_JAVASCRIPT'][] = 'assets/mootools/core/' . MOOTOOLS . '/mootools.js|static';
			}
		}

		// Load MooTools core for the debug bar and the command scheduler (see #5195)
		if (!$objLayout->addJQuery && !$objLayout->addMooTools)
		{
			if ($GLOBALS['TL_CONFIG']['debugMode'])
			{
				$GLOBALS['TL_JAVASCRIPT'][] = 'assets/mootools/core/' . MOOTOOLS . '/mootools-core.js|static';
			}
			elseif (!$GLOBALS['TL_CONFIG']['disableCron'])
			{
				$GLOBALS['TL_JAVASCRIPT'][] = 'assets/mootools/core/' . MOOTOOLS . '/mootools-request.js|static';
			}
		}

		// Initialize the sections
		$this->Template->header = '';
		$this->Template->left = '';
		$this->Template->main = '';
		$this->Template->right = '';
		$this->Template->footer = '';

		// Initialize the custom layout sections
		$this->Template->sections = array();
		$this->Template->sPosition = $objLayout->sPosition;

		// Default settings
		$this->Template->layout = $objLayout;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->base = \Environment::get('base');
		$this->Template->disableCron = $GLOBALS['TL_CONFIG']['disableCron'];
		$this->Template->cronTimeout = $this->getCronTimeout();
	}


	/**
	 * Create all header scripts
	 * @param object
	 * @param object
	 * @throws \Exception
	 */
	protected function createHeaderScripts($objPage, $objLayout)
	{
		$strStyleSheets = '';
		$strCcStyleSheets = '';
		$arrStyleSheets = deserialize($objLayout->stylesheet);
		$blnXhtml = ($objPage->outputFormat == 'xhtml');
		$strTagEnding = $blnXhtml ? ' />' : '>';
		$arrFramework = deserialize($objLayout->framework);

		// Google web fonts
		if ($objLayout->webfonts != '')
		{
			$protocol = \Environment::get('ssl') ? 'https://' : 'http://';
			$strStyleSheets .= '<link' . ($blnXhtml ? ' type="text/css"' : '') .' rel="stylesheet" href="' . $protocol . 'fonts.googleapis.com/css?family=' . $objLayout->webfonts . '"' . $strTagEnding . "\n";
		}

		// Add the Contao CSS framework style sheets
		if (is_array($arrFramework))
		{
			foreach ($arrFramework as $strFile)
			{
				if ($strFile != 'tinymce.css')
				{
					$GLOBALS['TL_FRAMEWORK_CSS'][] = 'assets/contao/css/' . $strFile;
				}
			}
		}

		// Add the TinyMCE style sheet
		if (is_array($arrFramework) && in_array('tinymce.css', $arrFramework) && file_exists(TL_ROOT . '/' . $GLOBALS['TL_CONFIG']['uploadPath'] . '/tinymce.css'))
		{
			$GLOBALS['TL_FRAMEWORK_CSS'][] = $GLOBALS['TL_CONFIG']['uploadPath'] . '/tinymce.css';
		}

		// User style sheets
		if (is_array($arrStyleSheets) && strlen($arrStyleSheets[0]))
		{
			$objStylesheets = \StyleSheetModel::findByIds($arrStyleSheets);

			if ($objStylesheets !== null)
			{
				while ($objStylesheets->next())
				{
					$media = implode(',', deserialize($objStylesheets->media));

					// Overwrite the media type with a custom media query
					if ($objStylesheets->mediaQuery != '')
					{
						$media = $objStylesheets->mediaQuery;
					}

					// Style sheets with a CC or a combination of font-face and media-type != all cannot be aggregated (see #5216)
					if ($objStylesheets->cc || ($objStylesheets->hasFontFace && $media != 'all'))
					{
						$strStyleSheet = '<link' . ($blnXhtml ? ' type="text/css"' : '') . ' rel="stylesheet" href="' . TL_ASSETS_URL . 'assets/css/' . $objStylesheets->name . '.css"' . (($media != '' && $media != 'all') ? ' media="' . $media . '"' : '') . $strTagEnding;

						if ($objStylesheets->cc)
						{
							$strStyleSheet = '<!--[' . $objStylesheets->cc . ']>' . $strStyleSheet . '<![endif]-->';
						}

						$strCcStyleSheets .= $strStyleSheet . "\n";
					}
					else
					{
						$GLOBALS['TL_USER_CSS'][] = 'assets/css/' . $objStylesheets->name . '.css|' . $media . '|static|' . max($objStylesheets->tstamp, $objStylesheets->tstamp2, $objStylesheets->tstamp3);
					}
				}
			}
		}

		$arrExternal = deserialize($objLayout->external);

		// External style sheets
		if (is_array($arrExternal) && !empty($arrExternal))
		{
			// Consider the sorting order (see #5038)
			if ($objLayout->orderExt != '')
			{
				$arrOrdered = array_map('intval', explode(',', $objLayout->orderExt));
				$arrExternal = array_merge($arrOrdered, array_diff($arrExternal, $arrOrdered));
			}

			// Get the file entries from the database
			$objFiles = \FilesModel::findMultipleByIds($arrExternal);

			if ($objFiles !== null)
			{
				while ($objFiles->next())
				{
					if (file_exists(TL_ROOT . '/' . $objFiles->path))
					{
						$GLOBALS['TL_USER_CSS'][] = $objFiles->path . '||static';
					}
				}
			}
		}

		// Add a placeholder for dynamic style sheets (see #4203)
		$strStyleSheets .= '[[TL_CSS]]';

		// Add the debug style sheet
		if ($GLOBALS['TL_CONFIG']['debugMode'])
		{
			$strStyleSheets .= '<link rel="stylesheet" href="' . $this->addStaticUrlTo('assets/contao/css/debug.css') . '"' . $strTagEnding . "\n";
		}

		// Always add conditional style sheets at the end
		$strStyleSheets .= $strCcStyleSheets;

		$newsfeeds = deserialize($objLayout->newsfeeds);
		$calendarfeeds = deserialize($objLayout->calendarfeeds);

		// Add newsfeeds
		if (is_array($newsfeeds) && !empty($newsfeeds))
		{
			$objFeeds = \NewsFeedModel::findByIds($newsfeeds);

			if ($objFeeds !== null)
			{
				while($objFeeds->next())
				{
					$base = $objFeeds->feedBase ?: \Environment::get('base');
					$strStyleSheets .= '<link rel="alternate" href="' . $base . 'share/' . $objFeeds->alias . '.xml" type="application/' . $objFeeds->format . '+xml" title="' . $objFeeds->title . '"' . $strTagEnding . "\n";
				}
			}
		}

		// Add calendarfeeds
		if (is_array($calendarfeeds) && !empty($calendarfeeds))
		{
			$objFeeds = \CalendarFeedModel::findByIds($calendarfeeds);

			if ($objFeeds !== null)
			{
				while($objFeeds->next())
				{
					$base = $objFeeds->feedBase ?: \Environment::get('base');
					$strStyleSheets .= '<link rel="alternate" href="' . $base . 'share/' . $objFeeds->alias . '.xml" type="application/' . $objFeeds->format . '+xml" title="' . $objFeeds->title . '"' . $strTagEnding . "\n";
				}
			}
		}

		// Add a placeholder for dynamic <head> tags (see #4203)
		$strHeadTags = '[[TL_HEAD]]';

		// Add the user <head> tags
		if (($strHead = trim($objLayout->head)) != false)
		{
			$strHeadTags .= $strHead . "\n";
		}

		$this->Template->stylesheets = $strStyleSheets;
		$this->Template->head = $strHeadTags;
	}


	/**
	 * Create all footer scripts
	 * @param object
	 */
	protected function createFooterScripts($objLayout)
	{
		$strScripts = '';

		// jQuery
		if ($objLayout->addJQuery)
		{
			$arrJquery = deserialize($objLayout->jquery, true);

			foreach ($arrJquery as $strTemplate)
			{
				if ($strTemplate != '')
				{
					$objTemplate = new \FrontendTemplate($strTemplate);
					$strScripts .= $objTemplate->parse();
				}
			}

			// Add a placeholder for dynamic scripts (see #4203)
			$strScripts .= '[[TL_JQUERY]]';
		}

		// MooTools
		if ($objLayout->addMooTools)
		{
			$arrMootools = deserialize($objLayout->mootools, true);

			foreach ($arrMootools as $strTemplate)
			{
				if ($strTemplate != '')
				{
					$objTemplate = new \FrontendTemplate($strTemplate);
					$strScripts .= $objTemplate->parse();
				}
			}

			// Add a placeholder for dynamic scripts (see #4203)
			$strScripts .= '[[TL_MOOTOOLS]]';
		}

		// Add a placeholder for dynamic scripts (see #4203)
		$strScripts .= '[[TL_HIGHLIGHTER]]';

		// Add the custom JavaScript
		if ($objLayout->script != '')
		{
			$strScripts .= "\n" . trim($objLayout->script) . "\n";
		}

		// Add the analytics scripts
		if ($objLayout->analytics != '')
		{
			$arrAnalytics = deserialize($objLayout->analytics, true);

			foreach ($arrAnalytics as $strTemplate)
			{
				if ($strTemplate != '')
				{
					$objTemplate = new \FrontendTemplate($strTemplate);
					$strScripts .= $objTemplate->parse();
				}
			}
		}

		$this->Template->mootools = $strScripts;
	}
}
