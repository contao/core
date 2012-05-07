<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Newsletter
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;

/**
 * Class NewsletterModel
 *
 * Provide methods to find and save newsletters.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Newsletter
 */
class NewsletterModel extends \Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_newsletter';


	/**
	 * Find sent newsletters by their parent IDs and their ID or alias
	 * @param integer
	 * @param string
	 * @param array
	 * @return \Model_Collection|null
	 */
	public static function findSentByParentAndIdOrAlias($intId, $varAlias, $arrPids)
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

		return static::findBy($arrColumns, array($intId, $varAlias));
	}


	/**
	 * Find sent newsletters by their parent ID
	 * @param integer
	 * @return \Model_Collection|null
	 */
	public static function findSentByPid($intPid)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=?");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.sent=1";
		}

		return static::findBy($arrColumns, $intPid, array('order'=>"$t.date DESC"));
	}


	/**
	 * Find sent newsletters by multiple parent IDs
	 * @param array
	 * @return \Model_Collection|null
	 */
	public static function findSentByPids($arrPids)
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

		return static::findBy($arrColumns, null, array('order'=>"$t.date DESC"));
	}
}
