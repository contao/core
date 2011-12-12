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
 * Class System
 *
 * Provide default methods that are required in all models and controllers.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
abstract class System
{

	/**
	 * Configuraion object
	 * @var object
	 */
	protected $Config;

	/**
	 * Input object
	 * @var object
	 */
	protected $Input;

	/**
	 * Environment object
	 * @var object
	 */
	protected $Environment;

	/**
	 * Session object
	 * @var object
	 */
	protected $Session;

	/**
	 * Database object
	 * @var object
	 */
	protected $Database;

	/**
	 * Encryption object
	 * @var object
	 */
	protected $Encryption;

	/**
	 * String object
	 * @var object
	 */
	protected $String;

	/**
	 * Files object
	 * @var object
	 */
	protected $Files;

	/**
	 * User object
	 * @var object
	 */
	protected $User;

	/**
	 * Template object
	 * @var object
	 */
	protected $Template;

	/**
	 * Data container object
	 * @var object
	 */
	protected $DataContainer;

	/**
	 * Automator object
	 * @var object
	 */
	protected $Automator;

	/**
	 * Cache array
	 * @var array
	 */
	protected $arrCache = array();


	/**
	 * Import some default libraries
	 */
	protected function __construct()
	{
		$this->import('Config');
		$this->import('Input');
		$this->import('Environment');
		$this->import('Session');
	}


	/**
	 * Import a library and make it accessible by its name or an optional key
	 * @param string
	 * @param string
	 * @param boolean
	 * @throws Exception
	 */
	protected function import($strClass, $strKey=false, $blnForce=false)
	{
		$strKey = $strKey ? $strKey : $strClass;

		if (!is_object($this->$strKey) || $blnForce)
		{
			$this->$strKey = (in_array('getInstance', get_class_methods($strClass))) ? call_user_func(array($strClass, 'getInstance')) : new $strClass();
		}
	}


