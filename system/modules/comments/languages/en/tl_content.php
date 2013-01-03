<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * PHP version 5
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Comments
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_content']['com_order']          = array('Sort order', 'Please choose the sort order.');
$GLOBALS['TL_LANG']['tl_content']['com_perPage']        = array('Items per page', 'The number of comments per page. Set to 0 to disable pagination.');
$GLOBALS['TL_LANG']['tl_content']['com_moderate']       = array('Moderate', 'Approve comments before they are published on the website.');
$GLOBALS['TL_LANG']['tl_content']['com_bbcode']         = array('Allow BBCode', 'Allow visitors to format their comments with BBCode.');
$GLOBALS['TL_LANG']['tl_content']['com_requireLogin']   = array('Require login to comment', 'Allow only authenticated users to create comments.');
$GLOBALS['TL_LANG']['tl_content']['com_disableCaptcha'] = array('Disable the security question', 'Here you can disable the security question (not recommended).');
$GLOBALS['TL_LANG']['tl_content']['com_template']       = array('Comments template', 'Here you can select the comments template.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_content']['comment_legend']  = 'Comment settings';

?>