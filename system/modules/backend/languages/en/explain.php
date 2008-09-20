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
 * Shortcuts
 */
$GLOBALS['TL_LANG']['XPL']['shortcuts'] = array
(
	array('colspan', 'Back end access keys'),
	array('[ALT] + <strong>s</strong>', '<span>S</span>ave'),
	array('[ALT] + <strong>c</strong>', 'Save and <span>c</span>lose'),
	array('[ALT] + <strong>e</strong>', 'Save and <span>e</span>dit'),
	array('[ALT] + <strong>n</strong>', 'Create <span>n</span>ew'),
	array('[ALT] + <strong>b</strong>', 'Go <span>b</span>ack'),
	array('[ALT] + <strong>t</strong>', 'Back to <span>t</span>op'),
	array('[ALT] + <strong>f</strong>', '<span>F</span>rontend preview'),
	array('[ALT] + <strong>x</strong>', 'E<span>x</span>it (logout)')
);


/**
 * Insert tags
 */
$GLOBALS['TL_LANG']['XPL']['insertTags'] = array
(
	array('Rich Text Editor', 'for more information on TinyMCE please visit <a href="http://tinymce.moxiecode.com" title="TinyMCE by moxiecode" onclick="window.open(this.href); return false;">http://tinymce.moxiecode.com</a>.'),
	array('Insert tags', 'for more information on insert tags please visit <a href="http://www.typolight.org/wiki/english:inserttags" title="TYPOlight online documentation" onclick="window.open(this.href); return false;">http://www.typolight.org/wiki/english:inserttags</a>.')
	/*
	array('date', 'will be replaced by the current date according to the global date format.'),
	array('date::format', 'will be replaced by the current date according to format <em>format</em> (e.g. replacing <em>format</em> with <em>Y/m/d</em> will echo <em>'.date('Y/m/d').'</em>). You can find more information about date formatting on <a href="http://www.php.net/date" title="Open http://www.php.net in a new window" onclick="window.open(this.href); return false;">www.php.net/date</a>.'),
	array('user::property', 'will be replaced by the property <em>property</em> of the current front end user. Available properties are <em>firstname</em>, <em>lastname</em>, <em>company</em>, <em>email</em>, <em>street</em>, <em>postal</em>, <em>city</em>, <em>phone</em>, <em>mobile</em> and <em>fax</em>.'),
	array('link::page', 'will be replaced by a link to an internal page (replace <em>page</em> with the page ID or alias).'),
	array('link::back', 'will be replaced by a link to the last page visited (can be used with <em>link_open</em>, <em>link_url</em> and <em>link_title</em> as well).'),
	array('link::login', 'will be replaced by a link to the login page of the current user (can be used with <em>link_open</em>, <em>link_url</em> and <em>link_title</em> as well) and is only shown if there is a logged in user.'),
	array('link_open::page', 'will be replaced by the opening tag of a link to an internal page. Thus, you can use a custom text or an image as link (e.g. <em>link_open::pagemy custom text&lt;/a&gt;</em>).'),
	array('link_url::page', 'will be replaced by the URL of an internal page. Thus, you can link to an internal page (e.g. <em>&lt;a href="link_url::page"&gt;my custom text&lt;/a&gt;</em>).'),
	array('link_title::page', 'will be replaced by the title of an internal page and can be used in TITLE or ALT attributes (e.g. <em>&lt;a href="link_url::page" title="link_title::page"&gt;my custom text&lt;/a&gt;</em>).'),
	array('article::ID', 'will be replaced by a link to an article (replace <em>ID</em> with the article ID).'),
	array('env::page_title', 'will be replaced by the title of the current page (env = environment).'),
	array('env::page_alias', 'will be replaced by the alias of the current page (env = environment).'),
	array('env::main_title', 'will be replaced by the title of the parent main navigation page (env = environment).'),
	array('env::main_alias', 'will be replaced by the alias of the parent main navigation page (env = environment).'),
	array('env::website_title', 'will be replaced by the title of the website root (env = environment).'),
	array('env::url', 'will be replaced by the current URL (e.g. http://www.yoursite.com).'),
	array('env::path', 'will be replaced by the current URL and the path to the TYPOlight directory (e.g. http://www.yoursite.com/path).'),
	array('env::request', 'will be replaced by the current request (e.g. home.html).'),
	array('env::referer', 'will be replaced by the URL of the last page visited.'),
	array('file::file.php', 'will be replaced by the ouput of file file.php. Included PHP files have to be stored in folder <em>templates</em>. It is also possible to add arguments (e.g. file.php?arg=val&amp;arg2=val2).')
	*/
);


/**
 * Date format
 */
$GLOBALS['TL_LANG']['XPL']['dateFormat'] = array
(
	array('colspan', 'TYPOlight supports a couple of different date and time formats which are based on PHP\'s date() function. However, to ensure that any user input can be transformed into a UNIX timestamp, you can only use numeric date and time formats (j, d, m, n, y, Y, g, G, h, H, i, s) with an additional single character inbetween each specification.<br /><br /><em>Here are some examples of valid date and time formats</em>:'),
	array('Y-m-d', 'YYYY-MM-DD, international ISO 8601 e.g. 2005-01-28'),
	array('m/d/Y', 'MM/DD/YYYY, english format e.g. 01/28/2005'),
	array('d.m.Y', 'DD.MM.YYYY, german format e.g. 28.01.2005'),
	array('y-n-j', 'YY-M-D, without leading zeros e.g. 05-1-28'),
	array('Ymd', 'YYYYMMDD, timestamp e.g. 20050128'),
	array('H:i:s', '24 hours, minutes and seconds e.g. 20:36:59'),
	array('g:i', '12 hours without leading zeros and minutes e.g. 8:36')
);


/**
 * Field "jumpTo"
 */
$GLOBALS['TL_LANG']['XPL']['jumpTo'] = array
(
	array('colspan', 'This setting defines to which page a user will be redirected on a certain action.'),
	array('Login form', 'after a user has logged in, he will be redirected to this page. Thus you can e.g. send him to the welcome page of a protected area.'),
	array('Automatic logout', 'after a user has logged out, he will be redirected to this page (e.g. a login page or a "good bye" page).'),
	array('Personal data', 'after a user has updated his personal data successfully, he will be redirected to this page.'),
	array('Website news', 'when a user clicks a "read more …" button, he can be redirected to a page including module <em>newsreader</em>.'),
	array('News archive menu', 'when a user chooses an archiv by clicking a certain month, he can be redirected to a page including module <em>news archive</em>.'),
	array('Form generator', 'after a user has submitted a form you can e.g. redirect him to a "thank you" or confirmation page.')
);

?>