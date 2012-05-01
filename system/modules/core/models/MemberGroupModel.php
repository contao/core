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
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \Database, \Model;


/**
 * Class MemberGroupModel
 *
 * Provide methods to find and save member groups.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Core
 */
class MemberGroupModel extends Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_member_group';


	/**
	 * Find a published group by its ID
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
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.disable=''";
		}

		return static::findOneBy($arrColumns, $intId);
	}


	/**
	 * Find the first active group with a published jumpTo page
	 * @param string
	 * @return \Model|null
	 */
	public static function findFirstActiveWithJumpToByIds($arrIds)
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$time = time();
		$objDatabase = Database::getInstance();
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
	 * @return \Model_Collection|null
	 */
	public static function findAllActive()
	{
		$time = time();
		$t = static::$strTable;

		return static::findBy(array("$t.disable='' AND ($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time)"), null);
	}
}
