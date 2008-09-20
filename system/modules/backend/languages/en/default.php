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
 * Error messages
 */
$GLOBALS['TL_LANG']['ERR']['general']        = 'An error occurred!';
$GLOBALS['TL_LANG']['ERR']['unique']         = 'Duplicate entry in field "%s"!';
$GLOBALS['TL_LANG']['ERR']['mandatory']      = 'Please fill in field "%s"!';
$GLOBALS['TL_LANG']['ERR']['minlength']      = 'Field "%s" has to be at least %d characters long!';
$GLOBALS['TL_LANG']['ERR']['digit']          = 'Please enter digits only!';
$GLOBALS['TL_LANG']['ERR']['prcnt']          = 'Please enter a percentage between 0 und 100!';
$GLOBALS['TL_LANG']['ERR']['alpha']          = 'Please enter alphabetic characters only!';
$GLOBALS['TL_LANG']['ERR']['alnum']          = 'Please enter alphanumeric characters only!';
$GLOBALS['TL_LANG']['ERR']['phone']          = 'Please enter a valid phone number!';
$GLOBALS['TL_LANG']['ERR']['extnd']          = 'For security reasons you can not use these characters (=<>&/()#) here!';
$GLOBALS['TL_LANG']['ERR']['email']          = 'Please enter a valid e-mail address!';
$GLOBALS['TL_LANG']['ERR']['url']            = 'Please enter a valid URL format and encode special characters!';
$GLOBALS['TL_LANG']['ERR']['date']           = 'Please enter the date as "%s"!';
$GLOBALS['TL_LANG']['ERR']['time']           = 'Please enter the time as "%s"!';
$GLOBALS['TL_LANG']['ERR']['dateTime']       = 'Please enter date and time as "%s"!';
$GLOBALS['TL_LANG']['ERR']['noSpace']        = 'Field "%s" must not contain any whitespace characters!';
$GLOBALS['TL_LANG']['ERR']['filesize']       = 'The maximum size for file uploads is %s kB!';
$GLOBALS['TL_LANG']['ERR']['filetype']       = 'File type "%s" is not allowed to be uploaded!';
$GLOBALS['TL_LANG']['ERR']['filepartial']    = 'File %s was only partially uploaded!';
$GLOBALS['TL_LANG']['ERR']['filewidth']      = 'File %s exceeds the maximum image width of %d pixels!';
$GLOBALS['TL_LANG']['ERR']['fileheight']     = 'File %s exceeds the maximum image height of %d pixels!';
$GLOBALS['TL_LANG']['ERR']['invalidReferer'] = 'Access denied! The current host address (%s) does not match the referer host address (%s)!';
$GLOBALS['TL_LANG']['ERR']['invalidPass']    = 'Invalid password!';
$GLOBALS['TL_LANG']['ERR']['passwordLength'] = 'A password has to be at least %d characters long!';
$GLOBALS['TL_LANG']['ERR']['passwordName']   = 'Your username and password must not be the same!';
$GLOBALS['TL_LANG']['ERR']['passwordMatch']  = 'The passwords did not match!';
$GLOBALS['TL_LANG']['ERR']['accountLocked']  = 'This account has been locked! You can log in again in %d minutes.';
$GLOBALS['TL_LANG']['ERR']['invalidLogin']   = 'Login failed!';
$GLOBALS['TL_LANG']['ERR']['invalidColor']   = 'Invalid color format!';
$GLOBALS['TL_LANG']['ERR']['all_fields']     = 'Please select at least one field!';
$GLOBALS['TL_LANG']['ERR']['aliasExists']    = 'Page alias "%s" already exists!';
$GLOBALS['TL_LANG']['ERR']['importFolder']   = 'Folder "%s" cannot be imported!';


/**
 * Page types
 */
$GLOBALS['TL_LANG']['PTY']['regular']   = array('Regular page', 'A regular page contains articles and content elements. It is the default page type.');
$GLOBALS['TL_LANG']['PTY']['redirect']  = array('Redirect to an external URL', 'This page automatically redirects to an external URL. It works like an hyperlink.');
$GLOBALS['TL_LANG']['PTY']['forward']   = array('Forward to another page', 'This page type automatically forwards the visitor to another page of the website. It redirects using a page ID or a page alias.');
$GLOBALS['TL_LANG']['PTY']['root']      = array('Root of a new website', 'If you want to create more than one website within the same page tree, you can define a new starting point here. A root page contains a DNS entry or a language or both.');
$GLOBALS['TL_LANG']['PTY']['error_403'] = array('Error 403 (access denied)', 'This page is shown when a user tries to access a protected page without permission. If this error page is put into a certain website root, its usage will be limited to the particular website.');
$GLOBALS['TL_LANG']['PTY']['error_404'] = array('Error 404 (page not found)', 'This page is shown when a user tries to access an unavailable page. If this error page is put into a certain website root, its usage will be limited to the particular website.');


