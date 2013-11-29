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
	 * Find a file by its primary key (backwards compatibility)
	 *
	 * @param mixed $varValue   The value
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model|null A model or null if there is no file
	 */
	public static function findByPk($varValue, array $arrOptions=array())
	{
		if (static::$strPk == 'id')
		{
			return static::findById($varValue, $arrOptions);
		}

		return parent::findByPk($varValue, $arrOptions);
	}


	/**
	 * Find a file by its ID or UUID (backwards compatibility)
	 *
	 * @param mixed $intId      The ID or UUID
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model|null A model or null if there is no file
	 */
	public static function findById($intId, array $arrOptions=array())
	{
		if (\Validator::isUuid($intId))
		{
			return static::findByUuid($intId, $arrOptions);
		}

		return static::findBy('id', $intId, $arrOptions);
	}


	/**
	 * Find multiple files by their IDs or UUIDs (backwards compatibility)
	 *
	 * @param array $arrIds     An array of IDs or UUIDs
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

		if (\Validator::isUuid(current($arrIds)))
		{
			return static::findMultipleByUuids($arrIds, $arrOptions);
		}

		return parent::findMultipleByIds($arrIds, $arrOptions);
	}


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

		// Convert UUIDs to binary
		if (strlen($strUuid) == 36)
		{
			$strUuid = \String::uuidToBin($strUuid);
		}

		return static::findOneBy(array("$t.uuid=UNHEX(?)"), bin2hex($strUuid), $arrOptions);
	}


	/**
	 * Find multiple files by their UUIDs
	 *
	 * @param array $arrUuids   An array of UUIDs
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

		foreach ($arrUuids as $k=>$v)
		{
			// Convert UUIDs to binary
			if (strlen($v) == 36)
			{
				$v = \String::uuidToBin($v);
			}

			$arrUuids[$k] = "UNHEX('" . bin2hex($v) . "')";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.uuid!=" . implode(", $t.uuid!=", $arrUuids);
		}

		return static::findBy(array("$t.uuid IN(" . implode(",", $arrUuids) . ")"), null, $arrOptions);
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

		foreach ($arrUuids as $k=>$v)
		{
			// Convert UUIDs to binary
			if (strlen($v) == 36)
			{
				$v = \String::uuidToBin($v);
			}

			$arrUuids[$k] = "UNHEX('" . bin2hex($v) . "')";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.uuid!=" . implode(", $t.uuid!=", $arrUuids);
		}

		return static::findBy(array("$t.uuid IN(" . implode(",", $arrUuids) . ") AND $t.extension IN('" . implode("','", $arrExtensions) . "')"), null, $arrOptions);
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
