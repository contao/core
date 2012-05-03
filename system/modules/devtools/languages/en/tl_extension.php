<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Devtools
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_extension']['title']       = array('Title', 'Please enter the extension title.');
$GLOBALS['TL_LANG']['tl_extension']['folder']      = array('Folder name', 'Please enter a unique folder name.');
$GLOBALS['TL_LANG']['tl_extension']['author']      = array('Author', 'Please enter the author\'s name and an optional e-mail address (e.g. <em>Name [e-mail@address.com]</em>).');
$GLOBALS['TL_LANG']['tl_extension']['copyright']   = array('Copyright', 'Please enter the copyright notice (e.g. <em>Name 2007</em>).');
$GLOBALS['TL_LANG']['tl_extension']['package']     = array('Package', 'Please enter the package name without spaces (e.g. <em>MyCustomModule</em>).');
$GLOBALS['TL_LANG']['tl_extension']['license']     = array('License', 'Please enter the license type (e.g. <em>GNU/LGPL</em>).');
$GLOBALS['TL_LANG']['tl_extension']['addBeMod']    = array('Add a back end module', 'Add a back end module to the extension.');
$GLOBALS['TL_LANG']['tl_extension']['beClasses']   = array('Back end classes', 'Here you can enter a comma separated list of back end classes.');
$GLOBALS['TL_LANG']['tl_extension']['beTables']    = array('Back end tables', 'Here you can enter a comma separated list of back end tables.');
$GLOBALS['TL_LANG']['tl_extension']['beTemplates'] = array('Back end templates', 'Here you can enter a comma separated list of back end templates.');
$GLOBALS['TL_LANG']['tl_extension']['addFeMod']    = array('Add a front end module', 'Add a front end module to the extension.');
$GLOBALS['TL_LANG']['tl_extension']['feClasses']   = array('Front end classes', 'Here you can enter a comma separated list of front end classes.');
$GLOBALS['TL_LANG']['tl_extension']['feTables']    = array('Front end tables', 'Here you can enter a comma separated list of front end tables.');
$GLOBALS['TL_LANG']['tl_extension']['feTemplates'] = array('Front end templates', 'Here you can enter a comma separated list of front end templates.');
$GLOBALS['TL_LANG']['tl_extension']['addLanguage'] = array('Add language packs', 'Add one or more language packs to the extension.');
$GLOBALS['TL_LANG']['tl_extension']['languages']   = array('Languages', 'Here you can enter a comma separated list of languages (e.g. <em>en,de</em>).');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_extension']['title_legend']    = 'Title and name';
$GLOBALS['TL_LANG']['tl_extension']['license_legend']  = 'License information';
$GLOBALS['TL_LANG']['tl_extension']['backend_legend']  = 'Back end resources';
$GLOBALS['TL_LANG']['tl_extension']['frontend_legend'] = 'Front end resources';
$GLOBALS['TL_LANG']['tl_extension']['language_legend'] = 'Language settings';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_extension']['headline'] = 'Create extension ID %s';
$GLOBALS['TL_LANG']['tl_extension']['label']    = 'How to create an extension';
$GLOBALS['TL_LANG']['tl_extension']['confirm']  = 'The files have been created';
$GLOBALS['TL_LANG']['tl_extension']['unique']   = 'Folder "%s" exists already!';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_extension']['new']    = array('New extension', 'Create a new extension');
$GLOBALS['TL_LANG']['tl_extension']['show']   = array('Extension details', 'Show the details of extension ID %s');
$GLOBALS['TL_LANG']['tl_extension']['edit']   = array('Edit extension', 'Edit extension ID %s');
$GLOBALS['TL_LANG']['tl_extension']['copy']   = array('Copy extension', 'Copy extension ID %s');
$GLOBALS['TL_LANG']['tl_extension']['delete'] = array('Delete extension', 'Delete extension ID %s');
$GLOBALS['TL_LANG']['tl_extension']['create'] = array('Create files', 'Create the files of extension ID %s');
$GLOBALS['TL_LANG']['tl_extension']['make']   = array('Create files', 'Click the "Create files" button to generate a new folder in the <em>system/modules</em> directory (note that existing files will be overwritten). The folder will contain all files and subfolders that are required to set up the extension. It is recommended to download these files to your development environment.');
