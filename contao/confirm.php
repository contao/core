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
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once '../system/initialize.php';


/**
 * Class Confirm
 *
 * Confirm an invalid token URL.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class Confirm extends Backend
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

		$this->loadLanguageFile('default');
		$this->loadLanguageFile('modules');
	}


	/**
	 * Run the controller
	 */
	public function run()
	{
		// Redirect to the back end home page
		if (Input::post('FORM_SUBMIT') == 'invalid_token_url')
		{
			list($strUrl) = explode('?', $this->Session->get('INVALID_TOKEN_URL'));
			$this->redirect($strUrl);
		}

		$this->Template = new BackendTemplate('be_confirm');

		// Prepare the URL
		$url = preg_replace('/(\?|&)rt=[^&]*/', '', $this->Session->get('INVALID_TOKEN_URL'));
		$this->Template->href = ampersand($url . ((strpos($url, '?') !== false) ? '&rt=' : '?rt=') . REQUEST_TOKEN);

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

		$this->loadLanguageFile($arrInfo['table']);

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
		unset($arrInfo['mode']);

		// Template variables
		$this->Template->confirm = true;
		$this->Template->link = specialchars($url);
		$this->Template->info = $arrInfo;
		$this->Template->labels = $GLOBALS['TL_LANG']['CONFIRM'];
		$this->Template->explain = $GLOBALS['TL_LANG']['ERR']['invalidTokenUrl'];
		$this->Template->cancel = $GLOBALS['TL_LANG']['MSC']['cancelBT'];
		$this->Template->continue = $GLOBALS['TL_LANG']['MSC']['continue'];
		$this->Template->theme = $this->getTheme();
		$this->Template->base = Environment::get('base');
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['invalidTokenUrl']);
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];

		$this->Template->output();
	}
}


/**
 * Instantiate the controller
 */
$objConfirm = new Confirm();
$objConfirm->run();
