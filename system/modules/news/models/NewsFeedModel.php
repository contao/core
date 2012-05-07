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
 * Class NewsFeedModel
 *
 * Provide methods to find and save news feeds.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    News
 */
class NewsFeedModel extends \Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_news_feed';


	/**
	 * Find all feeds which include a certain archive
	 * @param integer
	 * @return \Model_Collection|null
	 */
	public static function findByArchive($intId)
	{
		$t = static::$strTable;
		return static::findBy(array("$t.archives LIKE '%\"" . intval($intId) . "\"%'"), null);
	}


	/**
	 * Find news feeds by their IDs
	 * @param array
	 * @return \Model_Collection|null
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
