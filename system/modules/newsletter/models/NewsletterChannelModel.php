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
 * @method static $this findById($id, $opt=array())
 * @method static $this findByPk($id, $opt=array())
 * @method static $this findByIdOrAlias($val, $opt=array())
 * @method static $this findOneBy($col, $val, $opt=array())
 * @method static $this findOneByTstamp($val, $opt=array())
 * @method static $this findOneByTitle($val, $opt=array())
 * @method static $this findOneByJumpTo($val, $opt=array())
 * @method static $this findOneByUseSMTP($val, $opt=array())
 * @method static $this findOneBySmtpHost($val, $opt=array())
 * @method static $this findOneBySmtpUser($val, $opt=array())
 * @method static $this findOneBySmtpPass($val, $opt=array())
 * @method static $this findOneBySmtpEnc($val, $opt=array())
 * @method static $this findOneBySmtpPort($val, $opt=array())
 *
 * @method static \Model\Collection|\NewsletterChannelModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel findByTitle($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel findByJumpTo($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel findByUseSMTP($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel findBySmtpHost($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel findBySmtpUser($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel findBySmtpPass($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel findBySmtpEnc($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel findBySmtpPort($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\NewsletterChannelModel findAll($opt=array())
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
	 * @return \Model\Collection|\NewsletterChannelModel|null A collection of models or null if there are no newsletter channels
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
