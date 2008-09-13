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
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class Email
 *
 * Provide methodes to send e-mails.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Library
 */
class Email
{

	/**
	 * E-mail subject
	 * @var string
	 */
	protected $strSubject;

	/**
	 * Sender e-mail address
	 * @var string
	 */
	protected $strSender;

	/**
	 * Sender name
	 * @var string
	 */
	protected $strSenderName;

	/**
	 * E-mail priority
	 * @var integer
	 */
	protected $intPriority = 3;

	/**
	 * Text part of the e-mail
	 * @var string
	 */
	protected $strText;

	/**
	 * HTML part of the e-mail
	 * @var string
	 */
	protected $strHtml;

	/**
	 * Character set
	 * @var string
	 */
	protected $strCharset;

	/**
	 * Image directory
	 * @var string
	 */
	protected $strImageDir;

	/**
	 * Carbon copy recipients array
	 * @var array
	 */
	protected $arrCc = array();

	/**
	 * Blind carbon copy recipients array
	 * @var array
	 */
	protected $arrBcc = array();

	/**
	 * Reply to addresses
	 * @var array
	 */
	protected $arrReplyTo = array();

	/**
	 * Attachments array
	 * @var array
	 */
	protected $arrAttachments = array();

	/**
	 * String attachments array
	 * @var array
	 */
	protected $arrStringAttachments = array();


	/**
	 * Instantiate object and load phpmailer plugin
	 * @throws Exception
	 */
	public function __construct()
	{
		$strPluginPath = TL_ROOT . '/plugins/phpmailer';

		if (!is_dir($strPluginPath))
		{
			throw new Exception('Plugin phpmailer required');
		}

		include_once($strPluginPath . '/class.phpmailer.php');
		$this->strCharset = $GLOBALS['TL_CONFIG']['characterSet'];
	}


	/**
	 * Set an object property
	 * @param  string
	 * @param  mixed
	 * @throws Exception
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'subject':
				$this->strSubject = preg_replace('/[\t]+/', ' ', $varValue);
				$this->strSubject = preg_replace('/[\n\r]+/', '', $this->strSubject);
				break;

			case 'text':
				$this->strText = strip_tags($varValue);
				break;

			case 'html':
				$this->strHtml = $varValue;
				break;

			case 'from':
				$this->strSender = $varValue;
				break;

			case 'fromName':
				$this->strSenderName = $varValue;
				break;

			case 'priority':
				switch ($strKey)
				{
					case 1:
					case 'low':
						$this->intPriority = 1;
						break;
					case 3:
					case 'normal':
						$this->intPriority = 3;
						break;
					case 5:
					case 'high':
						$this->intPriority = 5;
						break;
				}
				break;

			case 'charset':
				$this->strCharset = $varValue;
				break;

			case 'imageDir':
				$this->strImageDir = $varValue;
				break;

			default:
				throw new Exception(sprintf('Invalid argument "%s"', $strKey));
				break;
		}
	}


	/**
	 * Return an object property
	 * @param string
	 * @return mixed
	 * @throws Exception
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'subject':
				return $this->strSubject;
				break;

			case 'text':
				return $this->strText;
				break;

			case 'html':
				return $this->strHtml;
				break;

			case 'from':
				return $this->strSender;
				break;

			case 'fromName':
				return $this->strSenderName;
				break;

			case 'priority':
				return $this->intPriority;
				break;

			case 'charset':
				return $this->strCharset;
				break;

			case 'imageDir':
				return $this->strImageDir;
				break;

			default:
				throw new Exception(sprintf('Unknown or protected property "%s"', $strKey));
				break;
		}
	}


	/**
	 * Get CC e-mail addresses from an array, string or unlimited number of arguments
	 *
	 * Friendly name portions (e.g. Leo <leo@typolight.org>) are allowed.
	 * @param mixed
	 */
	public function sendCc()
	{
		$this->arrCc = $this->compileRecipients(func_get_args());
	}


	/**
	 * Get BCC e-mail addresses from an array, string or unlimited number of arguments
	 *
	 * Friendly name portions (e.g. Leo <leo@typolight.org>) are allowed.
	 * @param mixed
	 */
	public function sendBcc()
	{
		$this->arrBcc = $this->compileRecipients(func_get_args());
	}


	/**
	 * Get ReplyTo e-mail addresses from an array, string or unlimited number of arguments
	 *
	 * Friendly name portions (e.g. Leo <leo@typolight.org>) are allowed.
	 * @param mixed
	 */
	public function replyTo()
	{
		$this->arrReplyTo = $this->compileRecipients(func_get_args());
	}


	/**
	 * Attach a file
	 * @param string
	 * @param string
	 */
	public function attachFile($strFile, $strMime='application/octet-stream')
	{
		$this->arrAttachments[] = array
		(
			'file' => $strFile,
			'mime' => $strMime
		);
	}


	/**
	 * Attach a file from a string
	 * @param string
	 * @param string
	 * @param string
	 */
	public function attachFileFromString($strContent, $strFilename, $strMime='application/octet-stream')
	{
		$this->arrStringAttachments[] = array
		(
			'mime' => $strMime,
			'file' => $strFilename,
			'content' => $strContent
		);
	}


