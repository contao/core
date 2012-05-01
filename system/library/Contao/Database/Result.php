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
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \Exception;


/**
 * Class Database_Result
 *
 * Provide methods to handle a database result.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
abstract class Database_Result
{

	/**
	 * Current result
	 * @var resource
	 */
	protected $resResult;

	/**
	 * Corresponding query string
	 * @var string
	 */
	protected $strQuery;

	/**
	 * Current index
	 * @var integer
	 */
	private $intIndex = -1;

	/**
	 * Current row index
	 * @var integer
	 */
	private $intRowIndex = -1;

	/**
	 * End indicator
	 * @var boolean
	 */
	private $blnDone = false;

	/**
	 * Remember modifications
	 * @var boolean
	 */
	private $blnModified = false;

	/**
	 * Result cache array
	 * @var array
	 */
	protected $arrCache = array();


	/**
	 * Validate the connection resource and store the query
	 * @param resource
	 * @param string
	 * @throws \Exception
	 */
	public function __construct($resResult, $strQuery)
	{
		if (!is_resource($resResult) && !is_object($resResult))
		{
			throw new Exception('Invalid result resource');
		}

		$this->resResult = $resResult;
		$this->strQuery = $strQuery;
	}


	/**
	 * Automatically free the current result
	 */
	public function __destruct()
	{
		$this->free();
	}


	/**
	 * Set a particular field of the current row
	 * @param mixed
	 * @param string
	 */
	public function __set($strKey, $varValue)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		$this->blnModified = true;
		$this->arrCache[$this->intIndex][$strKey] = $varValue;
	}


	/**
	 * Check whether a variable exists
	 * @param mixed
	 * @return boolean
	 */
	public function __isset($strKey)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		return isset($this->arrCache[$this->intIndex][$strKey]);
	}


	/**
	 * Return a result parameter or a particular field of the current row
	 *
	 * Supported parameters:
	 * - query:     corresponding query string
	 * - numRows:   number of rows of the current result
	 * - numFields: fields of the current result
	 *
	 * Throw an exception on requests for unknown fields.
	 * @param string
	 * @return mixed|null
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'query':
				return $this->strQuery;
				break;

			case 'numRows':
				return $this->num_rows();
				break;

			case 'numFields':
				return $this->num_fields();
				break;

			case 'isModified':
				return $this->blnModified;
				break;

			default:
				if ($this->intIndex < 0)
				{
					$this->first();
				}
				if (isset($this->arrCache[$this->intIndex][$strKey]))
				{
					return $this->arrCache[$this->intIndex][$strKey];
				}
				break;
		}

		return null;
	}


	/**
	 * Fetch the current row as enumerated array
	 * @return array
	 */
	public function fetchRow()
	{
		if (!$this->arrCache[++$this->intIndex])
		{
			if (($arrRow = $this->fetch_row()) == false)
			{
				--$this->intIndex;
				return false;
			}

			$this->arrCache[$this->intIndex] = $arrRow;
		}

		return array_values($this->arrCache[$this->intIndex]);
	}


	/**
	 * Fetch the current row as associative array
	 * @return array
	 */
	public function fetchAssoc()
	{
		if (!$this->arrCache[++$this->intIndex])
		{
			if (($arrRow = $this->fetch_assoc()) == false)
			{
				--$this->intIndex;
				return false;
			}

			$this->arrCache[$this->intIndex] = $arrRow;
		}

		return $this->arrCache[$this->intIndex];
	}


	/**
	 * Fetch a particular field of each row of the result
	 * @param string
	 * @return array
	 */
	public function fetchEach($strKey)
	{
		$arrReturn = array();

		if ($this->intIndex < 0)
		{
			$this->fetchAllAssoc();
		}

		foreach ($this->arrCache as $arrRow)
		{
			$arrReturn[] = $arrRow[$strKey];
		}

		return $arrReturn;
	}


	/**
	 * Fetch all rows as associative array
	 * @return array
	 */
	public function fetchAllAssoc()
	{
		do
		{
			$blnHasNext = $this->fetchAssoc();
		}
		while ($blnHasNext);

		return $this->arrCache;
	}


	/**
	 * Get the column information and return it as array
	 * @param integer
	 * @return array
	 */
	public function fetchField($intOffset=0)
	{
		$arrFields = $this->fetch_field($intOffset);

		if (is_object($arrFields))
		{
			$arrFields = get_object_vars($arrFields);
		}

		return $arrFields;
	}


	/**
	 * Go to the first row of the current result
	 * @return \Database_Result
	 */
	public function first()
	{
		if (!$this->arrCache)
		{
			$this->arrCache[++$this->intRowIndex] = $this->fetchAssoc();
		}

		$this->intIndex = 0;
		return $this;
	}


	/**
	 * Go to the previous row of the current result
	 * @return \Database_Result|boolean
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
	 * Go to the next row of the current result
	 * @return \Database_Result|boolean
	 */
	public function next()
	{
		if ($this->blnDone)
		{
			return false;
		}

		if (!$this->arrCache[++$this->intIndex])
		{
			--$this->intIndex; // see #3762

			if (($arrRow = $this->fetchAssoc()) == false)
			{
				$this->blnDone = true;
				return false;
			}

			$this->arrCache[$this->intIndex] = $arrRow;
			++$this->intRowIndex;

			return $this;
		}

		return $this;
	}


	/**
	 * Go to the last row of the current result
	 * @return \Database_Result|boolean
	 */
	public function last()
	{
		if (!$this->blnDone)
		{
			$this->arrCache = $this->fetchAllAssoc();
		}

		$this->blnDone = true;
		$this->intIndex = $this->intRowIndex = count($this->arrCache) - 1;

		return $this;
	}


	/**
	 * Return the number of rows in the result set
	 * @return integer
	 */
	public function count()
	{
		return $this->num_rows();
	}


	/**
	 * Return the current row as associative array
	 * @param boolean
	 * @return array
	 */
	public function row($blnFetchArray=false)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		return $blnFetchArray ? array_values($this->arrCache[$this->intIndex]) : $this->arrCache[$this->intIndex];
	}


	/**
	 * Reset the current result
	 * @return \Database_Result
	 */
	public function reset()
	{
		$this->intIndex = -1;
		$this->blnDone = false;
		return $this;
	}


	// Abstract database driver methods
	abstract protected function fetch_row();
	abstract protected function fetch_assoc();
	abstract protected function num_rows();
	abstract protected function num_fields();
	abstract protected function fetch_field($intOffset);
}
