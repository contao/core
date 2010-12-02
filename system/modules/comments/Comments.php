<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Comments
 * @license    LGPL
 * @filesource
 */


/**
 * Class Comments
 *
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.contao.org>
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
				$objPartial->setData($objComments->row());

				$objPartial->comment = trim(str_replace(array('{{', '}}'), array('&#123;&#123;', '&#125;&#125;'), $objComments->comment));
				$objPartial->datim = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $objComments->date);
				$objPartial->date = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objComments->date);
				$objPartial->class = (($count < 1) ? ' first' : '') . (($count >= ($total - 1)) ? ' last' : '') . (($count % 2 == 0) ? ' even' : ' odd');
				$objPartial->by = $GLOBALS['TL_LANG']['MSC']['comment_by'];
				$objPartial->id = 'c' . $objComments->id;
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
			global $objPage;

			// Do not index the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			$objTemplate->confirm = $GLOBALS['TL_LANG']['MSC']['com_confirm'];
			$_SESSION['TL_COMMENT_ADDED'] = false;
		}

		// Add the comment
		if ($this->Input->post('FORM_SUBMIT') == $strFormId && !$doNotSubmit)
		{
			$this->import('String');
			$strWebsite = $arrWidgets['website']->value;

			// Add http:// to the website
			if (strlen($strWebsite) && !preg_match('@^(https?://|ftp://|mailto:|#)@i', $strWebsite))
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
				'ip' => $this->Environment->ip,
				'date' => $time,
				'published' => ($objConfig->moderate ? '' : 1)
			);

			$insertId = $this->Database->prepare("INSERT INTO tl_comments %s")->set($arrSet)->execute()->insertId;

			// HOOK: add custom logic
			if (isset($GLOBALS['TL_HOOKS']['addComment']) && is_array($GLOBALS['TL_HOOKS']['addComment']))
			{
				foreach ($GLOBALS['TL_HOOKS']['addComment'] as $callback)
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($insertId, $arrSet);
				}
			}

			// Notification
			$objEmail = new Email();

			$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
			$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
			$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['com_subject'], $this->Environment->host);

			// Convert the comment to plain text
			$strComment = strip_tags($strComment);
			$strComment = $this->String->decodeEntities($strComment);
			$strComment = str_replace(array('[&]', '[lt]', '[gt]'), array('&', '<', '>'), $strComment);

			// Add comment details
			$objEmail->text = sprintf($GLOBALS['TL_LANG']['MSC']['com_message'],
									  $arrSet['name'] . ' (' . $arrSet['email'] . ')',
									  $strComment,
									  $this->Environment->base . $this->Environment->request,
									  $this->Environment->base . 'contao/main.php?do=comments&act=edit&id=' . $insertId);

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
			'<span style="text-decoration:underline;">$1</span>',
			"\n\n" . '<div class="code"><p>'. $GLOBALS['TL_LANG']['MSC']['com_code'] .'</p><pre>$1</pre></div>' . "\n\n",
			'<span style="color:$1;">$2</span>',
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
			$strComment = $this->String->encodeEmail($strComment);
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
		$strComment = nl2br_pre($strComment);

		// Use paragraphs to generate new lines
		if ($GLOBALS['TL_CONFIG']['pNewLine'])
		{
			if (strncmp('<p>', $strComment, 3) !== 0)
			{
				$strComment = '<p>'. $strComment .'</p>';
			}

			$arrSearch = array
			(
				'@<br />\s?<br />\s?@',
				'@\s?<br /></p>@',
				'@<p><div@', '@</div></p>@'
			);

			$arrReplace = array
			(
				"</p>\n<p>",
				'</p>',
				'<div', '</div>'
			);

			$strComment = preg_replace($arrSearch, $arrReplace, $strComment);
		}

		return $strComment;
	}
}

?>