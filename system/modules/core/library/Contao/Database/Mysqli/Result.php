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

namespace Contao\Database\Mysqli;


/**
 * MySQLi-specific database result class
 *
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
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
		return $this->resResult->fetch_row();
	}


	/**
	 * Fetch the current row as associative array
	 *
	 * @return array The row as associative array
	 */
	protected function fetch_assoc()
	{
		return $this->resResult->fetch_assoc();
	}


	/**
	 * Return the number of rows in the result set
	 *
	 * @return integer The number of rows
	 */
	protected function num_rows()
	{
		return $this->resResult->num_rows;
	}


	/**
	 * Return the number of fields of the result set
	 *
	 * @return integer The number of fields
	 */
	protected function num_fields()
	{
		return $this->resResult->field_count;
	}


	/**
	 * Get the column information and return it as array
	 *
	 * @param integer $intOffset The field offset
	 *
	 * @return array An array with the column information
	 */
	protected function fetch_field($intOffset)
	{
		return $this->resResult->fetch_field_direct($intOffset);
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

		$this->resResult->data_seek($intIndex);
	}


	/**
	 * Free the result
	 */
	public function free()
	{
		if (is_object($this->resResult))
		{
			$this->resResult->free();
		}
	}
}

// Backwards compatibility
class_alias('Contao\\Database\\Mysqli\\Result', 'Database_Result');
