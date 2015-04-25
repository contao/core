<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao\Model;


/**
 * The class reads the relation meta data from the DCA and creates the necessary
 * JOIN queries to retrieve an object from the database.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class QueryBuilder
{

	/**
	 * Build a query based on the given options
	 *
	 * @param array $arrOptions The options array
	 *
	 * @return string The query string
	 */
	public static function find(array $arrOptions)
	{
		$objBase = \DcaExtractor::getInstance($arrOptions['table']);

		if (!$objBase->hasRelations())
		{
			$strQuery = "SELECT * FROM " . $arrOptions['table'];
		}
		else
		{
			$arrJoins = array();
			$arrFields = array($arrOptions['table'] . ".*");
			$intCount = 0;

			foreach ($objBase->getRelations() as $strKey=>$arrConfig)
			{
				// Automatically join the single-relation records
				if ($arrConfig['load'] == 'eager' || $arrOptions['eager'])
				{
					if ($arrConfig['type'] == 'hasOne' || $arrConfig['type'] == 'belongsTo')
					{
						++$intCount;
						$objRelated = \DcaExtractor::getInstance($arrConfig['table']);

						foreach (array_keys($objRelated->getFields()) as $strField)
						{
							$arrFields[] = 'j' . $intCount . '.' . $strField . ' AS ' . $strKey . '__' . $strField;
						}

						$arrJoins[] = " LEFT JOIN " . $arrConfig['table'] . " j$intCount ON " . $arrOptions['table'] . "." . $strKey . "=j$intCount." . $arrConfig['field'];
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

		// Group by
		if ($arrOptions['group'] !== null)
		{
			$strQuery .= " GROUP BY " . $arrOptions['group'];
		}

		// Having (see #6446)
		if ($arrOptions['having'] !== null)
		{
			$strQuery .= " HAVING " . $arrOptions['having'];
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
	 *
	 * @param array $arrOptions The options array
	 *
	 * @return string The query string
	 */
	public static function count(array $arrOptions)
	{
		$strQuery = "SELECT COUNT(*) AS count FROM " . $arrOptions['table'];

		if ($arrOptions['column'] !== null)
		{
			$strQuery .= " WHERE " . (is_array($arrOptions['column']) ? implode(" AND ", $arrOptions['column']) : $arrOptions['table'] . '.' . $arrOptions['column'] . "=?");
		}

		return $strQuery;
	}
}
