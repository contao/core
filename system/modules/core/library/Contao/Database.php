<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Abstract parent class to handle database communication
 *
 * The class is responsible for connecting to the database, listing tables and
 * fields, handling transactions and locking tables. It also creates the related
 * Database\Statement and Database\Result objects.
 *
 * Usage:
 *
 *     $db   = Database::getInstance();
 *     $stmt = $db->prepare("SELECT * FROM tl_user WHERE id=?");
 *     $res  = $stmt->execute(4);
 *
 * @property string $error The last error message
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
abstract class Database
{

	/**
	 * Object instances (Singleton)
	 * @var array
	 */
	protected static $arrInstances = array();

	/**
	 * Connection configuration
	 * @var array
	 */
	protected $arrConfig = array();

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
	 * Cache
	 * @var array
	 */
	protected $arrCache = array();

	/**
	 * List tables query
	 * @var string
	 */
	protected $strListTables = "SHOW TABLES FROM `%s`";


	/**
	 * Establish the database connection
	 *
	 * @param array $arrConfig A configuration array
	 *
	 * @throws \Exception If a connection cannot be established
	 */
	protected function __construct(array $arrConfig)
	{
		$this->arrConfig = $arrConfig;
		$this->connect();

		if (!is_resource($this->resConnection) && !is_object($this->resConnection))
		{
			throw new \Exception(sprintf('Could not connect to database (%s)', $this->error));
		}
	}


	/**
	 * Close the database connection if it is not permanent
	 */
	public function __destruct()
	{
		if (!$this->arrConfig['dbPconnect'])
		{
			$this->disconnect();

			// Unset the reference (see #4772)
			$strKey = md5(implode('', $this->arrConfig));
			unset(static::$arrInstances[$strKey]);
		}
	}


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final public function __clone() {}


	/**
	 * Return an object property
	 *
	 * @param string $strKey The property name
	 *
	 * @return string|null The property value
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
	 * Instantiate the Database object (Factory)
	 *
	 * @param array $arrCustom A configuration array
	 *
	 * @return \Database The Database object
	 */
	public static function getInstance(array $arrCustom=null)
	{
		$arrConfig = array
		(
			'dbDriver'   => \Config::get('dbDriver'),
			'dbHost'     => \Config::get('dbHost'),
			'dbUser'     => \Config::get('dbUser'),
			'dbPass'     => \Config::get('dbPass'),
			'dbDatabase' => \Config::get('dbDatabase'),
			'dbPconnect' => \Config::get('dbPconnect'),
			'dbCharset'  => \Config::get('dbCharset'),
			'dbPort'     => \Config::get('dbPort'),
			'dbSocket'   => \Config::get('dbSocket'),
			'dbSqlMode'  => \Config::get('dbSqlMode')
		);

		if (is_array($arrCustom))
		{
			$arrConfig = array_merge($arrConfig, $arrCustom);
		}

		// Sort the array before generating the key
		ksort($arrConfig);
		$strKey = md5(implode('', $arrConfig));

		if (!isset(static::$arrInstances[$strKey]))
		{
			$strClass = 'Database\\' . str_replace(' ', '_', ucwords(str_replace('_', ' ', strtolower($arrConfig['dbDriver']))));
			static::$arrInstances[$strKey] = new $strClass($arrConfig);
		}

		return static::$arrInstances[$strKey];
	}


	/**
	 * Prepare a query and return a Database\Statement object
	 *
	 * @param string $strQuery The query string
	 *
	 * @return \Database\Statement The Database\Statement object
	 */
	public function prepare($strQuery)
	{
		return $this->createStatement($this->resConnection, $this->blnDisableAutocommit)->prepare($strQuery);
	}


	/**
	 * Execute a query and return a Database\Result object
	 *
	 * @param string $strQuery The query string
	 *
	 * @return \Database\Result|object The Database\Result object
	 */
	public function execute($strQuery)
	{
		return $this->prepare($strQuery)->execute();
	}


