<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Core
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Reads and writes content elements
 * 
 * @package   Core
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2011-2012
 */
class ContentModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_content';


	/**
	 * Find all published content elements by their parent ID
	 * 
	 * @param integer $intPid The article ID
	 * 
	 * @return \Model_Collection|null A collection of models or null if there are no content elements
	 */
	public static function findPublishedByPid($intPid)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=?");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.invisible=''";
		}

		return static::findBy($arrColumns, $intPid, array('order'=>"$t.sorting"));
	}
}
