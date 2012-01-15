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
 * Class Model
 *
 * Provide active record functionality.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Model
 */
abstract class Model extends \System
{

	/**
	 * Name of the current table
	 * @var string
	 */
	protected static $strTable;

	/**
	 * Name of the column
	 * @var string
	 */
	protected $strColumn;

	/**
	 * Value of the column
	 * @var mixed
	 */
	protected $varValue;

	/**
	 * Database result
	 * @var Database_Result
	 */
	protected $resResult;

	/**
	 * Data array
	 * @var array
	 */
	protected $arrData = array();

	/**
	 * Belongs to
	 * @var array
	 */
	protected $arrBelongsTo = array();

	/**
	 * Has many
	 * @var array
	 */
	protected $arrHasMany = array();


	/**
	 * Optionally store a result set
	 * @param Database_Result
	 */
	public function __construct(\Database_Result $resResult=null)
	{
		parent::__construct();

		if ($resResult !== null)
		{
			$this->resResult = $resResult;
			$this->setData($resResult->row());
		} 
	}


	/**
	 * Set an object property
	 * @param string
	 * @param mixed
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
		return $this->arrData[$strKey];
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
	 * Set the current record from an object or array
	 * @param array
	 * @throws Exception
	 */
	public function setData($varData)
	{
		if (is_object($varData))
		{
			$varData = get_object_vars($varData);
		}

		if (!is_array($varData))
		{
			throw new \Exception('Array required to set data');
		}

		$this->arrData = $varData;
	}


	/**
	 * Return the current record as associative array
	 * @return array
	 */
	public function getData()
	{
		return $this->arrData;
	}


	/**
	 * Return the database result object
	 * @return Database_Result
	 */
	public function getDatabaseResult()
	{
		return $this->resResult;
	}


	/**
	 * Set the current record from a database result row
	 * @param Database_Result
	 * @param string
	 * @param string
	 */
	public function setFromRow(\Database_Result $resResult, $strTable, $strColumn)
	{
		self::$strTable = $strTable;
		$this->strColumn = $strColumn;
		$this->varValue = $resResult->$strColumn;
		$this->resResult = $resResult;
		$this->arrData = $resResult->row();
	}


	/**
	 * Find a record by a certain field and return the database result
	 * @param string
	 * @param mixed
	 * @return Model
	 */
	public static function findBy($strColumn, $varValue, $intLimit=1, $strOperator='=')
	{
		if (self::$strTable == '')
		{
			return null;
		}

		$objStatement = Database::getInstance()->prepare("SELECT * FROM " . self::$strTable . " WHERE " . $strColumn . $strOperator . "?");

		if ($intLimit > 0)
		{
			$objStatement->limit($intLimit);
		}

		$resResult = $objStatement->executeUncached($varValue);

		if ($resResult->numRows < 1)
		{
			return null;
		}

		return new self($resResult);
	}


	/**
	 * Save the current record and return the number of affected rows or the last insert ID
	 * @param boolean
	 * @return integer
	 */
	public function save($blnForceInsert=false)
	{
		$this->import('Database');

		if ($this->resResult === null || $blnForceInsert)
		{
			return $this->Database->prepare("INSERT INTO " . $this->strTable . " %s")
								  ->set($this->arrData)
								  ->execute()
								  ->insertId;
		}
		else
		{
			return $this->Database->prepare("UPDATE " . $this->strTable . " %s WHERE " . $this->strColumn . "=?")
								  ->set($this->arrData)
								  ->execute($this->varValue)
								  ->affectedRows;
		}
	}


	/**
	 * Delete the current record and return the number of affected rows
	 * @return integer
	 */
	public function delete()
	{
		$this->import('Database');

		return $this->Database->prepare("DELETE FROM " . $this->strTable . " WHERE " . $this->strColumn . "=?")
							  ->execute($this->varValue)
							  ->affectedRows;
	}
}

?>