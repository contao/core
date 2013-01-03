<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package News
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Contao\News'              => 'system/modules/news/classes/News.php',

	// Models
	'Contao\NewsArchiveModel'  => 'system/modules/news/models/NewsArchiveModel.php',
	'Contao\NewsFeedModel'     => 'system/modules/news/models/NewsFeedModel.php',
	'Contao\NewsModel'         => 'system/modules/news/models/NewsModel.php',

	// Modules
	'Contao\ModuleNews'        => 'system/modules/news/modules/ModuleNews.php',
	'Contao\ModuleNewsArchive' => 'system/modules/news/modules/ModuleNewsArchive.php',
	'Contao\ModuleNewsList'    => 'system/modules/news/modules/ModuleNewsList.php',
	'Contao\ModuleNewsMenu'    => 'system/modules/news/modules/ModuleNewsMenu.php',
	'Contao\ModuleNewsReader'  => 'system/modules/news/modules/ModuleNewsReader.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_newsarchive'       => 'system/modules/news/templates',
	'mod_newsarchive_empty' => 'system/modules/news/templates',
	'mod_newslist'          => 'system/modules/news/templates',
	'mod_newsmenu'          => 'system/modules/news/templates',
	'mod_newsmenu_day'      => 'system/modules/news/templates',
	'mod_newsmenu_year'     => 'system/modules/news/templates',
	'mod_newsreader'        => 'system/modules/news/templates',
	'news_full'             => 'system/modules/news/templates',
	'news_latest'           => 'system/modules/news/templates',
	'news_short'            => 'system/modules/news/templates',
	'news_simple'           => 'system/modules/news/templates',
));