	/**
	 * Execute a raw query and return a Database\Result object
	 *
	 * @param string $strQuery The query string
	 *
	 * @return \Database\Result|object The Database\Result object
	 */
	public function query($strQuery)
	{
		return $this->createStatement($this->resConnection, $this->blnDisableAutocommit)->query($strQuery);
	}


	/**
	 * Auto-generate a FIND_IN_SET() statement
	 *
	 * @param string  $strKey     The field name
	 * @param mixed   $varSet     The set to find the key in
	 * @param boolean $blnIsField If true, the set will not be quoted
	 *
	 * @return string The FIND_IN_SET() statement
	 */
	public function findInSet($strKey, $varSet, $blnIsField=false)
	{
		if (is_array($varSet))
		{
			$varSet = implode(',', $varSet);
		}

		return $this->find_in_set($strKey, $varSet, $blnIsField);
	}


	/**
	 * Return all tables of a database as array
	 *
	 * @param string  $strDatabase The database name
	 * @param boolean $blnNoCache  If true, the cache will be bypassed
	 *
	 * @return array An array of table names
	 */
	public function listTables($strDatabase=null, $blnNoCache=false)
	{
		if ($strDatabase === null)
		{
			$strDatabase = $this->arrConfig['dbDatabase'];
		}

		if (!$blnNoCache && isset($this->arrCache[$strDatabase]))
		{
			return $this->arrCache[$strDatabase];
		}

		$arrReturn = array();
		$objTables = $this->query(sprintf($this->strListTables, $strDatabase));

		while ($objTables->next())
		{
			$arrReturn[] = current($objTables->row());
		}

		$this->arrCache[$strDatabase] = $arrReturn;

		return $this->arrCache[$strDatabase];
	}


	/**
	 * Determine if a particular database table exists
	 *
	 * @param string  $strTable    The table name
	 * @param string  $strDatabase The optional database name
	 * @param boolean $blnNoCache  If true, the cache will be bypassed
	 *
	 * @return boolean True if the table exists
	 */
	public function tableExists($strTable, $strDatabase=null, $blnNoCache=false)
	{
		if ($strTable == '')
		{
			return false;
		}

		return in_array($strTable, $this->listTables($strDatabase, $blnNoCache));
	}


	/**
	 * Return all columns of a particular table as array
	 *
	 * @param string  $strTable   The table name
	 * @param boolean $blnNoCache If true, the cache will be bypassed
	 *
	 * @return array An array of column names
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
	 *
	 * @param string  $strField   The field name
	 * @param string  $strTable   The table name
	 * @param boolean $blnNoCache If true, the cache will be bypassed
	 *
	 * @return boolean True if the field exists
	 */
	public function fieldExists($strField, $strTable, $blnNoCache=false)
	{
		if ($strField == '' || $strTable == '')
		{
			return false;
		}

		foreach ($this->listFields($strTable, $blnNoCache) as $arrField)
		{
			if ($arrField['name'] == $strField)
			{
				return true;
			}
		}

		return false;
	}


	/**
	 * Determine if a particular index exists
	 *
	 * @param string  $strName    The index name
	 * @param string  $strTable   The table name
	 * @param boolean $blnNoCache If true, the cache will be bypassed
	 *
	 * @return boolean True if the index exists
	 */
	public function indexExists($strName, $strTable, $blnNoCache=false)
	{
		if ($strName == '' || $strTable == '')
		{
			return false;
		}

		foreach ($this->listFields($strTable, $blnNoCache) as $arrField)
		{
			if ($arrField['name'] == $strName && $arrField['type'] == 'index')
			{
				return true;
			}
		}

		return false;
	}


	/**
	 * Return the field names of a particular table as array
	 *
	 * @param string  $strTable   The table name
	 * @param boolean $blnNoCache If true, the cache will be bypassed
	 *
	 * @return array An array of field names
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
	 * Check whether a field value in the database is unique
	 *
	 * @param string  $strTable The table name
	 * @param string  $strField The field name
	 * @param mixed   $varValue The field value
	 * @param integer $intId    The ID of a record to exempt
	 *
	 * @return boolean True if the field value is unique
	 */
	public function isUniqueValue($strTable, $strField, $varValue, $intId=null)
	{
		$strQuery = "SELECT * FROM $strTable WHERE $strField=?";

		if ($intId !== null)
		{
			$strQuery .= " AND id!=?";
		}

		$objUnique = $this->prepare($strQuery)
						  ->limit(1)
						  ->execute($varValue, $intId);

		return $objUnique->numRows ? false : true;
	}


