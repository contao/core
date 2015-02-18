<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Provide methods to handle a regular front end page.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class PageRegular extends \Frontend
{

	/**
	 * Generate a regular page
	 *
	 * @param \PageModel $objPage
	 * @param boolean    $blnCheckRequest
	 */
	public function generate($objPage, $blnCheckRequest=false)
	{
		$GLOBALS['TL_KEYWORDS'] = '';
		$GLOBALS['TL_LANGUAGE'] = $objPage->language;

		\System::loadLanguageFile('default');

		// Static URLs
		$this->setStaticUrls();

		// Get the page layout
		$objLayout = $this->getPageLayout($objPage);

		// HOOK: modify the page or layout object (see #4736)
		if (isset($GLOBALS['TL_HOOKS']['getPageLayout']) && is_array($GLOBALS['TL_HOOKS']['getPageLayout']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getPageLayout'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($objPage, $objLayout, $this);
			}
		}

		/** @var \ThemeModel $objTheme */
		$objTheme = $objLayout->getRelated('pid');

		// Set the layout template and template group
		$objPage->template = $objLayout->template ?: 'fe_page';
		$objPage->templateGroup = $objTheme->templates;

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

		$arrModuleIds = array();

		// Filter the disabled modules
		foreach ($arrModules as $module)
		{
			if ($module['enable'])
			{
				$arrModuleIds[] = $module['mod'];
			}
		}

		// Get all modules in a single DB query
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
				// Disabled module
				if (!$arrModule['enable'])
				{
					continue;
				}

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

		// Mark RTL languages (see #7171)
		if ($GLOBALS['TL_LANG']['MSC']['textDirection'] == 'rtl')
		{
			$this->Template->isRTL = true;
		}

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
		$this->Template->mainTitle = $objPage->rootPageTitle;
		$this->Template->pageTitle = $objPage->pageTitle ?: $objPage->title;

		// Meta robots tag
		$this->Template->robots = $objPage->robots ?: 'index,follow';

		// Remove shy-entities (see #2709)
		$this->Template->mainTitle = str_replace('[-]', '', $this->Template->mainTitle);
		$this->Template->pageTitle = str_replace('[-]', '', $this->Template->pageTitle);

		// Fall back to the default title tag
		if ($objLayout->titleTag == '')
		{
			$objLayout->titleTag = '{{page::pageTitle}} - {{page::rootPageTitle}}';
		}

		// Assign the title and description
		$this->Template->title = strip_insert_tags($this->replaceInsertTags($objLayout->titleTag)); // see #7097
		$this->Template->description = str_replace(array("\n", "\r", '"'), array(' ' , '', ''), $objPage->description);

		// Body onload and body classes
		$this->Template->onload = trim($objLayout->onload);
		$this->Template->class = trim($objLayout->cssClass . ' ' . $objPage->cssClass);

		// Execute AFTER the modules have been generated and create footer scripts first
		$this->createFooterScripts($objLayout);
		$this->createHeaderScripts($objPage, $objLayout);

		// Print the template to the screen
		$this->Template->output($blnCheckRequest);
	}


	/**
	 * Get a page layout and return it as database result object
	 *
	 * @param \PageModel $objPage
	 *
	 * @return \LayoutModel
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
		if (\Input::cookie('TL_VIEW') == 'mobile')
		{
			$blnMobile = true;
		}
		elseif (\Input::cookie('TL_VIEW') == 'desktop')
		{
			$blnMobile = false;
		}

		$intId = ($blnMobile && $objPage->mobileLayout) ? $objPage->mobileLayout : $objPage->layout;
		$objLayout = \LayoutModel::findByPk($intId);

		// Die if there is no layout
		if (null === $objLayout)
		{
			header('HTTP/1.1 501 Not Implemented');
			$this->log('Could not find layout ID "' . $intId . '"', __METHOD__, TL_ERROR);
			die_nicely('be_no_layout', 'No layout specified');
		}

		$objPage->hasJQuery = $objLayout->addJQuery;
		$objPage->hasMooTools = $objLayout->addMooTools;
		$objPage->isMobile = $blnMobile;

		return $objLayout;
	}


	/**
	 * Create a new template
	 *
	 * @param \PageModel   $objPage
	 * @param \LayoutModel $objLayout
	 */
	protected function createTemplate($objPage, $objLayout)
	{
		$blnXhtml = ($objPage->outputFormat == 'xhtml');

		/** @var \FrontendTemplate|object $objTemplate */
		$objTemplate = new \FrontendTemplate($objPage->template);

		$this->Template = $objTemplate;

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

		$arrFramework = deserialize($objLayout->framework);

		$this->Template->viewport = '';
		$this->Template->framework = '';

		// Generate the CSS framework
		if (is_array($arrFramework) && in_array('layout.css', $arrFramework))
		{
			$strFramework = '';

			if (in_array('responsive.css', $arrFramework))
			{
				$this->Template->viewport = '<meta name="viewport" content="width=device-width,initial-scale=1.0"' . ($blnXhtml ? ' />' : '>') . "\n";
			}

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
				$this->Template->framework = \Template::generateInlineStyle($strFramework, $blnXhtml) . "\n";
			}
		}

		// Overwrite the viewport tag (see #6251)
		if ($objLayout->viewport != '')
		{
			$this->Template->viewport = '<meta name="viewport" content="' . $objLayout->viewport . '"' . ($blnXhtml ? ' />' : '>') . "\n";
		}

		$this->Template->mooScripts = '';

		// Make sure TL_JAVASCRIPT exists (see #4890)
		if (isset($GLOBALS['TL_JAVASCRIPT']) && is_array($GLOBALS['TL_JAVASCRIPT']))
		{
			$arrAppendJs = $GLOBALS['TL_JAVASCRIPT'];
			$GLOBALS['TL_JAVASCRIPT'] = array();
		}
		else
		{
			$arrAppendJs = array();
			$GLOBALS['TL_JAVASCRIPT'] = array();
		}

		// jQuery scripts
		if ($objLayout->addJQuery)
		{
			if ($objLayout->jSource == 'j_googleapis' || $objLayout->jSource == 'j_fallback')
			{
				$this->Template->mooScripts .= \Template::generateScriptTag('//code.jquery.com/jquery-' . $GLOBALS['TL_ASSETS']['JQUERY'] . '.min.js', $blnXhtml) . "\n";

				// Local fallback (thanks to DyaGa)
				if ($objLayout->jSource == 'j_fallback')
				{
					$this->Template->mooScripts .= \Template::generateInlineScript('window.jQuery || document.write(\'<script src="' . TL_ASSETS_URL . 'assets/jquery/core/' . $GLOBALS['TL_ASSETS']['JQUERY'] . '/jquery.min.js">\x3C/script>\')', $blnXhtml) . "\n";
				}
			}
			else
			{
				$GLOBALS['TL_JAVASCRIPT'][] = 'assets/jquery/core/' . $GLOBALS['TL_ASSETS']['JQUERY'] . '/jquery.min.js|static';
			}
		}

		// MooTools scripts
		if ($objLayout->addMooTools)
		{
			if ($objLayout->mooSource == 'moo_googleapis' || $objLayout->mooSource == 'moo_fallback')
			{
				$this->Template->mooScripts .= \Template::generateScriptTag('//ajax.googleapis.com/ajax/libs/mootools/' . $GLOBALS['TL_ASSETS']['MOOTOOLS'] . '/mootools-yui-compressed.js', $blnXhtml) . "\n";

				// Local fallback (thanks to DyaGa)
				if ($objLayout->mooSource == 'moo_fallback')
				{
					$this->Template->mooScripts .= \Template::generateInlineScript('window.MooTools || document.write(\'<script src="' . TL_ASSETS_URL . 'assets/mootools/core/' . $GLOBALS['TL_ASSETS']['MOOTOOLS'] . '/mootools-core.js">\x3C/script>\')', $blnXhtml) . "\n";
				}

				$GLOBALS['TL_JAVASCRIPT'][] = 'assets/mootools/core/' . $GLOBALS['TL_ASSETS']['MOOTOOLS'] . '/mootools-more.js|static';
				$GLOBALS['TL_JAVASCRIPT'][] = 'assets/mootools/core/' . $GLOBALS['TL_ASSETS']['MOOTOOLS'] . '/mootools-mobile.js|static';
			}
			else
			{
				$GLOBALS['TL_JAVASCRIPT'][] = 'assets/mootools/core/' . $GLOBALS['TL_ASSETS']['MOOTOOLS'] . '/mootools.js|static';
			}
		}

		// Load MooTools core for the debug bar (see #5195)
		if (\Config::get('debugMode') && !$objLayout->addMooTools)
		{
			$GLOBALS['TL_JAVASCRIPT'][] = 'assets/mootools/core/' . $GLOBALS['TL_ASSETS']['MOOTOOLS'] . '/mootools-core.js|static';
		}

		// Picturefill
		if ($objLayout->picturefill)
		{
			$GLOBALS['TL_JAVASCRIPT'][] = 'assets/respimage/' . $GLOBALS['TL_ASSETS']['RESPIMAGE'] . '/respimage.min.js|static';
		}

		// Check whether TL_APPEND_JS exists (see #4890)
		if (!empty($arrAppendJs))
		{
			$GLOBALS['TL_JAVASCRIPT'] = array_merge($GLOBALS['TL_JAVASCRIPT'], $arrAppendJs);
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
		$this->Template->charset = \Config::get('characterSet');
		$this->Template->base = \Environment::get('base');
		$this->Template->disableCron = \Config::get('disableCron');
		$this->Template->cronTimeout = $this->getCronTimeout();
		$this->Template->isRTL = false;
	}


	/**
	 * Create all header scripts
	 *
	 * @param \PageModel   $objPage
	 * @param \LayoutModel $objLayout
	 */
	protected function createHeaderScripts($objPage, $objLayout)
	{
		$strStyleSheets = '';
		$strCcStyleSheets = '';
		$arrStyleSheets = deserialize($objLayout->stylesheet);
		$blnXhtml = ($objPage->outputFormat == 'xhtml');
		$arrFramework = deserialize($objLayout->framework);

		// Google web fonts
		if ($objLayout->webfonts != '')
		{
			$strStyleSheets .= \Template::generateStyleTag('//fonts.googleapis.com/css?family=' . str_replace('|', '%7C', $objLayout->webfonts), 'all', $blnXhtml) . "\n";
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
		if (is_array($arrFramework) && in_array('tinymce.css', $arrFramework) && file_exists(TL_ROOT . '/' . \Config::get('uploadPath') . '/tinymce.css'))
		{
			$GLOBALS['TL_FRAMEWORK_CSS'][] = \Config::get('uploadPath') . '/tinymce.css';
		}

		// Make sure TL_USER_CSS is set
		if (!is_array($GLOBALS['TL_USER_CSS']))
		{
			$GLOBALS['TL_USER_CSS'] = array();
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
						$strStyleSheet = '';

						// External style sheet
						if ($objStylesheets->type == 'external')
						{
							$objFile = \FilesModel::findByPk($objStylesheets->singleSRC);

							if ($objFile !== null)
							{
								$strStyleSheet = \Template::generateStyleTag(TL_ASSETS_URL . $objFile->path, $media, $blnXhtml);
							}
						}
						else
						{
							$strStyleSheet = \Template::generateStyleTag(TL_ASSETS_URL . 'assets/css/' . $objStylesheets->name . '.css', $media, $blnXhtml);
						}

						if ($objStylesheets->cc)
						{
							$strStyleSheet = '<!--[' . $objStylesheets->cc . ']>' . $strStyleSheet . '<![endif]-->';
						}

						$strCcStyleSheets .= $strStyleSheet . "\n";
					}
					else
					{
						// External style sheet
						if ($objStylesheets->type == 'external')
						{
							$objFile = \FilesModel::findByPk($objStylesheets->singleSRC);

							if ($objFile !== null)
							{
								$GLOBALS['TL_USER_CSS'][] = $objFile->path . '|' . $media . '|static|' . filemtime(TL_ROOT . '/' . $objFile->path);
							}
						}
						else
						{
							$GLOBALS['TL_USER_CSS'][] = 'assets/css/' . $objStylesheets->name . '.css|' . $media . '|static|' . max($objStylesheets->tstamp, $objStylesheets->tstamp2, $objStylesheets->tstamp3);
						}
					}
				}
			}
		}

		$arrExternal = deserialize($objLayout->external);

		// External style sheets
		if (!empty($arrExternal) && is_array($arrExternal))
		{
			// Consider the sorting order (see #5038)
			if ($objLayout->orderExt != '')
			{
				$tmp = deserialize($objLayout->orderExt);

				if (!empty($tmp) && is_array($tmp))
				{
					// Remove all values
					$arrOrder = array_map(function(){}, array_flip($tmp));

					// Move the matching elements to their position in $arrOrder
					foreach ($arrExternal as $k=>$v)
					{
						if (array_key_exists($v, $arrOrder))
						{
							$arrOrder[$v] = $v;
							unset($arrExternal[$k]);
						}
					}

					// Append the left-over style sheets at the end
					if (!empty($arrExternal))
					{
						$arrOrder = array_merge($arrOrder, array_values($arrExternal));
					}

					// Remove empty (unreplaced) entries
					$arrExternal = array_values(array_filter($arrOrder));
					unset($arrOrder);
				}
			}

			// Get the file entries from the database
			$objFiles = \FilesModel::findMultipleByUuids($arrExternal);

			if ($objFiles !== null)
			{
				$arrFiles = array();

				while ($objFiles->next())
				{
					if (file_exists(TL_ROOT . '/' . $objFiles->path))
					{
						$arrFiles[] = $objFiles->path . '|static';
					}
				}

				// Inject the external style sheets before or after the internal ones (see #6937)
				if ($objLayout->loadingOrder == 'external_first')
				{
					array_splice($GLOBALS['TL_USER_CSS'], 0, 0, $arrFiles);
				}
				else
				{
					array_splice($GLOBALS['TL_USER_CSS'], count($GLOBALS['TL_USER_CSS']), 0, $arrFiles);
				}
			}
		}

		// Add a placeholder for dynamic style sheets (see #4203)
		$strStyleSheets .= '[[TL_CSS]]';

		// Add the debug style sheet
		if (\Config::get('debugMode'))
		{
			$strStyleSheets .= \Template::generateStyleTag($this->addStaticUrlTo('assets/contao/css/debug.css'), 'all', $blnXhtml) . "\n";
		}

		// Always add conditional style sheets at the end
		$strStyleSheets .= $strCcStyleSheets;

		$newsfeeds = deserialize($objLayout->newsfeeds);
		$calendarfeeds = deserialize($objLayout->calendarfeeds);

		// Add newsfeeds
		if (!empty($newsfeeds) && is_array($newsfeeds))
		{
			$objFeeds = \NewsFeedModel::findByIds($newsfeeds);

			if ($objFeeds !== null)
			{
				while($objFeeds->next())
				{
					$strStyleSheets .= \Template::generateFeedTag(($objFeeds->feedBase ?: \Environment::get('base')) . 'share/' . $objFeeds->alias . '.xml', $objFeeds->format, $objFeeds->title, $blnXhtml) . "\n";
				}
			}
		}

		// Add calendarfeeds
		if (!empty($calendarfeeds) && is_array($calendarfeeds))
		{
			$objFeeds = \CalendarFeedModel::findByIds($calendarfeeds);

			if ($objFeeds !== null)
			{
				while($objFeeds->next())
				{
					$strStyleSheets .= \Template::generateFeedTag(($objFeeds->feedBase ?: \Environment::get('base')) . 'share/' . $objFeeds->alias . '.xml', $objFeeds->format, $objFeeds->title, $blnXhtml) . "\n";
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
	 *
	 * @param \LayoutModel $objLayout
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

		// Add a placeholder for dynamic scripts (see #4203, #5583)
		$strScripts .= '[[TL_BODY]]';

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
