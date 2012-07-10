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


##############################################################
#                                                            #
#  DO NOT CHANGE ANYTHING HERE! USE THE LOCAL CONFIGURATION  #
#  FILE localconfig.php TO MODIFY THE CONTAO CONFIGURATION!  #
#                                                            #
##############################################################


/**
 * -------------------------------------------------------------------------
 * GENERAL SETTINGS
 * -------------------------------------------------------------------------
 *
 * The title of the website is used in the <title> tag of the generated pages.
 *
 * The recommended default character set is UTF-8. All files of the framwork
 * are UTF-8 encoded as well. If you change the character set, make sure to
 * also change the database character set and the language file encoding.
 *
 * The administrator e-mail address is used for sending system alerts and other
 * messages concerning the content management system.
 *
 * If 'displayErrors' is set to true, error messages will be printed to the
 * screen. It is recommended to disable it for live pages.
 *
 * With option rewriteURL you can make Contao generate static URLs without
 * "index.php" (e.g. "alias.html" instead of "index.php/alias.html"). Note that
 * this feature requires Apache's mod_rewrite!
 *
 * The relative path to the Contao directory is usually set automatically.
 * However, if you are experiencing any problems, you can change it in the
 * local configuration file.
 */
$GLOBALS['TL_CONFIG']['websiteTitle']   = 'Contao Open Source CMS';
$GLOBALS['TL_CONFIG']['characterSet']   = 'utf-8';
$GLOBALS['TL_CONFIG']['adminEmail']     = '';
$GLOBALS['TL_CONFIG']['enableSearch']   = true;
$GLOBALS['TL_CONFIG']['indexProtected'] = false;
$GLOBALS['TL_CONFIG']['displayErrors']  = false;
$GLOBALS['TL_CONFIG']['logErrors']      = true;
$GLOBALS['TL_CONFIG']['rewriteURL']     = false;
$GLOBALS['TL_CONFIG']['folderUrl']      = true;
$GLOBALS['TL_CONFIG']['disableAlias']   = false;
$GLOBALS['TL_CONFIG']['minifyMarkup']   = false;
$GLOBALS['TL_CONFIG']['gzipScripts']    = false;
$GLOBALS['TL_CONFIG']['websitePath']    = null;


/**
 * -------------------------------------------------------------------------
 * DATE AND TIME SETTINGS
 * -------------------------------------------------------------------------
 *
 * Set 'timeZone' to your local timezone. If you leave it blank GMT will be
 * used as the default time zone.
 *
 *   datimFormat = show date and time
 *   dateFormat  = show date only
 *   timeFormat  = show time only
 *
 * See PHP function date() for more information.
 */
$GLOBALS['TL_CONFIG']['timeZone']    = 'GMT';
$GLOBALS['TL_CONFIG']['datimFormat'] = 'Y-m-d H:i';
$GLOBALS['TL_CONFIG']['dateFormat']  = 'Y-m-d';
$GLOBALS['TL_CONFIG']['timeFormat']  = 'H:i';


/**
 * -------------------------------------------------------------------------
 * INPUT AND SECURITY SETTINGS
 * -------------------------------------------------------------------------
 *
 * If you use the input library to handle input data, all HTML tags except
 * for these 'allowedTags' will be removed.
 *
 * In Contao 2.10, the referer check has been replaced with a request token
 * system, which you can disable here (not recommended).
 */
$GLOBALS['TL_CONFIG']['allowedTags']           = '<a><abbr><acronym><address><area><article><aside><b><big><blockquote><br><base><bdo><button><caption><cite><code><col><colgroup><dd><del><div><dfn><dl><dt><em><figure><figcaption><form><fieldset><hr><h1><h2><h3><h4><h5><h6><i><img><input><ins><label><legend><li><link><map><object><ol><optgroup><option><p><pre><param><q><section><select><small><span><strong><sub><sup><style><table><tbody><td><textarea><tfoot><th><thead><tr><tt><u><ul>';
$GLOBALS['TL_CONFIG']['disableRefererCheck']   = false;
$GLOBALS['TL_CONFIG']['disableIpCheck']        = false;
$GLOBALS['TL_CONFIG']['requestTokenWhitelist'] = array();


