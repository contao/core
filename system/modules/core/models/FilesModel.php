<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
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
	 * Find a file by its UUID
	 *
	 * @param array $strUuid    The UUID string
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model|null A model or null if there is no file
	 */
	public static function findByUuid($strUuid, array $arrOptions=array())
	{
		$t = static::$strTable;
		return static::findOneBy(array("HEX($t.uuid)=?"), bin2hex($strUuid), $arrOptions);
	}


	/**
	 * Find multiple files by their UUIDs
	 *
	 * @param array $arrUuids   An array of file UUIDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no files
	 */
	public static function findMultipleByUuids($arrUuids, array $arrOptions=array())
	{
		if (!is_array($arrUuids) || empty($arrUuids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrUuids = array_map('bin2hex', $arrUuids);

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = \Database::getInstance()->findInSet("HEX($t.uuid)", $arrUuids);
		}

		return static::findBy(array("HEX($t.uuid) IN('" . implode("','", $arrUuids) . "')"), null, $arrOptions);
	}


	/**
	 * Find multiple files by their paths
	 *
	 * @param array $arrPaths   An array of file paths
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no files
	 */
	public static function findMultipleByPaths($arrPaths, array $arrOptions=array())
	{
		if (!is_array($arrPaths) || empty($arrPaths))
		{
			return null;
		}

		$t = static::$strTable;

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = \Database::getInstance()->findInSet("$t.path", $arrPaths);
		}

		return static::findBy(array("$t.path IN(" . implode(',', array_fill(0, count($arrPaths), '?')) . ")"), $arrPaths, $arrOptions);
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
	 * Find multiple files by UUID and a list of extensions
	 *
	 * @param array $arrUuids      An array of file UUIDs
	 * @param array $arrExtensions An array of file extensions
	 * @param array $arrOptions    An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null of there are no matching files
	 */
	public static function findMultipleByUuidsAndExtensions($arrUuids, $arrExtensions, array $arrOptions=array())
	{
		if (!is_array($arrUuids) || empty($arrUuids) || !is_array($arrExtensions) || empty($arrExtensions))
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
		$arrUuids = array_map('bin2hex', $arrUuids);

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = \Database::getInstance()->findInSet("HEX($t.uuid)", $arrUuids);
		}

		return static::findBy(array("HEX($t.uuid) IN('" . implode("','", $arrUuids) . "') AND $t.extension IN('" . implode("','", $arrExtensions) . "')"), null, $arrOptions);
	}


	/**
	 * Find all files in a folder
	 *
	 * @param string $strPath    The folder path
	 * @param array  $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no matching files
	 */
	public static function findMultipleFilesByFolder($strPath, array $arrOptions=array())
	{
		$t = static::$strTable;
		$strPath = str_replace(array('%', '_'), array('\\%', '\\_'), $strPath);

		return static::findBy(array("$t.type='file' AND $t.path LIKE ? AND $t.path NOT LIKE ?"), array($strPath.'/%', $strPath.'/%/%'), $arrOptions);
	}


	/**
	 * Do not reload the data upon insert
	 *
	 * @param integer $intType The query type (Model::INSERT or Model::UPDATE)
	 */
	protected function postSave($intType) {}
}
