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
use \Database_Statement, \Database_Mssql_Result;


/**
 * Class Database_Mssql_Statement
 *
 * Driver class for MSSQL databases.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Driver
 */
class Database_Mssql_Statement extends Database_Statement
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
		return "'" . str_replace("'", "''", $strString) . "'";
	}


	/**
	 * Limit the current query
	 * @param integer
	 * @param integer
	 * @return void
	 */
	protected function limit_query($intRows, $intOffset)
	{
		$this->strQuery .= preg_replace('/(^\s*SELECT\s+(DISTINCT|DISTINCTROW)?)/i', '\\1 TOP ' . intval($intRows + $intOffset), $this->strQuery);
	}


	/**
	 * Execute the current query
	 * @return resource
	 */
	protected function execute_query()
	{
		return @mssql_query($this->strQuery, $this->resConnection);
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
	 * Return the number of affected rows
	 * @return integer
	 */
	protected function affected_rows()
	{
		return @mssql_rows_affected($this->resConnection);
	}


	/**
	 * Return the last insert ID
	 * @return integer
	 */
	protected function insert_id()
	{
		return @mssql_query('SELECT @@IDENTITY', $this->resConnection);
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
	 * @return \Database_Mssql_Result
	 */
	protected function createResult($resResult, $strQuery)
	{
		return new Database_Mssql_Result($resResult, $strQuery);
	}
}
