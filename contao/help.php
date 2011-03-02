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
 * Class Help
 *
 * Back end help wizard.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class Help extends Backend
{

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
	 * Run controller and parse the template
	 */
	public function run()
	{
		$this->loadLanguageFile($this->Input->get('table'));
		$this->loadDataContainer($this->Input->get('table'));

		$this->Template = new BackendTemplate('be_help');

		// Add reference
		if (count($GLOBALS['TL_DCA'][$this->Input->get('table')]['fields'][$this->Input->get('field')]['reference']))
		{
			$rows = array();
			$options = is_array($GLOBALS['TL_DCA'][$this->Input->get('table')]['fields'][$this->Input->get('field')]['options']) ? $GLOBALS['TL_DCA'][$this->Input->get('table')]['fields'][$this->Input->get('field')]['options'] : array_keys($GLOBALS['TL_DCA'][$this->Input->get('table')]['fields'][$this->Input->get('field')]['reference']);

			foreach ($options as $option)
			{
				if (is_array($GLOBALS['TL_DCA'][$this->Input->get('table')]['fields'][$this->Input->get('field')]['reference'][$option]) && strlen($GLOBALS['TL_DCA'][$this->Input->get('table')]['fields'][$this->Input->get('field')]['reference'][$option][1]))
				{
					$rows[] = $GLOBALS['TL_DCA'][$this->Input->get('table')]['fields'][$this->Input->get('field')]['reference'][$option];
				}
			}

			$this->Template->rows = $rows;
		}

		// Add explanation
		if (strlen($GLOBALS['TL_DCA'][$this->Input->get('table')]['fields'][$this->Input->get('field')]['explanation']))
		{
			$this->loadLanguageFile('explain');
			$key = $GLOBALS['TL_DCA'][$this->Input->get('table')]['fields'][$this->Input->get('field')]['explanation'];

			if (!is_array($GLOBALS['TL_LANG']['XPL'][$key]))
			{
				$this->Template->explanation = trim($GLOBALS['TL_LANG']['XPL'][$key]);
			}

			else
			{
				$this->Template->rows = $GLOBALS['TL_LANG']['XPL'][$key];
			}
		}

		$this->output();
	}


	/**
	 * Output the template file
	 */
	protected function output()
	{
		$this->Template->theme = $this->getTheme();
		$this->Template->base = $this->Environment->base;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->headline = strlen($GLOBALS['TL_DCA'][$this->Input->get('table')]['fields'][$this->Input->get('field')]['label'][0]) ? $GLOBALS['TL_DCA'][$this->Input->get('table')]['fields'][$this->Input->get('field')]['label'][0] : $this->Input->get('field');
		$this->Template->helpWizard = $GLOBALS['TL_LANG']['MSC']['helpWizard'];

		$this->Template->output();
	}
}


/**
 * Instantiate controller
 */
$objHelp = new Help();
$objHelp->run();

?>