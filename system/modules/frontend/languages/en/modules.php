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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['navigationMenu'] = 'Navigation';
$GLOBALS['TL_LANG']['FMD']['navigation']     = array('Navigation menu', 'This module renders the navigation menu of the website.');
$GLOBALS['TL_LANG']['FMD']['navigationMain'] = array('Navigation main menu items', 'This module renders all main menu items of the navigation menu of the website.');
$GLOBALS['TL_LANG']['FMD']['navigationSub']  = array('Navigation submenu items', 'This module renders all submenu items of the current navigation main menu item.');
$GLOBALS['TL_LANG']['FMD']['customnav']      = array('Custom navigation', 'This module renders a custom navigation menu from a set of selected pages.');
$GLOBALS['TL_LANG']['FMD']['breadcrumb']     = array('Breadcrumb navigation', 'This module embeds a breadcrumb navigation menu into a webpage.');
$GLOBALS['TL_LANG']['FMD']['quicknav']       = array('Quick navigation', 'This module renders a drop down menu from all pages that allows to jump to a particular page.');
$GLOBALS['TL_LANG']['FMD']['quicklink']      = array('Quick link', 'This module renders a drop down menu from a set of selected pages that allows to jump to a particular page.');
$GLOBALS['TL_LANG']['FMD']['user']           = 'User';
$GLOBALS['TL_LANG']['FMD']['login']          = array('Login form', 'This module embeds a form that allows a member to log in. Once a member is logged in he will be able to access password protected pages.');
$GLOBALS['TL_LANG']['FMD']['logout']         = array('Automatic logout', 'This module automatically logs out a member.');
$GLOBALS['TL_LANG']['FMD']['personalData']   = array('Personal data', 'This module creates a front end form that allows a member to change his personal data.');
$GLOBALS['TL_LANG']['FMD']['application']    = 'Applications';
$GLOBALS['TL_LANG']['FMD']['form']           = array('Form', 'This module embeds a form into a webpage. The data of a form can be sent via e-mail or be processed by another module.');
$GLOBALS['TL_LANG']['FMD']['search']         = array('Search engine', 'This module allowes you to search your local website.');
$GLOBALS['TL_LANG']['FMD']['articleList']    = array('Article list', 'This module displays a list of articles of a particular column.');
$GLOBALS['TL_LANG']['FMD']['miscellaneous']  = 'Miscellaneous';
$GLOBALS['TL_LANG']['FMD']['html']           = array('Custom HTML code', 'This module allows you to include some custom HTML code.');
$GLOBALS['TL_LANG']['FMD']['flash']          = array('Flash movie', 'This module embeds a Macromedia Flash movie into a webpage.');
$GLOBALS['TL_LANG']['FMD']['sitemap']        = array('Sitemap', 'This module shows all available pages.');
$GLOBALS['TL_LANG']['FMD']['randomImage']    = array('Random image', 'This module includes a random image into a webpage.');

?>