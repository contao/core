<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * This file contains some UTF-8 helper functions that allow to run Contao
 * without the mbstring extension. It is based on the UTF-8 library written
 * by Andreas Gohr <andi@splitbrain.org> which is part of the DokuWiki project.
 * Visit http://www.splitbrain.org/projects/dokuwiki to get the original file.
 *
 * This library supports the following functions:
 * - utf8_chr
 * - utf8_ord
 * - utf8_convert_encoding
 * - utf8_decode_entities
 * - utf8_detect_encoding
 * - utf8_romanize
 * - utf8_strlen
 * - utf8_strpos
 * - utf8_strrchr
 * - utf8_strrpos
 * - utf8_strstr
 * - utf8_strtolower
 * - utf8_strtoupper
 * - utf8_substr
 * - utf8_ucfirst
 * - utf8_str_split
 *
 * A few functions are based on the UTF-8 library written by Niels Leenheer
 * and Andy Matsubara which is part of the Zen Photo web photo album project.
 * Visit http://www.zenphoto.org to get the original file.
 */


/**
 * Check whether we can use mbstring
 */
define('USE_MBSTRING', function_exists('mb_strlen'));

if (USE_MBSTRING)
	mb_internal_encoding('UTF-8');


/**
 * Return a specific character
 *
 * Unicode version of chr() that handles UTF-8 characters. It is basically
 * used as callback function for utf8_decode_entities().
 *
 * @param integer $dec
 *
 * @return string
 */
function utf8_chr($dec)
{
	if ($dec < 128)
		return chr($dec);

	if ($dec < 2048)
		return chr(($dec >> 6) + 192) . chr(($dec & 63) + 128);

	if ($dec < 65536)
		return chr(($dec >> 12) + 224) . chr((($dec >> 6) & 63) + 128) . chr(($dec & 63) + 128);

	if ($dec < 2097152)
		return chr(($dec >> 18) + 240) . chr((($dec >> 12) & 63) + 128) . chr((($dec >> 6) & 63) + 128) . chr(($dec & 63) + 128);

	return '';
}


/**
 * Return the ASCII value of a character
 *
 * Unicode version of ord() that handles UTF-8 characters. The function has
 * been published by R. Rajesh Jeba Anbiah on php.net.
 *
 * @param string $str
 *
 * @return integer
 */
function utf8_ord($str)
{
	if (ord($str{0}) >= 0 && ord($str{0}) <= 127)
		return ord($str{0});

	if (ord($str{0}) >= 192 && ord($str{0}) <= 223)
		return (ord($str{0})-192)*64 + (ord($str{1})-128);

	if (ord($str{0}) >= 224 && ord($str{0}) <= 239)
		return (ord($str{0})-224)*4096 + (ord($str{1})-128)*64 + (ord($str{2})-128);

	if (ord($str{0}) >= 240 && ord($str{0}) <= 247)
		return (ord($str{0})-240)*262144 + (ord($str{1})-128)*4096 + (ord($str{2})-128)*64 + (ord($str{3})-128);

	if (ord($str{0}) >= 248 && ord($str{0}) <= 251)
		return (ord($str{0})-248)*16777216 + (ord($str{1})-128)*262144 + (ord($str{2})-128)*4096 + (ord($str{3})-128)*64 + (ord($str{4})-128);

	if (ord($str{0}) >= 252 && ord($str{0}) <= 253)
		return (ord($str{0})-252)*1073741824 + (ord($str{1})-128)*16777216 + (ord($str{2})-128)*262144 + (ord($str{3})-128)*4096 + (ord($str{4})-128)*64 + (ord($str{5})-128);

	if (ord($str{0}) >= 254 && ord($str{0}) <= 255) //error
		return false;

	return 0;
}


/**
 * Convert character encoding
 *
 * Use utf8_decode() to convert UTF-8 to ISO-8859-1, otherwise use iconv()
 * or mb_convert_encoding(). Return the original string if none of these
 * libraries is available.
 *
 * @param string $str
 * @param string $to
 * @param string $from
 *
 * @return string
 */
