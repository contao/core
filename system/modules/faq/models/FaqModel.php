<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Faq
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;

/**
 * Class FaqModel
 *
 * Provide methods to find and save FAQs.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Faq
 */
class FaqModel extends \Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_faq';


	/**
	 * Find a published FAQ from one or more categories by its ID or alias
	 * @param integer
	 * @param string
	 * @param array
	 * @return \Model|null
	 */
	public static function findPublishedByParentAndIdOrAlias($intId, $varAlias, $arrPids)
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("($t.id=? OR $t.alias=?) AND pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.published=1";
		}

		return static::findOneBy($arrColumns, array($intId, $varAlias));
	}


	/**
	 * Find all published FAQs by their parent IDs
	 * @param array
	 * @return \Model_Collection|null
	 */
	public static function findPublishedByPids($arrPids)
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		return static::findBy(array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")"), null, array('order'=>"$t.pid, $t.sorting"));
	}
}