/**
 * -------------------------------------------------------------------------
 * DATABASE SETTINGS
 * -------------------------------------------------------------------------
 * Database drivers start with an uppercase character. Currently supported
 * databases: MySQL, MySQLi, MSSQL, Oracle, PostgreSQL, Sybase.
 *
 *   dbUser     = database user name
 *   dbPass     = database password
 *   dbHost     = database host (might be "localhost" or "127.0.0.1")
 *   dbDatabase = name of the database
 *   dbPort     = database port number (e.g. 3306 for MySQL)
 *   dbPconnect = set to true if you want to use persistent connections
 *
 * Make sure that the database character set 'dbCharset' matches the character
 * set used on your HTML pages. Note that you might have to use a different
 * spelling (e.g. UTF8 instead of UTF-8 for MySQL).
 */
$GLOBALS['TL_CONFIG']['dbDriver']    = 'MySQL';
$GLOBALS['TL_CONFIG']['dbUser']      = '';
$GLOBALS['TL_CONFIG']['dbPass']      = '';
$GLOBALS['TL_CONFIG']['dbHost']      = 'localhost';
$GLOBALS['TL_CONFIG']['dbDatabase']  = '';
$GLOBALS['TL_CONFIG']['dbPort']      = 3306;
$GLOBALS['TL_CONFIG']['dbPconnect']  = false;
$GLOBALS['TL_CONFIG']['dbCharset']   = 'UTF8';
$GLOBALS['TL_CONFIG']['dbCollation'] = 'utf8_general_ci';


/**
 * -------------------------------------------------------------------------
 * FTP SETTINGS
 * -------------------------------------------------------------------------
 *
 * Here you can enable FTP for managing files and folders ("Safe Mode Hack").
 *
 *   ftpHost = host name (e.g. domain.com or domain.com:21)
 *   ftpPath = path to installation (e.g. html/)
 *   ftpUser = FTP username
 *   ftpPass = FTP password
 */
$GLOBALS['TL_CONFIG']['useFTP']  = false;
$GLOBALS['TL_CONFIG']['ftpHost'] = '';
$GLOBALS['TL_CONFIG']['ftpPath'] = '';
$GLOBALS['TL_CONFIG']['ftpUser'] = '';
$GLOBALS['TL_CONFIG']['ftpPass'] = '';
$GLOBALS['TL_CONFIG']['ftpSSL']  = false;
$GLOBALS['TL_CONFIG']['ftpPort'] = 21;


/**
 * -------------------------------------------------------------------------
 * ENCRYPTION SETTINGS
 * -------------------------------------------------------------------------
 *
 * Please provide an encryption key that is at least 8 characters long. Note
 * that encrypted data can only be decrypted with the same key! Therefore
 * note it down and do not change it if your data is encrypted already.
 *
 *   encryptionMode   = defaults to "cfb"
 *   encryptionCipher = defaults to "rijndael 256"
 *
 * See PHP extension "mcrypt" for more information.
 */
$GLOBALS['TL_CONFIG']['encryptionKey']    = '';
$GLOBALS['TL_CONFIG']['encryptionMode']   = 'cfb';
$GLOBALS['TL_CONFIG']['encryptionCipher'] = 'rijndael-256';


/**
 * -------------------------------------------------------------------------
 * FILE UPLOAD SETTINGS
 * -------------------------------------------------------------------------
 *
 * The default files directory is "files".
 *
 *   uploadTypes = allowed file types for uploads
 *   maxFileSize = maximum file size
 *   imageWidth  = maximum image width
 *   imageHeight = maximum image height
 *   jpgQuality  = thumbnail quality in percent
 *
 * Please enter how many upload fields you want to show in the back end.
 */
