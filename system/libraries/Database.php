<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class Database
 *
 * Provide methods to handle database communication.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
abstract class Database
{

	/**
	 * Current object instance (Singleton)
	 * @var object
	 */
	protected static $objInstance;

	/**
	 * Connection ID
	 * @var resource
	 */
	protected $resConnection;

	/**
	 * Disable autocommit
	 * @var boolean
	 */
	protected $blnDisableAutocommit = false;

	/**
	 * Cache array
	 * @var array
	 */
	protected $arrCache = array();


	/**
	 * Load the database configuration file and connect to the database
	 * @throws Exception
	 */
	protected function __construct()
	{
		$this->connect();

		if (!is_resource($this->resConnection) && !is_object($this->resConnection))
		{
			throw new Exception(sprintf('Could not connect to database (%s)', $this->error));
		}
	}


	/**
	 * Close the database connection if it is not permanent
	 */
	public function __destruct()
	{
		if (!$GLOBALS['TL_CONFIG']['dbPconnect'])
		{
			$this->disconnect();
		}
	}


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final private function __clone() {}


	/**
	 * Return an object property
	 * @return string
	 */
	public function __get($strKey)
	{
		if ($strKey == 'error')
		{
			return $this->get_error();
		}

		return null;
	}


	/**
	 * Instantiate a database driver object and return it (Factory)
	 * @return object
	 * @throws Exception
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			$strClass = 'DB_' . ucfirst(strtolower($GLOBALS['TL_CONFIG']['dbDriver']));
			$strFile = sprintf('%s/system/drivers/%s.php', TL_ROOT, $strClass);

			if (!file_exists($strFile))
			{
				throw new Exception(sprintf('Could not load database driver %s', $strClass));
			}

			include_once($strFile);
			define('DB_DRIVER', $strClass);

			self::$objInstance = new $strClass();
		}

		return self::$objInstance;
	}


	/**
	 * Prepare a statement (return a Database_Statement object)
	 * @param  string
	 * @return object
	 */
	public function prepare($strQuery)
	{
		$strClass = DB_DRIVER . '_Statement';
		$objStatement = new $strClass($this->resConnection, $this->blnDisableAutocommit);

		return $objStatement->prepare($strQuery);
	}


	/**
	 * Execute a query (return a Database_Result object)
	 * @param  string
	 * @return object
	 */
	public function execute($strQuery)
	{
		return $this->prepare($strQuery)->execute();
	}


	/**
	 * Execute a query and do not cache the result
	 * @param  string
	 * @return object
	 */
	public function executeUncached($strQuery)
	{
		return $this->prepare($strQuery)->executeUncached();
	}


	/**
	 * Execute a raw query (return a Database_Result object)
	 * @param  string
	 * @return object
	 */
	public function query($strQuery)
	{
		$strClass = DB_DRIVER . '_Statement';
		$objStatement = new $strClass($this->resConnection, $this->blnDisableAutocommit);

		return $objStatement->query($strQuery);
	}


	/**
	 * Return all tables of a database as array
	 * @param  string
	 * @param  boolean
	 * @return array
	 */
	public function listTables($strDatabase=false, $blnNoCache=false)
	{
		if (!$strDatabase)
		{
			$strDatabase = $GLOBALS['TL_CONFIG']['dbDatabase'];
		}

		if (!$blnNoCache && isset($this->arrCache[$strDatabase]))
		{
			return $this->arrCache[$strDatabase];
		}

		$arrReturn = array();
		$arrTables = $this->execute(sprintf($this->strListTables, $strDatabase))->fetchAllAssoc();

		foreach ($arrTables as $arrTable)
		{
			$arrReturn[] = current($arrTable);
		}

		$this->arrCache[$strDatabase] = $arrReturn;
		return $this->arrCache[$strDatabase];
	}


	/**
	 * Determine if a particular database table exists
	 * @param  string
	 * @param  string
	 * @param  boolean
	 * @return boolean
	 */
	public function tableExists($strTable, $strDatabase=false, $blnNoCache=false)
	{
		return in_array($strTable, $this->listTables($strDatabase, $blnNoCache));
	}


