<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleNewsReader
 *
 * Front end module "news reader".
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleNewsReader extends ModuleNews
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_newsreader';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### NEWS READER ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Return if no news item has been specified
		if (!$this->Input->get('items'))
		{
			return '';
		}

		$this->news_archives = $this->sortOutProtected(deserialize($this->news_archives, true));

		// Return if there are no archives
		if (!is_array($this->news_archives) || count($this->news_archives) < 1)
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		global $objPage;

		$this->Template->articles = '';
		$this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];

		$time = time();

		// Get news item
		$objArticle = $this->Database->prepare("SELECT *, (SELECT title FROM tl_news_archive WHERE tl_news_archive.id=tl_news.pid) AS archive, (SELECT jumpTo FROM tl_news_archive WHERE tl_news_archive.id=tl_news.pid) AS parentJumpTo, (SELECT name FROM tl_user WHERE id=author) AS author FROM tl_news WHERE pid IN(" . implode(',', $this->news_archives) . ") AND (id=? OR alias=?)" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1" : ""))
									 ->limit(1)
									 ->execute((is_numeric($this->Input->get('items')) ? $this->Input->get('items') : 0), $this->Input->get('items'), $time, $time);

		if ($objArticle->numRows < 1)
		{
			$this->Template->articles = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['invalidPage'], $this->Input->get('items')) . '</p>';

			// Do not index the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			// Send 404 header
			header('HTTP/1.1 404 Not Found');
			return;
		}

		$arrArticle = $this->parseArticles($objArticle);
		$this->Template->articles = $arrArticle[0];

		// Overwrite page title
		if (strlen($objArticle->headline))
		{
			$objPage->pageTitle = $objArticle->headline;
		}

		// Comments
		$objArchive = $this->Database->prepare("SELECT * FROM tl_news_archive WHERE id=?")
									 ->limit(1)
									 ->execute($objArticle->pid);

		if ($objArticle->noComments || $objArchive->numRows < 1 || !$objArchive->allowComments)
		{
			$this->Template->allowComments = false;
			return;
		}

		$limit = null;
		$arrComments = array();

		// Pagination
		if ($objArchive->perPage > 0)
		{
			$page = $this->Input->get('page') ? $this->Input->get('page') : 1;
			$limit = $objArchive->perPage;
			$offset = ($page - 1) * $objArchive->perPage;

			// Get total number of comments
			$objTotal = $this->Database->prepare("SELECT COUNT(*) AS count FROM tl_news_comments WHERE pid=?" . (!BE_USER_LOGGED_IN ? " AND published=1" : ""))
									   ->execute($objArticle->id);

			// Add pagination menu
			$objPagination = new Pagination($objTotal->count, $objArchive->perPage);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}

		// Get all published comments
		$objCommentsStmt = $this->Database->prepare("SELECT * FROM tl_news_comments WHERE pid=?" . (!BE_USER_LOGGED_IN ? " AND published=1" : "") . " ORDER BY date" . (($objArchive->sortOrder == 'descending') ? " DESC" : ""));

		if ($limit)
		{
			$objCommentsStmt->limit($limit, $offset);
		}

		$objComments = $objCommentsStmt->execute($objArticle->id);
		$total = $objComments->numRows;

		if ($total > 0)
		{
			$count = 0;
			$objTemplate = new FrontendTemplate($objArchive->template);

			while ($objComments->next())
			{
				$objTemplate->name = $objComments->name;
				$objTemplate->email = $objComments->email;
				$objTemplate->website = $objComments->website;
				$objTemplate->comment = trim($objComments->comment);
				$objTemplate->datim = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $objComments->date);
				$objTemplate->date = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objComments->date);
				$objTemplate->class = (($count < 1) ? ' first' : '') . (($count >= ($total - 1)) ? ' last' : '') . (($count % 2 == 0) ? ' even' : ' odd');
				$objTemplate->by = $GLOBALS['TL_LANG']['MSC']['comment_by'];
				$objTemplate->id = 'c' . $objComments->id;
				$objTemplate->ip = $objComments->ip;
				$objTemplate->timestamp = $objComments->date;

				$arrComments[] = $objTemplate->parse();
				$count++;
			}
		}

		$this->Template->allowComments = true;
		$this->Template->comments = $arrComments;
		$this->Template->addComment = $GLOBALS['TL_LANG']['MSC']['addComment'];
		$this->Template->name = $GLOBALS['TL_LANG']['MSC']['com_name'];
		$this->Template->email = $GLOBALS['TL_LANG']['MSC']['com_email'];
		$this->Template->website = $GLOBALS['TL_LANG']['MSC']['com_website'];

		// Access control
		if ($objArchive->requireLogin && !BE_USER_LOGGED_IN && !FE_USER_LOGGED_IN)
		{
			$this->Template->protected = true;
			return;
		}

		$this->import('FrontendUser', 'User');

		// Form fields
		$arrFields = array
		(
			'name' => array
			(
				'name' => 'name',
				'label' => $GLOBALS['TL_LANG']['MSC']['com_name'],
				'value' => trim($this->User->firstname . ' ' . $this->User->lastname),
				'inputType' => 'text',
				'eval' => array('mandatory'=>true, 'maxlength'=>64)
			),
			'email' => array
			(
				'name' => 'email',
				'label' => $GLOBALS['TL_LANG']['MSC']['com_email'],
				'value' => $this->User->email,
				'inputType' => 'text',
				'eval' => array('mandatory'=>true, 'rgxp'=>'email', 'maxlength'=>128, 'decodeEntities'=>true)
			),
			'website' => array
			(
				'name' => 'website',
				'label' => $GLOBALS['TL_LANG']['MSC']['com_website'],
				'inputType' => 'text',
				'eval' => array('rgxp'=>'url', 'maxlength'=>128, 'decodeEntities'=>true)
			)
		);

		// Captcha
		if (!$objArchive->disableCaptcha)
		{
			$arrFields['captcha'] = array
			(
				'name' => 'captcha',
				'inputType' => 'captcha',
				'eval' => array('mandatory'=>true)
			);
		}

		// Comment field
		$arrFields['comment'] = array
		(
			'name' => 'comment',
			'inputType' => 'textarea',
			'eval' => array('rows'=>4, 'cols'=>40, 'allowHtml'=>true)
		);

		$doNotSubmit = false;
		$arrWidgets = array();

		// Initialize widgets
		foreach ($arrFields as $arrField)
		{
			$strClass = $GLOBALS['TL_FFL'][$arrField['inputType']];

			// Continue if the class is not defined
			if (!$this->classFileExists($strClass))
			{
				continue;
			}

			$arrField['eval']['required'] = $arrField['eval']['mandatory'];
			$objWidget = new $strClass($this->prepareForWidget($arrField, $arrField['name'], $arrField['value']));

			// Validate widget
			if ($this->Input->post('FORM_SUBMIT') == 'tl_news_comment')
			{
				$objWidget->validate();

				if ($objWidget->hasErrors())
				{
					$doNotSubmit = true;
				}
			}

			$arrWidgets[] = $objWidget;
		}

		$this->Template->fields = $arrWidgets;
		$this->Template->submit = $GLOBALS['TL_LANG']['MSC']['com_submit'];
		$this->Template->action = ampersand($this->Environment->request);

		// Confirmation message
		if ($_SESSION['TL_COMMENT_ADDED'])
		{
			$this->Template->confirm = $GLOBALS['TL_LANG']['MSC']['com_confirm'];
			$_SESSION['TL_COMMENT_ADDED'] = false;
		}

		// Add comment
		if ($this->Input->post('FORM_SUBMIT') == 'tl_news_comment' && !$doNotSubmit)
		{
			$this->addComment($objArticle, $objArchive);

			// Pending for approval
			if ($objArchive->moderate)
			{
				$_SESSION['TL_COMMENT_ADDED'] = true;
			}

			$this->reload();
		}
	}


	/**
	 * Replace bbcode and add the comment to the database
	 * 
	 * Supports the following tags:
	 * 
	 * - [b][/b] bold
	 * - [i][/i] italic
	 * - [u][/u] underline
	 * - [img][/img]
	 * - [code][/code]
	 * - [color=#ff0000][/color]
	 * - [quote][/quote]
	 * - [quote=tim][/quote]
	 * - [url][/url]
	 * - [url=http://][/url]
	 * - [email][/email]
	 * - [email=name@domain.com][/email]
	 * @param object
	 * @param object
	 */
	protected function addComment(Database_Result $objArticle, Database_Result $objArchive)
	{
		$strWebsite = $this->Input->post('website');

		// Add http:// to website
		if (strlen($strWebsite) && !preg_match('@^https?://|ftp://|mailto:@i', $strWebsite))
		{
			$strWebsite = 'http://' . $strWebsite;
		}

		$strComment = trim($this->Input->post('comment', true));

		// Replace bbcode
		if ($objArchive->bbcode)
		{
			$arrSearch = array
			(
				'[b]', '[/b]',
				'[i]', '[/i]',
				'[u]', '[/u]',
				'[code]', '[/code]',
				'[/color]',
				'[quote]', '[/quote]'
			);

			$arrReplace = array
			(
				'<strong>', '</strong>',
				'<em>', '</em>',
				'<span style="text-decoration:underline;">', '</span>',
				'<div class="code"><p>' . $GLOBALS['TL_LANG']['MSC']['com_code'] . '</p><pre>', '</pre></div>',
				'</span>',
				'<div class="quote">', '</div>'
			);

			$strComment = str_replace($arrSearch, $arrReplace, $strComment);

			$strComment = preg_replace('/\[color=([^\]]+)\]/i', '<span style="color:$1;">', $strComment);
			$strComment = preg_replace('/\[quote=([^\]]+)\]/i', '<div class="quote"><p>' . sprintf($GLOBALS['TL_LANG']['MSC']['com_quote'], '$1') . '</p>', $strComment);
			$strComment = preg_replace('/\[img\]([^\[]+)\[\/img\]/i', '<img src="$1" alt="" />', $strComment);

			$strComment = preg_replace('/\[url\]([^\[]+)\[\/url\]/i', '<a href="$1">$1</a>', $strComment);
			$strComment = preg_replace('/\[url=([^\]]+)\]([^\[]+)\[\/url\]/i', '<a href="$1">$2</a>', $strComment);

			$strComment = preg_replace('/\[email\]([^\[]+)\[\/email\]/i', '<a href="mailto:$1">$1</a>', $strComment);
			$strComment = preg_replace('/\[email=([^\]]+)\]([^\[]+)\[\/email\]/i', '<a href="mailto:$1">$2</a>', $strComment);

			$strComment = preg_replace(array('@</div>(\n)*@', '@\r@'), array("</div>\n", ''), $strComment);
		}

		// Encode e-mail addresses
		if (strpos($strComment, 'mailto:') !== false)
		{
			$this->import('String');
			$strComment = $this->String->encodeEmail($strComment);
		}

		$time = time();

		// Prevent cross-site request forgeries
		$strComment = preg_replace('/(href|src|on[a-z]+)="[^"]*(typolight\/main\.php|javascript|vbscri?pt|script|alert|document|cookie|window)[^"]*"+/i', '$1="#"', $strComment);

		// Prepare record
		$arrSet = array
		(
			'pid' => $objArticle->id,
			'tstamp' => $time,
			'name' => $this->Input->post('name'),
			'email' => $this->Input->post('email', true),
			'website' => $strWebsite,
			'comment' => nl2br_pre($strComment),
			'ip' => $this->Environment->ip,
			'date' => $time,
			'published' => 1
		);

		// Moderate
		if ($objArchive->moderate)
		{
			$arrSet['published'] = '';
		}

		$insert = $this->Database->prepare("INSERT INTO tl_news_comments %s")->set($arrSet)->execute();

		// Send notification
		$objEmail = new Email();
		$strNotify = $GLOBALS['TL_ADMIN_EMAIL'];

		// Notify author
		if ($objArchive->notify == 'notify_author')
		{
			$objAuthor = $this->Database->prepare("SELECT email FROM tl_user WHERE id=?")
										->limit(1)
										->execute($objArticle->author);

			if ($objAuthor->numRows)
			{
				$strNotify = $objAuthor->email;
			}
		}

		$objEmail->from = $strNotify;
		$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['com_subject'], $this->Environment->host);

		$objEmail->text = sprintf($GLOBALS['TL_LANG']['MSC']['com_message'],
								  $arrSet['name'] . ' (' . $arrSet['email'] . ')',
								  strip_tags($arrSet['comment']),
								  $this->Environment->base . $this->Environment->request,
								  $this->Environment->base . 'typolight/main.php?do=news&key=comments&act=edit&id=' . $insert->insertId);

		$objEmail->sendTo($strNotify);
	}
}

?>