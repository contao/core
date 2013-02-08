<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class BackendTemplate
 *
 * Provide methods to handle back end templates.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
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

		// HOOK: add custom parse filters
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
		if (is_array($GLOBALS['TL_RTE']) && !empty($GLOBALS['TL_RTE']))
		{
			$this->base = $this->Environment->base;
			$this->uploadPath = $GLOBALS['TL_CONFIG']['uploadPath'];

			// Fallback to English if the user language is not supported
			$this->language = file_exists(TL_ROOT . '/plugins/tinyMCE/langs/' . $GLOBALS['TL_LANGUAGE'] . '.js') ? $GLOBALS['TL_LANGUAGE'] : 'en';

			foreach ($GLOBALS['TL_RTE'] as $file=>$fields)
			{
				$arrRteFields = array();

				foreach ($fields as $field)
				{
					$arrRteFields[] = $field['id'];
				}

				$this->rteFields = implode(',', $arrRteFields); // TinyMCE
				$this->ceFields = $fields; // Other RTEs
				$strFile = sprintf('%s/system/config/%s.php', TL_ROOT, $file);

				if (!file_exists($strFile))
				{
					throw new Exception(sprintf('Cannot find editor configuration file "%s.php"', $file));
				}

				ob_start();
				include($strFile);
				$this->rteConfig .= ob_get_contents();
				ob_end_clean();
			}
		}

		// Style sheets
		if (is_array($GLOBALS['TL_CSS']) && !empty($GLOBALS['TL_CSS']))
		{
			$strStyleSheets = '';

			foreach (array_unique($GLOBALS['TL_CSS']) as $stylesheet)
			{
				list($stylesheet, $media) = explode('|', $stylesheet);
				$strStyleSheets .= '<link rel="stylesheet" href="' . $this->addStaticUrlTo($stylesheet) . '" media="' . (($media != '') ? $media : 'all') . '">' . "\n";
			}

			$this->stylesheets = $strStyleSheets;
		}

		// JavaScripts
		if (is_array($GLOBALS['TL_JAVASCRIPT']) && !empty($GLOBALS['TL_JAVASCRIPT']))
		{
			$strJavaScripts = '';

			foreach (array_unique($GLOBALS['TL_JAVASCRIPT']) as $javascript)
			{
				$strJavaScripts .= '<script src="' . $this->addStaticUrlTo($javascript) . '"></script>' . "\n";
			}

			$this->javascripts = $strJavaScripts;
		}

		// MooTools scripts (added at the page bottom)
		if (is_array($GLOBALS['TL_MOOTOOLS']) && !empty($GLOBALS['TL_MOOTOOLS']))
		{
			$strMootools = '';

			foreach (array_unique($GLOBALS['TL_MOOTOOLS']) as $script)
			{
				$strMootools .= "\n" . trim($script) . "\n";
			}

			$this->mootools = $strMootools;
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

		// Add the browser and OS classes (see #3074)
		$ua = $this->Environment->agent;
		$strBuffer = str_replace('__ua__', $ua->class, $strBuffer);

		$this->strBuffer = $strBuffer;
		parent::output();
	}


	/**
	 * Return the locale string
	 * @return string
	 */
	protected function getLocaleString()
	{
		return 'var CONTAO_THEME="' . $this->getTheme() . '",'
			. 'CONTAO_COLLAPSE="' . $GLOBALS['TL_LANG']['MSC']['collapseNode'] . '",'
			. 'CONTAO_EXPAND="' . $GLOBALS['TL_LANG']['MSC']['expandNode'] . '",'
			. 'CONTAO_LOADING="' . $GLOBALS['TL_LANG']['MSC']['loadingData'] . '",'
			. 'CONTAO_SCRIPT_URL="' . TL_SCRIPT_URL . '",'
			. 'CONTAO_PATH="' . TL_PATH . '",'
			. 'REQUEST_TOKEN="' . REQUEST_TOKEN . '";';
	}


	/**
	 * Return the datepicker string
	 * 
	 * Fix the MooTools more parsers which incorrectly parse ISO-8601 and do
	 * not handle German date formats at all.
	 * @return string
	 */
	protected function getDateString()
	{
		return 'window.addEvent("domready",function(){'
			. 'Locale.define("en-US","Date",{'
				. 'months:["' . implode('","', $GLOBALS['TL_LANG']['MONTHS']) . '"],'
				. 'days:["' . implode('","', $GLOBALS['TL_LANG']['DAYS']) . '"],'
				. 'months_abbr:["' . implode('","', $GLOBALS['TL_LANG']['MONTHS_SHORT']) . '"],'
				. 'days_abbr:["' . implode('","', $GLOBALS['TL_LANG']['DAYS_SHORT']) . '"]'
			. '});'
			. 'Locale.define("en-US","DatePicker",{'
				. 'select_a_time:"' . $GLOBALS['TL_LANG']['DP']['select_a_time'] . '",'
				. 'use_mouse_wheel:"' . $GLOBALS['TL_LANG']['DP']['use_mouse_wheel'] . '",'
				. 'time_confirm_button:"' . $GLOBALS['TL_LANG']['DP']['time_confirm_button'] . '",'
				. 'apply_range:"' . $GLOBALS['TL_LANG']['DP']['apply_range'] . '",'
				. 'cancel:"' . $GLOBALS['TL_LANG']['DP']['cancel'] . '",'
				. 'week:"' . $GLOBALS['TL_LANG']['DP']['week'] . '"'
			. '});'
		. '});';
	}
}

?>