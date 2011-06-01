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
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_install']['installTool'] = array('Contao install tool', 'Install tool login');
$GLOBALS['TL_LANG']['tl_install']['locked']      = array('The install tool has been locked', 'For security reasons, the install tool has been locked after a wrong password had been entered more than three times in a row. To unlock it, open the local configuration file and set <em>installCount</em> to <em>0</em>.');
$GLOBALS['TL_LANG']['tl_install']['password']    = array('Password', 'Please enter the install tool password. The install tool password is not the same as the Contao back end password.');
$GLOBALS['TL_LANG']['tl_install']['changePass']  = array('Install tool password', 'To additionally secure the Contao install tool, you can either rename or completely remove the <strong>contao/install.php</strong> file.');
$GLOBALS['TL_LANG']['tl_install']['encryption']  = array('Generate an encryption key', 'This key is used to store encrypted data. Please note that encrypted data can only be decrypted with this key! Therefore note it down and do not change it if there is encrypted data already. Leave empty to generate a random key.');
$GLOBALS['TL_LANG']['tl_install']['database']    = array('Check database connection', 'Please enter your database connection parameters.');
$GLOBALS['TL_LANG']['tl_install']['collation']   = array('Collation', 'For more information see the <a href="http://dev.mysql.com/doc/refman/5.1/en/charset-unicode-sets.html" onclick="window.open(this.href); return false;">MySQL manual</a>.');
$GLOBALS['TL_LANG']['tl_install']['update']      = array('Update database tables', 'Please note that the update assistant has only been tested with MySQL and MySQLi drivers. If you are using a different database (e.g. Oracle), you might have to install or update your database manually. In this case, please go to <strong>system/modules</strong> and search all its subfolders for <strong>config/database.sql</strong> files.');
$GLOBALS['TL_LANG']['tl_install']['template']    = array('Import a template', 'Here you can import an <em>.sql</em> file from the <em>templates</em> directory with a pre-configured example website. Existing data will be deleted! If you only want to import a theme, please use the theme manager in the Contao back end instead.');
$GLOBALS['TL_LANG']['tl_install']['admin']       = array('Create an admin user', 'If you have imported the example website, the admin\'s username is <strong>k.jones</strong> and the password is <strong>kevinjones</strong>. See the example website (front end) for more information.');
$GLOBALS['TL_LANG']['tl_install']['completed']   = array('Congratulations!', 'Now please log into the <a href="contao/index.php">Contao back end</a> and check the system settings. Then visit your website to make sure that Contao is working correctly.');
$GLOBALS['TL_LANG']['tl_install']['ftp']         = array('Modify files via FTP', 'Your server does not support file access via PHP; most likely PHP runs as Apache module under a different user. Therefore please enter your FTP login details, so Contao can modify files via FTP (Safe Mode Hack).');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_install']['accept']         = 'Accept license';
$GLOBALS['TL_LANG']['tl_install']['beLogin']        = 'Contao back end login';
$GLOBALS['TL_LANG']['tl_install']['passError']      = 'Please enter a password to prevent unauthorized access!';
$GLOBALS['TL_LANG']['tl_install']['passConfirm']    = 'A custom password has been set.';
$GLOBALS['TL_LANG']['tl_install']['passSave']       = 'Save password';
$GLOBALS['TL_LANG']['tl_install']['keyError']       = 'Please create an encryption key!';
$GLOBALS['TL_LANG']['tl_install']['keyLength']      = 'An encryption key has to be at least 12 characters long!';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm']     = 'An encryption key has been created.';
$GLOBALS['TL_LANG']['tl_install']['keyCreate']      = 'Generate encryption key';
$GLOBALS['TL_LANG']['tl_install']['keySave']        = 'Generate or save key';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm']      = 'Database connection established.';
$GLOBALS['TL_LANG']['tl_install']['dbError']        = 'Not connected to a database!';
$GLOBALS['TL_LANG']['tl_install']['dbDriver']       = 'Driver';
$GLOBALS['TL_LANG']['tl_install']['dbHost']         = 'Host';
$GLOBALS['TL_LANG']['tl_install']['dbUsername']     = 'Username';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase']     = 'Database';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent']   = 'Persistent connection';
$GLOBALS['TL_LANG']['tl_install']['dbCharset']      = 'Character set';
$GLOBALS['TL_LANG']['tl_install']['dbCollation']    = 'Collation';
$GLOBALS['TL_LANG']['tl_install']['dbPort']         = 'Port number';
$GLOBALS['TL_LANG']['tl_install']['dbSave']         = 'Save database settings';
$GLOBALS['TL_LANG']['tl_install']['collationInfo']  = 'Changing the collation will affect all tables with a <em>tl_</em> prefix.';
$GLOBALS['TL_LANG']['tl_install']['updateError']    = 'The database is not up to date!';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm']  = 'The database is up to date.';
$GLOBALS['TL_LANG']['tl_install']['updateSave']     = 'Update database';
$GLOBALS['TL_LANG']['tl_install']['updateX']        = 'It seems that you are upgrading from a Contao version prior to version %s. If that is the case, <strong>it is required to run the version %s update</strong> to ensure the integrity of your data!';
$GLOBALS['TL_LANG']['tl_install']['updateXrun']     = 'Run version %s update';
$GLOBALS['TL_LANG']['tl_install']['importError']    = 'Please choose a template file!';
$GLOBALS['TL_LANG']['tl_install']['importConfirm']  = 'Template imported on %s';
$GLOBALS['TL_LANG']['tl_install']['importWarn']     = 'Any existing data will be deleted!';
$GLOBALS['TL_LANG']['tl_install']['templates']      = 'Templates';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate']  = 'Do not truncate tables';
$GLOBALS['TL_LANG']['tl_install']['importSave']     = 'Import template';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = 'Any existing data will be deleted! Do you really want to continue?';
$GLOBALS['TL_LANG']['tl_install']['adminError']     = 'Please fill in all fields to create an admin user!';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm']   = 'An admin user has been created.';
$GLOBALS['TL_LANG']['tl_install']['adminSave']      = 'Create admin account';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'You have successfully installed Contao.';
$GLOBALS['TL_LANG']['tl_install']['ftpHost']        = 'FTP hostname';
$GLOBALS['TL_LANG']['tl_install']['ftpPath']        = 'Relative path to Contao directory (e.g. <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser']        = 'FTP username';
$GLOBALS['TL_LANG']['tl_install']['ftpPass']        = 'FTP password';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4']       = 'Secure connection';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL']         = 'Connect via FTP-SSL';
$GLOBALS['TL_LANG']['tl_install']['ftpPort']        = 'FTP port';
$GLOBALS['TL_LANG']['tl_install']['ftpSave']        = 'Save FTP settings';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError']   = 'Could not connect to FTP server %s';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError']   = 'Could not login as "%s"';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError']   = 'Could not locate Contao directory %s';


/**
 * Updater
 */
$GLOBALS['TL_LANG']['tl_install']['CREATE']       = 'Create new tables';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD']    = 'Add new columns';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Change existing columns';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP']   = 'Drop existing columns';
$GLOBALS['TL_LANG']['tl_install']['DROP']         = 'Drop existing tables';

?>