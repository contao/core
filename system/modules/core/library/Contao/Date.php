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
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
class Date extends \System
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
	 * Formatted date
	 * @var string
	 */
	protected $strToDate;

	/**
	 * Formatted time
	 * @var string
	 */
	protected $strToTime;

	/**
	 * Formatted date and time
	 * @var string
	 */
	protected $strToDatim;

	/**
	 * Date range
	 * @var array
	 */
	protected $arrRange = array();


	/**
	 * Create the object properties and date ranges
	 * 
	 * @param integer $strDate   An optional date string
	 * @param string  $strFormat An optional format string
	 */
	public function __construct($strDate=null, $strFormat=null)
	{
		$this->strDate = ($strDate !== null) ? $strDate : time();
		$this->strFormat = ($strFormat !== null) ? $strFormat : $GLOBALS['TL_CONFIG']['dateFormat'];

		if (!preg_match('/^\-?[0-9]+$/', $this->strDate) || preg_match('/^[a-zA-Z]+$/', $this->strFormat))
		{
			$this->dateToUnix();
		}

		// Create the formatted dates
		$this->strToDate = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $this->strDate);
		$this->strToTime = $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $this->strDate);
		$this->strToDatim = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $this->strDate);

		$intYear = date('Y', $this->strDate);
		$intMonth = date('m', $this->strDate);
		$intDay = date('d', $this->strDate);

		// Create the date ranges
		$this->arrRange['day']['begin'] = mktime(0, 0, 0, $intMonth, $intDay, $intYear);
		$this->arrRange['day']['end'] = mktime(23, 59, 59, $intMonth, $intDay, $intYear);
		$this->arrRange['month']['begin'] = mktime(0, 0, 0, $intMonth, 1, $intYear);
		$this->arrRange['month']['end'] = mktime(23, 59, 59, $intMonth, date('t', $this->strDate), $intYear);
		$this->arrRange['year']['begin'] = mktime(0, 0, 0, 1, 1, $intYear);
		$this->arrRange['year']['end'] = mktime(23, 59, 59, 12, 31, $intYear);
	}


	/**
	 * Return an object property
	 * 
	 * Supported keys:
	 * 
	 * * timestamp:  the Unix timestamp
	 * * date:       the formatted date
	 * * time:       the formatted time
	 * * datim:      the formatted date and time
	 * * dayBegin:   the beginning of the current day
	 * * dayEnd:     the end of the current day
	 * * monthBegin: the beginning of the current month
	 * * monthEnd:   the end of the current month
	 * * yearBegin:  the beginning of the current year
	 * * yearEnd:    the end of the current year
	 * * format:     the date format string
	 * 
	 * @param string $strKey The property name
	 * 
	 * @return mixed The property value
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
				return $this->strToDate;
				break;

			case 'time':
				return $this->strToTime;
				break;

			case 'datim':
				return $this->strToDatim;
				break;

			case 'dayBegin':
				return $this->arrRange['day']['begin'];
				break;

			case 'dayEnd':
				return $this->arrRange['day']['end'];
				break;

			case 'monthBegin':
				return $this->arrRange['month']['begin'];
				break;

			case 'monthEnd':
				return $this->arrRange['month']['end'];
				break;

			case 'yearBegin':
				return $this->arrRange['year']['begin'];
				break;

			case 'yearEnd':
				return $this->arrRange['year']['end'];
				break;

			case 'format':
				return $this->strFormat;
				break;
		}

		return parent::__get($strKey);
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
			$strFormat = $GLOBALS['TL_CONFIG']['dateFormat'];
		}

		if (preg_match('/[BbCcDEeFfIJKkLlMNOoPpQqRrSTtUuVvWwXxZz]+/', $strFormat))
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
			$strFormat = $GLOBALS['TL_CONFIG']['dateFormat'];
		}

		if (preg_match('/[BbCcDEeFfIJKkLlMNOoPpQqRrSTtUuVvWwXxZz]+/', $strFormat))
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
				continue;
			}

			$arrInputFormat[$strFormat] .= $strCharacter;
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
		if (preg_match('/[BbCcDEeFfIJKkLlMNOoPpQqRrSTtUuVvWwXxZz]+/', $this->strFormat))
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

		$this->strDate =  mktime((int) $intHour, (int) $intMinute, (int) $intSecond, (int) $intMonth, (int) $intDay, (int) $intYear);
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
}
