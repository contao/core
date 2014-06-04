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
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class BackendHelp
 *
 * Back end help wizard.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class BackendHelp extends \Backend
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
	 * Run the controller and parse the template
	 */
	public function run()
	{
		$table = \Input::get('table');
		$field = \Input::get('field');

		\System::loadLanguageFile($table);
		$this->loadDataContainer($table);

		$this->Template = new \BackendTemplate('be_help');
		$this->Template->rows = array();
		$this->Template->explanation = '';

		$arrData = $GLOBALS['TL_DCA'][$table]['fields'][$field];

		// Back end modules
		if ($table == 'tl_user_group' && $field == 'modules')
		{
			$rows = array();

			foreach (array_keys($GLOBALS['BE_MOD']) as $group)
			{
				$rows[] = array('headspan', $arrData['reference'][$group]);

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
				$rows[] = array('headspan', $arrData['reference'][$group]);

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
				$rows[] = array('headspan', $arrData['reference'][$group]);

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
					$rows[] = array('headspan', $arrData['reference'][$key]);

					foreach ($option as $opt)
					{
						$rows[] = $arrData['reference'][$opt];
					}
				}
				else
				{
					if (!is_array($arrData['reference'][$option]))
					{
						$rows[] = array('headspan', $arrData['reference'][$option]);
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
			\System::loadLanguageFile('explain');
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

		$this->Template->theme = \Backend::getTheme();
		$this->Template->base = \Environment::get('base');
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['helpWizardTitle']);
		$this->Template->charset = \Config::get('characterSet');
		$this->Template->headline = $arrData['label'][0] ?: $field;
		$this->Template->helpWizard = $GLOBALS['TL_LANG']['MSC']['helpWizard'];

		\Config::set('debugMode', false);
		$this->Template->output();
	}
}
