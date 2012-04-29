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
use \Database, \Database_Statement, \Database_Result, \DcaExtractor, \Model_Collection, \Model_QueryBuilder, \System, \Exception;


/**
 * Class Model
 *
 * Provide active record and a bit of ORM functionality.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Model
 */
abstract class Model extends System
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
	 * Data array
	 * @var array
	 */
	protected $arrData = array();

	/**
	 * Relations
	 * @var array
	 */
	protected $arrRelations = array();

	/**
	 * Related
	 * @var array
	 */
	protected $arrRelated = array();


	/**
	 * Load the relations and optionally process a result set
	 * @param \Database_Result
	 */
	public function __construct(Database_Result $objResult=null)
	{
		parent::__construct();

		$objRelations = new DcaExtractor(static::$strTable);
		$this->arrRelations = $objRelations->getRelations();

		if ($objResult !== null)
		{
			$this->arrData = $objResult->row();

			// Look for joined fields
			foreach ($this->arrData as $k=>$v)
			{
				if (strpos($k, '__') !== false)
				{
					list($key, $field) = explode('__', $k, 2);

					// Create the related model
					if (!isset($this->arrRelated[$key]))
					{
						$table = $this->arrRelations[$key]['table'];
						$strClass = $this->getModelClassFromTable($table);
						$this->arrRelated[$key] = new $strClass();
					}

					$this->arrRelated[$key]->$field = $v;
					unset($this->arrData[$k]);
				}
			}
		}
	}


	/**
	 * Unset the primary key if an object is cloned
	 * @return mixed|void
	 */
	public function __clone()
	{
		unset($this->arrData[static::$strPk]);
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
	 * @return mixed|null
	 */
	public function __get($strKey)
	{
		if (isset($this->arrData[$strKey]))
		{
			return $this->arrData[$strKey];
		}

		return null;
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
	 * Return the current record as associative array
	 * @return array
	 */
	public function row()
	{
		return $this->arrData;
	}


	/**
	 * Set the current record from an array
	 * @param array
	 * @return \Model
	 */
	public function setRow(Array $arrData)
	{
		$this->arrData = $arrData;
		return $this;
	}


	/**
	 * Save the current record and return the number of affected rows or the last insert ID
	 * @param boolean
	 * @return \Model
	 */
	public function save($blnForceInsert=false)
	{
		$arrSet = $this->preSave($this->row());

		if (isset($this->{static::$strPk}) && !$blnForceInsert)
		{
			Database::getInstance()->prepare("UPDATE " . static::$strTable . " %s WHERE " . static::$strPk . "=?")
								   ->set($arrSet)
								   ->execute($this->{static::$strPk});
		}
		else
		{
			$stmt = Database::getInstance()->prepare("INSERT INTO " . static::$strTable . " %s")
										   ->set($arrSet)
										   ->execute();

			if (static::$strPk == 'id')
			{
				$this->id = $stmt->insertId;
			}
		}

		return $this;
	}


	/**
	 * Modify the current row before it is stored in the database
	 * @param array
	 * @return array
	 */
	protected function preSave(Array $arrSet)
	{
		return $arrSet;
	}


	/**
	 * Delete the current record and return the number of affected rows
	 * @return integer
	 */
	public function delete()
	{
		return Database::getInstance()->prepare("DELETE FROM " . static::$strTable . " WHERE " . static::$strPk . "=?")
									  ->execute($this->{static::$strPk})
									  ->affectedRows;
	}


	/**
	 * Lazy load related records
	 * @param string
	 * @return \Model|\Model_Collection
	 * @throws \Exception
	 */
	public function getRelated($strKey)
	{
		// The related model has been loaded before
		if (isset($this->arrRelated[$strKey]))
		{
			return $this->arrRelated[$strKey];
		}

		// The field or relation does not exist
		if (!isset($this->$strKey) || !isset($this->arrRelations[$strKey]))
		{
			throw new Exception("Field $strKey does not seem to be related");
		}

		$arrRelation = $this->arrRelations[$strKey];
		$strClass = $this->getModelClassFromTable($arrRelation['table']);

		// Load the related record(s)
		if ($arrRelation['type'] == 'hasOne' || $arrRelation['type'] == 'belongsTo')
		{
			$objModel = $strClass::findOneBy($arrRelation['field'], $this->$strKey);
			$this->arrRelated[$strKey] = $objModel;
		}
		elseif ($arrRelation['type'] == 'hasMany' || $arrRelation['type'] == 'belongsToMany')
		{
			$arrValues = deserialize($this->$strKey, true);
			$strField = $arrRelation['table'] . '.' . $arrRelation['field'];
			$objModel = $strClass::findBy(array($strField . " IN('" . implode("','", $arrValues) . "')"), null, array('order'=>Database::getInstance()->findInSet($strField, $arrValues)));
			$this->arrRelated[$strKey] = $objModel;
		}

		return $this->arrRelated[$strKey];
	}


	/**
	 * Find a single record by its primary key
	 * @param mixed
	 * @param array
	 * @return \Model|null
	 */
	public static function findByPk($varValue, Array $arrOptions=array())
	{
		$arrOptions = array_merge($arrOptions, array
		(
			'limit'  => 1,
			'column' => static::$strPk,
			'value'  => $varValue
		));

		return static::find($arrOptions);
	}


	/**
	 * Find a single record by its ID or alias
	 * @param mixed
	 * @param array
	 * @return \Model|null
	 */
	public static function findByIdOrAlias($varId, Array $arrOptions=array())
	{
		$t = static::$strTable;

		$arrOptions = array_merge($arrOptions, array
		(
			'limit'  => 1,
			'column' => array("($t.id=? OR $t.alias=?)"),
			'value'  => array((is_numeric($varId) ? $varId : 0), $varId)
		));

		return static::find($arrOptions);
	}


	/**
	 * Find a single record by various criteria
	 * @param mixed
	 * @param mixed
	 * @param array
	 * @return \Model|null
	 */
	public static function findOneBy($strColumn, $varValue, Array $arrOptions=array())
	{
		$arrOptions = array_merge($arrOptions, array
		(
			'limit'  => 1,
			'column' => $strColumn,
			'value'  => $varValue
		));

		return static::find($arrOptions);
	}


	/**
	 * Find records by various criteria
	 * @param mixed
	 * @param mixed
	 * @param array
	 * @return \Model_Collection|null
	 */
	public static function findBy($strColumn, $varValue, Array $arrOptions=array())
	{
		$arrOptions = array_merge($arrOptions, array
		(
			'column' => $strColumn,
			'value'  => $varValue
		));

		return static::find($arrOptions);
	}


	/**
	 * Find all records
	 * @param array
	 * @return \Model_Collection|null
	 */
	public static function findAll(Array $arrOptions=array())
	{
		return static::find($arrOptions);
	}


	/**
	 * Magic method to call $this->findByName() instead of $this->findBy('name')
	 * @param string
	 * @param array
	 * @return mixed|null
	 */
	public static function __callStatic($name, $args)
	{
		if (strncmp($name, 'findBy', 6) === 0)
		{
			return call_user_func('static::findBy', lcfirst(substr($name, 6)), array_shift($args), $args);
		}
		elseif (strncmp($name, 'findOneBy', 9) === 0)
		{
			return call_user_func('static::findOneBy', lcfirst(substr($name, 9)), array_shift($args), $args);
		}

		return null;
	}


	/**
	 * Find records and return the model or model collection
	 * @param array
	 * @return \Model|\Model_Collection|null
	 */
	protected static function find(Array $arrOptions)
	{
		if (static::$strTable == '')
		{
			return null;
		}

		$arrOptions['table'] = static::$strTable;
		$strQuery = Model_QueryBuilder::find($arrOptions);

		$objStatement = Database::getInstance()->prepare($strQuery);

		// Defaults for limit and offset
		if (!isset($arrOptions['limit']))
		{
			$arrOptions['limit'] = 0;
		}
		if (!isset($arrOptions['offset']))
		{
			$arrOptions['offset'] = 0;
		}

		// Limit
		if ($arrOptions['limit'] > 0 || $arrOptions['offset'] > 0)
		{
			$objStatement->limit($arrOptions['limit'], $arrOptions['offset']);
		}

		$objStatement = static::preFind($objStatement);
		$objResult = $objStatement->execute($arrOptions['value']);

		if ($objResult->numRows < 1)
		{
			return null;
		}

		$objResult = static::postFind($objResult);
		return ($arrOptions['limit'] == 1) ? new static($objResult) : new Model_Collection($objResult, static::$strTable);
	}


	/**
	 * Modify the statement before it is executed
	 * @param \Database_Statement
	 * @return \Database_Statement
	 */
	protected static function preFind(Database_Statement $objStatement)
	{
		return $objStatement;
	}


	/**
	 * Modify the result set before the model is created
	 * @param \Database_Result
	 * @return \Database_Result
	 */
	protected static function postFind(Database_Result $objResult)
	{
		return $objResult;
	}


	/**
	 * Return the number of records matching certain criteria
	 * @param mixed
	 * @param mixed
	 * @return integer
	 */
	public static function countBy($strColumn=null, $varValue=null)
	{
		if (static::$strTable == '')
		{
			return 0;
		}

		$strQuery = Model_QueryBuilder::count(array
		(
			'table'  => static::$strTable,
			'column' => $strColumn,
			'value'  => $varValue
		));

		return Database::getInstance()->prepare($strQuery)->execute($varValue)->count;
	}


	/**
	 * Return the total number of rows
	 * @return integer
	 */
	public static function countAll()
	{
		return static::countBy();
	}
}
