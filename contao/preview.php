<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 * @license    LGPL
 */


/**
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once '../system/initialize.php';


/**
 * Class Preview
 *
 * Set up the front end preview frames.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
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
		$this->loadLanguageFile('default');
	}


	/**
	 * Run the controller and parse the template
	 * @return void
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
