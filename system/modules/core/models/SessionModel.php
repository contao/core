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
 * @property integer $id
 * @property integer $pid
 * @property integer $tstamp
 * @property string  $name
 * @property string  $sessionID
 * @property string  $hash
 * @property string  $ip
 * @property boolean $su
 *
 * @method static $this findById()
 * @method static $this findByPk()
 * @method static $this findByIdOrAlias()
 * @method static $this findOneBy()
 * @method static $this findByHash()
 * @method static $this findOneByPid()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByName()
 * @method static $this findOneBySessionID()
 * @method static $this findOneByIp()
 * @method static $this findOneBySu()
 *
 * @method static \Model\Collection|\SessionModel findByPid()
 * @method static \Model\Collection|\SessionModel findByTstamp()
 * @method static \Model\Collection|\SessionModel findByName()
 * @method static \Model\Collection|\SessionModel findBySessionID()
 * @method static \Model\Collection|\SessionModel findByIp()
 * @method static \Model\Collection|\SessionModel findBySu()
 * @method static \Model\Collection|\SessionModel findMultipleByIds()
 * @method static \Model\Collection|\SessionModel findBy()
 * @method static \Model\Collection|\SessionModel findAll()
 *
 * @method static integer countById()
 * @method static integer countByPid()
 * @method static integer countByTstamp()
 * @method static integer countByName()
 * @method static integer countBySessionID()
 * @method static integer countByHash()
 * @method static integer countByIp()
 * @method static integer countBySu()
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
	 * @return static The model or null if there is no session
	 */
	public static function findByHashAndName($strHash, $strName, array $arrOptions=array())
	{
		$t = static::$strTable;

		return static::findOneBy(array("$t.hash=?", "$t.name=?"), array($strHash, $strName), $arrOptions);
	}
}
