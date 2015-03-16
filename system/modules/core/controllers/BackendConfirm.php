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
 * Confirm an invalid token URL.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class BackendConfirm extends \Backend
{

	/**
	 * Initialize the controller
	 *
	 * 1. Import the user
	 * 2. Call the parent constructor
	 * 3. Authenticate the user
	 * 4. Load the language files
	 * DO NOT CHANGE THIS ORDER!
	 */
	public function __construct()
	{
		$this->import('BackendUser', 'User');
		parent::__construct();

		$this->User->authenticate();

		\System::loadLanguageFile('default');
		\System::loadLanguageFile('modules');
	}


	/**
	 * Run the controller
	 */
	public function run()
	{
		// Redirect to the back end home page
		if (\Input::post('FORM_SUBMIT') == 'invalid_token_url')
		{
			list($strUrl) = explode('?', $this->Session->get('INVALID_TOKEN_URL'));
			$this->redirect($strUrl);
		}

		/** @var \BackendTemplate|object $objTemplate */
		$objTemplate = new \BackendTemplate('be_confirm');

		// Prepare the URL
		$url = preg_replace('/(\?|&)rt=[^&]*/', '', $this->Session->get('INVALID_TOKEN_URL'));
		$objTemplate->href = ampersand($url . ((strpos($url, '?') !== false) ? '&rt=' : '?rt=') . REQUEST_TOKEN);

		$vars = array();
		list(, $request) = explode('?', $url, 2);

		// Extract the arguments
		foreach (explode('&', $request) as $arg)
		{
			list($key, $value) = explode('=', $arg, 2);
			$vars[$key] = $value;
		}

		$arrInfo = array();

		// Provide more information about the link (see #4007)
		foreach ($vars as $k=>$v)
		{
			switch ($k)
			{
				default:
					$arrInfo[$k] = $v;
					break;

				case 'do':
					$arrInfo['do'] = $GLOBALS['TL_LANG']['MOD'][$v][0];
					break;

				case 'id':
					$arrInfo['id'] = 'ID ' . $v;
					break;
			}
		}

		// Use the first table if none is given
		if (!isset($arrInfo['table']))
		{
			foreach ($GLOBALS['BE_MOD'] as $category=>$modules)
			{
				if (isset($GLOBALS['BE_MOD'][$category][$vars['do']]))
				{
					$arrInfo['table'] = $GLOBALS['BE_MOD'][$category][$vars['do']]['tables'][0];
					break;
				}
			}
		}

		\System::loadLanguageFile($arrInfo['table']);

		// Override the action label
		if (isset($arrInfo['clipboard']))
		{
			$arrInfo['act'] = $GLOBALS['TL_LANG']['MSC']['clearClipboard'];
		}
		elseif (isset($arrInfo['mode']) && !isset($arrInfo['act']))
		{
			if ($arrInfo['mode'] == 'create')
			{
				$arrInfo['act'] = $GLOBALS['TL_LANG'][$arrInfo['table']]['new'][0];
			}
			elseif ($arrInfo['mode'] == 'cut' || $arrInfo['mode'] == 'copy')
			{
				$arrInfo['act'] = $GLOBALS['TL_LANG'][$arrInfo['table']][$arrInfo['mode']][0];
			}
		}
		else
		{
			$arrInfo['act'] = $GLOBALS['TL_LANG'][$arrInfo['table']][$arrInfo['act']][0];
		}

		unset($arrInfo['pid']);
		unset($arrInfo['clipboard']);
		unset($arrInfo['ref']);
		unset($arrInfo['mode']);

		// Template variables
		$objTemplate->confirm = true;
		$objTemplate->link = specialchars($url);
		$objTemplate->info = $arrInfo;
		$objTemplate->labels = $GLOBALS['TL_LANG']['CONFIRM'];
		$objTemplate->explain = $GLOBALS['TL_LANG']['ERR']['invalidTokenUrl'];
		$objTemplate->cancel = $GLOBALS['TL_LANG']['MSC']['cancelBT'];
		$objTemplate->continue = $GLOBALS['TL_LANG']['MSC']['continue'];
		$objTemplate->theme = \Backend::getTheme();
		$objTemplate->base = \Environment::get('base');
		$objTemplate->language = $GLOBALS['TL_LANGUAGE'];
		$objTemplate->title = specialchars($GLOBALS['TL_LANG']['MSC']['invalidTokenUrl']);
		$objTemplate->charset = \Config::get('characterSet');

		$objTemplate->output();
	}
}
