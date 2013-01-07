<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class Input
 *
 * Provide methods to clean up user input and to prevent XSS.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Library
 */
class Input
{

	/**
	 * Current object instance (Singleton)
	 * @var Input
	 */
	protected static $objInstance;

	/**
	 * Cache array
	 * @var array
	 */
	protected $arrCache = array();

	/**
	 * Magic quotes
	 * @var boolean
	 */
	protected $blnMagicQuotes;


	/**
	 * Clean the keys of the request arrays
	 */
	protected function __construct()
	{
		$_GET    = $this->cleanKey($_GET);
		$_POST   = $this->cleanKey($_POST);
		$_COOKIE = $this->cleanKey($_COOKIE);

		// Only check magic quotes once (see #3438)
		$this->blnMagicQuotes = function_exists('get_magic_quotes_gpc') && @get_magic_quotes_gpc();
	}


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final private function __clone() {}


	/**
	 * Return the current object instance (Singleton)
	 * @return Input
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			self::$objInstance = new self();
		}

		return self::$objInstance;
	}


	/**
	 * Return a $_GET parameter
	 * @param string
	 * @param boolean
	 * @return mixed
	 */
	public function get($strKey, $blnDecodeEntities=false)
	{
		$strCacheKey = $blnDecodeEntities ? 'getDecoded' : 'getEncoded';

		if (!isset($this->arrCache[$strCacheKey][$strKey]))
		{
			$varValue = $_GET[$strKey];

			if ($varValue === null)
			{
				return $varValue;
			}

			$varValue = $this->stripSlashes($varValue);
			$varValue = $this->decodeEntities($varValue);
			$varValue = $this->xssClean($varValue, true);
			$varValue = $this->stripTags($varValue);

			if (!$blnDecodeEntities)
			{
				$varValue = $this->encodeSpecialChars($varValue);
			}

			$this->arrCache[$strCacheKey][$strKey] = $varValue;
		}

		return $this->arrCache[$strCacheKey][$strKey];
	}


	/**
	 * Return a $_POST parameter
	 * @param string
	 * @param boolean
	 * @return mixed
	 */
	public function post($strKey, $blnDecodeEntities=false)
	{
		$strCacheKey = $blnDecodeEntities ? 'postDecoded' : 'postEncoded';

		if (!isset($this->arrCache[$strCacheKey][$strKey]))
		{
			$varValue = $this->findPost($strKey);

			if ($varValue === null)
			{
				return $varValue;
			}

			$varValue = $this->stripSlashes($varValue);
			$varValue = $this->decodeEntities($varValue);
			$varValue = $this->xssClean($varValue, true);
			$varValue = $this->stripTags($varValue);

			if (!$blnDecodeEntities)
			{
				$varValue = $this->encodeSpecialChars($varValue);
			}

			$this->arrCache[$strCacheKey][$strKey] = $varValue;
		}

		return $this->arrCache[$strCacheKey][$strKey];
	}


	/**
	 * Return a $_POST parameter preserving allowed HTML tags
	 * @param string
	 * @param boolean
	 * @return mixed
	 */
	public function postHtml($strKey, $blnDecodeEntities=false)
	{
		$strCacheKey = $blnDecodeEntities ? 'postHtmlDecoded' : 'postHtmlEncoded';

		if (!isset($this->arrCache[$strCacheKey][$strKey]))
		{
			$varValue = $this->findPost($strKey);

			if ($varValue === null)
			{
				return $varValue;
			}

			$varValue = $this->stripSlashes($varValue);
			$varValue = $this->decodeEntities($varValue);
			$varValue = $this->xssClean($varValue);
			$varValue = $this->stripTags($varValue, $GLOBALS['TL_CONFIG']['allowedTags']);

			if (!$blnDecodeEntities)
			{
				$varValue = $this->encodeSpecialChars($varValue);
			}

			$this->arrCache[$strCacheKey][$strKey] = $varValue;
		}

		return $this->arrCache[$strCacheKey][$strKey];
	}


	/**
	 * Return a $_POST parameter unencoded without stripping tags
	 * @param string
	 * @return mixed
	 */
	public function postRaw($strKey)
	{
		$strCacheKey = 'postRaw';

		if (!isset($this->arrCache[$strCacheKey][$strKey]))
		{
			$varValue = $this->findPost($strKey);

			if ($varValue === null)
			{
				return $varValue;
			}

			$varValue = $this->stripSlashes($varValue);
			$varValue = $this->preserveBasicEntities($varValue);
			$varValue = $this->xssClean($varValue);

			$this->arrCache[$strCacheKey][$strKey] = $varValue;
		}

		return $this->arrCache[$strCacheKey][$strKey];
	}


	/**
	 * Return a $_COOKIE parameter
	 * @param string
	 * @param boolean
	 * @return mixed
	 */
	public function cookie($strKey, $blnDecodeEntities=false)
	{
		$strCacheKey = $blnDecodeEntities ? 'cookieDecoded' : 'cookieEncoded';

		if (!isset($this->arrCache[$strCacheKey][$strKey]))
		{
			$varValue = $_COOKIE[$strKey];

			if ($varValue === null)
			{
				return $varValue;
			}

			$varValue = $this->stripSlashes($varValue);
			$varValue = $this->decodeEntities($varValue);
			$varValue = $this->xssClean($varValue, true);
			$varValue = $this->stripTags($varValue);

			if (!$blnDecodeEntities)
			{
				$varValue = $this->encodeSpecialChars($varValue);
			}

			$this->arrCache[$strCacheKey][$strKey] = $varValue;
		}

		return $this->arrCache[$strCacheKey][$strKey];
	}


