<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['news'] = array('News', 'This module allows you to manage news on your website.');


/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['news']        = 'News';
$GLOBALS['TL_LANG']['FMD']['newslist']    = array('Newslist', 'This module lists a certain number of news of a certain news archive.');
$GLOBALS['TL_LANG']['FMD']['newsreader']  = array('Newsreader', 'This module shows a single news article.');
$GLOBALS['TL_LANG']['FMD']['newsarchive'] = array('News archive', 'This module lists all news of a certain archive. Please note that you will need module "news archive menu" to browse the news archive.');
$GLOBALS['TL_LANG']['FMD']['newsmenu']    = array('News archive menu', 'This module allows you to browse the news archive.');

?>