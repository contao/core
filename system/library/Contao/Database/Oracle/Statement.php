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

use \Database_Statement, \Database_Oracle_Result;


/**
 * Class Database_Oracle_Statement
 *
 * Driver class for MySQL databases.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Database_Oracle_Statement extends Database_Statement
{

	/**
	 * Current statement resource
	 * @var resource
	 */
	protected $resStatement;


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
	 */
	protected function limit_query($intRows, $intOffset)
	{
		// Return if the current statement is not a SELECT statement
		if (strncasecmp($this->strQuery, 'SELECT', 6) !== 0)
		{
			return;
		}

		// Get field names of the current query
		$resStmt = @oci_parse($this->resConnection, sprintf('SELECT * FROM (%s) WHERE rownum < 2', $this->strQuery));
		@oci_execute($resStmt);

		$arrFields = array_keys(@oci_fetch_assoc($resStmt));
		$strFields = implode(', ', $arrFields);

		// Oracle starts row counting at 1
		++$intRows;

		$this->strQuery = sprintf('SELECT %s FROM (SELECT %s, ROWNUM AS ROWN FROM (%s) WHERE ROWNUM < %d) WHERE ROWN > %d',
								$strFields,
								$strFields,
								$this->strQuery,
								intval($intOffset + $intRows),
								intval($intOffset));
	}


	/**
	 * Execute the current query
	 * @return resource
	 */
	protected function execute_query()
	{
		if (($this->resStatement = @oci_parse($this->resConnection, $this->strQuery)) == false)
		{
			$this->resStatement = $this->resConnection;
			return false;
		}

		$strExecutionMode = $this->blnDisableAutocommit ? OCI_DEFAULT : OCI_COMMIT_ON_SUCCESS;

		if (!@oci_execute($this->resStatement, $strExecutionMode))
		{
			return false;
		}

		return $this->resStatement;
	}


	/**
	 * Return the last error message
	 * @return string
	 */
	protected function get_error()
	{
		$arrError = is_resource($this->resStatement) ? oci_error($this->resStatement) : oci_error();
		return $arrError['message'];
	}


	/**
	 * Return the number of affected rows
	 * @return integer
	 */
	protected function affected_rows()
	{
		return @oci_num_rows($this->resResult);
	}


	/**
	 * Return the last insert ID
	 * @return boolean
	 */
	protected function insert_id()
	{
		return false;
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
	 * @return \Database_Oracle_Result
	 */
	protected function createResult($resResult, $strQuery)
	{
		return new Database_Oracle_Result($resResult, $strQuery);
	}
}
