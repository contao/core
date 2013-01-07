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
 * MySQL-specific database class
 * 
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
class Mysql extends \Database
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
		$strHost = $this->arrConfig['dbHost'];

		if ($this->arrConfig['dbPort'])
		{
			$strHost .= ':' . $this->arrConfig['dbPort'];
		}

		if ($this->arrConfig['dbPconnect'])
		{
			$this->resConnection = @mysql_pconnect($strHost, $this->arrConfig['dbUser'], $this->arrConfig['dbPass']);
		}
		else
		{
			$this->resConnection = @mysql_connect($strHost, $this->arrConfig['dbUser'], $this->arrConfig['dbPass']);
		}

		if (is_resource($this->resConnection))
		{
			@mysql_query("SET sql_mode=''", $this->resConnection);
			@mysql_query("SET NAMES " . $this->arrConfig['dbCharset'], $this->resConnection);
			@mysql_select_db($this->arrConfig['dbDatabase'], $this->resConnection);
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
	 * 
	 * @return string The error message
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
	 * 
	 * @param string  $strKey     The field name
	 * @param mixed   $varSet     The set to find the key in
	 * @param boolean $blnIsField If true, the set will not be quoted
	 * 
	 * @return string The FIND_IN_SET() statement
	 */
	protected function find_in_set($strKey, $varSet, $blnIsField=false)
	{
		if ($blnIsField)
		{
			return "FIND_IN_SET(" . $strKey . ", " . $varSet . ")";
		}
		else
		{
			return "FIND_IN_SET(" . $strKey . ", '" . mysql_real_escape_string($varSet, $this->resConnection) . "')";
		}
	}


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
	 * 
	 * @param string $strDatabase The name of the target database
	 * 
	 * @return boolean True if the database was changed successfully
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
	 * 
	 * @param array $arrTables An array of table names
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
	 * 
	 * @param string $strTable The table name
	 * 
	 * @return integer The table size in bytes
	 */
	protected function get_size_of($strTable)
	{
		$objStatus = @mysql_query("SHOW TABLE STATUS LIKE '" . $strTable . "'");
		$objStatus = @mysql_fetch_object($objStatus);

		return ($objStatus->Data_length + $objStatus->Index_length);
	}


	/**
	 * Return the next autoincrement ID of a table
	 * 
	 * @param string The table name
	 * 
	 * @return integer The autoincrement ID
	 */
	protected function get_next_id($strTable)
	{
		$objStatus = @mysql_query("SHOW TABLE STATUS LIKE '" . $strTable . "'");
		$objStatus = @mysql_fetch_object($objStatus);

		return $objStatus->Auto_increment;
	}


	/**
	 * Create a Database\Statement object
	 * 
	 * @param resource $resConnection        The connection ID
	 * @param boolean  $blnDisableAutocommit If true, autocommitting will be disabled
	 * 
	 * @return \Database\Mysql\Statement The Database\Statement object
	 */
	protected function createStatement($resConnection, $blnDisableAutocommit)
	{
		return new \Database\Mysql\Statement($resConnection, $blnDisableAutocommit);
	}
}
