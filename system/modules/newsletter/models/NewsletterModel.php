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
 * @method static \NewsletterModel findById($id, $opt=array())
 * @method static \NewsletterModel findByPk($id, $opt=array())
 * @method static \NewsletterModel findByIdOrAlias($val, $opt=array())
 * @method static \NewsletterModel findOneBy($col, $val, $opt=array())
 * @method static \NewsletterModel findOneByPid($val, $opt=array())
 * @method static \NewsletterModel findOneByTstamp($val, $opt=array())
 * @method static \NewsletterModel findOneBySubject($val, $opt=array())
 * @method static \NewsletterModel findOneByAlias($val, $opt=array())
 * @method static \NewsletterModel findOneByContent($val, $opt=array())
 * @method static \NewsletterModel findOneByText($val, $opt=array())
 * @method static \NewsletterModel findOneByAddFile($val, $opt=array())
 * @method static \NewsletterModel findOneByFiles($val, $opt=array())
 * @method static \NewsletterModel findOneByTemplate($val, $opt=array())
 * @method static \NewsletterModel findOneBySendText($val, $opt=array())
 * @method static \NewsletterModel findOneByExternalImages($val, $opt=array())
 * @method static \NewsletterModel findOneBySender($val, $opt=array())
 * @method static \NewsletterModel findOneBySenderName($val, $opt=array())
 * @method static \NewsletterModel findOneBySent($val, $opt=array())
 * @method static \NewsletterModel findOneByDate($val, $opt=array())
 *
 * @method static \Model\Collection|\NewsletterModel findByPid($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel findBySubject($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel findByAlias($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel findByContent($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel findByText($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel findByAddFile($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel findByFiles($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel findByTemplate($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel findBySendText($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel findByExternalImages($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel findBySender($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel findBySenderName($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel findBySent($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel findByDate($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByPid($val, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countBySubject($val, $opt=array())
 * @method static integer countByAlias($val, $opt=array())
 * @method static integer countByContent($val, $opt=array())
 * @method static integer countByText($val, $opt=array())
 * @method static integer countByAddFile($val, $opt=array())
 * @method static integer countByFiles($val, $opt=array())
 * @method static integer countByTemplate($val, $opt=array())
 * @method static integer countBySendText($val, $opt=array())
 * @method static integer countByExternalImages($val, $opt=array())
 * @method static integer countBySender($val, $opt=array())
 * @method static integer countBySenderName($val, $opt=array())
 * @method static integer countBySent($val, $opt=array())
 * @method static integer countByDate($val, $opt=array())
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
