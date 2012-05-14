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
 * Reads and writes sessions
 * 
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2011-2012
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
	 * @param string $strHash The session hash
	 * @param string $strName The session name
	 * 
	 * @return \Model|null The model or null if there is no session
	 */
	public static function findByHashAndName($strHash, $strName)
	{
		$t = static::$strTable;
		return static::findOneBy(array("$t.hash=?", "$t.name=?"), array($strHash, $strName));
	}
}
