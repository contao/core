<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao\Database\Mysql;


/**
 * MySQL-specific database result class
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
		return @mysql_fetch_row($this->resResult);
	}


	/**
	 * Fetch the current row as associative array
	 * 
	 * @return array The row as associative array
	 */
	protected function fetch_assoc()
	{
		return @mysql_fetch_assoc($this->resResult);
	}


	/**
	 * Return the number of rows in the result set
	 * 
	 * @return integer The number of rows
	 */
	protected function num_rows()
	{
		return @mysql_num_rows($this->resResult);
	}


	/**
	 * Return the number of fields of the result set
	 * 
	 * @return integer The number of fields
	 */
	protected function num_fields()
	{
		return @mysql_num_fields($this->resResult);
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
		return @mysql_fetch_field($this->resResult, $intOffset);
	}


	/**
	 * Free the result
	 */
	public function free()
	{
		if (is_resource($this->resResult))
		{
			@mysql_free_result($this->resResult);
		}
	}
}

// Backwards compatibility
class_alias('Contao\\Database\\Mysql\\Result', 'Database_Result');
