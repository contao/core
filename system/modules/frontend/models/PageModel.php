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
 * Provide methods to find and save pages.
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
	 * @return \Contao\Model|null
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
	 * Find the first published root page by its host name and language
	 * @param string
	 * @param mixed
	 * @return \Contao\Model|null
	 */
	public static function findFirstPublishedRootByHostAndLanguage($strHost, $varLanguage)
	{
		$t = static::$strTable;
		$objDatabase = \Database::getInstance();
		$arrOptions = array();

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

			$arrOptions['order'] = "$t.dns DESC" . (!empty($varLanguage) ? ", " . $objDatabase->findInSet("$t.language", array_reverse($varLanguage)) . " DESC" : "") . ", $t.sorting";

			if (!BE_USER_LOGGED_IN)
			{
				$time = time();
				$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
			}

			return static::findOneBy($arrColumns, $strHost, $arrOptions);
		}
		else
		{
			$arrColumns = array("$t.type='root' AND ($t.dns=? OR $t.dns='') AND $t.language=?");
			$arrValues = array($strHost, $varLanguage);
			$arrOptions['order'] = "$t.dns DESC, $t.fallback";

			if (!BE_USER_LOGGED_IN)
			{
				$time = time();
				$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
			}

			return static::findOneBy($arrColumns, $arrValues, $arrOptions);
		}
	}


	/**
	 * Find the first published page by its parent ID
	 * @param integer
	 * @return \Contao\Model|null
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

		return static::findOneBy($arrColumns, $intPid, array('order'=>"$t.sorting"));
	}


	/**
	 * Find the first published regular page by its parent ID
	 * @param integer
	 * @return \Contao\Model|null
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

		return static::findOneBy($arrColumns, $intPid, array('order'=>"$t.sorting"));
	}


	/**
	 * Find an error 403 page by its parent ID
	 * @param integer
	 * @return \Contao\Model|null
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

		return static::findOneBy($arrColumns, $intPid, array('order'=>"$t.sorting"));
	}


	/**
	 * Find an error 404 page by its parent ID
	 * @param integer
	 * @return \Contao\Model|null
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

		return static::findOneBy($arrColumns, $intPid, array('order'=>"$t.sorting"));
	}


	/**
	 * Find a page matching a list of possible alias names
	 * @param array
	 * @return \Contao\Model|null
	 */
	public static function findByAliases($arrAliases)
	{
		if (!is_array($arrAliases) || empty($arrAliases))
		{
			return null;
		}

		// Remove everything that is not an alias
		$arrAliases = array_filter(array_map(function($v) {
			return preg_match('/^[\pN\pL\/\._-]+$/', $v) ? $v : null;
		}, $arrAliases));

		// Return if nothing is left
		if (empty($arrAliases))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.alias IN('" . implode("','", array_filter($arrAliases)) . "')");

		return static::findOneBy($arrColumns, null, array('order'=>Database::getInstance()->findInSet("$t.alias", $arrAliases)));
	}


	/**
	 * Find published pages by their IDs or aliases
	 * @param mixed
	 * @return \Contao\Model_Collection|null
	 */
	public static function findPublishedByIdOrAlias($varId)
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
	 * Find all published subpages by their parent ID and exclude pages
	 * which are only visible for guests
	 * @param integer
	 * @param boolean
	 * @param boolean
	 * @return \Contao\Model_Collection|null
	 */
	public static function findPublishedSubpagesWithoutGuestsByPid($intPid, $blnShowHidden=false, $blnIsSitemap=false)
	{
		$time = time();

		$objSubpages = \Database::getInstance()->prepare("SELECT p1.*, (SELECT COUNT(*) FROM tl_page p2 WHERE p2.pid=p1.id AND p2.type!='root' AND p2.type!='error_403' AND p2.type!='error_404'" . (!$blnShowHidden ? ($blnIsSitemap ? " AND (p2.hide='' OR sitemap='map_always')" : " AND p2.hide=''") : "") . ((FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN) ? " AND p2.guests=''" : "") . (!BE_USER_LOGGED_IN ? " AND (p2.start='' OR p2.start<$time) AND (p2.stop='' OR p2.stop>$time) AND p2.published=1" : "") . ") AS subpages FROM tl_page p1 WHERE p1.pid=? AND p1.type!='root' AND p1.type!='error_403' AND p1.type!='error_404'" . (!$blnShowHidden ? ($blnIsSitemap ? " AND (p1.hide='' OR sitemap='map_always')" : " AND p1.hide=''") : "") . ((FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN) ? " AND p1.guests=''" : "") . (!BE_USER_LOGGED_IN ? " AND (p1.start='' OR p1.start<$time) AND (p1.stop='' OR p1.stop>$time) AND p1.published=1" : "") . " ORDER BY p1.sorting")
											   ->execute($intPid);

		if ($objSubpages->numRows < 1)
		{
			return null;
		}

		return new \Model_Collection($objSubpages, 'tl_page');
	}


	/**
	 * Find all published regular pages by their IDs and exclude pages
	 * which are only visible for guests
	 * @param integer
	 * @return \Contao\Model_Collection|null
	 */
	public static function findPublishedRegularWithoutGuestsByIds($arrIds)
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.id IN(" . implode(',', array_map('intval', $arrIds)) . ") AND $t.type!='root' AND $t.type!='error_403' AND $t.type!='error_404'");

		if (FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.guests=''";
		}

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findBy($arrColumns, null, array('order'=>\Database::getInstance()->findInSet("$t.id", $arrIds)));
	}


	/**
	 * Find all published regular pages by their parent IDs and exclude
	 * pages which are only visible for guests
	 * @param integer
	 * @return \Contao\Model_Collection|null
	 */
	public static function findPublishedRegularWithoutGuestsByPid($intId)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.type!='root' AND $t.type!='error_403' AND $t.type!='error_404'");

		if (FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.guests=''";
		}

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findBy($arrColumns, $intId, array('order'=>"$t.sorting"));
	}


	/**
	 * Find the parent records of a record
	 * @param integer
	 * @return \Contao\Model_Collection|null
	 */
	public static function findParentsById($intId)
	{
		$objPages = \Database::getInstance()->prepare("SELECT *, @pid:=pid FROM tl_page WHERE id=?" . str_repeat(" UNION SELECT *, @pid:=pid FROM tl_page WHERE id=@pid", 9))
											->execute($intId);

		if ($objPages->numRows < 1)
		{
			return null;
		}

		return new \Model_Collection($objPages, 'tl_page');
	}
}
