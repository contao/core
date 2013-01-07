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
 * Class FilePicker
 *
 * Back end page picker.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class FilePicker extends Backend
{

	/**
	 * Current Ajax object
	 * @var object
	 */
	protected $objAjax;


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
	}


	/**
	 * Run the controller and parse the template
	 */
	public function run()
	{
		$this->Template = new BackendTemplate('be_picker');
		$this->Template->main = '';

		// Ajax request
		if ($_POST && Environment::get('isAjaxRequest'))
		{
			$this->objAjax = new Ajax(Input::post('action'));
			$this->objAjax->executePreActions();
		}

		$strTable = Input::get('table');
		$strField = Input::get('field');

		// Define the current ID
		define('CURRENT_ID', (Input::get('table') ? $this->Session->get('CURRENT_ID') : Input::get('id')));

		$this->loadDataContainer($strTable);
		$objDca = new DC_Table($strTable);

		// AJAX request
		if ($_POST && Environment::get('isAjaxRequest'))
		{
			$this->objAjax->executePostActions($objDca);
		}

		$objFileTree = new $GLOBALS['BE_FFL']['fileSelector'](array(
			'strId'    => $strField,
			'strTable' => $strTable,
			'strField' => $strField,
			'strName'  => $strField,
			'varValue' => explode(',', Input::get('value'))
		), $objDca);

		$this->Template->main = $objFileTree->generate();
		$this->Template->theme = $this->getTheme();
		$this->Template->base = Environment::get('base');
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['filepicker']);
		$this->Template->headline = $GLOBALS['TL_LANG']['MSC']['ppHeadline'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->options = $this->createPageList();
		$this->Template->expandNode = $GLOBALS['TL_LANG']['MSC']['expandNode'];
		$this->Template->collapseNode = $GLOBALS['TL_LANG']['MSC']['collapseNode'];
		$this->Template->loadingData = $GLOBALS['TL_LANG']['MSC']['loadingData'];
		$this->Template->search = $GLOBALS['TL_LANG']['MSC']['search'];
		$this->Template->action = ampersand(Environment::get('request'));
		$this->Template->value = $this->Session->get('file_selector_search');

		$GLOBALS['TL_CONFIG']['debugMode'] = false;
		$this->Template->output();
	}
}


/**
 * Instantiate the controller
 */
$objFilePicker = new FilePicker();
$objFilePicker->run();
