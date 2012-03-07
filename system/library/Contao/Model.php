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
 * Provide active record and a bit of ORM functionality.
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
	 * Data array
	 * @var array
	 */
	protected $arrData = array();


	/**
	 * Optionally take a result set
	 * @param Database_Result
	 */
	public function __construct(\Database_Result $objResult=null)
	{
		parent::__construct();

		if ($objResult !== null)
		{
			$this->setData($objResult);
		} 
	}


	/**
	 * Unset the primary key if an object is cloned
	 */
	public function __clone()
	{
		unset($this->arrData[static::$strPk]);
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
		return isset($this->arrData[$strKey]) ? $this->arrData[$strKey] : null;
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
	 * Set the current record from a Database_Result
	 * @param Database_Result
	 */
	public function setData(\Database_Result $objResult)
	{
		$this->arrData = $objResult->row();

		// Look for joined fields and make them an array
		foreach ($this->arrData as $k=>$v)
		{
			// E.g. author__id becomes author['id']
			if (strpos($k, '__') !== false)
			{
				list($key, $field) = explode('__', $k);

				if (!is_array($this->arrData[$key]))
				{
					$this->arrData[$key] = array();
				}

				$this->arrData[$key][$field] = $v;
				unset($this->arrData[$k]);
			}
		}
	}


	/**
	 * Be compatible with the Database_Result interface
	 * @return integer
	 */
	public function count()
	{
		return 1;
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
	 */
	public function setRow(Array $arrData)
	{
		$this->arrData = $arrData;
	}


	/**
	 * Find a record and return the model
	 * @param array
	 * @return Model|null
	 */
	protected static function find(Array $arrOptions)
	{
		if (static::$strTable == '')
		{
			return null;
		}

		$arrOptions['table'] = static::$strTable;
		$strQuery = \Model_QueryBuilder::find($arrOptions);

		$objStatement = \Database::getInstance()->prepare($strQuery)->limit(1);
		$objStatement = static::preFind($objStatement);

		$objResult = $objStatement->execute($arrOptions['value']);

		if ($objResult->numRows < 1)
		{
			return null;
		}

		$objResult = static::postFind($objResult);
		return new static($objResult);
	}


	/**
	 * Find a record by one or more conditions
	 * @param mixed
	 * @param mixed
	 * @param array
	 * @return Model|null
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
	 * Find a record by its primary key
	 * @param mixed
	 * @param array
	 * @return Model|null
	 */
	public static function findByPk($varValue, Array $arrOptions=array())
	{
		$arrOptions = array_merge($arrOptions, array
		(
			'column' => static::$strPk,
			'value'  => $varValue
		));

		return static::find($arrOptions);
	}


	/**
	 * Find a record by its ID or alias
	 * @param mixed
	 * @param array
	 * @return Model|null
	 */
	public static function findByIdOrAlias($varId, Array $arrOptions=array())
	{
		$t = static::$strTable;

		$arrOptions = array_merge($arrOptions, array
		(
			'column' => array("($t.id=? OR $t.alias=?)"),
			'value'  => array((is_numeric($varId) ? $varId : 0), $varId)
		));

		return static::find($arrOptions);
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
	 * @param Database_Result
	 * @return Database_Result
	 */
	protected static function postFind(\Database_Result $objResult)
	{
		return $objResult;
	}


	/**
	 * Lazy load related records
	 * @param string
	 * @throws Exception
	 */
	public function getRelated($key)
	{
		if (!isset($this->$key))
		{
			return;
		}

		// Load the DCA extract
		$objRelated = new \DcaExtractor(static::$strTable);
		$arrRelations = $objRelated->getRelations();

		// No relations defined
		if (empty($arrRelations) || !isset($arrRelations[$key]))
		{
			throw new \Exception("Field $key does not seem to be related");
		}

		// Return if the relation has been loaded eagerly
		if ($arrRelations[$key]['type'] == 'eager')
		{
			return;
		}

		// Get the class name without suffix (second parameter)
		$strName = $this->convertTableNameToModelClass($arrRelations[$key]['table'], true);

		// Load the related record(s)
		if ($arrRelations[$key]['type'] == 'hasOne' || $arrRelations[$key]['type'] == 'belongsTo')
		{
			$strClass = $strName . 'Model';
			$objModel = $strClass::findBy($arrRelations[$key]['field'], $this->$key);

			if ($objModel !== null)
			{
				$this->$key = $objModel->row();
			}
		}
		elseif ($arrRelations[$key]['type'] == 'hasMany' || $arrRelations[$key]['type'] == 'belongsToMany')
		{
			$arrValues = deserialize($this->$key, true);
			$arrColumns = array($arrRelations[$key]['field'] . " IN('" . implode("','", $arrValues) . "')");
			$strOrder = \Database::getInstance()->findInSet($arrRelations[$key]['field'], $arrValues);

			$strCollectionClass = $strName . 'Collection';
			$objModel = $strClass::findBy($arrColumns, null, $strOrder);

			if ($objModel !== null)
			{
				$set = array();

				while ($objModel->next())
				{
					$set[] = $objModel->row();
				}

				$this->$key = $set;
			}
		}
	}


	/**
	 * Save the current record and return the number of affected rows or the last insert ID
	 * @param boolean
	 */
	public function save($blnForceInsert=false)
	{
		$arrSet = $this->preSave($this->row());

		if (isset($this->{static::$strPk}) && !$blnForceInsert)
		{
			\Database::getInstance()->prepare("UPDATE " . static::$strTable . " %s WHERE " . static::$strPk . "=?")
									->set($arrSet)
									->execute($this->{static::$strPk});
		}
		else
		{
			$stmt = \Database::getInstance()->prepare("INSERT INTO " . static::$strTable . " %s")
											->set($arrSet)
											->execute();

			if (static::$strPk == 'id')
			{
				$this->id = $stmt->insertId;
			}
		}
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
		return \Database::getInstance()->prepare("DELETE FROM " . static::$strTable . " WHERE " . static::$strPk . "=?")->execute($this->{static::$strPk})->affectedRows;
	}
}

?>