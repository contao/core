<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Core
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_theme']['name']       = array('Theme title', 'Please enter a unique theme title.');
$GLOBALS['TL_LANG']['tl_theme']['author']     = array('Author', 'Please enter the name of the theme designer.');
$GLOBALS['TL_LANG']['tl_theme']['folders']    = array('Folders', 'Please select the folders that belong to the theme from the files directory.');
$GLOBALS['TL_LANG']['tl_theme']['templates']  = array('Templates folder', 'Here you can select a templates folder that will be exported with the theme.');
$GLOBALS['TL_LANG']['tl_theme']['screenshot'] = array('Screenshot', 'Here you can choose a screenshot of the theme.');
$GLOBALS['TL_LANG']['tl_theme']['vars']       = array('Global variables', 'Here you can define global variables for the style sheets of the theme (e.g. <em>$red</em> -> <em>c00</em> or <em>$margin</em> -> <em>12px</em>).');
$GLOBALS['TL_LANG']['tl_theme']['source']     = array('Source files', 'Here you can upload one or more .cto files to be imported.');
$GLOBALS['TL_LANG']['tl_theme']['tstamp']     = array('Revision date', 'Date and time of the latest revision');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_theme']['title_legend']  = 'Title and author';
$GLOBALS['TL_LANG']['tl_theme']['config_legend'] = 'Configuration';
$GLOBALS['TL_LANG']['tl_theme']['vars_legend']   = 'Global variables';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_theme']['theme_imported']   = 'Theme "%s" has been imported.';
$GLOBALS['TL_LANG']['tl_theme']['checking_theme']   = 'The theme data is being checked';
$GLOBALS['TL_LANG']['tl_theme']['tables_fields']    = 'Tables and fields';
$GLOBALS['TL_LANG']['tl_theme']['missing_field']    = 'The field <strong>%s</strong> is missing in the database and will not be imported.';
$GLOBALS['TL_LANG']['tl_theme']['tables_ok']        = 'The tables have been successfully checked.';
$GLOBALS['TL_LANG']['tl_theme']['custom_sections']  = 'Custom layout sections';
$GLOBALS['TL_LANG']['tl_theme']['missing_section']  = 'The layout section <strong>%s</strong> is not defined in the back end settings.';
$GLOBALS['TL_LANG']['tl_theme']['sections_ok']      = 'The layout sections have been successfully checked.';
$GLOBALS['TL_LANG']['tl_theme']['missing_xml']      = 'Theme "%s" is corrupt and cannot be imported.';
$GLOBALS['TL_LANG']['tl_theme']['custom_templates'] = 'Custom templates';
$GLOBALS['TL_LANG']['tl_theme']['template_exists']  = 'The template <strong>"%s"</strong> exists and will be overwritten.';
$GLOBALS['TL_LANG']['tl_theme']['templates_ok']     = 'No conflicts detected.';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_theme']['new']         = array('New theme', 'Create a new theme');
$GLOBALS['TL_LANG']['tl_theme']['show']        = array('Theme details', 'Show the details of theme ID %s');
$GLOBALS['TL_LANG']['tl_theme']['edit']        = array('Edit theme', 'Edit theme ID %s');
$GLOBALS['TL_LANG']['tl_theme']['delete']      = array('Delete theme', 'Delete theme ID %s');
$GLOBALS['TL_LANG']['tl_theme']['css']         = array('Style sheets', 'Edit the style sheets of theme ID %s');
$GLOBALS['TL_LANG']['tl_theme']['modules']     = array('Modules', 'Edit the front end modules of theme ID %s');
$GLOBALS['TL_LANG']['tl_theme']['layout']      = array('Layouts', 'Edit the page layouts of theme ID %s');
$GLOBALS['TL_LANG']['tl_theme']['importTheme'] = array('Theme import', 'Import new themes');
$GLOBALS['TL_LANG']['tl_theme']['exportTheme'] = array('Export', 'Export theme ID %s');
