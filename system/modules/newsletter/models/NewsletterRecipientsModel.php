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
 * Reads and writes newsletter recipients
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $tstamp
 * @property string  $email
 * @property boolean $active
 * @property string  $source
 * @property string  $addedOn
 * @property string  $confirmed
 * @property string  $ip
 * @property string  $token
 *
 * @method static \NewsletterRecipientsModel|null findById($id, $opt=array())
 * @method static \NewsletterRecipientsModel|null findByPk($id, $opt=array())
 * @method static \NewsletterRecipientsModel|null findByIdOrAlias($val, $opt=array())
 * @method static \NewsletterRecipientsModel|null findOneBy($col, $val, $opt=array())
 * @method static \NewsletterRecipientsModel|null findOneByPid($val, $opt=array())
 * @method static \NewsletterRecipientsModel|null findOneByTstamp($val, $opt=array())
 * @method static \NewsletterRecipientsModel|null findOneByEmail($val, $opt=array())
 * @method static \NewsletterRecipientsModel|null findOneByActive($val, $opt=array())
 * @method static \NewsletterRecipientsModel|null findOneBySource($val, $opt=array())
 * @method static \NewsletterRecipientsModel|null findOneByAddedOn($val, $opt=array())
 * @method static \NewsletterRecipientsModel|null findOneByConfirmed($val, $opt=array())
 * @method static \NewsletterRecipientsModel|null findOneByIp($val, $opt=array())
 * @method static \NewsletterRecipientsModel|null findOneByToken($val, $opt=array())
 *
 * @method static \Model\Collection|\NewsletterRecipientsModel[]|\NewsletterRecipientsModel|null findByPid($val, $opt=array())
 * @method static \Model\Collection|\NewsletterRecipientsModel[]|\NewsletterRecipientsModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\NewsletterRecipientsModel[]|\NewsletterRecipientsModel|null findByEmail($val, $opt=array())
 * @method static \Model\Collection|\NewsletterRecipientsModel[]|\NewsletterRecipientsModel|null findByActive($val, $opt=array())
 * @method static \Model\Collection|\NewsletterRecipientsModel[]|\NewsletterRecipientsModel|null findBySource($val, $opt=array())
 * @method static \Model\Collection|\NewsletterRecipientsModel[]|\NewsletterRecipientsModel|null findByAddedOn($val, $opt=array())
 * @method static \Model\Collection|\NewsletterRecipientsModel[]|\NewsletterRecipientsModel|null findByConfirmed($val, $opt=array())
 * @method static \Model\Collection|\NewsletterRecipientsModel[]|\NewsletterRecipientsModel|null findByIp($val, $opt=array())
 * @method static \Model\Collection|\NewsletterRecipientsModel[]|\NewsletterRecipientsModel|null findByToken($val, $opt=array())
 * @method static \Model\Collection|\NewsletterRecipientsModel[]|\NewsletterRecipientsModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\NewsletterRecipientsModel[]|\NewsletterRecipientsModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\NewsletterRecipientsModel[]|\NewsletterRecipientsModel|null findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByPid($val, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByEmail($val, $opt=array())
 * @method static integer countByActive($val, $opt=array())
 * @method static integer countBySource($val, $opt=array())
 * @method static integer countByAddedOn($val, $opt=array())
 * @method static integer countByConfirmed($val, $opt=array())
 * @method static integer countByIp($val, $opt=array())
 * @method static integer countByToken($val, $opt=array())
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class NewsletterRecipientsModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_newsletter_recipients';


	/**
	 * Find recipients by their e-mail address and parent ID
	 *
	 * @param string $strEmail   The e-mail address
	 * @param array  $arrPids    An array of newsletter channel IDs
	 * @param array  $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\NewsletterRecipientsModel[]|\NewsletterRecipientsModel|null A collection of models or null if there are no recipients
	 */
	public static function findByEmailAndPids($strEmail, $arrPids, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;

		return static::findBy(array("$t.email=? AND $t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")"), $strEmail, $arrOptions);
	}
}
