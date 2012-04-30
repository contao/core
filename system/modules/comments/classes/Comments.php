<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Comments
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \CommentsModel, \Email, \Environment, \Frontend, \FrontendTemplate, \Input, \Pagination, \String;


/**
 * Class Comments
 *
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class Comments extends Frontend
{

	/**
	 * Add comments to a template
	 * @param \FrontendTemplate
	 * @param \stdClass
	 * @param string
	 * @param integer
	 * @param array
	 * @return void
	 */
	public function addCommentsToTemplate(FrontendTemplate $objTemplate, \stdClass $objConfig, $strSource, $intParent, $arrNotifies)
	{
		global $objPage;

		$limit = 0;
		$offset = 0;
		$total = 0;
		$gtotal = 0;
		$arrComments = array();
		$objTemplate->comments = array(); // see #4064

		// Pagination
		if ($objConfig->perPage > 0)
		{
			// Get the total number of comments
			$intTotal = CommentsModel::countPublishedBySourceAndParent($strSource, $intParent);
			$total = $gtotal = $intTotal;

			// Get the current page
			$id = 'page_c' . $this->id;
			$page = Input::get($id) ?: 1;

			// Do not index or cache the page if the page number is outside the range
			if ($page < 1 || $page > max(ceil($total/$objConfig->perPage), 1))
			{
				global $objPage;
				$objPage->noSearch = 1;
				$objPage->cache = 0;

				// Send a 404 header
				header('HTTP/1.1 404 Not Found');
				$objTemplate->allowComments = false;
				return;
			}

			// Set limit and offset
			$limit = $objConfig->perPage;
			$offset = ($page - 1) * $objConfig->perPage;
 
			// Initialize the pagination menu
			$objPagination = new Pagination($total, $objConfig->perPage, 7, $id);
			$objTemplate->pagination = $objPagination->generate("\n  ");
		}

		$objTemplate->allowComments = true;

		// Get all published comments
		if ($limit)
		{
			$objComments = CommentsModel::findPublishedBySourceAndParent($strSource, $intParent, $limit, $offset);
		}
		else
		{
			$objComments = CommentsModel::findPublishedBySourceAndParent($strSource, $intParent);
		}

		if ($objComments !== null && ($total = $objComments->count()) > 0)
		{
			$count = 0;

			if ($objConfig->template == '')
			{
				$objConfig->template = 'com_default';
			}

			$objPartial = new FrontendTemplate($objConfig->template);

			while ($objComments->next())
			{
				$objPartial->setData($objComments->row());

				// Clean the RTE output
				if ($objPage->outputFormat == 'xhtml')
				{
					$objComments->comment = String::toXhtml($objComments->comment);
				}
				else
				{
					$objComments->comment = String::toHtml5($objComments->comment);
				}

				$objPartial->comment = trim(str_replace(array('{{', '}}'), array('&#123;&#123;', '&#125;&#125;'), $objComments->comment));

				$objPartial->datim = $this->parseDate($objPage->datimFormat, $objComments->date);
				$objPartial->date = $this->parseDate($objPage->dateFormat, $objComments->date);
				$objPartial->class = (($count < 1) ? ' first' : '') . (($count >= ($total - 1)) ? ' last' : '') . (($count % 2 == 0) ? ' even' : ' odd');
				$objPartial->by = $GLOBALS['TL_LANG']['MSC']['comment_by'];
				$objPartial->id = 'c' . $objComments->id;
				$objPartial->timestamp = $objComments->date;
				$objPartial->datetime = date('Y-m-d\TH:i:sP', $objComments->date);
				$objPartial->addReply = false;

				// Reply
				if ($objComments->addReply && $objComments->reply != '')
				{
					if (($objAuthor = $objComments->getRelated('author')) !== null)
					{
						$objPartial->addReply = true;
						$objPartial->rby = $GLOBALS['TL_LANG']['MSC']['reply_by'];
						$objPartial->reply = $this->replaceInsertTags($objComments->reply);
						$objPartial->author = $objAuthor;

						// Clean the RTE output
						if ($objPage->outputFormat == 'xhtml')
						{
							$objPartial->reply = String::toXhtml($objPartial->reply);
						}
						else
						{
							$objPartial->reply = String::toHtml5($objPartial->reply);
						}
					}
				}

				$arrComments[] = $objPartial->parse();
				++$count;
			}
		}

		$objTemplate->comments = $arrComments;
		$objTemplate->addComment = $GLOBALS['TL_LANG']['MSC']['addComment'];
		$objTemplate->name = $GLOBALS['TL_LANG']['MSC']['com_name'];
		$objTemplate->email = $GLOBALS['TL_LANG']['MSC']['com_email'];
		$objTemplate->website = $GLOBALS['TL_LANG']['MSC']['com_website'];
		$objTemplate->commentsTotal = $limit ? $gtotal : $total;

		// Get the front end user object
		$this->import('FrontendUser', 'User');

		// Access control
		if ($objConfig->requireLogin && !BE_USER_LOGGED_IN && !FE_USER_LOGGED_IN)
		{
			$objTemplate->requireLogin = true;
			return;
		}

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
				'eval' => array('rgxp'=>'email', 'mandatory'=>true, 'maxlength'=>128, 'decodeEntities'=>true)
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
		if (!$objConfig->disableCaptcha)
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
			'label' => $GLOBALS['TL_LANG']['MSC']['com_comment'],
			'inputType' => 'textarea',
			'eval' => array('mandatory'=>true, 'rows'=>4, 'cols'=>40, 'preserveTags'=>true)
		);

		$doNotSubmit = false;
		$arrWidgets = array();
		$strFormId = 'com_'. $strSource .'_'. $intParent;

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

			// Validate the widget
			if (Input::post('FORM_SUBMIT') == $strFormId)
			{
				$objWidget->validate();

				if ($objWidget->hasErrors())
				{
					$doNotSubmit = true;
				}
			}

			$arrWidgets[$arrField['name']] = $objWidget;
		}

		$objTemplate->fields = $arrWidgets;
		$objTemplate->submit = $GLOBALS['TL_LANG']['MSC']['com_submit'];
		$objTemplate->action = ampersand(Environment::get('request'));
		$objTemplate->messages = ''; // Backwards compatibility
		$objTemplate->formId = $strFormId;
		$objTemplate->hasError = $doNotSubmit;

		// Do not index or cache the page with the confirmation message
		if ($_SESSION['TL_COMMENT_ADDED'])
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			$objTemplate->confirm = $GLOBALS['TL_LANG']['MSC']['com_confirm'];
			$_SESSION['TL_COMMENT_ADDED'] = false;
		}

		// Add the comment
		if (!$doNotSubmit && Input::post('FORM_SUBMIT') == $strFormId)
		{
			$strWebsite = $arrWidgets['website']->value;

			// Add http:// to the website
			if (($strWebsite != '') && !preg_match('@^(https?://|ftp://|mailto:|#)@i', $strWebsite))
			{
				$strWebsite = 'http://' . $strWebsite;
			}

			// Do not parse any tags in the comment
			$strComment = htmlspecialchars(trim($arrWidgets['comment']->value));
			$strComment = str_replace(array('&amp;', '&lt;', '&gt;'), array('[&]', '[lt]', '[gt]'), $strComment);

			// Remove multiple line feeds
			$strComment = preg_replace('@\n\n+@', "\n\n", $strComment);

			// Parse BBCode
			if ($objConfig->bbcode)
			{
				$strComment = $this->parseBbCode($strComment);
			}

			// Prevent cross-site request forgeries
			$strComment = preg_replace('/(href|src|on[a-z]+)="[^"]*(contao\/main\.php|typolight\/main\.php|javascript|vbscri?pt|script|alert|document|cookie|window)[^"]*"+/i', '$1="#"', $strComment);

			$time = time();

			// Prepare the record
			$arrSet = array
			(
				'source' => $strSource,
				'parent' => $intParent,
				'tstamp' => $time,
				'name' => $arrWidgets['name']->value,
				'email' => $arrWidgets['email']->value,
				'website' => $strWebsite,
				'comment' => $this->convertLineFeeds($strComment),
				'ip' => $this->anonymizeIp(Environment::get('ip')),
				'date' => $time,
				'published' => ($objConfig->moderate ? '' : 1)
			);

			$objComment = new CommentsModel();
			$objComment->setRow($arrSet);
			$objComment->save();
			$insertId = $objComment->id;

			// HOOK: add custom logic
			if (isset($GLOBALS['TL_HOOKS']['addComment']) && is_array($GLOBALS['TL_HOOKS']['addComment']))
			{
				foreach ($GLOBALS['TL_HOOKS']['addComment'] as $callback)
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($insertId, $arrSet, $this);
				}
			}

			// Notification
			$objEmail = new Email();

			$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
			$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
			$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['com_subject'], Environment::get('host'));

			// Convert the comment to plain text
			$strComment = strip_tags($strComment);
			$strComment = String::decodeEntities($strComment);
			$strComment = str_replace(array('[&]', '[lt]', '[gt]'), array('&', '<', '>'), $strComment);

			// Add comment details
			$objEmail->text = sprintf($GLOBALS['TL_LANG']['MSC']['com_message'],
									  $arrSet['name'] . ' (' . $arrSet['email'] . ')',
									  $strComment,
									  Environment::get('base') . Environment::get('request'),
									  Environment::get('base') . 'contao/main.php?do=comments&act=edit&id=' . $insertId);

			// Do not send notifications twice
			if (is_array($arrNotifies))
			{
				$arrNotifies = array_unique($arrNotifies);
			}

			$objEmail->sendTo($arrNotifies);

			// Pending for approval
			if ($objConfig->moderate)
			{
				$_SESSION['TL_COMMENT_ADDED'] = true;
			}

			$this->reload();
		}
	}


	/**
	 * Replace bbcode and return the HTML string
	 * 
	 * Supports the following tags:
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
	 * @param string
	 * @return string
	 */
	public function parseBbCode($strComment)
	{
		$arrSearch = array
		(
			'@\[b\](.*)\[/b\]@Uis',
			'@\[i\](.*)\[/i\]@Uis',
			'@\[u\](.*)\[/u\]@Uis',
			'@\s*\[code\](.*)\[/code\]\s*@Uis',
			'@\[color=([^\]" ]+)\](.*)\[/color\]@Uis',
			'@\s*\[quote\](.*)\[/quote\]\s*@Uis',
			'@\s*\[quote=([^\]]+)\](.*)\[/quote\]\s*@Uis', 
			'@\[img\]\s*([^\[" ]+\.(jpe?g|png|gif|bmp|tiff?|ico))\s*\[/img\]@i',
			'@\[url\]\s*([^\[" ]+)\s*\[/url\]@i',
			'@\[url=([^\]" ]+)\](.*)\[/url\]@Uis',
			'@\[email\]\s*([^\[" ]+)\s*\[/email\]@i',
			'@\[email=([^\]" ]+)\](.*)\[/email\]@Uis',
			'@href="(([a-z0-9]+\.)*[a-z0-9]+\.([a-z]{2}|asia|biz|com|info|name|net|org|tel)(/|"))@i'
		);

		$arrReplace = array
		(
			'<strong>$1</strong>',
			'<em>$1</em>',
			'<span style="text-decoration:underline">$1</span>',
			"\n\n" . '<div class="code"><p>'. $GLOBALS['TL_LANG']['MSC']['com_code'] .'</p><pre>$1</pre></div>' . "\n\n",
			'<span style="color:$1">$2</span>',
			"\n\n" . '<div class="quote">$1</div>' . "\n\n",
			"\n\n" . '<div class="quote"><p>'. sprintf($GLOBALS['TL_LANG']['MSC']['com_quote'], '$1') .'</p>$2</div>' . "\n\n",
			'<img src="$1" alt="" />',
			'<a href="$1">$1</a>',
			'<a href="$1">$2</a>',
			'<a href="mailto:$1">$1</a>',
			'<a href="mailto:$1">$2</a>',
			'href="http://$1'
		);

		$strComment = preg_replace($arrSearch, $arrReplace, $strComment);

		// Encode e-mail addresses
		if (strpos($strComment, 'mailto:') !== false)
		{
			$strComment = String::encodeEmail($strComment);
		}

		return $strComment;
	}


	/**
	 * Convert line feeds to <br /> tags
	 * @param string
	 * @return string
	 */
	public function convertLineFeeds($strComment)
	{
		global $objPage;
		$strComment = nl2br_pre($strComment, ($objPage->outputFormat == 'xhtml'));

		// Use paragraphs to generate new lines
		if (strncmp('<p>', $strComment, 3) !== 0)
		{
			$strComment = '<p>'. $strComment .'</p>';
		}

		$arrReplace = array
		(
			'@<br>\s?<br>\s?@' => "</p>\n<p>", // Convert two linebreaks into a new paragraph
			'@\s?<br></p>@'    => '</p>',      // Remove BR tags before closing P tags
			'@<p><div@'        => '<div',      // Do not nest DIVs inside paragraphs
			'@</div></p>@'     => '</div>'     // Do not nest DIVs inside paragraphs
		);

		return preg_replace(array_keys($arrReplace), array_values($arrReplace), $strComment);
	}
}
