<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Core
 * @link    http://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Reads and writes members
 * 
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2011-2012
 */
class MemberModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_member';


	/**
	 * Find an active member by his/her e-mail-address and username
	 * 
	 * @param string $strEmail    The e-mail address
	 * @param string $strUsername The username
	 * 
	 * @return \Model|null The model or null if there is no member
	 */
	public static function findActiveByEmailAndUsername($strEmail, $strUsername=null)
	{
		$time = time();
		$t = static::$strTable;

		$arrColumns = array("$t.email=? AND $t.login=1 AND ($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.disable=''");

		if ($strUsername !== null)
		{
			$arrColumns[] = "$t.username=?";
		}

		return static::findOneBy($arrColumns, array($strEmail, $strUsername));
	}


	/**
	 * Find all users having a specific group
	 *
	 * @param int $groupID a tl_member_group.id
	 * @return \Model\Collection|null A collection of models or null if there are no members having the group
	 */
	public static function finyByGroup($groupID)
	{
		$t = static::$strTable;
		return static::findBy(array("$t.groups LIKE ?"), '%"'.$groupID.'"%');
	}
}
