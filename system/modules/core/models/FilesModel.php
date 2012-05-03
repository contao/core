<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Core
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \Database, \Model;


/**
 * Class FilesModel
 *
 * Provide methods to find and save files.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Core
 */
class FilesModel extends Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_files';


	/**
	 * Find multiple files by ID
	 * @param array
	 * @return \Model_Collection|null
	 */
	public static function findMultipleByIds($arrIds)
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$t = static::$strTable;
		return static::findBy(array("$t.id IN(" . implode(',', array_map('intval', $arrIds)) . ")"), null, array('order'=>Database::getInstance()->findInSet("$t.id", $arrIds)));
	}


	/**
	 * Find multiple files with the same base path
	 * @param array
	 * @return \Model_Collection|null
	 */
	public static function findMultipleByBasepath($strPath)
	{
		$t = static::$strTable;
		return static::findBy(array("$t.path LIKE ?"), $strPath . '%');
	}


	/**
	 * Find multiple files by ID and a list of extensions
	 * @param array
	 * @param array
	 * @return \Model_Collection|null
	 */
	public static function findMultipleByIdsAndExtensions($arrIds, $arrExtensions)
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
		return static::findBy(array("$t.id IN(" . implode(',', array_map('intval', $arrIds)) . ") AND $t.extension IN('" . implode("','", $arrExtensions) . "')"), null, array('order'=>Database::getInstance()->findInSet("$t.id", $arrIds)));
	}
}