$GLOBALS['TL_CONFIG']['uploadPath']     = 'files';
$GLOBALS['TL_CONFIG']['uploadTypes']    = 'jpg,jpeg,gif,png,ico,csv,doc,docx,xls,xlsx,ppt,pptx,odt,ods,odp,pdf,mp3,mp4,m4a,m4v,webm,ogg,wma,wmv,ram,rm,mov,fla,flv,swf,css,html,htm,txt,zip,rar,7z,cto';
$GLOBALS['TL_CONFIG']['maxFileSize']    = 2048000;
$GLOBALS['TL_CONFIG']['imageWidth']     = 800;
$GLOBALS['TL_CONFIG']['imageHeight']    = 600;
$GLOBALS['TL_CONFIG']['jpgQuality']     = 80;
$GLOBALS['TL_CONFIG']['uploadFields']   = 8;
$GLOBALS['TL_CONFIG']['gdMaxImgWidth']  = 3000;
$GLOBALS['TL_CONFIG']['gdMaxImgHeight'] = 3000;


/**
 * -------------------------------------------------------------------------
 * SMTP SETTINGS
 * -------------------------------------------------------------------------
 *
 * Here you can enable SMTP for sending mails. By default, mails are sent using
 * PHP function mail(). Please enter your SMTP parameters below.
 *
 *   smtpHost = host name (defaults to localhost)
 *   smtpUser = SMTP username
 *   smtpPass = SMTP password
 *   smtpEnc  = SMTP encryption ("ssl" or "tls")
 *   smtpPort = defaults to 25 or 465 for SSL
 */
$GLOBALS['TL_CONFIG']['useSMTP']  = false;
$GLOBALS['TL_CONFIG']['smtpHost'] = 'localhost';
$GLOBALS['TL_CONFIG']['smtpUser'] = '';
$GLOBALS['TL_CONFIG']['smtpPass'] = '';
$GLOBALS['TL_CONFIG']['smtpEnc']  = '';
$GLOBALS['TL_CONFIG']['smtpPort'] = 25;


/**
 * -------------------------------------------------------------------------
 * TIMEOUT SETTING
 * -------------------------------------------------------------------------
 *
 *   undoPeriod     = period of time undo steps are kept (default: 24 hours)
 *   versionPeriod  = period of time versions are kept (default: 90 days)
 *   logPeriod      = period of time log entries are kept (default: 14 days)
 *   sessionTimeout = period of time sessions are kept (default: 1 hour)
 *   lockPeriod     = period of time an account is locked (default: 5 minutes)
 */
$GLOBALS['TL_CONFIG']['undoPeriod']     = 86400;
$GLOBALS['TL_CONFIG']['versionPeriod']  = 7776000;
$GLOBALS['TL_CONFIG']['logPeriod']      = 1209600;
$GLOBALS['TL_CONFIG']['sessionTimeout'] = 3600;
$GLOBALS['TL_CONFIG']['lockPeriod']     = 300;


/**
 * -------------------------------------------------------------------------
 * DEFAULT USER CONFIGURATION
 * -------------------------------------------------------------------------
 *
 *   showHelp    = show a help text after each input field
 *   thumbnails  = show image thumbnails in the file manager
 *   debugMode   = debug the system and print results to the screen
 *   useRTE      = use the rich text editor (TinyMCE)
 *   useCE       = use the code editor (CodeMirror)
 */
$GLOBALS['TL_CONFIG']['showHelp']   = true;
$GLOBALS['TL_CONFIG']['thumbnails'] = true;
$GLOBALS['TL_CONFIG']['debugMode']  = false;
$GLOBALS['TL_CONFIG']['useRTE']     = true;
$GLOBALS['TL_CONFIG']['useCE']      = true;


/**
 * -------------------------------------------------------------------------
 * SYSTEM SETTINGS
 * -------------------------------------------------------------------------
 *
 * The number of resultsPerPage is used to limit query results in the back
 * end. It does not apply to the search engine.
 *
 * If you need custom page sections (in addition to "header", "left", "main",
 * "right" and "footer", you can define a comma separated list here.
 *
 * If you enter a maximum image width, images and media files cannot be wider
 * than this value and will not break your page layout.
 *
 * The default user owns all pages and items that are not assigned to a certain
 * user. Please enter the ID of the default user (database record).
 *
 * The default group owns all pages and items that are not assigned to a group.
 * Please enter the ID of the default group (database record).
 *
 * Default page permissions: allow everything for the owner (u1 - u6) of a page.
 * See back end module "Navigation" for more information.
 *
 * Please enter a comma separated list of allowed image types, editable files
 * and all all file types that are allowed to be downloaded.
 */