/**
 * File operation permissions
 */
$GLOBALS['TL_LANG']['FOP']['fop'] = array('File operation permissions', 'The file operation permissions apply to the enabled folders only.');
$GLOBALS['TL_LANG']['FOP']['f1']  = 'Upload files to the server';
$GLOBALS['TL_LANG']['FOP']['f2']  = 'Edit, copy or move files and folders';
$GLOBALS['TL_LANG']['FOP']['f3']  = 'Delete single files and empty folders';
$GLOBALS['TL_LANG']['FOP']['f4']  = 'Delete folders including all files and subfolders (!)';
$GLOBALS['TL_LANG']['FOP']['f5']  = 'Edit files in the source editor';


/**
 * CHMOD levels
 */
$GLOBALS['TL_LANG']['CHMOD']['editpage']       = 'Edit page';
$GLOBALS['TL_LANG']['CHMOD']['editnavigation'] = 'Edit page hierarchy';
$GLOBALS['TL_LANG']['CHMOD']['deletepage']     = 'Delete page';
$GLOBALS['TL_LANG']['CHMOD']['editarticles']   = 'Edit articles';
$GLOBALS['TL_LANG']['CHMOD']['movearticles']   = 'Edit article hierarchy';
$GLOBALS['TL_LANG']['CHMOD']['deletearticles'] = 'Delete articles';
$GLOBALS['TL_LANG']['CHMOD']['cuser']          = 'User';
$GLOBALS['TL_LANG']['CHMOD']['cgroup']         = 'Group';
$GLOBALS['TL_LANG']['CHMOD']['cworld']         = 'Everybody';


/**
 * Day names
 */
$GLOBALS['TL_LANG']['DAYS'][0] = 'Sunday';
$GLOBALS['TL_LANG']['DAYS'][1] = 'Monday';
$GLOBALS['TL_LANG']['DAYS'][2] = 'Tuesday';
$GLOBALS['TL_LANG']['DAYS'][3] = 'Wednesday';
$GLOBALS['TL_LANG']['DAYS'][4] = 'Thursday';
$GLOBALS['TL_LANG']['DAYS'][5] = 'Friday';
$GLOBALS['TL_LANG']['DAYS'][6] = 'Saturday';


/**
 * Month names
 */
$GLOBALS['TL_LANG']['MONTHS'][0]  = 'January';
$GLOBALS['TL_LANG']['MONTHS'][1]  = 'February';
$GLOBALS['TL_LANG']['MONTHS'][2]  = 'March';
$GLOBALS['TL_LANG']['MONTHS'][3]  = 'April';
$GLOBALS['TL_LANG']['MONTHS'][4]  = 'May';
$GLOBALS['TL_LANG']['MONTHS'][5]  = 'June';
$GLOBALS['TL_LANG']['MONTHS'][6]  = 'July';
$GLOBALS['TL_LANG']['MONTHS'][7]  = 'August';
$GLOBALS['TL_LANG']['MONTHS'][8]  = 'September';
$GLOBALS['TL_LANG']['MONTHS'][9]  = 'October';
$GLOBALS['TL_LANG']['MONTHS'][10] = 'November';
$GLOBALS['TL_LANG']['MONTHS'][11] = 'December';


/**
 * Week offset (0 = Sunday, 1 = Monday, …)
 */
$GLOBALS['TL_LANG']['MSC']['weekOffset']  = 0;
$GLOBALS['TL_LANG']['MSC']['titleFormat'] = 'l, dS of F Y';


/**
 * URLs
 */
$GLOBALS['TL_LANG']['MSC']['url']    = array('Target URL', 'Please enter the complete target URL including the network protocol (e.g. <em>http://www.domain.com</em>). To link to an e-mail address, please enter <em>mailto:name@domain.com</em>. You can also use insert tags (e.g. <em>link_url</em>).');
$GLOBALS['TL_LANG']['MSC']['target'] = array('Open in a new browser window', 'If you choose this option, the target URL opens in a new browser window.');


/**
 * Number format
 */
$GLOBALS['TL_LANG']['MSC']['decimalSeparator']   = '.';
$GLOBALS['TL_LANG']['MSC']['thousandsSeparator'] = ',';


/**
 * CSV files
 */
