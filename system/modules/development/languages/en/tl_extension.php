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
 * @package    Development
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_extension']['title']       = array('Title', 'Please enter a title.');
$GLOBALS['TL_LANG']['tl_extension']['folder']      = array('Folder name', 'Please enter a unique folder name. This folder will be created in the modules directory.');
$GLOBALS['TL_LANG']['tl_extension']['author']      = array('Author', 'Please enter a name and an optional e-mail address (e.g. <em>Name [e-mail@address.com]</em>).');
$GLOBALS['TL_LANG']['tl_extension']['copyright']   = array('Copyright', 'Please enter a copyright notice (e.g. <em>Name 2007</em>).');
$GLOBALS['TL_LANG']['tl_extension']['package']     = array('Package', 'Please enter the package name without spaces (e.g. <em>MyCustomModule</em>).');
$GLOBALS['TL_LANG']['tl_extension']['license']     = array('License', 'Please enter the license type (e.g. <em>GPL</em>).');
$GLOBALS['TL_LANG']['tl_extension']['addBeMod']    = array('Add a back end module', 'Choose this option if you are planning to set up a back end module.');
$GLOBALS['TL_LANG']['tl_extension']['beClasses']   = array('Back end classes', 'Please enter a comma separated list of back end class files that you want to be created.');
$GLOBALS['TL_LANG']['tl_extension']['beTables']    = array('Back end tables', 'Please enter a comma separated list of back end tables that you want to be created.');
$GLOBALS['TL_LANG']['tl_extension']['beTemplates'] = array('Back end templates', 'Please enter a comma separated list of back end template files that you want to be created.');
$GLOBALS['TL_LANG']['tl_extension']['addFeMod']    = array('Add a front end module', 'Choose this option if you are planning to set up a front end module.');
$GLOBALS['TL_LANG']['tl_extension']['feClasses']   = array('Front end classes', 'Please enter a comma separated list of front end class files that you want to be created.');
$GLOBALS['TL_LANG']['tl_extension']['feTables']    = array('Front end tables', 'Please enter a comma separated list of front end tables that you want to be created.');
$GLOBALS['TL_LANG']['tl_extension']['feTemplates'] = array('Front end templates', 'Please enter a comma separated list of front end template files that you want to be created.');
$GLOBALS['TL_LANG']['tl_extension']['addLanguage'] = array('Create a language pack', 'Choose this option if you are planning to create one or more language packs.');
$GLOBALS['TL_LANG']['tl_extension']['languages']   = array('Languages', 'Please enter a comma separated list of language packages that you want to be created (e.g. <em>en,de</em>).');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_extension']['headline'] = 'Create module ID %s';
$GLOBALS['TL_LANG']['tl_extension']['label']    = 'How to create a new module';
$GLOBALS['TL_LANG']['tl_extension']['confirm']  = 'The files have been created';
$GLOBALS['TL_LANG']['tl_extension']['unique']   = 'A folder called "%s" exists already!';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_extension']['new']    = array('New module', 'Create a new module');
$GLOBALS['TL_LANG']['tl_extension']['edit']   = array('Edit module', 'Edit module ID %s');
$GLOBALS['TL_LANG']['tl_extension']['copy']   = array('Copy module', 'Copy module ID %s');
$GLOBALS['TL_LANG']['tl_extension']['delete'] = array('Delete module', 'Delete module ID %s');
$GLOBALS['TL_LANG']['tl_extension']['show']   = array('Module details', 'Show details of module ID %s');
$GLOBALS['TL_LANG']['tl_extension']['create'] = array('Create files', 'Create files of module ID %s');
$GLOBALS['TL_LANG']['tl_extension']['make']   = array('Create files', 'If you hit the "Create files" button, a new folder will be created in the <em>modules</em> directory. This folder contains all necessary files and subfolders that you need to set up the module. You can then download these files to your development environment. Note that existing files on the server will be overwritten each time you hit the button!');

?>