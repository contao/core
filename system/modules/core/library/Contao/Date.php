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
 * Converts dates and date format string
 *
 * The class converts arbitrary date strings to Unix timestamps and provides
 * extended information like the begin or end of the day, week, month or year.
 *
 * Usage:
 *
 *     $date = new Date();
 *     echo $date->datim;
 *
 *     $date = new Date('2011-09-18', 'Y-m-d');
 *     echo $date->monthBegin;
 *
 *     Date::formatToJs('m/d/Y H:i');
 *
 * @property integer $tstamp     The Unix timestamp
 * @property string  $date       The formatted date
 * @property string  $time       The formatted time
 * @property string  $datim      The formatted date and time
 * @property integer $dayBegin   The beginning of the current day
 * @property integer $dayEnd     The end of the current day
 * @property integer $monthBegin The beginning of the current month
 * @property integer $monthEnd   The end of the current month
 * @property integer $yearBegin  The beginning of the current year
 * @property integer $yearEnd    The end of the current year
 * @property string  $format     The date format string
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Date
{

	/**
	 * Date string
	 * @var int
	 */
	protected $strDate;

	/**
	 * Format string
	 * @var string
	 */
	protected $strFormat;

	/**
	 * Date range
	 * @var array
	 */
	protected $arrRange = array();


	/**
	 * Set the object properties
	 *
	 * @param integer $strDate   An optional date string
	 * @param string  $strFormat An optional format string
	 */
	public function __construct($strDate=null, $strFormat=null)
	{
		$this->strDate = ($strDate !== null) ? $strDate : time();
		$this->strFormat = ($strFormat !== null) ? $strFormat : static::getNumericDateFormat();

		if (!preg_match('/^\-?[0-9]+$/', $this->strDate) || preg_match('/^[a-zA-Z]+$/', $this->strFormat))
		{
			$this->dateToUnix();
		}
	}


	/**
	 * Return an object property
	 *
	 * @param string $strKey The property name
	 *
	 * @return mixed|null The property value
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'tstamp':
			case 'timestamp':
				return $this->strDate;
				break;

			case 'date':
				return static::parse(static::getNumericDateFormat(), $this->strDate);
				break;

			case 'time':
				return static::parse(static::getNumericTimeFormat(), $this->strDate);
				break;

			case 'datim':
				return static::parse(static::getNumericDatimFormat(), $this->strDate);
				break;

			case 'dayBegin':
				$this->createDateRanges();

				return $this->arrRange['day']['begin'];
				break;

			case 'dayEnd':
				$this->createDateRanges();

				return $this->arrRange['day']['end'];
				break;

			case 'monthBegin':
				$this->createDateRanges();

				return $this->arrRange['month']['begin'];
				break;

			case 'monthEnd':
				$this->createDateRanges();

				return $this->arrRange['month']['end'];
				break;

			case 'yearBegin':
				$this->createDateRanges();

				return $this->arrRange['year']['begin'];
				break;

			case 'yearEnd':
				$this->createDateRanges();

				return $this->arrRange['year']['end'];
				break;

			case 'format':
				return $this->strFormat;
				break;
		}

		return null;
	}


	/**
	 * Create the date ranges
	 */
	protected function createDateRanges()
	{
		if (!empty($this->arrRange))
		{
			return;
		}

		$intYear = date('Y', $this->strDate);
		$intMonth = date('m', $this->strDate);
		$intDay = date('d', $this->strDate);

		$this->arrRange['day']['begin'] = mktime(0, 0, 0, $intMonth, $intDay, $intYear);
		$this->arrRange['day']['end'] = mktime(23, 59, 59, $intMonth, $intDay, $intYear);
		$this->arrRange['month']['begin'] = mktime(0, 0, 0, $intMonth, 1, $intYear);
		$this->arrRange['month']['end'] = mktime(23, 59, 59, $intMonth, date('t', $this->strDate), $intYear);
		$this->arrRange['year']['begin'] = mktime(0, 0, 0, 1, 1, $intYear);
		$this->arrRange['year']['end'] = mktime(23, 59, 59, 12, 31, $intYear);
	}


	/**
	 * Return the begin of the week as timestamp
	 *
	 * @param integer $intStartDay The week start day
	 *
	 * @return integer The Unix timestamp
	 */
	public function getWeekBegin($intStartDay=0)
	{
		$intOffset = date('w', $this->strDate) - $intStartDay;

		if ($intOffset < 0)
		{
			$intOffset += 7;
		}

		return strtotime('-' . $intOffset . ' days', $this->strDate);
	}


	/**
	 * Return the end of the week as timestamp
	 *
	 * @param integer $intStartDay The week start day
	 *
	 * @return integer The Unix timestamp
	 */
	public function getWeekEnd($intStartDay=0)
	{
		return strtotime('+1 week', $this->getWeekBegin($intStartDay)) - 1;
	}


	/**
	 * Return a regular expression to check a date
	 *
	 * @param string $strFormat An optional format string
	 *
	 * @return string The regular expression string
	 *
	 * @throws \Exception If $strFormat is invalid
	 */
	public static function getRegexp($strFormat=null)
	{
		if ($strFormat === null)
		{
			$strFormat = static::getNumericDateFormat();
		}

		if (!static::isNumericFormat($strFormat))
		{
			throw new \Exception(sprintf('Invalid date format "%s"', $strFormat));
		}

		return preg_replace_callback('/[a-zA-Z]/', function($matches)
			{
				// Thanks to Christian Labuda
				$arrRegexp = array
				(
					'a' => '(?P<a>am|pm)',
					'A' => '(?P<A>AM|PM)',
					'd' => '(?P<d>0[1-9]|[12][0-9]|3[01])',
					'g' => '(?P<g>[1-9]|1[0-2])',
					'G' => '(?P<G>[0-9]|1[0-9]|2[0-3])',
					'h' => '(?P<h>0[1-9]|1[0-2])',
					'H' => '(?P<H>[01][0-9]|2[0-3])',
					'i' => '(?P<i>[0-5][0-9])',
					'j' => '(?P<j>[1-9]|[12][0-9]|3[01])',
					'm' => '(?P<m>0[1-9]|1[0-2])',
					'n' => '(?P<n>[1-9]|1[0-2])',
					's' => '(?P<s>[0-5][0-9])',
					'Y' => '(?P<Y>[0-9]{4})',
					'y' => '(?P<y>[0-9]{2})',
				);

				return isset($arrRegexp[$matches[0]]) ? $arrRegexp[$matches[0]] : $matches[0];
			}
		, preg_quote($strFormat));
	}


	/**
	 * Return an input format string for a particular date (e.g. YYYY-MM-DD)
	 *
	 * @param string $strFormat An optional format string
	 *
	 * @return string The input format string
	 *
	 * @throws \Exception If $strFormat is invalid
	 */
	public static function getInputFormat($strFormat=null)
	{
		if ($strFormat === null)
		{
			$strFormat = static::getNumericDateFormat();
		}

		if (!static::isNumericFormat($strFormat))
		{
			throw new \Exception(sprintf('Invalid date format "%s"', $strFormat));
		}

		$arrCharacterMapper = array
		(
			'a' => 'am',
			'A' => 'AM',
			'd' => 'DD',
			'j' => 'D',
			'm' => 'MM',
			'n' => 'M',
			'y' => 'YY',
			'Y' => 'YYYY',
			'h' => 'hh',
			'H' => 'hh',
			'g' => 'h',
			'G' => 'h',
			'i' => 'mm',
			's' => 'ss',
		);

		$arrInputFormat = array();
		$arrCharacters = str_split($strFormat);

		foreach ($arrCharacters as $strCharacter)
		{
			if (isset($arrCharacterMapper[$strCharacter]))
			{
				$arrInputFormat[$strFormat] .= $arrCharacterMapper[$strCharacter];
			}
			else
			{
				$arrInputFormat[$strFormat] .= $strCharacter;
			}
		}

		return $arrInputFormat[$strFormat];
	}


	/**
	 * Convert a date string into a Unix timestamp using the format string
	 *
	 * @throws \Exception            If the format string is invalid
	 * @throws \OutOfBoundsException If the timestamp does not map to a valid date
	 */
	protected function dateToUnix()
	{
		if (!static::isNumericFormat($this->strFormat))
		{
			throw new \Exception(sprintf('Invalid date format "%s"', $this->strFormat));
		}

		$intCount  = 0;
		$intDay    = '';
		$intMonth  = '';
		$intYear   = '';
		$intHour   = '';
		$intMinute = '';
		$intSecond = '';

		$blnMeridiem = false;
		$blnCorrectHour = false;

		$arrCharacterMapper = array
		(
			'd' => 'intDay',
			'j' => 'intDay',
			'm' => 'intMonth',
			'n' => 'intMonth',
			'y' => 'intYear',
			'Y' => 'intYear',
			'h' => 'intHour',
			'H' => 'intHour',
			'g' => 'intHour',
			'G' => 'intHour',
			'i' => 'intMinute',
			's' => 'intSecond'
		);

		$arrCharacters = str_split($this->strFormat);

		foreach ($arrCharacters as $strCharacter)
		{
			$var = isset($arrCharacterMapper[$strCharacter]) ? $arrCharacterMapper[$strCharacter] : 'dummy';

			switch ($strCharacter)
			{
				case 'a':
				case 'A':
					$blnCorrectHour = true;
					$blnMeridiem = (strtolower(substr($this->strDate, $intCount, 2)) == 'pm') ? true : false;
					$intCount += 2;
					break;

				case 'd':
				case 'm':
				case 'y':
				case 'h':
				case 'H':
				case 'i':
				case 's':
					$$var .= substr($this->strDate, $intCount, 2);
					$intCount += 2;
					break;

				case 'j':
				case 'n':
				case 'g':
				case 'G':
					$$var .= substr($this->strDate, $intCount++, 1);

					if (preg_match('/[0-9]+/', substr($this->strDate, $intCount, 1)))
					{
						$$var .= substr($this->strDate, $intCount++, 1);
					}
					break;

				case 'Y':
					$$var .= substr($this->strDate, $intCount, 4);
					$intCount += 4;
					break;

				default:
					++$intCount;
					break;
			}
		}

		$intHour = (int) $intHour;

		if ($blnMeridiem)
		{
			$intHour += 12;
		}

		if ($blnCorrectHour && ($intHour == 12 || $intHour == 24))
		{
			$intHour -= 12;
		}

		if (!strlen($intMonth))
		{
			$intMonth = 1;
		}

		if (!strlen($intDay))
		{
			$intDay = 1;
		}

		if ($intYear == '')
		{
			$intYear = 1970;
		}

		// Validate the date (see #5086)
		if (checkdate($intMonth, $intDay, $intYear) === false)
		{
			throw new \OutOfBoundsException(sprintf('Invalid date "%s"', $this->strDate));
		}

		$this->strDate = mktime((int) $intHour, (int) $intMinute, (int) $intSecond, (int) $intMonth, (int) $intDay, (int) $intYear);
	}


	/**
	 * Convert a PHP format string into a JavaScript format string
	 *
	 * @param string $strFormat The PHP format string
	 *
	 * @return mixed The JavaScript format string
	 */
	public static function formatToJs($strFormat)
	{
		$chunks = str_split($strFormat);

		foreach ($chunks as $k=>$v)
		{
			switch ($v)
			{
				case 'D': $chunks[$k] = 'a'; break;
				case 'j': $chunks[$k] = 'e'; break;
				case 'l': $chunks[$k] = 'A'; break;
				case 'S': $chunks[$k] = 'o'; break;
				case 'F': $chunks[$k] = 'B'; break;
				case 'M': $chunks[$k] = 'b'; break;
				case 'a': $chunks[$k] = 'p'; break;
				case 'A': $chunks[$k] = 'p'; break;
				case 'g': $chunks[$k] = 'l'; break;
				case 'G': $chunks[$k] = 'k'; break;
				case 'h': $chunks[$k] = 'I'; break;
				case 'i': $chunks[$k] = 'M'; break;
				case 's': $chunks[$k] = 'S'; break;
				case 'U': $chunks[$k] = 's'; break;
			}
		}

		return preg_replace('/([a-zA-Z])/', '%$1', implode('', $chunks));
	}


	/**
	 * Check for a numeric date format
	 *
	 * @param string $strFormat The PHP format string
	 *
	 * @return boolean True if the date format is numeric
	 */
	public static function isNumericFormat($strFormat)
	{
		return !preg_match('/[BbCcDEeFfIJKkLlMNOoPpQqRrSTtUuVvWwXxZz]+/', $strFormat);
	}


	/**
	 * Return the numeric date format string
	 *
	 * @return string The numeric date format string
	 */
	public static function getNumericDateFormat()
	{
		if (TL_MODE == 'FE')
		{
			/** @var \PageModel $objPage */
			global $objPage;

			if ($objPage->dateFormat != '' && static::isNumericFormat($objPage->dateFormat))
			{
				return $objPage->dateFormat;
			}
		}

		return \Config::get('dateFormat');
	}


	/**
	 * Return the numeric time format string
	 *
	 * @return string The numeric time format string
	 */
	public static function getNumericTimeFormat()
	{
		if (TL_MODE == 'FE')
		{
			/** @var \PageModel $objPage */
			global $objPage;

			if ($objPage->timeFormat != '' && static::isNumericFormat($objPage->timeFormat))
			{
				return $objPage->timeFormat;
			}
		}

		return \Config::get('timeFormat');
	}


	/**
	 * Return the numeric datim format string
	 *
	 * @return string The numeric datim format string
	 */
	public static function getNumericDatimFormat()
	{
		if (TL_MODE == 'FE')
		{
			/** @var \PageModel $objPage */
			global $objPage;

			if ($objPage->datimFormat != '' && static::isNumericFormat($objPage->datimFormat))
			{
				return $objPage->datimFormat;
			}
		}

		return \Config::get('datimFormat');
	}


	/**
	 * Return a numeric format string depending on the regular expression name
	 *
	 * @return string The numeric format string
	 */
	public static function getFormatFromRgxp($strRgxp)
	{
		switch ($strRgxp)
		{
			case 'date':
				return static::getNumericDateFormat();
				break;

			case 'time':
				return static::getNumericTimeFormat();
				break;

			case 'datim':
				return static::getNumericDatimFormat();
				break;
		}

		return null;
	}


	/**
	 * Parse a date format string and translate textual representations
	 *
	 * @param string  $strFormat The date format string
	 * @param integer $intTstamp An optional timestamp
	 *
	 * @return string The textual representation of the date
	 */
	public static function parse($strFormat, $intTstamp=null)
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

		// HOOK: add custom logic (see #4260)
		if (isset($GLOBALS['TL_HOOKS']['parseDate']) && is_array($GLOBALS['TL_HOOKS']['parseDate']))
		{
			foreach ($GLOBALS['TL_HOOKS']['parseDate'] as $callback)
			{
				$strReturn = \System::importStatic($callback[0])->$callback[1]($strReturn, $strFormat, $intTstamp);
			}
		}

		return $strReturn;
	}


	/**
	 * Round a UNIX timestamp to the full minute
	 *
	 * @param integer $intTime The timestamp
	 *
	 * @return integer The rounded timestamp
	 */
	public static function floorToMinute($intTime=null)
	{
		if ($intTime === null)
		{
			$intTime = time();
		}

		return $intTime - ($intTime % 60);
	}
}
