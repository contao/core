<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Set the script name
 */
define('TL_SCRIPT', 'contao/changelog.php');


/**
 * Initialize the system
 */
define('TL_MODE', 'BE');
require dirname(__DIR__) . '/system/initialize.php';


/**
 * Class Changelog
 *
 * Show the changelog to an authenticated user.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class Changelog extends Backend
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
	}


	/**
	 * Run the controller
	 */
	public function run()
	{
		// Parse the changelog file
		$strBuffer = file_get_contents(TL_ROOT . '/system/docs/CHANGELOG.md');

		// Remove carriage returns (see #4190)
		$strBuffer = str_replace("\r", '', $strBuffer);

		// Convert to HTML
		$strBuffer = \Michelf\MarkdownExtra::defaultTransform($strBuffer);

		// Add the template
		$this->Template = new BackendTemplate('be_changelog');

		// Assign the template variables
		$this->Template->content = $strBuffer;
		$this->Template->theme = Backend::getTheme();
		$this->Template->base = Environment::get('base');
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['changelog']);
		$this->Template->charset = Config::get('characterSet');

		Config::set('debugMode', false);
		$this->Template->output();
	}
}


/**
 * Instantiate the controller
 */
$objChangelog = new Changelog();
$objChangelog->run();
