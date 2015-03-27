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
 * Reads and writes articles
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $sorting
 * @property integer $tstamp
 * @property string  $title
 * @property string  $alias
 * @property integer $author
 * @property string  $inColumn
 * @property string  $keywords
 * @property boolean $showTeaser
 * @property string  $teaserCssID
 * @property string  $teaser
 * @property string  $printable
 * @property string  $customTpl
 * @property boolean $protected
 * @property string  $groups
 * @property boolean $guests
 * @property string  $cssID
 * @property string  $space
 * @property boolean $published
 * @property string  $start
 * @property string  $stop
 * @property string  $classes
 *
 * @method static $this findById()
 * @method static $this findByPk()
 * @method static $this findByIdOrAlias()
 * @method static $this findOneBy()
 * @method static $this findOneByPid()
 * @method static $this findOneBySorting()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByTitle()
 * @method static $this findOneByAlias()
 * @method static $this findOneByAuthor()
 * @method static $this findOneByInColumn()
 * @method static $this findOneByKeywords()
 * @method static $this findOneByShowTeaser()
 * @method static $this findOneByTeaserCssID()
 * @method static $this findOneByTeaser()
 * @method static $this findOneByPrintable()
 * @method static $this findOneByCustomTpl()
 * @method static $this findOneByProtected()
 * @method static $this findOneByGroups()
 * @method static $this findOneByGuests()
 * @method static $this findOneByCssID()
 * @method static $this findOneBySpace()
 * @method static $this findOneByPublished()
 * @method static $this findOneByStart()
 * @method static $this findOneByStop()
 *
 * @method static \Model\Collection|\ArticleModel findByPid()
 * @method static \Model\Collection|\ArticleModel findBySorting()
 * @method static \Model\Collection|\ArticleModel findByTstamp()
 * @method static \Model\Collection|\ArticleModel findByTitle()
 * @method static \Model\Collection|\ArticleModel findByAlias()
 * @method static \Model\Collection|\ArticleModel findByAuthor()
 * @method static \Model\Collection|\ArticleModel findByInColumn()
 * @method static \Model\Collection|\ArticleModel findByKeywords()
 * @method static \Model\Collection|\ArticleModel findByShowTeaser()
 * @method static \Model\Collection|\ArticleModel findByTeaserCssID()
 * @method static \Model\Collection|\ArticleModel findByTeaser()
 * @method static \Model\Collection|\ArticleModel findByPrintable()
 * @method static \Model\Collection|\ArticleModel findByCustomTpl()
 * @method static \Model\Collection|\ArticleModel findByProtected()
 * @method static \Model\Collection|\ArticleModel findByGroups()
 * @method static \Model\Collection|\ArticleModel findByGuests()
 * @method static \Model\Collection|\ArticleModel findByCssID()
 * @method static \Model\Collection|\ArticleModel findBySpace()
 * @method static \Model\Collection|\ArticleModel findByPublished()
 * @method static \Model\Collection|\ArticleModel findByStart()
 * @method static \Model\Collection|\ArticleModel findByStop()
 * @method static \Model\Collection|\ArticleModel findMultipleByIds()
 * @method static \Model\Collection|\ArticleModel findBy()
 * @method static \Model\Collection|\ArticleModel findAll()
 *
 * @method static integer countById()
 * @method static integer countByPid()
 * @method static integer countBySorting()
 * @method static integer countByTstamp()
 * @method static integer countByTitle()
 * @method static integer countByAlias()
 * @method static integer countByAuthor()
 * @method static integer countByInColumn()
 * @method static integer countByKeywords()
 * @method static integer countByShowTeaser()
 * @method static integer countByTeaserCssID()
 * @method static integer countByTeaser()
 * @method static integer countByPrintable()
 * @method static integer countByCustomTpl()
 * @method static integer countByProtected()
 * @method static integer countByGroups()
 * @method static integer countByGuests()
 * @method static integer countByCssID()
 * @method static integer countBySpace()
 * @method static integer countByPublished()
 * @method static integer countByStart()
 * @method static integer countByStop()
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ArticleModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_article';


	/**
	 * Find an article by its ID or alias and its page
	 *
	 * @param mixed   $varId      The numeric ID or alias name
	 * @param integer $intPid     The page ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return static The model or null if there is no article
	 */
	public static function findByIdOrAliasAndPid($varId, $intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("($t.id=? OR $t.alias=?)");
		$arrValues = array((is_numeric($varId) ? $varId : 0), $varId);

		if ($intPid)
		{
			$arrColumns[] = "$t.pid=?";
			$arrValues[] = $intPid;
		}

		return static::findOneBy($arrColumns, $arrValues, $arrOptions);
	}


	/**
	 * Find a published article by its ID
	 *
	 * @param integer $intId      The article ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return static The model or null if there is no published article
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
	 * Find all published articles by their parent ID and column
	 *
	 * @param integer $intPid     The page ID
	 * @param string  $strColumn  The column name
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\ArticleModel|null A collection of models or null if there are no articles in the given column
	 */
	public static function findPublishedByPidAndColumn($intPid, $strColumn, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.inColumn=?");
		$arrValues = array($intPid, $strColumn);

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting";
		}

		return static::findBy($arrColumns, $arrValues, $arrOptions);
	}


	/**
	 * Find all published articles with teaser by their parent ID and column
	 *
	 * @param integer $intPid     The page ID
	 * @param string  $strColumn  The column name
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\ArticleModel|null A collection of models or null if there are no articles in the given column
	 */
	public static function findPublishedWithTeaserByPidAndColumn($intPid, $strColumn, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.inColumn=? AND $t.showTeaser=1");
		$arrValues = array($intPid, $strColumn);

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting";
		}

		return static::findBy($arrColumns, $arrValues, $arrOptions);
	}
}
