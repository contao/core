<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class DB_Postgresql
 *
 * Driver class for PostgreSQL databases.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Driver
 */
class DB_Postgresql extends Database
{

	/**
	 * List tables query
	 * @var string
	 */
	protected $strListTables = "SELECT tablename FROM pg_tables WHERE tablename NOT LIKE 'pg_%%' AND tablename not in ('sql_features', 'sql_implementation_info', 'sql_languages', 'sql_packages', 'sql_sizing', 'sql_sizing_profiles') ORDER BY tablename";

	/**
	 * List fields query
	 * @var string
	 */
	protected $strListFields = "SELECT a.attname, t.typname, a.attlen, a.atttypmod, a.attnotnull, a.atthasdef, a.attnum FROM pg_class c, pg_attribute a, pg_type t WHERE relkind in ('r','v') AND (c.relname='%s' or c.relname = lower('%s')) and a.attname not like '....%%' AND a.attnum > 0 AND a.atttypid = t.oid AND a.attrelid = c.oid ORDER BY a.attnum";


	/**
	 * Connect to the database server and select the database
	 */
	protected function connect()
	{
		$strConnection = sprintf('host=%s port=%s user=%s password=%s dbname=%s',
								$GLOBALS['TL_CONFIG']['dbHost'],
								$GLOBALS['TL_CONFIG']['dbPort'],
								$GLOBALS['TL_CONFIG']['dbUser'],
								$GLOBALS['TL_CONFIG']['dbPass'],
								$GLOBALS['TL_CONFIG']['dbDatabase']);

		if ($GLOBALS['TL_CONFIG']['dbPconnect'])
		{
			$this->resConnection = @pg_pconnect($strConnection);
		}
		else
		{
			$this->resConnection = @pg_connect($strConnection);
		}
	}


	/**
	 * Disconnect from the database
	 */
	protected function disconnect()
	{
		@pg_close($this->resConnection);
	}


	/**
	 * Return the last error message
	 * @return string
	 */
	protected function get_error()
	{
		if (is_resource($this->resConnection))
		{
			return pg_last_error($this->resConnection);
		}

		return pg_last_error();
	}


	/**
	 * Auto-generate a FIND_IN_SET() statement
	 * @param string
	 * @param string
	 * @param boolean
	 * @return string
	 */
	protected function find_in_set($strKey, $strSet, $blnIsField=false)
	{
		$arrSet = trimsplit(',', $strSet);

		foreach ($arrSet as $k=>$v)
		{
			$arrSet[$k] = pg_escape_string($v);
		}

		return $strKey . "='" . implode("' DESC, $strKey='", $arrSet) . "' DESC";
	}


	/**
	 * Return a standardized array with field information
	 * 
	 * Standardized format:
	 * - name:       field name (e.g. my_field)
	 * - type:       field type (e.g. "int" or "number")
	 * - length:     field length (e.g. 20)
	 * - precision:  precision of a float number (e.g. 5)
	 * - null:       NULL or NOT NULL
	 * - default:    default value (e.g. "default_value")
	 * - attributes: attributes (e.g. "unsigned")
	 * - extra:      extra information (e.g. auto_increment)
	 * @param string
	 * @return array
	 * @todo This function is not tested yet, nor is the list tables and list fields statement!
	 */
	protected function list_fields($strTable)
	{
		$arrReturn = array();
		$arrFields = $this->query(sprintf($this->strListFields, $strTable, $strTable))->fetchAllAssoc();

		foreach ($arrFields as $k=>$v)
		{
			$arrReturn[$k]['name'] = $v['attname'];
			$arrReturn[$k]['length'] = $v['attlen'];
		}

		return $arrReturn;
	}


	/**
	 * Change the current database
	 * @param string
	 * @return boolean
	 */
	protected function set_database($strDatabase)
	{
		$GLOBALS['TL_CONFIG']['dbDatabase'] = $strDatabase;
		return $this->connect();
	}


	/**
	 * Begin a transaction
	 */
	protected function begin_transaction()
	{
		@pg_query($this->resConnection, "BEGIN");
	}


	/**
	 * Commit a transaction
	 */
	protected function commit_transaction()
	{
		@pg_query($this->resConnection, "COMMIT");
	}


	/**
	 * Rollback a transaction
	 */
	protected function rollback_transaction()
	{
		@pg_query($this->resConnection, "ROLLBACK");
	}


