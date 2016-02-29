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
 * @method static \CommentsModel|null findById($id, $opt=array())
 * @method static \CommentsModel|null findByPk($id, $opt=array())
 * @method static \CommentsModel|null findByIdOrAlias($val, $opt=array())
 * @method static \CommentsModel|null findOneBy($col, $val, $opt=array())
 * @method static \CommentsModel|null findOneByTstamp($val, $opt=array())
 * @method static \CommentsModel|null findOneBySource($val, $opt=array())
 * @method static \CommentsModel|null findOneByParent($val, $opt=array())
 * @method static \CommentsModel|null findOneByDate($val, $opt=array())
 * @method static \CommentsModel|null findOneByName($val, $opt=array())
 * @method static \CommentsModel|null findOneByEmail($val, $opt=array())
 * @method static \CommentsModel|null findOneByWebsite($val, $opt=array())
 * @method static \CommentsModel|null findOneByComment($val, $opt=array())
 * @method static \CommentsModel|null findOneByAddReply($val, $opt=array())
 * @method static \CommentsModel|null findOneByAuthor($val, $opt=array())
 * @method static \CommentsModel|null findOneByReply($val, $opt=array())
 * @method static \CommentsModel|null findOneByPublished($val, $opt=array())
 * @method static \CommentsModel|null findOneByIp($val, $opt=array())
 * @method static \CommentsModel|null findOneByNotified($val, $opt=array())
 *
 * @method static \Model\Collection|\CommentsModel[]|\CommentsModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel[]|\CommentsModel|null findBySource($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel[]|\CommentsModel|null findByParent($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel[]|\CommentsModel|null findByDate($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel[]|\CommentsModel|null findByName($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel[]|\CommentsModel|null findByEmail($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel[]|\CommentsModel|null findByWebsite($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel[]|\CommentsModel|null findByComment($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel[]|\CommentsModel|null findByAddReply($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel[]|\CommentsModel|null findByAuthor($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel[]|\CommentsModel|null findByReply($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel[]|\CommentsModel|null findByPublished($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel[]|\CommentsModel|null findByIp($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel[]|\CommentsModel|null findByNotified($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel[]|\CommentsModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\CommentsModel[]|\CommentsModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\CommentsModel[]|\CommentsModel|null findAll($opt=array())
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
	 * @return \Model\Collection|\CommentsModel[]|\CommentsModel|null A collection of models or null if there are no comments
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
