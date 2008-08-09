<?php

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
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
 * Show error messages
 */
@ini_set('display_errors', 1);
@error_reporting(1);


/**
 * Class FtpCheck
 *
 * Back end FTP check.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class FtpCheck extends Controller
{

	/**
	 * Initialize the controller
	 */
	public function __construct()
	{
		parent::__construct();

		$GLOBALS['TL_LANGUAGE'] = 'en';
		$GLOBALS['TL_CONFIG']['showHelp'] = false;
		$GLOBALS['TL_CONFIG']['displayErrors'] = true;
	}


	/**
	 * Run controller and parse the login template
	 */
	public function run()
	{
		$this->Template = new BackendTemplate('be_ftp');


		/**
		 * Lock the tool if there are too many login attempts
		 */
		if ($GLOBALS['TL_CONFIG']['installCount'] >= 3)
		{
			$this->Template->locked = true;
			$this->outputAndExit();
		}


		/**
		 * Authenticate user
		 */
		if ($this->Input->post('FORM_SUBMIT') == 'tl_login')
		{
			$password =  sha1($this->Input->post('password', true));

			if (strlen($password) && $password != 'da39a3ee5e6b4b0d3255bfef95601890afd80709')
			{
				// Set cookie
				if ($password == $GLOBALS['TL_CONFIG']['installPassword'])
				{
					$this->setCookie('TL_INSTALL_AUTH', md5($this->Environment->ip.session_id()), (time()+300), $GLOBALS['TL_CONFIG']['websitePath']);
					$this->Config->update("\$GLOBALS['TL_CONFIG']['installCount']", 0);

					$this->reload();
				}

				// Increase count
				$this->Config->update("\$GLOBALS['TL_CONFIG']['installCount']", $GLOBALS['TL_CONFIG']['installCount'] + 1);
			}

			$this->Template->passwordError = 'Invalid password!';
		}

		// Check cookie
		if (!$this->Input->cookie('TL_INSTALL_AUTH'))
		{
			$this->Template->login = true;
			$this->outputAndExit();
		}

		// Renew cookie
		$this->setCookie('TL_INSTALL_AUTH', md5($this->Environment->ip.session_id()), (time()+300), $GLOBALS['TL_CONFIG']['websitePath']);


		/**
		 * Check FTP credentials
		 */
		$this->Template->ftpHost = $GLOBALS['TL_CONFIG']['ftpHost'];
		$this->Template->ftpUser = $GLOBALS['TL_CONFIG']['ftpUser'];
		$this->Template->ftpPath = $GLOBALS['TL_CONFIG']['ftpPath'];

		// Check if enabled
		if (!$GLOBALS['TL_CONFIG']['useFTP'])
		{
			$this->Template->ftpDisabled = true;
			$this->outputAndExit();
		}

		$this->Template->ftpHostError = true;
		$this->Template->ftpUserError = true;
		$this->Template->ftpPathError = true;

		// Connect to host
		if (($resFtp = ftp_connect($GLOBALS['TL_CONFIG']['ftpHost'])) == false)
		{
			$this->outputAndExit();
		}

		$this->Template->ftpHostError = false;

		// Log in
		if (!ftp_login($resFtp, $GLOBALS['TL_CONFIG']['ftpUser'], $GLOBALS['TL_CONFIG']['ftpPass']))
		{
			$this->outputAndExit();
		}

		$this->Template->ftpUserError = false;

		// Change to TYPOlight directory
		if (!ftp_chdir($resFtp, $GLOBALS['TL_CONFIG']['ftpPath']))
		{
			$this->outputAndExit();
		}

		$this->Template->ftpPathError = false;


		/**
		 * Output the template file
		 */
		$this->outputAndExit();
	}


	/**
	 * Output the template file and exit
	 */
	private function outputAndExit()
	{
		$this->Template->theme = $this->getTheme();
		$this->Template->base = $this->Environment->base;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->isMac = preg_match('/mac/i', $this->Environment->httpUserAgent);
		$this->Template->pageOffset = $this->Input->cookie('BE_PAGE_OFFSET');
		$this->Template->action = ampersand($this->Environment->request);

		$this->Template->output();
		exit;
	}
}


/**
 * Instantiate controller
 */
$objFtpCheck = new FtpCheck();
$objFtpCheck->run();

?>