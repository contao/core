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
 * Reads and writes pages
 * 
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
class PageModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_page';


	/**
	 * Find a published page by its ID
	 * 
	 * @param integer $intId      The page ID
	 * @param array   $arrOptions An optional options array
	 * 
	 * @return \Model|null The model or null if there is no published page
	 */
	public static function findPublishedById($intId, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.id=?");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findOneBy($arrColumns, $intId, $arrOptions);
	}


	/**
	 * Find the first published root page by its host name and language
	 * 
	 * @param string $strHost     The host name
	 * @param mixed  $varLanguage An ISO language code or an array of ISO language codes
	 * @param array  $arrOptions  An optional options array
	 * 
	 * @return \Model|null The model or null if there is no matching root page
	 */
	public static function findFirstPublishedRootByHostAndLanguage($strHost, $varLanguage, array $arrOptions=array())
	{
		$t = static::$strTable;
		$objDatabase = \Database::getInstance();

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

			if (!isset($arrOptions['order']))
			{
				$arrOptions['order'] = "$t.dns DESC" . (!empty($varLanguage) ? ", " . $objDatabase->findInSet("$t.language", array_reverse($varLanguage)) . " DESC" : "") . ", $t.sorting";
			}

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

			if (!isset($arrOptions['order']))
			{
				$arrOptions['order'] = "$t.dns DESC, $t.fallback";
			}

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
	 * 
	 * @param integer $intPid     The parent page's ID
	 * @param array   $arrOptions An optional options array
	 * 
	 * @return \Model|null The model or null if there is no published page
	 */
	public static function findFirstPublishedByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.type!='root' AND $t.type!='error_403' AND $t.type!='error_404'");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting";
		}

		return static::findOneBy($arrColumns, $intPid, $arrOptions);
	}


	/**
	 * Find the first published regular page by its parent ID
	 * 
	 * @param integer $intPid The parent page's ID
	 * @param array   $arrOptions An optional options array
	 * 
	 * @return \Model|null The model or null if there is no published regular page
	 */
	public static function findFirstPublishedRegularByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.type='regular'");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting";
		}

		return static::findOneBy($arrColumns, $intPid, $arrOptions);
	}


	/**
	 * Find an error 403 page by its parent ID
	 * 
	 * @param integer $intPid     The parent page's ID
	 * @param array   $arrOptions An optional options array
	 * 
	 * @return \Model|null The model or null if there is no 403 page
	 */
	public static function find403ByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.type='error_403'");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting";
		}

		return static::findOneBy($arrColumns, $intPid, $arrOptions);
	}


	/**
	 * Find an error 404 page by its parent ID
	 * 
	 * @param integer $intPid     The parent page's ID
	 * @param array   $arrOptions An optional options array
	 * 
	 * @return \Model|null The model or null if there is no 404 page
	 */
	public static function find404ByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.type='error_404'");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting";
		}

		return static::findOneBy($arrColumns, $intPid, $arrOptions);
	}


	/**
	 * Find pages matching a list of possible alias names
	 * 
	 * @param array $arrAliases An array of possible alias names
	 * @param array $arrOptions An optional options array
	 * 
	 * @return \Model_Collection|null A collection of Models or null if there is no matching pages
	 */
	public static function findByAliases($arrAliases, array $arrOptions=array())
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

		// Check the publication status (see #4652)
		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = \Database::getInstance()->findInSet("$t.alias", $arrAliases);
		}

		return static::findBy($arrColumns, null, $arrOptions);
	}


	/**
	 * Find published pages by their ID or aliases
	 * 
	 * @param mixed $varId      The numeric ID or the alias name
	 * @param array $arrOptions An optional options array
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no pages
	 */
	public static function findPublishedByIdOrAlias($varId, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("($t.id=? OR $t.alias=?)");
		$arrValues = array((is_numeric($varId) ? $varId : 0), $varId);

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findBy($arrColumns, $arrValues, $arrOptions);
	}


	/**
	 * Find all published subpages by their parent ID and exclude pages only visible for guests
	 * 
	 * @param integer $intPid        The parent page's ID
	 * @param boolean $blnShowHidden If true, hidden pages will be included
	 * @param boolean $blnIsSitemap  If true, the sitemap settings apply
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no pages
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

		return new \Model\Collection($objSubpages, 'tl_page');
	}


	/**
	 * Find all published regular pages by their IDs and exclude pages only visible for guests
	 * 
	 * @param integer $arrIds     An array of page IDs
	 * @param array   $arrOptions An optional options array
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no pages
	 */
	public static function findPublishedRegularWithoutGuestsByIds($arrIds, array $arrOptions=array())
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

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = \Database::getInstance()->findInSet("$t.id", $arrIds);
		}

		return static::findBy($arrColumns, null, $arrOptions);
	}


	/**
	 * Find all published regular pages by their parent IDs and exclude pages only visible for guests
	 * 
	 * @param integer $intPid     The parent page's ID
	 * @param array   $arrOptions An optional options array
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no pages
	 */
	public static function findPublishedRegularWithoutGuestsByPid($intPid, array $arrOptions=array())
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

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting";
		}

		return static::findBy($arrColumns, $intPid, $arrOptions);
	}


	/**
	 * Find the parent pages of a page
	 * 
	 * @param integer $intId The page's ID
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no parent pages
	 */
	public static function findParentsById($intId)
	{
		$objPages = \Database::getInstance()->prepare("SELECT *, @pid:=pid FROM tl_page WHERE id=?" . str_repeat(" UNION SELECT *, @pid:=pid FROM tl_page WHERE id=@pid", 9))
											->execute($intId);

		if ($objPages->numRows < 1)
		{
			return null;
		}

		return new \Model\Collection($objPages, 'tl_page');
	}
}
