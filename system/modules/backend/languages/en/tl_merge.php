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
 * @package    Language
 * @license    LGPL
 */


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_merge']['emptySelection'] = 'Please select at least one module!';
$GLOBALS['TL_LANG']['tl_merge']['autoloadExists'] = 'Module "%s" seems to have been converted already. Use the "override" option to force the update.';
$GLOBALS['TL_LANG']['tl_merge']['headline']       = 'Convert Contao 2 extensions';
$GLOBALS['TL_LANG']['tl_merge']['available']      = 'Available modules';
$GLOBALS['TL_LANG']['tl_merge']['override']       = 'Override existing files';
$GLOBALS['TL_LANG']['tl_merge']['explain']        = 'With this script you can prepare Contao 2 extensions for Contao 3. The script will create the <em>config/autoload.php</em> file with the paths to the existing classes and templates. If the <em>namespace</em> command is found in a PHP class, it will be included, too.';
$GLOBALS['TL_LANG']['tl_merge']['backendLink']    = 'Go back to the back end';

?>