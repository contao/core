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
 * @package    Comments
 * @license    LGPL
 * @filesource
 */


/**
 * Class ContentComments
 *
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ContentComments extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_comments';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### COMMENTS ###';

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		$limit = null;
		$arrComments = array();

		// Pagination
		if ($this->com_perPage > 0)
		{
			$page = $this->Input->get('page') ? $this->Input->get('page') : 1;
			$limit = $this->com_perPage;
			$offset = ($page - 1) * $this->com_perPage;

			// Get total number of comments
			$objTotal = $this->Database->prepare("SELECT COUNT(*) AS count FROM tl_comments WHERE pid=?" . (!BE_USER_LOGGED_IN ? " AND published=1" : ""))
									   ->execute($this->id);

			// Add pagination menu
			$objPagination = new Pagination($objTotal->count, $this->com_perPage);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}

		// Get all published comments
		$objCommentsStmt = $this->Database->prepare("SELECT * FROM tl_comments WHERE pid=?" . (!BE_USER_LOGGED_IN ? " AND published=1" : "") . " ORDER BY date" . (($this->com_order == 'descending') ? " DESC" : ""));

		if ($limit)
		{
			$objCommentsStmt->limit($limit, $offset);
		}

		$objComments = $objCommentsStmt->execute($this->id);

		if ($objComments->numRows)
		{
			$count = 0;
			$objTemplate = new FrontendTemplate($this->com_template);

			while ($objComments->next())
			{
				$objTemplate->name = $objComments->name;
				$objTemplate->email = $objComments->email;
				$objTemplate->website = $objComments->website;
				$objTemplate->comment = trim($objComments->comment);
				$objTemplate->datim = date($GLOBALS['TL_CONFIG']['datimFormat'], $objComments->date);
				$objTemplate->date = date($GLOBALS['TL_CONFIG']['dateFormat'], $objComments->date);
				$objTemplate->class = (($count++ % 2) == 0) ? ' even' : ' odd';
				$objTemplate->by = $GLOBALS['TL_LANG']['MSC']['comment_by'];
				$objTemplate->id = 'c' . $objComments->id;
				$objTemplate->ip = $objComments->ip;
				$objTemplate->timestamp = $objComments->date;

				$arrComments[] = $objTemplate->parse();
			}
		}

		$this->Template->comments = $arrComments;
		$this->Template->name = $GLOBALS['TL_LANG']['MSC']['com_name'];
		$this->Template->email = $GLOBALS['TL_LANG']['MSC']['com_email'];
		$this->Template->website = $GLOBALS['TL_LANG']['MSC']['com_website'];

		// Get front end user object
		$this->import('FrontendUser', 'User');

		// Access control
		if ($this->protected && !BE_USER_LOGGED_IN)
		{
			if (!FE_USER_LOGGED_IN)
			{
				$this->Template->protected = true;
				return;
			}

			$arrGroups = deserialize($this->groups);

			if (is_array($arrGroups) && count(array_intersect($this->User->groups, $arrGroups)) < 1)
			{
				$this->Template->protected = true;
				return;
			}
		}

		// Form fields
		$arrFields = array
		(
			'name' => array
			(
				'name' => 'name',
				'label' => $GLOBALS['TL_LANG']['MSC']['com_name'],
				'value' => $this->User->firstname . ' ' . $this->User->lastname,
				'inputType' => 'text',
				'eval' => array('mandatory'=>true, 'maxlength'=>64)
			),
			'email' => array
			(
				'name' => 'email',
				'label' => $GLOBALS['TL_LANG']['MSC']['com_email'],
				'value' => $this->User->email,
				'inputType' => 'text',
				'eval' => array('rgxp'=>'email', 'mandatory'=>true, 'maxlength'=>128)
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
		if (!$this->com_disableCaptcha)
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
			if ($this->Input->post('FORM_SUBMIT') == 'tl_comment')
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

		// Add comment
		if ($this->Input->post('FORM_SUBMIT') == 'tl_comment' && !$doNotSubmit)
		{
			$this->addComment();
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
	 */
	private function addComment()
	{
		$strWebsite = $this->Input->post('website');

		// Add http:// to website
		if (strlen($strWebsite) && !preg_match('@^https?://|ftp://|mailto:@i', $strWebsite))
		{
			$strWebsite = 'http://' . $strWebsite;
		}

		$strComment = trim($this->Input->post('comment', DECODE_ENTITIES));

		// Replace bbcode
		if ($this->com_bbcode)
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

		// Prevent cross-site request forgeries
		$strComment = preg_replace('/(href|src)="[^"]*(typolight\/main\.php|javascript|vbscri?pt|script|alert|document|cookie|window)[^"]*"/i', '$1="#"', $strComment);

		// Prepare record
		$arrSet = array
		(
			'pid' => $this->id,
			'tstamp' => time(),
			'name' => $this->Input->post('name'),
			'email' => $this->Input->post('email'),
			'website' => $strWebsite,
			'comment' => nl2br_pre($strComment),
			'ip' => $this->Environment->ip,
			'date' => time(),
			'published' => 1
		);

		// Moderate
		if ($this->com_moderate)
		{
			$arrSet['published'] = '';
		}

		$this->Database->prepare("INSERT INTO tl_comments %s")->set($arrSet)->execute();

		// Inform admin
		$objEmail = new Email();

		$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
		$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['com_subject'], $this->Environment->host);

		// Add comment details
		$strData = "\n\n";
		$strData .= 'Name: ' . $arrSet['name'] . ' (' . $arrSet['email'] . ')' . "\n";
		$strData .= 'Comment: ' . strip_tags($arrSet['comment']) . "\n";

		$objEmail->text = sprintf($GLOBALS['TL_LANG']['MSC']['com_message'], $strData . "\n") . "\n";
		$objEmail->sendTo($GLOBALS['TL_ADMIN_EMAIL']);
	}
}

?>