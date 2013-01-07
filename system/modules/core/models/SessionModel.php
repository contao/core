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
 * Reads and writes sessions
 * 
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
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
