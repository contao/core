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
 * Class DB_Mysql
 *
 * Driver class for MySQL databases.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Driver
 */
class DB_Mysql extends Database
{

	/**
	 * List tables query
	 * @var string
	 */
	protected $strListTables = "SHOW TABLES FROM `%s`";

	/**
	 * List fields query
	 * @var string
	 */
	protected $strListFields = "SHOW COLUMNS FROM `%s`";


	/**
	 * Connect to the database server and select the database
	 */
	protected function connect()
	{
		$strHost = $GLOBALS['TL_CONFIG']['dbHost'];

		if ($GLOBALS['TL_CONFIG']['dbPort'])
		{
			$strHost .= ':' . $GLOBALS['TL_CONFIG']['dbPort'];
		}

		if ($GLOBALS['TL_CONFIG']['dbPconnect'])
		{
			$this->resConnection = @mysql_pconnect($strHost, $GLOBALS['TL_CONFIG']['dbUser'], $GLOBALS['TL_CONFIG']['dbPass']);
		}
		else
		{
			$this->resConnection = @mysql_connect($strHost, $GLOBALS['TL_CONFIG']['dbUser'], $GLOBALS['TL_CONFIG']['dbPass']);
		}

		if (is_resource($this->resConnection))
		{
			@mysql_query("SET sql_mode=''", $this->resConnection);
			@mysql_query("SET NAMES " . $GLOBALS['TL_CONFIG']['dbCharset'], $this->resConnection);
			@mysql_select_db($GLOBALS['TL_CONFIG']['dbDatabase'], $this->resConnection);
		}
	}


	/**
	 * Disconnect from the database
	 */
	protected function disconnect()
	{
		@mysql_close($this->resConnection);
	}