	/**
	 * Lock one or more tables
	 * @param array
	 * @todo implement
	 */
	protected function lock_tables($arrTables) {}


	/**
	 * Unlock all tables
	 * @todo implement
	 */
	protected function unlock_tables() {}


	/**
	 * Return the table size in bytes
	 * @param string
	 * @return integer
	 * @todo implement
	 */
	protected function get_size_of($strTable) {}


	/**
	 * Return the next autoincrement ID of a table
	 * @param string
	 * @return integer
	 * @todo implement
	 */
	protected function get_next_id($strTable) {}


	/**
	 * Create a Database_Statement object
	 * @param resource
	 * @param boolean
	 * @return DB_Postgresql_Statement
	 */
	protected function createStatement($resConnection, $blnDisableAutocommit)
	{
		return new DB_Postgresql_Statement($resConnection, $blnDisableAutocommit);
	}
}


/**
 * Class DB_Postgresql_Statement
 *
 * Driver class for PostgreSQL databases.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Driver
 */
class DB_Postgresql_Statement extends Database_Statement
{

	/**
	 * Prepare a query and return it
	 * @param string
	 */
	protected function prepare_query($strQuery)
	{
		return $strQuery;
	}


	/**
	 * Escape a string
	 * @param string
	 * @return string
	 */
	protected function string_escape($strString)
	{
		return "'" . pg_escape_string($strString) . "'";
	}


	/**
	 * Limit the current query
	 * @param integer
	 * @param integer
	 */
	protected function limit_query($intRows, $intOffset)
	{
		if (strncasecmp($this->strQuery, 'SELECT', 6) === 0)
		{
			$this->strQuery .= ' LIMIT ' . $intOffset . ',' . $intRows;
		}
		else
		{
			$this->strQuery .= ' LIMIT ' . $intRows;
		}
	}


	/**
	 * Execute the current query
	 * @return resource
	 */
	protected function execute_query()
	{
		return @pg_query($this->resConnection, $this->strQuery);
	}


	/**
	 * Return the last error message
	 * @return string
	 */
	protected function get_error()
	{
		return pg_last_error($this->resConnection);
	}


	/**
	 * Return the number of affected rows
	 * @return integer
	 */
	protected function affected_rows()
	{
		return @pg_affected_rows($this->resResult);
	}


	/**
	 * Return the last insert ID
	 * @return integer
	 */
	protected function insert_id()
	{
		return @pg_getlastoid($this->resResult);
	}


	/**
	 * Explain the current query
	 * @return array
	 */
	protected function explain_query()
	{
		return @pg_fetch_assoc(@pg_query($this->resConnection, 'EXPLAIN ' . $this->strQuery));
	}

	/**
	 * Create a Database_Result object
	 * @param resource
	 * @param string
	 * @return DB_Postgresql_Result
	 */
	protected function createResult($resResult, $strQuery)
	{
		return new DB_Postgresql_Result($resResult, $strQuery);
	}
}


/**
 * Class DB_Postgresql_Result
 *
 * Driver class for PostgreSQL databases.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Driver
 */
class DB_Postgresql_Result extends Database_Result
{

	/**
	 * Fetch the current row as enumerated array
	 * @return array
	 */
	protected function fetch_row()
	{
		return @pg_fetch_row($this->resResult);
	}


	/**
	 * Fetch the current row as associative array
	 * @return array
	 */
	protected function fetch_assoc()
	{
		return @pg_fetch_assoc($this->resResult);
	}


	/**
	 * Return the number of rows of the current result
	 * @return integer
	 */
	protected function num_rows()
	{
		return @pg_num_rows($this->resResult);
	}


	/**
	 * Return the number of fields of the current result
	 * @return integer
	 */
	protected function num_fields()
	{
		return @pg_num_fields($this->resResult);
	}


	/**
	 * Get the column information
	 * @param integer
	 * @return object
	 */
	protected function fetch_field($intOffset)
	{
		$objData = new stdClass();

		$objData->name = @pg_field_name($this->resResult, $intOffset);
		$objData->max_length = @pg_field_size($this->resResult, $intOffset);
		$objData->not_null = @pg_field_is_null($this->resResult, $intOffset);
		$objData->type = @pg_field_type($this->resResult, $intOffset);

		return $objData;
	}


	/**
	 * Free the current result
	 */
	public function free()
	{
		if (is_resource($this->resResult))
		{
			@pg_free_result($this->resResult);
		}
	}
}

?>