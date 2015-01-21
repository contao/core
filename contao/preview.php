<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Set the script name
 */
define('TL_SCRIPT', 'contao/preview.php');


/**
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once '../system/initialize.php';


/**
 * Set up the front end preview frames.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Preview extends Backend
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
		System::loadLanguageFile('default');
	}


	/**
	 * Run the controller and parse the template
	 */
	public function run()
	{
		$this->Template = new BackendTemplate('be_preview');

		$this->Template->base = Environment::get('base');
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['fePreview']);
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->site = Input::get('site', true);

		if (Input::get('page'))
		{
			$this->Template->url = $this->redirectToFrontendPage(Input::get('page'), Input::get('article'), true);
		}
		else
		{
			$this->Template->url = Environment::get('base');
		}

		$GLOBALS['TL_CONFIG']['debugMode'] = false;
		$this->Template->output();
	}
}


/**
 * Instantiate the controller
 */
$objPreview = new Preview();
$objPreview->run();
