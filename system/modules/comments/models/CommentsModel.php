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
 * Reads and writes comments
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $source
 * @property integer $parent
 * @property string  $date
 * @property string  $name
 * @property string  $email
 * @property string  $website
 * @property string  $comment
 * @property boolean $addReply
 * @property integer $author
 * @property string  $reply
 * @property boolean $published
 * @property string  $ip
 * @property boolean $notified
 *
 * @method static $this findById($id, $opt=array())
 * @method static $this findByPk($id, $opt=array())
 * @method static $this findByIdOrAlias($val, $opt=array())
 * @method static $this findOneBy($col, $val, $opt=array())
 * @method static $this findOneByTstamp($val, $opt=array())
 * @method static $this findOneBySource($val, $opt=array())
 * @method static $this findOneByParent($val, $opt=array())
 * @method static $this findOneByDate($val, $opt=array())
 * @method static $this findOneByName($val, $opt=array())
 * @method static $this findOneByEmail($val, $opt=array())
 * @method static $this findOneByWebsite($val, $opt=array())
 * @method static $this findOneByComment($val, $opt=array())
 * @method static $this findOneByAddReply($val, $opt=array())
 * @method static $this findOneByAuthor($val, $opt=array())
 * @method static $this findOneByReply($val, $opt=array())
 * @method static $this findOneByPublished($val, $opt=array())
 * @method static $this findOneByIp($val, $opt=array())
 * @method static $this findOneByNotified($val, $opt=array())
 *
 * @method static \Model\Collection|\CommentsModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel findBySource($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel findByParent($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel findByDate($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel findByName($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel findByEmail($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel findByWebsite($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel findByComment($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel findByAddReply($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel findByAuthor($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel findByReply($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel findByPublished($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel findByIp($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel findByNotified($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\CommentsModel findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countBySource($val, $opt=array())
 * @method static integer countByParent($val, $opt=array())
 * @method static integer countByDate($val, $opt=array())
 * @method static integer countByName($val, $opt=array())
 * @method static integer countByEmail($val, $opt=array())
 * @method static integer countByWebsite($val, $opt=array())
 * @method static integer countByComment($val, $opt=array())
 * @method static integer countByAddReply($val, $opt=array())
 * @method static integer countByAuthor($val, $opt=array())
 * @method static integer countByReply($val, $opt=array())
 * @method static integer countByPublished($val, $opt=array())
 * @method static integer countByIp($val, $opt=array())
 * @method static integer countByNotified($val, $opt=array())
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
	 * @return \Model\Collection|\CommentsModel|null A collection of models or null if there are no comments
	 */
	public static function findPublishedBySourceAndParent($strSource, $intParent, $blnDesc=false, $intLimit=0, $intOffset=0, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.source=? AND $t.parent=?");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.published='1'";
		}

		$arrOptions['limit']  = $intLimit;
		$arrOptions['offset'] = $intOffset;

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order']  = ($blnDesc ? "$t.date DESC" : "$t.date");
		}

		return static::findBy($arrColumns, array($strSource, (int) $intParent), $arrOptions);
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
			$arrColumns[] = "$t.published='1'";
		}

		return static::countBy($arrColumns, array($strSource, (int) $intParent));
	}
}
