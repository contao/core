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

use \Database_Result;


/**
 * Class Database_Oracle_Result
 *
 * Driver class for MySQL databases.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Database_Oracle_Result extends Database_Result
{

	/**
	 * Fetch the current row as enumerated array
	 * @return array
	 */
	protected function fetch_row()
	{
		return @oci_fetch_row($this->resResult);
	}


	/**
	 * Fetch the current row as associative array
	 * @return array
	 */
	protected function fetch_assoc()
	{
		return @oci_fetch_assoc($this->resResult);
	}


	/**
	 * Return the number of rows of the current result
	 * @return integer
	 */
	protected function num_rows()
	{
		$this->fetchAllAssoc();
		$this->reset();

		return count($this->arrCache);
	}


	/**
	 * Return the number of fields of the current result
	 * @return integer
	 */
	protected function num_fields()
	{
		return @oci_num_fields($this->resResult);
	}


	/**
	 * Get the column information
	 * @param integer
	 * @return object
	 */
	protected function fetch_field($intOffset)
	{
		++$intOffset; // Oracle starts row counting at 1
		$objData = new \stdClass();

		$objData->name = @ocicolumnname($this->resResult, $intOffset);
		$objData->max_length = @ocicolumnsize($this->resResult, $intOffset);
		$objData->not_null = @ocicolumnisnull($this->resResult, $intOffset);
		$objData->type = @ocicolumntype($this->resResult, $intOffset);

		return $objData;
	}


	/**
	 * Free the current result
	 */
	public function free()
	{
		if (is_resource($this->resResult))
		{
			@oci_free_statement($this->resResult);
		}
	}
}
