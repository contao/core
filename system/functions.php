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
 * Load the required classes for the file cache
 */
include_once TL_ROOT . '/system/libraries/System.php';
include_once TL_ROOT . '/system/libraries/FileCache.php';


/**
 * Class autoloader
 *
 * Include classes automatically when they are instantiated.
 * @param string
 */
function __autoload($strClassName)
{
	$objCache = FileCache::getInstance('autoload');

	// Try to load the class name from the session cache
	if (isset($objCache->$strClassName))
	{
		if (@include_once(TL_ROOT . '/' . $objCache->$strClassName))
		{
			return; // The class could be loaded
		}
		else
		{
			unset($objCache->$strClassName); // The class has been removed
		}
	}

	$strLibrary = TL_ROOT . '/system/libraries/' . $strClassName . '.php';

	// Check for libraries first
	if (file_exists($strLibrary))
	{
		include_once($strLibrary);
		$objCache->$strClassName = 'system/libraries/' . $strClassName . '.php';

		return;
	}

	// Then check the modules folder
	foreach (scan(TL_ROOT . '/system/modules/') as $strFolder)
	{
		if (substr($strFolder, 0, 1) == '.')
		{
			continue;
		}

		$strModule = TL_ROOT . '/system/modules/' . $strFolder . '/' . $strClassName . '.php';

		if (file_exists($strModule))
		{
			include_once($strModule);
			$objCache->$strClassName = 'system/modules/' . $strFolder . '/' . $strClassName . '.php';

			return;
		}
	}

	// HOOK: include Swift classes
	if (class_exists('Swift', false))
	{
		Swift::autoload($strClassName);
		return;
	}

	// HOOK: include DOMPDF classes
	if (function_exists('DOMPDF_autoload'))
	{
		DOMPDF_autoload($strClassName);
		return;
	}

	trigger_error(sprintf('Could not load class %s', $strClassName), E_USER_ERROR);
}


/**
 * Error handler
 *
 * Handle errors like PHP does it natively but additionaly log them to the
 * application error log file.
 * @param int
 * @param string
 * @param string
 * @param int
 */
function __error($intType, $strMessage, $strFile, $intLine)
{
	$arrErrors = array
	(
		E_ERROR             => 'Fatal error',
		E_WARNING           => 'Warning',
		E_PARSE             => 'Parsing error',
		E_NOTICE            => 'Notice',
		E_CORE_ERROR        => 'Core error',
		E_CORE_WARNING      => 'Core warning',
		E_COMPILE_ERROR     => 'Compile error',
		E_COMPILE_WARNING   => 'Compile warning',
		E_USER_ERROR        => 'Fatal error',
		E_USER_WARNING      => 'Warning',
		E_USER_NOTICE       => 'Notice',
		E_STRICT            => 'Runtime notice',
		4096                => 'Recoverable error',
		8192                => 'Deprecated notice'
	);

	// Ignore functions with an error control operator (@function_name)
	if (ini_get('error_reporting') > 0)
	{
		if ($intType != E_NOTICE)
		{
			// Log error
			error_log(sprintf('PHP %s: %s in %s on line %s',
							$arrErrors[$intType],
							$strMessage,
							$strFile,
							$intLine));

			// Display error
			if (ini_get('display_errors'))
			{
				$strMessage = sprintf('<strong>%s</strong>: %s in <strong>%s</strong> on line <strong>%s</strong>',
									$arrErrors[$intType],
									$strMessage,
									$strFile,
									$intLine);

				$e = new Exception();
				$strMessage .= "\n" . '<pre style="margin: 11px 0 0 0;">' . "\n" . $e->getTraceAsString() . "\n" . '</pre>';

				echo '<br>' . $strMessage;
			}
		}

		// Exit on severe errors
		if (in_array($intType, array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR)))
		{
			show_help_message();
			exit;
		}
	}
}


/**
 * Exception handler
 *
 * Log exceptions in the application log file and print them to the screen
 * if "display_errors" is set. Callback to a custom exception handler defined
 * in the application file "config/error.php".
 * @param object
 */
