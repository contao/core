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
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_settings']['websiteTitle']        = array('Title of the website', 'Please enter the title of the website.');
$GLOBALS['TL_LANG']['tl_settings']['adminEmail']          = array('E-mail address of the system administrator', 'Please enter the e-mail address of the system administrator.');
$GLOBALS['TL_LANG']['tl_settings']['websitePath']         = array('Relative path to TYPOlight directory', 'Please enter the relative path to the directory containing the TYPOlight files (e.g. if you call the TYPOlight back end using <em>www.yourdomain.com/yourwebsite/typolight</em> the relative path would be <em>/yourwebsite</em>).');
$GLOBALS['TL_LANG']['tl_settings']['urlSuffix']           = array('URL suffix', 'The URL suffix is a file extension that is added to the URI string to simulate static documents. The default URL suffix is <em>.html</em>. Note that using no URL suffix at all can have a negative effect on your search engine ranking.');
$GLOBALS['TL_LANG']['tl_settings']['dateFormat']          = array('Date format', 'Please enter a date format as used by the PHP function date().');
$GLOBALS['TL_LANG']['tl_settings']['timeFormat']          = array('Time format', 'Please enter a time format as used by the PHP function date().');
$GLOBALS['TL_LANG']['tl_settings']['datimFormat']         = array('Date and time format', 'Please enter a date and time format as used by the PHP function date().');
$GLOBALS['TL_LANG']['tl_settings']['timeZone']            = array('Time zone', 'Please select your time zone.');
$GLOBALS['TL_LANG']['tl_settings']['characterSet']        = array('Character set', 'Please enter the character set. It is recommended to use UTF-8 to ensure that special characters are shown correctly. Do not change the character set unless you experience display errors.');
$GLOBALS['TL_LANG']['tl_settings']['encryptionKey']       = array('Encryption key', 'Here you can change the key which is used to encrypt data. Please note that encrypted data can only be decrypted with the same key. Therefore note it down and do not change it if your data is encrypted already!');
$GLOBALS['TL_LANG']['tl_settings']['uploadPath']          = array('Files directory', 'Please enter the relative path to the files directory (default: tl_files).');
$GLOBALS['TL_LANG']['tl_settings']['maxFileSize']         = array('Maximum upload file size', 'Please enter the maximum upload file size in bytes (default: 2 MB = 2048 kB = 2048000 bytes).');
$GLOBALS['TL_LANG']['tl_settings']['imageWidth']          = array('Maximum image width', 'Please enter the maximum image width for file uploads in pixels.');
$GLOBALS['TL_LANG']['tl_settings']['imageHeight']         = array('Maximum image height', 'Please enter the maximum image height for file uploads in pixels.');
$GLOBALS['TL_LANG']['tl_settings']['jpgQuality']          = array('Thumbnail quality', 'Please enter the thumbnail quality in percent (applies to JPGs only).');
$GLOBALS['TL_LANG']['tl_settings']['uploadFields']        = array('Simultaneous file uploads', 'Please enter the maximum number of simultaneous file uploads.');
$GLOBALS['TL_LANG']['tl_settings']['undoPeriod']          = array('Storage time for undo steps', 'Please enter the storage time for undo steps in seconds (default: 24 hours = 86400 seconds).');
$GLOBALS['TL_LANG']['tl_settings']['versionPeriod']       = array('Storage time for versions', 'Please enter the storage time for different versions of a record in seconds (default: 90 days = 7776000 seconds).');
$GLOBALS['TL_LANG']['tl_settings']['logPeriod']           = array('Storage time for log entries', 'Please enter the storage time for log entries in seconds (default: 14 days = 1209600 seconds).');
$GLOBALS['TL_LANG']['tl_settings']['sessionTimeout']      = array('Session timeout', 'Please enter the maximum lifetime of a session in seconds (default: 60 minutes = 3600 seconds). If there is no user input for longer than this period of time, the current session will be deleted and the user has to log in again.');
$GLOBALS['TL_LANG']['tl_settings']['lockPeriod']          = array('Waiting time in case of a locked account', 'Please enter the period of time a user has to wait before he is allowed to log in to a locked account again (default: 5 minutes = 300 seconds). This feature is supposed to make brute force attacks a little bit more difficult.');
$GLOBALS['TL_LANG']['tl_settings']['useSMTP']             = array('Use SMTP to send mails', 'By default TYPOlight uses PHP <em>mail()</em> to send mails. Here you can choose to use an SMTP server instead.');
$GLOBALS['TL_LANG']['tl_settings']['smtpHost']            = array('SMTP hostname', 'Please enter the host name of your SMTP server (defaults to localhost).');
$GLOBALS['TL_LANG']['tl_settings']['smtpPort']            = array('SMTP port number', 'Please enter the port number of your SMTP server (defaults to 25 or 465 for SSL).');
$GLOBALS['TL_LANG']['tl_settings']['smtpUser']            = array('SMTP username', 'If your SMTP server requires authentication, please enter a username here.');
$GLOBALS['TL_LANG']['tl_settings']['smtpPass']            = array('SMTP password', 'If your SMTP server requires authentication, please enter a password here.');
$GLOBALS['TL_LANG']['tl_settings']['useFTP']              = array('Use FTP to modify files', 'If PHP scripts are not allowed to modify files on your server due to safe mode or file permission restrictions, you can enable file operations via FTP here.');
$GLOBALS['TL_LANG']['tl_settings']['ftpHost']             = array('FTP hostname', 'Please enter the host name of your FTP server (e.g. <em>domain.com</em> or <em>domain.com:21</em>).');
$GLOBALS['TL_LANG']['tl_settings']['ftpPath']             = array('FTP path', 'Please enter path to your TYPOlight installation as seen from the FTP root (e.g. <em>html/typolight/</em>).');
$GLOBALS['TL_LANG']['tl_settings']['ftpUser']             = array('FTP username', 'Please enter the username for the FTP server.');
$GLOBALS['TL_LANG']['tl_settings']['ftpPass']             = array('FTP password', 'Please enter the password for the FTP server.');
$GLOBALS['TL_LANG']['tl_settings']['customSections']      = array('Custom layout sections', 'Here you can enter a comma separated list of custom layout sections. You can then use these sections in module <em>page layout</em> in addition to <em>header</em>, <em>left</em>, <em>main</em>, <em>right</em> and <em>footer</em>.');
$GLOBALS['TL_LANG']['tl_settings']['maxImageWidth']       = array('Maximum front end image width', 'Here you can define the maximum width of images and media files. If the width of a content element exceeds this value, it will be scaled down automatically.');
$GLOBALS['TL_LANG']['tl_settings']['validImageTypes']     = array('Valid image file types', 'Please enter a comma separated list of extensions of supported image types. Do not include file types that can not be handled by TYPOlight or your own script.');
$GLOBALS['TL_LANG']['tl_settings']['editableFiles']       = array('Editable file types', 'Please enter a comma separated list of extensions of file types that can be edited in the source editor.');
$GLOBALS['TL_LANG']['tl_settings']['allowedDownload']     = array('Valid download file types', 'Please enter a comma separated list of extensions of downloadable file types. For security reasons, file downloads are limited to the files directory and its subfolders.');
$GLOBALS['TL_LANG']['tl_settings']['uploadTypes']         = array('Valid upload file types', 'Please enter a comma separated list of extensions of uploadable file types. For security reasons, file uploads are limited to the files directory and its subfolders.');
$GLOBALS['TL_LANG']['tl_settings']['allowedTags']         = array('Allowed HTML tags', 'Please enter a list of allowed HTML tags. All other tags will be removed from the user input.');
$GLOBALS['TL_LANG']['tl_settings']['displayErrors']       = array('Display error messages', 'If you choose this option, error messages will be printed to the screen. This option is therefore only recommended for system modifications!');
$GLOBALS['TL_LANG']['tl_settings']['debugMode']           = array('Debug mode', 'If you choose this option, certain runtime information (e.g. database queries) will be printed to the screen. This option is therefore only recommended for system modifications!');
$GLOBALS['TL_LANG']['tl_settings']['rewriteURL']          = array('Rewrite URL', 'With this option you can make TYPOlight generate static URLs without "index.php" (e.g. <em>alias.html</em> instead of <em>index.php/alias.html</em>). This feature requires the Apache module <em>mod_rewrite</em>!');
$GLOBALS['TL_LANG']['tl_settings']['disableRefererCheck'] = array('Disable referer check', 'Choose this option if you do not want TYPOlight to check the referer address when a form is submitted. Please note that disabling the referer check is a big security risk! Typically, referer issues are caused by security or anonymizer tools.');
$GLOBALS['TL_LANG']['tl_settings']['disableAlias']        = array('Disable page alias usage', 'If you choose this option, TYPOlight will use the numeric ID of a page instead of its page alias to generate URLs (e.g. <em>index.php?id=12</em> instead of <em>home.html</em>).');
$GLOBALS['TL_LANG']['tl_settings']['enableGZip']          = array('Enable GZip compression', 'If you choose this option, front end and back end pages will be compressed before they are sent to the browser.');
$GLOBALS['TL_LANG']['tl_settings']['useDompdf']           = array('Use DOMPDF to generate PDF files', 'DOMPDF does not support unicode characters and takes longer than TCPDF to generate PDFs. TCPDF supports unicode characters but does not parse stylse sheets, so you cannot format its output easily.');
$GLOBALS['TL_LANG']['tl_settings']['resultsPerPage']      = array('Records per page', 'Here you can define the default number of records that are displayed in the back end.');
$GLOBALS['TL_LANG']['tl_settings']['enableSearch']        = array('Enable search engine', 'Automatically index front end pages so they can be found in the search results.');
$GLOBALS['TL_LANG']['tl_settings']['indexProtected']      = array('Index protected pages', 'Use this option with care and always exclude personalized pages from being indexed!');
$GLOBALS['TL_LANG']['tl_settings']['dynamicStopword']     = array('Dynamic stop words', 'Dynamic stop words are words that occur very often and are therefore ignored.');
$GLOBALS['TL_LANG']['tl_settings']['minWordLength']       = array('Minimum word length', 'Do not index words with less than 3 characters (a smaller number will increase the size of your database).');
$GLOBALS['TL_LANG']['tl_settings']['backendTheme']        = array('Back end theme', 'Please select a back end theme.');
$GLOBALS['TL_LANG']['tl_settings']['inactiveModules']     = array('Inactive back end modules', 'Here you can deactivate unneeded back end modules.');
$GLOBALS['TL_LANG']['tl_settings']['doNotCollapse']       = array('Do not collapse elements', 'Do not collapse content elements and other resources when previewed in the back end.');
$GLOBALS['TL_LANG']['tl_settings']['pNewLine']            = array('Use paragraphs to generate new lines', 'In the rich text editor, use paragraphs instead of line breaks to generate new lines.');
$GLOBALS['TL_LANG']['tl_settings']['defaultUser']         = array('Default page user', 'By default, a page belongs to the same user as its parent page. However, if no user is assigned, the default user will be the owner of the page. If there is no user and no default user, there will not be any access restrictions!');
$GLOBALS['TL_LANG']['tl_settings']['defaultGroup']        = array('Default page group', 'By default, a page belongs to the same group as its parent page. However, if no group is assigned, the default group will be the owner of the page. If there is no group and no default group, there will not be any access restrictions!');
$GLOBALS['TL_LANG']['tl_settings']['defaultChmod']        = array('Default page permissions', 'By default, a page uses the same permission settings as its parent page. However, if no permission settings are defined, these default permissions will be used.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_settings']['edit'] = 'Edit local configuration';

?>