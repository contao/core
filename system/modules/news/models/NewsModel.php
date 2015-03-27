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
 * @method static $this findById()
 * @method static $this findByPk()
 * @method static $this findByIdOrAlias()
 * @method static $this findOneBy()
 * @method static $this findOneByPid()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByHeadline()
 * @method static $this findOneByAlias()
 * @method static $this findOneByAuthor()
 * @method static $this findOneByDate()
 * @method static $this findOneByTime()
 * @method static $this findOneBySubheadline()
 * @method static $this findOneByTeaser()
 * @method static $this findOneByAddImage()
 * @method static $this findOneBySingleSRC()
 * @method static $this findOneByAlt()
 * @method static $this findOneBySize()
 * @method static $this findOneByImagemargin()
 * @method static $this findOneByImageUrl()
 * @method static $this findOneByFullsize()
 * @method static $this findOneByCaption()
 * @method static $this findOneByFloating()
 * @method static $this findOneByAddEnclosure()
 * @method static $this findOneByEnclosure()
 * @method static $this findOneBySource()
 * @method static $this findOneByJumpTo()
 * @method static $this findOneByArticleId()
 * @method static $this findOneByUrl()
 * @method static $this findOneByTarget()
 * @method static $this findOneByCssClass()
 * @method static $this findOneByNoComments()
 * @method static $this findOneByFeatured()
 * @method static $this findOneByPublished()
 * @method static $this findOneByStart()
 * @method static $this findOneByStop()
 *
 * @method static \Model\Collection|\NewsModel findByPid()
 * @method static \Model\Collection|\NewsModel findByTstamp()
 * @method static \Model\Collection|\NewsModel findByHeadline()
 * @method static \Model\Collection|\NewsModel findByAlias()
 * @method static \Model\Collection|\NewsModel findByAuthor()
 * @method static \Model\Collection|\NewsModel findByDate()
 * @method static \Model\Collection|\NewsModel findByTime()
 * @method static \Model\Collection|\NewsModel findBySubheadline()
 * @method static \Model\Collection|\NewsModel findByTeaser()
 * @method static \Model\Collection|\NewsModel findByAddImage()
 * @method static \Model\Collection|\NewsModel findBySingleSRC()
 * @method static \Model\Collection|\NewsModel findByAlt()
 * @method static \Model\Collection|\NewsModel findBySize()
 * @method static \Model\Collection|\NewsModel findByImagemargin()
 * @method static \Model\Collection|\NewsModel findByImageUrl()
 * @method static \Model\Collection|\NewsModel findByFullsize()
 * @method static \Model\Collection|\NewsModel findByCaption()
 * @method static \Model\Collection|\NewsModel findByFloating()
 * @method static \Model\Collection|\NewsModel findByAddEnclosure()
 * @method static \Model\Collection|\NewsModel findByEnclosure()
 * @method static \Model\Collection|\NewsModel findBySource()
 * @method static \Model\Collection|\NewsModel findByJumpTo()
 * @method static \Model\Collection|\NewsModel findByArticleId()
 * @method static \Model\Collection|\NewsModel findByUrl()
 * @method static \Model\Collection|\NewsModel findByTarget()
 * @method static \Model\Collection|\NewsModel findByCssClass()
 * @method static \Model\Collection|\NewsModel findByNoComments()
 * @method static \Model\Collection|\NewsModel findByFeatured()
 * @method static \Model\Collection|\NewsModel findByPublished()
 * @method static \Model\Collection|\NewsModel findByStart()
 * @method static \Model\Collection|\NewsModel findByStop()
 * @method static \Model\Collection|\NewsModel findMultipleByIds()
 * @method static \Model\Collection|\NewsModel findBy()
 * @method static \Model\Collection|\NewsModel findAll()
 *
 * @method static integer countById()
 * @method static integer countByPid()
 * @method static integer countByTstamp()
 * @method static integer countByHeadline()
 * @method static integer countByAlias()
 * @method static integer countByAuthor()
 * @method static integer countByDate()
 * @method static integer countByTime()
 * @method static integer countBySubheadline()
 * @method static integer countByTeaser()
 * @method static integer countByAddImage()
 * @method static integer countBySingleSRC()
 * @method static integer countByAlt()
 * @method static integer countBySize()
 * @method static integer countByImagemargin()
 * @method static integer countByImageUrl()
 * @method static integer countByFullsize()
 * @method static integer countByCaption()
 * @method static integer countByFloating()
 * @method static integer countByAddEnclosure()
 * @method static integer countByEnclosure()
 * @method static integer countBySource()
 * @method static integer countByJumpTo()
 * @method static integer countByArticleId()
 * @method static integer countByUrl()
 * @method static integer countByTarget()
 * @method static integer countByCssClass()
 * @method static integer countByNoComments()
 * @method static integer countByFeatured()
 * @method static integer countByPublished()
 * @method static integer countByStart()
 * @method static integer countByStop()
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
