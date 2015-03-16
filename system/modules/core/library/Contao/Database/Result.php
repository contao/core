<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
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
 * @property string  $query      The query string
 * @property integer $numRows    The number of rows in the result
 * @property integer $numFields  The number of fields in the result
 * @property boolean $isModified True if the result has been modified
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
	 * Current row index
	 * @var integer
	 */
	private $intIndex = -1;

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
		if (empty($this->arrCache))
		{
			$this->next();
		}

		$this->blnModified = true;
		$this->arrCache[$strKey] = $varValue;
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
		if (empty($this->arrCache))
		{
			$this->next();
		}

		return isset($this->arrCache[$strKey]);
	}


	/**
	 * Return an object property or a field of the current row
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
				if (empty($this->arrCache))
				{
					$this->next();
				}
				if (isset($this->arrCache[$strKey]))
				{
					return $this->arrCache[$strKey];
				}
				break;
		}

		return null;
	}


	/**
	 * Fetch the current row as enumerated array
	 *
	 * @return array|false The row as enumerated array or false if there is no row
	 */
	public function fetchRow()
	{
		if (($arrRow = $this->fetch_row()) == false)
		{
			return false;
		}

		++$this->intIndex;
		$this->arrCache = $arrRow;

		return $arrRow;
	}


	/**
	 * Fetch the current row as associative array
	 *
	 * @return array|false The row as associative array or false if there is no row
	 */
	public function fetchAssoc()
	{
		if (($arrRow = $this->fetch_assoc()) == false)
		{
			return false;
		}

		++$this->intIndex;
		$this->arrCache = $arrRow;

		return $arrRow;
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
		$this->reset();
		$arrReturn = array();

		while (($arrRow = $this->fetchAssoc()) !== false)
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
		$this->reset();
		$arrReturn = array();

		while (($arrRow = $this->fetchAssoc()) !== false)
		{
			$arrReturn[] = $arrRow;
		}

		return $arrReturn;
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
	 * @return \Database\Result|boolean The result object or false if there is no first row
	 */
	public function first()
	{
		$this->intIndex = 0;
		$this->data_seek($this->intIndex);

		if (($arrRow = $this->fetch_assoc()) == false)
		{
			return false;
		}

		$this->blnDone = false;
		$this->arrCache = $arrRow;

		return $this;
	}


	/**
	 * Go to the previous row of the current result
	 *
	 * @return \Database\Result|boolean The result object or false if there is no previous row
	 */
	public function prev()
	{
		if ($this->intIndex < 1)
		{
			return false;
		}

		--$this->intIndex;
		$this->data_seek($this->intIndex);

		if (($arrRow = $this->fetch_assoc()) == false)
		{
			return false;
		}

		$this->blnDone = false;
		$this->arrCache = $arrRow;

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

		if (($arrRow = $this->fetch_assoc()) == false)
		{
			$this->blnDone = true;

			return false;
		}

		++$this->intIndex;
		$this->arrCache = $arrRow;

		return $this;
	}


	/**
	 * Go to the last row of the current result
	 *
	 * @return \Database\Result|boolean The result object or false if there is no last row
	 */
	public function last()
	{
		$this->intIndex = $this->count() - 1;
		$this->data_seek($this->intIndex);

		if (($arrRow = $this->fetch_assoc()) == false)
		{
			return false;
		}

		$this->blnDone = true;
		$this->arrCache = $arrRow;

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
		if (empty($this->arrCache))
		{
			$this->next();
		}

		return $blnEnumerated ? array_values($this->arrCache) : $this->arrCache;
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
		$this->data_seek(0);
		$this->arrCache = array();

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
	 * @return array|object An array or object with the column information
	 */
	abstract protected function fetch_field($intOffset);


	/**
	 * Navigate to a certain row in the result set
	 *
	 * @param integer $intIndex The row index
	 *
	 * @throws \OutOfBoundsException If $intIndex is out of bounds
	 */
	abstract protected function data_seek($intIndex);


	/**
	 * Free the result
	 */
	abstract public function free();

}
