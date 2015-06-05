<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao\Database\Mysql;


/**
 * MySQL-specific database result class
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Result extends \Database\Result
{

	/**
	 * Fetch the current row as enumerated array
	 *
	 * @return array The row as array
	 */
	protected function fetch_row()
	{
		return mysql_fetch_row($this->resResult);
	}


	/**
	 * Fetch the current row as associative array
	 *
	 * @return array The row as associative array
	 */
	protected function fetch_assoc()
	{
		return mysql_fetch_assoc($this->resResult);
	}


	/**
	 * Return the number of rows in the result set
	 *
	 * @return integer The number of rows
	 */
	protected function num_rows()
	{
		return mysql_num_rows($this->resResult);
	}


	/**
	 * Return the number of fields of the result set
	 *
	 * @return integer The number of fields
	 */
	protected function num_fields()
	{
		return mysql_num_fields($this->resResult);
	}


	/**
	 * Get the column information and return it as array
	 *
	 * @param integer $intOffset The field offset
	 *
	 * @return array|object An array or object with the column information
	 */
	protected function fetch_field($intOffset)
	{
		return mysql_fetch_field($this->resResult, $intOffset);
	}


	/**
	 * Navigate to a certain row in the result set
	 *
	 * @param integer $intIndex The row index
	 *
	 * @throws \OutOfBoundsException If $intIndex is out of bounds
	 */
	protected function data_seek($intIndex)
	{
		if ($intIndex < 0)
		{
			throw new \OutOfBoundsException("Invalid index $intIndex (must be >= 0)");
		}

		$intTotal = $this->num_rows();

		if ($intTotal <= 0)
		{
			return; // see #6319
		}

		if ($intIndex >= $intTotal)
		{
			throw new \OutOfBoundsException("Invalid index $intIndex (only $intTotal rows in the result set)");
		}

		mysql_data_seek($this->resResult, $intIndex);
	}


	/**
	 * Free the result
	 */
	public function free()
	{
		if (is_resource($this->resResult))
		{
			mysql_free_result($this->resResult);
		}
	}
}

// Backwards compatibility
class_alias('Contao\\Database\\Mysql\\Result', 'Database_Result');
