<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
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
 * @method static $this findById($id, $opt=array())
 * @method static $this findByPk($id, $opt=array())
 * @method static $this findByIdOrAlias($val, $opt=array())
 * @method static $this findOneBy($col, $val, $opt=array())
 * @method static $this findOneByPid($val, $opt=array())
 * @method static $this findOneByTstamp($val, $opt=array())
 * @method static $this findOneByHeadline($val, $opt=array())
 * @method static $this findOneByAlias($val, $opt=array())
 * @method static $this findOneByAuthor($val, $opt=array())
 * @method static $this findOneByDate($val, $opt=array())
 * @method static $this findOneByTime($val, $opt=array())
 * @method static $this findOneBySubheadline($val, $opt=array())
 * @method static $this findOneByTeaser($val, $opt=array())
 * @method static $this findOneByAddImage($val, $opt=array())
 * @method static $this findOneBySingleSRC($val, $opt=array())
 * @method static $this findOneByAlt($val, $opt=array())
 * @method static $this findOneBySize($val, $opt=array())
 * @method static $this findOneByImagemargin($val, $opt=array())
 * @method static $this findOneByImageUrl($val, $opt=array())
 * @method static $this findOneByFullsize($val, $opt=array())
 * @method static $this findOneByCaption($val, $opt=array())
 * @method static $this findOneByFloating($val, $opt=array())
 * @method static $this findOneByAddEnclosure($val, $opt=array())
 * @method static $this findOneByEnclosure($val, $opt=array())
 * @method static $this findOneBySource($val, $opt=array())
 * @method static $this findOneByJumpTo($val, $opt=array())
 * @method static $this findOneByArticleId($val, $opt=array())
 * @method static $this findOneByUrl($val, $opt=array())
 * @method static $this findOneByTarget($val, $opt=array())
 * @method static $this findOneByCssClass($val, $opt=array())
 * @method static $this findOneByNoComments($val, $opt=array())
 * @method static $this findOneByFeatured($val, $opt=array())
 * @method static $this findOneByPublished($val, $opt=array())
 * @method static $this findOneByStart($val, $opt=array())
 * @method static $this findOneByStop($val, $opt=array())
 *
 * @method static \Model\Collection|\NewsModel findByPid($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByHeadline($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByAlias($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByAuthor($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByDate($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByTime($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findBySubheadline($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByTeaser($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByAddImage($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findBySingleSRC($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByAlt($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findBySize($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByImagemargin($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByImageUrl($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByFullsize($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByCaption($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByFloating($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByAddEnclosure($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByEnclosure($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findBySource($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByJumpTo($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByArticleId($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByUrl($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByTarget($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByCssClass($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByNoComments($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByFeatured($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByPublished($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByStart($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findByStop($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\NewsModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\NewsModel findAll($opt=array())
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
	 * Find published news items by their parent ID and ID or alias
	 *
	 * @param mixed $varId      The numeric ID or alias name
	 * @param array $arrPids    An array of parent IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return static The NewsModel or null if there are no news
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

		return static::findBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId), $arrOptions);
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
	 * @return \Model\Collection|\NewsModel|null A collection of models or null if there are no news
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
	 * @return \Model\Collection|\NewsModel|null A collection of models or null if there are no news
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
	 * @return \Model\Collection|\NewsModel|null A collection of models or null if there are no news
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
	 * @return \Model\Collection|\NewsModel|null A collection of models or null if there are no news
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
