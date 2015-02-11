<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Back end help wizard.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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

		/** @var \BackendTemplate|object $objTemplate */
		$objTemplate = new \BackendTemplate('be_help');
		$objTemplate->rows = array();
		$objTemplate->explanation = '';

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

			$objTemplate->rows = $rows;
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

			$objTemplate->rows = $rows;
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

			$objTemplate->rows = $rows;
		}

		// Add the reference
		elseif (!empty($arrData['reference']))
		{
			$rows = array();
			$options = is_array($arrData['options']) ? $arrData['options'] : array_keys($arrData['reference']);

			// Unset the predefined image sizes
			unset($options['image_sizes']);

			foreach ($options as $key=>$option)
			{
				if (is_array($option))
				{
					if (is_array($arrData['reference'][$key]))
					{
						$rows[] = array('headspan', $arrData['reference'][$key][0]);
					}
					else
					{
						$rows[] = array('headspan', $arrData['reference'][$key]);
					}

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

			$objTemplate->rows = $rows;
		}

		// Add an explanation
		if (isset($arrData['explanation']))
		{
			\System::loadLanguageFile('explain');
			$key = $arrData['explanation'];

			if (!is_array($GLOBALS['TL_LANG']['XPL'][$key]))
			{
				$objTemplate->explanation = trim($GLOBALS['TL_LANG']['XPL'][$key]);
			}
			else
			{
				$objTemplate->rows = $GLOBALS['TL_LANG']['XPL'][$key];
			}
		}

		$objTemplate->theme = \Backend::getTheme();
		$objTemplate->base = \Environment::get('base');
		$objTemplate->language = $GLOBALS['TL_LANGUAGE'];
		$objTemplate->title = specialchars($GLOBALS['TL_LANG']['MSC']['helpWizardTitle']);
		$objTemplate->charset = \Config::get('characterSet');
		$objTemplate->headline = $arrData['label'][0] ?: $field;
		$objTemplate->helpWizard = $GLOBALS['TL_LANG']['MSC']['helpWizard'];

		\Config::set('debugMode', false);
		$objTemplate->output();
	}
}
