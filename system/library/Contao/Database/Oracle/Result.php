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
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class Database_Oracle_Result
 *
 * Driver class for MySQL databases.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Driver
 */
class Database_Oracle_Result extends \Database_Result
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
	 * @return void
	 */
	public function free()
	{
		if (is_resource($this->resResult))
		{
			@oci_free_statement($this->resResult);
		}
	}
}
