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
 * Class Model_Collection
 *
 * Provide methods to handle multiple models.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Model
 */
abstract class Model_Collection extends \System
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable;

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
	 * Models array
	 * @var array
	 */
	protected $arrModels = array();


	/**
	 * Optionally take a result set
	 * @param \Database_Result
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

		$this->arrModels[$this->intIndex]->$strKey = $varValue;
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

		if (!isset($this->arrModels[$this->intIndex]->$strKey))
		{
			return null;
		}

		return $this->arrModels[$this->intIndex]->$strKey;
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

		return isset($this->arrModels[$this->intIndex]->$strKey);
	}


	/**
	 * Set the current record from a Database_Result
	 * @param \Database_Result
	 */
	public function setData(\Database_Result $objResult)
	{
		$strClass = $this->convertTableNameToModelClass(static::$strTable);

		while ($objResult->next())
		{
			$this->arrModels[] = new $strClass($objResult);
		}
	}


	/**
	 * Return the number of rows (models)
	 * @return integer
	 */
	public function count()
	{
		return count($this->arrModels);
	}


	/**
	 * Go to the first row
	 * @return Model_Collection
	 */
	public function first()
	{
		$this->intIndex = 0;
		return $this;
	}


	/**
	 * Go to the previous row
	 * @return Model_Collection
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
	 * @return Model_Collection
	 */
	public function next()
	{
		if ($this->blnDone)
		{
			return false;
		}

		if (!isset($this->arrModels[++$this->intIndex]))
		{
			--$this->intIndex;
			$this->blnDone = true;
			return false;
		}

		return $this;
	}


	/**
	 * Go to the last row
	 * @return Model_Collection
	 */
	public function last()
	{
		$this->blnDone = true;
		$this->intIndex = count($this->arrModels) - 1;
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

		return $this->arrModels[$this->intIndex]->row();
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
	 * Fetch a column of each row
	 * @param string
	 * @return array
	 * @throws \Exception
	 */
	public function fetchEach($key)
	{
		if (!isset($this->arrModels[0]->$key))
		{
			throw new \Exception("Unknown field $key");
		}

		$return = array();

		foreach ($this->arrModels as $objModel)
		{
			$return[] = $objModel->$key;
		}

		return $return;
	}


	/**
	 * Lazy load related records
	 * @param string
	 */
	public function getRelated($key)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		$this->arrModels[$this->intIndex]->getRelated($key);
	}


	/**
	 * Find records and return the model collection
	 * @param array
	 * @return Model_Collection|null
	 */
	protected static function find(Array $arrOptions)
	{
		if (static::$strTable == '')
		{
			return null;
		}

		$arrOptions['table'] = static::$strTable;
		$strQuery = \Model_QueryBuilder::find($arrOptions);

		$objStatement = \Database::getInstance()->prepare($strQuery);

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
		return new static($objResult);
	}


	/**
	 * Find records by one or more conditions
	 * @param mixed
	 * @param mixed
	 * @param array
	 * @return Model_Collection|null
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
	 * Find all records and return the model
	 * @param array
	 * @return Model_Collection|null
	 */
	public static function findAll(Array $arrOptions=array())
	{
		return static::find($arrOptions);
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

		$strQuery = \Model_QueryBuilder::count(array
		(
			'table'  => static::$strTable,
			'column' => $strColumn,
			'value'  => $varValue
		));

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
	 * @param \Database_Statement
	 * @return \Database_Statement
	 */
	protected static function preFind(\Database_Statement $objStatement)
	{
		return $objStatement;
	}


	/**
	 * Modify the result set before the model is created
	 * @param \Database_Result
	 * @return \Database_Result
	 */
	protected static function postFind(\Database_Result $objResult)
	{
		return $objResult;
	}


	/**
	 * Delete all records
	 */
	public function deleteAll()
	{
		foreach ($this->arrModels as $objModel)
		{
			$objModel->delete();
		}
	}
}
