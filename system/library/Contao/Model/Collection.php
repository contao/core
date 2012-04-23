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
	 * Database result
	 * @var \Contao\Database_Result
	 */
	private $objResult;

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
	 * Store the database result
	 * @param \Database_Result
	 */
	public function __construct(\Database_Result $objResult)
	{
		parent::__construct();
		$this->objResult = $objResult;
	}


	/**
	 * Set an object property
	 * @param string
	 * @param mixed
	 * @return void
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
	 * Return the number of rows
	 * @return integer
	 */
	public function count()
	{
		return $this->objResult->numRows;
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
	 * Return the current model
	 * @return \Contao\Model
	 */
	public function current()
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		return $this->arrModels[$this->intIndex];
	}


	/**
	 * Go to the first row
	 * @return \Contao\Model_Collection
	 */
	public function first()
	{
		if (empty($this->arrModels))
		{
			$this->fetchNext();
		}

		$this->intIndex = 0;
		return $this;
	}


	/**
	 * Go to the previous row
	 * @return \Contao\Model_Collection
	 */
	public function prev()
	{
		if ($this->intIndex < 1)
		{
			return false;
		}

		--$this->intIndex;
		return $this;
	}


	/**
	 * Go to the next row
	 * @return \Contao\Model_Collection
	 */
	public function next()
	{
		if ($this->blnDone)
		{
			return false;
		}

		if (!isset($this->arrModels[$this->intIndex + 1]))
		{
			if ($this->fetchNext() == false)
			{
				$this->blnDone = true;
				return false;
			}
		}

		++$this->intIndex;
		return $this;
	}


	/**
	 * Go to the last row
	 * @return \Contao\Model_Collection
	 */
	public function last()
	{
		if (!$this->blnDone)
		{
			while ($this->next());
		}

		$this->blnDone = true;
		$this->intIndex = count($this->arrModels) - 1;
		return $this;
	}


	/**
	 * Reset the model
	 * @return \Contao\Model_Collection
	 */
	public function reset()
	{
		$this->intIndex = -1;
		$this->blnDone = false;
		return $this;
	}


	/**
	 * Fetch a column of each row
	 * @param string
	 * @return array
	 * @throws \Exception
	 */
	public function fetchEach($strKey)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		if (!isset($this->arrModels[0]->$strKey))
		{
			throw new \Exception("Unknown field $strKey");
		}

		$return = array();

		foreach ($this->arrModels as $objModel)
		{
			$return[] = $objModel->$strKey;
		}

		return $return;
	}


	/**
	 * Fetch the next result row and create the model
	 * @return boolean
	 */
	protected function fetchNext()
	{
		if ($this->objResult->next() == false)
		{
			return false;
		}

		$strClass = $this->getModelClassFromTable(static::$strTable);
		$this->arrModels[$this->intIndex + 1] = new $strClass($this->objResult);

		return true;
	}


	/**
	 * Find records by one or more conditions
	 * @param mixed
	 * @param mixed
	 * @param array
	 * @return \Contao\Model_Collection|null
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
	 * @return \Contao\Model_Collection|null
	 */
	public static function findAll(Array $arrOptions=array())
	{
		return static::find($arrOptions);
	}


	/**
	 * Magic method to call $this->findByPid() instead of $this->findBy('pid')
	 * @param string
	 * @param array
	 * @return mixed|null
	 */
	public static function __callStatic($name, $args)
	{
		if (strncmp($name, 'findBy', 6) !== 0)
		{
			return null;
		}

		return call_user_func('static::findBy', lcfirst(substr($name, 6)), array_shift($args), $args);
	}


	/**
	 * Find records and return the model collection
	 * @param array
	 * @return \Contao\Model_Collection|null
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
	 * Modify the statement before it is executed
	 * @param \Database_Statement
	 * @return \Contao\Database_Statement
	 */
	protected static function preFind(\Database_Statement $objStatement)
	{
		return $objStatement;
	}


	/**
	 * Modify the result set before the model is created
	 * @param \Database_Result
	 * @return \Contao\Database_Result
	 */
	protected static function postFind(\Database_Result $objResult)
	{
		return $objResult;
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
	 * Save the current model
	 * @return \Contao\Model_Collection
	 */
	public function save()
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		$this->arrModels[$this->intIndex]->save();
		return $this;
	}


	/**
	 * Delete the current model and return the number of affected rows
	 * @return integer
	 */
	public function delete()
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		return $this->arrModels[$this->intIndex]->delete();
	}


	/**
	 * Lazy load related records
	 * @param string
	 * @return \Contao\Model_Collection
	 */
	public function getRelated($strKey)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		return $this->arrModels[$this->intIndex]->getRelated($strKey);
	}
}
