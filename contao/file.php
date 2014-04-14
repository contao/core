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
define('TL_SCRIPT', 'contao/file.php');


/**
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once '../system/initialize.php';


/**
 * Class FilePicker
 *
 * Back end page picker.
 * @copyright  Leo Feyer 2005-2014
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
		System::loadLanguageFile('default');
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
		$strDriver = 'DC_' . $GLOBALS['TL_DCA'][$strTable]['config']['dataContainer'];
		$objDca = new $strDriver($strTable);

		// AJAX request
		if ($_POST && Environment::get('isAjaxRequest'))
		{
			$this->objAjax->executePostActions($objDca);
		}

		$this->Session->set('filePickerRef', \Environment::get('request'));
		$arrValues = array_filter(explode(',', Input::get('value')));

		// Convert UUIDs to binary
		foreach ($arrValues as $k=>$v)
		{
			// Can be a UUID or a path
			if (Validator::isUuid($v))
			{
				$arrValues[$k] = String::uuidToBin($v);
			}
		}

		// Prepare the widget
		$objFileTree = new $GLOBALS['BE_FFL']['fileSelector'](array(
			'strId'    => $strField,
			'strTable' => $strTable,
			'strField' => $strField,
			'strName'  => $strField,
			'varValue' => $arrValues
		), $objDca);

		$this->Template->main = $objFileTree->generate();
		$this->Template->theme = Backend::getTheme();
		$this->Template->base = Environment::get('base');
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['filepicker']);
		$this->Template->charset = Config::get('characterSet');
		$this->Template->addSearch = false;
		$this->Template->search = $GLOBALS['TL_LANG']['MSC']['search'];
		$this->Template->action = ampersand(Environment::get('request'));
		$this->Template->value = $this->Session->get('file_selector_search');
		$this->Template->manager = $GLOBALS['TL_LANG']['MSC']['fileManager'];
		$this->Template->managerHref = 'contao/main.php?do=files&amp;popup=1';
		$this->Template->breadcrumb = $GLOBALS['TL_DCA']['tl_files']['list']['sorting']['breadcrumb'];

		Config::set('debugMode', false);
		$this->Template->output();
	}
}


/**
 * Instantiate the controller
 */
$objFilePicker = new FilePicker();
$objFilePicker->run();
