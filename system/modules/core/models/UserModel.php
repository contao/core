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
 * Reads and writes users
 * 
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2011-2012
 */
class UserModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_user';


	/**
	 * Find all users having a specific group
	 *
	 * @param int $groupID a tl_user_group.id
	 * @return \Model\Collection|null A collection of models or null if there are no members having the group
	 */
	public static function finyByGroup($groupID)
	{
		$t = static::$strTable;
		return static::findBy(array("$t.groups LIKE ?"), '%"'.$groupID.'"%');
	}
}
