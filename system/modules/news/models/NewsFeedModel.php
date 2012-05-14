<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package News
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Reads and writes news feeds
 * 
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2011-2012
 */
class NewsFeedModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_news_feed';


	/**
	 * Find all feeds which include a certain news archive
	 * 
	 * @param integer $intId The news archive ID
	 * 
	 * @return \Model_Collection|null A collection of models or null if the news archive is not part of a feed
	 */
	public static function findByArchive($intId)
	{
		$t = static::$strTable;
		return static::findBy(array("$t.archives LIKE '%\"" . intval($intId) . "\"%'"), null);
	}


	/**
	 * Find news feeds by their IDs
	 * 
	 * @param array $arrIds An array of news feed IDs
	 * 
	 * @return \Model_Collection|null A collection of models or null if there are no feeds
	 */
	public static function findByIds($arrIds)
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$t = static::$strTable;
		return static::findBy(array("$t.id IN(" . implode(',', array_map('intval', $arrIds)) . ")"), null);
	}
}
