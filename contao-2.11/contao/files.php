<?php

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
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once('../system/initialize.php');


/**
 * Class FileManager
 *
 * Popup file manager controller.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class FileManager extends Backend
{

	/**
	 * Current Ajax object
	 * @var object
	 */
	protected $objAjax;


	/**
	 * Initialize the controller
	 * 
	 * 1. Import user
	 * 2. Call parent constructor
	 * 3. Authenticate user
	 * 4. Load language files
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
	 * Run controller and parse the login template
	 */
	public function run()
	{
		$this->Template = new BackendTemplate('be_files');
		$this->Template->main = '';

		if ($this->Environment->isAjaxRequest)
		{
			$this->objAjax = new Ajax($this->Input->post('action'));
			$this->objAjax->executePreActions();
		}

		$this->Template->main .= $this->getBackendModule('files');

		if (!strlen($this->Template->headline))
		{
			$this->Template->headline = $GLOBALS['TL_CONFIG']['websiteTitle'];
		}

		$this->Template->theme = $this->getTheme();
		$this->Template->base = $this->Environment->base;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->pageOffset = $this->Input->cookie('BE_PAGE_OFFSET');
		$this->Template->error = ($this->Input->get('act') == 'error') ? $GLOBALS['TL_LANG']['ERR']['general'] : '';
		$this->Template->skipNavigation = $GLOBALS['TL_LANG']['MSC']['skipNavigation'];
		$this->Template->request = ampersand($this->Environment->request);
		$this->Template->top = $GLOBALS['TL_LANG']['MSC']['backToTop'];
		$this->Template->be27 = !$GLOBALS['TL_CONFIG']['oldBeTheme'];
		$this->Template->expandNode = $GLOBALS['TL_LANG']['MSC']['expandNode'];
		$this->Template->collapseNode = $GLOBALS['TL_LANG']['MSC']['collapseNode'];

		$this->Template->output();
	}
}


/**
 * Instantiate controller
 */
$objFileManager = new FileManager();
$objFileManager->run();

?>