	/**
	 * Add a log entry
	 * @param string
	 * @param string
	 * @param string
	 */
	protected function log($strText, $strFunction, $strAction)
	{
		$this->import('Database');

		$strUa = 'N/A';
		$strIp = '127.0.0.1';

		if ($this->Environment->httpUserAgent)
		{
			$strUa = $this->Environment->httpUserAgent;
		}
		if ($this->Environment->remoteAddr)
		{
			$strIp = $this->Environment->remoteAddr;
		}

		$this->Database->prepare("INSERT INTO tl_log (tstamp, source, action, username, text, func, ip, browser) VALUES(?, ?, ?, ?, ?, ?, ?, ?)")
					   ->execute(time(), (TL_MODE == 'FE' ? 'FE' : 'BE'), $strAction, ($GLOBALS['TL_USERNAME'] ? $GLOBALS['TL_USERNAME'] : ''), specialchars($strText), $strFunction, $strIp, $strUa);

		// HOOK: allow to add custom loggers
		if (isset($GLOBALS['TL_HOOKS']['addLogEntry']) && is_array($GLOBALS['TL_HOOKS']['addLogEntry']))
		{
			foreach ($GLOBALS['TL_HOOKS']['addLogEntry'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($strText, $strFunction, $strAction);
			}
		}
	}


	/**
	 * Add a request string to the current URI string
	 * @param string
	 * @param integer
	 * @return string
	 */
	protected function addToUrl($strRequest)
	{
		$strRequest = preg_replace('/^&(amp;)?/i', '', $strRequest);
		$queries = preg_split('/&(amp;)?/i', $this->Environment->queryString);

		// Overwrite existing parameters
		foreach ($queries as $k=>$v)
		{
			$explode = explode('=', $v);

			if (preg_match('/(^|&(amp;)?)' . preg_quote($explode[0], '/') . '=/i', $strRequest))
			{
				unset($queries[$k]);
			}
		}

		$href = '?';

		if (count($queries) > 0)
		{
			$href .= implode('&amp;', $queries) . '&amp;';
		}

		return $this->Environment->script . $href . str_replace(' ', '%20', $strRequest);
	}


	/**
	 * Reload the current page
	 */
	protected function reload()
	{
		$strLocation = $this->Environment->url . $this->Environment->requestUri;

		// Ajax request
		if ($this->Environment->isAjaxRequest)
		{
			echo json_encode(array
			(
				'token'  => REQUEST_TOKEN,
				'target' => $strLocation
			));

			exit;
		}

		if (headers_sent())
		{
			exit;
		}

		header('Location: ' . $strLocation);
		exit;
	}


	/**
	 * Redirect to another page
	 * @param string
	 * @param false
	 */
	protected function redirect($strLocation, $intStatus=303)
	{
		$strLocation = str_replace('&amp;', '&', $strLocation);

		// Ajax request
		if ($this->Environment->isAjaxRequest)
		{
			echo json_encode(array
			(
				'token'  => REQUEST_TOKEN,
				'target' => $strLocation
			));

			exit;
		}

		if (headers_sent())
		{
			exit;
		}

		// Header
		switch ($intStatus)
		{
			case 301:
				header('HTTP/1.1 301 Moved Permanently');
				break;

			case 302:
				header('HTTP/1.1 302 Found');
				break;

			case 303:
				header('HTTP/1.1 303 See Other');
				break;
		}

		// Check the target address
		if (preg_match('@^https?://@i', $strLocation))
		{
			header('Location: ' . $strLocation);
		}
		else
		{
			header('Location: ' . $this->Environment->base . $strLocation);
		}

		exit;
	}


	/**
	 * Return the current referer URL and optionally encode ampersands
	 * @param boolean
	 * @param string
	 * @return string
	 */
	protected function getReferer($blnEncodeAmpersands=false, $strTable='')
	{
		$key = ($this->Environment->script == 'contao/files.php') ? 'fileReferer' : 'referer';
		$session = $this->Session->get($key);

		// Use a specific referer
		if ($strTable != '' && isset($session[$strTable]) && $this->Input->get('act') != 'select')
		{
			$session['current'] = $session[$strTable];
		}

		// Get the default referer
		$return = preg_replace('/(&(amp;)?|\?)tg=[^& ]*/i', '', (($session['current'] != $this->Environment->requestUri) ? $session['current'] : $session['last']));
		$return = preg_replace('/^'.preg_quote(TL_PATH, '/').'\//i', '', $return);

		// Fallback to the generic referer in the front end
		if ($return == '' && TL_MODE == 'FE')
		{
			$return = $this->Environment->httpReferer;
		}

		// Fallback to the current URL if there is no referer
		if ($return == '')
		{
			$return = (TL_MODE == 'BE') ? 'contao/main.php' : $this->Environment->url;
		}

		// Do not urldecode here!
		return ampersand($return, $blnEncodeAmpersands);
	}


	/**
	 * Return the request string or an empty string if the request string
	 * is "index.php" and optionally encode ampersands
	 * @param boolean
	 * @return string
	 */
	protected function getIndexFreeRequest($blnAmpersand=true)
	{
		$strRequest = $this->Environment->request;

		if ($strRequest == 'index.php')
		{
			return '';
		}

		return ampersand($strRequest, $blnAmpersand);
	}


	/**
	 * Load a set of language files
	 * @param string
	 * @param boolean
	 * @param boolean
	 */
	protected function loadLanguageFile($strName, $strLanguage=false, $blnNoCache=false)
	{
		if (!$strLanguage)
		{
			$strLanguage = $GLOBALS['TL_LANGUAGE'];
		}

		// Return if the data has been loaded already
		if (!$blnNoCache && isset($GLOBALS['loadLanguageFile'][$strName][$strLanguage]))
		{
			return;
		}

		// Use a global cache variable to support nested calls
		$GLOBALS['loadLanguageFile'][$strName][$strLanguage] = true;

		// Parse all active modules
		foreach ($this->Config->getActiveModules() as $strModule)
		{
			$strFallback = sprintf('%s/system/modules/%s/languages/en/%s.php', TL_ROOT, $strModule, $strName);

			if (file_exists($strFallback))
			{
				include($strFallback);
			}

			if ($strLanguage == 'en')
			{
				continue;
			}

			$strFile = sprintf('%s/system/modules/%s/languages/%s/%s.php', TL_ROOT, $strModule, $strLanguage, $strName);

			if (file_exists($strFile))
			{
				include($strFile);
			}
		}

		// HOOK: allow to load custom labels
		if (isset($GLOBALS['TL_HOOKS']['loadLanguageFile']) && is_array($GLOBALS['TL_HOOKS']['loadLanguageFile']))
		{
			foreach ($GLOBALS['TL_HOOKS']['loadLanguageFile'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($strName, $strLanguage);
			}
		}

		// Handle single quotes in the deleteConfirm message
		if ($strName == 'default')
		{
			$GLOBALS['TL_LANG']['MSC']['deleteConfirm'] = str_replace("'", "\\'", $GLOBALS['TL_LANG']['MSC']['deleteConfirm']);
		}

		include(TL_ROOT . '/system/config/langconfig.php');
	}


	/**
	 * Parse a date format string and translate textual representations
	 * @param integer
	 * @param string
	 * @return string
	 */
	protected function parseDate($strFormat, $intTstamp=null)
	{
		$strModified = str_replace
		(
			array('l', 'D', 'F', 'M'),
			array('w::1', 'w::2', 'n::3', 'n::4'),
			$strFormat
		);

		if (is_null($intTstamp))
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
					$strReturn .= utf8_substr($GLOBALS['TL_LANG']['DAYS'][$index], 0, $GLOBALS['TL_LANG']['MSC']['dayShortLength']);
					break;

				case 3:
					$strReturn .= $GLOBALS['TL_LANG']['MONTHS'][($index - 1)];
					break;

				case 4:
					$strReturn .= utf8_substr($GLOBALS['TL_LANG']['MONTHS'][($index - 1)], 0, $GLOBALS['TL_LANG']['MSC']['monthShortLength']);
					break;

				default:
					$strReturn .= $chunk;
					break;
			}
		}

		return $strReturn;	
	}


