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
 * Reads and writes comments subscriptions
 * 
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
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
	 * @return \Model|null The subscription model or null
	 */
	public static function findByTokens($strToken, array $arrOptions=array())
	{
		$t = static::$strTable;
		return static::findOneBy(array("($t.tokenConfirm=? OR $t.tokenRemove=?)"), array($strToken, $strToken), $arrOptions);
	}


	/**
	 * Find a subscription by its tokens
	 * 
	 * @param string  $strSource  The source element
	 * @param integer $intParent  The parent ID
	 * @param string  $strEmail   The e-mail address
	 * @param array   $arrOptions An optional options array
	 * 
	 * @return \Model|null The subscription model or null
	 */
	public static function findBySourceParentAndEmail($strSource, $intParent, $strEmail, array $arrOptions=array())
	{
		$t = static::$strTable;
		return static::findOneBy(array("$t.source=? AND $t.parent=? AND $t.email=?"), array($strSource, $intParent, $strEmail), $arrOptions);
	}


	/**
	 * Find published comments by their source table and parent ID
	 * 
	 * @param string  $strSource  The source element
	 * @param integer $intParent  The parent ID
	 * @param array   $arrOptions An optional options array
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no active subscriptions
	 */
	public static function findActiveBySourceAndParent($strSource, $intParent, array $arrOptions=array())
	{
		$t = static::$strTable;
		return static::findBy(array("$t.source=? AND $t.parent=? AND tokenConfirm=''"), array($strSource, $intParent), $arrOptions);
	}
}
