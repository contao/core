<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao;


/**
 * Back end help wizard.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class BackendPassword extends \Backend
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
	 * Run the controller and parse the password template
	 */
	public function run()
	{
		/** @var \BackendTemplate|object $objTemplate */
		$objTemplate = new \BackendTemplate('be_password');

		if (\Input::post('FORM_SUBMIT') == 'tl_password')
		{
			$pw = \Input::postUnsafeRaw('password');
			$cnf = \Input::postUnsafeRaw('confirm');

			// The passwords do not match
			if ($pw != $cnf)
			{
				\Message::addError($GLOBALS['TL_LANG']['ERR']['passwordMatch']);
			}
			// Password too short
			elseif (utf8_strlen($pw) < \Config::get('minPasswordLength'))
			{
				\Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['passwordLength'], \Config::get('minPasswordLength')));
			}
			// Password and username are the same
			elseif ($pw == $this->User->username)
			{
				\Message::addError($GLOBALS['TL_LANG']['ERR']['passwordName']);
			}
			// Save the data
			else
			{
				// Make sure the password has been changed
				if (password_verify($pw, $this->User->password))
				{
					\Message::addError($GLOBALS['TL_LANG']['MSC']['pw_change']);
				}
				else
				{
					$this->loadDataContainer('tl_user');

					// Trigger the save_callback
					if (is_array($GLOBALS['TL_DCA']['tl_user']['fields']['password']['save_callback']))
					{
						$dc = new \DC_Table('tl_user');
						$dc->id = $this->User->id;

						foreach ($GLOBALS['TL_DCA']['tl_user']['fields']['password']['save_callback'] as $callback)
						{
							if (is_array($callback))
							{
								$this->import($callback[0]);
								$pw = $this->{$callback[0]}->{$callback[1]}($pw, $dc);
							}
							elseif (is_callable($callback))
							{
								$pw = $callback($pw, $dc);
							}
						}
					}

					$objUser = \UserModel::findByPk($this->User->id);
					$objUser->pwChange = '';
					$objUser->password = password_hash($pw, PASSWORD_DEFAULT);
					$objUser->save();

					\Message::addConfirmation($GLOBALS['TL_LANG']['MSC']['pw_changed']);
					$this->redirect('contao/main.php');
				}
			}

			$this->reload();
		}

		$objTemplate->theme = \Backend::getTheme();
		$objTemplate->messages = \Message::generate();
		$objTemplate->base = \Environment::get('base');
		$objTemplate->language = $GLOBALS['TL_LANGUAGE'];
		$objTemplate->title = specialchars($GLOBALS['TL_LANG']['MSC']['pw_new']);
		$objTemplate->charset = \Config::get('characterSet');
		$objTemplate->action = ampersand(\Environment::get('request'));
		$objTemplate->headline = $GLOBALS['TL_LANG']['MSC']['pw_change'];
		$objTemplate->submitButton = specialchars($GLOBALS['TL_LANG']['MSC']['continue']);
		$objTemplate->password = $GLOBALS['TL_LANG']['MSC']['password'][0];
		$objTemplate->confirm = $GLOBALS['TL_LANG']['MSC']['confirm'][0];

		$objTemplate->output();
	}
}
