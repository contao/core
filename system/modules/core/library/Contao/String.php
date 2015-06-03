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
 * Provides string manipulation methods
 *
 * Usage:
 *
 *     $short = String::substr($str, 32);
 *     $html  = String::substrHtml($str, 32);
 *     $xhtml = String::toXhtml($html5);
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class String
{

	/**
	 * Object instance (Singleton)
	 * @var \String
	 */
	protected static $objInstance;


	/**
	 * Shorten a string to a given number of characters
	 *
	 * The function preserves words, so the result might be a bit shorter or
	 * longer than the number of characters given. It strips all tags.
	 *
	 * @param string  $strString        The string to shorten
	 * @param integer $intNumberOfChars The target number of characters
	 * @param string  $strEllipsis      An optional ellipsis to append to the shortened string
	 *
	 * @return string The shortened string
	 */
	public static function substr($strString, $intNumberOfChars, $strEllipsis=' …')
	{
		$strString = preg_replace('/[\t\n\r]+/', ' ', $strString);
		$strString = strip_tags($strString);

		if (utf8_strlen($strString) <= $intNumberOfChars)
		{
			return $strString;
		}

		$intCharCount = 0;
		$arrWords = array();
		$arrChunks = preg_split('/\s+/', $strString);
		$blnAddEllipsis = false;

		foreach ($arrChunks as $strChunk)
		{
			$intCharCount += utf8_strlen(static::decodeEntities($strChunk));

			if ($intCharCount++ <= $intNumberOfChars)
			{
				$arrWords[] = $strChunk;
				continue;
			}

			// If the first word is longer than $intNumberOfChars already, shorten it
			// with utf8_substr() so the method does not return an empty string.
			if (empty($arrWords))
			{
				$arrWords[] = utf8_substr($strChunk, 0, $intNumberOfChars);
			}

			if ($strEllipsis !== false)
			{
				$blnAddEllipsis = true;
			}

			break;
		}

		// Backwards compatibility
		if ($strEllipsis === true)
		{
			$strEllipsis = ' …';
		}

		return implode(' ', $arrWords) . ($blnAddEllipsis ? $strEllipsis : '');
	}


	/**
	 * Shorten a HTML string to a given number of characters
	 *
	 * The function preserves words, so the result might be a bit shorter or
	 * longer than the number of characters given. It preserves allowed tags.
	 *
	 * @param string  $strString        The string to shorten
	 * @param integer $intNumberOfChars The target number of characters
	 *
	 * @return string The shortened HTML string
	 */
	public static function substrHtml($strString, $intNumberOfChars)
	{
		$strReturn = '';
		$intCharCount = 0;
		$arrOpenTags = array();
		$arrTagBuffer = array();
		$arrEmptyTags = array('area', 'base', 'br', 'col', 'hr', 'img', 'input', 'frame', 'link', 'meta', 'param');

		$strString = preg_replace('/[\t\n\r]+/', ' ', $strString);
		$strString = strip_tags($strString, \Config::get('allowedTags'));
		$strString = preg_replace('/ +/', ' ', $strString);

		// Seperate tags and text
		$arrChunks = preg_split('/(<[^>]+>)/', $strString, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);

		for ($i=0, $c=count($arrChunks); $i<$c; $i++)
		{
			// Buffer tags to include them later
			if (preg_match('/<([^>]+)>/', $arrChunks[$i]))
			{
				$arrTagBuffer[] = $arrChunks[$i];
				continue;
			}

			$buffer = $arrChunks[$i];

			// Get the substring of the current text
			if (($arrChunks[$i] = static::substr($arrChunks[$i], ($intNumberOfChars - $intCharCount), false)) == false)
			{
				break;
			}

			$blnModified = ($buffer !== $arrChunks[$i]);
			$intCharCount += utf8_strlen(static::decodeEntities($arrChunks[$i]));

			if ($intCharCount <= $intNumberOfChars)
			{
				foreach ($arrTagBuffer as $strTag)
				{
					$strTagName = strtolower(trim($strTag));

					// Extract the tag name (see #5669)
					if (($pos = strpos($strTagName, ' ')) !== false)
					{
						$strTagName = substr($strTagName, 1, $pos - 1);
					}
					else
					{
						$strTagName = substr($strTagName, 1, -1);
					}

					// Skip empty tags
					if (in_array($strTagName, $arrEmptyTags))
					{
						continue;
					}

					// Store opening tags in the open_tags array
					if (strncmp($strTagName, '/', 1) !== 0)
					{
						if (!empty($arrChunks[$i]) || $i<$c)
						{
							$arrOpenTags[] = $strTagName;
						}

						continue;
					}

					// Closing tags will be removed from the "open tags" array
					if (!empty($arrChunks[$i]) || $i<$c)
					{
						$arrOpenTags = array_values($arrOpenTags);

						for ($j=count($arrOpenTags)-1; $j>=0; $j--)
						{
							if ($strTagName == '/' . $arrOpenTags[$j])
							{
								unset($arrOpenTags[$j]);
								break;
							}
						}
					}
				}

				// If the current chunk contains text, add tags and text to the return string
				if (strlen($arrChunks[$i]) || $i<$c)
				{
					$strReturn .= implode('', $arrTagBuffer) . $arrChunks[$i];
				}

				// Stop after the first shortened chunk (see #7311)
				if ($blnModified)
				{
					break;
				}

				$arrTagBuffer = array();
				continue;
			}

			break;
		}

		// Close all remaining open tags
		krsort($arrOpenTags);

		foreach ($arrOpenTags as $strTag)
		{
			$strReturn .= '</' . $strTag . '>';
		}

		return trim($strReturn);
	}


	/**
	 * Decode all entities
	 *
	 * @param string  $strString     The string to decode
	 * @param integer $strQuoteStyle The quote style (defaults to ENT_COMPAT)
	 * @param string  $strCharset    An optional charset
	 *
	 * @return string The decoded string
	 */
	public static function decodeEntities($strString, $strQuoteStyle=ENT_COMPAT, $strCharset=null)
	{
		if ($strString == '')
		{
			return '';
		}

		if ($strCharset === null)
		{
			$strCharset = \Config::get('characterSet');
		}

		$strString = preg_replace('/(&#*\w+)[\x00-\x20]+;/i', '$1;', $strString);
		$strString = preg_replace('/(&#x*)([0-9a-f]+);/i', '$1$2;', $strString);

		return html_entity_decode($strString, $strQuoteStyle, $strCharset);
	}


	/**
	 * Restore basic entities
	 *
	 * @param string $strBuffer The string with the tags to be replaced
	 *
	 * @return string The string with the original entities
	 */
	public static function restoreBasicEntities($strBuffer)
	{
		return str_replace(array('[&]', '[&amp;]', '[lt]', '[gt]', '[nbsp]', '[-]'), array('&amp;', '&amp;', '&lt;', '&gt;', '&nbsp;', '&shy;'), $strBuffer);
	}


	/**
	 * Censor a single word or an array of words within a string
	 *
	 * @param string $strString  The string to censor
	 * @param mixed  $varWords   A string or array or words to replace
	 * @param string $strReplace An optional replacement string
	 *
	 * @return string The cleaned string
	 */
	public static function censor($strString, $varWords, $strReplace='')
	{
		foreach ((array) $varWords as $strWord)
		{
			$strString = preg_replace('/\b(' . str_replace('\*', '\w*?', preg_quote($strWord, '/')) . ')\b/i', $strReplace, $strString);
		}

		return $strString;
	}


	/**
	 * Encode all e-mail addresses within a string
	 *
	 * @param string $strString The string to encode
	 *
	 * @return string The encoded string
	 */
	public static function encodeEmail($strString)
	{
		$arrEmails = array();
		preg_match_all('/\w([-._\w]*\w)?@\w([-._\w]*\w)?\.\w{2,6}/', $strString, $arrEmails);

		foreach ((array) $arrEmails[0] as $strEmail)
		{
			$strEncoded = '';

			for($i=0; $i<strlen($strEmail); ++$i)
			{
				$blnHex = rand(0, 1);

				if ($blnHex)
				{
					$strEncoded .= sprintf('&#x%X;', ord($strEmail{$i}));
				}
				else
				{
					$strEncoded .= sprintf('&#%s;', ord($strEmail{$i}));
				}
			}

			$strString = str_replace($strEmail, $strEncoded, $strString);
		}

		return str_replace('mailto:', '&#109;&#97;&#105;&#108;&#116;&#111;&#58;', $strString);
	}


	/**
	 * Split a friendly-name e-address and return name and e-mail as array
	 *
	 * @param string $strEmail A friendly-name e-mail address
	 *
	 * @return array An array with name and e-mail address
	 */
	public static function splitFriendlyEmail($strEmail)
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
	 * Wrap words after a particular number of characers
	 *
	 * @param string  $strString The string to wrap
	 * @param integer $strLength The number of characters to wrap after
	 * @param string  $strBreak  An optional break character
	 *
	 * @return string The wrapped string
	 */
	public static function wordWrap($strString, $strLength=75, $strBreak="\n")
	{
		return wordwrap($strString, $strLength, $strBreak);
	}


	/**
	 * Highlight a phrase within a string
	 *
	 * @param string $strString     The string
	 * @param string $strPhrase     The phrase to highlight
	 * @param string $strOpeningTag The opening tag (defaults to <strong>)
	 * @param string $strClosingTag The closing tag (defaults to </strong>)
	 *
	 * @return string The highlighted string
	 */
	public static function highlight($strString, $strPhrase, $strOpeningTag='<strong>', $strClosingTag='</strong>')
	{
		if ($strString == '' || $strPhrase == '')
		{
			return $strString;
		}

		return preg_replace('/(' . preg_quote($strPhrase, '/') . ')/i', $strOpeningTag . '\\1' . $strClosingTag, $strString);
	}


	/**
	 * Split a string of comma separated values
	 *
	 * @param string $strString    The string to split
	 * @param string $strDelimiter An optional delimiter
	 *
	 * @return array The string chunks
	 */
	public static function splitCsv($strString, $strDelimiter=',')
	{
		$arrValues = preg_split('/'.$strDelimiter.'(?=(?:[^"]*"[^"]*")*(?![^"]*"))/', $strString);

		foreach ($arrValues as $k=>$v)
		{
			$arrValues[$k] = trim($v, ' "');
		}

		return $arrValues;
	}


	/**
	 * Convert a string to XHTML
	 *
	 * @param string $strString The HTML5 string
	 *
	 * @return string The XHTML string
	 */
	public static function toXhtml($strString)
	{
		$arrPregReplace = array
		(
			'/<(br|hr|img)([^>]*)>/i' => '<$1$2 />', // Close stand-alone tags
			'/ border="[^"]*"/'       => ''          // Remove deprecated attributes
		);

		$arrStrReplace = array
		(
			'/ />'             => ' />',        // Fix incorrectly closed tags
			'<b>'              => '<strong>',   // Replace <b> with <strong>
			'</b>'             => '</strong>',
			'<i>'              => '<em>',       // Replace <i> with <em>
			'</i>'             => '</em>',
			'<u>'              => '<span style="text-decoration:underline">',
			'</u>'             => '</span>',
			' target="_self"'  => '',
			' target="_blank"' => ' onclick="return !window.open(this.href)"'
		);

		$strString = preg_replace(array_keys($arrPregReplace), array_values($arrPregReplace), $strString);
		$strString = str_ireplace(array_keys($arrStrReplace), array_values($arrStrReplace), $strString);

		return $strString;
	}


	/**
	 * Convert a string to HTML5
	 *
	 * @param string $strString The XHTML string
	 *
	 * @return string The HTML5 string
	 */
	public static function toHtml5($strString)
	{
		$arrPregReplace = array
		(
			'/<(br|hr|img)([^>]*) \/>/i'                  => '<$1$2>',             // Close stand-alone tags
			'/ (cellpadding|cellspacing|border)="[^"]*"/' => '',                   // Remove deprecated attributes
			'/ rel="lightbox(\[([^\]]+)\])?"/'            => ' data-lightbox="$2"' // see #4073
		);

		$arrStrReplace = array
		(
			'<u>'                                              => '<span style="text-decoration:underline">',
			'</u>'                                             => '</span>',
			' target="_self"'                                  => '',
			' onclick="window.open(this.href); return false"'  => ' target="_blank"',
			' onclick="window.open(this.href);return false"'   => ' target="_blank"',
			' onclick="window.open(this.href); return false;"' => ' target="_blank"'
		);

		$strString = preg_replace(array_keys($arrPregReplace), array_values($arrPregReplace), $strString);
		$strString = str_ireplace(array_keys($arrStrReplace), array_values($arrStrReplace), $strString);

		return $strString;
	}


	/**
	 * Parse simple tokens that can be used to personalize newsletters
	 *
	 * @param string $strString The string to be parsed
	 * @param array  $arrData   The replacement data
	 *
	 * @return string The converted string
	 *
	 * @throws \Exception If $strString cannot be parsed
	 */
	public static function parseSimpleTokens($strString, $arrData)
	{
		$strReturn = '';

		// Remove any unwanted tags (especially PHP tags)
		$strString = strip_tags($strString, \Config::get('allowedTags'));
		$arrTags = preg_split('/(\{[^\}]+\})/', $strString, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);

		// Replace the tags
		foreach ($arrTags as $strTag)
		{
			if (strncmp($strTag, '{if', 3) === 0)
			{
				$strReturn .= preg_replace('/\{if ([A-Za-z0-9_]+)([=!<>]+)([^;$\(\)\[\]\}]+).*\}/i', '<?php if ($arrData[\'$1\'] $2 $3): ?>', $strTag);
			}
			elseif (strncmp($strTag, '{elseif', 7) === 0)
			{
				$strReturn .= preg_replace('/\{elseif ([A-Za-z0-9_]+)([=!<>]+)([^;$\(\)\[\]\}]+).*\}/i', '<?php elseif ($arrData[\'$1\'] $2 $3): ?>', $strTag);
			}
			elseif (strncmp($strTag, '{else', 5) === 0)
			{
				$strReturn .= '<?php else: ?>';
			}
			elseif (strncmp($strTag, '{endif', 6) === 0)
			{
				$strReturn .= '<?php endif; ?>';
			}
			else
			{
				$strReturn .= $strTag;
			}
		}

		// Replace tokens
		$strReturn = str_replace('?><br />', '?>', $strReturn);
		$strReturn = preg_replace('/##([A-Za-z0-9_]+)##/i', '<?php echo $arrData[\'$1\']; ?>', $strReturn);
		$strReturn = str_replace("]; ?>\n", '] . "\n"; ?>' . "\n", $strReturn); // see #7178

		// Eval the code
		ob_start();
		$blnEval = eval("?>" . $strReturn);
		$strReturn = ob_get_contents();
		ob_end_clean();

		// Throw an exception if there is an eval() error
		if ($blnEval === false)
		{
			throw new \Exception("Error parsing simple tokens ($strReturn)");
		}

		// Return the evaled code
		return $strReturn;
	}


	/**
	 * Convert a UUID string to binary data
	 *
	 * @param string $uuid The UUID string
	 *
	 * @return string The binary data
	 */
	public static function uuidToBin($uuid)
	{
		return hex2bin(str_replace('-', '', $uuid));
	}


	/**
	 * Get a UUID string from binary data
	 *
	 * @param string $data The binary data
	 *
	 * @return string The UUID string
	 */
	public static function binToUuid($data)
	{
		return implode('-', unpack('H8time_low/H4time_mid/H4time_high/H4clock_seq/H12node', $data));
	}


	/**
	 * Convert file paths inside "src" attributes to insert tags
	 *
	 * @param string $data The markup string
	 *
	 * @return string The markup with file paths converted to insert tags
	 */
	public static function srcToInsertTag($data)
	{
		$return = '';
		$paths = preg_split('/((src|href)="([^"]+)")/i', $data, -1, PREG_SPLIT_DELIM_CAPTURE);

		for ($i=0, $c=count($paths); $i<$c; $i=$i+4)
		{
			$return .= $paths[$i];

			if (!isset($paths[$i+1]))
			{
				continue;
			}

			$file = \FilesModel::findByPath($paths[$i+3]);

			if ($file !== null)
			{
				$return .= $paths[$i+2] . '="{{file::' . static::binToUuid($file->uuid) . '}}"';
			}
			else
			{
				$return .= $paths[$i+2] . '="' . $paths[$i+3] . '"';
			}
		}

		return $return;
	}


	/**
	 * Convert insert tags inside "src" attributes to file paths
	 *
	 * @param string $data The markup string
	 *
	 * @return string The markup with insert tags converted to file paths
	 */
	public static function insertTagToSrc($data)
	{
		$return = '';
		$paths = preg_split('/((src|href)="([^"]*)\{\{file::([^"\}]+)\}\}")/i', $data, -1, PREG_SPLIT_DELIM_CAPTURE);

		for ($i=0, $c=count($paths); $i<$c; $i=$i+5)
		{
			$return .= $paths[$i];

			if (!isset($paths[$i+1]))
			{
				continue;
			}

			$file = \FilesModel::findByUuid($paths[$i+4]);

			if ($file !== null)
			{
				$return .= $paths[$i+2] . '="' . $paths[$i+3] . $file->path . '"';
			}
			else
			{
				$return .= $paths[$i+2] . '="' . $paths[$i+3] . $paths[$i+4] . '"';
			}
		}

		return $return;
	}


	/**
	 * Sanitize a file name
	 *
	 * @param string $strName The file name
	 *
	 * @return string The sanitized file name
	 */
	public static function sanitizeFileName($strName)
	{
		// Remove invisible control characters and unused code points
		$strName = preg_replace('/[\pC]/u', '', $strName);

		if ($strName === null)
		{
			throw new \InvalidArgumentException('The file name could not be sanitzied');
		}

		// Remove special characters not supported on e.g. Windows
		$strName = str_replace(array('\\', '/', ':', '*', '?', '"', '<', '>', '|'), '-', $strName);

		return $strName;
	}


	/**
	 * Resolve a flagged URL such as assets/js/core.js|static|10184084
	 *
	 * @param string $url The URL
	 *
	 * @return \stdClass The options object
	 */
	public static function resolveFlaggedUrl(&$url)
	{
		$options = new \stdClass();

		// Defaults
		$options->static = false;
		$options->media  = null;
		$options->mtime  = null;
		$options->async  = false;

		$chunks = explode('|', $url);

		// Remove the flags from the URL
		$url = $chunks[0];

		for ($i=1; $i<count($chunks); $i++)
		{
			if (empty($chunks[$i]))
			{
				continue;
			}

			switch ($chunks[$i])
			{
				case 'static':
					$options->static = true;
					break;

				case 'async':
					$options->async = true;
					break;

				case is_numeric($chunks[$i]):
					$options->mtime = $chunks[$i];
					break;

				default:
					$options->media = $chunks[$i];
					break;
			}
		}

		return $options;
	}


	/**
	 * Prevent direct instantiation (Singleton)
	 *
	 * @deprecated String is now a static class
	 */
	protected function __construct() {}


	/**
	 * Prevent cloning of the object (Singleton)
	 *
	 * @deprecated String is now a static class
	 */
	final public function __clone() {}


	/**
	 * Return the object instance (Singleton)
	 *
	 * @return \String The object instance
	 *
	 * @deprecated String is now a static class
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
