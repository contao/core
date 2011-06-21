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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Error messages
 */
$GLOBALS['TL_LANG']['ERR']['form']    = 'The form could not be sent';
$GLOBALS['TL_LANG']['ERR']['captcha'] = 'Please answer the security question!';


/**
 * Security questions
 */
$GLOBALS['TL_LANG']['SEC']['question1'] = 'Please add %d and %d.';
$GLOBALS['TL_LANG']['SEC']['question2'] = 'What is the sum of %d and %d?';
$GLOBALS['TL_LANG']['SEC']['question3'] = 'Please calculate %d plus %d.';


/**
 * Content elements
 */
$GLOBALS['TL_LANG']['CTE']['texts']     = 'Text elements';
$GLOBALS['TL_LANG']['CTE']['headline']  = array('Headline', 'Generates a headline (h1 - h6).');
$GLOBALS['TL_LANG']['CTE']['text']      = array('Text', 'Generates a rich text element.');
$GLOBALS['TL_LANG']['CTE']['html']      = array('HTML', 'Allows you to add custom HTML code.');
$GLOBALS['TL_LANG']['CTE']['list']      = array('List', 'Generates an ordered or unordered list.');
$GLOBALS['TL_LANG']['CTE']['table']     = array('Table', 'Generates an optionally sortable table.');
$GLOBALS['TL_LANG']['CTE']['accordion'] = array('Accordion', 'Generates a mootools accordion pane.');
$GLOBALS['TL_LANG']['CTE']['code']      = array('Code', 'Highlights code snippets and prints them to the screen.');
$GLOBALS['TL_LANG']['CTE']['links']     = 'Link elements';
$GLOBALS['TL_LANG']['CTE']['hyperlink'] = array('Hyperlink', 'Generates a link to another website.');
$GLOBALS['TL_LANG']['CTE']['toplink']   = array('Top link', 'Generates a link to jump to the top of the page.');
$GLOBALS['TL_LANG']['CTE']['images']    = 'Image elements';
$GLOBALS['TL_LANG']['CTE']['image']     = array('Image', 'Generates a stand-alone image.');
$GLOBALS['TL_LANG']['CTE']['gallery']   = array('Gallery', 'Generates a lightbox image gallery.');
$GLOBALS['TL_LANG']['CTE']['files']     = 'File elements';
$GLOBALS['TL_LANG']['CTE']['download']  = array('Download', 'Generates a link to download a file.');
$GLOBALS['TL_LANG']['CTE']['downloads'] = array('Downloads', 'Generates multiple links to download files.');
$GLOBALS['TL_LANG']['CTE']['includes']  = 'Include elements';
$GLOBALS['TL_LANG']['CTE']['article']   = array('Article', 'Includes another article.');
$GLOBALS['TL_LANG']['CTE']['alias']     = array('Content element', 'Includes another content element.');
$GLOBALS['TL_LANG']['CTE']['form']      = array('Form', 'Includes a form.');
$GLOBALS['TL_LANG']['CTE']['module']    = array('Module', 'Includes a front end module.');
$GLOBALS['TL_LANG']['CTE']['teaser']    = array('Article teaser', 'Displays the teaser text of an article.');


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['go']            = 'Go';
$GLOBALS['TL_LANG']['MSC']['quicknav']      = 'Quick navigation';
$GLOBALS['TL_LANG']['MSC']['quicklink']     = 'Quick link';
$GLOBALS['TL_LANG']['MSC']['username']      = 'Username';
$GLOBALS['TL_LANG']['MSC']['login']         = 'Login';
$GLOBALS['TL_LANG']['MSC']['logout']        = 'Logout';
$GLOBALS['TL_LANG']['MSC']['loggedInAs']    = 'You are logged in as %s.';
$GLOBALS['TL_LANG']['MSC']['emptyField']    = 'Please enter your username and password!';
$GLOBALS['TL_LANG']['MSC']['confirmation']  = 'Confirmation';
$GLOBALS['TL_LANG']['MSC']['sMatches']      = '%s occurrences for %s';
$GLOBALS['TL_LANG']['MSC']['sEmpty']        = 'No matches for <strong>%s</strong>';
$GLOBALS['TL_LANG']['MSC']['sResults']      = 'Results %s - %s of %s for <strong>%s</strong>';
$GLOBALS['TL_LANG']['MSC']['sNoResult']     = 'Your search for <strong>%s</strong> returned no results.';
$GLOBALS['TL_LANG']['MSC']['seconds']       = 'seconds';
$GLOBALS['TL_LANG']['MSC']['up']            = 'Up';
$GLOBALS['TL_LANG']['MSC']['first']         = '&#171; First';
$GLOBALS['TL_LANG']['MSC']['previous']      = 'Previous';
$GLOBALS['TL_LANG']['MSC']['next']          = 'Next';
$GLOBALS['TL_LANG']['MSC']['last']          = 'Last &#187;';
$GLOBALS['TL_LANG']['MSC']['goToPage']      = 'Go to page %s';
$GLOBALS['TL_LANG']['MSC']['totalPages']    = 'Page %s of %s';
$GLOBALS['TL_LANG']['MSC']['fileUploaded']  = 'File %s uploaded successfully.';
$GLOBALS['TL_LANG']['MSC']['fileExceeds']   = 'Image %s uploaded successfully, however it is too big to be resized automatically.';
$GLOBALS['TL_LANG']['MSC']['fileResized']   = 'Image %s uploaded successfully and was scaled down to the maximum dimensions.';
$GLOBALS['TL_LANG']['MSC']['searchLabel']   = 'Search';
$GLOBALS['TL_LANG']['MSC']['keywords']      = 'Keywords';
$GLOBALS['TL_LANG']['MSC']['options']       = 'Options';
$GLOBALS['TL_LANG']['MSC']['matchAll']      = 'match all words';
$GLOBALS['TL_LANG']['MSC']['matchAny']      = 'match any word';
$GLOBALS['TL_LANG']['MSC']['saveData']      = 'Save data';
$GLOBALS['TL_LANG']['MSC']['printPage']     = 'Print this page';
$GLOBALS['TL_LANG']['MSC']['printAsPdf']    = 'Print article as PDF';
$GLOBALS['TL_LANG']['MSC']['facebookShare'] = 'Share on Facebook';
$GLOBALS['TL_LANG']['MSC']['twitterShare']  = 'Share on Twitter';
$GLOBALS['TL_LANG']['MSC']['pleaseWait']    = 'Please wait';
$GLOBALS['TL_LANG']['MSC']['loading']       = 'Loading …';
$GLOBALS['TL_LANG']['MSC']['more']          = 'Read more …';
$GLOBALS['TL_LANG']['MSC']['readMore']      = 'Read the article: %s';
$GLOBALS['TL_LANG']['MSC']['targetPage']    = 'Target page';
$GLOBALS['TL_LANG']['MSC']['com_name']      = 'Name';
$GLOBALS['TL_LANG']['MSC']['com_email']     = 'E-mail (not published)';
$GLOBALS['TL_LANG']['MSC']['com_website']   = 'Website';
$GLOBALS['TL_LANG']['MSC']['com_comment']   = 'Comment';
$GLOBALS['TL_LANG']['MSC']['com_submit']    = 'Submit comment';
$GLOBALS['TL_LANG']['MSC']['comment_by']    = 'Comment by';
$GLOBALS['TL_LANG']['MSC']['reply_by']      = 'Reply by';
$GLOBALS['TL_LANG']['MSC']['com_quote']     = '%s wrote:';
$GLOBALS['TL_LANG']['MSC']['com_code']      = 'Code:';
$GLOBALS['TL_LANG']['MSC']['com_subject']   = 'Contao :: New comment on %s';
$GLOBALS['TL_LANG']['MSC']['com_message']   = "%s has created a new comment on your website.\n\n---\n\n%s\n\n---\n\nView: %s\nEdit: %s\n\nIf you are moderating comments, you have to log in to the back end to publish it.";
$GLOBALS['TL_LANG']['MSC']['com_confirm']   = 'Your comment has been added and is now pending for approval.';
$GLOBALS['TL_LANG']['MSC']['invalidPage']   = 'Sorry, item "%s" does not exist.';
$GLOBALS['TL_LANG']['MSC']['list_orderBy']  = 'Order by %s';
$GLOBALS['TL_LANG']['MSC']['list_perPage']  = 'Results per page';
$GLOBALS['TL_LANG']['MSC']['published']     = 'Published';
$GLOBALS['TL_LANG']['MSC']['unpublished']   = 'Unpublished';
$GLOBALS['TL_LANG']['MSC']['addComment']    = 'Add a comment';
$GLOBALS['TL_LANG']['MSC']['autologin']     = 'Remember me';
$GLOBALS['TL_LANG']['MSC']['relevance']     = '%s relevance';

?>