function __exception($e)
{
	error_log(sprintf("PHP Fatal error: Uncaught exception '%s' with message '%s' thrown in %s on line %s",
					get_class($e),
					$e->getMessage(),
					$e->getFile(),
					$e->getLine()));

	// Display exception
	if (ini_get('display_errors'))
	{
		$strMessage = sprintf('<strong>Fatal error</strong>: Uncaught exception <strong>%s</strong> with message <strong>%s</strong> thrown in <strong>%s</strong> on line <strong>%s</strong>',
							get_class($e),
							$e->getMessage(),
							$e->getFile(),
							$e->getLine());

		$strMessage .= "\n" . '<pre style="margin: 11px 0 0 0;">' . "\n" . $e->getTraceAsString() . "\n" . '</pre>';
		echo '<br>' . $strMessage;
	}

	show_help_message();
	exit;
}


/**
 * Show a special Contao "what to do in case of an error" message
 */
function show_help_message()
{
	if (!ini_get('display_errors'))
	{
		header('HTTP/1.1 500 Internal Server Error');

		if (file_exists(TL_ROOT . '/templates/be_error.html5'))
		{
			include(TL_ROOT . '/templates/be_error.html5');
			exit;
		}
		elseif (file_exists(TL_ROOT . '/system/modules/backend/templates/be_error.html5'))
		{
			include(TL_ROOT . '/system/modules/backend/templates/be_error.html5');
			exit;
		}

		echo 'An error occurred while executing this script!';
	}
}


/**
 * Add a log entry
 * @param string
 * @param string
 */
function log_message($strMessage, $strLog='error.log')
{
	@error_log(sprintf("[%s] %s\n", date('d-M-Y H:i:s'), $strMessage), 3, TL_ROOT . '/system/logs/' . $strLog);
}


/**
 * Scan a directory and return its files and folders as array
 * @param string
 * @param boolean
 * @return array
 */
function scan($strFolder, $blnUncached=false)
{
	global $arrScanCache;

	// Add a trailing slash
	if (substr($strFolder, -1, 1) != '/')
	{
		$strFolder .= '/';
	}

	// Load from cache
	if (!$blnUncached && isset($arrScanCache[$strFolder]))
	{
		return $arrScanCache[$strFolder];
	}

	$arrReturn = array();

	// Scan directory
	foreach (scandir($strFolder) as $strFile)
	{
		if ($strFile == '.' || $strFile == '..')
		{
			continue;
		}

		$arrReturn[] = $strFile;
	}

	// Cache the result
	if (!$blnUncached)
	{
		$arrScanCache[$strFolder] = $arrReturn;
	}

	return $arrReturn;
}


/**
 * Convert special characters to HTML entities and make sure that
 * entities are never double converted.
 * @param string
 * @param boolean
 * @return string
 */
function specialchars($strString, $blnStripInsertTags=false)
{
	if ($blnStripInsertTags)
	{
		$strString = strip_insert_tags($strString);
	}

	if (version_compare(PHP_VERSION, '5.2.3', '>='))
	{
		return htmlspecialchars($strString, ENT_COMPAT, $GLOBALS['TL_CONFIG']['characterSet'], false);
	}

	return str_replace(array('&amp;amp;', '&amp;quot;', '&amp;lt;', '&amp;gt;'), array('&amp;', '&quot;', '&lt;', '&gt;'), htmlspecialchars($strString));
}


/**
 * Standardize a parameter (strip special characters and convert spaces to underscores)
 * @param string
 * @return string
 */
function standardize($strString)
{
	$arrSearch = array('/[^a-zA-Z0-9 _-]+/i', '/ +/', '/\-+/');
	$arrReplace = array('', '-', '-');

	$strString = html_entity_decode($strString, ENT_QUOTES, $GLOBALS['TL_CONFIG']['characterSet']);
	$strString = strip_insert_tags($strString);
	$strString = utf8_romanize($strString);
	$strString = preg_replace($arrSearch, $arrReplace, $strString);

	if (is_numeric(substr($strString, 0, 1)))
	{
		$strString = 'id-' . $strString;
	}

	return strtolower(trim($strString, '-'));
}


/**
 * Remove Contao insert tags from a string
 * @param string
 * @return string
 */
function strip_insert_tags($strString)
{
	return preg_replace('/\{\{[^\}]+\}\}/U', '', $strString);
}


/**
 * Return an unserialized array or the argument
 * @param mixed
 * @param boolean
 * @return mixed
 */
function deserialize($varValue, $blnForceArray=false)
{
	if (is_array($varValue))
	{
		return $varValue;
	}

	if (!is_string($varValue))
	{
		return $blnForceArray ? (is_null($varValue) ? array() : array($varValue)) : $varValue;
	}
	elseif (trim($varValue) == '')
	{
		return $blnForceArray ? array() : '';
	}

	$varUnserialized = unserialize($varValue);

	if (is_array($varUnserialized))
	{
		$varValue = $varUnserialized;
	}

	elseif ($blnForceArray)
	{
		$varValue = array($varValue);
	}

	return $varValue;
}