function utf8_convert_encoding($str, $to, $from=null)
{
	if ($str == '')
		return '';

	if (!$from)
		$from = utf8_detect_encoding($str);

	if ($from == $to)
		return $str;

	if ($from == 'UTF-8' && $to == 'ISO-8859-1')
		return utf8_decode($str);

	if ($from == 'ISO-8859-1' && $to == 'UTF-8')
		return utf8_encode($str);

	if (USE_MBSTRING)
	{
		@mb_substitute_character('none');

		return @mb_convert_encoding($str, $to, $from);
	}

	if (function_exists('iconv'))
	{
		if (strlen($iconv = @iconv($from, $to . '//IGNORE', $str)))
			return $iconv;

		return @iconv($from, $to, $str);
	}

	return $str;
}


/**
 * Convert all unicode entities to their applicable characters
 *
 * Calls utf8_chr() to convert unicode entities. HTML entities like '&nbsp;'
 * or '&quot;' will not be decoded.
 *
 * @param string $str
 *
 * @return string
 */
function utf8_decode_entities($str)
{
	$str = preg_replace_callback('~&#x([0-9a-f]+);~i', 'utf8_hexchr_callback', $str);
	$str = preg_replace_callback('~&#([0-9]+);~', 'utf8_chr_callback', $str);

	return $str;
}


/**
 * Callback function for utf8_decode_entities
 *
 * @param array $matches
 *
 * @return string
 */
function utf8_chr_callback($matches)
{
	return utf8_chr($matches[1]);
}


/**
 * Callback function for utf8_decode_entities
 *
 * @param array $matches
 *
 * @return string
 */
function utf8_hexchr_callback($matches)
{
	return utf8_chr(hexdec($matches[1]));
}


/**
 * Detect the encoding of a string
 *
 * Use mb_detect_encoding() if available since it seems to be about 20 times
 * faster than using ereg() or preg_match().
 *
 * @param string $str
 *
 * @return string
 */
function utf8_detect_encoding($str)
{
	if (USE_MBSTRING)
		return mb_detect_encoding($str, array('ASCII', 'ISO-2022-JP', 'UTF-8', 'EUC-JP', 'ISO-8859-1'));

	if (!preg_match("/[\x80-\xFF]/", $str))
	{
		if (!preg_match("/\x1B/", $str))
			return 'ASCII';

		return 'ISO-2022-JP';
	}

	if (preg_match("/^([\x01-\x7F]|[\xC0-\xDF][\x80-\xBF]|[\xE0-\xEF][\x80-\xBF][\x80-\xBF])+$/", $str) == 1)
		return 'UTF-8';

	if (preg_match("/^([\x01-\x7F]|\x8E[\xA0-\xDF]|\x8F[xA1-\xFE][\xA1-\xFE]|[\xA1-\xFE][\xA1-\xFE])+$/", $str) == 1)
		return 'EUC-JP';

	return 'ISO-8859-1';
}


/**
 * Romanize a string
 *
 * Use the UTF-8 lookup table to replace non ascii characters with their
 * respective roman character.
 *
 * @param string $str
 *
 * @return string
 */
function utf8_romanize($str)
{
	if (utf8_detect_encoding($str) == 'ASCII')
		return $str;

	global $UTF8_LOOKUP_TABLE;

	if (!is_array($UTF8_LOOKUP_TABLE))
		require_once TL_ROOT . '/system/helper/utf8_lookup.php';

	return strtr(utf8_convert_encoding($str, 'UTF-8'), $UTF8_LOOKUP_TABLE['romanize']);
}


/**
 * Determine the number of characters of a string
 *
 * Use mb_strlen() if available since it seems to be the fastes way to
 * determine the string length. Otherwise decode the string (will convert
 * non ISO-8859-1 characters to '?') and use strlen().
 *
 * @param string $str
 *
 * @return integer
 */
