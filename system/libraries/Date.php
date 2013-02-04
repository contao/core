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
 * Class Date
 *
 * Provide methods to handle different date formats.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Library
 */
class Date extends System
{

	/**
	 * Timestamp
	 * @var int
	 */
	protected $intTstamp;

	/**
	 * Format string
	 * @var string
	 */
	protected $strFormat;

	/**
	 * Date
	 * @var string
	 */
	protected $strDate;

	/**
	 * Time
	 * @var string
	 */
	protected $strTime;

	/**
	 * Date and time
	 * @var string
	 */
	protected $strDatim;

	/**
	 * Date range array
	 * @var array
	 */
	protected $arrRange = array();


	/**
	 * Create object properties and date ranges
	 * @param integer
	 * @param string
	 */
	public function __construct($intTstamp=false, $strFormat=false)
	{
		$this->intTstamp = ($intTstamp !== false) ? $intTstamp : time();
		$this->strFormat = ($strFormat !== false) ? $strFormat : $this->getNumericDateFormat();

		if (!preg_match('/^\-?[0-9]+$/', $this->intTstamp) || preg_match('/^[a-zA-Z]+$/', $this->strFormat))
		{
			$this->dateToUnix();
		}

		// Create dates
		$this->strDate = $this->parseDate($this->getNumericDateFormat(), $this->intTstamp);
		$this->strTime = $this->parseDate($this->getNumericTimeFormat(), $this->intTstamp);
		$this->strDatim = $this->parseDate($this->getNumericDatimFormat(), $this->intTstamp);

		$intYear = date('Y', $this->intTstamp);
		$intMonth = date('m', $this->intTstamp);
		$intDay = date('d', $this->intTstamp);

		// Create ranges
		$this->arrRange['day']['begin'] = mktime(0, 0, 0, $intMonth, $intDay, $intYear);
		$this->arrRange['day']['end'] = mktime(23, 59, 59, $intMonth, $intDay, $intYear);
		$this->arrRange['month']['begin'] = mktime(0, 0, 0, $intMonth, 1, $intYear);
		$this->arrRange['month']['end'] = mktime(23, 59, 59, $intMonth, date('t', $this->intTstamp), $intYear);
		$this->arrRange['year']['begin'] = mktime(0, 0, 0, 1, 1, $intYear);
		$this->arrRange['year']['end'] = mktime(23, 59, 59, 12, 31, $intYear);
	}


	/**
	 * Return an object property
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'tstamp':
			case 'timestamp':
				return $this->intTstamp;
				break;

			case 'date':
				return $this->strDate;
				break;

			case 'time':
				return $this->strTime;
				break;

			case 'datim':
				return $this->strDatim;
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

			default:
				return null;
				break;
		}
	}


	/**
	 * Return the begin of the week as timestamp
	 * @param integer
	 * @return integer
	 */
	public function getWeekBegin($intStartDay=0)
	{
		$intOffset = date('w', $this->intTstamp) - $intStartDay;

		if ($intOffset < 0)
		{
			$intOffset += 7;
		}

		return strtotime('-' . $intOffset . ' days', $this->intTstamp);
	}


	/**
	 * Return the end of the week as timestamp
	 * @param integer
	 * @return integer
	 */
	public function getWeekEnd($intStartDay=0)
	{
		return strtotime('+1 week', $this->getWeekBegin($intStartDay)) - 1;
	}


	/**
	 * Return a regular expression to check a date
	 * @param string
	 * @return string
	 * @throws Exception
	 */
	public function getRegexp($strFormat=false)
	{
		if (!$strFormat)
		{
			$strFormat = $this->getNumericDateFormat();
		}

		if (!$this->isNumericFormat($strFormat))
		{
			throw new Exception(sprintf('Invalid date format "%s"', $strFormat));
		}

		return preg_replace_callback('/[a-zA-Z]/', array(&$this, 'getRegexpCallback'), preg_quote($strFormat));
	}


	/**
	 * Callback function for getRegexp
	 * @param array
	 * @return string
	 */
	protected function getRegexpCallback($matches)
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


	/**
	 * Return an input format string for a particular date (e.g. YYYY-MM-DD)
	 * @param string
	 * @return string
	 * @throws Exception
	 */
	public function getInputFormat($strFormat=false)
	{
		if (!$strFormat)
		{
			$strFormat = $this->getNumericDateFormat();
		}

		if (!$this->isNumericFormat($strFormat))
		{
			throw new Exception(sprintf('Invalid date format "%s"', $strFormat));
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
	 * Convert a date string into a UNIX timestamp using a particular date format
	 * @throws Exception
	 */
	protected function dateToUnix()
	{
		if (!$this->isNumericFormat($this->strFormat))
		{
			throw new Exception(sprintf('Invalid date format "%s"', $this->strFormat));
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
					$blnMeridiem = (strtolower(substr($this->intTstamp, $intCount, 2)) == 'pm') ? true : false;
					$intCount += 2;
					break;

				case 'd':
				case 'm':
				case 'y':
				case 'h':
				case 'H':
				case 'i':
				case 's':
					$$var .= substr($this->intTstamp, $intCount, 2);
					$intCount += 2;
					break;

				case 'j':
				case 'n':
				case 'g':
				case 'G':
					$$var .= substr($this->intTstamp, $intCount++, 1);

					if (preg_match('/[0-9]+/i', substr($this->intTstamp, $intCount, 1)))
					{
						$$var .= substr($this->intTstamp, $intCount++, 1);
					}
					break;

				case 'Y':
					$$var .= substr($this->intTstamp, $intCount, 4);
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
			throw new Exception(sprintf('Invalid date "%s"', $this->intTstamp));
		}

		$this->intTstamp =  mktime((int) $intHour, (int) $intMinute, (int) $intSecond, (int) $intMonth, (int) $intDay, (int) $intYear);
	}


	/**
	 * Convert a PHP format string into a JavaScript format string
	 * @throws Exception
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

		return preg_replace('/([a-zA-Z])/', '%$1', implode($chunks));
	}


	/**
	 * Check for a numeric date format
	 * @param string
	 * @return boolean
	 */
	public function isNumericFormat($strFormat)
	{
		return !preg_match('/[BbCcDEeFfIJKkLlMNOoPpQqRrSTtUuVvWwXxZz]+/', $strFormat);
	}


	/**
	 * Return the numeric date format string
	 * @return string
	 */
	public function getNumericDateFormat()
	{
		if (TL_MODE == 'FE')
		{
			global $objPage;

			if ($objPage->dateFormat != '' && $this->isNumericFormat($objPage->dateFormat))
			{
				return $objPage->dateFormat;
			}
		}

		return $GLOBALS['TL_CONFIG']['dateFormat'];
	}


	/**
	 * Return the numeric time format string
	 * @return string
	 */
	public function getNumericTimeFormat()
	{
		if (TL_MODE == 'FE')
		{
			global $objPage;

			if ($objPage->timeFormat != '' && $this->isNumericFormat($objPage->timeFormat))
			{
				return $objPage->timeFormat;
			}
		}

		return $GLOBALS['TL_CONFIG']['timeFormat'];
	}


	/**
	 * Return the numeric datim format string
	 * @return string
	 */
	public function getNumericDatimFormat()
	{
		if (TL_MODE == 'FE')
		{
			global $objPage;

			if ($objPage->datimFormat != '' && $this->isNumericFormat($objPage->datimFormat))
			{
				return $objPage->datimFormat;
			}
		}

		return $GLOBALS['TL_CONFIG']['datimFormat'];
	}
}

?>