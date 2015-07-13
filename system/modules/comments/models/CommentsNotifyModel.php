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
 * Reads and writes comments subscriptions
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $source
 * @property integer $parent
 * @property string  $name
 * @property string  $email
 * @property string  $url
 * @property string  $addedOn
 * @property string  $ip
 * @property string  $tokenConfirm
 * @property string  $tokenRemove
 *
 * @method static $this findById()
 * @method static $this findByPk()
 * @method static $this findByIdOrAlias()
 * @method static $this findOneBy()
 * @method static $this findOneByTstamp()
 * @method static $this findOneBySource()
 * @method static $this findOneByParent()
 * @method static $this findOneByName()
 * @method static $this findOneByEmail()
 * @method static $this findOneByUrl()
 * @method static $this findOneByAddedOn()
 * @method static $this findOneByIp()
 * @method static $this findOneByTokenConfirm()
 * @method static $this findOneByTokenRemove()
 *
 * @method static \Model\Collection|\CommentsNotifyModel findByTstamp()
 * @method static \Model\Collection|\CommentsNotifyModel findBySource()
 * @method static \Model\Collection|\CommentsNotifyModel findByParent()
 * @method static \Model\Collection|\CommentsNotifyModel findByName()
 * @method static \Model\Collection|\CommentsNotifyModel findByEmail()
 * @method static \Model\Collection|\CommentsNotifyModel findByUrl()
 * @method static \Model\Collection|\CommentsNotifyModel findByAddedOn()
 * @method static \Model\Collection|\CommentsNotifyModel findByIp()
 * @method static \Model\Collection|\CommentsNotifyModel findByTokenConfirm()
 * @method static \Model\Collection|\CommentsNotifyModel findByTokenRemove()
 * @method static \Model\Collection|\CommentsNotifyModel findMultipleByIds()
 * @method static \Model\Collection|\CommentsNotifyModel findBy()
 * @method static \Model\Collection|\CommentsNotifyModel findAll()
 *
 * @method static integer countById()
 * @method static integer countByTstamp()
 * @method static integer countBySource()
 * @method static integer countByParent()
 * @method static integer countByName()
 * @method static integer countByEmail()
 * @method static integer countByUrl()
 * @method static integer countByAddedOn()
 * @method static integer countByIp()
 * @method static integer countByTokenConfirm()
 * @method static integer countByTokenRemove()
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class CommentsNotifyModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_comments_notify';


	/**
	 * Find a subscription by its tokens
	 *
	 * @param string $strToken   The token string
	 * @param array  $arrOptions An optional options array
	 *
	 * @return static The subscription model or null
	 */
	public static function findByTokens($strToken, array $arrOptions=array())
	{
		$t = static::$strTable;

		return static::findOneBy(array("($t.tokenConfirm=? OR $t.tokenRemove=?)"), array($strToken, $strToken), $arrOptions);
	}


	/**
	 * Find a subscription by its source table, parent ID and e-mail address
	 *
	 * @param string  $strSource  The source element
	 * @param integer $intParent  The parent ID
	 * @param string  $strEmail   The e-mail address
	 * @param array   $arrOptions An optional options array
	 *
	 * @return static The subscription model or null
	 */
	public static function findBySourceParentAndEmail($strSource, $intParent, $strEmail, array $arrOptions=array())
	{
		$t = static::$strTable;

		return static::findOneBy(array("$t.source=? AND $t.parent=? AND $t.email=?"), array($strSource, $intParent, $strEmail), $arrOptions);
	}


	/**
	 * Find active subscriptions by their source table and parent ID
	 *
	 * @param string  $strSource  The source element
	 * @param integer $intParent  The parent ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\CommentsNotifyModel|null A collection of models or null if there are no active subscriptions
	 */
	public static function findActiveBySourceAndParent($strSource, $intParent, array $arrOptions=array())
	{
		$t = static::$strTable;

		return static::findBy(array("$t.source=? AND $t.parent=? AND tokenConfirm=''"), array($strSource, $intParent), $arrOptions);
	}
}
