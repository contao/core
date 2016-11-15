<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Reads and writes pages
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $sorting
 * @property integer $tstamp
 * @property string  $title
 * @property string  $alias
 * @property string  $type
 * @property string  $pageTitle
 * @property string  $language
 * @property string  $robots
 * @property string  $description
 * @property string  $redirect
 * @property integer $jumpTo
 * @property string  $url
 * @property boolean $target
 * @property string  $dns
 * @property string  $staticFiles
 * @property string  $staticPlugins
 * @property boolean $fallback
 * @property string  $adminEmail
 * @property string  $dateFormat
 * @property string  $timeFormat
 * @property string  $datimFormat
 * @property boolean $createSitemap
 * @property string  $sitemapName
 * @property boolean $useSSL
 * @property boolean $autoforward
 * @property boolean $protected
 * @property string  $groups
 * @property boolean $includeLayout
 * @property integer $layout
 * @property integer $mobileLayout
 * @property boolean $includeCache
 * @property integer $cache
 * @property boolean $includeChmod
 * @property integer $cuser
 * @property integer $cgroup
 * @property string  $chmod
 * @property boolean $noSearch
 * @property string  $cssClass
 * @property string  $sitemap
 * @property boolean $hide
 * @property boolean $guests
 * @property integer $tabindex
 * @property boolean $accesskey
 * @property boolean $published
 * @property string  $start
 * @property string  $stop
 * @property array   $trail
 * @property string  $mainAlias
 * @property string  $mainTitle
 * @property string  $mainPageTitle
 * @property string  $parentAlias
 * @property string  $parentTitle
 * @property string  $parentPageTitle
 * @property string  $folderUrl
 * @property integer $rootId
 * @property string  $rootAlias
 * @property string  $rootTitle
 * @property string  $rootPageTitle
 * @property string  $domain
 * @property string  $rootLanguage
 * @property boolean $rootIsPublic
 * @property boolean $rootIsFallback
 * @property boolean $rootUseSSL
 * @property string  $rootFallbackLanguage
 * @property array   $subpages
 * @property string  $outputFormat
 * @property string  $outputVariant
 * @property boolean $hasJQuery
 * @property boolean $hasMooTools
 * @property boolean $isMobile
 * @property string  $template
 * @property string  $templateGroup
 *
 * @method static \PageModel|null findById($id, $opt=array())
 * @method static \PageModel|null findByPk($id, $opt=array())
 * @method static \PageModel|null findByIdOrAlias($val, $opt=array())
 * @method static \PageModel|null findOneBy($col, $val, $opt=array())
 * @method static \PageModel|null findOneByPid($val, $opt=array())
 * @method static \PageModel|null findOneBySorting($val, $opt=array())
 * @method static \PageModel|null findOneByTstamp($val, $opt=array())
 * @method static \PageModel|null findOneByTitle($val, $opt=array())
 * @method static \PageModel|null findOneByAlias($val, $opt=array())
 * @method static \PageModel|null findOneByType($val, $opt=array())
 * @method static \PageModel|null findOneByPageTitle($val, $opt=array())
 * @method static \PageModel|null findOneByLanguage($val, $opt=array())
 * @method static \PageModel|null findOneByRobots($val, $opt=array())
 * @method static \PageModel|null findOneByDescription($val, $opt=array())
 * @method static \PageModel|null findOneByRedirect($val, $opt=array())
 * @method static \PageModel|null findOneByJumpTo($val, $opt=array())
 * @method static \PageModel|null findOneByUrl($val, $opt=array())
 * @method static \PageModel|null findOneByTarget($val, $opt=array())
 * @method static \PageModel|null findOneByDns($val, $opt=array())
 * @method static \PageModel|null findOneByStaticFiles($val, $opt=array())
 * @method static \PageModel|null findOneByStaticPlugins($val, $opt=array())
 * @method static \PageModel|null findOneByFallback($val, $opt=array())
 * @method static \PageModel|null findOneByAdminEmail($val, $opt=array())
 * @method static \PageModel|null findOneByDateFormat($val, $opt=array())
 * @method static \PageModel|null findOneByTimeFormat($val, $opt=array())
 * @method static \PageModel|null findOneByDatimFormat($val, $opt=array())
 * @method static \PageModel|null findOneByCreateSitemap($val, $opt=array())
 * @method static \PageModel|null findOneBySitemapName($val, $opt=array())
 * @method static \PageModel|null findOneByUseSSL($val, $opt=array())
 * @method static \PageModel|null findOneByAutoforward($val, $opt=array())
 * @method static \PageModel|null findOneByProtected($val, $opt=array())
 * @method static \PageModel|null findOneByGroups($val, $opt=array())
 * @method static \PageModel|null findOneByIncludeLayout($val, $opt=array())
 * @method static \PageModel|null findOneByLayout($val, $opt=array())
 * @method static \PageModel|null findOneByMobileLayout($val, $opt=array())
 * @method static \PageModel|null findOneByIncludeCache($val, $opt=array())
 * @method static \PageModel|null findOneByCache($val, $opt=array())
 * @method static \PageModel|null findOneByIncludeChmod($val, $opt=array())
 * @method static \PageModel|null findOneByCuser($val, $opt=array())
 * @method static \PageModel|null findOneByCgroup($val, $opt=array())
 * @method static \PageModel|null findOneByChmod($val, $opt=array())
 * @method static \PageModel|null findOneByNoSearch($val, $opt=array())
 * @method static \PageModel|null findOneByCssClass($val, $opt=array())
 * @method static \PageModel|null findOneBySitemap($val, $opt=array())
 * @method static \PageModel|null findOneByHide($val, $opt=array())
 * @method static \PageModel|null findOneByGuests($val, $opt=array())
 * @method static \PageModel|null findOneByTabindex($val, $opt=array())
 * @method static \PageModel|null findOneByAccesskey($val, $opt=array())
 * @method static \PageModel|null findOneByPublished($val, $opt=array())
 * @method static \PageModel|null findOneByStart($val, $opt=array())
 * @method static \PageModel|null findOneByStop($val, $opt=array())
 *
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByPid($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findBySorting($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByTitle($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByAlias($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByType($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByPageTitle($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByLanguage($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByRobots($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByDescription($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByRedirect($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByJumpTo($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByUrl($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByTarget($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByDns($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByStaticFiles($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByStaticPlugins($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByFallback($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByAdminEmail($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByDateFormat($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByTimeFormat($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByDatimFormat($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByCreateSitemap($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findBySitemapName($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByUseSSL($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByAutoforward($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByProtected($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByGroups($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByIncludeLayout($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByLayout($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByMobileLayout($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByIncludeCache($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByCache($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByIncludeChmod($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByCuser($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByCgroup($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByChmod($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByNoSearch($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByCssClass($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findBySitemap($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByHide($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByGuests($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByTabindex($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByAccesskey($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByPublished($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByStart($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findByStop($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\PageModel[]|\PageModel|null findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByPid($val, $opt=array())
 * @method static integer countBySorting($val, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByTitle($val, $opt=array())
 * @method static integer countByAlias($val, $opt=array())
 * @method static integer countByType($val, $opt=array())
 * @method static integer countByPageTitle($val, $opt=array())
 * @method static integer countByLanguage($val, $opt=array())
 * @method static integer countByRobots($val, $opt=array())
 * @method static integer countByDescription($val, $opt=array())
 * @method static integer countByRedirect($val, $opt=array())
 * @method static integer countByJumpTo($val, $opt=array())
 * @method static integer countByUrl($val, $opt=array())
 * @method static integer countByTarget($val, $opt=array())
 * @method static integer countByDns($val, $opt=array())
 * @method static integer countByStaticFiles($val, $opt=array())
 * @method static integer countByStaticPlugins($val, $opt=array())
 * @method static integer countByFallback($val, $opt=array())
 * @method static integer countByAdminEmail($val, $opt=array())
 * @method static integer countByDateFormat($val, $opt=array())
 * @method static integer countByTimeFormat($val, $opt=array())
 * @method static integer countByDatimFormat($val, $opt=array())
 * @method static integer countByCreateSitemap($val, $opt=array())
 * @method static integer countBySitemapName($val, $opt=array())
 * @method static integer countByUseSSL($val, $opt=array())
 * @method static integer countByAutoforward($val, $opt=array())
 * @method static integer countByProtected($val, $opt=array())
 * @method static integer countByGroups($val, $opt=array())
 * @method static integer countByIncludeLayout($val, $opt=array())
 * @method static integer countByLayout($val, $opt=array())
 * @method static integer countByMobileLayout($val, $opt=array())
 * @method static integer countByIncludeCache($val, $opt=array())
 * @method static integer countByCache($val, $opt=array())
 * @method static integer countByIncludeChmod($val, $opt=array())
 * @method static integer countByCuser($val, $opt=array())
 * @method static integer countByCgroup($val, $opt=array())
 * @method static integer countByChmod($val, $opt=array())
 * @method static integer countByNoSearch($val, $opt=array())
 * @method static integer countByCssClass($val, $opt=array())
 * @method static integer countBySitemap($val, $opt=array())
 * @method static integer countByHide($val, $opt=array())
 * @method static integer countByGuests($val, $opt=array())
 * @method static integer countByTabindex($val, $opt=array())
 * @method static integer countByAccesskey($val, $opt=array())
 * @method static integer countByPublished($val, $opt=array())
 * @method static integer countByStart($val, $opt=array())
 * @method static integer countByStop($val, $opt=array())
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class PageModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_page';

	/**
	 * Details loaded
	 * @var boolean
	 */
	protected $blnDetailsLoaded = false;


	/**
	 * Find a published page by its ID
	 *
	 * @param integer $intId      The page ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \PageModel|null The model or null if there is no published page
	 */
	public static function findPublishedById($intId, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.id=?");

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
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
	 * @return \PageModel|null The model or null if there is no matching root page
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
				$arrColumns[] = "($t.language IN('". implode("','", $varLanguage) ."') OR $t.fallback='1')";
			}
			else
			{
				$arrColumns[] = "$t.fallback='1'";
			}

			if (!isset($arrOptions['order']))
			{
				$arrOptions['order'] = "$t.dns DESC" . (!empty($varLanguage) ? ", " . $objDatabase->findInSet("$t.language", array_reverse($varLanguage)) . " DESC" : "") . ", $t.sorting";
			}

			if (!BE_USER_LOGGED_IN)
			{
				$time = \Date::floorToMinute();
				$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
			}

			return static::findOneBy($arrColumns, $strHost, $arrOptions);
		}
		else
		{
			$arrColumns = array("$t.type='root' AND ($t.dns=? OR $t.dns='') AND ($t.language=? OR $t.fallback='1')");
			$arrValues = array($strHost, $varLanguage);

			if (!isset($arrOptions['order']))
			{
				$arrOptions['order'] = "$t.dns DESC, $t.fallback";
			}

			if (!BE_USER_LOGGED_IN)
			{
				$time = \Date::floorToMinute();
				$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
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
	 * @return \PageModel|null The model or null if there is no published page
	 */
	public static function findFirstPublishedByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.type!='root' AND $t.type!='error_403' AND $t.type!='error_404'");

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
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
	 * @return \PageModel|null The model or null if there is no published regular page
	 */
	public static function findFirstPublishedRegularByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.type='regular'");

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
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
	 * @return \PageModel|null The model or null if there is no 403 page
	 */
	public static function find403ByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.type='error_403'");

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
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
	 * @return \PageModel|null The model or null if there is no 404 page
	 */
	public static function find404ByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.type='error_404'");

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
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
	 * @return \Model\Collection|\PageModel[]|\PageModel|null A collection of models or null if there is no matching pages
	 */
	public static function findByAliases($arrAliases, array $arrOptions=array())
	{
		if (!is_array($arrAliases) || empty($arrAliases))
		{
			return null;
		}

		// Remove everything that is not an alias
		$arrAliases = array_filter(array_map(function ($v) { return preg_match('/^[\w\/.-]+$/u', $v) ? $v : null; }, $arrAliases));

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
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
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
	 * @return \Model\Collection|\PageModel[]|\PageModel|null A collection of models or null if there are no pages
	 */
	public static function findPublishedByIdOrAlias($varId, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("($t.id=? OR $t.alias=?)");
		$arrValues = array((is_numeric($varId) ? $varId : 0), $varId);

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
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
	 * @return \Model\Collection|\PageModel[]|\PageModel|null A collection of models or null if there are no pages
	 */
	public static function findPublishedSubpagesWithoutGuestsByPid($intPid, $blnShowHidden=false, $blnIsSitemap=false)
	{
		$time = \Date::floorToMinute();

		$objSubpages = \Database::getInstance()->prepare("SELECT p1.*, (SELECT COUNT(*) FROM tl_page p2 WHERE p2.pid=p1.id AND p2.type!='root' AND p2.type!='error_403' AND p2.type!='error_404'" . (!$blnShowHidden ? ($blnIsSitemap ? " AND (p2.hide='' OR sitemap='map_always')" : " AND p2.hide=''") : "") . ((FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN) ? " AND p2.guests=''" : "") . (!BE_USER_LOGGED_IN ? " AND (p2.start='' OR p2.start<='$time') AND (p2.stop='' OR p2.stop>'" . ($time + 60) . "') AND p2.published='1'" : "") . ") AS subpages FROM tl_page p1 WHERE p1.pid=? AND p1.type!='root' AND p1.type!='error_403' AND p1.type!='error_404'" . (!$blnShowHidden ? ($blnIsSitemap ? " AND (p1.hide='' OR sitemap='map_always')" : " AND p1.hide=''") : "") . ((FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN) ? " AND p1.guests=''" : "") . (!BE_USER_LOGGED_IN ? " AND (p1.start='' OR p1.start<='$time') AND (p1.stop='' OR p1.stop>'" . ($time + 60) . "') AND p1.published='1'" : "") . " ORDER BY p1.sorting")
											   ->execute($intPid);

		if ($objSubpages->numRows < 1)
		{
			return null;
		}

		return static::createCollectionFromDbResult($objSubpages, 'tl_page');
	}


	/**
	 * Find all published regular pages by their IDs and exclude pages only visible for guests
	 *
	 * @param integer $arrIds     An array of page IDs
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\PageModel[]|\PageModel|null A collection of models or null if there are no pages
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
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
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
	 * @return \Model\Collection|\PageModel[]|\PageModel|null A collection of models or null if there are no pages
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
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting";
		}

		return static::findBy($arrColumns, $intPid, $arrOptions);
	}


	/**
	 * Find the language fallback page by hostname
	 *
	 * @param string $strHost    The hostname
	 * @param array  $arrOptions An optional options array
	 *
	 * @return \PageModel|null The model or null if there is not fallback page
	 */
	public static function findPublishedFallbackByHostname($strHost, array $arrOptions=array())
	{
		// Try to load from the registry (see #8544)
		if (empty($arrOptions))
		{
			$objModel = \Model\Registry::getInstance()->fetch(static::$strTable, $strHost, 'contao.dns-fallback');

			if ($objModel !== null)
			{
				return $objModel;
			}
		}

		$t = static::$strTable;
		$arrColumns = array("$t.dns=? AND $t.fallback='1'");

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
		}

		return static::findOneBy($arrColumns, $strHost, $arrOptions);
	}


	/**
	 * Finds the published root pages
	 *
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\PageModel[]|\PageModel|null A collection of models or null if there are no parent pages
	 */
	public static function findPublishedRootPages(array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.type=?");

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
		}

		return static::findBy($arrColumns, 'root', $arrOptions);
	}


	/**
	 * Find the parent pages of a page
	 *
	 * @param integer $intId The page's ID
	 *
	 * @return \Model\Collection|\PageModel[]|\PageModel|null A collection of models or null if there are no parent pages
	 */
	public static function findParentsById($intId)
	{
		$arrModels = array();

		while ($intId > 0 && ($objPage = static::findByPk($intId)) !== null)
		{
			$intId = $objPage->pid;
			$arrModels[] = $objPage;
		}

		if (empty($arrModels))
		{
			return null;
		}

		return static::createCollection($arrModels, 'tl_page');
	}


	/**
	 * Find a page by its ID and return it with the inherited details
	 *
	 * @param integer $intId The page's ID
	 *
	 * @return \PageModel|null The model or null if there is no matching page
	 */
	public static function findWithDetails($intId)
	{
		$objPage = static::findByPk($intId);

		if ($objPage === null)
		{
			return null;
		}

		return $objPage->loadDetails();
	}


	/**
	 * Register the contao.dns-fallback alias when the model is attached to the registry
	 *
	 * @param \Model\Registry $registry The model registry
	 */
	public function onRegister(\Model\Registry $registry)
	{
		parent::onRegister($registry);

		// Register this model as being the fallback page for a given dns
		if ($this->fallback && $this->type == 'root' && !$registry->isRegisteredAlias($this, 'contao.dns-fallback', $this->dns))
		{
			$registry->registerAlias($this, 'contao.dns-fallback', $this->dns);
		}
	}


	/**
	 * Unregister the contao.dns-fallback alias when the model is detached from the registry
	 *
	 * @param \Model\Registry $registry The model registry
	 */
	public function onUnregister(\Model\Registry $registry)
	{
		parent::onUnregister($registry);

		// Unregister the fallback page
		if ($this->fallback && $this->type == 'root' && $registry->isRegisteredAlias($this, 'contao.dns-fallback', $this->dns))
		{
			$registry->unregisterAlias($this, 'contao.dns-fallback', $this->dns);
		}
	}


	/**
	 * Get the details of a page including inherited parameters
	 *
	 * @return \PageModel The page model
	 */
	public function loadDetails()
	{
		// Loaded already
		if ($this->blnDetailsLoaded)
		{
			return $this;
		}

		// Set some default values
		$this->protected = (boolean) $this->protected;
		$this->groups = $this->protected ? deserialize($this->groups) : false;
		$this->layout = $this->includeLayout ? $this->layout : false;
		$this->mobileLayout = $this->includeLayout ? $this->mobileLayout : false;
		$this->cache = $this->includeCache ? $this->cache : false;

		$pid = $this->pid;
		$type = $this->type;
		$alias = $this->alias;
		$name = $this->title;
		$title = $this->pageTitle ?: $this->title;
		$folderUrl = '';
		$palias = '';
		$pname = '';
		$ptitle = '';
		$trail = array($this->id, $pid);

		// Inherit the settings
		if ($this->type == 'root')
		{
			$objParentPage = $this; // see #4610
		}
		else
		{
			// Load all parent pages
			$objParentPage = \PageModel::findParentsById($pid);

			if ($objParentPage !== null)
			{
				while ($pid > 0 && $type != 'root' && $objParentPage->next())
				{
					$pid = $objParentPage->pid;
					$type = $objParentPage->type;

					// Parent title
					if ($ptitle == '')
					{
						$palias = $objParentPage->alias;
						$pname = $objParentPage->title;
						$ptitle = $objParentPage->pageTitle ?: $objParentPage->title;
					}

					// Page title
					if ($type != 'root')
					{
						$alias = $objParentPage->alias;
						$name = $objParentPage->title;
						$title = $objParentPage->pageTitle ?: $objParentPage->title;
						$folderUrl = basename($alias) . '/' . $folderUrl;
						$trail[] = $objParentPage->pid;
					}

					// Cache
					if ($objParentPage->includeCache && $this->cache === false)
					{
						$this->cache = $objParentPage->cache;
					}

					// Layout
					if ($objParentPage->includeLayout)
					{
						if ($this->layout === false)
						{
							$this->layout = $objParentPage->layout;
						}
						if ($this->mobileLayout === false)
						{
							$this->mobileLayout = $objParentPage->mobileLayout;
						}
					}

					// Protection
					if ($objParentPage->protected && $this->protected === false)
					{
						$this->protected = true;
						$this->groups = deserialize($objParentPage->groups);
					}
				}
			}

			// Set the titles
			$this->mainAlias = $alias;
			$this->mainTitle = $name;
			$this->mainPageTitle = $title;
			$this->parentAlias = $palias;
			$this->parentTitle = $pname;
			$this->parentPageTitle = $ptitle;
			$this->folderUrl = $folderUrl;
		}

		// Set the root ID and title
		if ($objParentPage !== null && $objParentPage->type == 'root')
		{
			$this->rootId = $objParentPage->id;
			$this->rootAlias = $objParentPage->alias;
			$this->rootTitle = $objParentPage->title;
			$this->rootPageTitle = $objParentPage->pageTitle ?: $objParentPage->title;
			$this->domain = $objParentPage->dns;
			$this->rootLanguage = $objParentPage->language;
			$this->language = $objParentPage->language;
			$this->staticFiles = $objParentPage->staticFiles;
			$this->staticPlugins = $objParentPage->staticPlugins;
			$this->dateFormat = $objParentPage->dateFormat;
			$this->timeFormat = $objParentPage->timeFormat;
			$this->datimFormat = $objParentPage->datimFormat;
			$this->adminEmail = $objParentPage->adminEmail;

			// Store whether the root page has been published
			$time = \Date::floorToMinute();
			$this->rootIsPublic = ($objParentPage->published && ($objParentPage->start == '' || $objParentPage->start <= $time) && ($objParentPage->stop == '' || $objParentPage->stop > ($time + 60)));
			$this->rootIsFallback = true;
			$this->rootUseSSL = $objParentPage->useSSL;
			$this->rootFallbackLanguage = $objParentPage->language;

			// Store the fallback language (see #6874)
			if (!$objParentPage->fallback)
			{
				$this->rootIsFallback = false;
				$this->rootFallbackLanguage = null;

				$objFallback = static::findPublishedFallbackByHostname($objParentPage->dns);

				if ($objFallback !== null)
				{
					$this->rootFallbackLanguage = $objFallback->language;
				}
			}
		}

		// No root page found
		elseif (TL_MODE == 'FE' && $this->type != 'root')
		{
			header('HTTP/1.1 404 Not Found');
			\System::log('Page ID "'. $this->id .'" does not belong to a root page', __METHOD__, TL_ERROR);
			die_nicely('be_no_root', 'No root page found');
		}

		$this->trail = array_reverse($trail);

		// Do not cache protected pages
		if ($this->protected)
		{
			$this->cache = 0;
		}

		// Use the global date format if none is set (see #6104)
		if ($this->dateFormat == '')
		{
			$this->dateFormat = \Config::get('dateFormat');
		}
		if ($this->timeFormat == '')
		{
			$this->timeFormat = \Config::get('timeFormat');
		}
		if ($this->datimFormat == '')
		{
			$this->datimFormat = \Config::get('datimFormat');
		}

		// Prevent saving (see #6506 and #7199)
		$this->preventSaving();
		$this->blnDetailsLoaded = true;

		return $this;
	}


	/**
	 * Generate an URL depending on the current rewriteURL setting
	 *
	 * @param string $strParams    An optional string of URL parameters
	 * @param string $strForceLang Force a certain language
	 *
	 * @return string An URL that can be used in the front end
	 */
	public function getFrontendUrl($strParams=null, $strForceLang=null)
	{
		if ($strForceLang !== null)
		{
			@trigger_error('Using PageModel::getFrontendUrl() with $strForceLang has been deprecated and will no longer work in Contao 5.0.', E_USER_DEPRECATED);
		}

		return \Controller::generateFrontendUrl($this->loadDetails()->row(), $strParams, $strForceLang, true);
	}


	/**
	 * Generate an absolute URL depending on the current rewriteURL setting
	 *
	 * @param string $strParams An optional string of URL parameters
	 *
	 * @return string An absolute URL that can be used in the front end
	 */
	public function getAbsoluteUrl($strParams=null)
	{
		$strUrl = \Controller::generateFrontendUrl($this->loadDetails()->row(), $strParams, null, true);

		if (strncmp($strUrl, 'http://', 7) !== 0 && strncmp($strUrl, 'https://', 8) !== 0)
		{
			$strUrl = ($this->rootUseSSL ? 'https://' : 'http://') . \Environment::get('host') . TL_PATH . '/' . $strUrl;
		}

		return $strUrl;
	}
}
