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
 * @method static \NewsFeedModel|null findById($id, $opt=array())
 * @method static \NewsFeedModel|null findByPk($id, $opt=array())
 * @method static \NewsFeedModel|null findByIdOrAlias($val, $opt=array())
 * @method static \NewsFeedModel|null findOneBy($col, $val, $opt=array())
 * @method static \NewsFeedModel|null findOneByTstamp($val, $opt=array())
 * @method static \NewsFeedModel|null findOneByTitle($val, $opt=array())
 * @method static \NewsFeedModel|null findOneByAlias($val, $opt=array())
 * @method static \NewsFeedModel|null findOneByLanguage($val, $opt=array())
 * @method static \NewsFeedModel|null findOneByArchives($val, $opt=array())
 * @method static \NewsFeedModel|null findOneByFormat($val, $opt=array())
 * @method static \NewsFeedModel|null findOneBySource($val, $opt=array())
 * @method static \NewsFeedModel|null findOneByMaxItems($val, $opt=array())
 * @method static \NewsFeedModel|null findOneByFeedBase($val, $opt=array())
 * @method static \NewsFeedModel|null findOneByDescription($val, $opt=array())
 *
 * @method static \Model\Collection|\NewsFeedModel[]|\NewsFeedModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel[]|\NewsFeedModel|null findByTitle($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel[]|\NewsFeedModel|null findByAlias($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel[]|\NewsFeedModel|null findByLanguage($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel[]|\NewsFeedModel|null findByArchives($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel[]|\NewsFeedModel|null findByFormat($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel[]|\NewsFeedModel|null findBySource($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel[]|\NewsFeedModel|null findByMaxItems($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel[]|\NewsFeedModel|null findByFeedBase($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel[]|\NewsFeedModel|null findByDescription($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel[]|\NewsFeedModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel[]|\NewsFeedModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\NewsFeedModel[]|\NewsFeedModel|null findAll($opt=array())
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
	 * @return \Model\Collection|\NewsFeedModel[]|\NewsFeedModel|null A collection of models or null if the news archive is not part of a feed
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
	 * @return \Model\Collection|\NewsFeedModel[]|\NewsFeedModel|null A collection of models or null if there are no feeds
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
