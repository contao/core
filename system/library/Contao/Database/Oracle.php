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
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \Database, \Database_Oracle_Statement;


/**
 * Class Database_Oracle
 *
 * Driver class for Oracle databases.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Database_Oracle extends Database
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
	 * @return \Database_Oracle_Statement
	 */
	protected function createStatement($resConnection, $blnDisableAutocommit)
	{
		return new Database_Oracle_Statement($resConnection, $blnDisableAutocommit);
	}
}
