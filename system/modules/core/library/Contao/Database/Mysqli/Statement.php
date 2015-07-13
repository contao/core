<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao\Database\Mysqli;


/**
 * MySQLi-specific database statement class
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Statement extends \Database\Statement
{

	/**
	 * Connection ID
	 * @var \mysqli
	 */
	protected $resConnection;


	/**
	 * Prepare a query string and return it
	 *
	 * @param string $strQuery The query string
	 *
	 * @return string The modified query string
	 */
	protected function prepare_query($strQuery)
	{
		return $strQuery;
	}


	/**
	 * Escape a string
	 *
	 * @param string $strString The unescaped string
	 *
	 * @return string The escaped string
	 */
	protected function string_escape($strString)
	{
		return "'" . $this->resConnection->real_escape_string($strString) . "'";
	}


	/**
	 * Add limit and offset to the query string
	 *
	 * @param integer $intRows   The maximum number of rows
	 * @param integer $intOffset The number of rows to skip
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
	 * Execute the query
	 *
	 * @return resource The result resource
	 */
	protected function execute_query()
	{
		return $this->resConnection->query($this->strQuery);
	}


	/**
	 * Return the last error message
	 *
	 * @return string The error message
	 */
	protected function get_error()
	{
		return $this->resConnection->error;
	}


	/**
	 * Return the number of affected rows
	 *
	 * @return integer The number of affected rows
	 */
	protected function affected_rows()
	{
		return $this->resConnection->affected_rows;
	}


	/**
	 * Return the last insert ID
	 *
	 * @return integer The last insert ID
	 */
	protected function insert_id()
	{
		return $this->resConnection->insert_id;
	}


	/**
	 * Explain the current query
	 *
	 * @return array The information array
	 */
	protected function explain_query()
	{
		return $this->resConnection->query('EXPLAIN ' . $this->strQuery)->fetch_assoc();
	}


	/**
	 * Create a Database\Result object
	 *
	 * @param resource $resResult The database result
	 * @param string   $strQuery  The query string
	 *
	 * @return \Database\Mysqli\Result The result object
	 */
	protected function createResult($resResult, $strQuery)
	{
		return new \Database\Mysqli\Result($resResult, $strQuery);
	}
}

// Backwards compatibility
class_alias('Contao\\Database\\Mysqli\\Statement', 'Database_Statement');