	/**
	 * Return all error, confirmation and info messages as HTML
	 * @return string
	 */
	protected function getMessages($blnDcLayout=false)
	{
		$strMessages = '';
		$arrGroups = array('TL_ERROR', 'TL_CONFIRM', 'TL_INFO');

		foreach ($arrGroups as $strGroup)
		{
			if (!is_array($_SESSION[$strGroup]))
			{
				continue;
			}

			$strClass = strtolower($strGroup);

			foreach ($_SESSION[$strGroup] as $strMessage)
			{
				$strMessages .= sprintf('<p class="%s">%s</p>%s', $strClass, $strMessage, "\n");
			}

			if (!$_POST)
			{
				$_SESSION[$strGroup] = array();
			}
		}

		$strMessages = trim($strMessages);

		if ($strMessages != '')
		{
			$strMessages = sprintf('%s<div class="tl_message">%s%s%s</div>%s', ($blnDcLayout ? "\n\n" : "\n"), "\n", $strMessages, "\n", ($blnDcLayout ? '' : "\n"));
		}

		return $strMessages;
	}


	/**
	 * Urlencode an image path preserving slashes
	 * @param string
	 * @return string
	 */
	protected function urlEncode($strPath)
	{
		return str_replace('%2F', '/', rawurlencode($strPath));
	}


	/**
	 * Set a cookie
	 * @param string
	 * @param mixed
	 * @param integer
	 * @param string
	 * @param string
	 * @param boolean
	 */
	protected function setCookie($strName, $varValue, $intExpires, $strPath='', $strDomain=null, $blnSecure=null)
	{
		if ($strPath == '')
		{
			$strPath = '/';
		}

		setcookie($strName, $varValue, $intExpires, $strPath, $strDomain, $blnSecure);
	}


	/**
	 * Split a friendly name address and return name and e-mail as array
	 * @param string
	 * @return array
	 */
	protected function splitFriendlyName($strEmail)
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
	 * Encode an internationalized domain name
	 * @param string
	 * @return string
	 */
	protected function idnaEncode($strDomain)
	{
		if (!class_exists('idna_convert', false))
		{
			require_once(TL_ROOT . '/plugins/idna/idna_convert.class.php');
		}

		$objIdn = new idna_convert();
		return $objIdn->encode($strDomain);
	}


