<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Comments
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class CommentsModel
 *
 * Provide methods to find and save comments.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Comments
 */
class CommentsModel extends \Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_comments';


	/**
	 * Find published comments by their source table and parent ID
	 * @param string
	 * @param integer
	 * @param boolean
	 * @param integer
	 * @param integer
	 * @return \Model_Collection|null
	 */
	public static function findPublishedBySourceAndParent($strSource, $intParent, $lbnDesc=false, $intLimit=0, $intOffset=0)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.source=? AND $t.parent=?");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.published=1";
		}

		$arrOptions = array
		(
			'order'  => ($lbnDesc ? "$t.date DESC" : "$t.date"),
			'limit'  => $intLimit,
			'offset' => $intOffset
		);

		return static::findBy($arrColumns, array($strSource, $intParent), $arrOptions);
	}


	/**
	 * Count published comments by their source table and parent ID
	 * @param string
	 * @param integer
	 * @return integer
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