	/**
	 * Return all columns of a particular table as array
	 * @param  string
	 * @param  boolean
	 * @return array
	 */
	public function listFields($strTable, $blnNoCache=false)
	{
		if (!$blnNoCache && isset($this->arrCache[$strTable]))
		{
			return $this->arrCache[$strTable];
		}

		$this->arrCache[$strTable] = $this->list_fields($strTable);
		return $this->arrCache[$strTable];
	}


	/**
	 * Determine if a particular column exists
	 * @param  string
	 * @param  string
	 * @param  boolean
	 * @return boolean
	 */
	public function fieldExists($strField, $strTable, $blnNoCache=false)
	{
		foreach ($this->listFields($strTable, $blnNoCache) as $arrField)
		{
			if ($arrField['name'] == $strField)
			{
				return true;
				break;
			}
		}

		return false;
	}


	/**
	 * Return the field names of a particular table as array
	 * @param  string
	 * @param  boolean
	 * @return array
	 */
	public function getFieldNames($strTable, $blnNoCache=false)
	{
		$arrNames = array();
		$arrFields = $this->listFields($strTable, $blnNoCache);

		foreach ($arrFields as $arrField)
		{
			$arrNames[] = $arrField['name'];
		}

		return $arrNames;
	}


	/**
	 * Change the current database
	 * @param  string
	 * @return boolean
	 */
	public function setDatabase($strDatabase)
	{
		return $this->set_database($strDatabase);
	}


	/**
	 * Begin a transaction
	 */
	public function beginTransaction()
	{
		$this->begin_transaction();
	}


	/**
	 * Commit a transaction
	 */
	public function commitTransaction()
	{
		$this->commit_transaction();
	}


	/**
	 * Rollback a transaction
	 */
	public function rollbackTransaction()
	{
		$this->rollback_transaction();
	}


	/**
	 * Lock tables
	 * @param array
	 */
	public function lockTables($arrTables)
	{
		$this->lock_tables($arrTables);
	}


	/**
	 * Unlock tables
	 */
	public function unlockTables()
	{
		$this->unlock_tables();
	}


	/**
	 * Abstract database driver methods
	 */
	abstract protected function connect();
	abstract protected function disconnect();
	abstract protected function get_error();
	abstract protected function begin_transaction();
	abstract protected function commit_transaction();
	abstract protected function rollback_transaction();
	abstract protected function list_fields($strTable);
	abstract protected function set_database($strDatabase);
	abstract protected function lock_tables($arrTables);
	abstract protected function unlock_tables();
}


