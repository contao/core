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

use \Database_Statement, \Database_Mysqli_Result;


/**
 * Class Database_Mysqli_Statement
 *
 * Driver class for MySQLi databases.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Database_Mysqli_Statement extends Database_Statement
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
		return "'" . $this->resConnection->real_escape_string($strString) . "'";
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
		return @$this->resConnection->query($this->strQuery);
	}


	/**
	 * Return the last error message
	 * @return string
	 */
	protected function get_error()
	{
		return @$this->resConnection->error;
	}


	/**
	 * Return the number of affected rows
	 * @return integer
	 */
	protected function affected_rows()
	{
		return @$this->resConnection->affected_rows;
	}


	/**
	 * Return the last insert ID
	 * @return integer
	 */
	protected function insert_id()
	{
		return @$this->resConnection->insert_id;
	}


	/**
	 * Explain the current query
	 * @return array
	 */
	protected function explain_query()
	{
		return @$this->resConnection->query('EXPLAIN ' . $this->strQuery)->fetch_assoc();
	}

	/**
	 * Create a Database_Result object
	 * @param resource
	 * @param string
	 * @return \Database_Mysqli_Result
	 */
	protected function createResult($resResult, $strQuery)
	{
		return new Database_Mysqli_Result($resResult, $strQuery);
	}
}
