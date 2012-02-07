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
 * Class Model_QueryBuilder
 *
 * Provide methods to built queries and join tables if neccessary.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Model
 */
class Model_QueryBuilder
{

	/**
	 * Build a query based on the given options
	 * @param array
	 * @return string
	 */
	public static function find($arrOptions)
	{
		$objBase = new \DcaExtractor($arrOptions['table']);

		if (!$objBase->hasRelations())
		{
			$strQuery = "SELECT * FROM " . $arrOptions['table'];
		}
		else
		{
			$arrJoins = array();
			$arrFields = array($arrOptions['table'] . ".*");

			foreach ($objBase->getRelations() as $strKey=>$arrConfig)
			{
				// Automatically join the single-relation records
				if ($arrConfig['load'] == 'eager' || $arrOptions['eager'])
				{
					if ($arrConfig['type'] == 'hasOne' || $arrConfig['type'] == 'belongsTo')
					{
						$objRelated = new \DcaExtractor($arrConfig['table']);

						foreach (array_keys($objRelated->getFields()) as $strField)
						{
							$arrFields[] = $arrConfig['table'] . '.' . $strField . ' AS ' . $strKey . '__' . $strField;
						}

						$arrJoins[] = " LEFT JOIN " . $arrConfig['table'] . " ON " . $arrOptions['table'] . "." . $strKey . "=" . $arrConfig['table'] . ".id";
					}
				}
			}

			// Generate the query
			$strQuery = "SELECT " . implode(', ', $arrFields) . " FROM " . $arrOptions['table'] . implode("", $arrJoins);
		}

		// Where condition
		if ($arrOptions['column'] !== null)
		{
			$strQuery .= " WHERE " . (is_array($arrOptions['column']) ? implode(" AND ", $arrOptions['column']) : $arrOptions['table'] . '.' . $arrOptions['column'] . "=?");
		}

		// Order by
		if ($arrOptions['order'] !== null)
		{
			$strQuery .= " ORDER BY " . $arrOptions['order'];
		}

		return $strQuery;
	}


	/**
	 * Build a query based on the given options to count the number of records
	 * @param array
	 * @return string
	 */
	public static function count($arrOptions)
	{
		$strQuery = "SELECT COUNT(*) AS count FROM " . $arrOptions['table'];

		if ($arrOptions['column'] !== null)
		{
			$strQuery .= " WHERE " . (is_array($arrOptions['column']) ? implode(" AND ", $arrOptions['column']) : $arrOptions['table'] . '.' . $arrOptions['column'] . "=?");
		}

		return $strQuery;
	}
}

?>