<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Library
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;

use \Database, \Database_Mssql_Statement;


/**
 * Class Database_Mssql
 *
 * Driver class for MSSQL databases.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Database_Mssql extends Database
{

	/**
	 * List tables query
	 * @var string
	 */
	protected $strListTables = "SELECT * FROM sysobjects WHERE type='U' ORDER BY name";

	/**
	 * List fields query
	 * @var string
	 */
	protected $strListFields = "SELECT c.name, t.name, c.length, c.isnullable, m.text, sign(c.status & 0x80), sign(ISNULL(r.status, 0) & 16), sign(ISNULL(k.keyno, 0)) FROM syscolumns c JOIN systypes t ON (t.xusertype=c.xusertype) JOIN sysobjects o on (o.id=c.id) LEFT OUTER JOIN sysconstraints r ON ((r.id=o.id) AND (r.colid=c.colid)) LEFT OUTER JOIN syscomments m ON (m.id = c.cdefault) LEFT OUTER JOIN sysobjects p ON ((p.parent_obj=o.id) AND (p.xtype='PK')) LEFT OUTER JOIN sysindexes i ON ((i.id=p.parent_obj) AND (i.name=p.name)) LEFT OUTER JOIN sysindexkeys k ON ((k.id=i.id) AND (k.indid=i.indid) AND (k.colid=c.colid)) WHERE o.name='%s'";


	/**
	 * Connect to the database server and select the database
	 */
	protected function connect()
	{
		$strHost = $GLOBALS['TL_CONFIG']['dbHost'];

		if ($GLOBALS['TL_CONFIG']['dbPort'])
		{
			$strHost .= ',' . $GLOBALS['TL_CONFIG']['dbPort'];
		}

		if ($GLOBALS['TL_CONFIG']['dbPconnect'])
		{
			$this->resConnection = @mssql_pconnect($strHost, $GLOBALS['TL_CONFIG']['dbUser'], $GLOBALS['TL_CONFIG']['dbPass'], $GLOBALS['TL_CONFIG']['dbCharset']);
		}
		else
		{
			$this->resConnection = @mssql_connect($strHost, $GLOBALS['TL_CONFIG']['dbUser'], $GLOBALS['TL_CONFIG']['dbPass'], $GLOBALS['TL_CONFIG']['dbCharset']);
		}

		if (is_resource($this->resConnection))
		{
			@mssql_select_db($GLOBALS['TL_CONFIG']['dbDatabase'], $this->resConnection);
		}
	}


	/**
	 * Disconnect from the database
	 */
	protected function disconnect()
	{
		@mssql_close($this->resConnection);
	}


	/**
	 * Return the last error message
	 * @return string
	 */
	protected function get_error()
	{
		return mssql_get_last_message();
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
	 * @todo This function is not tested yet, nor is the list tables and list fields statement!
	 */
	protected function list_fields($strTable)
	{
		$arrReturn = array();
		$arrFields = $this->query(sprintf($this->strListFields, $strTable))->fetchAllAssoc();

		foreach ($arrFields as $k=>$v)
		{
			$arrReturn[$k]['name'] = $v['name'];
			$arrReturn[$k]['length'] = $v['length'];
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
		return @mssql_select_db($strDatabase, $this->resConnection);
	}


	/**
	 * Begin a transaction
	 */
	protected function begin_transaction()
	{
		@mssql_query("BEGIN TRAN", $this->resConnection);
	}


	/**
	 * Commit a transaction
	 */
	protected function commit_transaction()
	{
		@mssql_query("COMMIT TRAN", $this->resConnection);
	}


	/**
	 * Rollback a transaction
	 */
	protected function rollback_transaction()
	{
		@mssql_query("ROLLBACK TRAN", $this->resConnection);
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
	 * @return \Database_Mssql_Statement
	 */
	protected function createStatement($resConnection, $blnDisableAutocommit)
	{
		return new Database_Mssql_Statement($resConnection, $blnDisableAutocommit);
	}
}
