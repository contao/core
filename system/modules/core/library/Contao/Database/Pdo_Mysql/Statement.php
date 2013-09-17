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

namespace Contao\Database\Pdo_Mysql;


/**
 * MySQLi-specific database statement class
 *
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
class Statement extends \Database\Statement
{

	/**
	 * Parameters
	 * @var array
	 */
	protected $arrParams = array();


	/**
	 * Prepare a query string so the following functions can handle it
	 *
	 * @param string $strQuery The query string
	 *
	 * @return \Database\Statement The statement object
	 *
	 * @throws \Exception If $strQuery is empty
	 */
	public function prepare($strQuery)
	{
		if ($strQuery == '')
		{
			throw new \Exception('Empty query string');
		}

		$this->resResult = null;
		$this->strQuery = $this->prepare_query($strQuery);

		// Auto-generate the SET/VALUES subpart
		if (strncasecmp($this->strQuery, 'INSERT', 6) === 0 || strncasecmp($this->strQuery, 'UPDATE', 6) === 0)
		{
			$this->strQuery = str_replace('%s', '%p', $this->strQuery);
		}

		return $this;
	}


	/**
	 * Execute the query and return the result object
	 *
	 * @return \Database\Result The result object
	 */
	public function execute()
	{
		$arrParams = func_get_args();

		if (is_array($arrParams[0]))
		{
			$arrParams = array_values($arrParams[0]);
		}

		$this->arrParams = $arrParams;
		return $this->query();
	}


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
		return $this->resConnection->quote($strString);
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
		$objStmt = $this->resConnection->prepare($this->strQuery, array
		(
			\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL
		));

		$objStmt->execute($this->arrParams);
		return $objStmt;
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
	 * Return the number of affected rows
	 *
	 * @return integer The number of affected rows
	 */
	protected function affected_rows()
	{
		return $this->resConnection->rowCount();
	}


	/**
	 * Return the last insert ID
	 *
	 * @return integer The last insert ID
	 */
	protected function insert_id()
	{
		return $this->resConnection->lastInsertId();
	}


	/**
	 * Explain the current query
	 *
	 * @return array The information array
	 */
	protected function explain_query()
	{
		return $this->resConnection->query('EXPLAIN ' . $this->strQuery)->fetch(\PDO::FETCH_ASSOC);
	}


	/**
	 * Create a Database\Result object
	 *
	 * @param resource $resResult The database result
	 * @param string   $strQuery  The query string
	 *
	 * @return \Database\Pdo\Result The result object
	 */
	protected function createResult($resResult, $strQuery)
	{
		return new \Database\Pdo_Mysql\Result($resResult, $strQuery);
	}
}

// Backwards compatibility
class_alias('Contao\\Database\\Pdo_Mysql\\Statement', 'Database_Statement');
