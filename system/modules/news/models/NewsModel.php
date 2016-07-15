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
 * Reads and writes news
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $tstamp
 * @property string  $headline
 * @property string  $alias
 * @property integer $author
 * @property integer $date
 * @property integer $time
 * @property string  $subheadline
 * @property string  $teaser
 * @property boolean $addImage
 * @property string  $singleSRC
 * @property string  $alt
 * @property string  $size
 * @property string  $imagemargin
 * @property string  $imageUrl
 * @property boolean $fullsize
 * @property string  $caption
 * @property string  $floating
 * @property boolean $addEnclosure
 * @property string  $enclosure
 * @property string  $source
 * @property integer $jumpTo
 * @property integer $articleId
 * @property string  $url
 * @property boolean $target
 * @property string  $cssClass
 * @property boolean $noComments
 * @property boolean $featured
 * @property boolean $published
 * @property string  $start
 * @property string  $stop
 * @property string  $authorName
 *
 * @method static \NewsModel|null findById($id, $opt=array())
 * @method static \NewsModel|null findByPk($id, $opt=array())
 * @method static \NewsModel|null findByIdOrAlias($val, $opt=array())
 * @method static \NewsModel|null findOneBy($col, $val, $opt=array())
 * @method static \NewsModel|null findOneByPid($val, $opt=array())
 * @method static \NewsModel|null findOneByTstamp($val, $opt=array())
 * @method static \NewsModel|null findOneByHeadline($val, $opt=array())
 * @method static \NewsModel|null findOneByAlias($val, $opt=array())
 * @method static \NewsModel|null findOneByAuthor($val, $opt=array())
 * @method static \NewsModel|null findOneByDate($val, $opt=array())
 * @method static \NewsModel|null findOneByTime($val, $opt=array())
 * @method static \NewsModel|null findOneBySubheadline($val, $opt=array())
 * @method static \NewsModel|null findOneByTeaser($val, $opt=array())
 * @method static \NewsModel|null findOneByAddImage($val, $opt=array())
 * @method static \NewsModel|null findOneBySingleSRC($val, $opt=array())
 * @method static \NewsModel|null findOneByAlt($val, $opt=array())
 * @method static \NewsModel|null findOneBySize($val, $opt=array())
 * @method static \NewsModel|null findOneByImagemargin($val, $opt=array())
 * @method static \NewsModel|null findOneByImageUrl($val, $opt=array())
 * @method static \NewsModel|null findOneByFullsize($val, $opt=array())
 * @method static \NewsModel|null findOneByCaption($val, $opt=array())
 * @method static \NewsModel|null findOneByFloating($val, $opt=array())
 * @method static \NewsModel|null findOneByAddEnclosure($val, $opt=array())
 * @method static \NewsModel|null findOneByEnclosure($val, $opt=array())
 * @method static \NewsModel|null findOneBySource($val, $opt=array())
 * @method static \NewsModel|null findOneByJumpTo($val, $opt=array())
 * @method static \NewsModel|null findOneByArticleId($val, $opt=array())
 * @method static \NewsModel|null findOneByUrl($val, $opt=array())
 * @method static \NewsModel|null findOneByTarget($val, $opt=array())
 * @method static \NewsModel|null findOneByCssClass($val, $opt=array())
 * @method static \NewsModel|null findOneByNoComments($val, $opt=array())
 * @method static \NewsModel|null findOneByFeatured($val, $opt=array())
 * @method static \NewsModel|null findOneByPublished($val, $opt=array())
 * @method static \NewsModel|null findOneByStart($val, $opt=array())
 * @method static \NewsModel|null findOneByStop($val, $opt=array())
 *
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByPid($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByHeadline($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByAlias($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByAuthor($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByDate($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByTime($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findBySubheadline($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByTeaser($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByAddImage($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findBySingleSRC($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByAlt($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findBySize($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByImagemargin($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByImageUrl($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByFullsize($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByCaption($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByFloating($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByAddEnclosure($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByEnclosure($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findBySource($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByJumpTo($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByArticleId($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByUrl($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByTarget($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByCssClass($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByNoComments($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByFeatured($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByPublished($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByStart($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findByStop($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\NewsModel[]|\NewsModel|null findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByPid($val, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByHeadline($val, $opt=array())
 * @method static integer countByAlias($val, $opt=array())
 * @method static integer countByAuthor($val, $opt=array())
 * @method static integer countByDate($val, $opt=array())
 * @method static integer countByTime($val, $opt=array())
 * @method static integer countBySubheadline($val, $opt=array())
 * @method static integer countByTeaser($val, $opt=array())
 * @method static integer countByAddImage($val, $opt=array())
 * @method static integer countBySingleSRC($val, $opt=array())
 * @method static integer countByAlt($val, $opt=array())
 * @method static integer countBySize($val, $opt=array())
 * @method static integer countByImagemargin($val, $opt=array())
 * @method static integer countByImageUrl($val, $opt=array())
 * @method static integer countByFullsize($val, $opt=array())
 * @method static integer countByCaption($val, $opt=array())
 * @method static integer countByFloating($val, $opt=array())
 * @method static integer countByAddEnclosure($val, $opt=array())
 * @method static integer countByEnclosure($val, $opt=array())
 * @method static integer countBySource($val, $opt=array())
 * @method static integer countByJumpTo($val, $opt=array())
 * @method static integer countByArticleId($val, $opt=array())
 * @method static integer countByUrl($val, $opt=array())
 * @method static integer countByTarget($val, $opt=array())
 * @method static integer countByCssClass($val, $opt=array())
 * @method static integer countByNoComments($val, $opt=array())
 * @method static integer countByFeatured($val, $opt=array())
 * @method static integer countByPublished($val, $opt=array())
 * @method static integer countByStart($val, $opt=array())
 * @method static integer countByStop($val, $opt=array())
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class NewsModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_news';


	/**
	 * Find a published news item from one or more news archives by its ID or alias
	 *
	 * @param mixed $varId      The numeric ID or alias name
	 * @param array $arrPids    An array of parent IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return \NewsModel|null The model or null if there are no news
	 */
	public static function findPublishedByParentAndIdOrAlias($varId, $arrPids, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("($t.id=? OR $t.alias=?) AND $t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
		}

		return static::findOneBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId), $arrOptions);
	}


	/**
	 * Find published news items by their parent ID
	 *
	 * @param array   $arrPids     An array of news archive IDs
	 * @param boolean $blnFeatured If true, return only featured news, if false, return only unfeatured news
	 * @param integer $intLimit    An optional limit
	 * @param integer $intOffset   An optional offset
	 * @param array   $arrOptions  An optional options array
	 *
	 * @return \Model\Collection|\NewsModel[]|\NewsModel|null A collection of models or null if there are no news
	 */
	public static function findPublishedByPids($arrPids, $blnFeatured=null, $intLimit=0, $intOffset=0, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if ($blnFeatured === true)
		{
			$arrColumns[] = "$t.featured='1'";
		}
		elseif ($blnFeatured === false)
		{
			$arrColumns[] = "$t.featured=''";
		}

		// Never return unpublished elements in the back end, so they don't end up in the RSS feed
		if (!BE_USER_LOGGED_IN || TL_MODE == 'BE')
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order']  = "$t.date DESC";
		}

		$arrOptions['limit']  = $intLimit;
		$arrOptions['offset'] = $intOffset;

		return static::findBy($arrColumns, null, $arrOptions);
	}


	/**
	 * Count published news items by their parent ID
	 *
	 * @param array   $arrPids     An array of news archive IDs
	 * @param boolean $blnFeatured If true, return only featured news, if false, return only unfeatured news
	 * @param array   $arrOptions  An optional options array
	 *
	 * @return integer The number of news items
	 */
	public static function countPublishedByPids($arrPids, $blnFeatured=null, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return 0;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if ($blnFeatured === true)
		{
			$arrColumns[] = "$t.featured='1'";
		}
		elseif ($blnFeatured === false)
		{
			$arrColumns[] = "$t.featured=''";
		}

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
		}

		return static::countBy($arrColumns, null, $arrOptions);
	}


	/**
	 * Find published news items with the default redirect target by their parent ID
	 *
	 * @param integer $intPid     The news archive ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\NewsModel[]|\NewsModel|null A collection of models or null if there are no news
	 */
	public static function findPublishedDefaultByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.source='default'");

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.date DESC";
		}

		return static::findBy($arrColumns, $intPid, $arrOptions);
	}


	/**
	 * Find published news items by their parent ID
	 *
	 * @param integer $intId      The news archive ID
	 * @param integer $intLimit   An optional limit
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\NewsModel[]|\NewsModel|null A collection of models or null if there are no news
	 */
	public static function findPublishedByPid($intId, $intLimit=0, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=?");

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.date DESC";
		}

		if ($intLimit > 0)
		{
			$arrOptions['limit'] = $intLimit;
		}

		return static::findBy($arrColumns, $intId, $arrOptions);
	}


	/**
	 * Find all published news items of a certain period of time by their parent ID
	 *
	 * @param integer $intFrom    The start date as Unix timestamp
	 * @param integer $intTo      The end date as Unix timestamp
	 * @param array   $arrPids    An array of news archive IDs
	 * @param integer $intLimit   An optional limit
	 * @param integer $intOffset  An optional offset
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\NewsModel[]|\NewsModel|null A collection of models or null if there are no news
	 */
	public static function findPublishedFromToByPids($intFrom, $intTo, $arrPids, $intLimit=0, $intOffset=0, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.date>=? AND $t.date<=? AND $t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order']  = "$t.date DESC";
		}

		$arrOptions['limit']  = $intLimit;
		$arrOptions['offset'] = $intOffset;

		return static::findBy($arrColumns, array($intFrom, $intTo), $arrOptions);
	}


	/**
	 * Count all published news items of a certain period of time by their parent ID
	 *
	 * @param integer $intFrom    The start date as Unix timestamp
	 * @param integer $intTo      The end date as Unix timestamp
	 * @param array   $arrPids    An array of news archive IDs
	 * @param array   $arrOptions An optional options array
	 *
	 * @return integer The number of news items
	 */
	public static function countPublishedFromToByPids($intFrom, $intTo, $arrPids, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.date>=? AND $t.date<=? AND $t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
		}

		return static::countBy($arrColumns, array($intFrom, $intTo), $arrOptions);
	}
}
