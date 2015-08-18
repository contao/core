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
 * Reads and writes news feeds
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $title
 * @property string  $alias
 * @property string  $language
 * @property string  $archives
 * @property string  $format
 * @property string  $source
 * @property integer $maxItems
 * @property string  $feedBase
 * @property string  $description
 * @property string  $feedName
 *
 * @method static \NewsFeedModel findById($id, $opt=array())
 * @method static \NewsFeedModel findByPk($id, $opt=array())
 * @method static \NewsFeedModel findByIdOrAlias($val, $opt=array())
 * @method static \NewsFeedModel findOneBy($col, $val, $opt=array())
 * @method static \NewsFeedModel findOneByTstamp($val, $opt=array())
 * @method static \NewsFeedModel findOneByTitle($val, $opt=array())
 * @method static \NewsFeedModel findOneByAlias($val, $opt=array())
 * @method static \NewsFeedModel findOneByLanguage($val, $opt=array())
 * @method static \NewsFeedModel findOneByArchives($val, $opt=array())
 * @method static \NewsFeedModel findOneByFormat($val, $opt=array())
 * @method static \NewsFeedModel findOneBySource($val, $opt=array())
 * @method static \NewsFeedModel findOneByMaxItems($val, $opt=array())
 * @method static \NewsFeedModel findOneByFeedBase($val, $opt=array())
 * @method static \NewsFeedModel findOneByDescription($val, $opt=array())
 *
 * @method static \Model\Collection|\NewsFeedModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel findByTitle($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel findByAlias($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel findByLanguage($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel findByArchives($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel findByFormat($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel findBySource($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel findByMaxItems($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel findByFeedBase($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel findByDescription($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByTitle($val, $opt=array())
 * @method static integer countByAlias($val, $opt=array())
 * @method static integer countByLanguage($val, $opt=array())
 * @method static integer countByArchives($val, $opt=array())
 * @method static integer countByFormat($val, $opt=array())
 * @method static integer countBySource($val, $opt=array())
 * @method static integer countByMaxItems($val, $opt=array())
 * @method static integer countByFeedBase($val, $opt=array())
 * @method static integer countByDescription($val, $opt=array())
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
	 * @param integer $intId      The news archive ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\NewsFeedModel|null A collection of models or null if the news archive is not part of a feed
	 */
	public static function findByArchive($intId, array $arrOptions=array())
	{
		$t = static::$strTable;

		return static::findBy(array("$t.archives LIKE '%\"" . intval($intId) . "\"%'"), null, $arrOptions);
	}


	/**
	 * Find news feeds by their IDs
	 *
	 * @param array $arrIds     An array of news feed IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\NewsFeedModel|null A collection of models or null if there are no feeds
	 */
	public static function findByIds($arrIds, array $arrOptions=array())
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$t = static::$strTable;

		return static::findBy(array("$t.id IN(" . implode(',', array_map('intval', $arrIds)) . ")"), null, $arrOptions);
	}
}
