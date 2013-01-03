<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;


/**
 * A SwiftMailer adapter class
 * 
 * The class functions as an adapter for the Swift mailer framework. It can be
 * used to send e-mails via the PHP mail function or an SMTP server.
 * 
 * Usage:
 * 
 *     $email = new Email();
 *     $email->subject = 'Hello';
 *     $email->text = 'Is it me you are looking for?';
 *     $email->sendTo('lionel@richie.com');
 * 
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
class Email extends \System
{

	/**
	 * Mailer object
	 * @var \Email
	 */
	protected static $objMailer;

	/**
	 * Message object
	 * @var \Swift_Message
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
	 * Log file name
	 * @var string
	 */
	protected $strLogFile = 'email.log';


	/**
	 * Instantiate the object and load the mailer framework
	 */
	public function __construct()
	{
		parent::__construct();
		$this->strCharset = $GLOBALS['TL_CONFIG']['characterSet'];

		// Instantiate mailer
		if (self::$objMailer === null)
		{
			if (!$GLOBALS['TL_CONFIG']['useSMTP'])
			{
				// Mail
				$objTransport = \Swift_MailTransport::newInstance();
			}
			else
			{
				// SMTP
				$objTransport = \Swift_SmtpTransport::newInstance($GLOBALS['TL_CONFIG']['smtpHost'], $GLOBALS['TL_CONFIG']['smtpPort']);

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

			self::$objMailer = \Swift_Mailer::newInstance($objTransport);
		}

		// Instantiate Swift_Message
		$this->objMessage = \Swift_Message::newInstance();
		$this->objMessage->getHeaders()->addTextHeader('X-Mailer', 'Contao Open Source CMS');
	}


	/**
	 * Set an object property
	 * 
	 * Supported keys:
	 * 
	 * * subject:     the e-mail subject
	 * * text:        the text part of the mail
	 * * html:        the HTML part of the mail
	 * * from:        the sender's e-mail address
	 * * fromName:    the sender's name
	 * * priority:    the e-mail priority
	 * * charset:     the e-mail character set
	 * * imageDir:    the base directory to look for internal images
	 * * embedImages: whether to embed images inline
	 * * logFile:     the log file path
	 * 
	 * @param string $strKey   The property name
	 * @param mixed  $varValue The property value
	 * 
	 * @throws \Exception If $strKey is unknown
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'subject':
				$this->strSubject = preg_replace(array('/[\t]+/', '/[\n\r]+/'), array(' ', ''), $varValue);
				break;

			case 'text':
				$this->strText = \String::decodeEntities($varValue);
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
				throw new \Exception(sprintf('Invalid argument "%s"', $strKey));
				break;
		}
	}


	/**
	 * Return an object property
	 * 
	 * Supported keys:
	 * 
	 * * subject:     the e-mail subject
	 * * text:        the text part of the mail
	 * * html:        the HTML part of the mail
	 * * from:        the sender's e-mail address
	 * * fromName:    the sender's name
	 * * priority:    the e-mail priority
	 * * charset:     the e-mail character set
	 * * imageDir:    the base directory to look for internal images
	 * * embedImages: whether to embed images inline
	 * * logFile:     the log file path
	 * * failures:    an array of rejected e-mail addresses
	 * 
	 * @param string $strKey The property name
	 * 
	 * @return mixed The property value
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
		}

		return parent::__get($strKey);
	}


	/**
	 * Return true if there are failures
	 * 
	 * @return boolean True if there are failures
	 */
	public function hasFailures()
	{
		return !empty($this->arrFailures);
	}


	/**
	 * Add a custom text header
	 * 
	 * @param string $strKey   The header name
	 * @param string $strValue The header value
	 */
	public function addHeader($strKey, $strValue)
	{
		$this->objMessage->getHeaders()->addTextHeader($strKey, $strValue);
	}


	/**
	 * Add CC e-mail addresses
	 *
	 * Friendly name portions (e.g. Admin <admin@example.com>) are allowed. The
	 * method takes an unlimited number of recipient addresses.
	 */
	public function sendCc()
	{
		$this->objMessage->setCc($this->compileRecipients(func_get_args()));
	}


	/**
	 * Add BCC e-mail addresses
	 *
	 * Friendly name portions (e.g. Admin <admin@example.com>) are allowed. The
	 * method takes an unlimited number of recipient addresses.
	 */
	public function sendBcc()
	{
		$this->objMessage->setBcc($this->compileRecipients(func_get_args()));
	}


	/**
	 * Add ReplyTo e-mail addresses
	 *
	 * Friendly name portions (e.g. Admin <admin@example.com>) are allowed. The
	 * method takes an unlimited number of recipient addresses.
	 */
	public function replyTo()
	{
		$this->objMessage->setReplyTo($this->compileRecipients(func_get_args()));
	}


	/**
	 * Attach a file
	 * 
	 * @param string $strFile The file path
	 * @param string $strMime The MIME type (defaults to "application/octet-stream")
	 */
	public function attachFile($strFile, $strMime='application/octet-stream')
	{
		$this->objMessage->attach(\Swift_Attachment::fromPath($strFile, $strMime)->setFilename(basename($strFile)));
	}


	/**
	 * Attach a file from a string
	 * 
	 * @param string $strContent  The file content
	 * @param string $strFilename The file name
	 * @param string $strMime     The MIME type (defaults to "application/octet-stream")
	 */
	public function attachFileFromString($strContent, $strFilename, $strMime='application/octet-stream')
	{
		$this->objMessage->attach(\Swift_Attachment::newInstance($strContent, $strFilename, $strMime));
	}


	/**
	 * Send the e-mail
	 *
	 * Friendly name portions (e.g. Admin <admin@example.com>) are allowed. The
	 * method takes an unlimited number of recipient addresses.
	 * 
	 * @return boolean True if the e-mail was sent successfully
	 */
	public function sendTo()
	{
		$arrRecipients = $this->compileRecipients(func_get_args());

		if (empty($arrRecipients))
		{
			return false;
		}

		$this->objMessage->setTo($arrRecipients);
		$this->objMessage->setCharset($this->strCharset);
		$this->objMessage->setPriority($this->intPriority);

		// Default subject
		if ($this->strSubject == '')
		{
			$this->strSubject = 'No subject';
		}

		$this->objMessage->setSubject($this->strSubject);

		// HTML e-mail
		if ($this->strHtml != '')
		{
			// Embed images
			if ($this->blnEmbedImages)
			{
				if ($this->strImageDir == '')
				{
					$this->strImageDir = TL_ROOT . '/';
				}

				$arrCid = array();
				$arrMatches = array();
				$strBase = \Environment::get('base');

				// Thanks to @ofriedrich and @aschempp (see #4562)
				preg_match_all('/<[a-z][a-z0-9]*\b[^>]*((src=|background=|url\()["\']??)(.+\.(jpe?g|png|gif|bmp|tiff?|swf))(["\' ]??(\)??))[^>]*>/Ui', $this->strHtml, $arrMatches);

				// Check for internal images
				if (!empty($arrMatches) && isset($arrMatches[0]))
				{
					for ($i=0; $i<count($arrMatches[0]); $i++)
					{
						$url = $arrMatches[3][$i];

						// Try to remove the base URL
						$src = str_replace($strBase, '', $url);
						$src = rawurldecode($src); // see #3713

						// Embed the image if the URL is now relative
						if (!preg_match('@^https?://@', $src) && file_exists($this->strImageDir . $src))
						{
							if (!isset($arrCid[$src]))
							{
								$arrCid[$src] = $this->objMessage->embed(\Swift_EmbeddedFile::fromPath($this->strImageDir . $src));
							}

							$this->strHtml = str_replace($arrMatches[1][$i] . $arrMatches[3][$i] . $arrMatches[5][$i], $arrMatches[1][$i] . $arrCid[$src] . $arrMatches[5][$i], $this->strHtml);
						}
					}
				}
			}

			$this->objMessage->setBody($this->strHtml, 'text/html');
		}

		// Text content
		if ($this->strText != '')
		{
			if ($this->strHtml != '')
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

		// Set the return path (see #5004)
		$this->objMessage->setReturnPath($this->strSender);

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

		$arrCc = $this->objMessage->getCc();
		$arrBcc = $this->objMessage->getBcc();

		// Add a log entry
		$strMessage = 'An e-mail has been sent to ' . implode(', ', array_keys($this->objMessage->getTo()));

		if (!empty($arrCc))
		{
			$strMessage .= ', CC to ' . implode(', ', array_keys($arrCc));
		}

		if (!empty($arrBcc))
		{
			$strMessage .= ', BCC to ' . implode(', ', array_keys($arrBcc));
		}

		log_message($strMessage, $this->strLogFile);
		return true;
	}


	/**
	 * Extract the e-mail addresses from the func_get_args() arguments
	 * 
	 * @param array $arrRecipients The recipients array
	 * 
	 * @return array An array of e-mail addresses
	 */
	protected function compileRecipients($arrRecipients)
	{
		$arrReturn = array();

		foreach ($arrRecipients as $varRecipients)
		{
			if (!is_array($varRecipients))
			{
				$varRecipients = \String::splitCsv($varRecipients);
			}

			// Support friendly name addresses and internationalized domain names
			foreach ($varRecipients as $v)
			{
				list($strName, $strEmail) = $this->splitFriendlyName($v);

				$strName = trim($strName, ' "');
				$strEmail = \Idna::encodeEmail($strEmail);

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
