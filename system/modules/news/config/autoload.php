<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    News
 * @license    LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'Contao\\ModuleNews'        => 'system/modules/news/ModuleNews.php',
	'Contao\\ModuleNewsArchive' => 'system/modules/news/ModuleNewsArchive.php',
	'Contao\\ModuleNewsList'    => 'system/modules/news/ModuleNewsList.php',
	'Contao\\ModuleNewsMenu'    => 'system/modules/news/ModuleNewsMenu.php',
	'Contao\\ModuleNewsReader'  => 'system/modules/news/ModuleNewsReader.php',
	'Contao\\News'              => 'system/modules/news/News.php',

	// Models
	'Contao\\NewsArchiveModel'  => 'system/modules/news/models/NewsArchiveModel.php',
	'Contao\\NewsModel'         => 'system/modules/news/models/NewsModel.php',
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

?>