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
 * Reads and writes sessions
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class SessionModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_session';


	/**
	 * Find a session by its hash and name
	 *
	 * @param string $strHash    The session hash
	 * @param string $strName    The session name
	 * @param array  $arrOptions An optional options array
	 *
	 * @return \Model|null The model or null if there is no session
	 */
	public static function findByHashAndName($strHash, $strName, array $arrOptions=array())
	{
		$t = static::$strTable;
		return static::findOneBy(array("$t.hash=?", "$t.name=?"), array($strHash, $strName), $arrOptions);
	}
}