/**
 * Class Database_Statement
 *
 * Provide methods to execute a database query.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
abstract class Database_Statement
{

	/**
	 * Connection ID
	 * @var resource
	 */
	protected $resConnection;

	/**
	 * Current result
	 * @var resource
	 */
	protected $resResult;

	/**
	 * Current query string
	 * @var string
	 */
	protected $strQuery;

	/**
	 * Disable autocommit
	 * @var boolean
	 */
	protected $blnDisableAutocommit = false;

	/**
	 * Cache array
	 * @var array
	 */
	protected static $arrCache = array();


	/**
	 * Validate the connection resource and store the query
	 * @param  resource
	 * @param  boolean
	 * @throws Exception
	 */
	public function __construct($resConnection, $blnDisableAutocommit=false)
	{
		if (!is_resource($resConnection) && !is_object($resConnection))
		{
			throw new Exception('Invalid connection resource');
		}

		$this->resConnection = $resConnection;
		$this->blnDisableAutocommit = $blnDisableAutocommit;
	}


	/**
	 * Return a parameter
	 *
	 * Supported parameters:
	 * - query:        current query string
	 * - error:        last error message
	 * - affectedRows: number of affected rows
	 * - insertId:     last insert ID
	 *
	 * Throw an exception on requests for protected properties.
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'query':
				return $this->strQuery;
				break;

			case 'error':
				return $this->get_error();
				break;

			case 'affectedRows':
				return $this->affected_rows();
				break;

			case 'insertId':
				return $this->insert_id();
				break;

			default:
				return null;
				break;
		}
	}


	/**
	 * Prepare a statement
	 * @param  string
	 * @return object
	 * @throws Exception
	 */
	public function prepare($strQuery)
	{
		if (!strlen($strQuery))
		{
			throw new Exception('Empty query string');
		}

		$this->resResult = NULL;
		$this->strQuery = $this->prepare_query($strQuery);

		// Autogenerate SET/VALUES subpart
		if (in_array(substr(strtoupper($this->strQuery), 0, 6), array('INSERT', 'UPDATE')))
		{
			$this->strQuery = str_replace('%s', '%p', $this->strQuery);
		}

		// Replace wildcards
		$arrChunks = preg_split("/('[^']*')/", $this->strQuery, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);

		foreach ($arrChunks as $k=>$v)
		{
			if (substr($v, 0, 1) == "'")
			{
				continue;
			}

			$arrChunks[$k] = str_replace('?', '%s', $v);
		}

		$this->strQuery = implode('', $arrChunks);
		return $this;
	}


	/**
	 * Take an associative array and autogenerate the SET/VALUES subpart of a query
	 * 
	 * Usage example:
	 * $objStatement->prepare("UPDATE table %s")->set(array('id'=>'my_id'));
	 * will be transformed into "UPDATE table SET id='my_id'".
	 * @param  array
	 * @return object
	 */
	public function set($arrParams)
	{
		$arrParams = $this->escapeParams($arrParams);

		if (strpos($this->strQuery, '%s') < 0)
		{
			return $this;
		}

		$strTrim = trim($this->strQuery);

		if (strncasecmp($strTrim, 'INSERT', 6) === 0)
		{
			$strQuery = sprintf('(%s) VALUES (%s)',
								implode(', ', array_keys($arrParams)),
								str_replace('%', '%%', implode(', ', array_values($arrParams))));
		}
		elseif (strncasecmp($strTrim, 'UPDATE', 6) === 0)
		{
			$arrSet = array();

			foreach ($arrParams as $k=>$v)
			{
				$arrSet[] = $k . '=' . $v;
			}

			$strQuery = 'SET ' . str_replace('%', '%%', implode(', ', $arrSet));
		}

		$this->strQuery = str_replace('%p', $strQuery, $this->strQuery);
		return $this;
	}


	/**
	 * Limit the current result to a certain number of rows and take an offset value as second argument
	 * @param  int
	 * @param  int
	 * @return object
	 */
	public function limit($intRows, $intOffset=0)
	{
		$this->limit_query((($intRows > 0) ? $intRows : 30), (($intOffset > 0) ? $intOffset : 0));
		return $this;
	}


	/**
	 * Escape parameters and execute the current statement
	 * @return object
	 * @throws Exception
	 */
	public function execute()
	{
		$arrParams = func_get_args();

		if (is_array($arrParams[0]))
		{
			$arrParams = array_values($arrParams[0]);
		}

		$this->replaceWildcards($arrParams);
		$strKey = md5($this->strQuery);

		// Try to load result from cache
		if (isset(self::$arrCache[$strKey]) && !self::$arrCache[$strKey]->isModified)
		{
			return self::$arrCache[$strKey]->reset();
		}

		$objResult = $this->query();

		// Cache result objects
		if ($objResult instanceof Database_Result)
		{
			self::$arrCache[$strKey] = $objResult;
		}

		return $objResult;
	}


	/**
	 * Execute the current statement but do not cache the result
	 * @return object
	 * @throws Exception
	 */
	public function executeUncached()
	{
		$arrParams = func_get_args();

		if (is_array($arrParams[0]))
		{
			$arrParams = array_values($arrParams[0]);
		}

		$this->replaceWildcards($arrParams);
		return $this->query();
	}


	/**
	 * Execute a query and return the result object
	 * @param  string
	 * @return object
	 * @throws Exception
	 */
	public function query($strQuery='')
	{
		if (!empty($strQuery))
		{
			$this->strQuery = $strQuery;
		}

		// Make sure there is a query string
		if ($this->strQuery == '')
		{
			throw new Exception('Empty query string');
		}

		// Execute the query
		if (($this->resResult = $this->execute_query()) == false)
		{
			throw new Exception(sprintf('Query error: %s (%s)', $this->error, $this->strQuery));
		}

		// No result set available
		if (!is_resource($this->resResult) && !is_object($this->resResult))
		{
			$this->debugQuery();
			return $this;
		}

		// Instantiate a result object
		$strClass = DB_DRIVER . '_Result';
		$objResult = new $strClass($this->resResult, $this->strQuery);
		$this->debugQuery($objResult);

		return $objResult;
	}


	/**
	 * Build the query string
	 * @param array
	 * @throws Exception
	 */
	protected function replaceWildcards($arrParams)
	{
		$arrParams = $this->escapeParams($arrParams);

		// Clean wildcards
		$this->strQuery = preg_replace('/%([^bcdufosxX])/', '%%$1', $this->strQuery);
		$this->strQuery = preg_replace('/%%+/', '%%', $this->strQuery);

		// Replace wildcards
		if (($this->strQuery = @vsprintf($this->strQuery, $arrParams)) == false)
		{
			throw new Exception('Too few arguments to build the query string');
		}
	}


	/**
	 * Escape parameters and serialize objects and arrays
	 * @param  array
	 * @return array
	 */
	protected function escapeParams($arrParams)
	{
		foreach ($arrParams as $k=>$v)
		{
			switch (gettype($v))
			{
				case 'string':
					$arrParams[$k] = $this->string_escape($v);
					break;

				case 'boolean':
					$arrParams[$k] = ($v === true) ? 1 : 0;
					break;

				case 'object':
					$arrParams[$k] = $this->string_escape(serialize($v));
					break;

				case 'array':
					$arrParams[$k] = $this->string_escape(serialize($v));
					break;

				default:
					$arrParams[$k] = ($v === NULL) ? 'NULL' : $v;
					break;
			}
		}

		return $arrParams;
	}


	/**
	 * Debug a query
	 * @param object
	 */
	protected function debugQuery($objResult=false)
	{
		if (!$GLOBALS['TL_CONFIG']['debugMode'])
		{
			return;
		}

		$arrData[] = $this->strQuery;

		if (!$objResult || strncmp(strtoupper($this->strQuery), 'SELECT', 6) !== 0)
		{
			$arrData[] = sprintf('%d rows affected', $this->affectedRows);
			$GLOBALS['TL_DEBUG'][] = $arrData;

			return;
		}

		$arrData[] = sprintf('%s rows returned', $objResult->numRows);

		if (($arrExplain = $this->explain()) != false)
		{
			$arrData[] = $arrExplain;
		}

		$GLOBALS['TL_DEBUG'][] = $arrData;
	}


	/**
	 * Explain the current query
	 * @return int
	 */
	public function explain()
	{
		return $this->explain_query();
	}


	/**
	 * Abstract database driver methods
	 */
	abstract protected function prepare_query($strQuery);
	abstract protected function string_escape($strString);
	abstract protected function limit_query($intOffset, $intRows);
	abstract protected function execute_query();
	abstract protected function get_error();
	abstract protected function affected_rows();
	abstract protected function insert_id();
	abstract protected function explain_query();
}


