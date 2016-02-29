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
 * Reads and writes newsletter channels
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $title
 * @property integer $jumpTo
 * @property boolean $useSMTP
 * @property string  $smtpHost
 * @property string  $smtpUser
 * @property string  $smtpPass
 * @property string  $smtpEnc
 * @property integer $smtpPort
 *
 * @method static \NewsletterChannelModel|null findById($id, $opt=array())
 * @method static \NewsletterChannelModel|null findByPk($id, $opt=array())
 * @method static \NewsletterChannelModel|null findByIdOrAlias($val, $opt=array())
 * @method static \NewsletterChannelModel|null findOneBy($col, $val, $opt=array())
 * @method static \NewsletterChannelModel|null findOneByTstamp($val, $opt=array())
 * @method static \NewsletterChannelModel|null findOneByTitle($val, $opt=array())
 * @method static \NewsletterChannelModel|null findOneByJumpTo($val, $opt=array())
 * @method static \NewsletterChannelModel|null findOneByUseSMTP($val, $opt=array())
 * @method static \NewsletterChannelModel|null findOneBySmtpHost($val, $opt=array())
 * @method static \NewsletterChannelModel|null findOneBySmtpUser($val, $opt=array())
 * @method static \NewsletterChannelModel|null findOneBySmtpPass($val, $opt=array())
 * @method static \NewsletterChannelModel|null findOneBySmtpEnc($val, $opt=array())
 * @method static \NewsletterChannelModel|null findOneBySmtpPort($val, $opt=array())
 *
 * @method static \Model\Collection|\NewsletterChannelModel[]|\NewsletterChannelModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel[]|\NewsletterChannelModel|null findByTitle($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel[]|\NewsletterChannelModel|null findByJumpTo($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel[]|\NewsletterChannelModel|null findByUseSMTP($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel[]|\NewsletterChannelModel|null findBySmtpHost($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel[]|\NewsletterChannelModel|null findBySmtpUser($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel[]|\NewsletterChannelModel|null findBySmtpPass($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel[]|\NewsletterChannelModel|null findBySmtpEnc($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel[]|\NewsletterChannelModel|null findBySmtpPort($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel[]|\NewsletterChannelModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel[]|\NewsletterChannelModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel[]|\NewsletterChannelModel|null findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByTitle($val, $opt=array())
 * @method static integer countByJumpTo($val, $opt=array())
 * @method static integer countByUseSMTP($val, $opt=array())
 * @method static integer countBySmtpHost($val, $opt=array())
 * @method static integer countBySmtpUser($val, $opt=array())
 * @method static integer countBySmtpPass($val, $opt=array())
 * @method static integer countBySmtpEnc($val, $opt=array())
 * @method static integer countBySmtpPort($val, $opt=array())
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class NewsletterChannelModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_newsletter_channel';


	/**
	 * Find multiple newsletter channels by their IDs
	 *
	 * @param array $arrIds     An array of newsletter channel IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\NewsletterChannelModel[]|\NewsletterChannelModel|null A collection of models or null if there are no newsletter channels
	 */
	public static function findByIds($arrIds, array $arrOptions=array())
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$t = static::$strTable;

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.title";
		}

		return static::findBy(array("$t.id IN(" . implode(',', array_map('intval', $arrIds)) . ")"), null, $arrOptions);
	}
}
