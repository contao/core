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
 * MySQL-specific database class
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Mysql extends \Database
{

	/**
	 * Connect to the database server and select the database
	 *
	 * @throws \Exception If the connection cannot be established
	 */
	protected function connect()
	{
		$strHost = $this->arrConfig['dbHost'];

		if ($this->arrConfig['dbPort'])
		{
			$strHost .= ':' . $this->arrConfig['dbPort'];
		}

		if ($this->arrConfig['dbSocket'])
		{
			$strHost .= ':' . $this->arrConfig['dbSocket'];
		}

		if ($this->arrConfig['dbPconnect'])
		{
			$this->resConnection = mysql_pconnect($strHost, $this->arrConfig['dbUser'], $this->arrConfig['dbPass']);
		}
		else
		{
			$this->resConnection = mysql_connect($strHost, $this->arrConfig['dbUser'], $this->arrConfig['dbPass']);
		}

		if (mysql_error())
		{
			throw new \Exception(mysql_error());
		}

		mysql_query("SET NAMES " . $this->arrConfig['dbCharset'], $this->resConnection);
		mysql_query("SET sql_mode='" . $this->arrConfig['dbSqlMode'] . "'", $this->resConnection);

		mysql_select_db($this->arrConfig['dbDatabase'], $this->resConnection);
	}


	/**
	 * Disconnect from the database
	 */
	protected function disconnect()
	{
		mysql_close($this->resConnection);
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
		$objFields = $this->query("SHOW FULL COLUMNS FROM $strTable");

		while ($objFields->next())
		{
			$arrTmp = array();
			$arrChunks = preg_split('/(\([^\)]+\))/', $objFields->Type, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);

			$arrTmp['name'] = $objFields->Field;
			$arrTmp['type'] = $arrChunks[0];

			if (!empty($arrChunks[1]))
			{
				$arrChunks[1] = str_replace(array('(', ')'), '', $arrChunks[1]);

				// Handle enum fields (see #6387)
				if ($arrChunks[0] == 'enum')
				{
					$arrTmp['length'] = $arrChunks[1];
				}
				else
				{
					$arrSubChunks = explode(',', $arrChunks[1]);
					$arrTmp['length'] = trim($arrSubChunks[0]);

					if (!empty($arrSubChunks[1]))
					{
						$arrTmp['precision'] = trim($arrSubChunks[1]);
					}
				}
			}

			if (!empty($arrChunks[2]))
			{
				$arrTmp['attributes'] = trim($arrChunks[2]);
			}

			if ($objFields->Key != '')
			{
				switch ($objFields->Key)
				{
					case 'PRI':
						$arrTmp['index'] = 'PRIMARY';
						break;

					case 'UNI':
						$arrTmp['index'] = 'UNIQUE';
						break;

					case 'MUL':
						// Ignore
						break;

					default:
						$arrTmp['index'] = 'KEY';
						break;
				}
			}

			// Do not modify the order!
			$arrTmp['collation'] = $objFields->Collation;
			$arrTmp['null'] = ($objFields->Null == 'YES') ? 'NULL' : 'NOT NULL';
			$arrTmp['default'] = $objFields->Default;
			$arrTmp['extra'] = $objFields->Extra;
			$arrTmp['origtype'] = $objFields->Type;

			$arrReturn[] = $arrTmp;
		}

		$objIndex = $this->query("SHOW INDEXES FROM `$strTable`");

		while ($objIndex->next())
		{
			$strColumnName = $objIndex->Column_name;

			if ($objIndex->Sub_part != '')
			{
				$strColumnName .= '(' . $objIndex->Sub_part . ')';
			}

			$arrReturn[$objIndex->Key_name]['name'] = $objIndex->Key_name;
			$arrReturn[$objIndex->Key_name]['type'] = 'index';
			$arrReturn[$objIndex->Key_name]['index_fields'][] = $strColumnName;
			$arrReturn[$objIndex->Key_name]['index'] = (($objIndex->Non_unique == 0) ? 'UNIQUE' : 'KEY');
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
		return mysql_select_db($strDatabase, $this->resConnection);
	}


	/**
	 * Begin a transaction
	 */
	protected function begin_transaction()
	{
		mysql_query("SET AUTOCOMMIT=0", $this->resConnection);
		mysql_query("BEGIN", $this->resConnection);
	}


	/**
	 * Commit a transaction
	 */
	protected function commit_transaction()
	{
		mysql_query("COMMIT", $this->resConnection);
		mysql_query("SET AUTOCOMMIT=1", $this->resConnection);
	}


	/**
	 * Rollback a transaction
	 */
	protected function rollback_transaction()
	{
		mysql_query("ROLLBACK", $this->resConnection);
		mysql_query("SET AUTOCOMMIT=1", $this->resConnection);
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

		mysql_query("LOCK TABLES " . implode(', ', $arrLocks));
	}


	/**
	 * Unlock all tables
	 */
	protected function unlock_tables()
	{
		mysql_query("UNLOCK TABLES");
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
		$objStatus = mysql_query("SHOW TABLE STATUS LIKE '" . $strTable . "'");
		$objStatus = mysql_fetch_object($objStatus);

		return ($objStatus->Data_length + $objStatus->Index_length);
	}


	/**
	 * Return the next autoincrement ID of a table
	 *
	 * @param string $strTable The table name
	 *
	 * @return integer The autoincrement ID
	 */
	protected function get_next_id($strTable)
	{
		$objStatus = mysql_query("SHOW TABLE STATUS LIKE '" . $strTable . "'");
		$objStatus = mysql_fetch_object($objStatus);

		return $objStatus->Auto_increment;
	}


	/**
	 * Return a universal unique identifier
	 *
	 * @return string The UUID string
	 */
	protected function get_uuid()
	{
		static $ids;

		if (empty($ids))
		{
			$res = mysql_query(implode(' UNION ALL ', array_fill(0, 10, "SELECT UNHEX(REPLACE(UUID(), '-', '')) AS uuid")));

			while (($row = mysql_fetch_object($res)) != false)
			{
				$ids[] = $row->uuid;
			}
		}

		return array_pop($ids);
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
