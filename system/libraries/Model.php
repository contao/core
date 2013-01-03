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
 * Class Model
 *
 * Provide active record functionality.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Model
 */
abstract class Model extends System
{

	/**
	 * Name of the current table
	 * @var string
	 */
	protected $strTable;

	/**
	 * Name of the field that references the active record
	 * @var string
	 */
	protected $strRefField;

	/**
	 * Value of the field that references the active record
	 * @var mixed
	 */
	protected $varRefId;

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
	 * Indicate whether the current record exists
	 * @var boolean
	 */
	protected $blnRecordExists = false;


	/**
	 * Import the database object
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->import('Database');
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
			throw new Exception('Array required to set data');
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
	public function setFromRow(Database_Result $resResult, $strTable, $strRefField)
	{
		$this->strTable = $strTable;
		$this->strRefField = $strRefField;
		$this->varRefId = $resResult->$strRefField;

		$this->resResult = $resResult;
		$this->arrData = $resResult->row();
		$this->blnRecordExists = true;
	}


	/**
	 * Find a record by its reference field and return true if it has been found
	 * @param string
	 * @param integer
	 * @return boolean
	 */
	public function findBy($strRefField, $varRefId)
	{
		$this->blnRecordExists = false;
		$this->strRefField = $strRefField;
		$this->varRefId = $varRefId;

		$resResult = $this->Database->prepare("SELECT * FROM " . $this->strTable . " WHERE " . $this->strRefField . "=?")
									->executeUncached($this->varRefId);

		if ($resResult->numRows == 1)
		{
			$this->resResult = $resResult;
			$this->arrData = $resResult->fetchAssoc();
			$this->blnRecordExists = true;

			return true;
		}

		return false;
	}


	/**
	 * Save the current record and return the number of affected rows or the last insert ID
	 * @param boolean
	 * @return integer
	 */
	public function save($blnForceInsert=false)
	{
		if ($this->blnRecordExists && !$blnForceInsert)
		{
			return $this->Database->prepare("UPDATE " . $this->strTable . " %s WHERE " . $this->strRefField . "=?")
								  ->set($this->arrData)
								  ->execute($this->varRefId)
								  ->affectedRows;
		}
		else
		{
			return $this->Database->prepare("INSERT INTO " . $this->strTable . " %s")
								  ->set($this->arrData)
								  ->execute()
								  ->insertId;
		}
	}


	/**
	 * Delete the current record and return the number of affected rows
	 * @return integer
	 */
	public function delete()
	{
		return $this->Database->prepare("DELETE FROM " . $this->strTable . " WHERE " . $this->strRefField . "=?")
							  ->execute($this->varRefId)
							  ->affectedRows;
	}
}

?>