	/**
	 * Return the last error message
	 * @return string
	 */
	protected function get_error()
	{
		if (is_resource($this->resConnection))
		{
			return mysql_error($this->resConnection);
		}

		return mysql_error();
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
		if ($blnIsField)
		{
			return "FIND_IN_SET(" . $strKey . ", " . $strSet . ")";
		}
		else
		{
			return "FIND_IN_SET(" . $strKey . ", '" . mysql_real_escape_string($strSet, $this->resConnection) . "')";
		}
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
	 * - index:      PRIMARY, UNIQUE or INDEX
	 * - extra:      extra information (e.g. auto_increment)
	 * @param string
	 * @return array
	 * @todo Support all kind of keys (e.g. FULLTEXT or FOREIGN).
	 */
	protected function list_fields($strTable)
	{
		$arrReturn = array();
		$arrFields = $this->query(sprintf($this->strListFields, $strTable))->fetchAllAssoc();

		foreach ($arrFields as $k=>$v)
		{
			$arrChunks = preg_split('/(\([^\)]+\))/', $v['Type'], -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);

			$arrReturn[$k]['name'] = $v['Field'];
			$arrReturn[$k]['type'] = $arrChunks[0];

			if (strlen($arrChunks[1]))
			{
				$arrChunks[1] = str_replace(array('(', ')'), array('', ''), $arrChunks[1]);
				$arrSubChunks = explode(',', $arrChunks[1]);

				$arrReturn[$k]['length'] = trim($arrSubChunks[0]);

				if (strlen($arrSubChunks[1]))
				{
					$arrReturn[$k]['precision'] = trim($arrSubChunks[1]);
				}
			}

			if (strlen($arrChunks[2]))
			{
				$arrReturn[$k]['attributes'] = trim($arrChunks[2]);
			}

			if (strlen($v['Key']))
			{
				switch ($v['Key'])
				{
					case 'PRI':
						$arrReturn[$k]['index'] = 'PRIMARY';
						break;

					case 'UNI':
						$arrReturn[$k]['index'] = 'UNIQUE';
						break;

					case 'MUL':
						// Ignore
						break;

					default:
						$arrReturn[$k]['index'] = 'KEY';
						break;
				}
			}

			$arrReturn[$k]['null'] = ($v['Null'] == 'YES') ? 'NULL' : 'NOT NULL';
			$arrReturn[$k]['default'] = $v['Default'];
			$arrReturn[$k]['extra'] = $v['Extra'];
		}

		$arrIndexes = $this->query("SHOW INDEXES FROM `$strTable`")->fetchAllAssoc();

		foreach ($arrIndexes as $arrIndex)
		{
			$arrReturn[$arrIndex['Key_name']]['name'] = $arrIndex['Key_name'];
			$arrReturn[$arrIndex['Key_name']]['type'] = 'index';
			$arrReturn[$arrIndex['Key_name']]['index_fields'][] = $arrIndex['Column_name'];
			$arrReturn[$arrIndex['Key_name']]['index'] = (($arrIndex['Non_unique'] == 0) ? 'UNIQUE' : 'KEY');
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
		return @mysql_select_db($strDatabase, $this->resConnection);
	}


	/**
	 * Begin a transaction
	 */
	protected function begin_transaction()
	{
		@mysql_query("SET AUTOCOMMIT=0", $this->resConnection);
		@mysql_query("BEGIN", $this->resConnection);
	}


	/**
	 * Commit a transaction
	 */
	protected function commit_transaction()
	{
		@mysql_query("COMMIT", $this->resConnection);
		@mysql_query("SET AUTOCOMMIT=1", $this->resConnection);
	}


	/**
	 * Rollback a transaction
	 */
	protected function rollback_transaction()
	{
		@mysql_query("ROLLBACK", $this->resConnection);
		@mysql_query("SET AUTOCOMMIT=1", $this->resConnection);
	}


	/**
	 * Lock one or more tables
	 * @param array
	 */
	protected function lock_tables($arrTables)
	{
		$arrLocks = array();

		foreach ($arrTables as $table=>$mode)
		{
			$arrLocks[] = $table .' '. $mode;
		}

		@mysql_query("LOCK TABLES " . implode(', ', $arrLocks));
	}


	/**
	 * Unlock all tables
	 */
	protected function unlock_tables()
	{
		@mysql_query("UNLOCK TABLES");
	}


	/**
	 * Return the table size in bytes
	 * @param string
	 * @return integer
	 */
	protected function get_size_of($strTable)
	{
		$objStatus = @mysql_query("SHOW TABLE STATUS LIKE '" . $strTable . "'");
		$objStatus = @mysql_fetch_object($objStatus);

		return ($objStatus->Data_length + $objStatus->Index_length);
	}


	/**
	 * Return the next autoincrement ID of a table
	 * @param string
	 * @return integer
	 */
	protected function get_next_id($strTable)
	{
		$objStatus = @mysql_query("SHOW TABLE STATUS LIKE '" . $strTable . "'");
		$objStatus = @mysql_fetch_object($objStatus);

		return $objStatus->Auto_increment;
	}


	/**
	 * Create a Database_Statement object
	 * @param resource
	 * @param boolean
	 * @return DB_Mysql_Statement
	 */
	protected function createStatement($resConnection, $blnDisableAutocommit)
	{
		return new DB_Mysql_Statement($resConnection, $blnDisableAutocommit);
	}
}


/**
 * Class DB_Mysql_Statement
 *
 * Driver class for MySQL databases.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Driver
 */
class DB_Mysql_Statement extends Database_Statement
{

	/**
	 * Prepare a query and return it
	 * @param string
	 * @return string
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
		return "'" . mysql_real_escape_string($strString, $this->resConnection) . "'";
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
		return @mysql_query($this->strQuery, $this->resConnection);
	}


	/**
	 * Return the last error message
	 * @return string
	 */
	protected function get_error()
	{
		return mysql_error($this->resConnection);
	}


	/**
	 * Return the number of affected rows
	 * @return integer
	 */
	protected function affected_rows()
	{
		return @mysql_affected_rows($this->resConnection);
	}


	/**
	 * Return the last insert ID
	 * @return integer
	 */
	protected function insert_id()
	{
		return @mysql_insert_id($this->resConnection);
	}


	/**
	 * Explain the current query
	 * @return array
	 */
	protected function explain_query()
	{
		return @mysql_fetch_assoc(@mysql_query('EXPLAIN ' . $this->strQuery, $this->resConnection));
	}

	/**
	 * Create a Database_Result object
	 * @param resource
	 * @param string
	 * @return DB_Mysql_Result
	 */
	protected function createResult($resResult, $strQuery)
	{
		return new DB_Mysql_Result($resResult, $strQuery);
	}
}


/**
 * Class DB_Mysql_Result
 *
 * Driver class for MySQL databases.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Driver
 */
class DB_Mysql_Result extends Database_Result
{

	/**
	 * Fetch the current row as enumerated array
	 * @return array
	 */
	protected function fetch_row()
	{
		return @mysql_fetch_row($this->resResult);
	}


	/**
	 * Fetch the current row as associative array
	 * @return array
	 */
	protected function fetch_assoc()
	{
		return @mysql_fetch_assoc($this->resResult);
	}


	/**
	 * Return the number of rows of the current result
	 * @return integer
	 */
	protected function num_rows()
	{
		return @mysql_num_rows($this->resResult);
	}


	/**
	 * Return the number of fields of the current result
	 * @return integer
	 */
	protected function num_fields()
	{
		return @mysql_num_fields($this->resResult);
	}


	/**
	 * Get the column information
	 * @param integer
	 * @return object
	 */
	protected function fetch_field($intOffset)
	{
		return @mysql_fetch_field($this->resResult, $intOffset);
	}


	/**
	 * Free the current result
	 */
	public function free()
	{
		if (is_resource($this->resResult))
		{
			@mysql_free_result($this->resResult);
		}
	}
}

?>