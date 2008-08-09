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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class PageRegular
 *
 * Provide methods to handle a regular front end page.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
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

		// Initialize the template
		$this->createTemplate($objPage, $objLayout);

		// Initialize modules and sections
		$arrCustomSections = array();
		$arrSections = array('header', 'left', 'main', 'right', 'footer');
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

		// Page title and description (call AFTER modules have been generated)
		$this->Template->mainTitle = $objPage->rootTitle;
		$this->Template->pageTitle = strlen($objPage->pageTitle) ? $objPage->pageTitle : $objPage->title;
		$this->Template->title = $this->Template->mainTitle . ' - ' . $this->Template->pageTitle;
		$this->Template->description = str_replace(array("\n", "\r"), array(' ' , ''), $objPage->description);

		// Include all style sheets (call AFTER modules have been generated)
		$this->createStyleSheets($objLayout);

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
	private function getPageLayout($intId)
	{
		$objLayout = $this->Database->prepare("SELECT * FROM tl_layout WHERE id=?")
									->limit(1)
									->execute($intId);

		// Fallback layout
		if ($objLayout->numRows < 1)
		{
			$objLayout = $this->Database->prepare("SELECT * FROM tl_layout WHERE fallback=?")
										->limit(1)
										->execute(1);
		}
		
		// Die if there is no layout at all
		if ($objLayout->numRows < 1)
		{
			$this->log('Could not find layout ID "' . $intId . '"', 'PageRegular getPageLayout()', TL_ERROR);

			header('HTTP/1.0 501 Not Implemented');
			die('No layout specified');
		}

		return $objLayout;
	}


	/**
	 * Create a new template
	 * @param object
	 * @param object
	 */
	private function createTemplate(Database_Result $objPage, Database_Result $objLayout)
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
		if ($objPage->noSearch)
		{
			$this->Template->robots = '<meta name="robots" content="noindex,nofollow" />' . "\n";
		}

		// Add urchin ID if there is no back end user
		if (!BE_USER_LOGGED_IN)
		{
			$this->Template->urchinId = $objLayout->urchinId;
			$this->Template->urchinUrl = $this->Environment->ssl ? 'https://ssl.google-analytics.com/ga.js' : 'http://www.google-analytics.com/ga.js';
		}

		// Additional <head> tags
		if (($strHead = trim($objLayout->head)) != false)
		{
			$this->Template->head = $strHead . "\n";
		}

		// Body onload
		if (($strOnload = trim($objLayout->onload)) != false)
		{
			$this->Template->onload = sprintf(' onload="%s"', $strOnload);
		}

		// HOOK: extension "bodyclass"
		if (in_array('bodyclass', $this->Config->getActiveModules()))
		{
			if (strlen($objPage->cssBody))
			{
				$this->Template->onload .= sprintf(' class="%s"', $objPage->cssBody);
			}
		}

		// Mootools script
		if (strlen($objLayout->mootools) && $objLayout->mootools != '-')
		{
			$objTemplate = new FrontendTemplate($objLayout->mootools);
			$this->Template->mootools = $objTemplate->parse();
		}

		$arrMargin = array
		(
			'left'   => '0 auto 0 0',
			'center' => '0 auto',
			'right'  => '0 0 0 auto'
		);

		// Add the CSS framework
		$this->Template->framework .= '<style type="text/css" media="screen">' . "\n";
		$this->Template->framework .= '<!--/*--><![CDATA[/*><!--*/' . "\n";

		// Wrapper
		if ($objLayout->static)
		{
			$arrSize = deserialize($objLayout->width);

			$this->Template->framework .= sprintf('* html body { text-align:%s; }', $objLayout->align) . "\n";
			$this->Template->framework .= sprintf('#wrapper { width:%s; margin:%s; }', $arrSize['value'] . $arrSize['unit'], $arrMargin[$objLayout->align]) . "\n";
		}

		// Header
		if ($objLayout->header)
		{
			$arrSize = deserialize($objLayout->headerHeight);
			$this->Template->framework .= sprintf('#header { height:%s; }', $arrSize['value'] . $arrSize['unit']) . "\n";
		}

		$strMain = '';

		// Left column
		if ($objLayout->cols == '2cll' || $objLayout->cols == '3cl')
		{
			$arrSize = deserialize($objLayout->widthLeft);

			$this->Template->framework .= sprintf('#left { width:%s; }', $arrSize['value'] . $arrSize['unit']) . "\n";
			$strMain .= sprintf(' margin-left:%s;', $arrSize['value'] . $arrSize['unit']);
		}

		// Right column
		if ($objLayout->cols == '2clr' || $objLayout->cols == '3cl')
		{
			$arrSize = deserialize($objLayout->widthRight);

			$this->Template->framework .= sprintf('#right { width:%s; }', $arrSize['value'] . $arrSize['unit']) . "\n";
			$strMain .= sprintf(' margin-right:%s;', $arrSize['value'] . $arrSize['unit']);
		}

		// Main column
		if (strlen($strMain))
		{
			$this->Template->framework .= sprintf('#main { %s }', $strMain) . "\n";
		}

		// Footer
		if ($objLayout->footer)
		{
			$arrSize = deserialize($objLayout->footerHeight);
			$this->Template->framework .= sprintf('#footer { height:%s; }', $arrSize['value'] . $arrSize['unit']) . "\n";
		}

		$this->Template->framework .= '/*]]>*/-->' . "\n";
		$this->Template->framework .= '</style>' . "\n";
		$this->Template->framework .= '<link rel="stylesheet" href="system/typolight.css" type="text/css" media="screen" />' . "\n";
		$this->Template->framework .= '<!--[if IE]><link rel="stylesheet" href="system/iefixes.css" type="text/css" media="screen" /><![endif]-->' . "\n";

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
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->base = $this->Environment->base;
	}


	/**
	 * Create all style sheets and scripts
	 * @param object
	 */
	private function createStylesheets(Database_Result $objLayout)
	{
		$strStyleSheets = '';
		$arrStyleSheets = deserialize($objLayout->stylesheet);

		// Add internal style sheets
		if (is_array($GLOBALS['TL_CSS']) && count($GLOBALS['TL_CSS']))
		{
			foreach (array_unique($GLOBALS['TL_CSS']) as $stylesheet)
			{
				$strStyleSheets .= '<link rel="stylesheet" href="' . $stylesheet . '" type="text/css" media="all" />' . "\n";
			}
		}

		// Add style sheets
		if (is_array($arrStyleSheets) && strlen($arrStyleSheets[0]))
		{
			$objStylesheets = $this->Database->execute("SELECT name, media FROM tl_style_sheet WHERE id IN (" . implode(', ', $arrStyleSheets) . ") ORDER BY FIELD(id, " . implode(', ', $arrStyleSheets) . ")");

			while ($objStylesheets->next())
			{
				$strStyleSheets .= sprintf('<link rel="stylesheet" href="%s" type="text/css" media="%s" />',
											$objStylesheets->name . '.css',
											implode(', ', deserialize($objStylesheets->media))) . "\n";
			}
		}

		$newsfeeds = deserialize($objLayout->newsfeeds);
		$calendarfeeds = deserialize($objLayout->calendarfeeds);

		// Add newsfeeds
		if (is_array($newsfeeds) && count($newsfeeds) > 0)
		{
			$objFeeds = $this->Database->execute("SELECT * FROM tl_news_archive WHERE id IN(" . implode(',', $newsfeeds) . ")");

			while($objFeeds->next())
			{
				$base = strlen($objFeeds->feedBase) ? $objFeeds->feedBase : $this->Environment->base;
				$strStyleSheets .= '<link rel="alternate" href="' . $base . $objFeeds->alias . '.xml" type="application/' . $objFeeds->format . '+xml" title="' . $objFeeds->title . '" />' . "\n";
			}
		}

		// Add calendarfeeds
		if (is_array($calendarfeeds) && count($calendarfeeds) > 0)
		{
			$objFeeds = $this->Database->execute("SELECT * FROM tl_calendar WHERE id IN(" . implode(',', $calendarfeeds) . ")");

			while($objFeeds->next())
			{
				$base = strlen($objFeeds->feedBase) ? $objFeeds->feedBase : $this->Environment->base;
				$strStyleSheets .= '<link rel="alternate" href="' . $base . $objFeeds->alias . '.xml" type="application/' . $objFeeds->format . '+xml" title="' . $objFeeds->title . '" />' . "\n";
			}
		}

		$strJavaScripts = '';

		// Add internal scripts
		if (is_array($GLOBALS['TL_JAVASCRIPT']) && count($GLOBALS['TL_JAVASCRIPT']))
		{
			foreach (array_unique($GLOBALS['TL_JAVASCRIPT']) as $javascript)
			{
				$strJavaScripts .= '<script type="text/javascript" src="' . $javascript . '"></script>' . "\n";
			}
		}

		$strHeadTags = '';

		// Add internal <head> tags
		if (is_array($GLOBALS['TL_HEAD']) && count($GLOBALS['TL_HEAD']))
		{
			foreach (array_unique($GLOBALS['TL_HEAD']) as $head)
			{
				$strHeadTags .= $head . "\n";
			}
		}

		$this->Template->stylesheets = $strStyleSheets;
		$this->Template->head = $strJavaScripts . $strHeadTags . $this->Template->head;
	}
}

?>