function utf8_strlen($str)
{
	if (USE_MBSTRING)
		return mb_strlen($str);

	return strlen(utf8_decode($str));
}


/**
 * Find the position of the first occurence of a string in another string
 *
 * Use mb_strpos() if available. Otherwise combine strpos() and utf8_strlen()
 * to detect the numeric position of the first occurrence.
 *
 * @param string  $haystack
 * @param string  $needle
 * @param integer $offset
 *
 * @return integer
 */
function utf8_strpos($haystack, $needle, $offset=0)
{
	if (USE_MBSTRING)
	{
		if ($offset === 0)
			return mb_strpos($haystack, $needle);

		return mb_strpos($haystack, $needle, $offset);
	}

	$comp = 0;
	$length = null;

	while ($length === null || $length < $offset)
	{
		$pos = strpos($haystack, $needle, $offset + $comp);

		if ($pos === false)
			return false;

		$length = utf8_strlen(substr($haystack, 0, $pos));

		if ($length < $offset)
			$comp = $pos - $length;
	}

	return $length;
}


/**
 * Find the last occurrence of a character in a string
 *
 * Use mb_strrchr() if available since it seems to be about eight times
 * faster than combining utf8_substr() and utf8_strrpos().
 *
 * @param string $haystack
 * @param string $needle
 *
 * @return string
 */
function utf8_strrchr($haystack, $needle)
{
	if (USE_MBSTRING)
		return mb_strrchr($haystack, $needle);

	$pos = utf8_strrpos($haystack, $needle);

	if ($pos === false)
		return false;

	return utf8_substr($haystack, $pos);
}


/**
 * Find the position of the last occurrence of a string in another string
 *
 * Use mb_strrpos() if available since it is about twice as fast as our
 * workaround. Otherwise use utf8_strlen() to determine the position.
 *
 * @param string $haystack
 * @param string $needle
 *
 * @return mixed
 */
function utf8_strrpos($haystack, $needle)
{
	if (USE_MBSTRING)
		return mb_strrpos($haystack, $needle);

	$pos = strrpos($haystack, $needle);

	if ($pos === false)
		return false;

	return utf8_strlen(substr($haystack, 0, $pos));
}


/**
 * Find the first occurrence of a string in another string
 *
 * Use mb_strstr() if available since it seems to be about eight times
 * faster than combining utf8_substr() and utf8_strpos().
 *
 * @param string $haystack
 * @param string $needle
 *
 * @return string
 */
function utf8_strstr($haystack, $needle)
{
	if (USE_MBSTRING)
		return mb_strstr($haystack, $needle);

	$pos = utf8_strpos($haystack, $needle);

	if ($pos === false)
		return false;

	return utf8_substr($haystack, $pos);
}


/**
 * Make a string lowercase
 *
 * Use mb_strtolower() if available, although our workaround does not seem
 * to be significantly slower.
 *
 * @param string $str
 *
 * @return string
 */
function utf8_strtolower($str)
{
	if (USE_MBSTRING)
		return mb_strtolower($str, utf8_detect_encoding($str));

	global $UTF8_LOOKUP_TABLE;

	if (!is_array($UTF8_LOOKUP_TABLE))
		require_once TL_ROOT . '/system/helper/utf8_lookup.php';

	return strtr($str, $UTF8_LOOKUP_TABLE['strtolower']);
}


/**
 * Make a string uppercase
 *
 * Use mb_strtoupper() if available, although our workaround does not seem
 * to be significantly slower.
 *
 * @param string $str
 *
 * @return string
 */
function utf8_strtoupper($str)
{
	if (USE_MBSTRING)
		return mb_strtoupper($str, utf8_detect_encoding($str));

	global $UTF8_LOOKUP_TABLE;

	if (!is_array($UTF8_LOOKUP_TABLE))
		require_once TL_ROOT . '/system/helper/utf8_lookup.php';

	return strtr($str, $UTF8_LOOKUP_TABLE['strtoupper']);
}


