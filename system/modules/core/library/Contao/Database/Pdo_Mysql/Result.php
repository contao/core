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
		return $this->resResult->fetch(\PDO::FETCH_NUM);
	}


	/**
	 * Fetch the current row as associative array
	 *
	 * @return array The row as associative array
	 */
	protected function fetch_assoc()
	{
		return $this->resResult->fetch(\PDO::FETCH_ASSOC);
	}


	/**
	 * Return the number of rows in the result set
	 *
	 * @return integer The number of rows
	 */
	protected function num_rows()
	{
		return $this->resResult->rowCount();
	}


	/**
	 * Return the number of fields of the result set
	 *
	 * @return integer The number of fields
	 */
	protected function num_fields()
	{
		return $this->resResult->columnCount();
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
		return $this->resResult->fetchColumn($intOffset);
	}


	/**
	 * Navigate to a certain row in the result set
	 *
	 * @param integer $intIndex The row index
	 */
	protected function data_seek($intIndex)
	{
		$this->resResult->fetch(\PDO::FETCH_ASSOC, \PDO::FETCH_ORI_ABS, $intIndex);
	}


	/**
	 * Free the result
	 */
	public function free()
	{
		$this->resResult = null;
	}
}

// Backwards compatibility
class_alias('Contao\\Database\\Pdo_Mysql\\Result', 'Database_Result');