	/**
	 * Decode an internationalized domain name
	 * @param string
	 * @return string
	 */
	protected function idnaDecode($strDomain)
	{
		if (!class_exists('idna_convert', false))
		{
			require_once(TL_ROOT . '/plugins/idna/idna_convert.class.php');
		}

		$objIdn = new idna_convert();
		return $objIdn->decode($strDomain);
	}


	/**
	 * Encode an e-mail address
	 * @param string
	 * @return string
	 */
	protected function idnaEncodeEmail($strEmail)
	{
		if ($strEmail == '')
		{
			return '';
		}

		list($strLocal, $strHost) = explode('@', $strEmail);
		return $strLocal .'@'. $this->idnaEncode($strHost);
	}


	/**
	 * Encode an URL
	 * @param string
	 * @return string
	 */
	protected function idnaEncodeUrl($strUrl)
	{
		if ($strUrl == '')
		{
			return '';
		}

		// Empty anchor (see #3555)
		if ($strUrl == '#')
		{
			return $strUrl;
		}

		// E-mail address
		if (strncasecmp($strUrl, 'mailto:', 7) === 0)
		{
			return $this->idnaEncodeEmail($strUrl);
		}

		$arrUrl = parse_url($strUrl);

		// Scheme
		if (isset($arrUrl['scheme']))
		{
			$arrUrl['scheme'] .= '://';
		}

		// User
		if (isset($arrUrl['user']))
		{
			$arrUrl['user'] .= isset($arrUrl['pass']) ? ':' : '@';
		}

		// Password
		if (isset($arrUrl['pass']))
		{
			$arrUrl['pass'] .= '@';
		}

		// Host
		if (isset($arrUrl['host']))
		{
			$arrUrl['host'] = $this->idnaEncode($arrUrl['host']);
		}

		// Port
		if (isset($arrUrl['port']))
		{
			$arrUrl['port'] = ':' . $arrUrl['port'];
		}

		// Path
		if (isset($arrUrl['path']))
		{
			$arrUrl['path'] = $arrUrl['path'];
		}

		// Query
		if (isset($arrUrl['query']))
		{
			$arrUrl['query'] = '?' . $arrUrl['query'];
		}

		// Anchor
		if (isset($arrUrl['fragment']))
		{
			$arrUrl['fragment'] = '#' . $arrUrl['fragment'];
		}

		return $arrUrl['scheme'] . $arrUrl['user'] . $arrUrl['pass'] . $arrUrl['host'] . $arrUrl['port'] . $arrUrl['path'] . $arrUrl['query'] . $arrUrl['fragment']; 
	}


	/**
	 * Validate an e-mail address
	 * @param string
	 * @return boolean
	 */
	protected function isValidEmailAddress($strEmail)
	{
		if (preg_match('/^(\w+[!#\$%&\'\*\+\-\/=\?^_`\.\{\|\}~]*)+(?<!\.)@\w+([_\.-]*\w+)*\.[a-z]{2,6}$/i', $strEmail))
		{
			return true;
		}

		return false;
	}


	/**
	 * Convert a filesize into a human readable format
	 * @param integer
	 * @param integer
	 * @return string
	 */
	protected function getReadableSize($intSize, $intDecimals=1)
	{
		for ($i=0; $intSize>1000; $i++)
		{
			$intSize /= 1000;
		}

		return $this->getFormattedNumber($intSize, $intDecimals) . ' ' . $GLOBALS['TL_LANG']['UNITS'][$i];
	}


	/**
	 * Format a number
	 * @param mixed
	 * @param integer
	 * @return mixed
	 */
	protected function getFormattedNumber($varNumber, $intDecimals=2)
	{
		return number_format(round($varNumber, $intDecimals), $intDecimals, $GLOBALS['TL_LANG']['MSC']['decimalSeparator'], $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']);
	}
}

?>