	/**
	 * Return the IDs of all child records of a particular record (see #2475)
	 *
	 * @author Andreas Schempp
	 *
	 * @param mixed   $arrParentIds An array of parent IDs
	 * @param string  $strTable     The table name
	 * @param boolean $blnSorting   True if the table has a sorting field
	 * @param array   $arrReturn    The array to be returned
	 * @param string  $strWhere     Additional WHERE condition
	 *
	 * @return array An array of child record IDs
	 */
	public function getChildRecords($arrParentIds, $strTable, $blnSorting=false, $arrReturn=array(), $strWhere='')
	{
		if (!is_array($arrParentIds))
		{
			$arrParentIds = array($arrParentIds);
		}

		if (empty($arrParentIds))
		{
			return $arrReturn;
		}

		$arrParentIds = array_map('intval', $arrParentIds);
		$objChilds = $this->query("SELECT id, pid FROM " . $strTable . " WHERE pid IN(" . implode(',', $arrParentIds) . ")" . ($strWhere ? " AND $strWhere" : "") . ($blnSorting ? " ORDER BY " . $this->findInSet('pid', $arrParentIds) . ", sorting" : ""));

		if ($objChilds->numRows > 0)
		{
			if ($blnSorting)
			{
				$arrChilds = array();
				$arrOrdered = array();

				while ($objChilds->next())
				{
					$arrChilds[] = $objChilds->id;
					$arrOrdered[$objChilds->pid][] = $objChilds->id;
				}

				foreach (array_reverse(array_keys($arrOrdered)) as $pid)
				{
					$pos = (int) array_search($pid, $arrReturn);
					array_insert($arrReturn, $pos+1, $arrOrdered[$pid]);
				}

				$arrReturn = $this->getChildRecords($arrChilds, $strTable, $blnSorting, $arrReturn, $strWhere);
			}
			else
			{
				$arrChilds = $objChilds->fetchEach('id');
				$arrReturn = array_merge($arrChilds, $this->getChildRecords($arrChilds, $strTable, $blnSorting, $arrReturn, $strWhere));
			}
		}

		return $arrReturn;
	}


	/**
	 * Return the IDs of all parent records of a particular record
	 *
	 * @param integer $intId    The ID of the record
	 * @param string  $strTable The table name
	 *
	 * @return array An array of parent record IDs
	 */
	public function getParentRecords($intId, $strTable)
	{
		$arrReturn = array();

		// Currently supports a nesting-level of 10
		$objPages = $this->prepare("SELECT id, @pid:=pid FROM $strTable WHERE id=?" . str_repeat(" UNION SELECT id, @pid:=pid FROM $strTable WHERE id=@pid", 9))
						 ->execute($intId);

		while ($objPages->next())
		{
			$arrReturn[] = $objPages->id;
		}

		return $arrReturn;
	}


	/**
	 * Change the current database
	 *
	 * @param string $strDatabase The name of the target database
	 *
	 * @return boolean True if the database was changed successfully
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
	 * Lock one or more tables
	 *
	 * @param array $arrTables An array of table names to be locked
	 */
	public function lockTables($arrTables)
	{
		$this->lock_tables($arrTables);
	}


	/**
	 * Unlock all tables
	 */
	public function unlockTables()
	{
		$this->unlock_tables();
	}


	/**
	 * Return the table size in bytes
	 *
	 * @param string $strTable The table name
	 *
	 * @return integer The table size in bytes
	 */
	public function getSizeOf($strTable)
	{
		return $this->get_size_of($strTable);
	}


