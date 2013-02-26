<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class RequestToken
 *
 * This class provides methods to set and validate request tokens.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Library
 */
class RequestToken extends System
{

	/**
	 * Current object instance (Singleton)
	 * @var RequestToken
	 */
	protected static $objInstance;

	/**
	 * Token
	 * @var string
	 */
	protected $strToken;


	/**
	 * Load the token or generate a new one
	 */
	protected function __construct()
	{
		$this->strToken = @$_SESSION['REQUEST_TOKEN'];

		// Backwards compatibility
		if (is_array($this->strToken))
		{
			$this->strToken = null;
			unset($_SESSION['REQUEST_TOKEN']);
		}

		// Generate a new token
		if ($this->strToken == '')
		{
			$this->strToken = md5(uniqid(mt_rand(), true));
			$_SESSION['REQUEST_TOKEN'] = $this->strToken;
		}

		// Set the REQUEST_TOKEN constant
		if (!defined('REQUEST_TOKEN'))
		{
			define('REQUEST_TOKEN', $this->strToken);
		}
	}


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final private function __clone() {}


	/**
	 * Return the current object instance (Singleton)
	 * @return RequestToken
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			self::$objInstance = new self();
		}

		return self::$objInstance;
	}


	/**
	 * Return the token
	 * @return string
	 */
	public function get()
	{
		return $this->strToken;
	}


	/**
	 * Validate a token
	 * @param string
	 * @return boolean
	 */
	public function validate($strToken)
	{
		return ($strToken != '' && $this->strToken != '' && $strToken == $this->strToken);
	}
}

?>