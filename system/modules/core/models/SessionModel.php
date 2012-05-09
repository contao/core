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
 * Class SessionModel
 *
 * Provide methods to find and save sessions.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Core
 */
class SessionModel extends \Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_session';


	/**
	 * Find a session by its hash and name
	 * @param string
	 * @param string
	 * @return \Model|null
	 */
	public static function findByHashAndName($strHash, $strName)
	{
		$t = static::$strTable;
		return static::findOneBy(array("$t.hash=?", "$t.name=?"), array($strHash, $strName));
	}
}
