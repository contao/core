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
	 * Current index
	 * @var integer
	 */
	private $intIndex = -1;

	/**
	 * End indicator
	 * @var boolean
	 */
	private $blnDone = false;

	/**
	 * True if the record exists
	 * @var boolean
	 */
	protected $blnExists = false;

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
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		$this->arrData[$this->intIndex][$strKey] = $varValue;
	}


	/**
	 * Return an object property
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		if (!isset($this->arrData[$this->intIndex][$strKey]))
		{
			return null;
		}

		return $this->arrData[$this->intIndex][$strKey];
	}


	/**
	 * Check whether a property is set
	 * @param string
	 * @return boolean
	 */
	public function __isset($strKey)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		return isset($this->arrData[$this->intIndex][$strKey]);
	}


	/**
	 * Set the current record from a Database_Result
	 * @param Database_Result
	 * @throws Exception
	 */
	public function setData(\Database_Result $objResult)
	{
		$i = -1;

		// Walk through the result set
		while ($objResult->next())
		{
			$this->arrData[++$i] = $objResult->row();

			// Look for joined fields and make them an object
			foreach ($this->arrData[$i] as $k=>$v)
			{
				// E.g. author__id becomes author['id']
				if (strpos($k, '__') !== false)
				{
					list($key, $field) = explode('__', $k);

					if (!is_array($this->arrData[$i][$key]))
					{
						$this->arrData[$i][$key] = array();
					}

					$this->arrData[$i][$key][$field] = $v;
					unset($this->arrData[$i][$k]);
				}
			}
		}
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
	 * Return the number of rows in the result set
	 * @return integer
	 */
	public function count()
	{
		return count($this->arrData);
	}


	/**
	 * Go to the first row
	 * @return Model
	 */
	public function first()
	{
		$this->intIndex = 0;
		return $this;
	}


	/**
	 * Go to the previous row
	 * @return Model
	 */
	public function prev()
	{
		if ($this->intIndex == 0)
		{
			return false;
		}

		--$this->intIndex;
		return $this;
	}


	/**
	 * Go to the next row
	 * @return Model
	 */
	public function next()
	{
		if ($this->blnDone)
		{
			return false;
		}

		if (!isset($this->arrData[++$this->intIndex]))
		{
			--$this->intIndex;
			$this->blnDone = true;
			return false;
		}

		return $this;
	}


	/**
	 * Go to the last row
	 * @return Model
	 */
	public function last()
	{
		$this->blnDone = true;
		$this->intIndex = count($this->arrData) - 1;
		return $this;
	}


	/**
	 * Return the current row
	 * @return array
	 */
	public function row()
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		return $this->arrData[$this->intIndex];
	}


	/**
	 * Reset the model
	 */
	public function reset()
	{
		$this->intIndex = -1;
		$this->blnDone = false;
	}


	/**
	 * Find a record and return the model
	 * @param mixed
	 * @param mixed
	 * @param string
	 * @param integer
	 * @param integer
	 * @param boolean
	 * @return Model|null
	 */
	protected static function find($strColumn, $varValue, $strOrder=null, $intLimit=0, $intOffset=0, $blnForceEager=false)
	{
		if (static::$strTable == '')
		{
			return null;
		}

		$objBase = new \DcaExtractor(static::$strTable);

		if (!$objBase->hasRelations())
		{
			$strQuery = "SELECT * FROM " . static::$strTable;
		}
		else
		{
			$arrJoins = array();
			$arrFields = array(static::$strTable . ".*");

			foreach ($objBase->getRelations() as $strKey=>$arrConfig)
			{
				// Automatically join the single-relation records
				if ($arrConfig['load'] == 'eager' || $blnForceEager)
				{
					if ($arrConfig['type'] == 'hasOne' || $arrConfig['type'] == 'belongsTo')
					{
						$objRelated = new \DcaExtractor($arrConfig['table']);

						foreach (array_keys($objRelated->getFields()) as $strField)
						{
							$arrFields[] = $arrConfig['table'] . '.' . $strField . ' AS ' . $strKey . '__' . $strField;
						}

						$arrJoins[] = " LEFT JOIN " . $arrConfig['table'] . " ON " . static::$strTable . "." . $strKey . "=" . $arrConfig['table'] . ".id";
					}
				}
			}

			// Generate the query
			$strQuery = "SELECT " . implode(', ', $arrFields) . " FROM " . static::$strTable . implode("", $arrJoins);
		}

		// Where condition
		if ($strColumn !== null)
		{
			$strQuery .= " WHERE " . (is_array($strColumn) ? implode(" AND ", $strColumn) : static::$strTable . '.' . $strColumn . "=?");
		}

		// Order by
		if ($strOrder !== null)
		{
			$strQuery .= " ORDER BY " . $strOrder;
		}

		$objStatement = \Database::getInstance()->prepare($strQuery);

		// Limit
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
	 * @param boolean
	 * @return Model|null
	 */
	public static function findBy($strColumn, $varValue, $strOrder=null, $intLimit=0, $intOffset=0, $blnForceEager=false)
	{
		return static::find($strColumn, $varValue, $strOrder, $intLimit, $intOffset, $blnForceEager);
	}


	/**
	 * Find a single record by its primary key
	 * @param mixed
	 * @param boolean
	 * @return Model|null
	 */
	public static function findByPk($varValue, $blnForceEager=false)
	{
		return static::findBy(static::$strPk, $varValue, null, 1, 0, $blnForceEager);
	}


	/**
	 * Find a single record by one or more conditions
	 * @param mixed
	 * @param mixed
	 * @param string
	 * @param boolean
	 * @return Model|null
	 */
	public static function findOneBy($strColumn, $varValue, $strOrder=null, $blnForceEager=false)
	{
		return static::findBy($strColumn, $varValue, $strOrder, 1, 0, $blnForceEager);
	}


	/**
	 * Find a single record by its ID or alias
	 * @param mixed
	 * @param mixed
	 * @param boolean
	 * @return Model|null
	 */
	public static function findByIdOrAlias($varId, $varAlias, $blnForceEager=false)
	{
		$t = static::$strTable;
		return static::findOneBy(array("($t.id=? OR $t.alias=?)"), array((is_numeric($varId) ? $varId : 0), $varAlias), null, $blnForceEager);
	}


	/**
	 * Find all records and return the model
	 * @param string
	 * @param integer
	 * @param integer
	 * @param boolean
	 * @return Model|null
	 */
	public static function findAll($strOrder=null, $intLimit=0, $intOffset=0, $blnForceEager=false)
	{
		return static::findBy(null, null, $strOrder, $intLimit, $intOffset, $blnForceEager=false);
	}


	/**
	 * Find records by one or more conditions and return the number of rows
	 * @param mixed
	 * @param mixed
	 * @return integer
	 */
	public static function countBy($strColumn, $varValue)
	{
		if (static::$strTable == '')
		{
			return null;
		}

		$strQuery = "SELECT COUNT(*) AS count FROM " . static::$strTable;

		// Where condition
		if ($strColumn !== null)
		{
			$strQuery .= " WHERE " . (is_array($strColumn) ? implode(" AND ", $strColumn) : static::$strTable . '.' . $strColumn . "=?");
		}

		$objResult = \Database::getInstance()->prepare($strQuery)->execute($varValue);
		return new static($objResult);
	}


	/**
	 * Find all records and return the number of rows
	 * @return integer
	 */
	public static function countAll()
	{
		return static::countBy(null, null);
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
	 * Lazy load a related record
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

		// No relation defined
		if (empty($arrRelations) || !isset($arrRelations[$key]))
		{
			throw new \Exception("Field $key does not seem to be related");
		}

		// Return if the relation has been loaded eagerly
		if ($arrRelations[$key]['type'] == 'eager')
		{
			return;
		}

		// Get the Model class name (e.g. tl_form_field becomes FormFieldModel)
		$arrChunks = explode('_', $arrRelations[$key]['table']);

		if ($arrChunks[0] == 'tl')
		{
			array_shift($arrChunks);
		}

		$strModelClass = implode('', array_map('ucfirst', $arrChunks)) . 'Model';

		// Load the related record(s)
		if ($arrRelations[$key]['type'] == 'hasOne' || $arrRelations[$key]['type'] == 'belongsTo')
		{
			$objModel = $strModelClass::findBy($arrRelations[$key]['field'], $this->$key);

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
			$objModel = $strModelClass::findBy($arrColumns, null, $strOrder);

			if ($objModel !== null)
			{
				$this->$key = $objModel->getData();
			}
		}
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