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
 * Reads and writes members
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
	 * @param array  $arrOptions  An optional options array
	 *
	 * @return \Model|null The model or null if there is no member
	 */
	public static function findActiveByEmailAndUsername($strEmail, $strUsername=null, array $arrOptions=array())
	{
		$time = time();
		$t = static::$strTable;

		$arrColumns = array("$t.email=? AND $t.login=1 AND ($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.disable=''");

		if ($strUsername !== null)
		{
			$arrColumns[] = "$t.username=?";
		}

		return static::findOneBy($arrColumns, array($strEmail, $strUsername), $arrOptions);
	}
}
