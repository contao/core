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
 * @method static \CommentsNotifyModel|null findById($id, $opt=array())
 * @method static \CommentsNotifyModel|null findByPk($id, $opt=array())
 * @method static \CommentsNotifyModel|null findByIdOrAlias($val, $opt=array())
 * @method static \CommentsNotifyModel|null findOneBy($col, $val, $opt=array())
 * @method static \CommentsNotifyModel|null findOneByTstamp($val, $opt=array())
 * @method static \CommentsNotifyModel|null findOneBySource($val, $opt=array())
 * @method static \CommentsNotifyModel|null findOneByParent($val, $opt=array())
 * @method static \CommentsNotifyModel|null findOneByName($val, $opt=array())
 * @method static \CommentsNotifyModel|null findOneByEmail($val, $opt=array())
 * @method static \CommentsNotifyModel|null findOneByUrl($val, $opt=array())
 * @method static \CommentsNotifyModel|null findOneByAddedOn($val, $opt=array())
 * @method static \CommentsNotifyModel|null findOneByIp($val, $opt=array())
 * @method static \CommentsNotifyModel|null findOneByTokenConfirm($val, $opt=array())
 * @method static \CommentsNotifyModel|null findOneByTokenRemove($val, $opt=array())
 *
 * @method static \Model\Collection|\CommentsNotifyModel[]|\CommentsNotifyModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\CommentsNotifyModel[]|\CommentsNotifyModel|null findBySource($val, $opt=array())
 * @method static \Model\Collection|\CommentsNotifyModel[]|\CommentsNotifyModel|null findByParent($val, $opt=array())
 * @method static \Model\Collection|\CommentsNotifyModel[]|\CommentsNotifyModel|null findByName($val, $opt=array())
 * @method static \Model\Collection|\CommentsNotifyModel[]|\CommentsNotifyModel|null findByEmail($val, $opt=array())
 * @method static \Model\Collection|\CommentsNotifyModel[]|\CommentsNotifyModel|null findByUrl($val, $opt=array())
 * @method static \Model\Collection|\CommentsNotifyModel[]|\CommentsNotifyModel|null findByAddedOn($val, $opt=array())
 * @method static \Model\Collection|\CommentsNotifyModel[]|\CommentsNotifyModel|null findByIp($val, $opt=array())
 * @method static \Model\Collection|\CommentsNotifyModel[]|\CommentsNotifyModel|null findByTokenConfirm($val, $opt=array())
 * @method static \Model\Collection|\CommentsNotifyModel[]|\CommentsNotifyModel|null findByTokenRemove($val, $opt=array())
 * @method static \Model\Collection|\CommentsNotifyModel[]|\CommentsNotifyModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\CommentsNotifyModel[]|\CommentsNotifyModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\CommentsNotifyModel[]|\CommentsNotifyModel|null findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countBySource($val, $opt=array())
 * @method static integer countByParent($val, $opt=array())
 * @method static integer countByName($val, $opt=array())
 * @method static integer countByEmail($val, $opt=array())
 * @method static integer countByUrl($val, $opt=array())
 * @method static integer countByAddedOn($val, $opt=array())
 * @method static integer countByIp($val, $opt=array())
 * @method static integer countByTokenConfirm($val, $opt=array())
 * @method static integer countByTokenRemove($val, $opt=array())
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
	 * @return \CommentsNotifyModel|null The model or null if there are no subscriptions
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
	 * @return \CommentsNotifyModel|null The model or null if there are no subscriptions
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
	 * @return \Model\Collection|\CommentsNotifyModel[]|\CommentsNotifyModel|null A collection of models or null if there are no active subscriptions
	 */
	public static function findActiveBySourceAndParent($strSource, $intParent, array $arrOptions=array())
	{
		$t = static::$strTable;

		return static::findBy(array("$t.source=? AND $t.parent=? AND tokenConfirm=''"), array($strSource, $intParent), $arrOptions);
	}
}
