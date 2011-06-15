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
		if (count($GLOBALS['TL_RTE']))
		{
			$this->base = $this->Environment->base;
			$this->uploadPath = $GLOBALS['TL_CONFIG']['uploadPath'];

			foreach ($GLOBALS['TL_RTE'] as $file=>$fields)
			{
				if (strncmp($file, 'tiny', 4) === 0)
				{
					// Concat the field IDs for TinyMCE
					$arrRteFields = array();

					foreach ($fields as $field)
					{
						$arrRteFields[] = $field['id'];
					}

					$this->rteFields = implode(',', $arrRteFields);

					// Fallback to English if the user language is not supported
					$this->language = file_exists(TL_ROOT . '/plugins/tinyMCE/langs/' . $GLOBALS['TL_LANGUAGE'] . '.js') ? $GLOBALS['TL_LANGUAGE'] : 'en';
				}
				else
				{
					// Otherwise simply pass the fields array
					$this->ceFields = $fields;
				}

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
		if (is_array($GLOBALS['TL_CSS']) && count($GLOBALS['TL_CSS']))
		{
			$strStyleSheets = '';

			foreach (array_unique($GLOBALS['TL_CSS']) as $stylesheet)
			{
				list($stylesheet, $media) = explode('|', $stylesheet);
				$strStyleSheets .= '<link rel="stylesheet" href="' . $stylesheet . '" media="' . (($media != '') ? $media : 'all') . '">' . "\n";
			}

			$this->stylesheets = $strStyleSheets;
		}

		// JavaScripts
		if (is_array($GLOBALS['TL_JAVASCRIPT']) && count($GLOBALS['TL_JAVASCRIPT']))
		{
			$strJavaScripts = '';

			foreach (array_unique($GLOBALS['TL_JAVASCRIPT']) as $javascript)
			{
				$strJavaScripts .= '<script src="' . $javascript . '"></script>' . "\n";
			}

			$this->javascripts = $strJavaScripts;
		}

		// MooTools scripts (added at the page bottom)
		if (is_array($GLOBALS['TL_MOOTOOLS']) && count($GLOBALS['TL_MOOTOOLS']))
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
}

?>