	/**
	 * Set a $_GET parameter
	 * @param string
	 * @param mixed
	 */
	public function setGet($strKey, $varValue)
	{
		$strKey = $this->cleanKey($strKey);

		unset($this->arrCache['getEncoded'][$strKey]);
		unset($this->arrCache['getDecoded'][$strKey]);

		if ($varValue === null)
		{
			unset($_GET[$strKey]);
		}
		else
		{
			$_GET[$strKey] = $varValue;
		}
	}


	/**
	 * Set a $_POST parameter
	 * @param string
	 * @param mixed
	 */
	public function setPost($strKey, $varValue)
	{
		$strKey = $this->cleanKey($strKey);

		unset($this->arrCache['postEncoded'][$strKey]);
		unset($this->arrCache['postDecoded'][$strKey]);
		unset($this->arrCache['postRaw'][$strKey]);

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
	 * Set a $_COOKIE parameter
	 * @param string
	 * @param mixed
	 */
	public function setCookie($strKey, $varValue)
	{
		$strKey = $this->cleanKey($strKey);

		unset($this->arrCache['cookieEncoded'][$strKey]);
		unset($this->arrCache['cookieDecoded'][$strKey]);

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
	public function resetCache()
	{
		$this->arrCache = array();
	}


	/**
	 * Sanitize a key name or an array (thanks to Andreas Schempp)
	 * @param mixed
	 * @return mixed
	 */
	protected function cleanKey($varValue)
	{
		// Recursively clean arrays
		if (is_array($varValue))
		{
			$return = array();

			foreach ($varValue as $k=>$v)
			{
				$k = $this->cleanKey($k);

				if (is_array($v))
				{
					$v = $this->cleanKey($v);
				}

				$return[$k] = $v;
			}

			return $return;
		}

		$varValue = $this->stripSlashes($varValue);
		$varValue = $this->decodeEntities($varValue);
		$varValue = $this->xssClean($varValue, true);
		$varValue = $this->stripTags($varValue);

		return $varValue;
	}


	/**
	 * Strip slashes
	 * @param mixed
	 * @return mixed
	 */
	protected function stripSlashes($varValue)
	{
		if ($varValue == '' || !$this->blnMagicQuotes)
		{
			return $varValue;
		}

		// Recursively clean arrays
		if (is_array($varValue))
		{
			foreach ($varValue as $k=>$v)
			{
				$varValue[$k] = $this->stripSlashes($v);
			}

			return $varValue;
		}

		return stripslashes($varValue);
	}


	/**
	 * Strip tags preserving HTML comments
	 * @param mixed
	 * @param string
	 * @return mixed
	 */
	protected function stripTags($varValue, $strAllowedTags='')
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
				$varValue[$k] = $this->stripTags($v, $strAllowedTags);
			}

			return $varValue;
		}

		$varValue = str_replace(array('<!--','<![', '-->'), array('&lt;!--', '&lt;![', '--&gt;'), $varValue);
		$varValue = strip_tags($varValue, $strAllowedTags);
		$varValue = str_replace(array('&lt;!--', '&lt;![', '--&gt;'), array('<!--', '<![', '-->'), $varValue);

		return $varValue;
	}


	/**
	 * Clean user input and try to prevent XSS attacks
	 * @param mixed
	 * @param boolean
	 * @return mixed
	 */
	protected function xssClean($varValue, $blnStrictMode=false)
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
				$varValue[$k] = $this->xssClean($v);
			}

			return $varValue;
		}

		// Return if var is not a string
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

		// Remove NULL characters
		$varValue = preg_replace('/\0+/', '', $varValue);
		$varValue = preg_replace('/(\\\\0)+/', '', $varValue);

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

		return preg_replace($arrRegexp, '', $varValue);
	}


	/**
	 * Decode HTML entities
	 * @param mixed
	 * @return mixed
	 */
	protected function decodeEntities($varValue)
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
				$varValue[$k] = $this->decodeEntities($v);
			}

			return $varValue;
		}

		// Preserve basic entities
		$varValue = $this->preserveBasicEntities($varValue);
		$varValue = html_entity_decode($varValue, ENT_COMPAT, $GLOBALS['TL_CONFIG']['characterSet']);

		return $varValue;
	}


	/**
	 * Preserve basic entities
	 * @param mixed
	 * @return mixed
	 */
	protected function preserveBasicEntities($varValue)
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
				$varValue[$k] = $this->preserveBasicEntities($v);
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
	 * Encode special characters
	 * @param mixed
	 * @return mixed
	 */
	protected function encodeSpecialChars($varValue)
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
				$varValue[$k] = $this->encodeSpecialChars($v);
			}

			return $varValue;
		}

		$arrSearch = array('#', '<', '>', '(', ')', '\\', '=');
		$arrReplace = array('&#35;', '&#60;', '&#62;', '&#40;', '&#41;', '&#92;', '&#61;');

		return str_replace($arrSearch, $arrReplace, $varValue);
	}


	/**
	 * Fallback to the session form data if there is no post data
	 * @param string
	 * @return mixed
	 */
	protected function findPost($strKey)
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
}

?>