	/**
	 * Get e-mail addresses from an array, string or unlimited number of arguments and send the e-mail
	 *
	 * Friendly name portions (e.g. Leo <leo@typolight.org>) are allowed.
	 * @param mixed
	 * @return boolean
	 */
	public function sendTo()
	{
		$arrRecipients = $this->compileRecipients(func_get_args());

		if (!count($arrRecipients))
		{
			return false;
		}

		$objMail = new PHPMailer();

		$objMail->PluginDir = TL_ROOT . '/plugins/phpmailer/';
		$objMail->SetLanguage('en', TL_ROOT . '/plugins/phpmailer/language/');

		$objMail->AddCustomHeader('X-Mailer: TYPOlight');
		$objMail->Priority = $this->intPriority;
		$objMail->CharSet = $this->strCharset;

		// HTML e-mail
		if (strlen($this->strHtml))
		{
			$objMail->IsHTML(true);

			$this->findHtmlImages($objMail);
			$objMail->Body = $this->strHtml;

			if (strlen($this->strText))
			{
				$objMail->AltBody = $this->strText;
			}
		}

		// Text e-mail
		else
		{
			$objMail->IsHTML(false);
			$objMail->Body = $this->strText;
		}

		// File attachments
		foreach ($this->arrAttachments as $arrFile)
		{
			$objMail->AddAttachment($arrFile['file'], basename($arrFile['file']), 'base64', $arrFile['mime']);
		}

		// String attachments
		foreach ($this->arrStringAttachments as $arrFile)
		{
			$objMail->AddStringAttachment($arrFile['content'], $arrFile['file'], 'base64', $arrFile['mime']);
		}

		// Carbon copy
		foreach ($this->arrCc as $strRecipient)
		{
			$objMail->AddCC($strRecipient);
		}

		// Blind carbon copy
		foreach ($this->arrBcc as $strRecipient)
		{
			$objMail->AddBCC($strRecipient);
		}

		// Reply to
		foreach ($this->arrReplyTo as $strRecipient)
		{
			$objMail->AddReplyTo($strRecipient);
		}

		// Add the administrator e-mail as default sender
		if (!strlen($this->strSender))
		{
			$this->strSender = $GLOBALS['TL_CONFIG']['adminEmail'];
			$this->strSenderName = '';
		}

		$objMail->From = $this->strSender;
		$objMail->FromName = $this->strSenderName;
		$objMail->Sender = $this->strSender;

		// Add "No subject" as default subject
		if (!strlen($this->strSubject))
		{
			$this->strSubject = 'No subject';
		}

		$objMail->Subject = $this->strSubject;

		// Use SMTP to send mails
		if ($GLOBALS['TL_CONFIG']['useSMTP'])
		{
			$objMail->IsSMTP(true);
			$objMail->SMTPAuth = $GLOBALS['TL_CONFIG']['smtpUser'] ? true : false;

			$objMail->Host = $GLOBALS['TL_CONFIG']['smtpHost'];
			$objMail->Port = $GLOBALS['TL_CONFIG']['smtpPort'];
			$objMail->Username = $GLOBALS['TL_CONFIG']['smtpUser'];
			$objMail->Password = $GLOBALS['TL_CONFIG']['smtpPass'];
		}

		// Add recipients
		foreach ($arrRecipients as $strRecipient)
		{
			$objMail->AddAddress($strRecipient);
		}

		// Add log entry
		if (!$objMail->Send())
		{
			log_message('An error occured while trying to send an e-mail: ' . $objMail->ErrorInfo, 'email.log');
			return false;
		}

		$arrAllRecipients = array_merge($arrRecipients, $this->arrCc, $this->arrBcc);
		log_message(sprintf('An e-mail has been sent to %s', implode(', ', $arrAllRecipients)), 'email.log');

		return true;
	}


	/**
	 * Compile e-mail addresses from an array of (different) arguments
	 * @param array
	 * @return array
	 */
	private function compileRecipients($arrRecipients)
	{
		$arrReturn = array();

		foreach ($arrRecipients as $varRecipients)
		{
			if (!is_array($varRecipients))
			{
				$varRecipients = trimsplit(',', $varRecipients);
			}

			$arrReturn = array_merge($arrReturn, $varRecipients);
		}

		return array_unique($arrReturn);
	}


	/**
	 * Extract images from the HTML source and add them as inline images
	 * @param object
	 */
	private function findHtmlImages(PHPMailer $objMail)
	{
		$arrTypes = array
		(
			'gif'  => 'image/gif',
			'jpg'  => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'jpe'  => 'image/jpeg',
			'bmp'  => 'image/bmp',
			'png'  => 'image/png',
			'tif'  => 'image/tiff',
			'tiff' => 'image/tiff',
			'swf'  => 'application/x-shockwave-flash'
		);

		// Set default image directory
		if (!strlen($this->strImageDir))
		{
			$this->strImageDir = TL_ROOT . '/';
		}

		$arrImages = array();
		$arrMatches = array();

		// Find images
		preg_match_all('/(?:"|\')([^"\']+\.(' . implode('|', array_keys($arrTypes)) . '))(?:"|\')/Ui', $this->strHtml, $arrMatches);

		// Create cid and replace image source
		foreach (array_unique($arrMatches[1]) as $m)
		{
			if (file_exists($this->strImageDir . $m))
			{
				$c = md5($m);

				$arrImages[$m] = array
				(
					'src' => $m,
					'cid' => $c
				);

				$this->strHtml = str_replace('"' . $m . '"', '"cid:' . $c . '"', $this->strHtml);
			}
		}

		// Add images
		if (count($arrImages))
		{
			ksort($arrImages);

			foreach ($arrImages as $img)
			{
				$ext = preg_replace('#^.*\.(\w{3,4})$#e', 'strtolower("$1")', $img['src']);
				$objMail->AddEmbeddedImage($this->strImageDir . $img['src'], $img['cid'], basename($img['src']), 'base64', $arrTypes[$ext]);
            }
        }
    }
}

?>