<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \Model;


/**
 * Class FaqModel
 *
 * Provide methods to find and save FAQs.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Model
 */
class FaqModel extends Model
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