/**
 * Split a string into fragments, remove whitespace and return fragments as array
 * @param string
 * @param string
 * @return string
 */
function trimsplit($strPattern, $strString)
{
	global $arrSplitCache;
	$strKey = md5($strPattern.$strString);

	// Load from cache
	if (isset($arrSplitCache[$strKey]))
	{
		return $arrSplitCache[$strKey];
	}

	// Split
	if (strlen($strPattern) == 1)
	{
		$arrFragments = array_map('trim', explode($strPattern, $strString));
	}
	else
	{
		$arrFragments = array_map('trim', preg_split('/'.$strPattern.'/ui', $strString));
	}

	// Empty array
	if (count($arrFragments) < 2 && !strlen($arrFragments[0]))
	{
		$arrFragments = array();
	}

	$arrSplitCache[$strKey] = $arrFragments;
	return $arrFragments;
}


/**
 * Convert all ampersands into their HTML entity (default) or unencoded value
 * @param string
 * @return string
 */
function ampersand($strString, $blnEncode=true)
{
	return preg_replace('/&(amp;)?/i', ($blnEncode ? '&amp;' : '&'), $strString);
}


/**
 * Replace line breaks with HTML5-style <br> tags
 * @param string
 * @return string
 */
function nl2br_html5($str)
{
	if (version_compare(PHP_VERSION, '5.3.0') >= 0)
	{
		return nl2br($str, false);
	}
	else
	{
		return str_replace('<br />', '<br>', nl2br($str));
	}
}


/**
 * Replace line breaks with XHTML-style <br /> tags
 * @param string
 * @return string
 */
function nl2br_xhtml($str)
{
	return nl2br($str);
}


/**
 * Replace line breaks with <br> tags preserving preformatted text
 * @param string
 * @param boolean
 * @return string
 */
function nl2br_pre($str, $xhtml=false)
{
	$str = $xhtml ? nl2br_xhtml($str) : nl2br_html5($str);

	if (stripos($str, '<pre') === false)
	{
		return $str;
	}

	$chunks = array();
	preg_match_all('/<pre[^>]*>.*<\/pre>/Uis', $str, $chunks);

	foreach ($chunks as $chunk)
	{
		$str = str_replace($chunk, str_ireplace(array('<br>', '<br />'), '', $chunk), $str);
	}

	return $str;
}


/**
 * Replace line breaks with <br> tags (to be used with preg_replace_callback)
 * @param array
 * @return string
 */
function nl2br_callback($matches)
{
	return str_replace("\n", '<br>', $matches[0]);
}


/**
 * Compare two file names using a case insensitive "natural order" algorithm
 * @param string
 * @param string
 * @return integer
 */
function basename_natcasecmp($a, $b)
{
	return strnatcasecmp(basename($a), basename($b));
}


/**
 * Compare two file names using a case insensitive, reverse "natural order" algorithm
 * @param string
 * @param string
 * @return integer
 */
function basename_natcasercmp($a, $b)
{
	return - strnatcasecmp(basename($a), basename($b));
}


/**
 * Sort an array by keys using a case insensitive "natural order" algorithm
 * @param array
 * @return array
 */
function natcaseksort($arrArray)
{
	$arrBuffer = array_flip($arrArray);
	natcasesort($arrBuffer);

	return array_flip($arrBuffer);
}


/**
 * Compare two values based on their length (ascending)
 * @param integer
 * @param integer
 * @return integer
 */
function length_sort_asc($a, $b)
{
   	return strlen($a) - strlen($b);
}


/**
 * Compare two values based on their length (descending)
 * @param integer
 * @param integer
 * @return integer
 */
function length_sort_desc($a, $b)
{
   	return strlen($b) - strlen($a);
}


/**
 * Insert a parameter or array into an existing array at a particular index
 * @param array
 * @param int
 * @param mixed
 */
function array_insert(&$arrCurrent, $intIndex, $arrNew)
{
	if (!is_array($arrCurrent))
	{
		$arrCurrent = $arrNew;
		return;
	}

	if (is_array($arrNew))
	{
		$arrBuffer = array_splice($arrCurrent, 0, $intIndex);
		$arrCurrent = array_merge_recursive($arrBuffer, $arrNew, $arrCurrent);

		return;
	}

	array_splice($arrCurrent, $intIndex, 0, $arrNew);
}


