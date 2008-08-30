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
 * Class System
 *
 * Provide default methods that are required in all models and controllers.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
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
	 * @throws Exception
	 */
	protected function import($strClass, $strKey=false)
	{
		$strKey = $strKey ? $strKey : $strClass;

		if (!is_object($this->$strKey))
		{
			$this->$strKey = (in_array('getInstance', get_class_methods($strClass))) ? call_user_func(array($strClass, 'getInstance')) : new $strClass();
		}
	}


	/**
	 * Add a log entry
	 * @param string
	 * @param string
	 * @param integer
	 */
	protected function log($strText, $strFunction, $strAction)
	{
		$this->import('Database');
		$strIp = '127.0.0.1';

		if ($this->Environment->remoteAddr)
		{
			$strIp = $this->Environment->remoteAddr;
		}

		$this->Database->prepare("INSERT INTO tl_log (tstamp, source, action, username, text, func, ip, browser) VALUES(?, ?, ?, ?, ?, ?, ?, ?)")
					   ->execute(time(), (TL_MODE == 'FE' ? 'FE' : 'BE'), $strAction, ($GLOBALS['TL_USERNAME'] ? $GLOBALS['TL_USERNAME'] : ''), $strText, $strFunction, $strIp, $this->Environment->httpUserAgent);
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

			if (preg_match('/' . preg_quote($explode[0], '/') . '=/i', $strRequest))
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
		if (headers_sent())
		{
			exit;
		}

		header('Location: ' . $this->Environment->url . $this->Environment->requestUri);
		exit;
	}


	/**
	 * Redirect to another page
	 * @param string
	 * @param false
	 */
	protected function redirect($strLocation, $blnTemporary=false)
	{
		if (headers_sent())
		{
			exit;
		}

		// Header
		if ($blnTemporary)
		{
			header('HTTP/1.1 302 Moved Temporarily');
		}
		else
		{
			header('HTTP/1.1 301 Moved Permanently');
		}

		// Check target address
		if (preg_match('@^https?://@i', $strLocation))
		{
			header('Location: ' . str_replace('&amp;', '&', $strLocation));
		}
		else
		{
			header('Location: ' . $this->Environment->base . str_replace('&amp;', '&', $strLocation));
		}

		exit;
	}


	/**
	 * Return the current referer URL and optionally encode ampersands
	 * @param boolean
	 * @return string
	 */
	protected function getReferer($blnEncodeAmpersands=false)
	{
		$session = $this->Session->getData();
		$key = ($this->Environment->script == 'typolight/files.php') ? 'fileReferer' : 'referer';

		$return = preg_replace('/(&(amp;)?|\?)tg=[^& ]*/i', '', (($session[$key]['current'] != $this->Environment->requestUri) ? $session[$key]['current'] : $session[$key]['last']));
		$return = preg_replace('/^'.preg_quote(TL_PATH, '/').'\//i', '', $return);

		if (!strlen($return) && TL_MODE == 'FE')
		{
			$return = $this->Environment->httpReferer;
		}

		if (!strlen($return))
		{
			$return = (TL_MODE == 'BE') ? 'typolight/main.php' : $this->Environment->url;
		}

		return ampersand(urldecode($return), $blnEncodeAmpersands);
	}


	/**
	 * Load a set of language files
	 * @param string
	 * @param boolean
	 */
	protected function loadLanguageFile($strName, $strLanguage=false)
	{
		if (!$strLanguage)
		{
			$strLanguage = $GLOBALS['TL_LANGUAGE'];
		}

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
	}


	/**
	 * Return all error, confirmation and info messages as HTML
	 * @return string
	 */
	protected function getMessages()
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

		if (strlen($strMessages))
		{
			$strMessages = sprintf('%s<div class="tl_message">%s%s%s</div>', "\n\n", "\n", $strMessages, "\n");
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
		return str_replace('%2F', '/', urlencode($strPath));
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
		if (!strlen($strPath))
		{
			$strPath = '/';
		}

		setcookie($strName, $varValue, $intExpires, $strPath, $strDomain, $blnSecure);
	}
}

?>