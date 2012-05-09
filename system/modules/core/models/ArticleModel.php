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


/**
 * Class ArticleModel
 *
 * Provide methods to find and save articles.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Core
 */
class ArticleModel extends \Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_article';


	/**
	 * Find an article by its ID or alias and its page
	 * @param mixed
	 * @param integer
	 * @return \Model|null
	 */
	public static function findByIdOrAliasAndPid($varId, $intPid)
	{
		$t = static::$strTable;
		$arrColumns = array("($t.id=? OR $t.alias=?)");
		$arrValues = array((is_numeric($varId) ? $varId : 0), $varId);

		if ($intPid)
		{
			$arrColumns[] = "$t.pid=?";
			$arrValues[] = $intPid;
		}

		return static::findOneBy($arrColumns, $arrValues);
	}


	/**
	 * Find a published article by its ID
	 * @param integer
	 * @return \Model|null
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
	 * Find all published articles by their parent ID and column
	 * @param integer
	 * @param string
	 * @return \Model_Collection|null
	 */
	public static function findPublishedByPidAndColumn($intPid, $strColumn)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.inColumn=?");
		$arrValues = array($intPid, $strColumn);

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findBy($arrColumns, $arrValues, array('order'=>"$t.sorting"));
	}


	/**
	 * Find all published articles with teaser by their parent ID and column
	 * @param integer
	 * @param string
	 * @return \Model_Collection|null
	 */
	public static function findPublishedWithTeaserByPidAndColumn($intPid, $strColumn)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.inColumn=? AND $t.showTeaser=1");
		$arrValues = array($intPid, $strColumn);

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findBy($arrColumns, $arrValues, array('order'=>"$t.sorting"));
	}
}
