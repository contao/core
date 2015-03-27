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
 * Reads and writes member groups
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $name
 * @property boolean $redirect
 * @property integer $jumpTo
 * @property boolean $disable
 * @property string  $start
 * @property string  $stop
 *
 * @method static $this findById()
 * @method static $this findByPk()
 * @method static $this findByIdOrAlias()
 * @method static $this findOneBy()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByName()
 * @method static $this findOneByRedirect()
 * @method static $this findOneByJumpTo()
 * @method static $this findOneByDisable()
 * @method static $this findOneByStart()
 * @method static $this findOneByStop()
 *
 * @method static \Model\Collection|\MemberGroupModel findByTstamp()
 * @method static \Model\Collection|\MemberGroupModel findByName()
 * @method static \Model\Collection|\MemberGroupModel findByRedirect()
 * @method static \Model\Collection|\MemberGroupModel findByJumpTo()
 * @method static \Model\Collection|\MemberGroupModel findByDisable()
 * @method static \Model\Collection|\MemberGroupModel findByStart()
 * @method static \Model\Collection|\MemberGroupModel findByStop()
 * @method static \Model\Collection|\MemberGroupModel findMultipleByIds()
 * @method static \Model\Collection|\MemberGroupModel findBy()
 * @method static \Model\Collection|\MemberGroupModel findAll()
 *
 * @method static integer countById()
 * @method static integer countByTstamp()
 * @method static integer countByName()
 * @method static integer countByRedirect()
 * @method static integer countByJumpTo()
 * @method static integer countByDisable()
 * @method static integer countByStart()
 * @method static integer countByStop()
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class MemberGroupModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_member_group';


	/**
	 * Find a published group by its ID
	 *
	 * @param integer $intId      The member group ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return static The model or null if there is no member group
	 */
	public static function findPublishedById($intId, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.id=?");

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.disable=''";
		}

		return static::findOneBy($arrColumns, $intId, $arrOptions);
	}


	/**
	 * Find the first active group with a published jumpTo page
	 *
	 * @param string $arrIds An array of member group IDs
	 *
	 * @return static The model or null if there is no matching member group
	 */
	public static function findFirstActiveWithJumpToByIds($arrIds)
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$time = \Date::floorToMinute();
		$objDatabase = \Database::getInstance();
		$arrIds = array_map('intval', $arrIds);

		$objResult = $objDatabase->prepare("SELECT p.* FROM tl_member_group g LEFT JOIN tl_page p ON g.jumpTo=p.id WHERE g.id IN(" . implode(',', $arrIds) . ") AND g.jumpTo>0 AND g.redirect='1' AND g.disable!='1' AND (g.start='' OR g.start<='$time') AND (g.stop='' OR g.stop>'" . ($time + 60) . "') AND p.published='1' AND (p.start='' OR p.start<='$time') AND (p.stop='' OR p.stop>'" . ($time + 60) . "') ORDER BY " . $objDatabase->findInSet('g.id', $arrIds))
								 ->limit(1)
								 ->execute();

		if ($objResult->numRows < 1)
		{
			return null;
		}

		return new static($objResult);
	}


	/**
	 * Find all active groups
	 *
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\MemberGroupModel|null A collection of models or null if there are no member groups
	 */
	public static function findAllActive(array $arrOptions=array())
	{
		$t = static::$strTable;
		$time = \Date::floorToMinute();

		return static::findBy(array("$t.disable='' AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "')"), null, $arrOptions);
	}
}
