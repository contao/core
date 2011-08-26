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
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Include SwiftMailer classes
 */
require_once(TL_ROOT . '/plugins/swiftmailer/classes/Swift.php');
require_once(TL_ROOT . '/plugins/swiftmailer/swift_init.php');


/**
 * Class Email
 *
 * Provide methodes to send e-mails.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Email extends System
{

	/**
	 * Mailer object
	 * @var object
	 */
	protected static $objMailer;

	/**
	 * Message object
	 * @var object
	 */
	protected $objMessage;

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
	 * E-mail subject
	 * @var string
	 */
	protected $strSubject;

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
	 * Embed images
	 * @var boolean
	 */
	protected $blnEmbedImages = true;

	/**
	 * Invalid addresses
	 * @var array
	 */
	protected $arrFailures = array();

	/**
	 * Log file
	 * @var string
	 */
	protected $strLogFile = 'email.log';


	/**
	 * Instantiate object and load Swift plugin
	 * @throws Exception
	 */
	public function __construct()
	{
		parent::__construct();

		$this->import('String');
		$this->strCharset = $GLOBALS['TL_CONFIG']['characterSet'];

		// Instantiate mailer
		if (!is_object(self::$objMailer))
		{
			if (!$GLOBALS['TL_CONFIG']['useSMTP'])
			{
				// Mail
				$objTransport = Swift_MailTransport::newInstance();
			}
			else
			{
				// SMTP
				$objTransport = Swift_SmtpTransport::newInstance($GLOBALS['TL_CONFIG']['smtpHost'], $GLOBALS['TL_CONFIG']['smtpPort']);

				// Encryption
				if ($GLOBALS['TL_CONFIG']['smtpEnc'] == 'ssl' || $GLOBALS['TL_CONFIG']['smtpEnc'] == 'tls')
				{
					$objTransport->setEncryption($GLOBALS['TL_CONFIG']['smtpEnc']);
				}

				// Authentication
				if ($GLOBALS['TL_CONFIG']['smtpUser'] != '')
				{
					$objTransport->setUsername($GLOBALS['TL_CONFIG']['smtpUser'])->setPassword($GLOBALS['TL_CONFIG']['smtpPass']);
				}
			}

			self::$objMailer = Swift_Mailer::newInstance($objTransport);
		}

		// Instantiate Swift_Message
		$this->objMessage = Swift_Message::newInstance();
		$this->objMessage->getHeaders()->addTextHeader('X-Mailer', 'Contao Open Source CMS');
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
				$this->strSubject = preg_replace(array('/[\t]+/', '/[\n\r]+/'), array(' ', ''), $varValue);
				break;

			case 'text':
				$this->strText = $varValue;
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
				switch ($varValue)
				{
					case 1:
					case 'highest':
						$this->intPriority = 1;
						break;
					case 2:
					case 'high':
						$this->intPriority = 2;
						break;
					case 3:
					case 'normal':
						$this->intPriority = 3;
						break;
					case 4:
					case 'low':
						$this->intPriority = 4;
						break;
					case 5:
					case 'lowest':
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

			case 'embedImages':
				$this->blnEmbedImages = $varValue;
				break;

			case 'logFile':
				$this->strLogFile = $varValue;
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

			case 'embedImages':
				return $this->blnEmbedImages;
				break;

			case 'logFile':
				return $this->strLogFile;
				break;

			case 'failures':
				return $this->arrFailures;
				break;

			default:
				return null;
				break;
		}
	}


	/**
	 * Add a custom text header
	 * @param string
	 * @param string
	 */
	public function addHeader($strKey, $strValue)
	{
		$this->objMessage->getHeaders()->addTextHeader($strKey, $strValue);
	}


	/**
	 * Get CC e-mail addresses from an array, string or unlimited number of arguments
	 *
	 * Friendly name portions (e.g. Leo <leo@contao.org>) are allowed.
	 * @param mixed
	 */
	public function sendCc()
	{
		$this->objMessage->setCc($this->compileRecipients(func_get_args()));
	}


	/**
	 * Get BCC e-mail addresses from an array, string or unlimited number of arguments
	 *
	 * Friendly name portions (e.g. Leo <leo@contao.org>) are allowed.
	 * @param mixed
	 */
	public function sendBcc()
	{
		$this->objMessage->setBcc($this->compileRecipients(func_get_args()));
	}


	/**
	 * Get ReplyTo e-mail addresses from an array, string or unlimited number of arguments
	 *
	 * Friendly name portions (e.g. Leo <leo@contao.org>) are allowed.
	 * @param mixed
	 */
	public function replyTo()
	{
		$this->objMessage->setReplyTo($this->compileRecipients(func_get_args()));
	}


	/**
	 * Attach a file
	 * @param string
	 * @param string
	 */
	public function attachFile($strFile, $strMime='application/octet-stream')
	{
		$this->objMessage->attach(Swift_Attachment::fromPath($strFile, $strMime)->setFilename(basename($strFile)));
	}


	/**
	 * Attach a file from a string
	 * @param string
	 * @param string
	 * @param string
	 */
	public function attachFileFromString($strContent, $strFilename, $strMime='application/octet-stream')
	{
		$this->objMessage->attach(Swift_Attachment::newInstance($strContent, $strFilename, $strMime));
	}


	/**
	 * Get e-mail addresses from an array, string or unlimited number of arguments and send the e-mail
	 *
	 * Friendly name portions (e.g. Leo <leo@contao.org>) are allowed.
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

		$this->objMessage->setTo($arrRecipients);
		$this->objMessage->setCharset($this->strCharset);
		$this->objMessage->setPriority($this->intPriority);

		// Default subject
		if (empty($this->strSubject))
		{
			$this->strSubject = 'No subject';
		}

		$this->objMessage->setSubject($this->strSubject);

		// HTML e-mail
		if (!empty($this->strHtml))
		{
			// Embed images
			if ($this->blnEmbedImages)
			{
				if (!strlen($this->strImageDir))
				{
					$this->strImageDir = TL_ROOT . '/';
				}

				$arrMatches = array();
				preg_match_all('/src="([^"]+\.(jpe?g|png|gif|bmp|tiff?|swf))"/Ui', $this->strHtml, $arrMatches);
				$strBase = Environment::getInstance()->base;

				// Check for internal images
				foreach (array_unique($arrMatches[1]) as $url)
				{
					// Try to remove the base URL
					$src = str_replace($strBase, '', $url);

					// Embed the image if the URL is now relative
					if (!preg_match('@^https?://@', $src) && file_exists($this->strImageDir . $src))
					{
						$cid = $this->objMessage->embed(Swift_EmbeddedFile::fromPath($this->strImageDir . $src));
						$this->strHtml = str_replace('src="' . $url . '"', 'src="' . $cid . '"', $this->strHtml);
					}
				}
			}

			$this->objMessage->setBody($this->strHtml, 'text/html');
		}

		// Text content
		if (!empty($this->strText))
		{
			if (!empty($this->strHtml))
			{
				$this->objMessage->addPart($this->strText, 'text/plain');
			}
			else
			{
				$this->objMessage->setBody($this->strText, 'text/plain');
			}
		}

		// Add the administrator e-mail as default sender
		if ($this->strSender == '')
		{
			list($this->strSenderName, $this->strSender) = $this->splitFriendlyName($GLOBALS['TL_CONFIG']['adminEmail']);
		}

		// Sender
		if ($this->strSenderName != '')
		{
			$this->objMessage->setFrom(array($this->strSender=>$this->strSenderName));
		}
		else
		{
			$this->objMessage->setFrom($this->strSender);
		}

		// Send e-mail
		$intSent = self::$objMailer->send($this->objMessage, $this->arrFailures);

		// Log failures
		if (!empty($this->arrFailures))
		{
			log_message('E-mail address rejected: ' . implode(', ', $this->arrFailures), $this->strLogFile);
		}

		// Return if no e-mails have been sent
		if ($intSent < 1)
		{
			return false;
		}

		// Add log entry
		$strMessage = 'An e-mail has been sent to ' . implode(', ', array_keys($this->objMessage->getTo()));

		if (count($this->objMessage->getCc()) > 0)
		{
			$strMessage .= ', CC to ' . implode(', ', array_keys($this->objMessage->getCc()));
		}

		if (count($this->objMessage->getBcc()) > 0)
		{
			$strMessage .= ', BCC to ' . implode(', ', array_keys($this->objMessage->getBcc()));
		}

		log_message($strMessage, $this->strLogFile);
		return true;
	}


	/**
	 * Compile e-mail addresses from an array of (different) arguments
	 * @param array
	 * @return array
	 */
	protected function compileRecipients($arrRecipients)
	{
		$arrReturn = array();

		foreach ($arrRecipients as $varRecipients)
		{
			if (!is_array($varRecipients))
			{
				$varRecipients = $this->String->splitCsv($varRecipients);
			}

			// Support friendly name addresses and internationalized domain names
			foreach ($varRecipients as $v)
			{
				list($strName, $strEmail) = $this->splitFriendlyName($v);

				$strName = trim($strName, ' "');
				$strEmail = $this->idnaEncodeEmail($strEmail);

				if ($strName != '')
				{
					$arrReturn[$strEmail] = $strName;
				}
				else
				{
					$arrReturn[] = $strEmail;
				}
			}
		}

		return $arrReturn;
	}
}

?>