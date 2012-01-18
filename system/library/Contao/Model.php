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
	 * Name of the table
	 * @var string
	 */
	protected static $strTable;

	/**
	 * Primary key
	 * @var string
	 */
	protected static $strPk = 'id';

	/**
	 * Database result
	 * @var Database_Result
	 */
	protected $objResult;

	/**
	 * Data array
	 * @var array
	 */
	protected $arrData = array();

	/**
	 * True if the record exists
	 * @var boolean
	 */
	protected $blnExists = false;


	/**
	 * Optionally take a result set
	 * @param Database_Result
	 */
	public function __construct(\Database_Result $objResult=null)
	{
		parent::__construct();

		if ($objResult !== null)
		{
			$this->setData($objResult->row());
			$this->objResult = $objResult->reset();
			$this->blnExists = true;
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
			throw new \Exception('Array or object required to set data');
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
	 * Return the database result
	 * @return Database_Result
	 */
	public function getDbResult()
	{
		return $this->objResult;
	}


	/**
	 * Return the number of rows in the result set
	 * @return integer
	 */
	public function count()
	{
		return $this->objResult->numRows;
	}


	/**
	 * Return the first row
	 * @return boolean
	 */
	public function first()
	{
		$return = $this->objResult->first();

		if ($return !== false)
		{
			$this->setData($this->objResult->row());
			return true;
		}

		return false;
	}


	/**
	 * Return the previous row
	 * @return boolean
	 */
	public function prev()
	{
		$return = $this->objResult->prev();

		if ($return !== false)
		{
			$this->setData($this->objResult->row());
			return true;
		}

		return false;
	}


	/**
	 * Return the next row
	 * @return boolean
	 */
	public function next()
	{
		$return = $this->objResult->next();

		if ($return !== false)
		{
			$this->setData($this->objResult->row());
			return true;
		}

		return false;
	}


	/**
	 * Return the last row
	 * @return boolean
	 */
	public function last()
	{
		$return = $this->objResult->last();

		if ($return !== false)
		{
			$this->setData($this->objResult->row());
			return true;
		}

		return false;
	}


	/**
	 * Return the current row
	 * @return array
	 */
	public function row()
	{
		return $this->getData();
	}


	/**
	 * Reset the model
	 */
	public function reset()
	{
		$this->objResult->reset();
		$this->arrData = array();
	}


	/**
	 * Find a record and return the model
	 * @param mixed
	 * @param mixed
	 * @param string
	 * @param integer
	 * @param integer
	 * @return Model
	 */
	protected static function find($strColumn, $varValue, $strOrder=null, $intLimit=0, $intOffset=0)
	{
		if (static::$strTable == '')
		{
			return null;
		}

		$strQuery = "SELECT * FROM " . static::$strTable;

		if ($strColumn !== null)
		{
			if (is_array($strColumn))
			{
				$strQuery .= " WHERE " . implode(" AND ", $strColumn);
			}
			else
			{
				$strQuery .= " WHERE " . $strColumn . "=?";
			}
		}

		if ($strOrder !== null)
		{
			$strQuery .= " ORDER BY " . $strOrder;
		}

		$objStatement = \Database::getInstance()->prepare($strQuery);

		if ($intLimit > 0 || $intOffset > 0)
		{
			$objStatement->limit($intLimit, $intOffset);
		}

		$objStatement = static::preFind($objStatement);
		$objResult = $objStatement->execute($varValue);

		if ($objResult->numRows < 1)
		{
			return null;
		}

		$objResult = static::postFind($objResult);
		return new static($objResult);
	}


	/**
	 * Find records by one or more conditions
	 * @param mixed
	 * @param mixed
	 * @param string
	 * @param integer
	 * @param integer
	 * @return Model 
	 */
	public static function findBy($strColumn, $varValue, $strOrder=null, $intLimit=0, $intOffset=0)
	{
		return static::find($strColumn, $varValue, $strOrder, $intLimit, $intOffset);
	}


	/**
	 * Find a single record by its primary key
	 * @param mixed
	 * @return Model 
	 */
	public static function findByPk($varValue)
	{
		return static::findBy(static::$strPk, $varValue, null, 1);
	}


	/**
	 * Find a single record by one or more conditions
	 * @param mixed
	 * @param mixed
	 * @param string
	 * @return Model 
	 */
	public static function findOneBy($strColumn, $varValue, $strOrder=null)
	{
		return static::findBy($strColumn, $varValue, $strOrder, 1);
	}


	/**
	 * Find a single record by its ID or alias
	 * @param mixed
	 * @param mixed
	 * @return Model
	 */
	public static function findByIdOrAlias($varId, $varAlias)
	{
		return static::findOneBy(array("(id=? OR alias=?)"), array((is_numeric($varId) ? $varId : 0), $varAlias));
	}


	/**
	 * Find all records and return the model
	 * @param string
	 * @param integer
	 * @param integer
	 * @return Model
	 */
	public static function findAll($strOrder=null, $intLimit=0, $intOffset=0)
	{
		return static::findBy(null, null, $strOrder, $intLimit, $intOffset);
	}


	/**
	 * Modify the statement before it is executed
	 * @param Database_Statement
	 * @return Database_Statement
	 */
	protected static function preFind(\Database_Statement $objStatement)
	{
		return $objStatement;
	}


	/**
	 * Modify the result set before the model is created
	 * @param Database_Statement
	 * @return Database_Statement
	 */
	protected static function postFind(\Database_Result $objResult)
	{
		return $objResult;
	}


	/**
	 * Save the current record and return the number of affected rows or the last insert ID
	 * @param boolean
	 * @return integer
	 */
	public function save($blnForceInsert=false)
	{
		$this->import('Database');
		$arrSet = $this->preSave($this->arrData);

		if (!$this->blnExists || $blnForceInsert)
		{
			$this->blnExists = true;

			return $this->Database->prepare("INSERT INTO " . static::$strTable . " %s")
								  ->set($arrSet)
								  ->execute()
								  ->insertId;
		}
		else
		{
			return $this->Database->prepare("UPDATE " . static::$strTable . " %s WHERE " . static::$strPk . "=?")
								  ->set($arrSet)
								  ->execute($this->{static::$strPk})
								  ->affectedRows;
		}
	}


	/**
	 * Modify the current row before it is stored in the database
	 * @param array
	 * @return array
	 */
	protected function preSave($arrSet)
	{
		return $arrSet;
	}


	/**
	 * Delete the current record and return the number of affected rows
	 * @return integer
	 */
	public function delete()
	{
		$this->import('Database');

		return $this->Database->prepare("DELETE FROM " . static::$strTable . " WHERE " . static::$strPk . "=?")
							  ->execute($this->{static::$strPk})
							  ->affectedRows;
	}
}

?>