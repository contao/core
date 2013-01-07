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
 * Reads and writes member groups
 * 
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
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
	 * @return \Model|null The model or null if there is no member group
	 */
	public static function findPublishedById($intId, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.id=?");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.disable=''";
		}

		return static::findOneBy($arrColumns, $intId, $arrOptions);
	}


	/**
	 * Find the first active group with a published jumpTo page
	 * 
	 * @param string $arrIds An array of member group IDs
	 * 
	 * @return \Model|null The model or null if there is no matching member group
	 */
	public static function findFirstActiveWithJumpToByIds($arrIds)
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$time = time();
		$objDatabase = \Database::getInstance();
		$arrIds = array_map('intval', $arrIds);

		$objResult = $objDatabase->prepare("SELECT p.* FROM tl_member_group g LEFT JOIN tl_page p ON g.jumpTo=p.id WHERE g.id IN(" . implode(',', $arrIds) . ") AND g.jumpTo>0 AND g.redirect=1 AND g.disable!=1 AND (g.start='' OR g.start<$time) AND (g.stop='' OR g.stop>$time) AND p.published=1 AND (p.start='' OR p.start<$time) AND (p.stop='' OR p.stop>$time) ORDER BY " . $objDatabase->findInSet('g.id', $arrIds))
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
	 * @return \Model\Collection|null A collection of models or null if there are no member groups
	 */
	public static function findAllActive(array $arrOptions=array())
	{
		$time = time();
		$t = static::$strTable;

		return static::findBy(array("$t.disable='' AND ($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time)"), null, $arrOptions);
	}
}
