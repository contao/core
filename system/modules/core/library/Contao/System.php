<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;


/**
 * Abstract library base class
 *
 * The class provides miscellaneous methods that are used all throughout the
 * application. It is the base class of the Contao library which provides the
 * central "import" method to load other library classes.
 *
 * Usage:
 *
 *     class MyClass extends \System
 *     {
 *         public function __construct()
 *         {
 *             $this->import('Database');
 *         }
 *     }
 *
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
abstract class System
{

	/**
	 * Cache
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
	 * Get an object property
	 *
	 * Lazy load the Input and Environment libraries (which are now static) and
	 * only include them as object property if an old module requires it
	 *
	 * @param string $strKey The property name
	 *
	 * @return mixed|null The property value or null
	 */
	public function __get($strKey)
	{
		if (!isset($this->arrObjects[$strKey]))
		{
			if ($strKey == 'Input' || $strKey == 'Environment')
			{
				$this->arrObjects[$strKey] = $strKey::getInstance();
			}
			else
			{
				return null;
			}
		}

		return $this->arrObjects[$strKey];
	}


	/**
	 * Import a library and make it accessible by its name or an optional key
	 *
	 * @param string  $strClass The class name
	 * @param string  $strKey   An optional key to store the object under
	 * @param boolean $blnForce If true, existing objects will be overridden
	 */
	protected function import($strClass, $strKey=null, $blnForce=false)
	{
		$strKey = $strKey ?: $strClass;

		if ($blnForce || !isset($this->arrObjects[$strKey]))
		{
			$this->arrObjects[$strKey] = (in_array('getInstance', get_class_methods($strClass))) ? call_user_func(array($strClass, 'getInstance')) : new $strClass();
		}
	}


	/**
	 * Import a library in non-object context
	 *
	 * @param string  $strClass The class name
	 * @param string  $strKey   An optional key to store the object under
	 * @param boolean $blnForce If true, existing objects will be overridden
	 *
	 * @return object The imported object
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
	 * Add a log entry to the database
	 *
	 * @param string $strText     The log message
	 * @param string $strFunction The function name
	 * @param string $strCategory The category name
	 */
	public static function log($strText, $strFunction, $strCategory)
	{
		$strUa = 'N/A';
		$strIp = '127.0.0.1';

		if (\Environment::get('httpUserAgent'))
		{
			$strUa = \Environment::get('httpUserAgent');
		}
		if (\Environment::get('remoteAddr'))
		{
			$strIp = static::anonymizeIp(\Environment::get('ip'));
		}

		\Database::getInstance()->prepare("INSERT INTO tl_log (tstamp, source, action, username, text, func, ip, browser) VALUES(?, ?, ?, ?, ?, ?, ?, ?)")
							   ->execute(time(), (TL_MODE == 'FE' ? 'FE' : 'BE'), $strCategory, ($GLOBALS['TL_USERNAME'] ? $GLOBALS['TL_USERNAME'] : ''), specialchars($strText), $strFunction, $strIp, $strUa);

		// HOOK: allow to add custom loggers
		if (isset($GLOBALS['TL_HOOKS']['addLogEntry']) && is_array($GLOBALS['TL_HOOKS']['addLogEntry']))
		{
			foreach ($GLOBALS['TL_HOOKS']['addLogEntry'] as $callback)
			{
				static::importStatic($callback[0])->$callback[1]($strText, $strFunction, $strCategory);
			}
		}
	}


	/**
	 * Return the referer URL and optionally encode ampersands
	 *
	 * @param boolean $blnEncodeAmpersands If true, ampersands will be encoded
	 * @param string  $strTable            An optional table name
	 *
	 * @return string The referer URL
	 */
	public static function getReferer($blnEncodeAmpersands=false, $strTable=null)
	{
		$ref = \Input::get('ref');
		$key = \Input::get('popup') ? 'popupReferer' : 'referer';
		$session = \Session::getInstance()->get($key);

		// Unique referer ID
		if ($ref && isset($session[$ref]))
		{
			$session = $session[$ref];
		}
		elseif (TL_MODE == 'BE' && is_array($session))
		{
			$session = end($session);
		}

		// Use a specific referer
		if ($strTable != '' && isset($session[$strTable]) && \Input::get('act') != 'select')
		{
			$session['current'] = $session[$strTable];
		}

		// Determine current or last
		$strUrl = ($session['current'] != \Environment::get('request')) ? $session['current'] : $session['last'];

		// Remove "toggle" and "toggle all" parameters
		$return = preg_replace('/(&(amp;)?|\?)p?tg=[^& ]*/i', '', $strUrl);

		// Fallback to the generic referer in the front end
		if ($return == '' && TL_MODE == 'FE')
		{
			$return = \Environment::get('httpReferer');
		}

		// Fallback to the current URL if there is no referer
		if ($return == '')
		{
			$return = (TL_MODE == 'BE') ? 'contao/main.php' : \Environment::get('url');
		}

		// Do not urldecode here!
		return ampersand($return, $blnEncodeAmpersands);
	}


	/**
	 * Load a set of language files
	 *
	 * @param string  $strName     The table name
	 * @param boolean $strLanguage An optional language code
	 * @param boolean $blnNoCache  If true, the cache will be bypassed
	 *
	 * @throws \Exception In case a language does not exist
	 */
	public static function loadLanguageFile($strName, $strLanguage=null, $blnNoCache=false)
	{
		if ($strLanguage === null)
		{
			$strLanguage = str_replace('-', '_', $GLOBALS['TL_LANGUAGE']);
		}

		// Fall back to English
		if ($strLanguage == '')
		{
			$strLanguage = 'en';
		}

		// Return if the language file has been loaded already
		if (isset($GLOBALS['loadLanguageFile'][$strName][$strLanguage]) && !$blnNoCache)
		{
			return;
		}

		$strCacheKey = $strLanguage;

		// Make sure the language exists
		if (!is_dir(TL_ROOT . '/system/modules/core/languages/' . $strLanguage))
		{
			$strShortLang = substr($strLanguage, 0, 2);

			// Fall back to "de" if "de_DE" does not exist
			if ($strShortLang != $strLanguage)
			{
				if (!is_dir(TL_ROOT . '/system/modules/core/languages/' . $strShortLang))
				{
					throw new \Exception("Language $strLanguage does not exist");
				}
				else
				{
					$strLanguage = $strShortLang;
				}
			}
		}

		$arrCreateLangs = ($strLanguage == 'en') ? array('en') : array('en', $strLanguage);

		// Load the language(s)
		foreach ($arrCreateLangs as $strCreateLang)
		{
			$strCacheFile = 'system/cache/language/' . $strCreateLang . '/' . $strName . '.php';

			// Try to load from cache
			if (!$GLOBALS['TL_CONFIG']['bypassCache'] && file_exists(TL_ROOT . '/' . $strCacheFile))
			{
				include TL_ROOT . '/' . $strCacheFile;
			}
			else
			{
				foreach (\Config::getInstance()->getActiveModules() as $strModule)
				{
					$strFile = 'system/modules/' . $strModule . '/languages/' . $strCreateLang . '/' . $strName;

					if (file_exists(TL_ROOT . '/' . $strFile . '.xlf'))
					{
						eval(static::convertXlfToPhp($strFile . '.xlf', $strCreateLang));
					}
					elseif (file_exists(TL_ROOT . '/' . $strFile . '.php'))
					{
						include TL_ROOT . '/' . $strFile . '.php';
					}
				}
			}
		}

		// HOOK: allow to load custom labels
		if (isset($GLOBALS['TL_HOOKS']['loadLanguageFile']) && is_array($GLOBALS['TL_HOOKS']['loadLanguageFile']))
		{
			foreach ($GLOBALS['TL_HOOKS']['loadLanguageFile'] as $callback)
			{
				static::importStatic($callback[0])->$callback[1]($strName, $strLanguage, $strCacheKey);
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
		$GLOBALS['loadLanguageFile'][$strName][$strCacheKey] = true;
	}


	/**
	 * Return the countries as array
	 *
	 * @return array An array of country names
	 */
	public static function getCountries()
	{
		$return = array();
		$countries = array();
		$arrAux = array();

		static::loadLanguageFile('countries');
		include TL_ROOT . '/system/config/countries.php';

		foreach ($countries as $strKey=>$strName)
		{
			$arrAux[$strKey] = isset($GLOBALS['TL_LANG']['CNT'][$strKey]) ? utf8_romanize($GLOBALS['TL_LANG']['CNT'][$strKey]) : $strName;
		}

		asort($arrAux);

		foreach (array_keys($arrAux) as $strKey)
		{
			$return[$strKey] = isset($GLOBALS['TL_LANG']['CNT'][$strKey]) ? $GLOBALS['TL_LANG']['CNT'][$strKey] : $countries[$strKey];
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getCountries']) && is_array($GLOBALS['TL_HOOKS']['getCountries']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getCountries'] as $callback)
			{
				static::importStatic($callback[0])->$callback[1]($return, $countries);
			}
		}

		return $return;
	}


	/**
	 * Return the available languages as array
	 *
	 * @param boolean $blnInstalledOnly If true, return only installed languages
	 *
	 * @return array An array of languages
	 */
	public static function getLanguages($blnInstalledOnly=false)
	{
		$return = array();
		$languages = array();
		$arrAux = array();
		$langsNative = array();

		static::loadLanguageFile('languages');
		include TL_ROOT . '/system/config/languages.php';

		foreach ($languages as $strKey=>$strName)
		{
			$arrAux[$strKey] = isset($GLOBALS['TL_LANG']['LNG'][$strKey]) ? utf8_romanize($GLOBALS['TL_LANG']['LNG'][$strKey]) : $strName;
		}

		asort($arrAux);
		$arrBackendLanguages = scan(TL_ROOT . '/system/modules/core/languages');

		foreach (array_keys($arrAux) as $strKey)
		{
			if ($blnInstalledOnly && !in_array($strKey, $arrBackendLanguages))
			{
				continue;
			}

			$return[$strKey] = isset($GLOBALS['TL_LANG']['LNG'][$strKey]) ? $GLOBALS['TL_LANG']['LNG'][$strKey] : $languages[$strKey];

			if (isset($langsNative[$strKey]) && $langsNative[$strKey] != $return[$strKey])
			{
				$return[$strKey] .= ' - ' . $langsNative[$strKey];
			}
		}

		return $return;
	}


	/**
	 * Return the timezones as array
	 *
	 * @return array An array of timezones
	 */
	public static function getTimeZones()
	{
		$arrReturn = array();
		$timezones = array();

		require TL_ROOT . '/system/config/timezones.php';

		foreach ($timezones as $strGroup=>$arrTimezones)
		{
			foreach ($arrTimezones as $strTimezone)
			{
				$arrReturn[$strGroup][] = $strTimezone;
			}
		}

		return $arrReturn;
	}


	/**
	 * Urlencode a file path preserving slashes
	 *
	 * @param string $strPath The file path
	 *
	 * @return string The encoded file path
	 */
	public static function urlEncode($strPath)
	{
		return str_replace('%2F', '/', rawurlencode($strPath));
	}


	/**
	 * Set a cookie
	 *
	 * @param string  $strName     The cookie name
	 * @param mixed   $varValue    The cookie value
	 * @param integer $intExpires  The expiration date
	 * @param string  $strPath     An optional path
	 * @param string  $strDomain   An optional domain name
	 * @param boolean $blnSecure   If true, the secure flag will be set
	 * @param boolean $blnHttpOnly If true, the http-only flag will be set
	 */
	public static function setCookie($strName, $varValue, $intExpires, $strPath=null, $strDomain=null, $blnSecure=false, $blnHttpOnly=false)
	{
		if ($strPath == '')
		{
			$strPath = $GLOBALS['TL_CONFIG']['websitePath'] ?: '/'; // see #4390
		}

		$objCookie = new \stdClass();

		$objCookie->strName     = $strName;
		$objCookie->varValue    = $varValue;
		$objCookie->intExpires  = $intExpires;
		$objCookie->strPath     = $strPath;
		$objCookie->strDomain   = $strDomain;
		$objCookie->blnSecure   = $blnSecure;
		$objCookie->blnHttpOnly = $blnHttpOnly;

		// HOOK: allow to add custom logic
		if (isset($GLOBALS['TL_HOOKS']['setCookie']) && is_array($GLOBALS['TL_HOOKS']['setCookie']))
		{
			foreach ($GLOBALS['TL_HOOKS']['setCookie'] as $callback)
			{
				$objCookie = static::importStatic($callback[0])->$callback[1]($objCookie);
			}
		}

		setcookie($objCookie->strName, $objCookie->varValue, $objCookie->intExpires, $objCookie->strPath, $objCookie->strDomain, $objCookie->blnSecure, $objCookie->blnHttpOnly);
	}


	/**
	 * Convert a byte value into a human readable format
	 *
	 * @param integer $intSize     The size in bytes
	 * @param integer $intDecimals The number of decimals to show
	 *
	 * @return string The human readable size
	 */
	public static function getReadableSize($intSize, $intDecimals=1)
	{
		for ($i=0; $intSize>=1024; $i++)
		{
			$intSize /= 1024;
		}

		return static::getFormattedNumber($intSize, $intDecimals) . ' ' . $GLOBALS['TL_LANG']['UNITS'][$i];
	}


	/**
	 * Format a number
	 *
	 * @param mixed   $varNumber   An integer or float number
	 * @param integer $intDecimals The number of decimals to show
	 *
	 * @return mixed The formatted number
	 */
	public static function getFormattedNumber($varNumber, $intDecimals=2)
	{
		return number_format(round($varNumber, $intDecimals), $intDecimals, $GLOBALS['TL_LANG']['MSC']['decimalSeparator'], $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']);
	}


	/**
	 * Anonymize an IP address by overriding the last chunk
	 *
	 * @param string $strIp The IP address
	 *
	 * @return string The encoded IP address
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
			return substr_replace($strIp, ':0000', strrpos($strIp, ':'));
		}
		// IPv4
		else
		{
			return substr_replace($strIp, '.0', strrpos($strIp, '.'));
		}
	}


	/**
	 * Read the contents of a PHP file, stripping the opening and closing PHP tags
	 *
	 * @param string $strName The name of the PHP file
	 *
	 * @return string The PHP code without the PHP tags
	 */
	protected static function readPhpFileWithoutTags($strName)
	{
		$strCode = rtrim(file_get_contents(TL_ROOT . '/' . $strName));

		// Opening tag
		if (strncmp($strCode, '<?php', 5) === 0)
		{
			$strCode = substr($strCode, 5);
		}

		// die() statement
		$strCode = str_replace(array(
			" if (!defined('TL_ROOT')) die('You cannot access this file directly!');",
			" if (!defined('TL_ROOT')) die('You can not access this file directly!');"
		), '', $strCode);

		// Closing tag
		if (substr($strCode, -2) == '?>')
		{
			$strCode = substr($strCode, 0, -2);
		}

		return rtrim($strCode);
	}


	/**
	 * Convert an .xlf file into a PHP language file
	 *
	 * @param string $strName     The name of the .xlf file
	 * @param string $strLanguage The language code
	 *
	 * @return string The PHP code
	 */
	protected static function convertXlfToPhp($strName, $strLanguage)
	{
		// Read the .xlf file
		$xml = new \DOMDocument();
		$xml->preserveWhiteSpace = false;
		$xml->load(TL_ROOT . '/' . $strName);

		$return = "\n// $strName\n";
		$units = $xml->getElementsByTagName('trans-unit');

		// Set up the quotekey function
		$quotekey = function($key)
		{
			if ($key === '0')
			{
				return 0;
			}
			elseif (is_numeric($key))
			{
				return intval($key);
			}
			else
			{
				return "'$key'";
			}
		};

		// Add the labels
		foreach ($units as $unit)
		{
			$node = ($strLanguage == 'en') ? $unit->getElementsByTagName('source') : $unit->getElementsByTagName('target');

			if ($node === null || $node->item(0) === null)
			{
				continue;
			}

			$value = str_replace("\n", '\n', $node->item(0)->nodeValue);

			// Some closing </em> tags oddly have an extra space in
			if (strpos($value, '</ em>') !== false)
			{
				$value = str_replace('</ em>', '</em>', $value);
			}

			// Quote the value
			if (strpos($value, '\n') !== false)
			{
				$value = '"' . str_replace('"', '\\"', $value) . '"';
			}
			else
			{
				$value = "'" . str_replace("'", "\\'", $value) . "'";
			}

			$chunks = explode('.', $unit->getAttribute('id'));

			// Handle keys with dots
			if (preg_match('/tl_layout\.[a-z]+\.css\./', $unit->getAttribute('id')))
			{
				$chunks = array($chunks[0], $chunks[1] . '.' . $chunks[2], $chunks[3]);
			}

			// Create the array entries
			switch (count($chunks))
			{
				case 2:
					$return .= "\$GLOBALS['TL_LANG']['" . $chunks[0] . "'][" . $quotekey($chunks[1]) . "] = " . $value . ";\n";
					break;

				case 3:
					$return .= "\$GLOBALS['TL_LANG']['" . $chunks[0] . "'][" . $quotekey($chunks[1]) . "][" . $quotekey($chunks[2]) . "] = " . $value . ";\n";
					break;

				case 4:
					$return .= "\$GLOBALS['TL_LANG']['" . $chunks[0] . "'][" . $quotekey($chunks[1]) . "][" . $quotekey($chunks[2]) . "][" . $quotekey($chunks[3]) . "] = " . $value . ";\n";
					break;
			}
		}

		return rtrim($return);
	}


	/**
	 * Enable a back end module
	 * @param string
	 * @return boolean
	 */
	public static function enableModule($strName)
	{
		$objFile = new \File('system/modules/' . $strName . '/.skip', true);

		if (!$objFile->exists())
		{
			return false;
		}

		$objFile->delete();
		return true;
	}


	/**
	 * Disable a back end module
	 * @param string
	 * @return boolean
	 */
	public static function disableModule($strName)
	{
		$objFile = new \File('system/modules/' . $strName . '/.skip', true);

		if ($objFile->exists())
		{
			return false;
		}

		$objFile->write('Remove this file to enable the module');
		$objFile->close();

		return true;
	}


	/**
	 * Parse a date format string and translate textual representations
	 *
	 * @param string  $strFormat The date format string
	 * @param integer $intTstamp An optional timestamp
	 *
	 * @return string The textual representation of the date
	 *
	 * @deprecated Use Date::parse() instead
	 */
	public static function parseDate($strFormat, $intTstamp=null)
	{
		return \Date::parse($strFormat, $intTstamp);
	}


	/**
	 * Add a request string to the current URL
	 *
	 * @param string $strRequest The string to be added
	 *
	 * @return string The new URL
	 *
	 * @deprecated Use Controller::addToUrl() instead
	 */
	public static function addToUrl($strRequest)
	{
		return \Controller::addToUrl($strRequest);
	}


	/**
	 * Reload the current page
	 *
	 * @deprecated Use Controller::reload() instead
	 */
	public static function reload()
	{
		\Controller::reload();
	}


	/**
	 * Redirect to another page
	 *
	 * @param string  $strLocation The target URL
	 * @param integer $intStatus   The HTTP status code (defaults to 303)
	 *
	 * @deprecated Use Controller::redirect() instead
	 */
	public static function redirect($strLocation, $intStatus=303)
	{
		\Controller::redirect($strLocation, $intStatus);
	}


	/**
	 * Add an error message
	 *
	 * @param string $strMessage The error message
	 *
	 * @deprecated Use Message::addError() instead
	 */
	protected function addErrorMessage($strMessage)
	{
		\Message::addError($strMessage);
	}


	/**
	 * Add a confirmation message
	 *
	 * @param string $strMessage The confirmation
	 *
	 * @deprecated Use Message::addConfirmation() instead
	 */
	protected function addConfirmationMessage($strMessage)
	{
		\Message::addConfirmation($strMessage);
	}


	/**
	 * Add a new message
	 *
	 * @param string $strMessage The new message
	 *
	 * @deprecated Use Message::addNew() instead
	 */
	protected function addNewMessage($strMessage)
	{
		\Message::addNew($strMessage);
	}


	/**
	 * Add an info message
	 *
	 * @param string $strMessage The info message
	 *
	 * @deprecated Use Message::addInfo() instead
	 */
	protected function addInfoMessage($strMessage)
	{
		\Message::addInfo($strMessage);
	}


	/**
	 * Add an unformatted message
	 *
	 * @param string $strMessage The unformatted message
	 *
	 * @deprecated Use Message::addRaw() instead
	 */
	protected function addRawMessage($strMessage)
	{
		\Message::addRaw($strMessage);
	}


	/**
	 * Add a message
	 *
	 * @param string $strMessage The message
	 * @param string $strType    The message type
	 *
	 * @deprecated Use Message::add() instead
	 */
	protected function addMessage($strMessage, $strType)
	{
		\Message::add($strMessage, $strType);
	}


	/**
	 * Return all messages as HTML
	 *
	 * @param boolean $blnDcLayout If true, the line breaks are different
	 * @param boolean $blnNoWrapper If true, there will be no wrapping DIV
	 *
	 * @return string The messages HTML markup
	 *
	 * @deprecated Use Message::generate() instead
	 */
	protected function getMessages($blnDcLayout=false, $blnNoWrapper=false)
	{
		return \Message::generate($blnDcLayout, $blnNoWrapper);
	}


	/**
	 * Reset the message system
	 *
	 * @deprecated Use Message::reset() instead
	 */
	protected function resetMessages()
	{
		\Message::reset();
	}


	/**
	 * Return all available message types
	 *
	 * @return array An array of message types
	 *
	 * @deprecated Use Message::getTypes() instead
	 */
	protected function getMessageTypes()
	{
		return \Message::getTypes();
	}


	/**
	 * Encode an internationalized domain name
	 *
	 * @param string $strDomain The domain name
	 *
	 * @return string The encoded domain name
	 *
	 * @deprecated Use Idna::encode() instead
	 */
	protected function idnaEncode($strDomain)
	{
		return \Idna::encode($strDomain);
	}


	/**
	 * Decode an internationalized domain name
	 *
	 * @param string $strDomain The domain name
	 *
	 * @return string The decoded domain name
	 *
	 * @deprecated Use Idna::decode() instead
	 */
	protected function idnaDecode($strDomain)
	{
		return \Idna::decode($strDomain);
	}


	/**
	 * Encode the domain in an e-mail address
	 *
	 * @param string $strEmail The e-mail address
	 *
	 * @return string The encoded e-mail address
	 *
	 * @deprecated Use Idna::encodeEmail() instead
	 */
	protected function idnaEncodeEmail($strEmail)
	{
		return \Idna::encodeEmail($strEmail);
	}


	/**
	 * Encode the domain in an URL
	 *
	 * @param string $strUrl The URL
	 *
	 * @return string The encoded URL
	 *
	 * @deprecated Use Idna::encodeUrl() instead
	 */
	protected function idnaEncodeUrl($strUrl)
	{
		return \Idna::encodeUrl($strUrl);
	}


	/**
	 * Validate an e-mail address
	 *
	 * @param string $strEmail The e-mail address
	 *
	 * @return boolean True if it is a valid e-mail address
	 *
	 * @deprecated Use Validator::isEmail() instead
	 */
	protected function isValidEmailAddress($strEmail)
	{
		return \Validator::isEmail($strEmail);
	}


	/**
	 * Split a friendly-name e-address and return name and e-mail as array
	 *
	 * @param string $strEmail A friendly-name e-mail address
	 *
	 * @return array An array with name and e-mail address
	 *
	 * @deprecated Use String::splitFriendlyEmail() instead
	 */
	public static function splitFriendlyName($strEmail)
	{
		return \String::splitFriendlyEmail($strEmail);
	}


	/**
	 * Return the request string without the index.php fragment
	 *
	 * @param boolean $blnAmpersand If true, ampersands will be encoded
	 *
	 * @return string The request string
	 *
	 * @deprecated Use Environment::get('indexFreeRequest') instead
	 */
	public static function getIndexFreeRequest($blnAmpersand=true)
	{
		return ampersand(\Environment::get('indexFreeRequest'), $blnAmpersand);
	}


	/**
	 * Compile a Model class name from a table name (e.g. tl_form_field becomes FormFieldModel)
	 *
	 * @param string $strTable The table name
	 *
	 * @return string The model class name
	 *
	 * @deprecated Use Model::getClassFromTable() instead
	 */
	public static function getModelClassFromTable($strTable)
	{
		return \Model::getClassFromTable($strTable);
	}
}
