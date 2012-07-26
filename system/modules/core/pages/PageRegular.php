<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Core
 * @link    http://www.contao.org
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
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
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
			foreach ($arrModules as $arrModule)
			{
				// Replace the module ID with the result row
				if ($arrModule['mod'] > 0)
				{
					$objModules->next();
					$arrModule['mod'] = $objModules;
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
					$this->Template->mooScripts .= '<script' . ($blnXhtml ? ' type="text/javascript"' : '') . '>window.jQuery || document.write(\'<script src="' . TL_PLUGINS_URL . 'plugins/jQuery/core/' . JQUERY . '/jquery.min.js">\x3C/script>\')</script>' . "\n";
				}
			}
			else
			{
				$this->Template->mooScripts .= '<script' . ($blnXhtml ? ' type="text/javascript"' : '') . ' src="plugins/jQuery/core/' . JQUERY . '/jquery.min.js"></script>' . "\n";
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
					$this->Template->mooScripts .= '<script' . ($blnXhtml ? ' type="text/javascript"' : '') . '>window.MooTools || document.write(\'<script src="' . TL_PLUGINS_URL . 'plugins/mootools/core/' . MOOTOOLS . '/mootools-core.js">\x3C/script>\')</script>' . "\n";
				}

				$this->Template->mooScripts .= '<script' . ($blnXhtml ? ' type="text/javascript"' : '') . ' src="' . TL_PLUGINS_URL . 'plugins/mootools/core/' . MOOTOOLS . '/mootools-more.js"></script>' . "\n";
				$this->Template->mooScripts .= '<script' . ($blnXhtml ? ' type="text/javascript"' : '') . ' src="' . TL_PLUGINS_URL . 'plugins/mootools/core/' . MOOTOOLS . '/mootools-mobile.js"></script>' . "\n";
			}
			else
			{
				$this->Template->mooScripts .= '<script' . ($blnXhtml ? ' type="text/javascript"' : '') . ' src="plugins/mootools/core/' . MOOTOOLS . '/mootools.js"></script>' . "\n";
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

		$objCombiner = new \Combiner();

		// Add the Contao CSS framework style sheets
		if (is_array($arrFramework))
		{
			foreach ($arrFramework as $strFile)
			{
				if ($strFile != 'tinymce.css')
				{
					$objCombiner->add('assets/contao/' . $strFile);
				}
			}
		}

		// Skip the TinyMCE style sheet
		if (is_array($arrFramework) && in_array('tinymce.css', $arrFramework) && file_exists(TL_ROOT . '/' . $GLOBALS['TL_CONFIG']['uploadPath'] . '/tinymce.css'))
		{
			$objCombiner->add($GLOBALS['TL_CONFIG']['uploadPath'] . '/tinymce.css', filemtime(TL_ROOT .'/'. $GLOBALS['TL_CONFIG']['uploadPath'] . '/tinymce.css'));
		}

		// Internal style sheets
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
					$strStyleSheets .= '<link' . ($blnXhtml ? ' type="text/css"' : '') . ' rel="stylesheet" href="' . $this->addStaticUrlTo($stylesheet) . '"' . (($media != '' && $media != 'all') ? ' media="' . $media . '"' : '') . $strTagEnding . "\n";
				}
			}
		}

		// Add a placeholder for dynamic style sheets (see #4203)
		$strStyleSheets .= '[[TL_CSS]]';

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

					// Aggregate regular style sheets
					if (!$objStylesheets->cc && !$objStylesheets->hasFontFace)
					{
						$objCombiner->add('assets/css/' . $objStylesheets->name . '.css', max($objStylesheets->tstamp, $objStylesheets->tstamp2, $objStylesheets->tstamp3), $media);
					}
					else
					{
						$strStyleSheet = '<link' . ($blnXhtml ? ' type="text/css"' : '') . ' rel="stylesheet" href="' . TL_SCRIPT_URL . 'assets/css/' . $objStylesheets->name . '.css"' . (($media != '' && $media != 'all') ? ' media="' . $media . '"' : '') . $strTagEnding;

						if ($objStylesheets->cc)
						{
							$strStyleSheet = '<!--[' . $objStylesheets->cc . ']>' . $strStyleSheet . '<![endif]-->';
						}

						$strCcStyleSheets .= $strStyleSheet . "\n";
					}
				}
			}
		}

		$arrExternal = deserialize($objLayout->external);

		// External style sheets
		if (is_array($arrExternal) && !empty($arrExternal))
		{
			foreach (array_unique($arrExternal) as $stylesheet)
			{
				if ($stylesheet == '')
				{
					continue;
				}

				// Validate the file name
				if (strpos($stylesheet, '..') !== false || strncmp($stylesheet, $GLOBALS['TL_CONFIG']['uploadPath'] . '/', strlen($GLOBALS['TL_CONFIG']['uploadPath']) + 1) !== 0)
				{
					throw new \Exception("Invalid path $stylesheet");
				}

				list($stylesheet, $media, $mode) = explode('|', $stylesheet);

				// Only .css files are allowed
				if (substr($stylesheet, -4) != '.css')
				{
					throw new \Exception("Invalid file extension $stylesheet");
				}

				// Check whether the file exists
				if (!file_exists(TL_ROOT . '/' . $stylesheet))
				{
					continue;
				}

				// Include the file
				if ($mode == 'static')
				{
					$objCombiner->add($stylesheet, filemtime(TL_ROOT . '/' . $stylesheet), $media);
				}
				else
				{
					$strStyleSheets .= '<link' . ($blnXhtml ? ' type="text/css"' : '') . ' rel="stylesheet" href="' . $this->addStaticUrlTo($stylesheet) . '"' . (($media != '' && $media != 'all') ? ' media="' . $media . '"' : '') . $strTagEnding . "\n";
				}
			}
		}

		// Create the aggregated style sheet
		if ($objCombiner->hasEntries())
		{
			$strStyleSheets .= '<link' . ($blnXhtml ? ' type="text/css"' : '') . ' rel="stylesheet" href="' . $objCombiner->getCombinedFile() . '"' . $strTagEnding . "\n";
		}

		// Add the debug style sheet
		if ($GLOBALS['TL_CONFIG']['debugMode'])
		{
			$strStyleSheets .= '<link rel="stylesheet" href="' . $this->addStaticUrlTo('assets/contao/debug.css') . '">' . "\n";
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

		$strHeadTags = '';

		// Add the user <head> tags
		if (($strHead = trim($objLayout->head)) != false)
		{
			$strHeadTags .= $strHead . "\n";
		}

		$this->Template->stylesheets = $strStyleSheets;
		$this->Template->head = $strHeadTags . '[[TL_HEAD]]'; // see #4203
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