/**
 * Class Database_Result
 *
 * Provide methods to handle a database result.
 * @copyright  Leo Feyer 2005-2011
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
	 * @param  resource
	 * @param  string
	 * @throws Exception
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
	 * @param string
	 * @param string
	 */
	public function __set($strKey, $strValue)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		$this->blnModified = true;
		$this->arrCache[$this->intIndex][$strKey] = $strValue;
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
	 * @param  string
	 * @return string
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
				return null;
				break;
		}
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
	 * @param  string
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
	 * Get column information and return it as array
	 * @param  int
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
	 * @return object
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
	 * Go to the next row of the current result
	 * @return mixed
	 */
	public function next()
	{
		if ($this->blnDone)
		{
			return false;
		}

		if (!$this->arrCache[++$this->intIndex])
		{
			if (($arrRow = $this->fetchAssoc()) == false)
			{
				$this->blnDone = true;
				--$this->intIndex;

				return false;
			}

			$this->arrCache[$this->intIndex] = $arrRow;
			++$this->intRowIndex;

			return $this;
		}

		return $this;
	}


	/**
	 * Go to the previous row of the current result
	 * @return mixed
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
	 * Go to the last row of the current result
	 * @return mixed
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
	 * @return object
	 */
	public function reset()
	{
		$this->intIndex = -1;
		$this->blnDone = false;

		return $this;
	}


	/**
	 * Abstract database driver methods
	 */
	abstract protected function fetch_row();
	abstract protected function fetch_assoc();
	abstract protected function num_rows();
	abstract protected function num_fields();
	abstract protected function fetch_field($intOffset);
}

?>