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
 * Class Database
 *
 * Provide methods to handle database communication.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
abstract class Database
{

	/**
	 * Current object instances (Singletons)
	 * @var array
	 */
	protected static $arrInstances;
	
	/**
	 * Connection config
	 * @var array
	 */
	protected $arrConfig;

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
	 * @throws \Exception
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
		}
	}


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final private function __clone() {}


	/**
	 * Return an object property
	 * @param string
	 * @return string|null
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
	 * @return \Contao\Database
	 * @throws \Exception
	 */
	public static function getInstance($strKey='core', array $arrConfig=null)
	{
		if (!is_object(static::$arrInstances[$strKey]))
		{
			if ($arrConfig === null)
			{
				$arrConfig = $GLOBALS['TL_CONFIG'];
			}
			else
			{
				$arrConfig = array_merge($GLOBALS['TL_CONFIG'], $arrConfig);
			}

			$strClass = '\\Database_' . ucfirst(strtolower($arrConfig['dbDriver']));
			static::$arrInstances[$strKey] = new $strClass($arrConfig);
		}

		return static::$arrInstances[$strKey];
	}


	/**
	 * Prepare a statement (return a Database_Statement object)
	 * @param string
	 * @return \Contao\Database_Statement
	 */
	public function prepare($strQuery)
	{
		$objStatement = $this->createStatement($this->resConnection, $this->blnDisableAutocommit);
		return $objStatement->prepare($strQuery);
	}


	/**
	 * Execute a query (return a Database_Result object)
	 * @param string
	 * @return \Contao\Database_Result
	 */
	public function execute($strQuery)
	{
		return $this->prepare($strQuery)->execute();
	}


	/**
	 * Execute a query and do not cache the result
	 * @param string
	 * @return \Contao\Database_Result
	 */
	public function executeUncached($strQuery)
	{
		return $this->prepare($strQuery)->executeUncached();
	}


	/**
	 * Execute a raw query (returns a Database_Result object)
	 * @param string
	 * @return \Contao\Database_Result
	 */
	public function query($strQuery)
	{
		$objStatement = $this->createStatement($this->resConnection, $this->blnDisableAutocommit);
		return $objStatement->query($strQuery);
	}


	/**
	 * Auto-generate a FIND_IN_SET() statement
	 * @param string
	 * @param mixed
	 * @param boolean
	 * @return string
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
	 * @param string
	 * @param boolean
	 * @return array
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
		$arrTables = $this->query(sprintf($this->strListTables, $strDatabase))->fetchAllAssoc();

		foreach ($arrTables as $arrTable)
		{
			$arrReturn[] = current($arrTable);
		}

		$this->arrCache[$strDatabase] = $arrReturn;
		return $this->arrCache[$strDatabase];
	}


	/**
	 * Determine if a particular database table exists
	 * @param string
	 * @param string
	 * @param boolean
	 * @return boolean
	 */
	public function tableExists($strTable, $strDatabase=null, $blnNoCache=false)
	{
		return in_array($strTable, $this->listTables($strDatabase, $blnNoCache));
	}


	/**
	 * Return all columns of a particular table as array
	 * @param string
	 * @param boolean
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
	 * @param string
	 * @param string
	 * @param boolean
	 * @return boolean
	 */
	public function fieldExists($strField, $strTable, $blnNoCache=false)
	{
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
	 * Return the field names of a particular table as array
	 * @param string
	 * @param boolean
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
	 * @param string
	 * @return boolean
	 */
	public function setDatabase($strDatabase)
	{
		return $this->set_database($strDatabase);
	}


	/**
	 * Begin a transaction
	 * @return void
	 */
	public function beginTransaction()
	{
		$this->begin_transaction();
	}


	/**
	 * Commit a transaction
	 * @return void
	 */
	public function commitTransaction()
	{
		$this->commit_transaction();
	}


	/**
	 * Rollback a transaction
	 * @return void
	 */
	public function rollbackTransaction()
	{
		$this->rollback_transaction();
	}


	/**
	 * Lock one or more tables
	 * @param array
	 * @return void
	 */
	public function lockTables($arrTables)
	{
		$this->lock_tables($arrTables);
	}


	/**
	 * Unlock all tables
	 * @return void
	 */
	public function unlockTables()
	{
		$this->unlock_tables();
	}


	/**
	 * Return the table size in bytes
	 * @param string
	 * @return integer
	 */
	public function getSizeOf($strTable)
	{
		return $this->get_size_of($strTable);
	}


	/**
	 * Return the next autoincrement ID of a table
	 * @param string
	 * @return integer
	 */
	public function getNextId($strTable)
	{
		return $this->get_next_id($strTable);
	}


	// Abstract database driver methods
	abstract protected function connect();
	abstract protected function disconnect();
	abstract protected function get_error();
	abstract protected function find_in_set($strKey, $strSet, $blnIsField=false);
	abstract protected function begin_transaction();
	abstract protected function commit_transaction();
	abstract protected function rollback_transaction();
	abstract protected function list_fields($strTable);
	abstract protected function set_database($strDatabase);
	abstract protected function lock_tables($arrTables);
	abstract protected function unlock_tables();
	abstract protected function get_size_of($strTable);
	abstract protected function get_next_id($strTable);
	abstract protected function createStatement($resConnection, $blnDisableAutocommit);
}
