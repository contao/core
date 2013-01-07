<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao\Database;


/**
 * Lazy load the result set rows
 * 
 * The class functions as a wrapper for the database result set and lazy loads
 * the result rows when they are first requested.
 * 
 * Usage:
 * 
 *     while ($result->next())
 *     {
 *         echo $result->name;
 *         print_r($result->row());
 *     }
 * 
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
abstract class Result
{

	/**
	 * Database result
	 * @var resource
	 */
	protected $resResult;

	/**
	 * Query string
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
	 * Modification indicator
	 * @var boolean
	 */
	private $blnModified = false;

	/**
	 * Result cache
	 * @var array
	 */
	protected $arrCache = array();


	/**
	 * Validate the connection resource and store the query string
	 * 
	 * @param resource $resResult The database result
	 * @param string   $strQuery  The query string
	 * 
	 * @throws \Exception If $resResult is not a valid resource
	 */
	public function __construct($resResult, $strQuery)
	{
		if (!is_resource($resResult) && !is_object($resResult))
		{
			throw new \Exception('Invalid result resource');
		}

		$this->resResult = $resResult;
		$this->strQuery = $strQuery;
	}


	/**
	 * Automatically free the result
	 */
	public function __destruct()
	{
		$this->free();
	}


	/**
	 * Set a particular field of the current row
	 * 
	 * @param mixed  $strKey   The field name
	 * @param string $varValue The field value
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
	 * Check whether a field exists
	 * 
	 * @param mixed $strKey The field name
	 * 
	 * @return boolean True if the field exists
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
	 * Return an object property or a field of the current row
	 *
	 * Supported parameters:
	 * 
	 * * query:      the corresponding query string
	 * * numRows:    the number of rows of the current result
	 * * numFields:  the number of fields of the current result
	 * * isModified: true if the row has been modified
	 *
	 * @param string $strKey The field name
	 * 
	 * @return mixed|null The field value or null
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
	 * 
	 * @return array The row as array
	 */
	public function fetchRow()
	{
		if (!isset($this->arrCache[++$this->intIndex]))
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
	 * 
	 * @return array The row as associative array
	 */
	public function fetchAssoc()
	{
		if (!isset($this->arrCache[++$this->intIndex]))
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
	 * 
	 * @param string $strKey The field name
	 * 
	 * @return array An array of field values
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
			if ($strKey != 'id' && isset($arrRow['id']))
			{
				$arrReturn[$arrRow['id']] = $arrRow[$strKey];
			}
			else
			{
				$arrReturn[] = $arrRow[$strKey];
			}
		}

		return $arrReturn;
	}


	/**
	 * Fetch all rows as associative array
	 * 
	 * @return array An array with all rows
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
	 * 
	 * @param integer $intOffset The field offset
	 * 
	 * @return array An array with the column information
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
	 * 
	 * @return \Database\Result The result object
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
	 * 
	 * @return \Database\Result|boolean The result object or false if there is no previous row
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
	 * 
	 * @return \Database\Result|boolean The result object or false if there is no next row
	 */
	public function next()
	{
		if ($this->blnDone)
		{
			return false;
		}

		if (!isset($this->arrCache[++$this->intIndex]))
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
	 * 
	 * @return \Database\Result The result object
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
	 * 
	 * @return integer The number of rows
	 */
	public function count()
	{
		return $this->num_rows();
	}


	/**
	 * Return the current row as associative array
	 * 
	 * @param boolean $blnEnumerated If true, an enumerated array will be returned
	 * 
	 * @return array The row as array
	 */
	public function row($blnEnumerated=false)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		return $blnEnumerated ? array_values($this->arrCache[$this->intIndex]) : $this->arrCache[$this->intIndex];
	}


	/**
	 * Reset the current result
	 * 
	 * @return \Database\Result The result object
	 */
	public function reset()
	{
		$this->intIndex = -1;
		$this->blnDone = false;
		return $this;
	}


	/**
	 * Fetch the current row as enumerated array
	 * 
	 * @return array The row as array
	 */
	abstract protected function fetch_row();


	/**
	 * Fetch the current row as associative array
	 * 
	 * @return array The row as associative array
	 */
	abstract protected function fetch_assoc();


	/**
	 * Return the number of rows in the result set
	 * 
	 * @return integer The number of rows
	 */
	abstract protected function num_rows();


	/**
	 * Return the number of fields of the result set
	 * 
	 * @return integer The number of fields
	 */
	abstract protected function num_fields();


	/**
	 * Get the column information and return it as array
	 * 
	 * @param integer $intOffset The field offset
	 * 
	 * @return array An array with the column information
	 */
	abstract protected function fetch_field($intOffset);


	/**
	 * Free the result
	 */
	abstract public function free();

}
