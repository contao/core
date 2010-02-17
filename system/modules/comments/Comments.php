<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Comments
 * @license    LGPL
 * @filesource
 */


/**
 * Class Comments
 *
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Controller
 */
class Comments extends Frontend
{

	/**
	 * Add comments to a template
	 * @param object
	 * @param object
	 * @param string
	 * @param integer
	 * @param array
	 */
	public function addCommentsToTemplate($objTemplate, $objConfig, $strSource, $intParent, $arrNotifies)
	{
		$limit = null;
		$arrComments = array();

		// Pagination
		if ($objConfig->perPage > 0)
		{
			$page = $this->Input->get('page') ? $this->Input->get('page') : 1;
			$limit = $objConfig->perPage;
			$offset = ($page - 1) * $objConfig->perPage;
 
			// Get total number of comments
			$objTotal = $this->Database->prepare("SELECT COUNT(*) AS count FROM tl_comments WHERE source=? AND parent=?" . (!BE_USER_LOGGED_IN ? " AND published=1" : ""))
									   ->execute($strSource, $intParent);

			// Add pagination menu
			$objPagination = new Pagination($objTotal->count, $objConfig->perPage);
			$objTemplate->pagination = $objPagination->generate("\n  ");
		}

		// Get all published comments
		$objCommentsStmt = $this->Database->prepare("SELECT * FROM tl_comments WHERE source=? AND parent=?" . (!BE_USER_LOGGED_IN ? " AND published=1" : "") . " ORDER BY date" . (($objConfig->order == 'descending') ? " DESC" : ""));

		if ($limit)
		{
			$objCommentsStmt->limit($limit, $offset);
		}

		$objComments = $objCommentsStmt->execute($strSource, $intParent);
		$total = $objComments->numRows;

		if ($total > 0)
		{
			$count = 0;

			if ($objConfig->template == '')
			{
				$objConfig->template = 'com_default';
			}

			$objPartial = new FrontendTemplate($objConfig->template);

			while ($objComments->next())
			{
				// Use paragraphs to generate new lines
				if ($GLOBALS['TL_CONFIG']['pNewLine'])
				{
					$comment = $objComments->comment;

					if (strncmp('<p>', $comment, 3) !== 0)
					{
						$comment = '<p>'. $comment .'</p>';
					}

					$objComments->comment = preg_replace(array('@<br />\W?<br />\W?@i', '@\W?<br /></p>@i'), array("</p>\n<p>", '</p>'), $comment);
				}

				$objPartial->name = $objComments->name;
				$objPartial->email = $objComments->email;
				$objPartial->website = $objComments->website;
				$objPartial->comment = trim($objComments->comment);
				$objPartial->datim = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $objComments->date);
				$objPartial->date = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objComments->date);
				$objPartial->class = (($count < 1) ? ' first' : '') . (($count >= ($total - 1)) ? ' last' : '') . (($count % 2 == 0) ? ' even' : ' odd');
				$objPartial->by = $GLOBALS['TL_LANG']['MSC']['comment_by'];
				$objPartial->id = 'c' . $objComments->id;
				$objPartial->ip = $objComments->ip;
				$objPartial->timestamp = $objComments->date;

				$arrComments[] = $objPartial->parse();
				++$count;
			}
		}

		$objTemplate->comments = $arrComments;
		$objTemplate->addComment = $GLOBALS['TL_LANG']['MSC']['addComment'];
		$objTemplate->name = $GLOBALS['TL_LANG']['MSC']['com_name'];
		$objTemplate->email = $GLOBALS['TL_LANG']['MSC']['com_email'];
		$objTemplate->website = $GLOBALS['TL_LANG']['MSC']['com_website'];

		// Get front end user object
		$this->import('FrontendUser', 'User');

		// Access control
		if ($objConfig->requireLogin && !BE_USER_LOGGED_IN && !FE_USER_LOGGED_IN)
		{
			$objTemplate->protected = true;
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
			'eval' => array('rows'=>4, 'cols'=>40, 'preserveTags'=>true)
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

			// Validate widget
			if ($this->Input->post('FORM_SUBMIT') == $strFormId)
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
		$objTemplate->action = ampersand($this->Environment->request);
		$objTemplate->messages = $this->getMessages();
		$objTemplate->formId = $strFormId;
		$objTemplate->hasError = $doNotSubmit;

		// Confirmation message
		if ($_SESSION['TL_COMMENT_ADDED'])
		{
			$objTemplate->confirm = $GLOBALS['TL_LANG']['MSC']['com_confirm'];
			$_SESSION['TL_COMMENT_ADDED'] = false;
		}

		// Add comment
		if ($this->Input->post('FORM_SUBMIT') == $strFormId && !$doNotSubmit)
		{
			$strWebsite = $arrWidgets['website']->value;

			// Add http:// to website
			if (strlen($strWebsite) && !preg_match('@^https?://|ftp://|mailto:@i', $strWebsite))
			{
				$strWebsite = 'http://' . $strWebsite;
			}

			// Do not parse any tags in the comment
			$strComment = htmlspecialchars($arrWidgets['comment']->value);
			$strComment = str_replace(array('&amp;', '&lt;', '&gt;'), array('[&]', '[lt]', '[gt]'), $strComment);

			// Parse BBCode
			if ($objConfig->bbcode)
			{
				$strComment = $this->parseBbCode($strComment);
			}

			// Prevent cross-site request forgeries
			$strComment = preg_replace('/(href|src|on[a-z]+)="[^"]*(typolight\/main\.php|javascript|vbscri?pt|script|alert|document|cookie|window)[^"]*"+/i', '$1="#"', $strComment);

			$intPublished = $objConfig->moderate ? '' : 1;
			$time = time();

			// Prepare record
			$arrSet = array
			(
				'source' => $strSource,
				'parent' => $intParent,
				'tstamp' => $time,
				'name' => $arrWidgets['name']->value,
				'email' => $arrWidgets['email']->value,
				'website' => $strWebsite,
				'comment' => nl2br_pre($strComment),
				'ip' => $this->Environment->ip,
				'date' => $time,
				'published' => $intPublished
			);

			$insert = $this->Database->prepare("INSERT INTO tl_comments %s")->set($arrSet)->execute();

			// Notification
			$objEmail = new Email();

			$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
			$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
			$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['com_subject'], $this->Environment->host);

			// Add comment details
			$objEmail->text = sprintf($GLOBALS['TL_LANG']['MSC']['com_message'],
									  $arrSet['name'] . ' (' . $arrSet['email'] . ')',
									  str_replace(array('[&]', '[lt]', '[gt]'), array('&', '<', '>'), strip_tags($strComment)),
									  $this->Environment->base . $this->Environment->request,
									  $this->Environment->base . 'typolight/main.php?do=comments&act=edit&id=' . $insert->insertId);

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
	 * @param string
	 * @return string
	 */
	public function parseBbCode($strComment)
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

		// Replace simple tokens
		$strComment = str_replace($arrSearch, $arrReplace, trim($strComment));

		// Color, quote and image
		$strComment = preg_replace('/\[color=([^\]]+)\]/i', '<span style="color:$1;">', $strComment);
		$strComment = preg_replace('/\[quote=([^\]]+)\]/i', '<div class="quote"><p>' . sprintf($GLOBALS['TL_LANG']['MSC']['com_quote'], '$1') . '</p>', $strComment);
		$strComment = preg_replace('/\[img\]([^\[]+)\[\/img\]/i', '<img src="$1" alt="" />', $strComment);

		// URLs
		$strComment = preg_replace('/\[url\]([^\[]+)\[\/url\]/i', '<a href="$1">$1</a>', $strComment);
		$strComment = preg_replace('/\[url=([^\]]+)\]([^\[]+)\[\/url\]/i', '<a href="$1">$2</a>', $strComment);

		// E-mail addresses
		$strComment = preg_replace('/\[email\]([^\[]+)\[\/email\]/i', '<a href="mailto:$1">$1</a>', $strComment);
		$strComment = preg_replace('/\[email=([^\]]+)\]([^\[]+)\[\/email\]/i', '<a href="mailto:$1">$2</a>', $strComment);

		// Line feeds
		$strComment = preg_replace(array('@</div>(\n)*@', '@\r@'), array("</div>\n", ''), $strComment);

		// Encode e-mail addresses
		if (strpos($strComment, 'mailto:') !== false)
		{
			$this->import('String');
			$strComment = $this->String->encodeEmail($strComment);
		}

		return $strComment;
	}
}

?>