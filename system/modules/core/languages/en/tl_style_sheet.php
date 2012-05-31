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
$GLOBALS['TL_LANG']['tl_style_sheet']['name']        = array('Name', 'Please enter the style sheet name.');
$GLOBALS['TL_LANG']['tl_style_sheet']['embedImages'] = array('Embed images up to', 'Here you can enter the file size in bytes up to which images will be embedded in the style sheet as data: string. Set to 0 to disable the feature.');
$GLOBALS['TL_LANG']['tl_style_sheet']['cc']          = array('Conditional comment', 'Conditional comments allow you to create Internet Explorer specific style sheets (e.g. <em>if lt IE 9</em>).');
$GLOBALS['TL_LANG']['tl_style_sheet']['media']       = array('Media types', 'Here you can choose the media types the style sheet applies to.');
$GLOBALS['TL_LANG']['tl_style_sheet']['mediaQuery']  = array('Media query', 'Here you can define the media type using a media query like <em>screen and (min-width: 800px)</em>. The media types defined above will then be overwritten.');
$GLOBALS['TL_LANG']['tl_style_sheet']['vars']        = array('Global variables', 'Here you can define global variables for the style sheet (e.g. <em>$red</em> -> <em>c00</em> or <em>$margin</em> -> <em>12px</em>).');
$GLOBALS['TL_LANG']['tl_style_sheet']['source']      = array('Source files', 'Here you can upload one or more .css files to be imported.');
$GLOBALS['TL_LANG']['tl_style_sheet']['tstamp']      = array('Revision date', 'Date and time of the latest revision');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_style_sheet']['title_legend']  = 'Name';
$GLOBALS['TL_LANG']['tl_style_sheet']['config_legend'] = 'Configuration';
$GLOBALS['TL_LANG']['tl_style_sheet']['media_legend']  = 'Media settings';
$GLOBALS['TL_LANG']['tl_style_sheet']['vars_legend']   = 'Global variables';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_style_sheet']['css_imported'] = 'Style sheet "%s" has been imported.';
$GLOBALS['TL_LANG']['tl_style_sheet']['css_renamed']  = 'Style sheet "%s" has been imported as "%s".';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_style_sheet']['new']        = array('New style sheet', 'Create a new style sheet');
$GLOBALS['TL_LANG']['tl_style_sheet']['show']       = array('Style sheet details', 'Show the details of style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['edit']       = array('Edit style sheet', 'Edit style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['editheader'] = array('Edit style sheet settings', 'Edit the settings of style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['cut']        = array('Move style sheet', 'Move style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['copy']       = array('Duplicate style sheet', 'Duplicate style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['delete']     = array('Delete style sheet', 'Delete style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['import']     = array('CSS import', 'Import existing CSS files');
