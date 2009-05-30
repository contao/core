<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class Request
 *
 * Provide methods to handle HTTP request.
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Library
 */
class Request
{

	/**
	 * HTTP object
	 * @var object
	 */
	protected $objHttp;


	/**
	 * Instantiate object and load http plugin
	 * @throws Exception
	 */
	public function __construct()
	{
		$strPluginPath = TL_ROOT . '/plugins/http';

		if (!is_dir($strPluginPath))
		{
			throw new Exception('Plugin http required');
		}

		include_once($strPluginPath . '/class.http.php');
		$this->objHttp = new Http();
	}


	/**
	 * Set an object property
	 * @param string
	 * @param mixed
	 * @throws Exception
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'data':
				$this->objHttp->setParams($varValue);
				break;

			case 'method':
				$this->objHttp->setMethod(strtoupper($varValue));
				break;

			default:
				throw new Exception(sprintf('Invalid argument "%s"', $strKey));
				break;
		}
	}


	/**
	 * Return an object property
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'error':
				return $this->objHttp->getError();
				break;

			case 'code':
				return $this->objHttp->getStatus();
				break;

			case 'response':
				return $this->objHttp->getResult();
				break;

			case 'headers':
				return $this->objHttp->getHeaders();
				break;

			default:
				return null;
				break;
		}
	}


	/**
	 * Return true if there has been an error
	 * @return boolean
	 */
	public function hasError()
	{
		return ($this->objHttp->getError() != '');
	}


	/**
	 * Perform an HTTP request (handle GET, POST, PUT and any other HTTP request)
	 * @param string
	 * @param string
	 * @param string
	 */
	public function send($strUrl, $strData=false, $strMethod=false)
	{
		if ($strData)
		{
			$this->objHttp->setParams($strData);
		}

		if ($strMethod)
		{
			$this->objHttp->setMethod(strtoupper($strMethod));
		}

		$this->objHttp->execute($strUrl);
	}
}

?>