<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Safely read the user input
 *
 * The class functions as an adapter for the global input arrays ($_GET, $_POST,
 * $_COOKIE) and safely returns their values. To prevent XSS vulnerabilities,
 * you should always use the class when reading user input.
 *
 * Usage:
 *
 *     if (Input::get('action') == 'register')
 *     {
 *         $username = Input::post('username');
 *         $password = Input::post('password');
 *     }
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Input
{

	/**
	 * Object instance (Singleton)
	 * @var \Input
	 */
	protected static $objInstance;

	/**
	 * Cache
	 * @var array
	 */
	protected static $arrCache = array();

	/**
	 * Unused $_GET parameters
	 * @var array
	 */
	protected static $arrUnusedGet = array();

	/**
	 * Magic quotes setting
	 * @var boolean
	 */
	protected static $blnMagicQuotes;


	/**
	 * Clean the global GPC arrays
	 */
	public static function initialize()
	{
		$_GET    = static::cleanKey($_GET);
		$_POST   = static::cleanKey($_POST);
		$_COOKIE = static::cleanKey($_COOKIE);

		// Only check magic quotes once (see #3438)
		static::$blnMagicQuotes = function_exists('get_magic_quotes_gpc') && @get_magic_quotes_gpc();
	}


	/**
	 * Return a $_GET variable
	 *
	 * @param string  $strKey            The variable name
	 * @param boolean $blnDecodeEntities If true, all entities will be decoded
	 * @param boolean $blnKeepUnused     If true, the parameter will not be marked as used (see #4277)
	 *
	 * @return mixed The cleaned variable value
	 */
	public static function get($strKey, $blnDecodeEntities=false, $blnKeepUnused=false)
	{
		if (!isset($_GET[$strKey]))
		{
			return null;
		}

		$strCacheKey = $blnDecodeEntities ? 'getDecoded' : 'getEncoded';

		if (!isset(static::$arrCache[$strCacheKey][$strKey]))
		{
			$varValue = $_GET[$strKey];

			$varValue = static::stripSlashes($varValue);
			$varValue = static::decodeEntities($varValue);
			$varValue = static::xssClean($varValue, true);
			$varValue = static::stripTags($varValue);

			if (!$blnDecodeEntities)
			{
				$varValue = static::encodeSpecialChars($varValue);
			}

			$varValue = static::encodeInsertTags($varValue);

			static::$arrCache[$strCacheKey][$strKey] = $varValue;
		}

		// Mark the parameter as used (see #4277)
		if (!$blnKeepUnused)
		{
			unset(static::$arrUnusedGet[$strKey]);
		}

		return static::$arrCache[$strCacheKey][$strKey];
	}


	/**
	 * Return a $_POST variable
	 *
	 * @param string  $strKey            The variable name
	 * @param boolean $blnDecodeEntities If true, all entities will be decoded
	 *
	 * @return mixed The cleaned variable value
	 */
	public static function post($strKey, $blnDecodeEntities=false)
	{
		$strCacheKey = $blnDecodeEntities ? 'postDecoded' : 'postEncoded';

		if (!isset(static::$arrCache[$strCacheKey][$strKey]))
		{
			$varValue = static::findPost($strKey);

			if ($varValue === null)
			{
				return $varValue;
			}

			$varValue = static::stripSlashes($varValue);
			$varValue = static::decodeEntities($varValue);
			$varValue = static::xssClean($varValue, true);
			$varValue = static::stripTags($varValue);

			if (!$blnDecodeEntities)
			{
				$varValue = static::encodeSpecialChars($varValue);
			}

			if (TL_MODE != 'BE')
			{
				$varValue = static::encodeInsertTags($varValue);
			}

			static::$arrCache[$strCacheKey][$strKey] = $varValue;
		}

		return static::$arrCache[$strCacheKey][$strKey];
	}


	/**
	 * Return a $_POST variable preserving allowed HTML tags
	 *
	 * @param string  $strKey            The variable name
	 * @param boolean $blnDecodeEntities If true, all entities will be decoded
	 *
	 * @return mixed The cleaned variable value
	 */
	public static function postHtml($strKey, $blnDecodeEntities=false)
	{
		$strCacheKey = $blnDecodeEntities ? 'postHtmlDecoded' : 'postHtmlEncoded';

		if (!isset(static::$arrCache[$strCacheKey][$strKey]))
		{
			$varValue = static::findPost($strKey);

			if ($varValue === null)
			{
				return $varValue;
			}

			$varValue = static::stripSlashes($varValue);
			$varValue = static::decodeEntities($varValue);
			$varValue = static::xssClean($varValue);
			$varValue = static::stripTags($varValue, \Config::get('allowedTags'));

			if (!$blnDecodeEntities)
			{
				$varValue = static::encodeSpecialChars($varValue);
			}

			if (TL_MODE != 'BE')
			{
				$varValue = static::encodeInsertTags($varValue);
			}

			static::$arrCache[$strCacheKey][$strKey] = $varValue;
		}

		return static::$arrCache[$strCacheKey][$strKey];
	}


	/**
	 * Return a raw, unsafe $_POST variable
	 *
	 * @param string $strKey The variable name
	 *
	 * @return mixed The raw variable value
	 */
	public static function postRaw($strKey)
	{
		$strCacheKey = 'postRaw';

		if (!isset(static::$arrCache[$strCacheKey][$strKey]))
		{
			$varValue = static::findPost($strKey);

			if ($varValue === null)
			{
				return $varValue;
			}

			$varValue = static::stripSlashes($varValue);
			$varValue = static::preserveBasicEntities($varValue);
			$varValue = static::xssClean($varValue);

			if (TL_MODE != 'BE')
			{
				$varValue = static::encodeInsertTags($varValue);
			}

			static::$arrCache[$strCacheKey][$strKey] = $varValue;
		}

		return static::$arrCache[$strCacheKey][$strKey];
	}


	/**
	 * Return a raw, unsafe and unfiltered $_POST variable
	 *
	 * @param string $strKey The variable name
	 *
	 * @return mixed The raw variable value
	 *
	 * @internal
	 */
	public static function postUnsafeRaw($strKey)
	{
		$strCacheKey = 'postUnsafeRaw';

		if (!isset(static::$arrCache[$strCacheKey][$strKey]))
		{
			$varValue = static::findPost($strKey);

			if ($varValue === null)
			{
				return $varValue;
			}

			static::$arrCache[$strCacheKey][$strKey] = $varValue;
		}

		return static::$arrCache[$strCacheKey][$strKey];
	}


	/**
	 * Return a $_COOKIE variable
	 *
	 * @param string  $strKey            The variable name
	 * @param boolean $blnDecodeEntities If true, all entities will be decoded
	 *
	 * @return mixed The cleaned variable value
	 */
	public static function cookie($strKey, $blnDecodeEntities=false)
	{
		if (!isset($_COOKIE[$strKey]))
		{
			return null;
		}

		$strCacheKey = $blnDecodeEntities ? 'cookieDecoded' : 'cookieEncoded';

		if (!isset(static::$arrCache[$strCacheKey][$strKey]))
		{
			$varValue = $_COOKIE[$strKey];

			$varValue = static::stripSlashes($varValue);
			$varValue = static::decodeEntities($varValue);
			$varValue = static::xssClean($varValue, true);
			$varValue = static::stripTags($varValue);

			if (!$blnDecodeEntities)
			{
				$varValue = static::encodeSpecialChars($varValue);
			}

			$varValue = static::encodeInsertTags($varValue);

			static::$arrCache[$strCacheKey][$strKey] = $varValue;
		}

		return static::$arrCache[$strCacheKey][$strKey];
	}


	/**
	 * Set a $_GET variable
	 *
	 * @param string  $strKey       The variable name
	 * @param mixed   $varValue     The variable value
	 * @param boolean $blnAddUnused If true, the value usage will be checked
	 */
	public static function setGet($strKey, $varValue, $blnAddUnused=false)
	{
		// Convert special characters (see #7829)
		$strKey = str_replace(array(' ', '.', '['), '_', $strKey);

		$strKey = static::cleanKey($strKey);

		unset(static::$arrCache['getEncoded'][$strKey]);
		unset(static::$arrCache['getDecoded'][$strKey]);

		if ($varValue === null)
		{
			unset($_GET[$strKey]);
		}
		else
		{
			$_GET[$strKey] = $varValue;

			if ($blnAddUnused)
			{
				static::setUnusedGet($strKey, $varValue); // see #4277
			}
		}
	}


	/**
	 * Set a $_POST variable
	 *
	 * @param string $strKey   The variable name
	 * @param mixed  $varValue The variable value
	 */
	public static function setPost($strKey, $varValue)
	{
		$strKey = static::cleanKey($strKey);

		unset(static::$arrCache['postEncoded'][$strKey]);
		unset(static::$arrCache['postDecoded'][$strKey]);
		unset(static::$arrCache['postHtmlEncoded'][$strKey]);
		unset(static::$arrCache['postHtmlDecoded'][$strKey]);
		unset(static::$arrCache['postRaw'][$strKey]);
		unset(static::$arrCache['postUnsafeRaw'][$strKey]);

		if ($varValue === null)
		{
			unset($_POST[$strKey]);
		}
		else
		{
			$_POST[$strKey] = $varValue;
		}
	}


	/**
	 * Set a $_COOKIE variable
	 *
	 * @param string $strKey   The variable name
	 * @param mixed  $varValue The variable value
	 */
	public static function setCookie($strKey, $varValue)
	{
		$strKey = static::cleanKey($strKey);

		unset(static::$arrCache['cookieEncoded'][$strKey]);
		unset(static::$arrCache['cookieDecoded'][$strKey]);

		if ($varValue === null)
		{
			unset($_COOKIE[$strKey]);
		}
		else
		{
			$_COOKIE[$strKey] = $varValue;
		}
	}


	/**
	 * Reset the internal cache
	 */
	public static function resetCache()
	{
		static::$arrCache = array();
	}


	/**
	 * Return whether there are unused GET parameters
	 *
	 * @return boolean True if there are unused GET parameters
	 */
	public static function hasUnusedGet()
	{
		return count(static::$arrUnusedGet) > 0;
	}


	/**
	 * Return the unused GET parameters as array
	 *
	 * @return array The unused GET parameter array
	 */
	public static function getUnusedGet()
	{
		return array_keys(static::$arrUnusedGet);
	}


	/**
	 * Set an unused GET parameter
	 *
	 * @param string $strKey   The array key
	 * @param mixed  $varValue The array value
	 */
	public static function setUnusedGet($strKey, $varValue)
	{
		static::$arrUnusedGet[$strKey] = $varValue;
	}


	/**
	 * Sanitize the variable names (thanks to Andreas Schempp)
	 *
	 * @param mixed $varValue A variable name or an array of variable names
	 *
	 * @return mixed The clean name or array of names
	 */
	public static function cleanKey($varValue)
	{
		// Recursively clean arrays
		if (is_array($varValue))
		{
			$return = array();

			foreach ($varValue as $k=>$v)
			{
				$k = static::cleanKey($k);

				if (is_array($v))
				{
					$v = static::cleanKey($v);
				}

				$return[$k] = $v;
			}

			return $return;
		}

		$varValue = static::stripSlashes($varValue);
		$varValue = static::decodeEntities($varValue);
		$varValue = static::xssClean($varValue, true);
		$varValue = static::stripTags($varValue);

		return $varValue;
	}


	/**
	 * Strip slashes
	 *
	 * @param mixed $varValue A string or array
	 *
	 * @return mixed The string or array without slashes
	 */
	public static function stripSlashes($varValue)
	{
		if ($varValue == '' || !static::$blnMagicQuotes)
		{
			return $varValue;
		}

		// Recursively clean arrays
		if (is_array($varValue))
		{
			foreach ($varValue as $k=>$v)
			{
				$varValue[$k] = static::stripSlashes($v);
			}

			return $varValue;
		}

		return stripslashes($varValue);
	}


	/**
	 * Strip HTML and PHP tags preserving HTML comments
	 *
	 * @param mixed  $varValue       A string or array
	 * @param string $strAllowedTags A string of tags to preserve
	 *
	 * @return mixed The cleaned string or array
	 */
	public static function stripTags($varValue, $strAllowedTags='')
	{
		if ($varValue === null || $varValue == '')
		{
			return $varValue;
		}

		// Recursively clean arrays
		if (is_array($varValue))
		{
			foreach ($varValue as $k=>$v)
			{
				$varValue[$k] = static::stripTags($v, $strAllowedTags);
			}

			return $varValue;
		}

		// Encode opening arrow brackets (see #3998)
		$varValue = preg_replace_callback('@</?([^\s<>/]*)@', function ($matches) use ($strAllowedTags)
		{
			if ($matches[1] == '' || strpos(strtolower($strAllowedTags), '<' . strtolower($matches[1]) . '>') === false)
			{
				$matches[0] = str_replace('<', '&lt;', $matches[0]);
			}

			return $matches[0];
		}, $varValue);

		// Strip the tags and restore HTML comments
		$varValue = strip_tags($varValue, $strAllowedTags);
		$varValue = str_replace(array('&lt;!--', '&lt;!['), array('<!--', '<!['), $varValue);

		// Recheck for encoded null bytes
		while (strpos($varValue, '\\0') !== false)
		{
			$varValue = str_replace('\\0', '', $varValue);
		}

		return $varValue;
	}


	/**
	 * Clean a value and try to prevent XSS attacks
	 *
	 * @param mixed   $varValue      A string or array
	 * @param boolean $blnStrictMode If true, the function removes also JavaScript event handlers
	 *
	 * @return mixed The cleaned string or array
	 */
	public static function xssClean($varValue, $blnStrictMode=false)
	{
		if ($varValue === null || $varValue == '')
		{
			return $varValue;
		}

		// Recursively clean arrays
		if (is_array($varValue))
		{
			foreach ($varValue as $k=>$v)
			{
				$varValue[$k] = static::xssClean($v);
			}

			return $varValue;
		}

		// Return if the value is not a string
		if (is_bool($varValue) || $varValue === null || is_numeric($varValue))
		{
			return $varValue;
		}

		// Validate standard character entites and UTF16 two byte encoding
		$varValue = preg_replace('/(&#*\w+)[\x00-\x20]+;/i', '$1;', $varValue);
		$varValue = preg_replace('/(&#x*)([0-9a-f]+);/i', '$1$2;', $varValue);

		// Remove carriage returns
		$varValue = preg_replace('/\r+/', '', $varValue);

		// Replace unicode entities
		$varValue = utf8_decode_entities($varValue);

		// Remove null bytes
		$varValue = str_replace(chr(0), '', $varValue);

		// Remove encoded null bytes
		while (strpos($varValue, '\\0') !== false)
		{
			$varValue = str_replace('\\0', '', $varValue);
		}

		// Define a list of keywords
		$arrKeywords = array
		(
			'/\bj\s*a\s*v\s*a\s*s\s*c\s*r\s*i\s*p\s*t\b/is', // javascript
			'/\bv\s*b\s*s\s*c\s*r\s*i\s*p\s*t\b/is', // vbscript
			'/\bv\s*b\s*s\s*c\s*r\s*p\s*t\b/is', // vbscrpt
			'/\bs\s*c\s*r\s*i\s*p\s*t\b/is', //script
			'/\ba\s*p\s*p\s*l\s*e\s*t\b/is', // applet
			'/\ba\s*l\s*e\s*r\s*t\b/is', // alert
			'/\bd\s*o\s*c\s*u\s*m\s*e\s*n\s*t\b/is', // document
			'/\bw\s*r\s*i\s*t\s*e\b/is', // write
			'/\bc\s*o\s*o\s*k\s*i\s*e\b/is', // cookie
			'/\bw\s*i\s*n\s*d\s*o\s*w\b/is' // window
		);

		// Compact exploded keywords like "j a v a s c r i p t"
		foreach ($arrKeywords as $strKeyword)
		{
			$arrMatches = array();
			preg_match_all($strKeyword, $varValue, $arrMatches);

			foreach ($arrMatches[0] as $strMatch)
			{
				$varValue = str_replace($strMatch, preg_replace('/\s*/', '', $strMatch), $varValue);
			}
		}

		$arrRegexp[] = '/<(a|img)[^>]*[^a-z](<script|<xss)[^>]*>/is';
		$arrRegexp[] = '/<(a|img)[^>]*[^a-z]document\.cookie[^>]*>/is';
		$arrRegexp[] = '/<(a|img)[^>]*[^a-z]vbscri?pt\s*:[^>]*>/is';
		$arrRegexp[] = '/<(a|img)[^>]*[^a-z]expression\s*\([^>]*>/is';

		// Also remove event handlers and JavaScript in strict mode
		if ($blnStrictMode)
		{
			$arrRegexp[] = '/vbscri?pt\s*:/is';
			$arrRegexp[] = '/javascript\s*:/is';
			$arrRegexp[] = '/<\s*embed.*swf/is';
			$arrRegexp[] = '/<(a|img)[^>]*[^a-z]alert\s*\([^>]*>/is';
			$arrRegexp[] = '/<(a|img)[^>]*[^a-z]javascript\s*:[^>]*>/is';
			$arrRegexp[] = '/<(a|img)[^>]*[^a-z]window\.[^>]*>/is';
			$arrRegexp[] = '/<(a|img)[^>]*[^a-z]document\.[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onabort\s*=[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onblur\s*=[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onchange\s*=[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onclick\s*=[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onerror\s*=[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onfocus\s*=[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onkeypress\s*=[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onkeydown\s*=[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onkeyup\s*=[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onload\s*=[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onmouseover\s*=[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onmouseup\s*=[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onmousedown\s*=[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onmouseout\s*=[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onreset\s*=[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onselect\s*=[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onsubmit\s*=[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onunload\s*=[^>]*>/is';
			$arrRegexp[] = '/<[^>]*[^a-z]onresize\s*=[^>]*>/is';
		}

		$varValue = preg_replace($arrRegexp, '', $varValue);

		// Recheck for encoded null bytes
		while (strpos($varValue, '\\0') !== false)
		{
			$varValue = str_replace('\\0', '', $varValue);
		}

		return $varValue;
	}


	/**
	 * Decode HTML entities
	 *
	 * @param mixed $varValue A string or array
	 *
	 * @return mixed The decoded string or array
	 */
	public static function decodeEntities($varValue)
	{
		if ($varValue === null || $varValue == '')
		{
			return $varValue;
		}

		// Recursively clean arrays
		if (is_array($varValue))
		{
			foreach ($varValue as $k=>$v)
			{
				$varValue[$k] = static::decodeEntities($v);
			}

			return $varValue;
		}

		// Preserve basic entities
		$varValue = static::preserveBasicEntities($varValue);
		$varValue = html_entity_decode($varValue, ENT_QUOTES, \Config::get('characterSet'));

		return $varValue;
	}


	/**
	 * Preserve basic entities by replacing them with square brackets (e.g. &amp; becomes [amp])
	 *
	 * @param mixed $varValue A string or array
	 *
	 * @return mixed The string or array with the converted entities
	 */
	public static function preserveBasicEntities($varValue)
	{
		if ($varValue === null || $varValue == '')
		{
			return $varValue;
		}

		// Recursively clean arrays
		if (is_array($varValue))
		{
			foreach ($varValue as $k=>$v)
			{
				$varValue[$k] = static::preserveBasicEntities($v);
			}

			return $varValue;
		}

		$varValue = str_replace
		(
			array('[&amp;]', '&amp;', '[&lt;]', '&lt;', '[&gt;]', '&gt;', '[&nbsp;]', '&nbsp;', '[&shy;]', '&shy;'),
			array('[&]', '[&]', '[lt]', '[lt]', '[gt]', '[gt]', '[nbsp]', '[nbsp]', '[-]', '[-]'),
			$varValue
		);

		return $varValue;
	}


	/**
	 * Encode special characters which are potentially dangerous
	 *
	 * @param mixed $varValue A string or array
	 *
	 * @return mixed The encoded string or array
	 */
	public static function encodeSpecialChars($varValue)
	{
		if ($varValue === null || $varValue == '')
		{
			return $varValue;
		}

		// Recursively clean arrays
		if (is_array($varValue))
		{
			foreach ($varValue as $k=>$v)
			{
				$varValue[$k] = static::encodeSpecialChars($v);
			}

			return $varValue;
		}

		$arrSearch = array('#', '<', '>', '(', ')', '\\', '=');
		$arrReplace = array('&#35;', '&#60;', '&#62;', '&#40;', '&#41;', '&#92;', '&#61;');

		return str_replace($arrSearch, $arrReplace, $varValue);
	}


	/**
	 * Encode the opening and closing delimiters of insert tags
	 *
	 * @param string $varValue The input string
	 *
	 * @return string The encoded input string
	 */
	public static function encodeInsertTags($varValue)
	{
		return str_replace(array('{{', '}}'), array('&#123;&#123;', '&#125;&#125;'), $varValue);
	}


	/**
	 * Fallback to the session form data if there is no post data
	 *
	 * @param string $strKey The variable name
	 *
	 * @return mixed The variable value
	 */
	public static function findPost($strKey)
	{
		if (isset($_POST[$strKey]))
		{
			return $_POST[$strKey];
		}

		if (isset($_SESSION['FORM_DATA'][$strKey]))
		{
			return ($strKey == 'FORM_SUBMIT') ? preg_replace('/^auto_/i', '', $_SESSION['FORM_DATA'][$strKey]) : $_SESSION['FORM_DATA'][$strKey];
		}

		return null;
	}


	/**
	 * Clean the keys of the request arrays
	 *
	 * @deprecated Input is now a static class
	 */
	protected function __construct()
	{
		static::initialize();
	}


	/**
	 * Prevent cloning of the object (Singleton)
	 *
	 * @deprecated Input is now a static class
	 */
	final public function __clone() {}


	/**
	 * Return the object instance (Singleton)
	 *
	 * @return \Input The object instance
	 *
	 * @deprecated Input is now a static class
	 */
	public static function getInstance()
	{
		if (static::$objInstance === null)
		{
			static::$objInstance = new static();
		}

		return static::$objInstance;
	}
}
