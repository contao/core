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
 * Reads and writes comments subscriptions
 * 
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2011-2012
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
	 * @param string $strToken The token string
	 * 
	 * @return \Model|null The subscription model or null
	 */
	public static function findByTokens($strToken)
	{
		$t = static::$strTable;
		return static::findOneBy(array("($t.tokenConfirm=? OR $t.tokenRemove=?)"), array($strToken, $strToken));
	}


	/**
	 * Find a subscription by its tokens
	 * 
	 * @param string  $strSource The source element
	 * @param integer $intParent The parent ID
	 * @param string  $strEmail  The e-mail address
	 * 
	 * @return \Model|null The subscription model or null
	 */
	public static function findBySourceParentAndEmail($strSource, $intParent, $strEmail)
	{
		$t = static::$strTable;
		return static::findOneBy(array("$t.source=? AND $t.parent=? AND $t.email=?"), array($strSource, $intParent, $strEmail));
	}


	/**
	 * Find published comments by their source table and parent ID
	 * 
	 * @param string  $strSource The source element
	 * @param integer $intParent The parent ID
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no active subscriptions
	 */
	public static function findActiveBySourceAndParent($strSource, $intParent)
	{
		$t = static::$strTable;
		return static::findBy(array("$t.source=? AND $t.parent=? AND tokenConfirm=''"), array($strSource, $intParent));
	}
}
