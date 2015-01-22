<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
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
	'mod_newsarchive'   => 'system/modules/news/templates/modules',
	'mod_newslist'      => 'system/modules/news/templates/modules',
	'mod_newsmenu'      => 'system/modules/news/templates/modules',
	'mod_newsmenu_day'  => 'system/modules/news/templates/modules',
	'mod_newsmenu_year' => 'system/modules/news/templates/modules',
	'mod_newsreader'    => 'system/modules/news/templates/modules',
	'news_full'         => 'system/modules/news/templates/news',
	'news_latest'       => 'system/modules/news/templates/news',
	'news_short'        => 'system/modules/news/templates/news',
	'news_simple'       => 'system/modules/news/templates/news',
));