/**
 * Duplicate a particular element of an array
 * @param array
 * @param integer
 * @return array
 */
function array_duplicate($arrStack, $intIndex)
{
	$arrBuffer = $arrStack;
	$arrStack = array();

	for ($i=0; $i<=$intIndex; $i++)
	{
		$arrStack[] = $arrBuffer[$i];
	}

	for ($i=$intIndex; $i<count($arrBuffer); $i++)
	{
		$arrStack[] = $arrBuffer[$i];
	}

	return $arrStack;
}


/**
 * Move an array element one position up
 * @param array
 * @param integer
 * @return array
 */
function array_move_up($arrStack, $intIndex)
{
	if ($intIndex > 0)
	{
		$arrBuffer = $arrStack[$intIndex];
		$arrStack[$intIndex] = $arrStack[($intIndex-1)];
		$arrStack[($intIndex-1)] = $arrBuffer;
	}

	else
	{
		array_push($arrStack, $arrStack[$intIndex]);
		array_shift($arrStack);
	}

	return $arrStack;
}


/**
 * Move an array element one position down
 * @param array
 * @param int
 * @return array
 */
function array_move_down($arrStack, $intIndex)
{
	if (($intIndex+1) < count($arrStack))
	{
		$arrBuffer = $arrStack[$intIndex];
		$arrStack[$intIndex] = $arrStack[($intIndex+1)];
		$arrStack[($intIndex+1)] = $arrBuffer;
	}

	else
	{
		array_unshift($arrStack, $arrStack[$intIndex]);
		array_pop($arrStack);
	}

	return $arrStack;
}


/**
 * Delete a particular element of an array
 * @param array
 * @param int
 * @return array
 */
function array_delete($arrStack, $intIndex)
{
	unset($arrStack[$intIndex]);
	return array_values($arrStack);
}


/**
 * Return true if an array is associative
 * @param  array
 * @return boolean
 */
function array_is_assoc($arrArray)
{
	return (is_array($arrArray) && array_keys($arrArray) !== range(0, (sizeof($arrArray) - 1)));
}


/**
 * Load the mbstring library
 */
require(TL_ROOT . '/system/mbstring.php');


/**
 * Define some mbstring wrapper functions
 */
if (!USE_MBSTRING)
{
	// mb_convert_encoding
	function mb_convert_encoding($str, $to, $from=null)
	{
		if (is_null($from))
			return utf8_convert_encoding($str, $to);

		return utf8_convert_encoding($str, $to, $from);
	}

	// mb_detect_encoding
	function mb_detect_encoding($str)
	{
		return utf8_detect_encoding($str);
	}

	// mb_stripos
	function mb_stripos($haystack, $needle, $offset=null)
	{
		if (is_null($offset))
			return stripos($haystack, $needle);

		return stripos($haystack, $needle, $offset);
	}

	// mb_stristr
	function mb_stristr($haystack, $needle)
	{
		return stristr($haystack, $needle);
	}

	// mb_strlen
	function mb_strlen($str)
	{
		return utf8_strlen($str);
	}

	// mb_strpos
	function mb_strpos($haystack, $needle, $offset=null)
	{
		if (is_null($offset))
			return utf8_strpos($haystack, $needle);

		return utf8_strpos($haystack, $needle, $offset);
	}

	// mb_strrchr
	function mb_strrchr($haystack, $needle)
	{
		return utf8_strrchr($haystack, $needle);
	}

	// mb_strrpos
	function mb_strrpos($haystack, $needle)
	{
		return utf8_strrpos($haystack, $needle);
	}

	// mb_strstr
	function mb_strstr($haystack, $needle)
	{
		return utf8_strstr($haystack, $needle);
	}

	// mb_strtolower
	function mb_strtolower($str)
	{
		return utf8_strtolower($str);
	}

	// mb_strtoupper
	function mb_strtoupper($str)
	{
		return utf8_strtoupper($str);
	}

	// mb_substr
	function mb_substr($str, $start, $length=null)
	{
		if (is_null($length))
			return utf8_substr($str, $start);

		return utf8_substr($str, $start, $length);
	}

	// mb_substr_count
	function mb_substr_count($haystack, $needle, $offset=null)
	{
		if (is_null($offset))
			return substr_count($haystack, $needle);

		return substr_count($haystack, $needle, $offset);
	}
}

?>