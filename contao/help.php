<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class Help extends Backend
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
	 * Run the controller and parse the template
	 */
	public function run()
	{
		$table = $this->Input->get('table');
		$field = $this->Input->get('field');

		$this->loadLanguageFile($table);
		$this->loadDataContainer($table);

		$this->Template = new BackendTemplate('be_help');
		$this->Template->rows = array();
		$this->Template->explanation = '';

		$arrData = $GLOBALS['TL_DCA'][$table]['fields'][$field];

		// Back end modules
		if ($table == 'tl_user_group' && $field == 'modules')
		{
			$rows = array();

			foreach (array_keys($GLOBALS['BE_MOD']) as $group)
			{
				$rows[] = array('colspan', $arrData['reference'][$group]);

				foreach ($GLOBALS['BE_MOD'][$group] as $module=>$class)
				{
					$rows[] = $arrData['reference'][$module];
				}
			}

			$this->Template->rows = $rows;
		}

		// Front end modules
		elseif ($table == 'tl_module' && $field == 'type')
		{
			$rows = array();

			foreach (array_keys($GLOBALS['FE_MOD']) as $group)
			{
				$rows[] = array('colspan', $arrData['reference'][$group]);

				foreach ($GLOBALS['FE_MOD'][$group] as $module=>$class)
				{
					$rows[] = $arrData['reference'][$module];
				}
			}

			$this->Template->rows = $rows;
		}

		// Content elements
		elseif ($table == 'tl_content' && $field == 'type')
		{
			$rows = array();

			foreach (array_keys($GLOBALS['TL_CTE']) as $group)
			{
				$rows[] = array('colspan', $arrData['reference'][$group]);

				foreach ($GLOBALS['TL_CTE'][$group] as $element=>$class)
				{
					$rows[] = $arrData['reference'][$element];
				}
			}

			$this->Template->rows = $rows;
		}

		// Add the reference
		elseif (!empty($arrData['reference']))
		{
			$rows = array();
			$options = is_array($arrData['options']) ? $arrData['options'] : array_keys($arrData['reference']);

			foreach ($options as $key=>$option)
			{
				if (is_array($option))
				{
					$rows[] = array('colspan', $arrData['reference'][$key]);

					foreach ($option as $opt)
					{
						$rows[] = $arrData['reference'][$opt];
					}
				}
				else
				{
					if (!is_array($arrData['reference'][$option]))
					{
						$rows[] = array('colspan', $arrData['reference'][$option]);
					}
					else
					{
						$rows[] = $arrData['reference'][$option];
					}
				}
			}

			$this->Template->rows = $rows;
		}

		// Add an explanation
		if (isset($arrData['explanation']))
		{
			$this->loadLanguageFile('explain');
			$key = $arrData['explanation'];

			if (!is_array($GLOBALS['TL_LANG']['XPL'][$key]))
			{
				$this->Template->explanation = trim($GLOBALS['TL_LANG']['XPL'][$key]);
			}
			else
			{
				$this->Template->rows = $GLOBALS['TL_LANG']['XPL'][$key];
			}
		}

		$this->Template->theme = $this->getTheme();
		$this->Template->base = $this->Environment->base;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->headline = strlen($arrData['label'][0]) ? $arrData['label'][0] : $field;
		$this->Template->helpWizard = $GLOBALS['TL_LANG']['MSC']['helpWizard'];

		$this->Template->output();
	}
}


/**
 * Instantiate the controller
 */
$objHelp = new Help();
$objHelp->run();

?>