/**
 * Return substring of a string
 *
 * Use mb_substr() if available since it is about three times faster than
 * our workaround. Otherwise, use PCRE regular expressions with 'u' flag.
 * Thanks to Andreas Gohr <andi@splitbrain.org> for this wonderful algorithm
 * which is the fastes workaround I could find on the internet.
 *
 * @param string  $str
 * @param integer $start
 * @param integer $length
 *
 * @return string
 */
function utf8_substr($str, $start, $length=null)
{
	if (USE_MBSTRING)
	{
		if ($length === null)
			return mb_substr($str, $start);

		return mb_substr($str, $start, $length);
	}

	$str = (string) $str;
	$start = (int) $start;

	if ($length !== null)
		$length = (int) $length;

	// Handle trivial cases
	if ($length === 0)
		return '';

	if ($start < 0 && $length < 0 && $length < $start)
		return '';

	$start_pattern = '';
	$length_pattern = '';

	// Normalise -ve offsets
	if ($start < 0)
	{
		$strlen = strlen(utf8_decode($str));
		$start = $strlen + $start;

		if ($start < 0)
			$start = 0;
	}

	// Establish a pattern for offset
	if ($start > 0)
	{
		$Ox = (int) ($start / 65535);
		$Oy = $start % 65535;

		if ($Ox)
			$start_pattern = '(?:.{65535}){'.$Ox.'}';

		$start_pattern = '^(?:'.$start_pattern.'.{'.$Oy.'})';
	}

	// Anchor the pattern if offset == 0
	else
	{
		$start_pattern = '^';
	}

	// Establish a pattern for length
	if ($length === null)
	{
		$length_pattern = '(.*)$';
	}
	else
	{
		if (!isset($strlen))
			$strlen = strlen(utf8_decode($str));

		if ($start > $strlen)
			return '';

		if ($length > 0)
		{
			// Reduce any length that would go passed the end of the string
			$length = min($strlen-$start, $length);

			$Lx = (int) ($length / 65535);
			$Ly = $length % 65535;

			if ($Lx)
				$length_pattern = '(?:.{65535}){'.$Lx.'}';

			$length_pattern = '('.$length_pattern.'.{'.$Ly.'})';
		}
		else if ($length < 0)
		{
			if ($length < ($start - $strlen))
				return '';

			$Lx = (int) ((-$length) / 65535);
			$Ly = (-$length) % 65535;

			if ($Lx)
				$length_pattern = '(?:.{65535}){'.$Lx.'}';

			$length_pattern = '(.*)(?:'.$length_pattern.'.{'.$Ly.'})$';
		}
	}

	$match = array();

	if (!preg_match('#'.$start_pattern.$length_pattern.'#us', $str, $match))
		return '';

	return $match[1];
}


/**
 * Make sure the first letter is uppercase
 *
 * @param string $str
 *
 * @return string
 */
function utf8_ucfirst($str)
{
	return utf8_strtoupper(utf8_substr($str, 0, 1)) . utf8_substr($str, 1);
}


/**
 * Convert a string to an array
 *
 * Unicode version of str_split() that handles UTF-8 characters. The function
 * has been published by saeedco on php.net.
 *
 * @param string $str
 *
 * @return array
 */
function utf8_str_split($str)
{
	$array = array();

	for ($i=0; $i<strlen($str);)
	{
		$split = 1;
		$value = ord($str[$i]);
		$key = null;

		if($value >= 192 && $value <= 223)
			$split=2;
		elseif($value >= 224 && $value <= 239)
			$split=3;
		elseif($value >= 240 && $value <= 247)
			$split=4;

		for ($j=0; $j<$split; $j++,$i++)
		{
			$key .= $str[$i];
		}

		array_push($array, $key);
	}

	return $array;
}
