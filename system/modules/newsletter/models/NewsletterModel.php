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
 * @method static \NewsletterModel|null findById($id, $opt=array())
 * @method static \NewsletterModel|null findByPk($id, $opt=array())
 * @method static \NewsletterModel|null findByIdOrAlias($val, $opt=array())
 * @method static \NewsletterModel|null findOneBy($col, $val, $opt=array())
 * @method static \NewsletterModel|null findOneByPid($val, $opt=array())
 * @method static \NewsletterModel|null findOneByTstamp($val, $opt=array())
 * @method static \NewsletterModel|null findOneBySubject($val, $opt=array())
 * @method static \NewsletterModel|null findOneByAlias($val, $opt=array())
 * @method static \NewsletterModel|null findOneByContent($val, $opt=array())
 * @method static \NewsletterModel|null findOneByText($val, $opt=array())
 * @method static \NewsletterModel|null findOneByAddFile($val, $opt=array())
 * @method static \NewsletterModel|null findOneByFiles($val, $opt=array())
 * @method static \NewsletterModel|null findOneByTemplate($val, $opt=array())
 * @method static \NewsletterModel|null findOneBySendText($val, $opt=array())
 * @method static \NewsletterModel|null findOneByExternalImages($val, $opt=array())
 * @method static \NewsletterModel|null findOneBySender($val, $opt=array())
 * @method static \NewsletterModel|null findOneBySenderName($val, $opt=array())
 * @method static \NewsletterModel|null findOneBySent($val, $opt=array())
 * @method static \NewsletterModel|null findOneByDate($val, $opt=array())
 *
 * @method static \Model\Collection|\NewsletterModel[]|\NewsletterModel|null findByPid($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel[]|\NewsletterModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel[]|\NewsletterModel|null findBySubject($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel[]|\NewsletterModel|null findByAlias($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel[]|\NewsletterModel|null findByContent($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel[]|\NewsletterModel|null findByText($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel[]|\NewsletterModel|null findByAddFile($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel[]|\NewsletterModel|null findByFiles($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel[]|\NewsletterModel|null findByTemplate($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel[]|\NewsletterModel|null findBySendText($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel[]|\NewsletterModel|null findByExternalImages($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel[]|\NewsletterModel|null findBySender($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel[]|\NewsletterModel|null findBySenderName($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel[]|\NewsletterModel|null findBySent($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel[]|\NewsletterModel|null findByDate($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel[]|\NewsletterModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel[]|\NewsletterModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\NewsletterModel[]|\NewsletterModel|null findAll($opt=array())
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
	 * Find a sent newsletter by its parent IDs and its ID or alias
	 *
	 * @param integer $varId      The numeric ID or alias name
	 * @param array   $arrPids    An array of newsletter channel IDs
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \NewsletterModel|null The model or null if there are no sent newsletters
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
	 * @return \Model\Collection|\NewsletterModel[]|\NewsletterModel|null A collection of models or null if there are no sent newsletters
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
	 * @return \Model\Collection|\NewsletterModel[]|\NewsletterModel|null A collection of models or null if there are no sent newsletters
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
