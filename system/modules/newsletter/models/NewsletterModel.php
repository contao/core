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
 * Reads and writes newsletters
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $tstamp
 * @property string  $subject
 * @property string  $alias
 * @property string  $content
 * @property string  $text
 * @property boolean $addFile
 * @property string  $files
 * @property string  $template
 * @property boolean $sendText
 * @property boolean $externalImages
 * @property string  $sender
 * @property string  $senderName
 * @property boolean $sent
 * @property string  $date
 * @property integer $channel
 *
 * @method static $this findById()
 * @method static $this findByPk()
 * @method static $this findByIdOrAlias()
 * @method static $this findOneBy()
 * @method static $this findOneByPid()
 * @method static $this findOneByTstamp()
 * @method static $this findOneBySubject()
 * @method static $this findOneByAlias()
 * @method static $this findOneByContent()
 * @method static $this findOneByText()
 * @method static $this findOneByAddFile()
 * @method static $this findOneByFiles()
 * @method static $this findOneByTemplate()
 * @method static $this findOneBySendText()
 * @method static $this findOneByExternalImages()
 * @method static $this findOneBySender()
 * @method static $this findOneBySenderName()
 * @method static $this findOneBySent()
 * @method static $this findOneByDate()
 *
 * @method static \Model\Collection|\NewsletterModel findByPid()
 * @method static \Model\Collection|\NewsletterModel findByTstamp()
 * @method static \Model\Collection|\NewsletterModel findBySubject()
 * @method static \Model\Collection|\NewsletterModel findByAlias()
 * @method static \Model\Collection|\NewsletterModel findByContent()
 * @method static \Model\Collection|\NewsletterModel findByText()
 * @method static \Model\Collection|\NewsletterModel findByAddFile()
 * @method static \Model\Collection|\NewsletterModel findByFiles()
 * @method static \Model\Collection|\NewsletterModel findByTemplate()
 * @method static \Model\Collection|\NewsletterModel findBySendText()
 * @method static \Model\Collection|\NewsletterModel findByExternalImages()
 * @method static \Model\Collection|\NewsletterModel findBySender()
 * @method static \Model\Collection|\NewsletterModel findBySenderName()
 * @method static \Model\Collection|\NewsletterModel findBySent()
 * @method static \Model\Collection|\NewsletterModel findByDate()
 * @method static \Model\Collection|\NewsletterModel findMultipleByIds()
 * @method static \Model\Collection|\NewsletterModel findBy()
 * @method static \Model\Collection|\NewsletterModel findAll()
 *
 * @method static integer countById()
 * @method static integer countByPid()
 * @method static integer countByTstamp()
 * @method static integer countBySubject()
 * @method static integer countByAlias()
 * @method static integer countByContent()
 * @method static integer countByText()
 * @method static integer countByAddFile()
 * @method static integer countByFiles()
 * @method static integer countByTemplate()
 * @method static integer countBySendText()
 * @method static integer countByExternalImages()
 * @method static integer countBySender()
 * @method static integer countBySenderName()
 * @method static integer countBySent()
 * @method static integer countByDate()
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class NewsletterModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_newsletter';


	/**
	 * Find sent newsletters by their parent IDs and their ID or alias
	 *
	 * @param integer $varId      The numeric ID or alias name
	 * @param array   $arrPids    An array of newsletter channel IDs
	 * @param array   $arrOptions An optional options array
	 *
	 * @return static A collection of models or null if there are no sent newsletters
	 */
	public static function findSentByParentAndIdOrAlias($varId, $arrPids, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("($t.id=? OR $t.alias=?) AND $t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.sent=1";
		}

		return static::findOneBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId), $arrOptions);
	}


	/**
	 * Find sent newsletters by their parent ID
	 *
	 * @param integer $intPid     The newsletter channel ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\NewsletterModel|null A collection of models or null if there are no sent newsletters
	 */
	public static function findSentByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=?");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.sent=1";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.date DESC";
		}

		return static::findBy($arrColumns, $intPid, $arrOptions);
	}


	/**
	 * Find sent newsletters by multiple parent IDs
	 *
	 * @param array $arrPids    An array of newsletter channel IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\NewsletterModel|null A collection of models or null if there are no sent newsletters
	 */
	public static function findSentByPids($arrPids, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.sent=1";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.date DESC";
		}

		return static::findBy($arrColumns, null, $arrOptions);
	}
}
