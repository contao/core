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
 * @package    Backend
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class PageModel
 *
 * Provide methods to find and save modules.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Model
 */
class PageModel extends \Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_page';


	/**
	 * Find a published page by its ID
	 * @param mixed
	 * @return Model
	 */
	public static function findPublishedById($intId)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.id=?");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findOneBy($arrColumns, $intId);
	}


	/**
	 * Find published pages by their IDs or aliases
	 * @param mixed
	 * @return Model
	 */
	public static function findPublishedByIdOrAliases($varId)
	{
		$t = static::$strTable;
		$arrColumns = array("($t.id=? OR $t.alias=?)");
		$arrValues = array((is_numeric($varId) ? $varId : 0), $varId);

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findBy($arrColumns, $arrValues);
	}


	/**
	 * Find the first published root page by its host name and language
	 * @param string
	 * @param mixed
	 * @return Model
	 */
	public static function findFirstPublishedRootByHostAndLanguage($strHost, $varLanguage)
	{
		$time = time();
		$objDatabase = \Database::getInstance();
		$t = static::$strTable;

		if (is_array($varLanguage))
		{
			$arrColumns = array("$t.type='root' AND ($t.dns=? OR $t.dns='')");

			if (!empty($varLanguage))
			{
				$arrColumns[] = "($t.language IN('". implode("','", $varLanguage) ."') OR $t.fallback=1)";
			}
			else
			{
				$arrColumns[] = "$t.fallback=1";
			}

			$strOrder = "$t.dns DESC" . (!empty($varLanguage) ? ", " . $objDatabase->findInSet("$t.language", array_reverse($varLanguage)) . " DESC" : "") . ", $t.sorting";

			if (!BE_USER_LOGGED_IN)
			{
				$time = time();
				$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
			}

			return static::findOneBy($arrColumns, $strHost, $strOrder);
		}
		else
		{
			$arrColumns = array("$t.type='root' AND ($t.dns=? OR $t.dns='') AND $t.language=?");
			$arrValues = array($strHost, $varLanguage);

			if (!BE_USER_LOGGED_IN)
			{
				$time = time();
				$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
			}

			return static::findOneBy($arrColumns, $arrValues, "$t.dns DESC, $t.fallback");
		}
	}


	/**
	 * Find the first published page by its parent ID
	 * @param integer
	 * @return Model|null
	 */
	public static function findFirstPublishedByPid($intPid)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.type!='root' AND $t.type!='error_403' AND $t.type!='error_404'");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findOneBy($arrColumns, $intPid, "$t.sorting");
	}


	/**
	 * Find the first published regular page by its parent ID
	 * @param integer
	 * @return Model|null
	 */
	public static function findFirstPublishedRegularByPid($intPid)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.type='regular'");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findOneBy($arrColumns, $intPid, "$t.sorting");
	}


	/**
	 * Find an error 403 page by its parent ID
	 * @param integer
	 * @return Model|null
	 */
	public static function find403ByPid($intPid)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.type='error_403'");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findOneBy($arrColumns, $intPid, "$t.sorting");
	}


	/**
	 * Find an error 404 page by its parent ID
	 * @param integer
	 * @return Model|null
	 */
	public static function find404ByPid($intPid)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.type='error_404'");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findOneBy($arrColumns, $intPid, "$t.sorting");
	}


	/**
	 * Find the parent records of a record
	 * @param integer
	 * @return Model|null
	 */
	public static function findParentsById($intId)
	{
		$objPages = \Database::getInstance()->prepare("SELECT *, @pid:=pid FROM tl_page WHERE id=?" . str_repeat(" UNION SELECT *, @pid:=pid FROM tl_page WHERE id=@pid", 9))
											->execute($intId);

		if ($objPages->numRows < 1)
		{
			return null;
		}

		return new static($objPages);
	}
}

?>