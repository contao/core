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
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \Environment, \File, \Idna, \Input, \Message, \Validator, \Exception, \stdClass;


/**
 * Class System
 *
 * Provide default methods that are required in all models and controllers.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
abstract class System
{

	/**
	 * Cache object
	 * @var Cache
	 */
	protected $Cache;

	/**
	 * Config object
	 * @var Cache
	 */
	protected $Config;

	/**
	 * Database object
	 * @var Database
	 */
	protected $Database;

	/**
	 * Encryption object
	 * @var Encryption
	 */
	protected $Encryption;

	/**
	 * Files object
	 * @var Files
	 */
	protected $Files;

	/**
	 * Search object
	 * @var Search
	 */
	protected $Search;

	/**
	 * Session object
	 * @var Search
	 */
	protected $Session;

	/**
	 * String object
	 * @var String
	 */
	protected $String;

	/**
	 * Template object
	 * @var Template
	 */
	protected $Template;

	/**
	 * User object
	 * @var User
	 */
	protected $User;

	/**
	 * Automator object
	 * @var Automator
	 */
	protected $Automator;

	/**
	 * Data container object
	 * @var DataContainer
	 */
	protected $DataContainer;

	/**
	 * Messages object
	 * @var Messages
	 */
	protected $Messages;

	/**
	 * Cookie hook object
	 * @var Messages
	 */
	protected $objCookie;

	/**
	 * Cache array
	 * @var array
	 */
	protected $arrCache = array(); // Backwards compatibility

	/**
	 * Default libraries
	 * @var array
	 */
	protected $arrObjects = array();

	/**
	 * Static objects
	 * @var array
	 */
	protected static $arrStaticObjects = array();


	/**
	 * Import the Config and Session instances
	 */
	protected function __construct()
	{
		$this->import('Config');
		$this->import('Session');
	}


	/**
	 * Lazy load the Input and Environment libraries (which are now static) and
	 * only include them as object property if an old module requires it
	 * @param string
	 * @return mixed|null
	 */
	public function __get($strKey)
	{
		if ($strKey == 'Input' || $strKey == 'Environment')
		{
			if (!isset($this->arrObjects[$strKey]))
			{
				$this->arrObjects[$strKey] = $strKey::getInstance();
			}

			return $this->arrObjects[$strKey];
		}

		return null;
	}


	/**
	 * Import a library and make it accessible by its name or an optional key
	 * @param string
	 * @param string
	 * @param boolean
	 */
	protected function import($strClass, $strKey=null, $blnForce=false)
	{
		$strKey = $strKey ?: $strClass;

		if (!$blnForce && is_object($this->$strKey))
		{
			return;
		}

		$this->$strKey = (in_array('getInstance', get_class_methods($strClass))) ? call_user_func(array($strClass, 'getInstance')) : new $strClass();
	}


	/**
	 * Instantiate a library in non-object context
	 * @param string
	 * @param string
	 * @param boolean
	 * @return object
	 */
	public static function importStatic($strClass, $strKey=null, $blnForce=false)
	{
		$strKey = $strKey ?: $strClass;

		if ($blnForce || !isset(static::$arrStaticObjects[$strKey]))
		{
			static::$arrStaticObjects[$strKey] = (in_array('getInstance', get_class_methods($strClass))) ? call_user_func(array($strClass, 'getInstance')) : new $strClass();
		}

		return static::$arrStaticObjects[$strKey];
	}


	/**
	 * Add a log entry
	 * @param string
	 * @param string
	 * @param string
	 */
	public static function log($strText, $strFunction, $strAction)
	{
		$strUa = 'N/A';
		$strIp = '127.0.0.1';

		if (Environment::get('httpUserAgent'))
		{
			$strUa = Environment::get('httpUserAgent');
		}
		if (Environment::get('remoteAddr'))
		{
			$strIp = static::anonymizeIp(Environment::get('ip'));
		}

		Database::getInstance()->prepare("INSERT INTO tl_log (tstamp, source, action, username, text, func, ip, browser) VALUES(?, ?, ?, ?, ?, ?, ?, ?)")
							   ->execute(time(), (TL_MODE == 'FE' ? 'FE' : 'BE'), $strAction, ($GLOBALS['TL_USERNAME'] ? $GLOBALS['TL_USERNAME'] : ''), specialchars($strText), $strFunction, $strIp, $strUa);

		// HOOK: allow to add custom loggers
		if (isset($GLOBALS['TL_HOOKS']['addLogEntry']) && is_array($GLOBALS['TL_HOOKS']['addLogEntry']))
		{
			foreach ($GLOBALS['TL_HOOKS']['addLogEntry'] as $callback)
			{
				static::importStatic($callback[0])->$callback[1]($strText, $strFunction, $strAction);
			}
		}
	}


	/**
	 * Add a request string to the current URI string
	 * @param string
	 * @return string
	 */
	public static function addToUrl($strRequest)
	{
		$strRequest = preg_replace('/^&(amp;)?/i', '', $strRequest);
		$queries = preg_split('/&(amp;)?/i', Environment::get('queryString'));

		// Overwrite existing parameters
		foreach ($queries as $k=>$v)
		{
			$explode = explode('=', $v);

			if (preg_match('/(^|&(amp;)?)' . preg_quote($explode[0], '/') . '=/i', $strRequest))
			{
				unset($queries[$k]);
			}
		}

		$href = '?';

		if (!empty($queries))
		{
			$href .= implode('&amp;', $queries) . '&amp;';
		}

		return Environment::get('script') . $href . str_replace(' ', '%20', $strRequest);
	}


	/**
	 * Reload the current page
	 */
	public static function reload()
	{
		$strLocation = Environment::get('url') . Environment::get('requestUri');

		// Ajax request
		if (Environment::get('isAjaxRequest'))
		{
			echo $strLocation;
			exit;
		}

		if (headers_sent())
		{
			exit;
		}

		header('Location: ' . $strLocation);
		exit;
	}


	/**
	 * Redirect to another page
	 * @param string
	 * @param integer
	 */
	public static function redirect($strLocation, $intStatus=303)
	{
		$strLocation = str_replace('&amp;', '&', $strLocation);

		// Ajax request
		if (Environment::get('isAjaxRequest'))
		{
			echo $strLocation;
			exit;
		}

		if (headers_sent())
		{
			exit;
		}

		// Header
		switch ($intStatus)
		{
			case 301:
				header('HTTP/1.1 301 Moved Permanently');
				break;

			case 302:
				header('HTTP/1.1 302 Found');
				break;

			case 303:
				header('HTTP/1.1 303 See Other');
				break;
		}

		// Check the target address
		if (preg_match('@^https?://@i', $strLocation))
		{
			header('Location: ' . $strLocation);
		}
		else
		{
			header('Location: ' . Environment::get('base') . $strLocation);
		}

		exit;
	}


	/**
	 * Return the current referer URL and optionally encode ampersands
	 * @param boolean
	 * @param string
	 * @return string
	 */
	public static function getReferer($blnEncodeAmpersands=false, $strTable=null)
	{
		$key = (Environment::get('script') == 'contao/files.php') ? 'fileReferer' : 'referer';
		$session = Session::getInstance()->get($key);

		// Use a specific referer
		if ($strTable != '' && isset($session[$strTable]) && Input::get('act') != 'select')
		{
			$session['current'] = $session[$strTable];
		}

		// Get the default referer
		$return = preg_replace('/(&(amp;)?|\?)tg=[^& ]*/i', '', (($session['current'] != Environment::get('requestUri')) ? $session['current'] : $session['last']));
		$return = preg_replace('/^'.preg_quote(TL_PATH, '/').'\//i', '', $return);

		// Fallback to the generic referer in the front end
		if ($return == '' && TL_MODE == 'FE')
		{
			$return = Environment::get('httpReferer');
		}

		// Fallback to the current URL if there is no referer
		if ($return == '')
		{
			$return = (TL_MODE == 'BE') ? 'contao/main.php' : Environment::get('url');
		}

		// Do not urldecode here!
		return ampersand($return, $blnEncodeAmpersands);
	}


	/**
	 * Return the request string or an empty string if the request string
	 * is "index.php" and optionally encode ampersands
	 * @param boolean
	 * @return string
	 */
	public static function getIndexFreeRequest($blnAmpersand=true)
	{
		$strRequest = Environment::get('request');

		if ($strRequest == 'index.php')
		{
			return '';
		}

		return ampersand($strRequest, $blnAmpersand);
	}


	/**
	 * Load a set of language files
	 * @param string
	 * @param boolean
	 * @param boolean
	 */
	public static function loadLanguageFile($strName, $strLanguage=false, $blnNoCache=false)
	{
		if (!$strLanguage)
		{
			$strLanguage = $GLOBALS['TL_LANGUAGE'];
		}

		// Return if the data has been loaded already
		if (isset($GLOBALS['loadLanguageFile'][$strName][$strLanguage]) && !$blnNoCache)
		{
			return;
		}

		$strCacheFallback = TL_ROOT . '/system/cache/language/en/' . $strName . '.php';
		$strCacheFile = TL_ROOT . '/system/cache/language/' . $strLanguage . '/' . $strName . '.php';

		if (!$GLOBALS['TL_CONFIG']['bypassCache'] && file_exists($strCacheFile))
		{
			include $strCacheFallback;
			include $strCacheFile;
		}
		else
		{
			// Generate the cache files
			$objCacheFallback = new File('system/cache/language/en/' . $strName . '.php');
			$objCacheFallback->write('<?php' . "\n");

			$objCacheFile = new File('system/cache/language/' . $strLanguage . '/' . $strName . '.php');
			$objCacheFile->write('<?php' . "\n");

			// Parse all active modules
			foreach (Config::getInstance()->getActiveModules() as $strModule)
			{
				$strFallback = TL_ROOT . '/system/modules/' . $strModule . '/languages/en/' . $strName . '.php';

				if (file_exists($strFallback))
				{
					$objCacheFallback->append(file_get_contents($strFallback, null, null, 6));
					include $strFallback;
				}

				if ($strLanguage == 'en')
				{
					continue;
				}

				$strFile = TL_ROOT . '/system/modules/' . $strModule . '/languages/' . $strLanguage . '/' . $strName . '.php';

				if (file_exists($strFile))
				{
					$objCacheFile->append(file_get_contents($strFile, null, null, 6));
					include $strFile;
				}
			}

			$objCacheFallback->close();
			$objCacheFile->close();
		}

		// HOOK: allow to load custom labels
		if (isset($GLOBALS['TL_HOOKS']['loadLanguageFile']) && is_array($GLOBALS['TL_HOOKS']['loadLanguageFile']))
		{
			foreach ($GLOBALS['TL_HOOKS']['loadLanguageFile'] as $callback)
			{
				static::importStatic($callback[0])->$callback[1]($strName, $strLanguage);
			}
		}

		// Handle single quotes in the deleteConfirm message
		if ($strName == 'default')
		{
			$GLOBALS['TL_LANG']['MSC']['deleteConfirm'] = str_replace("'", "\\'", $GLOBALS['TL_LANG']['MSC']['deleteConfirm']);
		}

		// Local configuration file
		if (file_exists(TL_ROOT . '/system/config/langconfig.php'))
		{
			include TL_ROOT . '/system/config/langconfig.php';
		}

		// Use a global cache variable to support nested calls
		$GLOBALS['loadLanguageFile'][$strName][$strLanguage] = true;
	}


	/**
	 * Parse a date format string and translate textual representations
	 * @param string
	 * @param integer
	 * @return string
	 */
	public static function parseDate($strFormat, $intTstamp=null)
	{
		$strModified = str_replace
		(
			array('l', 'D', 'F', 'M'),
			array('w::1', 'w::2', 'n::3', 'n::4'),
			$strFormat
		);

		if ($intTstamp === null)
		{
			$strDate = date($strModified);
		}
		elseif (!is_numeric($intTstamp))
		{
			return '';
		}
		elseif ($intTstamp == 0)
		{
			return '-'; // see #4249
		}
		else
		{
			$strDate = date($strModified, $intTstamp);
		}

		if (strpos($strDate, '::') === false)
		{
			return $strDate;
		}

		if (!$GLOBALS['TL_LANG']['MSC']['dayShortLength'])
		{
			$GLOBALS['TL_LANG']['MSC']['dayShortLength'] = 3;
		}

		if (!$GLOBALS['TL_LANG']['MSC']['monthShortLength'])
		{
			$GLOBALS['TL_LANG']['MSC']['monthShortLength'] = 3;
		}

		$strReturn = '';
		$chunks = preg_split("/([0-9]{1,2}::[1-4])/", $strDate, -1, PREG_SPLIT_DELIM_CAPTURE);

		foreach ($chunks as $chunk)
		{
			list($index, $flag) = explode('::', $chunk);

			switch ($flag)
			{
				case 1:
					$strReturn .= $GLOBALS['TL_LANG']['DAYS'][$index];
					break;

				case 2:
					$strReturn .= $GLOBALS['TL_LANG']['DAYS_SHORT'][$index];
					break;

				case 3:
					$strReturn .= $GLOBALS['TL_LANG']['MONTHS'][($index - 1)];
					break;

				case 4:
					$strReturn .= $GLOBALS['TL_LANG']['MONTHS_SHORT'][($index - 1)];
					break;

				default:
					$strReturn .= $chunk;
					break;
			}
		}

		return $strReturn;
	}


	/**
	 * Add an error message
	 * @param string
	 * @deprecated Use Message::addError() instead
	 */
	protected function addErrorMessage($strMessage)
	{
		Message::addError($strMessage);
	}


	/**
	 * Add a confirmation message
	 * @param string
	 * @deprecated Use Message::addConfirmation() instead
	 */
	protected function addConfirmationMessage($strMessage)
	{
		Message::addConfirmation($strMessage);
	}


	/**
	 * Add a new message
	 * @param string
	 * @deprecated Use Message::addNew() instead
	 */
	protected function addNewMessage($strMessage)
	{
		Message::addNew($strMessage);
	}


	/**
	 * Add an info message
	 * @param string
	 * @deprecated Use Message::addInfo() instead
	 */
	protected function addInfoMessage($strMessage)
	{
		Message::addInfo($strMessage);
	}


	/**
	 * Add a raw message
	 * @param string
	 * @deprecated Use Message::addRaw() instead
	 */
	protected function addRawMessage($strMessage)
	{
		Message::addRaw($strMessage);
	}


	/**
	 * Add a message
	 * @param string
	 * @param string
	 * @deprecated Use Message::add() instead
	 */
	protected function addMessage($strMessage, $strType)
	{
		Message::add($strMessage, $strType);
	}


	/**
	 * Return all messages as HTML
	 * @param boolean
	 * @param boolean
	 * @return string
	 * @deprecated Use Message::generate() instead
	 */
	protected function getMessages($blnDcLayout=false, $blnNoWrapper=false)
	{
		return Message::generate($blnDcLayout, $blnNoWrapper);
	}


	/**
	 * Reset the message system
	 * @deprecated Use Message::reset() instead
	 */
	protected function resetMessages()
	{
		Message::reset();
	}


	/**
	 * Return all available message types
	 * @return array
	 * @deprecated Use Message::getTypes() instead
	 */
	protected function getMessageTypes()
	{
		return Message::getTypes();
	}


	/**
	 * Urlencode an image path preserving slashes
	 * @param string
	 * @return string
	 */
	public static function urlEncode($strPath)
	{
		return str_replace('%2F', '/', rawurlencode($strPath));
	}


	/**
	 * Set a cookie
	 * @param string
	 * @param mixed
	 * @param integer
	 * @param string
	 * @param string
	 * @param boolean
	 */
	public static function setCookie($strName, $varValue, $intExpires, $strPath=null, $strDomain=null, $blnSecure=false)
	{
		if ($strPath === null)
		{
			$strPath = $GLOBALS['TL_CONFIG']['websitePath'];
		}

		$objCookie = new stdClass();

		$objCookie->strName    = $strName;
		$objCookie->varValue   = $varValue;
		$objCookie->intExpires = $intExpires;
		$objCookie->strPath    = $strPath;
		$objCookie->strDomain  = $strDomain;
		$objCookie->blnSecure  = $blnSecure;

		// HOOK: allow to add custom logic
		if (isset($GLOBALS['TL_HOOKS']['setCookie']) && is_array($GLOBALS['TL_HOOKS']['setCookie']))
		{
			foreach ($GLOBALS['TL_HOOKS']['setCookie'] as $callback)
			{
				$objCookie = static::importStatic($callback[0])->$callback[1]($objCookie);
			}
		}

		setcookie($objCookie->strName, $objCookie->varValue, $objCookie->intExpires, $objCookie->strPath, $objCookie->strDomain, $objCookie->blnSecure);
	}


	/**
	 * Split a friendly name address and return name and e-mail as array
	 * @param string
	 * @return array
	 */
	public static function splitFriendlyName($strEmail)
	{
		if (strpos($strEmail, '<') !== false)
		{
			return array_map('trim', explode(' <', str_replace('>', '', $strEmail)));
		}
		elseif (strpos($strEmail, '[') !== false)
		{
			return array_map('trim', explode(' [', str_replace(']', '', $strEmail)));
		}
		else
		{
			return array('', $strEmail);
		}
	}


	/**
	 * Encode an internationalized domain name
	 * @param string
	 * @return string
	 * @deprecated Use Idna::encode() instead
	 */
	protected function idnaEncode($strDomain)
	{
		return Idna::encode($strDomain);
	}


	/**
	 * Decode an internationalized domain name
	 * @param string
	 * @return string
	 * @deprecated Use Idna::decode() instead
	 */
	protected function idnaDecode($strDomain)
	{
		return Idna::decode($strDomain);
	}


	/**
	 * Encode an e-mail address
	 * @param string
	 * @return string
	 * @deprecated Use Idna::encodeEmail() instead
	 */
	protected function idnaEncodeEmail($strEmail)
	{
		return Idna::encodeEmail($strEmail);
	}


	/**
	 * Encode an URL
	 * @param string
	 * @return string
	 * @deprecated Use Idna::encodeUrl() instead
	 */
	protected function idnaEncodeUrl($strUrl)
	{
		return Idna::encodeUrl($strUrl);
	}


	/**
	 * Validate an e-mail address
	 * @param string
	 * @return boolean
	 * @deprecated Use Validator::isEmail() instead
	 */
	protected function isValidEmailAddress($strEmail)
	{
		return Validator::isEmail($strEmail);
	}


	/**
	 * Convert a filesize into a human readable format
	 * @param integer
	 * @param integer
	 * @return string
	 */
	public static function getReadableSize($intSize, $intDecimals=1)
	{
		for ($i=0; $intSize>=1000; $i++)
		{
			$intSize /= 1000;
		}

		return static::getFormattedNumber($intSize, $intDecimals) . ' ' . $GLOBALS['TL_LANG']['UNITS'][$i];
	}


	/**
	 * Format a number
	 * @param mixed
	 * @param integer
	 * @return mixed
	 */
	public static function getFormattedNumber($varNumber, $intDecimals=2)
	{
		return number_format(round($varNumber, $intDecimals), $intDecimals, $GLOBALS['TL_LANG']['MSC']['decimalSeparator'], $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']);
	}


	/**
	 * Anonymize an IP address by overriding the last chunk
	 * @param string
	 * @return string
	 */
	public static function anonymizeIp($strIp)
	{
		// The feature has been disabled
		if (!$GLOBALS['TL_CONFIG']['privacyAnonymizeIp'])
		{
			return $strIp;
		}

		// Localhost
		if ($strIp == '127.0.0.1' || $strIp == '::1')
		{
			return $strIp;
		}

		// IPv6
		if (strpos($strIp, ':') !== false)
		{
			return str_replace(strrchr($strIp, ':'), ':0000', $strIp);
		}
		// IPv4
		else
		{
			return str_replace(strrchr($strIp, '.'), '.0', $strIp);
		}
	}


	/**
	 * Compile a Model class name from a table name (e.g. tl_form_field becomes FormFieldModel)
	 * @param string
	 * @return string
	 */
	public static function getModelClassFromTable($strTable)
	{
		$arrChunks = explode('_', $strTable);

		if ($arrChunks[0] == 'tl')
		{
			array_shift($arrChunks);
		}

		return implode('', array_map('ucfirst', $arrChunks)) . 'Model';
	}
}
