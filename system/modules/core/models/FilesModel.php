<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Reads and writes file entries
 * 
 * The files themselves reside in the files directory. This class only handles
 * the corresponding database entries (database aided file system).
 * 
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
class FilesModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_files';


	/**
	 * Find multiple files by their IDs
	 * 
	 * @param array $arrIds     An array of file IDs
	 * @param array $arrOptions An optional options array
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no files
	 */
	public static function findMultipleByIds($arrIds, array $arrOptions=array())
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$t = static::$strTable;

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = \Database::getInstance()->findInSet("$t.id", $arrIds);
		}

		return static::findBy(array("$t.id IN(" . implode(',', array_map('intval', $arrIds)) . ")"), null, $arrOptions);
	}


	/**
	 * Find multiple files with the same base path
	 * 
	 * @param string $strPath    The base path
	 * @param array  $arrOptions An optional options array
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no matching files
	 */
	public static function findMultipleByBasepath($strPath, array $arrOptions=array())
	{
		$t = static::$strTable;
		return static::findBy(array("$t.path LIKE ?"), $strPath . '%', $arrOptions);
	}


	/**
	 * Find multiple files by ID and a list of extensions
	 * 
	 * @param array $arrIds        An array of file IDs
	 * @param array $arrExtensions An array of file extensions
	 * @param array $arrOptions    An optional options array
	 * 
	 * @return \Model\Collection|null A collection of models or null of there are no matching files
	 */
	public static function findMultipleByIdsAndExtensions($arrIds, $arrExtensions, array $arrOptions=array())
	{
		if (!is_array($arrIds) || empty($arrIds) || !is_array($arrExtensions) || empty($arrExtensions))
		{
			return null;
		}

		foreach ($arrExtensions as $k=>$v)
		{
			if (!preg_match('/^[a-z0-9]{2,5}$/i', $v))
			{
				unset($arrExtensions[$k]);
			}
		}

		$t = static::$strTable;

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = \Database::getInstance()->findInSet("$t.id", $arrIds);
		}

		return static::findBy(array("$t.id IN(" . implode(',', array_map('intval', $arrIds)) . ") AND $t.extension IN('" . implode("','", $arrExtensions) . "')"), null, $arrOptions);
	}
}
