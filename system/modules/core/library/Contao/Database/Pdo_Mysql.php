<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao\Database;


/**
 * PDO_MySQL-specific database class
 *
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
class Pdo_Mysql extends \Database
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
	 *
	 * @throws \PDOException If the connection cannot be established
	 */
	protected function connect()
	{
		$dns = 'mysql:dbname=' . $this->arrConfig['dbDatabase'];

		if ($this->arrConfig['dbSocket'])
		{
			$dns .= ';unix_socket=' . $this->arrConfig['dbSocket'];
		}
		else
		{
			$dns .= ';host=' . $this->arrConfig['dbHost'] . ';port=' . $this->arrConfig['dbPort'];
		}

		$this->resConnection = new \PDO($dns, $this->arrConfig['dbUser'], $this->arrConfig['dbPass'], array
		(
			\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "UTF8"',
			\PDO::ATTR_PERSISTENT => $this->arrConfig['dbPconnect']
		));
	}


	/**
	 * Disconnect from the database
	 */
	protected function disconnect()
	{
		$this->resConnection = null;
	}


	/**
	 * Return the last error message
	 *
	 * @return string The error message
	 */
	protected function get_error()
	{
		$err = $this->resConnection->errorInfo();
		return array_pop($err);
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
			return "FIND_IN_SET(" . $strKey . ", " . $this->resConnection->quote($varSet) . ")";
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

			if (!empty($arrChunks[1]))
			{
				$arrChunks[1] = str_replace(array('(', ')'), array('', ''), $arrChunks[1]);
				$arrSubChunks = explode(',', $arrChunks[1]);

				$arrReturn[$k]['length'] = trim($arrSubChunks[0]);

				if (!empty($arrSubChunks[1]))
				{
					$arrReturn[$k]['precision'] = trim($arrSubChunks[1]);
				}
			}

			if (!empty($arrChunks[2]))
			{
				$arrReturn[$k]['attributes'] = trim($arrChunks[2]);
			}

			if (!empty($v['Key']))
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
		$this->resConnection->exec("USE $strDatabase");
	}


	/**
	 * Begin a transaction
	 */
	protected function begin_transaction()
	{
		$this->resConnection->beginTransaction();
	}


	/**
	 * Commit a transaction
	 */
	protected function commit_transaction()
	{
		$this->resConnection->commit();
	}


	/**
	 * Rollback a transaction
	 */
	protected function rollback_transaction()
	{
		$this->resConnection->rollBack();
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

		$this->resConnection->query("LOCK TABLES " . implode(', ', $arrLocks));
	}


	/**
	 * Unlock all tables
	 */
	protected function unlock_tables()
	{
		$this->resConnection->query("UNLOCK TABLES");
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
		$objStatus = $this->resConnection->query("SHOW TABLE STATUS LIKE '" . $strTable . "'")
										 ->fetchObject();

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
		$objStatus = $this->resConnection->query("SHOW TABLE STATUS LIKE '" . $strTable . "'")
										 ->fetchObject();

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
			$res = $this->resConnection->query(implode(' UNION ALL ', array_fill(0, 10, "SELECT UNHEX(REPLACE(UUID(), '-', '')) AS uuid")));

			while (($row = $res->fetchObject()) != false)
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
	 * @return \Database\Pdo\Statement The Database\Statement object
	 */
	protected function createStatement($resConnection, $blnDisableAutocommit)
	{
		return new \Database\Pdo_Mysql\Statement($resConnection, $blnDisableAutocommit);
	}
}
