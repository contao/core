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
$GLOBALS['TL_LANG']['tl_page']['title']          = array('Page name', 'Please enter the page name.');
$GLOBALS['TL_LANG']['tl_page']['alias']          = array('Page alias', 'The page alias is a unique reference to the page which can be called instead of its numeric ID.');
$GLOBALS['TL_LANG']['tl_page']['type']           = array('Page type', 'Please choose the page type.');
$GLOBALS['TL_LANG']['tl_page']['pageTitle']      = array('Page title', 'Please enter the page title.');
$GLOBALS['TL_LANG']['tl_page']['language']       = array('Language', 'Please enter the page language according to the ISO-639-1 standard (e.g. "en" for English).');
$GLOBALS['TL_LANG']['tl_page']['robots']         = array('Robots tag', 'Here you can define how search engines handle the page.');
$GLOBALS['TL_LANG']['tl_page']['description']    = array('Page description', 'Here you can enter a short description of the page which will be evaluated by search engines like Google or Yahoo. Search engines usually indicate between 150 and 300 characters.');
$GLOBALS['TL_LANG']['tl_page']['redirect']       = array('Redirect type', 'Please choose the redirect type.');
$GLOBALS['TL_LANG']['tl_page']['jumpTo']         = array('Redirect page', 'Please choose the page to which visitors will be redirected. Leave blank to redirect to the first regular subpage.');
$GLOBALS['TL_LANG']['tl_page']['fallback']       = array('Language fallback', 'Show this page if there is none that matches the visitor\'s language.');
$GLOBALS['TL_LANG']['tl_page']['dns']            = array('Domain name', 'Here you can restrict the access to the website to a certain domain name.');
$GLOBALS['TL_LANG']['tl_page']['adminEmail']     = array('E-mail address of the website administrator', 'Auto-generated messages like subscription confirmation e-mails will be sent to this address.');
$GLOBALS['TL_LANG']['tl_page']['dateFormat']     = array('Date format', 'The date format string will be parsed with the PHP date() function.');
$GLOBALS['TL_LANG']['tl_page']['timeFormat']     = array('Time format', 'The time format string will be parsed with the PHP date() function.');
$GLOBALS['TL_LANG']['tl_page']['datimFormat']    = array('Date and time format', 'The date and time format string will be parsed with the PHP date() function.');
$GLOBALS['TL_LANG']['tl_page']['createSitemap']  = array('Create an XML sitemap', 'Create a Google XML sitemap in the root directory.');
$GLOBALS['TL_LANG']['tl_page']['sitemapName']    = array('Sitemap file name', 'Please enter the name of the sitemap file without extension.');
$GLOBALS['TL_LANG']['tl_page']['useSSL']         = array('Use HTTPS in sitemaps', 'Generate the sitemap URLs of this website with <em>https://</em>.');
$GLOBALS['TL_LANG']['tl_page']['autoforward']    = array('Forward to another page', 'Redirect visitors to another page (e.g. a login page).');
$GLOBALS['TL_LANG']['tl_page']['protected']      = array('Protect page', 'Restrict page access to certain member groups.');
$GLOBALS['TL_LANG']['tl_page']['groups']         = array('Allowed member groups', 'These groups will be able to access the page.');
$GLOBALS['TL_LANG']['tl_page']['includeLayout']  = array('Assign a layout', 'Assign a page layout to the page and its subpages.');
$GLOBALS['TL_LANG']['tl_page']['layout']         = array('Page layout', 'You can manage page layouts with the "themes" module.');
$GLOBALS['TL_LANG']['tl_page']['includeCache']   = array('Set cache timeout', 'Set a cache timeout value for the page and its subpages.');
$GLOBALS['TL_LANG']['tl_page']['cache']          = array('Cache timeout', 'After this period, the cached version of the page will expire.');
$GLOBALS['TL_LANG']['tl_page']['includeChmod']   = array('Assign access rights', 'Access rights determine what back end users can do with the page.');
$GLOBALS['TL_LANG']['tl_page']['cuser']          = array('Owner', 'Please select a user as the owner of the page.');
$GLOBALS['TL_LANG']['tl_page']['cgroup']         = array('Group', 'Please select a group as the owner of the page.');
$GLOBALS['TL_LANG']['tl_page']['chmod']          = array('Access rights', 'Please assign the access rights for the page and its subpages.');
$GLOBALS['TL_LANG']['tl_page']['noSearch']       = array('Do not search', 'Exclude the page from the search index.');
$GLOBALS['TL_LANG']['tl_page']['cssClass']       = array('CSS class', 'The class(es) will be used in the navigation menu and the body tag.');
$GLOBALS['TL_LANG']['tl_page']['sitemap']        = array('Show in sitemap', 'Here you can define whether the page is shown in the sitemap.');
$GLOBALS['TL_LANG']['tl_page']['hide']           = array('Hide from navigation', 'Hide the page from the navigation menu.');
$GLOBALS['TL_LANG']['tl_page']['guests']         = array('Show to guests only', 'Hide the page if there is an authenticated user.');
$GLOBALS['TL_LANG']['tl_page']['tabindex']       = array('Tab index', 'The position of the navigation item in the tabbing order.');
$GLOBALS['TL_LANG']['tl_page']['accesskey']      = array('Access key', 'A navigation item can be focused by pressing the [ALT] or [CTRL] key and the access key simultaneously.');
$GLOBALS['TL_LANG']['tl_page']['published']      = array('Publish page', 'Make the page publicly visible on the website.');
$GLOBALS['TL_LANG']['tl_page']['start']          = array('Show from', 'Do not show the page on the website before this day.');
$GLOBALS['TL_LANG']['tl_page']['stop']           = array('Show until', 'Do not show the page on the website on and after this day.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_page']['title_legend']     = 'Name and type';
$GLOBALS['TL_LANG']['tl_page']['meta_legend']      = 'Meta information';
$GLOBALS['TL_LANG']['tl_page']['redirect_legend']  = 'Redirect settings';
$GLOBALS['TL_LANG']['tl_page']['dns_legend']       = 'DNS settings';
$GLOBALS['TL_LANG']['tl_page']['sitemap_legend']   = 'XML sitemap';
$GLOBALS['TL_LANG']['tl_page']['forward_legend']   = 'Auto-forward';
$GLOBALS['TL_LANG']['tl_page']['protected_legend'] = 'Access protection';
$GLOBALS['TL_LANG']['tl_page']['layout_legend']    = 'Layout settings';
$GLOBALS['TL_LANG']['tl_page']['cache_legend']     = 'Cache settings';
$GLOBALS['TL_LANG']['tl_page']['chmod_legend']     = 'Access rights';
$GLOBALS['TL_LANG']['tl_page']['search_legend']    = 'Search settings';
$GLOBALS['TL_LANG']['tl_page']['expert_legend']    = 'Expert settings';
$GLOBALS['TL_LANG']['tl_page']['tabnav_legend']    = 'Keyboard navigation';
$GLOBALS['TL_LANG']['tl_page']['publish_legend']   = 'Publish settings';


