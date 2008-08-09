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
 * Back end modules
 */
array_insert($GLOBALS['BE_MOD']['system'], 4, array
(
	'extension' => array
	(
		'tables'     => array('tl_extension'),
		'create'     => array('ModuleExtension', 'generate'),
		'icon'       => 'system/modules/development/html/extension.gif'
	),
	'labels' => array
	(
		'callback'   => 'ModuleLabels',
		'icon'       => 'system/modules/development/html/labels.gif',
		'stylesheet' => 'system/modules/development/html/labels.css'
	)
));

?>