<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Newsletter
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['email']   = array('E-mail address', 'Please enter the recipient\'s e-mail address.');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['active']  = array('Activate recipient', 'Recipients are usually activated automatically (double-opt-in).');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['addedOn'] = array('Subscription date', 'The date of subscription.');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['ip']      = array('IP address', 'The IP address of the subscriber.');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['token']   = array('Token key', 'The confirmation token of the subscription.');


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
