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
 * Namespace
 */
namespace Contao;


/**
 * Class Database_Statement
 *
 * Provide methods to execute a database query.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
abstract class Database_Statement
{

	/**
	 * Connection ID
	 * @var resource
	 */
	protected $resConnection;

	/**
	 * Current result
	 * @var resource
	 */
	protected $resResult;

	/**
	 * Current query string
	 * @var string
	 */
	protected $strQuery;

	/**
	 * Disable autocommit
	 * @var boolean
	 */
	protected $blnDisableAutocommit = false;

	/**
	 * Cache array
	 * @var array
	 */
	protected static $arrCache = array();


	/**
	 * Validate the connection resource and store the query
	 * @param resource
	 * @param boolean
	 * @throws Exception
	 */
	public function __construct($resConnection, $blnDisableAutocommit=false)
	{
		if (!is_resource($resConnection) && !is_object($resConnection))
		{
			throw new \Exception('Invalid connection resource');
		}

		$this->resConnection = $resConnection;
		$this->blnDisableAutocommit = $blnDisableAutocommit;
	}


	/**
	 * Return a parameter
	 *
	 * Supported parameters:
	 * - query:        current query string
	 * - error:        last error message
	 * - affectedRows: number of affected rows
	 * - insertId:     last insert ID
	 *
	 * Throw an exception on requests for protected properties.
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'query':
				return $this->strQuery;
				break;

			case 'error':
				return $this->get_error();
				break;

			case 'affectedRows':
				return $this->affected_rows();
				break;

			case 'insertId':
				return $this->insert_id();
				break;

			default:
				return null;
				break;
		}
	}


	/**
	 * Prepare a statement
	 * @param string
	 * @return Database_Statement
	 * @throws Exception
	 */
	public function prepare($strQuery)
	{
		if (!strlen($strQuery))
		{
			throw \Exception('Empty query string');
		}

		$this->resResult = NULL;
		$this->strQuery = $this->prepare_query($strQuery);

		// Auto-generate the SET/VALUES subpart
		if (strncasecmp($this->strQuery, 'INSERT', 6) === 0 || strncasecmp($this->strQuery, 'UPDATE', 6) === 0)
		{
			$this->strQuery = str_replace('%s', '%p', $this->strQuery);
		}

		// Replace wildcards
		$arrChunks = preg_split("/('[^']*')/", $this->strQuery, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);

		foreach ($arrChunks as $k=>$v)
		{
			if (substr($v, 0, 1) == "'")
			{
				continue;
			}

			$arrChunks[$k] = str_replace('?', '%s', $v);
		}

		$this->strQuery = trim(implode('', $arrChunks));
		return $this;
	}


	/**
	 * Take an associative array and auto-generate the SET/VALUES subpart of a query
	 * 
	 * Usage example:
	 * $objStatement->prepare("UPDATE table %s")->set(array('id'=>'my_id'));
	 * will be transformed into "UPDATE table SET id='my_id'".
	 * @param array
	 * @return Database_Statement
	 */
	public function set($arrParams)
	{
		$arrParams = $this->escapeParams($arrParams);

		// INSERT
		if (strncasecmp($this->strQuery, 'INSERT', 6) === 0)
		{
			$strQuery = sprintf('(%s) VALUES (%s)',
								implode(', ', array_keys($arrParams)),
								str_replace('%', '%%', implode(', ', array_values($arrParams))));
		}

		// UPDATE
		elseif (strncasecmp($this->strQuery, 'UPDATE', 6) === 0)
		{
			$arrSet = array();

			foreach ($arrParams as $k=>$v)
			{
				$arrSet[] = $k . '=' . $v;
			}

			$strQuery = 'SET ' . str_replace('%', '%%', implode(', ', $arrSet));
		}

		$this->strQuery = str_replace('%p', $strQuery, $this->strQuery);
		return $this;
	}


	/**
	 * Limit the current result to a certain number of rows and take an offset value as second argument
	 * @param integer
	 * @param integer
	 * @return Database_Statement
	 */
	public function limit($intRows, $intOffset=0)
	{
		if ($intRows <= 0)
		{
			$intRows = 30;
		}

		if ($intOffset < 0)
		{
			$intOffset = 0;
		}

		$this->limit_query($intRows, $intOffset);
		return $this;
	}


	/**
	 * Escape the parameters and execute the current statement
	 * @return Database_Result
	 * @throws Exception
	 */
	public function execute()
	{
		$arrParams = func_get_args();

		if (is_array($arrParams[0]))
		{
			$arrParams = array_values($arrParams[0]);
		}

		$this->replaceWildcards($arrParams);
		$strKey = md5($this->strQuery);

		// Try to load the result from cache
		if (isset(self::$arrCache[$strKey]) && !self::$arrCache[$strKey]->isModified)
		{
			return self::$arrCache[$strKey]->reset();
		}

		$objResult = $this->query();

		// Cache the result objects
		if ($objResult instanceof Database_Result)
		{
			self::$arrCache[$strKey] = $objResult;
		}

		return $objResult;
	}


	/**
	 * Execute the current statement but do not cache the result
	 * @return Database_Result
	 * @throws Exception
	 */
	public function executeUncached()
	{
		$arrParams = func_get_args();

		if (is_array($arrParams[0]))
		{
			$arrParams = array_values($arrParams[0]);
		}

		$this->replaceWildcards($arrParams);
		return $this->query();
	}


	/**
	 * Execute a query and return the result object
	 * @param string
	 * @return Database_Result
	 * @throws Exception
	 */
	public function query($strQuery='')
	{
		if (!empty($strQuery))
		{
			$this->strQuery = $strQuery;
		}

		// Make sure there is a query string
		if ($this->strQuery == '')
		{
			throw new \Exception('Empty query string');
		}

		// Execute the query
		if (($this->resResult = $this->execute_query()) == false)
		{
			throw new \Exception(sprintf('Query error: %s (%s)', $this->error, $this->strQuery));
		}

		// No result set available
		if (!is_resource($this->resResult) && !is_object($this->resResult))
		{
			$this->debugQuery();
			return $this;
		}

		// Instantiate a result object
		$objResult = $this->createResult($this->resResult, $this->strQuery);
		$this->debugQuery($objResult);

		return $objResult;
	}


	/**
	 * Build the query string
	 * @param array
	 * @throws Exception
	 */
	protected function replaceWildcards($arrParams)
	{
		$arrParams = $this->escapeParams($arrParams);
		$this->strQuery = preg_replace('/(?<!%)%([^bcdufosxX%])/', '%%$1', $this->strQuery);

		// Replace wildcards
		if (($this->strQuery = @vsprintf($this->strQuery, $arrParams)) == false)
		{
			throw new \Exception('Too few arguments to build the query string');
		}
	}


	/**
	 * Escape the parameters and serialize objects and arrays
	 * @param array
	 * @return array
	 */
	protected function escapeParams($arrParams)
	{
		foreach ($arrParams as $k=>$v)
		{
			switch (gettype($v))
			{
				case 'string':
					$arrParams[$k] = $this->string_escape($v);
					break;

				case 'boolean':
					$arrParams[$k] = ($v === true) ? 1 : 0;
					break;

				case 'object':
					$arrParams[$k] = $this->string_escape(serialize($v));
					break;

				case 'array':
					$arrParams[$k] = $this->string_escape(serialize($v));
					break;

				default:
					$arrParams[$k] = ($v === NULL) ? 'NULL' : $v;
					break;
			}
		}

		return $arrParams;
	}


	/**
	 * Debug a query
	 * @param Database_Result
	 */
	protected function debugQuery($objResult=null)
	{
		if (!$GLOBALS['TL_CONFIG']['debugMode'])
		{
			return;
		}

		$arrData[] = $this->strQuery;

		if ($objResult !== null || strncmp(strtoupper($this->strQuery), 'SELECT', 6) !== 0)
		{
			$arrData[] = sprintf('%d rows affected', $this->affectedRows);
			$GLOBALS['TL_DEBUG']['db'][] = $arrData;

			return;
		}

		$arrData[] = sprintf('%s rows returned', $objResult->numRows);

		if (($arrExplain = $this->explain()) != false)
		{
			$arrData[] = $arrExplain;
		}

		$GLOBALS['TL_DEBUG']['db'][] = $arrData;
	}


	/**
	 * Explain the current query
	 * @return string
	 */
	public function explain()
	{
		return $this->explain_query();
	}


	/**
	 * Abstract database driver methods
	 */
	abstract protected function prepare_query($strQuery);
	abstract protected function string_escape($strString);
	abstract protected function limit_query($intOffset, $intRows);
	abstract protected function execute_query();
	abstract protected function get_error();
	abstract protected function affected_rows();
	abstract protected function insert_id();
	abstract protected function explain_query();
	abstract protected function createResult($resResult, $strQuery);
}

?>