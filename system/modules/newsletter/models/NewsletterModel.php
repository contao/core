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
	 * @return \Model\Collection|null A collection of models or null if there are no sent newsletters
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

		return static::findBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId), $arrOptions);
	}


	/**
	 * Find sent newsletters by their parent ID
	 *
	 * @param integer $intPid     The newsletter channel ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no sent newsletters
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
	 * @return \Model\Collection|null A collection of models or null if there are no sent newsletters
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