	/**
	 * Return the next autoincrement ID of a table
	 *
	 * @param string $strTable The table name
	 *
	 * @return integer The autoincrement ID
	 */
	public function getNextId($strTable)
	{
		return $this->get_next_id($strTable);
	}


	/**
	 * Return a universal unique identifier
	 *
	 * @return string The UUID string
	 */
	public function getUuid()
	{
		return $this->get_uuid();
	}


	/**
	 * Connect to the database server and select the database
	 */
	abstract protected function connect();


	/**
	 * Disconnect from the database
	 */
	abstract protected function disconnect();


	/**
	 * Return the last error message
	 *
	 * @return string The error message
	 */
	abstract protected function get_error();


	/**
	 * Auto-generate a FIND_IN_SET() statement
	 *
	 * @param string  $strKey     The field name
	 * @param mixed   $varSet     The set to find the key in
	 * @param boolean $blnIsField If true, the set will not be quoted
	 *
	 * @return string The FIND_IN_SET() statement
	 */
	abstract protected function find_in_set($strKey, $varSet, $blnIsField=false);


	/**
	 * Return a standardized array with the field information
	 *
	 * * name:       field name (e.g. my_field)
	 * * type:       field type (e.g. "int" or "number")
	 * * length:     field length (e.g. 20)
	 * * precision:  precision of a float number (e.g. 5)
	 * * null:       NULL or NOT NULL
	 * * default:    default value (e.g. "default_value")
	 * * attributes: attributes (e.g. "unsigned")
	 * * index:      PRIMARY, UNIQUE or INDEX
	 * * extra:      extra information (e.g. auto_increment)
	 *
	 * @param string $strTable The table name
	 *
	 * @return array An array with the field information
	 *
	 * @todo Support all kind of keys (e.g. FULLTEXT or FOREIGN)
	 */
	abstract protected function list_fields($strTable);


	/**
	 * Change the current database
	 *
	 * @param string $strDatabase The name of the target database
	 *
	 * @return boolean True if the database was changed successfully
	 */
	abstract protected function set_database($strDatabase);


	/**
	 * Begin a transaction
	 */
	abstract protected function begin_transaction();


	/**
	 * Commit a transaction
	 */
	abstract protected function commit_transaction();


	/**
	 * Rollback a transaction
	 */
	abstract protected function rollback_transaction();


	/**
	 * Lock one or more tables
	 *
	 * @param array $arrTables An array of table names
	 */
	abstract protected function lock_tables($arrTables);


	/**
	 * Unlock all tables
	 */
	abstract protected function unlock_tables();


	/**
	 * Return the table size in bytes
	 *
	 * @param string $strTable The table name
	 *
	 * @return integer The table size in bytes
	 */
	abstract protected function get_size_of($strTable);


	/**
	 * Return the next autoincrement ID of a table
	 *
	 * @param string $strTable The table name
	 *
	 * @return integer The autoincrement ID
	 */
	abstract protected function get_next_id($strTable);


	/**
	 * Return a universal unique identifier
	 *
	 * @return string The UUID string
	 */
	abstract protected function get_uuid();


	/**
	 * Create a Database\Statement object
	 *
	 * @param resource $resConnection        The connection ID
	 * @param boolean  $blnDisableAutocommit If true, autocommitting will be disabled
	 *
	 * @return \Database\Statement The Database\Statement object
	 */
	abstract protected function createStatement($resConnection, $blnDisableAutocommit);


	/**
	 * Execute a query and do not cache the result
	 *
	 * @param string $strQuery The query string
	 *
	 * @return \Database\Result|object The Database\Result object
	 *
	 * @deprecated Use \Database::execute() instead
	 */
	public function executeUncached($strQuery)
	{
		return $this->execute($strQuery);
	}


	/**
	 * Always execute the query and add or replace an existing cache entry
	 *
	 * @param string $strQuery The query string
	 *
	 * @return \Database\Result|object The Database\Result object
	 *
	 * @deprecated Use \Database::execute() instead
	 */
	public function executeCached($strQuery)
	{
		return $this->execute($strQuery);
	}
}
