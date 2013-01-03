<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Comments
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Reads and writes comments
 * 
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
class CommentsModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_comments';


	/**
	 * Find published comments by their source table and parent ID
	 * 
	 * @param string  $strSource  The source element
	 * @param integer $intParent  The parent ID
	 * @param boolean $blnDesc    If true, comments will be sorted descending
	 * @param integer $intLimit   An optional limit
	 * @param integer $intOffset  An optional offset
	 * @param array   $arrOptions An optional options array
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no comments
	 */
	public static function findPublishedBySourceAndParent($strSource, $intParent, $blnDesc=false, $intLimit=0, $intOffset=0, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.source=? AND $t.parent=?");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.published=1";
		}

		$arrOptions['limit']  = $intLimit;
		$arrOptions['offset'] = $intOffset;

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order']  = ($blnDesc ? "$t.date DESC" : "$t.date");
		}

		return static::findBy($arrColumns, array($strSource, $intParent), $arrOptions);
	}


	/**
	 * Count published comments by their source table and parent ID
	 * 
	 * @param string  $strSource The source element
	 * @param integer $intParent The parent ID
	 * 
	 * @return integer The number of comments
	 */
	public static function countPublishedBySourceAndParent($strSource, $intParent)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.source=? AND $t.parent=?");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.published=1";
		}

		return static::countBy($arrColumns, array($strSource, $intParent));
	}
}
