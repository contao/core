<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class PageRegular
 *
 * Provide methods to handle a regular front end page.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class PageRegular extends Frontend
{

	/**
	 * Generate a regular page
	 * @param object
	 */
	public function generate(Database_Result $objPage)
	{
		$GLOBALS['TL_KEYWORDS'] = '';
		$GLOBALS['TL_LANGUAGE'] = $objPage->language;

		$this->loadLanguageFile('default');

		$objLayout = $this->getPageLayout($objPage->layout);
		$objPage->template = strlen($objLayout->template) ? $objLayout->template : 'fe_page';
		$objPage->templateGroup = $objLayout->templates;

		// Initialize the template
		$this->createTemplate($objPage, $objLayout);

		// Initialize modules and sections
		$arrCustomSections = array();
		$arrSections = array('header', 'left', 'right', 'main', 'footer');
		$arrModules = deserialize($objLayout->modules);

		// Generate all modules
		foreach ($arrModules as $arrModule)
		{
			if (in_array($arrModule['col'], $arrSections))
			{
				$this->Template->$arrModule['col'] .= $this->getFrontendModule($arrModule['mod'], $arrModule['col']);
			}
			else
			{
				$arrCustomSections[$arrModule['col']] .= $this->getFrontendModule($arrModule['mod'], $arrModule['col']);
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
		$this->Template->pageTitle = strlen($objPage->pageTitle) ? $objPage->pageTitle : $objPage->title;

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
		$this->createHeaderScripts($objLayout);

		// Add an invisible character to empty sections (IE fix)
		if (!$this->Template->header && $objLayout->header)
		{
			$this->Template->header = '&nbsp;';
		}

		if (!$this->Template->left && ($objLayout->cols == '2cll' || $objLayout->cols == '3cl'))
		{
			$this->Template->left = '&nbsp;';
		}

		if (!$this->Template->right && ($objLayout->cols == '2clr' || $objLayout->cols == '3cl'))
		{
			$this->Template->right = '&nbsp;';
		}

		if (!$this->Template->footer && $objLayout->footer)
		{
			$this->Template->footer = '&nbsp;';
		}

		// Print the template to the screen
		$this->Template->output();
	}


	/**
	 * Get a page layout and return it as database result object
	 * @param integer
	 * @return object
	 */
	protected function getPageLayout($intId)
	{
		$objLayout = $this->Database->prepare("SELECT l.*, t.templates FROM tl_layout l LEFT JOIN tl_theme t ON l.pid=t.id WHERE l.id=?")
									->limit(1)
									->execute($intId);

		// Fallback layout
		if ($objLayout->numRows < 1)
		{
			$objLayout = $this->Database->prepare("SELECT l.*, t.templates FROM tl_layout l LEFT JOIN tl_theme t ON l.pid=t.id WHERE l.fallback=?")
										->limit(1)
										->execute(1);
		}
		
		// Die if there is no layout at all
		if ($objLayout->numRows < 1)
		{
			$this->log('Could not find layout ID "' . $intId . '"', 'PageRegular getPageLayout()', TL_ERROR);

			header('HTTP/1.1 501 Not Implemented');
			die('No layout specified');
		}

		return $objLayout;
	}


	/**
	 * Create a new template
	 * @param object
	 * @param object
	 */
	protected function createTemplate(Database_Result $objPage, Database_Result $objLayout)
	{
		$this->Template = new FrontendTemplate($objPage->template);

		// DTD
		switch ($objLayout->doctype)
		{
			case 'xhtml_strict':
				$this->Template->doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\n";
				break;

			case 'xhtml_trans':
				$this->Template->doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
				break;
		}

		// Robots
		if (strlen($objPage->robots))
		{
			$this->Template->robots = '<meta name="robots" content="' . $objPage->robots . '" />' . "\n";
		}

		// Initialize margin
		$arrMargin = array
		(
			'left'   => '0 auto 0 0',
			'center' => '0 auto',
			'right'  => '0 0 0 auto'
		);

		$strFramework = '';

		// Wrapper
		if ($objLayout->static)
		{
			$arrSize = deserialize($objLayout->width);
			$strFramework .= sprintf('#wrapper { width:%s; margin:%s; }', $arrSize['value'] . $arrSize['unit'], $arrMargin[$objLayout->align]) . "\n";
		}

		// Header
		if ($objLayout->header)
		{
			$arrSize = deserialize($objLayout->headerHeight);

			if ($arrSize['value'] != '' && $arrSize['value'] >= 0)
			{
				$strFramework .= sprintf('#header { height:%s; }', $arrSize['value'] . $arrSize['unit']) . "\n";
			}
		}

		$strMain = '';

		// Left column
		if ($objLayout->cols == '2cll' || $objLayout->cols == '3cl')
		{
			$arrSize = deserialize($objLayout->widthLeft);

			if ($arrSize['value'] != '' && $arrSize['value'] >= 0)
			{
				$strFramework .= sprintf('#left { width:%s; }', $arrSize['value'] . $arrSize['unit']) . "\n";
				$strMain .= sprintf(' margin-left:%s;', $arrSize['value'] . $arrSize['unit']);
			}
		}

		// Right column
		if ($objLayout->cols == '2clr' || $objLayout->cols == '3cl')
		{
			$arrSize = deserialize($objLayout->widthRight);

			if ($arrSize['value'] != '' && $arrSize['value'] >= 0)
			{
				$strFramework .= sprintf('#right { width:%s; }', $arrSize['value'] . $arrSize['unit']) . "\n";
				$strMain .= sprintf(' margin-right:%s;', $arrSize['value'] . $arrSize['unit']);
			}
		}

		// Main column
		if (strlen($strMain))
		{
			$strFramework .= sprintf('#main {%s }', $strMain) . "\n";
		}

		// Footer
		if ($objLayout->footer)
		{
			$arrSize = deserialize($objLayout->footerHeight);

			if ($arrSize['value'] != '' && $arrSize['value'] >= 0)
			{
				$strFramework .= sprintf('#footer { height:%s; }', $arrSize['value'] . $arrSize['unit']) . "\n";
			}
		}

		$this->Template->framework = '';

		// Add layout specific CSS
		if (!empty($strFramework))
		{
			$this->Template->framework .= '<style type="text/css" media="screen">' . "\n";
			$this->Template->framework .= '<!--/*--><![CDATA[/*><!--*/' . "\n";
			$this->Template->framework .= $strFramework;
			$this->Template->framework .= '/*]]>*/-->' . "\n";
			$this->Template->framework .= '</style>' . "\n";
		}

		// Include basic style sheets
		$this->Template->framework .= '<link rel="stylesheet" href="system/contao.css" type="text/css" media="screen" />' . "\n";
		$this->Template->framework .= '<!--[if lte IE 7]><link rel="stylesheet" href="system/iefixes.css" type="text/css" media="screen" /><![endif]-->' . "\n";

		// MooTools scripts
		if ($objLayout->mooSource == 'moo_googleapis')
		{
			$protocol = $this->Environment->ssl ? 'https://' : 'http://';

			$this->Template->mooScripts  = '<script type="text/javascript" src="'. $protocol .'ajax.googleapis.com/ajax/libs/mootools/'. MOOTOOLS_CORE .'/mootools-yui-compressed.js"></script>' . "\n";
			$this->Template->mooScripts .= '<script type="text/javascript" src="plugins/mootools/mootools-more.js?'. MOOTOOLS_MORE .'"></script>' . "\n";
		}
		else
		{
			$this->Template->mooScripts  = '<script type="text/javascript" src="plugins/mootools/mootools-core.js?'. MOOTOOLS_CORE .'"></script>' . "\n";
			$this->Template->mooScripts .= '<script type="text/javascript" src="plugins/mootools/mootools-more.js?'. MOOTOOLS_MORE .'"></script>' . "\n";
		}

		// Initialize sections
		$this->Template->header = '';
		$this->Template->left = '';
		$this->Template->main = '';
		$this->Template->right = '';
		$this->Template->footer = '';

		// Initialize custom layout sections
		$this->Template->sections = array();
		$this->Template->sPosition = $objLayout->sPosition;

		// Default settings
		$this->Template->layout = $objLayout;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->base = $this->Environment->base;
	}


	/**
	 * Create all header scripts
	 * @param object
	 */
	protected function createHeaderScripts(Database_Result $objLayout)
	{
		$strStyleSheets = '';
		$arrStyleSheets = deserialize($objLayout->stylesheet);

		// Internal style sheets
		if (is_array($GLOBALS['TL_CSS']) && count($GLOBALS['TL_CSS']))
		{
			foreach (array_unique($GLOBALS['TL_CSS']) as $stylesheet)
			{
				list($stylesheet, $media) = explode('|', $stylesheet);
				$strStyleSheets .= '<link rel="stylesheet" href="' . $stylesheet . '" type="text/css" media="' . (($media != '') ? $media : 'all') . '" />' . "\n";
			}
		}

		// Default TinyMCE style sheet
		if (!$objLayout->skipTinymce && file_exists(TL_ROOT . '/' . $GLOBALS['TL_CONFIG']['uploadPath'] . '/tinymce.css'))
		{
			$strStyleSheets .= '<link rel="stylesheet" href="' . $GLOBALS['TL_CONFIG']['uploadPath'] . '/tinymce.css?' . filemtime(TL_ROOT .'/'. $GLOBALS['TL_CONFIG']['uploadPath'] . '/tinymce.css') . '" type="text/css" media="screen" />' . "\n";
		}

		// User style sheets
		if (is_array($arrStyleSheets) && strlen($arrStyleSheets[0]))
		{
			$arrAggregator = array();
			$strCcStyleSheets = '';
			$objStylesheets = $this->Database->execute("SELECT *, (SELECT MAX(tstamp) FROM tl_style WHERE tl_style.pid=tl_style_sheet.id) AS tstamp2, (SELECT COUNT(*) FROM tl_style WHERE tl_style.selector='@font-face' AND tl_style.pid=tl_style_sheet.id) AS hasFontFace FROM tl_style_sheet WHERE id IN (" . implode(', ', $arrStyleSheets) . ") ORDER BY FIELD(id, " . implode(', ', $arrStyleSheets) . ")");

			while ($objStylesheets->next())
			{
				// Try to aggregate regular style sheets
				if ($objLayout->aggregate && !$objStylesheets->cc && !$objStylesheets->hasFontFace)
				{
					$key = md5($objStylesheets->id .'-'. max($objStylesheets->tstamp, $objStylesheets->tstamp2) .'-'. $objStylesheets->media);

					$arrAggregator[$key] = array
					(
						'name' => $objStylesheets->name . '.css',
						'media' => implode(',', deserialize($objStylesheets->media))
					);
				}

				// Add each style sheet separately
				else
				{
					$strStyleSheet = sprintf('<link rel="stylesheet" href="%s" type="text/css" media="%s" />',
											 $objStylesheets->name . '.css?' . max($objStylesheets->tstamp, $objStylesheets->tstamp2),
											 implode(',', deserialize($objStylesheets->media)));

					if ($objStylesheets->cc)
					{
						$strStyleSheet = '<!--[' . $objStylesheets->cc . ']>' . $strStyleSheet . '<![endif]-->';
					}

					$strCcStyleSheets .= $strStyleSheet . "\n";
				}
			}

			// Create the aggregated style sheet
			if (count($arrAggregator) > 0)
			{
				$key = substr(md5(implode('-', array_keys($arrAggregator))), 0, 16);

				// Load the existing file
				if (file_exists(TL_ROOT .'/system/html/'. $key .'.css'))
				{
					$strStyleSheets .= '<link rel="stylesheet" href="system/html/'. $key .'.css" type="text/css" media="all" />' . "\n";
				}

				// Create a new file
				else
				{
					$objFile = new File('system/html/'. $key .'.css');

					foreach ($arrAggregator as $file)
					{
						// Adjust the file paths
						$content = file_get_contents(TL_ROOT .'/'. $file['name']);
						$content = str_replace('url("'. $GLOBALS['TL_CONFIG']['uploadPath'] . '/', 'url("../../'. $GLOBALS['TL_CONFIG']['uploadPath'] . '/', $content);

						// Append the style sheet
						$objFile->append('@media '. (($file['media'] != '') ? $file['media'] : 'all') ."{\n". $content .'}');
					}

					$objFile->close();
					$strStyleSheets .= '<link rel="stylesheet" href="system/html/'. $key .'.css" type="text/css" media="all" />' . "\n";
				}
			}

			// Always add conditional style sheets at the end
			$strStyleSheets .= $strCcStyleSheets;
		}

		$newsfeeds = deserialize($objLayout->newsfeeds);
		$calendarfeeds = deserialize($objLayout->calendarfeeds);

		// Add newsfeeds
		if (is_array($newsfeeds) && count($newsfeeds) > 0)
		{
			$objFeeds = $this->Database->execute("SELECT * FROM tl_news_archive WHERE makeFeed=1 AND id IN(" . implode(',', array_map('intval', $newsfeeds)) . ")");

			while($objFeeds->next())
			{
				$base = strlen($objFeeds->feedBase) ? $objFeeds->feedBase : $this->Environment->base;
				$strStyleSheets .= '<link rel="alternate" href="' . $base . $objFeeds->alias . '.xml" type="application/' . $objFeeds->format . '+xml" title="' . $objFeeds->title . '" />' . "\n";
			}
		}

		// Add calendarfeeds
		if (is_array($calendarfeeds) && count($calendarfeeds) > 0)
		{
			$objFeeds = $this->Database->execute("SELECT * FROM tl_calendar WHERE makeFeed=1 AND id IN(" . implode(',', array_map('intval', $calendarfeeds)) . ")");

			while($objFeeds->next())
			{
				$base = strlen($objFeeds->feedBase) ? $objFeeds->feedBase : $this->Environment->base;
				$strStyleSheets .= '<link rel="alternate" href="' . $base . $objFeeds->alias . '.xml" type="application/' . $objFeeds->format . '+xml" title="' . $objFeeds->title . '" />' . "\n";
			}
		}

		$strHeadTags = '';

		// Add internal scripts
		if (is_array($GLOBALS['TL_JAVASCRIPT']) && count($GLOBALS['TL_JAVASCRIPT']))
		{
			foreach (array_unique($GLOBALS['TL_JAVASCRIPT']) as $javascript)
			{
				$strHeadTags .= '<script type="text/javascript" src="' . $javascript . '"></script>' . "\n";
			}
		}

		// Add internal <head> tags
		if (is_array($GLOBALS['TL_HEAD']) && count($GLOBALS['TL_HEAD']))
		{
			foreach (array_unique($GLOBALS['TL_HEAD']) as $head)
			{
				$strHeadTags .= trim($head) . "\n";
			}
		}

		// Add <head> tags
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
	protected function createFooterScripts(Database_Result $objLayout)
	{
		$strMootools = '';
		$arrMootools = deserialize($objLayout->mootools, true);

		// Add MooTools templates
		foreach ($arrMootools as $strTemplate)
		{
			if ($strTemplate == '')
			{
				continue;
			}

			$objTemplate = new FrontendTemplate($strTemplate);

			// Backwards compatibility
			try
			{
				$strMootools .= $objTemplate->parse();
			}
			catch (Exception $e)
			{
				$this->log($e->getMessage(), 'PageRegular createFooterScripts()', TL_ERROR);
			}
		}

		// Add internal MooTools scripts
		if (is_array($GLOBALS['TL_MOOTOOLS']) && count($GLOBALS['TL_MOOTOOLS']))
		{
			foreach (array_unique($GLOBALS['TL_MOOTOOLS']) as $script)
			{
				$strMootools .= "\n" . trim($script) . "\n";
			}
		}

		// Add custom JavaScript
		if ($objLayout->script != '')
		{
			$strMootools .= "\n" . trim($objLayout->script) . "\n";
		}

		$this->Template->mootools = $strMootools;
	}
}

?>