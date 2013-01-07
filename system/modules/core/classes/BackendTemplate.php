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
 * Class BackendTemplate
 *
 * Provide methods to handle back end templates.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class BackendTemplate extends \Template
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
	 * @throws \Exception
	 */
	public function output()
	{
		// Rich text editor configuration
		if (is_array($GLOBALS['TL_RTE']) && !empty($GLOBALS['TL_RTE']))
		{
			$this->base = \Environment::get('base');
			$this->uploadPath = $GLOBALS['TL_CONFIG']['uploadPath'];

			// Fallback to English if the user language is not supported
			$this->language = file_exists(TL_ROOT . '/assets/tinymce/langs/' . $GLOBALS['TL_LANGUAGE'] . '.js') ? $GLOBALS['TL_LANGUAGE'] : 'en';

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
					throw new \Exception(sprintf('Cannot find editor configuration file "%s.php"', $file));
				}

				ob_start();
				include $strFile;
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
				$strStyleSheets .= '<link rel="stylesheet" href="' . $this->addStaticUrlTo($stylesheet) . '"' . (($media != '' && $media != 'all') ? ' media="' . $media . '"' : '') . '>' . "\n";
			}

			$this->stylesheets = $strStyleSheets;
		}

		// Add the debug style sheet
		if ($GLOBALS['TL_CONFIG']['debugMode'])
		{
			$this->stylesheets .= '<link rel="stylesheet" href="' . $this->addStaticUrlTo('assets/contao/css/debug.css') . '">' . "\n";
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
		$ua = \Environment::get('agent');
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
		return
			'var Contao={'
				. 'theme:"' . $this->getTheme() . '",'
				. 'lang:{'
					. 'close:"' . $GLOBALS['TL_LANG']['MSC']['close'] . '",'
					. 'collapse:"' . $GLOBALS['TL_LANG']['MSC']['collapseNode'] . '",'
					. 'expand:"' . $GLOBALS['TL_LANG']['MSC']['expandNode'] . '",'
					. 'loading:"' . $GLOBALS['TL_LANG']['MSC']['loadingData'] . '",'
					. 'apply:"' . $GLOBALS['TL_LANG']['MSC']['apply'] . '"'
				. '},'
				. 'script_url:"' . TL_ASSETS_URL . '",'
				. 'request_token:"' . REQUEST_TOKEN . '"'
			. '};';
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