$GLOBALS['TL_CONFIG']['loginCount']           = 3;
$GLOBALS['TL_CONFIG']['resultsPerPage']       = 30;
$GLOBALS['TL_CONFIG']['maxResultsPerPage']    = 500;
$GLOBALS['TL_CONFIG']['customSections']       = '';
$GLOBALS['TL_CONFIG']['maxImageWidth']        = '';
$GLOBALS['TL_CONFIG']['defaultUser']          = 0;
$GLOBALS['TL_CONFIG']['defaultGroup']         = 0;
$GLOBALS['TL_CONFIG']['defaultChmod']         = array('u1', 'u2', 'u3', 'u4', 'u5', 'u6', 'g4', 'g5', 'g6');
$GLOBALS['TL_CONFIG']['validImageTypes']      = 'jpg,jpeg,gif,png,tif,tiff,bmp';
$GLOBALS['TL_CONFIG']['editableFiles']        = 'htm,html,css,js,txt,log,xml';
$GLOBALS['TL_CONFIG']['templateFiles']        = 'tpl,html5,xhtml';
$GLOBALS['TL_CONFIG']['allowedDownload']      = 'jpg,jpeg,gif,png,doc,xls,ppt,odt,ods,odp,pdf,mp3,wma,wmv,ram,rm,mov,zip,rar,7z';
$GLOBALS['TL_CONFIG']['installPassword']      = '';
$GLOBALS['TL_CONFIG']['liveUpdateBase']       = 'http://www.inetrobots.com/liveupdate/';
$GLOBALS['TL_CONFIG']['repository_wsdl']      = 'http://www.contao.org/services/repository.wsdl';
$GLOBALS['TL_CONFIG']['repository_languages'] = 'en,de';
$GLOBALS['TL_CONFIG']['repository_listsize']  = 10;
$GLOBALS['TL_CONFIG']['backendTheme']         = 'default';
$GLOBALS['TL_CONFIG']['inactiveModules']      = '';
$GLOBALS['TL_CONFIG']['liveUpdateId']         = '';
$GLOBALS['TL_CONFIG']['disableInsertTags']    = false;
$GLOBALS['TL_CONFIG']['rootFiles']            = array();
$GLOBALS['TL_CONFIG']['doNotCollapse']        = false;
$GLOBALS['TL_CONFIG']['urlSuffix']            = '.html';
$GLOBALS['TL_CONFIG']['exampleWebsite']       = '';
$GLOBALS['TL_CONFIG']['minPasswordLength']    = 8;
$GLOBALS['TL_CONFIG']['cacheMode']            = 'both';
$GLOBALS['TL_CONFIG']['autologin']            = 7776000;
$GLOBALS['TL_CONFIG']['staticFiles']          = '';
$GLOBALS['TL_CONFIG']['staticSystem']         = '';
$GLOBALS['TL_CONFIG']['staticPlugins']        = '';
$GLOBALS['TL_CONFIG']['disableCron']          = false;
$GLOBALS['TL_CONFIG']['coreOnlyMode']         = false;
$GLOBALS['TL_CONFIG']['addLanguageToUrl']     = false;
$GLOBALS['TL_CONFIG']['doNotRedirectEmpty']   = false;
$GLOBALS['TL_CONFIG']['useAutoItem']          = false;
$GLOBALS['TL_CONFIG']['privacyAnonymizeIp']   = true;
$GLOBALS['TL_CONFIG']['privacyAnonymizeGA']   = true;
$GLOBALS['TL_CONFIG']['bypassCache']          = false;
$GLOBALS['TL_CONFIG']['loadGoogleFonts']      = false;
