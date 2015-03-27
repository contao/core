<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao\Database;


/**
 * Create and execute queries
 *
 * The class creates the database queries replacing the wildcards and escaping
 * the values. It then executes the query and returns a result object.
 *
 * Usage:
 *
 *     $db = Database::getInstance();
 *     $stmt = $db->prepare("SELECT * FROM tl_member WHERE city=?");
 *     $stmt->limit(10);
 *     $res = $stmt->execute('London');
 *
 * @property string  $query        The query string
 * @property string  $error        The last error message
 * @property integer $affectedRows The number of affected rows
 * @property integer $insertId     The last insert ID
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
abstract class Statement
{

	/**
	 * Connection ID
	 * @var resource
	 */
	protected $resConnection;

	/**
	 * Database result
	 * @var resource
	 */
	protected $resResult;

	/**
	 * Query string
	 * @var string
	 */
	protected $strQuery;

	/**
	 * Query start
	 * @var int
	 */
	protected $intQueryStart;

	/**
	 * Query end
	 * @var int
	 */
	protected $intQueryEnd;

	/**
	 * Autocommit indicator
	 * @var boolean
	 */
	protected $blnDisableAutocommit = false;

	/**
	 * Result cache
	 * @var array
	 */
	protected static $arrCache = array();


	/**
	 * Validate the connection resource and store the query string
	 *
	 * @param resource $resConnection        The connection resource
	 * @param boolean  $blnDisableAutocommit Optionally disable autocommitting
	 *
	 * @throws \Exception If $resConnection is not a valid resource
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
	 * Return an object property
	 *
	 * @param string $strKey The property name
	 *
	 * @return mixed|null The property value or null
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
		}

		return null;
	}


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
		$this->strQuery = $this->prepare_query(trim($strQuery));

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

		$this->strQuery = implode('', $arrChunks);

		return $this;
	}


	/**
	 * Autogenerate the SET/VALUES subpart of a query from an associative array
	 *
	 * Usage:
	 *
	 *     $set = array(
	 *         'firstname' => 'Leo',
	 *         'lastname'  => 'Feyer'
	 *     );
	 *     $stmt->prepare("UPDATE tl_member %s")->set($set);
	 *
	 * @param array $arrParams The associative array
	 *
	 * @return \Database\Statement The statement object
	 */
	public function set($arrParams)
	{
		$strQuery = '';
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
	 * Handle limit and offset
	 *
	 * @param integer $intRows   The maximum number of rows
	 * @param integer $intOffset The number of rows to skip
	 *
	 * @return \Database\Statement The statement object
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
	 * Execute the query and return the result object
	 *
	 * @return \Database\Result|object The result object
	 */
	public function execute()
	{
		$arrParams = func_get_args();

		if (!empty($arrParams) && is_array($arrParams[0]))
		{
			$arrParams = array_values($arrParams[0]);
		}

		$this->replaceWildcards($arrParams);

		return $this->query();
	}


	/**
	 * Directly send a query string to the database
	 *
	 * @param string $strQuery The query string
	 *
	 * @return \Database\Result|\Database\Statement The result object or the statement object if there is no result set
	 *
	 * @throws \Exception If the query cannot be executed
	 */
	public function query($strQuery='')
	{
		if (!empty($strQuery))
		{
			$this->strQuery = trim($strQuery);
		}

		// Make sure there is a query string
		if ($this->strQuery == '')
		{
			throw new \Exception('Empty query string');
		}

		$this->intQueryStart = microtime(true);

		// Execute the query
		if (($this->resResult = $this->execute_query()) == false)
		{
			throw new \Exception(sprintf('Query error: %s (%s)', $this->error, $this->strQuery));
		}

		$this->intQueryEnd = microtime(true);

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
	 * Replace the wildcards in the query string
	 *
	 * @param array $arrValues The values array
	 *
	 * @throws \Exception If $arrValues has too few values to replace the wildcards in the query string
	 */
	protected function replaceWildcards($arrValues)
	{
		$arrValues = $this->escapeParams($arrValues);
		$this->strQuery = preg_replace('/(?<!%)%([^bcdufosxX%])/', '%%$1', $this->strQuery);

		// Replace wildcards
		if (($this->strQuery = @vsprintf($this->strQuery, $arrValues)) == false)
		{
			throw new \Exception('Too few arguments to build the query string');
		}
	}


	/**
	 * Escape the values and serialize objects and arrays
	 *
	 * @param array $arrValues The values array
	 *
	 * @return array The array with the escaped values
	 */
	protected function escapeParams($arrValues)
	{
		foreach ($arrValues as $k=>$v)
		{
			switch (gettype($v))
			{
				case 'string':
					$arrValues[$k] = $this->string_escape($v);
					break;

				case 'boolean':
					$arrValues[$k] = ($v === true) ? 1 : 0;
					break;

				case 'object':
					$arrValues[$k] = $this->string_escape(serialize($v));
					break;

				case 'array':
					$arrValues[$k] = $this->string_escape(serialize($v));
					break;

				default:
					$arrValues[$k] = ($v === null) ? 'NULL' : $v;
					break;
			}
		}

		return $arrValues;
	}


	/**
	 * Debug a query
	 *
	 * @param \Database\Result $objResult An optional result object
	 */
	protected function debugQuery($objResult=null)
	{
		if (!\Config::get('debugMode'))
		{
			return;
		}

		$arrData['query'] = specialchars($this->strQuery);

		if ($objResult === null || strncasecmp($this->strQuery, 'SELECT', 6) !== 0)
		{
			if (strncasecmp($this->strQuery, 'SHOW', 4) === 0)
			{
				$arrData['return_count'] = $this->affectedRows;
				$arrData['returned'] = sprintf('%s row(s) returned', $this->affectedRows);
			}
			else
			{
				$arrData['affected_count'] = $this->affectedRows;
				$arrData['affected'] = sprintf('%d row(s) affected', $this->affectedRows);
			}
		}
		else
		{
			if (($arrExplain = $this->explain()) != false)
			{
				$arrData['explain'] = $arrExplain;
			}

			$arrData['return_count'] = $objResult->numRows;
			$arrData['returned'] = sprintf('%s row(s) returned', $objResult->numRows);
		}

		$arrData['duration'] = \System::getFormattedNumber((($this->intQueryEnd - $this->intQueryStart) * 1000), 3) . ' ms';
		$GLOBALS['TL_DEBUG']['database_queries'][] = $arrData;
	}


	/**
	 * Explain the current query
	 *
	 * @return string The explanation string
	 */
	public function explain()
	{
		return $this->explain_query();
	}


	/**
	 * Prepare a query string and return it
	 *
	 * @param string $strQuery The query string
	 *
	 * @return string The modified query string
	 */
	abstract protected function prepare_query($strQuery);


	/**
	 * Escape a string
	 *
	 * @param string $strString The unescaped string
	 *
	 * @return string The escaped string
	 */
	abstract protected function string_escape($strString);


	/**
	 * Add limit and offset to the query string
	 *
	 * @param integer $intRows   The maximum number of rows
	 * @param integer $intOffset The number of rows to skip
	 */
	abstract protected function limit_query($intRows, $intOffset);


	/**
	 * Execute the query
	 *
	 * @return resource The result resource
	 */
	abstract protected function execute_query();


	/**
	 * Return the last error message
	 *
	 * @return string The error message
	 */
	abstract protected function get_error();


	/**
	 * Return the last insert ID
	 *
	 * @return integer The last insert ID
	 */
	abstract protected function affected_rows();


	/**
	 * Return the last insert ID
	 *
	 * @return integer The last insert ID
	 */
	abstract protected function insert_id();


	/**
	 * Explain the current query
	 *
	 * @return array The information array
	 */
	abstract protected function explain_query();


	/**
	 * Create a Database\Result object
	 *
	 * @param resource $resResult The database result
	 * @param string   $strQuery  The query string
	 *
	 * @return \Database\Result The result object
	 */
	abstract protected function createResult($resResult, $strQuery);


	/**
	 * Bypass the cache and always execute the query
	 *
	 * @return \Database\Result The result object
	 *
	 * @deprecated Use Database\Statement::execute() instead
	 */
	public function executeUncached()
	{
		return call_user_func_array(array($this, 'execute'), func_get_args());
	}


	/**
	 * Always execute the query and add or replace an existing cache entry
	 *
	 * @return \Database\Result The result object
	 *
	 * @deprecated Use Database\Statement::execute() instead
	 */
	public function executeCached()
	{
		return call_user_func_array(array($this, 'execute'), func_get_args());
	}
}
