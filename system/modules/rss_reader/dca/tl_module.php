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
 * @package    RssReader
 * @license    LGPL
 * @filesource
 */


/**
 * Load tl_page labels
 */
$this->loadLanguageFile('tl_page');


/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['rss_reader'] = 'name,type,headline;rss_feed,rss_cache,rss_template;rss_numberOfItems,perPage,skipFirst,searchable;guests,protected;align,space,cssID';


/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['rss_feed'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['rss_feed'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['rss_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['rss_template'],
	'default'                 => 'rss_default',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => $this->getTemplateGroup('rss_')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['rss_numberOfItems'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['rss_numberOfItems'],
	'default'                 => 3,
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['rss_cache'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['rss_cache'],
	'default'                 => 3600,
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array(0, 15, 30, 60, 300, 900, 1800, 3600, 21600, 43200, 86400, 259200, 604800),
	'reference'               => &$GLOBALS['TL_LANG']['CACHE']
);

?>