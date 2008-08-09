<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class Date
 *
 * Provide methods to handle different date formats.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Library
 */
class Date
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
		$this->intTstamp = $intTstamp ? $intTstamp : time();
		$this->strFormat = $strFormat ? $strFormat : $GLOBALS['TL_CONFIG']['dateFormat'];

		if (!is_numeric($this->intTstamp) || preg_match('/^[a-zA-Z]+$/i', $this->strFormat))
		{
			$this->dateToUnix();
		}

		// Create dates
		$this->strDate = date($GLOBALS['TL_CONFIG']['dateFormat'], $this->intTstamp);
		$this->strTime = date($GLOBALS['TL_CONFIG']['timeFormat'], $this->intTstamp);
		$this->strDatim = date($GLOBALS['TL_CONFIG']['datimFormat'], $this->intTstamp);

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
	 * @throws Exception
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
				throw new Exception(sprintf('Unknown or protected property "%s"', $strKey));
				break;
		}
	}


	/**
	 * Return a regular expression that matches a particular date format
	 * @param  string
	 * @param  string
	 * @return string
	 */
	public function getRegexp($strFormat=false, $strRegexpSyntax='perl')
	{
		if (!$strFormat)
		{
			$strFormat = $GLOBALS['TL_CONFIG']['dateFormat'];
		}

		if (preg_match('/[BbCcDEeFfIJKkLlMNOoPpQqRrSTtUuVvWwXxZz]+/', $strFormat))
		{
			throw new Exception(sprintf('Invalid date format "%s"', $strFormat));
		}

		$arrRegexp = array();
		$arrCharacters = str_split($strFormat);

		foreach ($arrCharacters as $strCharacter)
		{
			switch ($strCharacter)
			{
				case 'a':
				case 'A':
					$arrRegexp[$strFormat]['perl']  .= '[apmAPM]{2,2}';
					$arrRegexp[$strFormat]['posix'] .= '[apmAPM]{2}';
					break;

				case 'd':
				case 'm':
				case 'y':
				case 'h':
				case 'H':
				case 'i':
				case 's':
					$arrRegexp[$strFormat]['perl']  .= '[0-9]{2,2}';
					$arrRegexp[$strFormat]['posix'] .= '[[:digit:]]{2}';
					break;

				case 'j':
				case 'n':
				case 'g':
				case 'G':
					$arrRegexp[$strFormat]['perl']  .= '[0-9]{1,2}';
					$arrRegexp[$strFormat]['posix'] .= '[[:digit:]]{1,2}';
					break;

				case 'Y':
					$arrRegexp[$strFormat]['perl']  .= '[0-9]{4,4}';
					$arrRegexp[$strFormat]['posix'] .= '[[:digit:]]{4}';
					break;

				default:
					$arrRegexp[$strFormat]['perl']  .= preg_quote($strCharacter, '/');
					$arrRegexp[$strFormat]['posix'] .= preg_quote($strCharacter, '/');
					break;
			}
		}

		return $arrRegexp[$strFormat][$strRegexpSyntax];
	}


	/**
	 * Return an input format string for a particular date (e.g. YYYY-MM-DD)
	 * @param  string
	 * @return string
	 */
	public function getInputFormat($strFormat=false)
	{
		if (!$strFormat)
		{
			$strFormat = $GLOBALS['TL_CONFIG']['dateFormat'];
		}

		if (preg_match('/[BbCcDEeFfIJKkLlMNOoPpQqRrSTtUuVvWwXxZz]+/', $strFormat))
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
			if (array_key_exists($strCharacter, $arrCharacterMapper))
			{
				$arrInputFormat[$strFormat] .= $arrCharacterMapper[$strCharacter];
				continue;
			}

			$arrInputFormat[$strFormat] .= $strCharacter;
		}

		return $arrInputFormat[$strFormat];
	}


	/**
	 * Convert a date string into a UNIX timestamp using a particular date format
	 * @throws Exception
	 */
	private function dateToUnix()
	{
		if (preg_match('/[BbCcDEeFfIJKkLlMNOoPpQqRrSTtUuVvWwXxZz]+/', $this->strFormat))
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
			$var = array_key_exists($strCharacter, $arrCharacterMapper) ? $arrCharacterMapper[$strCharacter] : 'dummy';

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

		$this->intTstamp =  mktime((int) $intHour, (int) $intMinute, (int) $intSecond, (int) $intMonth, (int) $intDay, (int) $intYear);
	}
}

?>