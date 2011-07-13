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
 * @package    Newsletter
 * @license    LGPL
 * @filesource
 */


/**
 * Class Newsletter
 *
 * Provide methods to handle newsletters.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class Newsletter extends Backend
{

	/**
	 * Renturn a form to choose an existing style sheet and import it
	 * @param object
	 * @return string
	 */
	public function send(DataContainer $objDc)
	{
		$objNewsletter = $this->Database->prepare("SELECT n.*, c.useSMTP, c.smtpHost, c.smtpPort, c.smtpUser, c.smtpPass FROM tl_newsletter n LEFT JOIN tl_newsletter_channel c ON n.pid=c.id WHERE n.id=?")
										->limit(1)
										->execute($objDc->id);

		// Return if there is no newsletter
		if ($objNewsletter->numRows < 1)
		{
			return '';
		}

		// Overwrite the SMTP configuration
		if ($objNewsletter->useSMTP)
		{
			$GLOBALS['TL_CONFIG']['useSMTP'] = true;

			$GLOBALS['TL_CONFIG']['smtpHost'] = $objNewsletter->smtpHost;
			$GLOBALS['TL_CONFIG']['smtpUser'] = $objNewsletter->smtpUser;
			$GLOBALS['TL_CONFIG']['smtpPass'] = $objNewsletter->smtpPass;
			$GLOBALS['TL_CONFIG']['smtpEnc']  = $objNewsletter->smtpEnc;
			$GLOBALS['TL_CONFIG']['smtpPort'] = $objNewsletter->smtpPort;
		}

		// Add default sender address
		if (!strlen($objNewsletter->sender))
		{
			list($objNewsletter->senderName, $objNewsletter->sender) = $this->splitFriendlyName($GLOBALS['TL_CONFIG']['adminEmail']);
		}

		$arrAttachments = array();

		// Add attachments
		if ($objNewsletter->addFile)
		{
			$files = deserialize($objNewsletter->files);

			if (is_array($files) && count($files) > 0)
			{
				foreach ($files as $file)
				{
					if (is_file(TL_ROOT . '/' . $file))
					{
						$arrAttachments[] = $file;
					}
				}
			}
		}

		$css = '';

		// Add style sheet newsletter.css
		if (!$objNewsletter->sendText && file_exists(TL_ROOT . '/newsletter.css'))
		{
			$buffer = file_get_contents(TL_ROOT . '/newsletter.css');
			$buffer = preg_replace('@/\*\*.*\*/@Us', '', $buffer);

			$css  = '<style type="text/css">' . "\n";
			$css .= trim($buffer) . "\n";
			$css .= '</style>' . "\n";
		}

		// Replace insert tags
		$html = $this->replaceInsertTags($objNewsletter->content);
		$text = $this->replaceInsertTags($objNewsletter->text);

		// Convert relative URLs
		if ($objNewsletter->externalImages)
		{
			$html = $this->convertRelativeUrls($html);
		}

		// Send newsletter
		if (strlen($this->Input->get('token')) && $this->Input->get('token') == $this->Session->get('tl_newsletter_send'))
		{
			$referer = preg_replace('/&(amp;)?(start|mpc|token|recipient|preview)=[^&]*/', '', $this->Environment->request);

			// Preview
			if (isset($_GET['preview']))
			{
				// Check the e-mail address
				if (!$this->isValidEmailAddress($this->Input->get('recipient', true)))
				{
					$_SESSION['TL_PREVIEW_ERROR'] = true;
					$this->redirect($referer);
				}

				$arrRecipient['email'] = urldecode($this->Input->get('recipient', true));

				// Send
				$objEmail = $this->generateEmailObject($objNewsletter, $arrAttachments);
				$this->sendNewsletter($objEmail, $objNewsletter, $arrRecipient, $text, $html, $css);

				// Redirect
				$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_newsletter']['confirm'], 1);
				$this->redirect($referer);
			}

			// Get the total number of recipients
			$objTotal = $this->Database->prepare("SELECT COUNT(DISTINCT email) AS total FROM tl_newsletter_recipients WHERE pid=? AND active=1")
									   ->execute($objNewsletter->pid);

			// Return if there are no recipients
			if ($objTotal->total < 1)
			{
				$this->Session->set('tl_newsletter_send', null);
				$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['tl_newsletter']['error'];

				$this->redirect($referer);
			}

			$intTotal = $objTotal->total;

			// Get page and timeout
			$intTimeout = ($this->Input->get('timeout') > 0) ? $this->Input->get('timeout') : 1;
			$intStart = $this->Input->get('start') ? $this->Input->get('start') : 0;
			$intPages = $this->Input->get('mpc') ? $this->Input->get('mpc') : 10;

			// Get recipients
			$objRecipients = $this->Database->prepare("SELECT *, r.email FROM tl_newsletter_recipients r LEFT JOIN tl_member m ON(r.email=m.email) WHERE r.pid=? AND r.active=1 GROUP BY r.email ORDER BY r.email")
											->limit($intPages, $intStart)
											->execute($objNewsletter->pid);

			echo '<div style="font-family:Verdana, sans-serif; font-size:11px; line-height:16px; margin-bottom:12px;">';

			// Send newsletter
			if ($objRecipients->numRows > 0)
			{
				// Update status
				if ($intStart == 0)
				{
					$this->Database->prepare("UPDATE tl_newsletter SET sent=1, date=? WHERE id=?")
								   ->execute(time(), $objNewsletter->id);

					$_SESSION['REJECTED_RECIPIENTS'] = array();
				}

				while ($objRecipients->next())
				{
					$objEmail = $this->generateEmailObject($objNewsletter, $arrAttachments);
					$this->sendNewsletter($objEmail, $objNewsletter, $objRecipients->row(), $text, $html, $css);

					echo 'Sending newsletter to <strong>' . $objRecipients->email . '</strong><br>';
				}
			}

			echo '<div style="margin-top:12px;">';

			// Redirect back home
			if ($objRecipients->numRows < 1 || ($intStart + $intPages) >= $intTotal)
			{
				$this->Session->set('tl_newsletter_send', null);

				// Deactivate rejected addresses
				if (!empty($_SESSION['REJECTED_RECIPIENTS']))
				{
					$intRejected = count($_SESSION['REJECTED_RECIPIENTS']);
					$_SESSION['TL_INFO'][] = sprintf($GLOBALS['TL_LANG']['tl_newsletter']['rejected'], $intRejected);
					$intTotal -= $intRejected;

					foreach ($_SESSION['REJECTED_RECIPIENTS'] as $strRecipient)
					{
						$this->Database->prepare("UPDATE tl_newsletter_recipients SET active='' WHERE email=?")
									   ->execute($strRecipient);

						$this->log('Recipient address "' . $strRecipient . '" was rejected and has been deactivated', 'Newsletter sendNewsletter()', TL_ERROR);
					}
				}

				$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_newsletter']['confirm'], $intTotal);

				echo '<script>setTimeout(\'window.location="' . $this->Environment->base . $referer . '"\', 1000);</script>';
				echo '<a href="' . $this->Environment->base . $referer . '">Please click here to proceed if you are not using JavaScript</a>';
			}

			// Redirect to the next cycle
			else
			{
				$url = preg_replace('/&(amp;)?(start|mpc|recipient)=[^&]*/', '', $this->Environment->request) . '&start=' . ($intStart + $intPages) . '&mpc=' . $intPages;

				echo '<script>setTimeout(\'window.location="' . $this->Environment->base . $url . '"\', ' . ($intTimeout * 1000) . ');</script>';
				echo '<a href="' . $this->Environment->base . $url . '">Please click here to proceed if you are not using JavaScript</a>';
			}

			echo '</div></div>';
			exit;
		}

		$strToken = md5(uniqid(mt_rand(), true));
		$this->Session->set('tl_newsletter_send', $strToken);
		$sprintf = strlen($objNewsletter->senderName) ? $objNewsletter->senderName . ' &lt;%s&gt;' : '%s';
		$this->import('BackendUser', 'User');

		// Preview newsletter
		$return = '
<div id="tl_buttons">
<a href="'.$this->getReferer(true).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.sprintf($GLOBALS['TL_LANG']['tl_newsletter']['send'][1], $objNewsletter->id).'</h2>
'.$this->getMessages().'
<form action="'.ampersand($this->Environment->script, true).'" id="tl_newsletter_send" class="tl_form" method="get">
<div class="tl_formbody_edit tl_newsletter_send">
<input type="hidden" name="do" value="' . $this->Input->get('do') . '">
<input type="hidden" name="table" value="' . $this->Input->get('table') . '">
<input type="hidden" name="key" value="' . $this->Input->get('key') . '">
<input type="hidden" name="id" value="' . $this->Input->get('id') . '">
<input type="hidden" name="token" value="' . $strToken . '">
<table class="prev_header">
  <tr class="row_0">
    <td class="col_0">' . $GLOBALS['TL_LANG']['tl_newsletter']['from'] . '</td>
    <td class="col_1">' . sprintf($sprintf, $objNewsletter->sender) . '</td>
  </tr>
  <tr class="row_1">
    <td class="col_0">' . $GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] . '</td>
    <td class="col_1">' . $objNewsletter->subject . '</td>
  </tr>
  <tr class="row_2">
    <td class="col_0">' . $GLOBALS['TL_LANG']['tl_newsletter']['template'][0] . '</td>
    <td class="col_1">' . $objNewsletter->template . '</td>
  </tr>' . ((is_array($arrAttachments) && count($arrAttachments) > 0) ? '
  <tr class="row_3">
    <td class="col_0">' . $GLOBALS['TL_LANG']['tl_newsletter']['attachments'] . '</td>
    <td class="col_1">' . implode(', ', $arrAttachments) . '</td>
  </tr>' : '') . '
</table>' . (!$objNewsletter->sendText ? '
<div class="preview_html">
' . $html . '
</div>' : '') . '
<div class="preview_text">
' . nl2br_html5($text) . '
</div>
<div class="tl_tbox block">
<div class="w50">
  <h3><label for="ctrl_mpc">' . $GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] . '</label></h3>
  <input type="text" name="mpc" id="ctrl_mpc" value="10" class="tl_text" onfocus="Backend.getScrollOffset();">' . (($GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] && $GLOBALS['TL_CONFIG']['showHelp']) ? '
  <p class="tl_help tl_tip">' . $GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] . '</p>' : '') . '
</div>
<div class="w50">
  <h3><label for="ctrl_timeout">' . $GLOBALS['TL_LANG']['tl_newsletter']['timeout'][0] . '</label></h3>
  <input type="text" name="timeout" id="ctrl_timeout" value="1" class="tl_text" onfocus="Backend.getScrollOffset();">' . (($GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] && $GLOBALS['TL_CONFIG']['showHelp']) ? '
  <p class="tl_help tl_tip">' . $GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] . '</p>' : '') . '
</div>
<div class="w50">
  <h3><label for="ctrl_start">' . $GLOBALS['TL_LANG']['tl_newsletter']['start'][0] . '</label></h3>
  <input type="text" name="start" id="ctrl_start" value="0" class="tl_text" onfocus="Backend.getScrollOffset();">' . (($GLOBALS['TL_LANG']['tl_newsletter']['start'][1] && $GLOBALS['TL_CONFIG']['showHelp']) ? '
  <p class="tl_help tl_tip">' . $GLOBALS['TL_LANG']['tl_newsletter']['start'][1] . '</p>' : '') . '
</div>
<div class="w50">
  <h3><label for="ctrl_recipient">' . $GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][0] . '</label></h3>' . (strlen($_SESSION['TL_PREVIEW_ERROR']) ? '
  <div class="tl_error">' . $GLOBALS['TL_LANG']['ERR']['email'] . '</div>' : '') . '
  <input type="text" name="recipient" id="ctrl_recipient" value="'.$this->User->email.'" class="tl_text" onfocus="Backend.getScrollOffset();">' . (($GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] && $GLOBALS['TL_CONFIG']['showHelp']) ? '
  <p class="tl_help tl_tip">' . $GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] . '</p>' : '') . '
</div>
</div>
</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="preview" class="tl_submit" accesskey="p" value="'.specialchars($GLOBALS['TL_LANG']['tl_newsletter']['preview']).'">
<input type="submit" id="send" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_newsletter']['send'][0]).'" onclick="return confirm(\''. str_replace("'", "\\'", $GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm']) .'\')">
</div>

</div>
</form>';

		$_SESSION['TL_PREVIEW_ERROR'] = false;
		return $return;
	}


	/**
	 * Generate the e-mail object and return it
	 * @param object
	 * @param array
	 * @return object
	 */
	protected function generateEmailObject(Database_Result $objNewsletter, $arrAttachments)
	{
		$objEmail = new Email();

		$objEmail->from = $objNewsletter->sender;
		$objEmail->subject = $objNewsletter->subject;

		// Add sender name
		if (strlen($objNewsletter->senderName))
		{
			$objEmail->fromName = $objNewsletter->senderName;
		}

		$objEmail->embedImages = !$objNewsletter->externalImages;
		$objEmail->logFile = 'newsletter_' . $objNewsletter->id . '.log';

		// Attachments
		if (is_array($arrAttachments) && count($arrAttachments) > 0)
		{
			foreach ($arrAttachments as $strAttachment)
			{
				$objEmail->attachFile(TL_ROOT . '/' . $strAttachment);
			}
		}

		return $objEmail;
	}


	/**
	 * Compile the newsletter and send it
	 * @param object
	 * @param object
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	protected function sendNewsletter(Email $objEmail, Database_Result $objNewsletter, $arrRecipient, $text, $html, $css)
	{
		// Prepare text content
		$objEmail->text = $this->parseSimpleTokens($text, $arrRecipient);

		// Add HTML content
		if (!$objNewsletter->sendText)
		{
			// Get the mail template
			$objTemplate = new BackendTemplate((strlen($objNewsletter->template) ? $objNewsletter->template : 'mail_default'));
			$objTemplate->setData($objNewsletter->row());

			$objTemplate->title = $objNewsletter->subject;
			$objTemplate->body = $this->parseSimpleTokens($html, $arrRecipient);
			$objTemplate->charset = $GLOBALS['TL_CONFIG']['characterSet'];
			$objTemplate->css = $css;

			// Parse template
			$objEmail->html = $objTemplate->parse();
			$objEmail->imageDir = TL_ROOT . '/';
		}

		// Deactivate invalid addresses
		try
		{
			$objEmail->sendTo($arrRecipient['email']);
		}
		catch (Swift_RfcComplianceException $e)
		{
			$_SESSION['REJECTED_RECIPIENTS'][] = $arrRecipient['email'];
		}

		// Rejected recipients
		if (count($objEmail->failures))
		{
			$_SESSION['REJECTED_RECIPIENTS'][] = $arrRecipient['email'];
		}
	}


	/**
	 * Return a form to choose a CSV file and import it
	 * @param object
	 * @return string
	 */
	public function importRecipients()
	{
		if ($this->Input->get('key') != 'import')
		{
			return '';
		}

		// Import CSS
		if ($this->Input->post('FORM_SUBMIT') == 'tl_recipients_import')
		{
			if (!$this->Input->post('source') || !is_array($this->Input->post('source')))
			{
				$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['ERR']['all_fields'];
				$this->reload();
			}

			$time = time();
			$intTotal = 0;
			$intInvalid = 0;

			foreach ($this->Input->post('source') as $strCsvFile)
			{
				$objFile = new File($strCsvFile);

				if ($objFile->extension != 'csv')
				{
					$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $objFile->extension);
					continue;
				}

				// Get separator
				switch ($this->Input->post('separator'))
				{
					case 'semicolon':
						$strSeparator = ';';
						break;

					case 'tabulator':
						$strSeparator = '\t';
						break;

					case 'linebreak':
						$strSeparator = '\n';
						break;

					default:
						$strSeparator = ',';
						break;
				}

				$arrRecipients = array();
				$resFile = $objFile->handle;

				while(($arrRow = @fgetcsv($resFile, null, $strSeparator)) !== false)
				{
					$arrRecipients = array_merge($arrRecipients, $arrRow);
				}

				$arrRecipients = array_filter(array_unique($arrRecipients));

				foreach ($arrRecipients as $strRecipient)
				{
					// Skip invalid entries
					if (!$this->isValidEmailAddress($strRecipient))
					{
						$this->log('Recipient address "' . $strRecipient . '" seems to be invalid and has been skipped', 'Newsletter importRecipients()', TL_ERROR);

						++$intInvalid;
						continue;
					}

					// Check whether the e-mail address exists
					$objRecipient = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_newsletter_recipients WHERE pid=? AND email=?")
												   ->execute($this->Input->get('id'), $strRecipient);

					if ($objRecipient->total < 1)
					{
						$this->Database->prepare("INSERT INTO tl_newsletter_recipients SET pid=?, tstamp=$time, email=?, active=1")
									   ->execute($this->Input->get('id'), $strRecipient);

						++$intTotal;
					}
				}
			}

			$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_newsletter_recipients']['confirm'], $intTotal);

			if ($intInvalid > 0)
			{
				$_SESSION['TL_INFO'][] = sprintf($GLOBALS['TL_LANG']['tl_newsletter_recipients']['invalid'], $intInvalid);
			}

			setcookie('BE_PAGE_OFFSET', 0, 0, '/');
			$this->reload();
		}

		$objTree = new FileTree($this->prepareForWidget($GLOBALS['TL_DCA']['tl_newsletter_recipients']['fields']['source'], 'source', null, 'source', 'tl_newsletter_recipients'));

		// Return form
		return '
<div id="tl_buttons">
<a href="'.ampersand(str_replace('&key=import', '', $this->Environment->request)).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_newsletter_recipients']['import'][1].'</h2>
'.$this->getMessages().'
<form action="'.ampersand($this->Environment->request, true).'" id="tl_recipients_import" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_recipients_import">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">

<div class="tl_tbox block">
  <h3><label for="separator">'.$GLOBALS['TL_LANG']['MSC']['separator'][0].'</label></h3>
  <select name="separator" id="separator" class="tl_select" onfocus="Backend.getScrollOffset();">
    <option value="comma">'.$GLOBALS['TL_LANG']['MSC']['comma'].'</option>
    <option value="semicolon">'.$GLOBALS['TL_LANG']['MSC']['semicolon'].'</option>
    <option value="tabulator">'.$GLOBALS['TL_LANG']['MSC']['tabulator'].'</option>
    <option value="linebreak">'.$GLOBALS['TL_LANG']['MSC']['linebreak'].'</option>
  </select>'.(($GLOBALS['TL_LANG']['MSC']['separator'][1] != '') ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['MSC']['separator'][1].'</p>' : '').'
  <h3><label for="source">'.$GLOBALS['TL_LANG']['MSC']['source'][0].'</label> <a href="contao/files.php" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['fileManager']) . '" rel="lightbox[files 765 80%]">' . $this->generateImage('filemanager.gif', $GLOBALS['TL_LANG']['MSC']['fileManager'], 'style="vertical-align:text-bottom;"') . '</a></h3>
'.$objTree->generate().(($GLOBALS['TL_LANG']['MSC']['source'][1] != '') ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['MSC']['source'][1].'</p>' : '').'
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  <input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_newsletter_recipients']['import'][0]).'">
</div>

</div>
</form>';
	}


	/**
	 * Synchronize newsletter subscription of new users
	 * @param object
	 * @param array
	 */
	public function createNewUser($userId, $arrData)
	{
		$arrNewsletters = deserialize($arrData['newsletter'], true);

		// Return if there are no newsletters
		if (!is_array($arrNewsletters))
		{
			return;
		}

		$time = time();

		// Add recipients
		foreach ($arrNewsletters as $intNewsletter)
		{
			$intNewsletter = intval($intNewsletter);

			if ($intNewsletter < 1)
			{
				continue;
			}

			$objRecipient = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_newsletter_recipients WHERE pid=? AND email=?")
										   ->execute($intNewsletter, $arrData['email']);

			if ($objRecipient->total < 1)
			{
				$this->Database->prepare("INSERT INTO tl_newsletter_recipients SET pid=?, tstamp=$time, email=?, addedOn=$time, ip=?")
							   ->execute($intNewsletter, $arrData['email'], $this->Environment->ip);
			}
		}
	}


	/**
	 * Activate newsletter subscription of new users
	 * @param object
	 */
	public function activateAccount($objUser)
	{
		$arrNewsletters = deserialize($objUser->newsletter, true);

		// Return if there are no newsletters
		if (!is_array($arrNewsletters))
		{
			return;
		}

		// Activate e-mail addresses
		foreach ($arrNewsletters as $intNewsletter)
		{
			$intNewsletter = intval($intNewsletter);

			if ($intNewsletter < 1)
			{
				continue;
			}

			$this->Database->prepare("UPDATE tl_newsletter_recipients SET active=1 WHERE pid=? AND email=?")
						   ->execute($intNewsletter, $objUser->email);
		}
	}


	/**
	 * Synchronize newsletter subscription of existing users
	 * @param mixed
	 * @param object
	 * @param object
	 * @return mixed
	 */
	public function synchronize($varValue, $objUser, $objModule=null)
	{
		// Return if there is no user (e.g. upon registration)
		if (is_null($objUser))
		{
			return $varValue;
		}

		$blnIsFrontend = true;

		// If called from the back end, the second argument is a DataContainer object
		if ($objUser instanceof DataContainer)
		{
			$objUser = $this->Database->prepare("SELECT * FROM tl_member WHERE id=?")
									  ->limit(1)
									  ->execute($objUser->id);

			if ($objUser->numRows < 1)
			{
				return $varValue;
			}

			$blnIsFrontend = false;
		}

		// Nothing has changed or e-mail address is empty
		if ($varValue == $objUser->newsletter || $objUser->email == '')
		{
			return $varValue;
		}

		$time = time();
		$varValue = deserialize($varValue, true);

		// Get all channel IDs (thanks to Andreas Schempp)
		if ($blnIsFrontend && $objModule instanceof Module)
		{
			$arrChannel = deserialize($objModule->newsletters, true);
		}
		else
		{
			$arrChannel = $this->Database->query("SELECT id FROM tl_newsletter_channel")->fetchEach('id');
		}

		$arrDelete = array_values(array_diff($arrChannel, $varValue));

		// Delete existing recipients
		if (is_array($arrDelete) && count($arrDelete) > 0)
		{
			$this->Database->prepare("DELETE FROM tl_newsletter_recipients WHERE pid IN(" . implode(',', array_map('intval', $arrDelete)) . ") AND email=?")
						   ->execute($objUser->email);
		}

		// Add recipients
		foreach ($varValue as $intId)
		{
			$intId = intval($intId);

			if ($intId < 1)
			{
				continue;
			}

			$objRecipient = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_newsletter_recipients WHERE pid=? AND email=?")
										   ->execute($intId, $objUser->email);

			if ($objRecipient->total < 1)
			{
				$this->Database->prepare("INSERT INTO tl_newsletter_recipients SET pid=?, tstamp=$time, email=?, active=?, addedOn=?, ip=?")
							   ->execute($intId, $objUser->email, ($objUser->disable ? '' : 1), ($blnIsFrontend ? $time : ''), ($blnIsFrontend ? $this->Environment->ip : ''));
			}
		}

		return serialize($varValue);
	}


	/**
	 * Update a particular member account
	 * @param integer
	 * @param object
	 */
	public function updateAccount()
	{
		$intUser = $this->Input->get('id');

		// Front end call
		if (TL_MODE == 'FE')
		{
			$this->import('FrontendUser', 'User');
			$intUser = $this->User->id;
		}

		// Return if there is no user (e.g. upon registration)
		if (!$intUser)
		{
			return;
		}

		// Edit account
		if (TL_MODE == 'FE' || $this->Input->get('act') == 'edit')
		{
			$objUser = $this->Database->prepare("SELECT email, disable FROM tl_member WHERE id=?")
									  ->limit(1)
									  ->execute($intUser);

			if ($objUser->numRows)
			{
				$strEmail = $this->Input->post('email', true);

				// E-mail address has changed
				if (!empty($_POST) && $strEmail != '' && $strEmail != $objUser->email)
				{
					$this->Database->prepare("UPDATE tl_newsletter_recipients SET email=? WHERE email=?")
								   ->execute($strEmail, $objUser->email);

					$objUser->email = $strEmail;
				}

				$objSubscriptions = $this->Database->prepare("SELECT pid FROM tl_newsletter_recipients WHERE email=?")
												   ->execute($objUser->email);

				if ($objSubscriptions->numRows)
				{
					$strNewsletters = serialize($objSubscriptions->fetchEach('pid'));
				}
				else
				{
					$strNewsletters = '';
				}

				$this->Database->prepare("UPDATE tl_member SET newsletter=? WHERE id=?")
							   ->execute($strNewsletters, $intUser);

				// Update the front end user object
				if (TL_MODE == 'FE')
				{
					$this->User->newsletter = $strNewsletters;
				}

				// Check activation status
				elseif (!empty($_POST) && $this->Input->post('disable') != $objUser->disable)
				{
					$this->Database->prepare("UPDATE tl_newsletter_recipients SET active=? WHERE email=?")
								   ->execute(($this->Input->post('disable') ? '' : 1), $objUser->email);

					$objUser->disable = $this->Input->post('disable');
				}
			}
		}

		// Delete account
		elseif ($this->Input->get('act') == 'delete')
		{
			$objUser = $this->Database->prepare("SELECT email FROM tl_member WHERE id=?")
									  ->limit(1)
									  ->execute($intUser);

			if ($objUser->numRows)
			{
				$objSubscriptions = $this->Database->prepare("DELETE FROM tl_newsletter_recipients WHERE email=?")
												   ->execute($objUser->email);
			}
		}
	}


	/**
	 * Get all editable newsletters and return them as array
	 * @param object
	 * @return array
	 */
	public function getNewsletters($objModule)
	{
		$objNewsletter = $this->Database->execute("SELECT id, title FROM tl_newsletter_channel");

		if ($objNewsletter->numRows < 1)
		{
			return array();
		}

		$arrNewsletters = array();

		// Back end
		if (TL_MODE == 'BE')
		{
			while ($objNewsletter->next())
			{
				$arrNewsletters[$objNewsletter->id] = $objNewsletter->title;
			}

			return $arrNewsletters;
		}

		// Front end
		$newsletters = deserialize($objModule->newsletters, true);

		if (!is_array($newsletters) || count($newsletters) < 1)
		{
			return array();
		}

		while ($objNewsletter->next())
		{
			if (in_array($objNewsletter->id, $newsletters))
			{
				$arrNewsletters[$objNewsletter->id] = $objNewsletter->title;
			}
		}

		return $arrNewsletters;
	}


	/**
	 * Add newsletters to the indexer
	 * @param array
	 * @param integer
	 * @return array
	 */
	public function getSearchablePages($arrPages, $intRoot=0)
	{
		$arrRoot = array();

		if ($intRoot > 0)
		{
			$arrRoot = $this->getChildRecords($intRoot, 'tl_page');
		}

		$time = time();
		$arrProcessed = array();

		// Get all channels
		$objNewsletter = $this->Database->execute("SELECT id, jumpTo FROM tl_newsletter_channel");

		// Walk through each channel
		while ($objNewsletter->next())
		{
			if (is_array($arrRoot) && count($arrRoot) > 0 && !in_array($objNewsletter->jumpTo, $arrRoot))
			{
				continue;
			}

			// Get the URL of the jumpTo page
			if (!isset($arrProcessed[$objNewsletter->jumpTo]))
			{
				$arrProcessed[$objNewsletter->jumpTo] = false;

				// Get target page
				$objParent = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=? AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1 AND noSearch!=1")
											->limit(1)
											->execute($objNewsletter->jumpTo);

				// Determin domain
				if ($objParent->numRows)
				{
					$domain = $this->Environment->base;
					$objParent = $this->getPageDetails($objParent->id);

					if (strlen($objParent->domain))
					{
						$domain = ($this->Environment->ssl ? 'https://' : 'http://') . $objParent->domain . TL_PATH . '/';
					}

					$arrProcessed[$objNewsletter->jumpTo] = $domain . $this->generateFrontendUrl($objParent->row(), '/items/%s');
				}
			}

			// Skip events without target page
			if ($arrProcessed[$objNewsletter->jumpTo] === false)
			{
				continue;
			}

			$strUrl = $arrProcessed[$objNewsletter->jumpTo];

			// Get items
			$objItem = $this->Database->prepare("SELECT * FROM tl_newsletter WHERE pid=? AND sent=1 ORDER BY date DESC")
									  ->execute($objNewsletter->id);

			// Add items to the indexer
			while ($objItem->next())
			{
				$arrPages[] = sprintf($strUrl, ((strlen($objItem->alias) && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objItem->alias : $objItem->id));
			}
		}

		return $arrPages;
	}
}

?>