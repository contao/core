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
 * @method static $this findById()
 * @method static $this findOneByTstamp()
 * @method static $this findOneBySource()
 * @method static $this findOneByParent()
 * @method static $this findOneByDate()
 * @method static $this findOneByName()
 * @method static $this findOneByEmail()
 * @method static $this findOneByWebsite()
 * @method static $this findOneByComment()
 * @method static $this findOneByAddReply()
 * @method static $this findOneByAuthor()
 * @method static $this findOneByReply()
 * @method static $this findOneByPublished()
 * @method static $this findOneByIp()
 * @method static $this findOneByNotified()
 * @method static \Model\Collection findByTstamp()
 * @method static \Model\Collection findBySource()
 * @method static \Model\Collection findByParent()
 * @method static \Model\Collection findByDate()
 * @method static \Model\Collection findByName()
 * @method static \Model\Collection findByEmail()
 * @method static \Model\Collection findByWebsite()
 * @method static \Model\Collection findByComment()
 * @method static \Model\Collection findByAddReply()
 * @method static \Model\Collection findByAuthor()
 * @method static \Model\Collection findByReply()
 * @method static \Model\Collection findByPublished()
 * @method static \Model\Collection findByIp()
 * @method static \Model\Collection findByNotified()
 * @method static integer countById()
 * @method static integer countByTstamp()
 * @method static integer countBySource()
 * @method static integer countByParent()
 * @method static integer countByDate()
 * @method static integer countByName()
 * @method static integer countByEmail()
 * @method static integer countByWebsite()
 * @method static integer countByComment()
 * @method static integer countByAddReply()
 * @method static integer countByAuthor()
 * @method static integer countByReply()
 * @method static integer countByPublished()
 * @method static integer countByIp()
 * @method static integer countByNotified()
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
