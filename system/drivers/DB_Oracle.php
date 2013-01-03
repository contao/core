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
 * Class DB_Oracle
 *
 * Driver class for Oracle databases.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Driver
 */
class DB_Oracle extends Database
{

	/**
	 * List tables query
	 * @var string
	 */
	protected $strListTables = "SELECT table_name, table_type FROM cat WHERE table_type='TABLE'";

	/**
	 * List fields query
	 * @var string
	 */
	protected $strListFields = "SELECT cname, coltype, width, SCALE, PRECISION, NULLS, DEFAULTVAL FROM col WHERE tname='%s' ORDER BY colno";


	/**
	 * Connect to the database server and select the database
	 */
	protected function connect()
	{
		if ($GLOBALS['TL_CONFIG']['dbPconnect'])
		{
			$this->resConnection = @oci_pconnect($GLOBALS['TL_CONFIG']['dbUser'], $GLOBALS['TL_CONFIG']['dbPass'], '', $GLOBALS['TL_CONFIG']['dbCharset']);
		}
		else
		{
			$this->resConnection = @oci_connect($GLOBALS['TL_CONFIG']['dbUser'], $GLOBALS['TL_CONFIG']['dbPass'], '', $GLOBALS['TL_CONFIG']['dbCharset']);
		}
	}


	/**
	 * Disconnect from the database
	 */
	protected function disconnect()
	{
		@oci_close($this->resConnection);
	}


	/**
	 * Return the last error message
	 * @return string
	 */
	protected function get_error()
	{
		$arrError = oci_error();
		return $arrError['message'];
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
			$arrSet[$k] = str_replace("'", "''", $v);
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
	 */
	protected function list_fields($strTable)
	{
		$arrReturn = array();
		$arrFields = $this->query(sprintf($this->strListFields, $strTable))->fetchAllAssoc();

		foreach ($arrFields as $k=>$v)
		{
			$arrReturn[$k]['name'] = $v['CNAME'];
			$arrReturn[$k]['type'] = $v['COLTYPE'];
			$arrReturn[$k]['length'] = $v['WIDTH'];
			$arrReturn[$k]['precision'] = $v['PRECISION'];
			$arrReturn[$k]['null'] = $v['NULLS'];
			$arrReturn[$k]['default'] = $v['DEFAULTVAL'];
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
		return false;
	}


	/**
	 * Begin a transaction
	 */
	protected function begin_transaction()
	{
		$this->blnDisableAutocommit = true;
	}


	/**
	 * Commit a transaction
	 */
	protected function commit_transaction()
	{
		@oci_commit($this->resConnection);
		$this->blnDisableAutocommit = false;
	}


	/**
	 * Rollback a transaction
	 */
	protected function rollback_transaction()
	{
		@oci_rollback($this->resConnection);
		$this->blnDisableAutocommit = false;
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
	 * @return DB_Oracle_Statement
	 */
	protected function createStatement($resConnection, $blnDisableAutocommit)
	{
		return new DB_Oracle_Statement($resConnection, $blnDisableAutocommit);
	}
}


/**
 * Class DB_Oracle_Statement
 *
 * Driver class for MySQL databases.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Driver
 */
class DB_Oracle_Statement extends Database_Statement
{

	/**
	 * Current statement resource
	 * @var resource
	 */
	protected $resStatement;


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
		return "'" . str_replace("'", "''", $strString) . "'";
	}


	/**
	 * Limit the current query
	 * @param integer
	 * @param integer
	 */
	protected function limit_query($intRows, $intOffset)
	{
		// Return if the current statement is not a SELECT statement
		if (strncasecmp($this->strQuery, 'SELECT', 6) !== 0)
		{
			return;
		}

		// Get field names of the current query
		$resStmt = @oci_parse($this->resConnection, sprintf('SELECT * FROM (%s) WHERE rownum < 2', $this->strQuery));
		@oci_execute($resStmt);

		$arrFields = array_keys(@oci_fetch_assoc($resStmt));
		$strFields = implode(', ', $arrFields);

		// Oracle starts row counting at 1
		++$intRows;

		$this->strQuery = sprintf('SELECT %s FROM (SELECT %s, ROWNUM AS ROWN FROM (%s) WHERE ROWNUM < %d) WHERE ROWN > %d',
								$strFields,
								$strFields,
								$this->strQuery,
								intval($intOffset + $intRows),
								intval($intOffset));
	}


	/**
	 * Execute the current query
	 * @return resource
	 */
	protected function execute_query()
	{
		if (($this->resStatement = @oci_parse($this->resConnection, $this->strQuery)) == false)
		{
			$this->resStatement = $this->resConnection;
			return false;
		}

		$strExecutionMode = $this->blnDisableAutocommit ? OCI_DEFAULT : OCI_COMMIT_ON_SUCCESS;

		if (!@oci_execute($this->resStatement, $strExecutionMode))
		{
			return false;
		}

		return $this->resStatement;
	}


	/**
	 * Return the last error message
	 * @return string
	 */
	protected function get_error()
	{
		$arrError = is_resource($this->resStatement) ? oci_error($this->resStatement) : oci_error();
		return $arrError['message'];
	}


	/**
	 * Return the number of affected rows
	 * @return integer
	 */
	protected function affected_rows()
	{
		return @oci_num_rows($this->resResult);
	}


	/**
	 * Return the last insert ID
	 * @return boolean
	 */
	protected function insert_id()
	{
		return false;
	}


	/**
	 * Explain the current query
	 * @return boolean
	 */
	protected function explain_query()
	{
		return false;
	}

	/**
	 * Create a Database_Result object
	 * @param resource
	 * @param string
	 * @return DB_Oracle_Result
	 */
	protected function createResult($resResult, $strQuery)
	{
		return new DB_Oracle_Result($resResult, $strQuery);
	}
}


/**
 * Class DB_Oracle_Result
 *
 * Driver class for MySQL databases.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Driver
 */
class DB_Oracle_Result extends Database_Result
{

	/**
	 * Fetch the current row as enumerated array
	 * @return array
	 */
	protected function fetch_row()
	{
		return @oci_fetch_row($this->resResult);
	}


	/**
	 * Fetch the current row as associative array
	 * @return array
	 */
	protected function fetch_assoc()
	{
		return @oci_fetch_assoc($this->resResult);
	}


	/**
	 * Return the number of rows of the current result
	 * @return integer
	 */
	protected function num_rows()
	{
		$this->fetchAllAssoc();
		$this->reset();

		return count($this->arrCache);
	}


	/**
	 * Return the number of fields of the current result
	 * @return integer
	 */
	protected function num_fields()
	{
		return @oci_num_fields($this->resResult);
	}


	/**
	 * Get the column information
	 * @param integer
	 * @return object
	 */
	protected function fetch_field($intOffset)
	{
		++$intOffset; // Oracle starts row counting at 1
		$objData = new stdClass();

		$objData->name = @ocicolumnname($this->resResult, $intOffset);
		$objData->max_length = @ocicolumnsize($this->resResult, $intOffset);
		$objData->not_null = @ocicolumnisnull($this->resResult, $intOffset);
		$objData->type = @ocicolumntype($this->resResult, $intOffset);

		return $objData;
	}


	/**
	 * Free the current result
	 */
	public function free()
	{
		if (is_resource($this->resResult))
		{
			@oci_free_statement($this->resResult);
		}
	}
}

?>