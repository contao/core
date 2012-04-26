<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    System
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class FeedItem
 *
 * Provide methods to generate RSS/Atom feed items.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class FeedItem extends \System
{

	/**
	 * Data array
	 * @var array
	 */
	protected $arrData = array();


	/**
	 * Take an array of arguments and initialize the object
	 * @param array
	 */
	public function __construct($arrData=null)
	{
		parent::__construct();

		if (is_array($arrData))
		{
			$this->arrData = $arrData;
		}
	}


	/**
	 * Set an object property
	 * @param string
	 * @param mixed
	 * @return void
	 */
	public function __set($strKey, $varValue)
	{
		$this->arrData[$strKey] = $varValue;
	}


	/**
	 * Return an object property
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		if (isset($this->arrData[$strKey]))
		{
			return $this->arrData[$strKey];
		}

		return parent::__get($strKey);
	}


	/**
	 * Check whether a property is set
	 * @param string
	 * @return boolean
	 */
	public function __isset($strKey)
	{
		return isset($this->arrData[$strKey]);
	}


	/**
	 * Add an enclosure
	 * @param string
	 * @return void
	 */
	public function addEnclosure($strFile)
	{
		if ($strFile == '' || !file_exists(TL_ROOT . '/' . $strFile))
		{
			return;
		}

		$objFile = new \File($strFile);

		$this->arrData['enclosure'][] = array
		(
			'url' => $this->Environment->base . $strFile,
			'length' => $objFile->size,
			'type' => $objFile->mime
		);
	}
}
