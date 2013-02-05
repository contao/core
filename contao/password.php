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
 * Class Index
 *
 * Provides a form to change the back end password.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class Index extends Backend
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

		if ($this->Input->post('FORM_SUBMIT') == 'tl_password')
		{
			$pw = $this->Input->post('password');
			$cnf = $this->Input->post('confirm');

			// Do not allow special characters
			if (preg_match('/[#\(\)\/<=>]/', html_entity_decode($this->Input->post('password'))))
			{
				$this->addErrorMessage($GLOBALS['TL_LANG']['ERR']['extnd']);
			}
			// Passwords do not match
			elseif ($pw != $cnf)
			{
				$this->addErrorMessage($GLOBALS['TL_LANG']['ERR']['passwordMatch']);
			}
			// Password too short
			elseif (utf8_strlen($pw) < $GLOBALS['TL_CONFIG']['minPasswordLength'])
			{
				$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['ERR']['passwordLength'], $GLOBALS['TL_CONFIG']['minPasswordLength']));
			}
			// Password and username are the same
			elseif ($pw == $this->User->username)
			{
				$this->addErrorMessage($GLOBALS['TL_LANG']['ERR']['passwordName']);
			}
			// Save the data
			else
			{
				list(, $strSalt) = explode(':', $this->User->password);
				$strPassword = sha1($strSalt . $pw);

				// Make sure the password has been changed
				if ($strPassword . ':' . $strSalt == $this->User->password)
				{
					$this->addErrorMessage($GLOBALS['TL_LANG']['MSC']['pw_change']);
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

					$strSalt = substr(md5(uniqid(mt_rand(), true)), 0, 23);
					$strPassword = sha1($strSalt . $pw);

					$this->Database->prepare("UPDATE tl_user SET password=?, pwChange='' WHERE id=?")
								   ->execute($strPassword . ':' . $strSalt, $this->User->id);

					$this->addConfirmationMessage($GLOBALS['TL_LANG']['MSC']['pw_changed']);
					$this->redirect('contao/main.php');
				}
			}

			$this->reload();
		}

		$this->Template->theme = $this->getTheme();
		$this->Template->messages = $this->getMessages();
		$this->Template->base = $this->Environment->base;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->action = ampersand($this->Environment->request);
		$this->Template->headline = $GLOBALS['TL_LANG']['MSC']['pw_change'];
		$this->Template->submitButton = specialchars($GLOBALS['TL_LANG']['MSC']['continue']);
		$this->Template->password = $GLOBALS['TL_LANG']['MSC']['password'][0];
		$this->Template->confirm = $GLOBALS['TL_LANG']['MSC']['confirm'][0];
		$this->Template->disableCron = $GLOBALS['TL_CONFIG']['disableCron'];

		$this->Template->output();
	}
}


/**
 * Instantiate the controller
 */
$objIndex = new Index();
$objIndex->run();

?>