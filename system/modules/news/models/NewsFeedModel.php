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
 * @method static $this findById()
 * @method static $this findByPk()
 * @method static $this findByIdOrAlias()
 * @method static $this findOneBy()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByTitle()
 * @method static $this findOneByAlias()
 * @method static $this findOneByLanguage()
 * @method static $this findOneByArchives()
 * @method static $this findOneByFormat()
 * @method static $this findOneBySource()
 * @method static $this findOneByMaxItems()
 * @method static $this findOneByFeedBase()
 * @method static $this findOneByDescription()
 *
 * @method static \Model\Collection|\NewsFeedModel findByTstamp()
 * @method static \Model\Collection|\NewsFeedModel findByTitle()
 * @method static \Model\Collection|\NewsFeedModel findByAlias()
 * @method static \Model\Collection|\NewsFeedModel findByLanguage()
 * @method static \Model\Collection|\NewsFeedModel findByArchives()
 * @method static \Model\Collection|\NewsFeedModel findByFormat()
 * @method static \Model\Collection|\NewsFeedModel findBySource()
 * @method static \Model\Collection|\NewsFeedModel findByMaxItems()
 * @method static \Model\Collection|\NewsFeedModel findByFeedBase()
 * @method static \Model\Collection|\NewsFeedModel findByDescription()
 * @method static \Model\Collection|\NewsFeedModel findMultipleByIds()
 * @method static \Model\Collection|\NewsFeedModel findBy()
 * @method static \Model\Collection|\NewsFeedModel findAll()
 *
 * @method static integer countById()
 * @method static integer countByTstamp()
 * @method static integer countByTitle()
 * @method static integer countByAlias()
 * @method static integer countByLanguage()
 * @method static integer countByArchives()
 * @method static integer countByFormat()
 * @method static integer countBySource()
 * @method static integer countByMaxItems()
 * @method static integer countByFeedBase()
 * @method static integer countByDescription()
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
