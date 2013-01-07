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
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_settings']['websiteTitle']        = array('Website title', 'Please enter the title of the website.');
$GLOBALS['TL_LANG']['tl_settings']['adminEmail']          = array('E-mail address of the system administrator', 'Auto-generated messages like subscription confirmation e-mails will be sent to this address.');
$GLOBALS['TL_LANG']['tl_settings']['dateFormat']          = array('Date format', 'The date format string will be parsed with the PHP date() function.');
$GLOBALS['TL_LANG']['tl_settings']['timeFormat']          = array('Time format', 'The time format string will be parsed with the PHP date() function.');
$GLOBALS['TL_LANG']['tl_settings']['datimFormat']         = array('Date and time format', 'The date and time format string will be parsed with the PHP date() function.');
$GLOBALS['TL_LANG']['tl_settings']['timeZone']            = array('Time zone', 'Please select the server time zone.');
$GLOBALS['TL_LANG']['tl_settings']['websitePath']         = array('Relative path to the Contao directory', 'The relative path to the Contao directory is usually set automatically by the install tool.');
$GLOBALS['TL_LANG']['tl_settings']['characterSet']        = array('Character set', 'It is recommended to use UTF-8, so special characters are displayed correctly.');
$GLOBALS['TL_LANG']['tl_settings']['customSections']      = array('Custom layout sections', 'Here you can enter a comma separated list of custom layout sections.');
$GLOBALS['TL_LANG']['tl_settings']['disableCron']         = array('Disable the command scheduler', 'Disable the periodic command scheduler and execute the cron.php script by a real cron job (which you have to set up manually).');
$GLOBALS['TL_LANG']['tl_settings']['minifyMarkup']        = array('Minify the markup', 'Minify the HTML markup before it is sent to the browser (requires the PHP tidy extension).');
$GLOBALS['TL_LANG']['tl_settings']['gzipScripts']         = array('Compress scripts', 'Create a compressed version of the combined CSS and JavaScript files. Requires adjustments of the .htaccess file.');
$GLOBALS['TL_LANG']['tl_settings']['resultsPerPage']      = array('Items per page', 'Here you can define the number of items per page in the back end.');
$GLOBALS['TL_LANG']['tl_settings']['maxResultsPerPage']   = array('Maximum items per page', 'This overall limit takes effect if a user chooses the "show all records" option.');
$GLOBALS['TL_LANG']['tl_settings']['doNotCollapse']       = array('Do not collapse elements', 'Do not collapse elements in the back end preview.');
$GLOBALS['TL_LANG']['tl_settings']['urlSuffix']           = array('URL suffix', 'The URL suffix will be added to the URI string to simulate static documents.');
$GLOBALS['TL_LANG']['tl_settings']['cacheMode']           = array('Cache mode', 'Here you can select the cache mode.');
$GLOBALS['TL_LANG']['tl_settings']['privacyAnonymizeIp']  = array('Anonymize IP addresses', 'Anonymize any IP address that is stored in the database, except in the <em>tl_session</em> table (the IP address is bound to the session for security reasons).');
$GLOBALS['TL_LANG']['tl_settings']['privacyAnonymizeGA']  = array('Anonymize Google Analytics', 'Anonymize the IP addresses that are sent to Google Analytics.');
$GLOBALS['TL_LANG']['tl_settings']['rewriteURL']          = array('Rewrite URLs', 'Make Contao generate static URLs without the index.php fragment. This feature requires "mod_rewrite", renaming the ".htaccess.default" file to ".htaccess" and adjusting the RewriteBase as necessary.');
$GLOBALS['TL_LANG']['tl_settings']['addLanguageToUrl']    = array('Add the language to the URL', 'Add the language string as first URL parameter (e.g. <em>http://domain.tld/en/</em>).');
$GLOBALS['TL_LANG']['tl_settings']['doNotRedirectEmpty']  = array('Do not redirect empty URLs', 'For an empty URL display the website instead of redirecting to the language root page (not recommended).');
$GLOBALS['TL_LANG']['tl_settings']['useAutoItem']         = array('Use the auto_item parameter', 'Skip the <em>items/</em> or <em>events/</em> fragment in the URL and automatically discover the item based on the <em>auto_item</em> parameter.');
$GLOBALS['TL_LANG']['tl_settings']['disableAlias']        = array('Disable page alias usage', 'Use the numeric ID of a page or article instead of its alias.');
$GLOBALS['TL_LANG']['tl_settings']['allowedTags']         = array('Allowed HTML tags', 'Here you can enter a list of allowed HTML tags that will not be stripped.');
$GLOBALS['TL_LANG']['tl_settings']['debugMode']           = array('Enable debug mode', 'Print certain runtime information like database queries to the screen.');
$GLOBALS['TL_LANG']['tl_settings']['coreOnlyMode']        = array('Run in safe mode', 'Run Contao in safe mode and load only core modules.');
$GLOBALS['TL_LANG']['tl_settings']['lockPeriod']          = array('Account locking time', 'An account will be locked if a wrong password is entered three times in a row.');
$GLOBALS['TL_LANG']['tl_settings']['displayErrors']       = array('Display error messages', 'Print error messages to the screen (not recommended for productional sites).');
$GLOBALS['TL_LANG']['tl_settings']['logErrors']           = array('Log error messages', 'Write error messages to the error log file (<em>system/logs/error.log</em>).');
$GLOBALS['TL_LANG']['tl_settings']['disableRefererCheck'] = array('Disable request tokens', 'Do not check the request token when a form is submitted. Attention: potential security risk!');
$GLOBALS['TL_LANG']['tl_settings']['disableIpCheck']      = array('Disable IP check', 'Do not bind sessions to IP addresses. Choosing this option is a potential security risk!');
$GLOBALS['TL_LANG']['tl_settings']['allowedDownload']     = array('Download file types', 'Here you can enter a comma separated list of downloadable file types.');
$GLOBALS['TL_LANG']['tl_settings']['validImageTypes']     = array('Image file types', 'Here you can enter a comma separated list of file types that can be handled by the image class.');
$GLOBALS['TL_LANG']['tl_settings']['editableFiles']       = array('Editable file types', 'Here you can enter a comma separated list of file types that can be edited in the source editor.');
$GLOBALS['TL_LANG']['tl_settings']['templateFiles']       = array('Template file types', 'Here you can enter a comma separated list of supported template file types.');
$GLOBALS['TL_LANG']['tl_settings']['maxImageWidth']       = array('Maximum front end width', 'If the width of an image or movie exceeds this value, it will be adjusted automatically.');
$GLOBALS['TL_LANG']['tl_settings']['jpgQuality']          = array('JPG thumbnail quality', 'Here you can enter the JPG thumbnail quality in percent.');
$GLOBALS['TL_LANG']['tl_settings']['gdMaxImgWidth']       = array('Maximum GD image width', 'Here you can enter the maximum image width that the GD library shall try to handle.');
$GLOBALS['TL_LANG']['tl_settings']['gdMaxImgHeight']      = array('Maximum GD image height', 'Here you can enter the maximum image height that the GD library shall try to handle.');
$GLOBALS['TL_LANG']['tl_settings']['uploadPath']          = array('Files directory', 'Here you can set the relative path to the Contao files directory.');
$GLOBALS['TL_LANG']['tl_settings']['uploadTypes']         = array('Upload file types', 'Here you can enter a comma separated list of uploadable file types.');
$GLOBALS['TL_LANG']['tl_settings']['uploadFields']        = array('Simultaneous file uploads', 'Here you can enter the maximum number of simultaneous file uploads.');
$GLOBALS['TL_LANG']['tl_settings']['maxFileSize']         = array('Maximum upload file size', 'Here you can enter the maximum upload file size in bytes (1 MB = 1000 kB = 1000000 byte).');
$GLOBALS['TL_LANG']['tl_settings']['imageWidth']          = array('Maximum image width', 'Here you can enter the maximum width for image uploads in pixels.');
$GLOBALS['TL_LANG']['tl_settings']['imageHeight']         = array('Maximum image height', 'Here you can enter the maximum height for image uploads in pixels.');
$GLOBALS['TL_LANG']['tl_settings']['enableSearch']        = array('Enable searching', 'Index pages so they can be searched.');
$GLOBALS['TL_LANG']['tl_settings']['indexProtected']      = array('Index protected pages', 'Use this option carefully and always exclude personalized pages from being indexed!');
$GLOBALS['TL_LANG']['tl_settings']['useSMTP']             = array('Send e-mails via SMTP', 'Use an SMTP server instead of the PHP mail() function to send e-mails.');
$GLOBALS['TL_LANG']['tl_settings']['smtpHost']            = array('SMTP hostname', 'Please enter the host name of the SMTP server.');
$GLOBALS['TL_LANG']['tl_settings']['smtpUser']            = array('SMTP username', 'Here you can enter the SMTP username.');
$GLOBALS['TL_LANG']['tl_settings']['smtpPass']            = array('SMTP password', 'Here you can enter the SMTP password.');
$GLOBALS['TL_LANG']['tl_settings']['smtpEnc']             = array('SMTP encryption', 'Here you can choose an encryption method (SSL or TLS).');
$GLOBALS['TL_LANG']['tl_settings']['smtpPort']            = array('SMTP port number', 'Please enter the port number of the SMTP server.');
$GLOBALS['TL_LANG']['tl_settings']['inactiveModules']     = array('Inactive extensions', 'Here you can deactivate unneeded extensions.');
$GLOBALS['TL_LANG']['tl_settings']['undoPeriod']          = array('Storage time for undo steps', 'Here you can enter the storage time for undo steps in seconds (24 hours = 86400 seconds).');
$GLOBALS['TL_LANG']['tl_settings']['versionPeriod']       = array('Storage time for versions', 'Here you can enter the storage time for different versions of a record in seconds (90 days = 7776000 seconds).');
$GLOBALS['TL_LANG']['tl_settings']['logPeriod']           = array('Storage time for log entries', 'Here you can enter the storage time for log entries in seconds (14 days = 1209600 seconds).');
$GLOBALS['TL_LANG']['tl_settings']['sessionTimeout']      = array('Session timeout', 'Here you can enter the maximum lifetime of a session in seconds (60 minutes = 3600 seconds).');
$GLOBALS['TL_LANG']['tl_settings']['autologin']           = array('Auto login period', 'Here you can set the front end auto login period (90 days = 7776000 seconds).');
$GLOBALS['TL_LANG']['tl_settings']['defaultUser']         = array('Default page owner', 'Here you can select a user as the default owner of a page.');
$GLOBALS['TL_LANG']['tl_settings']['defaultGroup']        = array('Default page group', 'Here you can select a group as the default owner of a page.');
$GLOBALS['TL_LANG']['tl_settings']['defaultChmod']        = array('Default access rights', 'Please assign the default access rights for pages and articles.');
$GLOBALS['TL_LANG']['tl_settings']['liveUpdateBase']      = array('Live Update URL', 'Here you can enter the live update URL.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_settings']['title_legend']    = 'Website title';
$GLOBALS['TL_LANG']['tl_settings']['date_legend']     = 'Date and time';
$GLOBALS['TL_LANG']['tl_settings']['global_legend']   = 'Global configuration';
$GLOBALS['TL_LANG']['tl_settings']['backend_legend']  = 'Back end configuration';
$GLOBALS['TL_LANG']['tl_settings']['frontend_legend'] = 'Front end configuration';
$GLOBALS['TL_LANG']['tl_settings']['cache_legend']    = 'Cache configuration';
$GLOBALS['TL_LANG']['tl_settings']['privacy_legend']  = 'Privacy settings';
$GLOBALS['TL_LANG']['tl_settings']['security_legend'] = 'Security settings';
$GLOBALS['TL_LANG']['tl_settings']['files_legend']    = 'Files and images';
$GLOBALS['TL_LANG']['tl_settings']['uploads_legend']  = 'Upload settings';
$GLOBALS['TL_LANG']['tl_settings']['search_legend']   = 'Search engine settings';
$GLOBALS['TL_LANG']['tl_settings']['smtp_legend']     = 'SMTP configuration';
$GLOBALS['TL_LANG']['tl_settings']['ftp_legend']      = 'Safe Mode Hack';
$GLOBALS['TL_LANG']['tl_settings']['modules_legend']  = 'Inactive extensions';
$GLOBALS['TL_LANG']['tl_settings']['timeout_legend']  = 'Timeout values';
$GLOBALS['TL_LANG']['tl_settings']['chmod_legend']    = 'Default access rights';
$GLOBALS['TL_LANG']['tl_settings']['update_legend']   = 'Live Update';
$GLOBALS['TL_LANG']['tl_settings']['static_legend']   = 'Static resources';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_settings']['edit']    = 'Edit the local configuration';
$GLOBALS['TL_LANG']['tl_settings']['both']    = 'Use the server and the browser cache';
$GLOBALS['TL_LANG']['tl_settings']['server']  = 'Use only the server cache';
$GLOBALS['TL_LANG']['tl_settings']['browser'] = 'Use only the browser cache';
$GLOBALS['TL_LANG']['tl_settings']['none']    = 'Disable caching';

?>