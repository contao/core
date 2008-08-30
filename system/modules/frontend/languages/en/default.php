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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Error messages
 */
$GLOBALS['TL_LANG']['ERR']['captcha'] = 'Please answer the security question!';


/**
 * Security questions
 */
$GLOBALS['TL_LANG']['SEC']['question1'] = 'Please add %d and %d.';
$GLOBALS['TL_LANG']['SEC']['question2'] = 'What is the sum of %d and %d?';
$GLOBALS['TL_LANG']['SEC']['question3'] = 'Please calculate %d plus %s.';


/**
 * Content elements
 */
$GLOBALS['TL_LANG']['CTE']['texts']     = 'Text elements';
$GLOBALS['TL_LANG']['CTE']['headline']  = array('Headline', 'This element contains a stand-alone headline, which is wrapped in &#60;H1&#62; tags.');
$GLOBALS['TL_LANG']['CTE']['text']      = array('Text', 'This element contains rich text with hyperlinks. In addition you can add an image to this element.');
$GLOBALS['TL_LANG']['CTE']['html']      = array('HTML', 'This element contains HTML code. Please note that certain HTML tags are disabled for security reasons.');
$GLOBALS['TL_LANG']['CTE']['list']      = array('List', 'This element contains an ordered or unordered list.');
$GLOBALS['TL_LANG']['CTE']['table']     = array('Table', 'This element contains a table.');
$GLOBALS['TL_LANG']['CTE']['accordion'] = array('Accordion', 'This element generates a content pane of a moofx accordion. Please note that this effect requires a mootools JavaScript template which you can choose in the page layout module.');
$GLOBALS['TL_LANG']['CTE']['code']      = array('Code', 'This element contains code snippets that are formatted in a special way. The code will only be printed to the screen and not be executed.');
$GLOBALS['TL_LANG']['CTE']['links']     = 'Link elements';
$GLOBALS['TL_LANG']['CTE']['hyperlink'] = array('Hyperlink', 'This element contains a hyperlink to another website.');
$GLOBALS['TL_LANG']['CTE']['toplink']   = array('Top link', 'This element generates a hyperlink that allows to jump to the top of the page.');
$GLOBALS['TL_LANG']['CTE']['images']    = 'Image elements';
$GLOBALS['TL_LANG']['CTE']['image']     = array('Image', 'This element contains a single image.');
$GLOBALS['TL_LANG']['CTE']['gallery']   = array('Image gallery', 'This element contains several thumbnail images which can be viewed fullsize by clicking them.');
$GLOBALS['TL_LANG']['CTE']['files']     = 'File elements';
$GLOBALS['TL_LANG']['CTE']['download']  = array('Download', 'This element contains a hyperlink to a file which can be downloaded by the visitors of your website.');
$GLOBALS['TL_LANG']['CTE']['downloads'] = array('Downloads', 'This element contains multiple files which can be downloaded by the visitors of your website.');
$GLOBALS['TL_LANG']['CTE']['includes']  = 'Include elements';
$GLOBALS['TL_LANG']['CTE']['alias']     = array('Alias', 'This element allows to include a content element from another article.');
$GLOBALS['TL_LANG']['CTE']['article']   = array('Article alias', 'This element allows to include an article in another article.');
$GLOBALS['TL_LANG']['CTE']['teaser']    = array('Article teaser', 'This element allows to show the teaser text of a certain article.');
$GLOBALS['TL_LANG']['CTE']['form']      = array('Form', 'Use this option to embed a form into an article.');
$GLOBALS['TL_LANG']['CTE']['module']    = array('Module', 'Use this option to embed a module (e.g. a navigation menu or a Flash movie) into an article.');


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['go']           = 'Go';
$GLOBALS['TL_LANG']['MSC']['quicknav']     = 'Quick navigation';
$GLOBALS['TL_LANG']['MSC']['quicklink']    = 'Quick link';
$GLOBALS['TL_LANG']['MSC']['username']     = 'Username';
$GLOBALS['TL_LANG']['MSC']['login']        = 'Login';
$GLOBALS['TL_LANG']['MSC']['logout']       = 'Logout';
$GLOBALS['TL_LANG']['MSC']['loggedInAs']   = 'You are logged in as %s.';
$GLOBALS['TL_LANG']['MSC']['emptyField']   = 'Please enter username and password!';
$GLOBALS['TL_LANG']['MSC']['confirmation'] = 'Confirmation';
$GLOBALS['TL_LANG']['MSC']['sMatches']     = '%s occurrences for %s';
$GLOBALS['TL_LANG']['MSC']['sEmpty']       = 'No matches for <strong>%s</strong>';
$GLOBALS['TL_LANG']['MSC']['sResults']     = 'Results %s - %s of %s for <strong>%s</strong>';
$GLOBALS['TL_LANG']['MSC']['sNoResult']    = 'Your search for <strong>%s</strong> returned no results.';
$GLOBALS['TL_LANG']['MSC']['seconds']      = 'seconds';
$GLOBALS['TL_LANG']['MSC']['first']        = '&#171; First';
$GLOBALS['TL_LANG']['MSC']['previous']     = 'Previous';
$GLOBALS['TL_LANG']['MSC']['next']         = 'Next';
$GLOBALS['TL_LANG']['MSC']['last']         = 'Last &#187;';
$GLOBALS['TL_LANG']['MSC']['goToPage']     = 'Go to page %s';
$GLOBALS['TL_LANG']['MSC']['totalPages']   = 'Page %s of %s';
$GLOBALS['TL_LANG']['MSC']['fileUploaded'] = 'File %s uploaded successfully';
$GLOBALS['TL_LANG']['MSC']['fileResized']  = 'File %s uploaded and scaled down to the maximum dimensions';
$GLOBALS['TL_LANG']['MSC']['searchLabel']  = 'Search';
$GLOBALS['TL_LANG']['MSC']['matchAll']     = 'match all words';
$GLOBALS['TL_LANG']['MSC']['matchAny']     = 'match any word';
$GLOBALS['TL_LANG']['MSC']['saveData']     = 'Save data';
$GLOBALS['TL_LANG']['MSC']['printAsPdf']   = 'Print article as PDF';
$GLOBALS['TL_LANG']['MSC']['pleaseWait']   = 'Please wait';
$GLOBALS['TL_LANG']['MSC']['loading']      = 'Loading...';
$GLOBALS['TL_LANG']['MSC']['more']         = 'Read more...';
$GLOBALS['TL_LANG']['MSC']['com_name']     = 'Name';
$GLOBALS['TL_LANG']['MSC']['com_email']    = 'E-mail (not published)';
$GLOBALS['TL_LANG']['MSC']['com_website']  = 'Website';
$GLOBALS['TL_LANG']['MSC']['com_submit']   = 'Submit comment';
$GLOBALS['TL_LANG']['MSC']['comment_by']   = 'Comment by';
$GLOBALS['TL_LANG']['MSC']['com_quote']    = '%s wrote:';
$GLOBALS['TL_LANG']['MSC']['com_code']     = 'Code:';
$GLOBALS['TL_LANG']['MSC']['com_subject']  = 'TYPOlight :: New comment on %s';
$GLOBALS['TL_LANG']['MSC']['com_message']  = 'A new comment has been created on your website.%sIf you are moderating your comments, you have to log in to the back end to activate it.';

?>