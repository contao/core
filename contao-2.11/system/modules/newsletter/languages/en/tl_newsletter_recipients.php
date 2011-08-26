<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Newsletter
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['email']   = array('E-mail address', 'Please enter the recipient\'s e-mail address.');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['active']  = array('Activate recipient', 'Recipients are usually activated automatically (double-opt-in).');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['ip']      = array('IP address', 'The IP address of the subscriber.');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['addedOn'] = array('Subscription date', 'The date of subscription.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['email_legend'] = 'E-mail address';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['confirm']    = '%s new recipients have been imported.';
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['invalid']    = '%s invalid entries have been skipped.';
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['subscribed'] = 'subscribed on %s';
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['manually']   = 'added manually';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['new']        = array('Add recipient', 'Add a new recipient');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['show']       = array('Recipient details', 'Show the details of recipient ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['edit']       = array('Edit recipient', 'Edit recipient ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['copy']       = array('Duplicate recipient', 'Duplicate recipient ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['delete']     = array('Delete recipient', 'Delete recipient ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['editheader'] = array('Edit channel', 'Edit the channel settings');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['toggle']     = array('Activate/deactivate recipient', 'Activate/deactivate recipient ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['import']     = array('CSV import', 'Import recipients from a CSV file');

?>