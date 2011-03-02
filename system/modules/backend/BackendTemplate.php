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
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class BackendTemplate
 *
 * Provide methods to handle back end templates.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class BackendTemplate extends Template
{

	/**
	 * Add a hook to modify the template output
	 * @return string
	 */
	public function parse()
	{
		$strBuffer = parent::parse();

		// HOOK: add custom parse filter
		if (isset($GLOBALS['TL_HOOKS']['parseBackendTemplate']) && is_array($GLOBALS['TL_HOOKS']['parseBackendTemplate']))
		{
			foreach ($GLOBALS['TL_HOOKS']['parseBackendTemplate'] as $callback)
			{
				$this->import($callback[0]);
				$strBuffer = $this->$callback[0]->$callback[1]($strBuffer, $this->strTemplate);
			}
		}

		return $strBuffer;
	}


	/**
	 * Parse the template file, add the TinyMCE configuration and print it to the screen
	 */
	public function output()
	{
		// Rich text editor configuration
		if (count($GLOBALS['TL_RTE']))
		{
			$this->base = $this->Environment->base;
			$this->brNewLine = $GLOBALS['TL_CONFIG']['pNewLine'] ? false : true;
			$this->rteFields = implode(',', $GLOBALS['TL_RTE']['fields']);
			$this->ceFields = $GLOBALS['TL_RTE']['fields'];
			$this->ceField = $GLOBALS['TL_RTE']['fields'][0]; // Backwards compatibility
			$this->uploadPath = $GLOBALS['TL_CONFIG']['uploadPath'];
			list($type, $this->syntax) = explode('|', $GLOBALS['TL_RTE']['type']);
			$strFile = sprintf('%s/system/config/%s.php', TL_ROOT, $type);

			if (!file_exists($strFile))
			{
				throw new Exception(sprintf('Cannot find rich text editor configuration file "%s.php"', $GLOBALS['TL_RTE']['type']));
			}

			// Fallback to English if the user language is not supported
			if (file_exists(TL_ROOT . '/plugins/tinyMCE/langs/' . $GLOBALS['TL_LANGUAGE'] . '.js'))
			{
				$this->language = $GLOBALS['TL_LANGUAGE'];
			}
			else
			{
				$this->language = 'en';
			}

			ob_start();
			include($strFile);
			$this->rteConfig = ob_get_contents();
			ob_end_clean();
		}

		// Style sheets
		if (is_array($GLOBALS['TL_CSS']) && count($GLOBALS['TL_CSS']))
		{
			$strStyleSheets = '';

			foreach ($GLOBALS['TL_CSS'] as $stylesheet)
			{
				list($stylesheet, $media) = explode('|', $stylesheet);
				$strStyleSheets .= '<link rel="stylesheet" type="text/css" href="' . $stylesheet . '" media="' . (($media != '') ? $media : 'all') . '" />' . "\n";
			}

			$this->stylesheets = $strStyleSheets;
		}

		// JavaScripts
		if (is_array($GLOBALS['TL_JAVASCRIPT']) && count($GLOBALS['TL_JAVASCRIPT']))
		{
			$strJavaScripts = '';

			foreach ($GLOBALS['TL_JAVASCRIPT'] as $javascript)
			{
				$strJavaScripts .= '<script type="text/javascript" src="' . $javascript . '"></script>' . "\n";
			}

			$this->javascripts = $strJavaScripts;
		}

		$strBuffer = $this->parse();

		// HOOK: add custom output filter
		if (isset($GLOBALS['TL_HOOKS']['outputBackendTemplate']) && is_array($GLOBALS['TL_HOOKS']['outputBackendTemplate']))
		{
			foreach ($GLOBALS['TL_HOOKS']['outputBackendTemplate'] as $callback)
			{
				$this->import($callback[0]);
				$strBuffer = $this->$callback[0]->$callback[1]($strBuffer, $this->strTemplate);
			}
		}

		$this->strBuffer = $strBuffer;
		parent::output();
	}
}

?>