$GLOBALS['TL_LANG']['MSC']['separator'] = array('Separator', 'Please select the character that separates the values.');
$GLOBALS['TL_LANG']['MSC']['comma']     = 'Comma';
$GLOBALS['TL_LANG']['MSC']['semicolon'] = 'Semicolon';
$GLOBALS['TL_LANG']['MSC']['tabulator'] = 'Tabulator';
$GLOBALS['TL_LANG']['MSC']['linebreak'] = 'Line break';


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['id']         = array('ID', 'An ID is a unique identification number of a record of a table. IDs are usually assigned automatically. As these IDs are used to relate records, changing them might break the data consistency!');
$GLOBALS['TL_LANG']['MSC']['pid']        = array('Parent ID', 'A parent ID is the identification number of a superior record the current record is related to. Parent IDs are usually assigned automatically. As parent IDs are used to relate records, changing them might break the data consistency!');
$GLOBALS['TL_LANG']['MSC']['sorting']    = array('Sorting value', 'A sorting value is used to arrange records in a certain order. Sorting values are usually assigned automatically.');
$GLOBALS['TL_LANG']['MSC']['all']        = array('Edit multiple', 'Edit multiple records at once');
$GLOBALS['TL_LANG']['MSC']['deleteAll']  = array('Delete all records', 'Delete all currently shown records');
$GLOBALS['TL_LANG']['MSC']['all_fields'] = array('Available fields', 'Please select the fields you want to edit.');
$GLOBALS['TL_LANG']['MSC']['password']   = array('Password', 'Please enter a password.');
$GLOBALS['TL_LANG']['MSC']['confirm']    = array('Confirm password', 'Please confirm the password.');


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['feLink']         = 'Go to front end';
$GLOBALS['TL_LANG']['MSC']['fePreview']      = 'Front end preview';
$GLOBALS['TL_LANG']['MSC']['feUser']         = 'Front end user';
$GLOBALS['TL_LANG']['MSC']['backToTop']      = 'Back to top';
$GLOBALS['TL_LANG']['MSC']['user']           = 'User';
$GLOBALS['TL_LANG']['MSC']['loginTo']        = 'Log into %s';
$GLOBALS['TL_LANG']['MSC']['loginBT']        = 'Login';
$GLOBALS['TL_LANG']['MSC']['logoutBT']       = 'Logout';
$GLOBALS['TL_LANG']['MSC']['backBT']         = 'Go back';
$GLOBALS['TL_LANG']['MSC']['cancelBT']       = 'Cancel';
$GLOBALS['TL_LANG']['MSC']['deleteConfirm']  = 'Do you really want to delete entry ID %s?';
$GLOBALS['TL_LANG']['MSC']['delAllConfirm']  = 'Do you really want to delete the selected records?';
$GLOBALS['TL_LANG']['MSC']['filterRecords']  = 'Records';
$GLOBALS['TL_LANG']['MSC']['filterAll']      = 'All';
$GLOBALS['TL_LANG']['MSC']['showRecord']     = 'Show details of record %s';
$GLOBALS['TL_LANG']['MSC']['editRecord']     = 'Edit record %s';
$GLOBALS['TL_LANG']['MSC']['all_info']       = 'Edit selected records of table %s';
$GLOBALS['TL_LANG']['MSC']['showOnly']       = 'Show';
$GLOBALS['TL_LANG']['MSC']['sortBy']         = 'Sort';
$GLOBALS['TL_LANG']['MSC']['filter']         = 'Filter';
$GLOBALS['TL_LANG']['MSC']['search']         = 'Search';
$GLOBALS['TL_LANG']['MSC']['noResult']       = 'No records found.';
$GLOBALS['TL_LANG']['MSC']['save']           = 'Save';
$GLOBALS['TL_LANG']['MSC']['saveNclose']     = 'Save and close';
$GLOBALS['TL_LANG']['MSC']['saveNedit']      = 'Save and edit';
$GLOBALS['TL_LANG']['MSC']['saveNback']      = 'Save and go back';
$GLOBALS['TL_LANG']['MSC']['saveNcreate']    = 'Save and new';
$GLOBALS['TL_LANG']['MSC']['continue']       = 'Continue';
$GLOBALS['TL_LANG']['MSC']['skipNavigation'] = 'Skip navigation';
$GLOBALS['TL_LANG']['MSC']['selectAll']      = 'Select all';
$GLOBALS['TL_LANG']['MSC']['pw_changed']     = 'The password has been updated.';
$GLOBALS['TL_LANG']['MSC']['fallback']       = 'default';
$GLOBALS['TL_LANG']['MSC']['view']           = 'View in a new window';
$GLOBALS['TL_LANG']['MSC']['fullsize']       = 'Open full size image in a new window';
$GLOBALS['TL_LANG']['MSC']['colorpicker']    = 'Color picker (requires JavaScript)';
$GLOBALS['TL_LANG']['MSC']['pagepicker']     = 'Page picker (requires JavaScript)';
$GLOBALS['TL_LANG']['MSC']['ppHeadline']     = 'TYPOlight pages';
$GLOBALS['TL_LANG']['MSC']['yes']            = 'yes';
$GLOBALS['TL_LANG']['MSC']['no']             = 'no';
$GLOBALS['TL_LANG']['MSC']['goBack']         = 'Go back';
$GLOBALS['TL_LANG']['MSC']['reload']         = 'Reload';
$GLOBALS['TL_LANG']['MSC']['above']          = 'above';
$GLOBALS['TL_LANG']['MSC']['below']          = 'below';
$GLOBALS['TL_LANG']['MSC']['date']           = 'Date';
$GLOBALS['TL_LANG']['MSC']['tstamp']         = 'Date';
$GLOBALS['TL_LANG']['MSC']['entry']          = '%s entry';
$GLOBALS['TL_LANG']['MSC']['entries']        = '%s entries';
$GLOBALS['TL_LANG']['MSC']['left']           = 'left';
$GLOBALS['TL_LANG']['MSC']['center']         = 'center';
$GLOBALS['TL_LANG']['MSC']['right']          = 'right';
$GLOBALS['TL_LANG']['MSC']['justify']        = 'justified';
$GLOBALS['TL_LANG']['MSC']['filetree']       = 'File system';
$GLOBALS['TL_LANG']['MSC']['male']           = 'Male';
$GLOBALS['TL_LANG']['MSC']['female']         = 'Female';
$GLOBALS['TL_LANG']['MSC']['fileSize']       = 'File size';
$GLOBALS['TL_LANG']['MSC']['fileCreated']    = 'Created';
$GLOBALS['TL_LANG']['MSC']['fileModified']   = 'Last modified';
$GLOBALS['TL_LANG']['MSC']['fileAccessed']   = 'Last accessed';
$GLOBALS['TL_LANG']['MSC']['fileDownload']   = 'Download';
$GLOBALS['TL_LANG']['MSC']['version']        = 'Version';
$GLOBALS['TL_LANG']['MSC']['restore']        = 'Restore';
$GLOBALS['TL_LANG']['MSC']['backendModules'] = 'Back end modules';
$GLOBALS['TL_LANG']['MSC']['welcomeTo']      = '%s back end';
$GLOBALS['TL_LANG']['MSC']['updateVersion']  = 'TYPOlight version %s available';
$GLOBALS['TL_LANG']['MSC']['wordWrap']       = 'Word wrap';
$GLOBALS['TL_LANG']['MSC']['saveAlert']      = 'ATTENTION! You will lose any unsaved changes. Continue?';
$GLOBALS['TL_LANG']['MSC']['toggleNodes']    = 'Toggle all nodes';
$GLOBALS['TL_LANG']['MSC']['expandNode']     = 'Expand node';
$GLOBALS['TL_LANG']['MSC']['collapseNode']   = 'Collapse node';
$GLOBALS['TL_LANG']['MSC']['deleteSelected'] = 'Delete selected';
$GLOBALS['TL_LANG']['MSC']['editSelected']   = 'Edit selected';
$GLOBALS['TL_LANG']['MSC']['changeSelected'] = 'Change selection';
$GLOBALS['TL_LANG']['MSC']['resetSelected']  = 'Reset selection';
$GLOBALS['TL_LANG']['MSC']['fileManager']    = 'Open file manager in a popup window';
$GLOBALS['TL_LANG']['MSC']['systemMessages'] = 'System messages';
$GLOBALS['TL_LANG']['MSC']['tasksCur']       = '%s pending task(s)';
$GLOBALS['TL_LANG']['MSC']['tasksNew']       = '%s new task(s)';
$GLOBALS['TL_LANG']['MSC']['tasksDue']       = '%s overdue task(s)';
$GLOBALS['TL_LANG']['MSC']['clearClipboard'] = 'Clear clipboard';
$GLOBALS['TL_LANG']['MSC']['hiddenElements'] = 'Unpublished elements';
$GLOBALS['TL_LANG']['MSC']['hiddenHide']     = 'hide';
$GLOBALS['TL_LANG']['MSC']['hiddenShow']     = 'show';
$GLOBALS['TL_LANG']['MSC']['apply']          = 'Apply';

?>