/**
 * Cache timeout labels
 */
$GLOBALS['TL_LANG']['CACHE'][0]       = '0 (do not cache)';
$GLOBALS['TL_LANG']['CACHE'][5]       = '5 seconds';
$GLOBALS['TL_LANG']['CACHE'][15]      = '15 seconds';
$GLOBALS['TL_LANG']['CACHE'][30]      = '30 seconds';
$GLOBALS['TL_LANG']['CACHE'][60]      = '60 seconds';
$GLOBALS['TL_LANG']['CACHE'][300]     = '5 minutes';
$GLOBALS['TL_LANG']['CACHE'][900]     = '15 minutes';
$GLOBALS['TL_LANG']['CACHE'][1800]    = '30 minutes';
$GLOBALS['TL_LANG']['CACHE'][3600]    = '60 minutes';
$GLOBALS['TL_LANG']['CACHE'][10800]   = '3 hours';
$GLOBALS['TL_LANG']['CACHE'][21600]   = '6 hours';
$GLOBALS['TL_LANG']['CACHE'][43200]   = '12 hours';
$GLOBALS['TL_LANG']['CACHE'][86400]   = '24 hours';
$GLOBALS['TL_LANG']['CACHE'][259200]  = '3 days';
$GLOBALS['TL_LANG']['CACHE'][604800]  = '7 days';
$GLOBALS['TL_LANG']['CACHE'][2592000] = '30 days';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_page']['permanent']   = '301 Permanent redirect';
$GLOBALS['TL_LANG']['tl_page']['temporary']   = '302 Temporary redirect';
$GLOBALS['TL_LANG']['tl_page']['map_default'] = 'Default';
$GLOBALS['TL_LANG']['tl_page']['map_always']  = 'Show always';
$GLOBALS['TL_LANG']['tl_page']['map_never']   = 'Show never';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_page']['new']        = array('New page', 'Create a new page');
$GLOBALS['TL_LANG']['tl_page']['show']       = array('Page details', 'Show the details of page ID %s');
$GLOBALS['TL_LANG']['tl_page']['edit']       = array('Edit page', 'Edit page ID %s');
$GLOBALS['TL_LANG']['tl_page']['cut']        = array('Move page', 'Move page ID %s');
$GLOBALS['TL_LANG']['tl_page']['copy']       = array('Duplicate page', 'Duplicate page ID %s');
$GLOBALS['TL_LANG']['tl_page']['copyChilds'] = array('Duplicate with subpages', 'Duplicate page ID %s with its subpages');
$GLOBALS['TL_LANG']['tl_page']['delete']     = array('Delete page', 'Delete page ID %s');
$GLOBALS['TL_LANG']['tl_page']['toggle']     = array('Publish/unpublish page', 'Publish/unpublish page ID %s');
$GLOBALS['TL_LANG']['tl_page']['pasteafter'] = array('Paste after', 'Paste after page ID %s');
$GLOBALS['TL_LANG']['tl_page']['pasteinto']  = array('Paste into', 'Paste into page ID %s');
$GLOBALS['TL_LANG']['tl_page']['articles']   = array('Edit articles', 'Edit the articles of page ID %s');

?>