<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Reads and writes sessions
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $tstamp
 * @property string  $name
 * @property string  $sessionID
 * @property string  $hash
 * @property string  $ip
 * @property boolean $su
 *
 * @method static \SessionModel|null findById($id, $opt=array())
 * @method static \SessionModel|null findByPk($id, $opt=array())
 * @method static \SessionModel|null findByIdOrAlias($val, $opt=array())
 * @method static \SessionModel|null findOneBy($col, $val, $opt=array())
 * @method static \SessionModel|null findByHash($val, $opt=array())
 * @method static \SessionModel|null findOneByPid($val, $opt=array())
 * @method static \SessionModel|null findOneByTstamp($val, $opt=array())
 * @method static \SessionModel|null findOneByName($val, $opt=array())
 * @method static \SessionModel|null findOneBySessionID($val, $opt=array())
 * @method static \SessionModel|null findOneByIp($val, $opt=array())
 * @method static \SessionModel|null findOneBySu($val, $opt=array())
 *
 * @method static \Model\Collection|\SessionModel[]|\SessionModel|null findByPid($val, $opt=array())
 * @method static \Model\Collection|\SessionModel[]|\SessionModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\SessionModel[]|\SessionModel|null findByName($val, $opt=array())
 * @method static \Model\Collection|\SessionModel[]|\SessionModel|null findBySessionID($val, $opt=array())
 * @method static \Model\Collection|\SessionModel[]|\SessionModel|null findByIp($val, $opt=array())
 * @method static \Model\Collection|\SessionModel[]|\SessionModel|null findBySu($val, $opt=array())
 * @method static \Model\Collection|\SessionModel[]|\SessionModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\SessionModel[]|\SessionModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\SessionModel[]|\SessionModel|null findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByPid($val, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByName($val, $opt=array())
 * @method static integer countBySessionID($val, $opt=array())
 * @method static integer countByHash($val, $opt=array())
 * @method static integer countByIp($val, $opt=array())
 * @method static integer countBySu($val, $opt=array())
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
	 * @return \SessionModel|null The model or null if there is no session
	 */
	public static function findByHashAndName($strHash, $strName, array $arrOptions=array())
	{
		$t = static::$strTable;

		return static::findOneBy(array("$t.hash=?", "$t.name=?"), array($strHash, $strName), $arrOptions);
	}
}
