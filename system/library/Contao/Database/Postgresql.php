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
use \Database, \Database_Postgresql_Statement;


/**
 * Class Database_Postgresql
 *
 * Driver class for PostgreSQL databases.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Driver
 */
class Database_Postgresql extends Database
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
	 * @return \Database_Postgresql_Statement
	 */
	protected function createStatement($resConnection, $blnDisableAutocommit)
	{
		return new Database_Postgresql_Statement($resConnection, $blnDisableAutocommit);
	}
}
