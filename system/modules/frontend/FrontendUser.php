<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class FrontendUser
 *
 * Provide methods to manage front end users.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Model
 */
class FrontendUser extends User
{

	/**
	 * Current object instance (do not remove)
	 * @var object
	 */
	protected static $objInstance;

	/**
	 * Name of the corresponding table
	 * @var string
	 */
	protected $strTable = 'tl_member';

	/**
	 * Name of the current cookie
	 * @var string
	 */
	protected $strCookie = 'FE_USER_AUTH';

	/**
	 * Path to the login script
	 * @var string
	 */
	protected $strLoginScript = 'index.php';

	/**
	 * Path to the protected file
	 * @var string
	 */
	protected $strRedirect = 'index.php';

	/**
	 * Group login page
	 * @var string
	 */
	protected $strLoginPage;


	/**
	 * Initialize the object
	 */
	protected function __construct()
	{
		parent::__construct();

		$this->strIp = $this->Environment->ip;
		$this->strHash = $this->Input->cookie($this->strCookie);
	}


	/**
	 * Set the current referer and save the session
	 */
	public function __destruct()
	{
		$session = $this->Session->getData();

		if (!isset($_GET['pdf']) && !isset($_GET['file']) && !isset($_GET['id']) && $session['referer']['current'] != $this->Environment->requestUri)
		{
			$session['referer']['last'] = $session['referer']['current'];
			$session['referer']['current'] = $this->Environment->requestUri;
		}

		$this->Session->setData($session);

		if (strlen($this->intId))
		{
			$this->Database->prepare("UPDATE " . $this->strTable . " SET session=? WHERE id=?")
						   ->execute(serialize($session), $this->intId);
		}
	}


	/**
	 * Extend parent getter class and modify some parameters
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'groups':
				return is_array($this->arrData['groups']) ? $this->arrData['groups'] : array($this->arrData['groups']);
				break;

			case 'loginPage':
				return $this->strLoginPage;
				break;

			default:
				return parent::__get($strKey);
				break;
		}
	}


	/**
	 * Return the current object instance (Singleton)
	 * @return object
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			self::$objInstance = new FrontendUser();
		}

		return self::$objInstance;
	}


	/**
	 * Set all user properties from a database record
	 * @param object
	 */
	protected function setUserFromDb()
	{
		$this->intId = $this->id;

		// Unserialize values
		foreach ($this->arrData as $k=>$v)
		{
			if (!is_numeric($v))
			{
				$this->$k = deserialize($v);
			}
		}

		$GLOBALS['TL_LANGUAGE'] = $this->language;
		$GLOBALS['TL_USERNAME'] = $this->username;

		// Make sure that groups is an array
		if (!is_array($this->groups))
		{
			$this->groups = strlen($this->groups) ? array($this->groups) : array();
		}

		$time = time();

		// Unset inactive groups
		$objGroups = $this->Database->prepare("SELECT id FROM tl_member_group WHERE disable!=1 AND (start='' OR start<?) AND (stop='' OR stop>?)")
									->execute($time, $time);

		$this->groups = array_intersect($this->groups, $objGroups->fetchEach('id'));

		// Get group login page
		$objGroup = $this->Database->prepare("SELECT * FROM tl_member_group WHERE id=? AND disable!=1 AND (start='' OR start<?) AND (stop='' OR stop>?)")
								   ->limit(1)
								   ->execute($this->groups[0], $time, $time);

		if ($objGroup->numRows && $objGroup->redirect && $objGroup->jumpTo)
		{
			$this->strLoginPage = $objGroup->jumpTo;
		}

		if (is_array($this->session))
		{
			$this->Session->setData($this->session);
			return;
		}

		$this->Database->prepare("UPDATE " . $this->strTable . " SET session='' WHERE id=?")
					   ->execute($this->intId);

		$this->session = array();
	}
}

?>