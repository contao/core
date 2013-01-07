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
 * Class ChangePassword
 *
 * Handle back end password changes.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ChangePassword extends Backend
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
	 * Run the controller and parse the password template
	 */
	public function run()
	{
		$this->Template = new BackendTemplate('be_password');

		if (Input::post('FORM_SUBMIT') == 'tl_password')
		{
			$pw = Input::post('password', true);
			$cnf = Input::post('confirm', true);

			// The passwords do not match
			if ($pw != $cnf)
			{
				Message::addError($GLOBALS['TL_LANG']['ERR']['passwordMatch']);
			}
			// Password too short
			elseif (utf8_strlen($pw) < $GLOBALS['TL_CONFIG']['minPasswordLength'])
			{
				Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['passwordLength'], $GLOBALS['TL_CONFIG']['minPasswordLength']));
			}
			// Password and username are the same
			elseif ($pw == $this->User->username)
			{
				Message::addError($GLOBALS['TL_LANG']['ERR']['passwordName']);
			}
			// Save the data
			else
			{
				// Make sure the password has been changed
				if (crypt($pw, $this->User->password) == $this->User->password)
				{
					Message::addError($GLOBALS['TL_LANG']['MSC']['pw_change']);
				}
				else
				{
					$this->loadDataContainer('tl_user');

					// Trigger the save_callback
					if (is_array($GLOBALS['TL_DCA']['tl_user']['fields']['password']['save_callback']))
					{
						foreach ($GLOBALS['TL_DCA']['tl_user']['fields']['password']['save_callback'] as $callback)
						{
							$this->import($callback[0]);
							$pw = $this->$callback[0]->$callback[1]($pw);
						}
					}

					$objUser = UserModel::findByPk($this->User->id);
					$objUser->pwChange = '';
					$objUser->password = Encryption::hash($pw);
					$objUser->save();

					Message::addConfirmation($GLOBALS['TL_LANG']['MSC']['pw_changed']);
					$this->redirect('contao/main.php');
				}
			}

			$this->reload();
		}

		$this->Template->theme = $this->getTheme();
		$this->Template->messages = Message::generate();
		$this->Template->base = Environment::get('base');
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['pw_new']);
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->action = ampersand(Environment::get('request'));
		$this->Template->headline = $GLOBALS['TL_LANG']['MSC']['pw_change'];
		$this->Template->submitButton = specialchars($GLOBALS['TL_LANG']['MSC']['continue']);
		$this->Template->password = $GLOBALS['TL_LANG']['MSC']['password'][0];
		$this->Template->confirm = $GLOBALS['TL_LANG']['MSC']['confirm'][0];

		$this->Template->output();
	}
}


/**
 * Instantiate the controller
 */
$objChangePassword = new ChangePassword();
$objChangePassword->run();
