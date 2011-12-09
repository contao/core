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
 * Class Environment
 *
 * Provide methods to get OS independent environment parameters.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Environment
{

	/**
	 * Current object instance (Singleton)
	 * @var object
	 */
	protected static $objInstance;

	/**
	 * Cache array
	 * @var array
	 */
	protected $arrCache = array();


	/**
	 * Prevent direct instantiation (Singleton)
	 */
	protected function __construct() {}


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final private function __clone() {}


	/**
	 * Set an environment parameter
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		$this->arrCache[$strKey] = $varValue;
	}


	/**
	 * Return an environment parameter
	 * @param  string
	 * @return string
	 * @throws Exception
	 */
	public function __get($strKey)
	{
		if (isset($this->arrCache[$strKey]))
		{
			return $this->arrCache[$strKey];
		}

		if (in_array($strKey, get_class_methods($this)))
		{
			$this->arrCache[$strKey] = $this->$strKey();
		}
		else
		{
			$arrChunks = preg_split('/([A-Z][a-z]*)/', $strKey, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
			$strServerKey = strtoupper(implode('_', $arrChunks));

			$this->arrCache[$strKey] = $_SERVER[$strServerKey];
		}

		return $this->arrCache[$strKey];
	}


	/**
	 * Return the current object instance (Singleton)
	 * @return object
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			self::$objInstance = new Environment();
		}

		return self::$objInstance;
	}


	/**
	 * Return the absolute path to the script (e.g. /home/www/html/website/index.php)
	 * @return string
	 */
	protected function scriptFilename()
	{
		return str_replace('//', '/', str_replace('\\', '/', (PHP_SAPI == 'cgi' || PHP_SAPI == 'isapi' || PHP_SAPI == 'cgi-fcgi' || PHP_SAPI == 'fpm-fcgi') && ($_SERVER['ORIG_PATH_TRANSLATED'] ? $_SERVER['ORIG_PATH_TRANSLATED'] : $_SERVER['PATH_TRANSLATED']) ? ($_SERVER['ORIG_PATH_TRANSLATED'] ? $_SERVER['ORIG_PATH_TRANSLATED'] : $_SERVER['PATH_TRANSLATED']) : ($_SERVER['ORIG_SCRIPT_FILENAME'] ? $_SERVER['ORIG_SCRIPT_FILENAME'] : $_SERVER['SCRIPT_FILENAME'])));
	}


	/**
	 * Return the relative path to the script (e.g. /website/index.php)
	 * @return string
	 */
	protected function scriptName()
	{
		return (PHP_SAPI == 'cgi' || PHP_SAPI == 'isapi' || PHP_SAPI == 'cgi-fcgi' || PHP_SAPI == 'fpm-fcgi') && ($_SERVER['ORIG_PATH_INFO'] ? $_SERVER['ORIG_PATH_INFO'] : $_SERVER['PATH_INFO']) ? ($_SERVER['ORIG_PATH_INFO'] ? $_SERVER['ORIG_PATH_INFO'] : $_SERVER['PATH_INFO']) : ($_SERVER['ORIG_SCRIPT_NAME'] ? $_SERVER['ORIG_SCRIPT_NAME'] : $_SERVER['SCRIPT_NAME']);
	}


	/**
	 * Alias for scriptName()
	 * @return string
	 */
	protected function phpSelf()
	{
		return $this->scriptName;
	}


	/**
	 * Return the document root (e.g. /home/www/user/)
	 *
	 * Calculated as SCRIPT_FILENAME minus SCRIPT_NAME as some CGI versions
	 * and mod-rewrite rules might return an incorrect DOCUMENT_ROOT.
	 * @return string
	 */
	protected function documentRoot()
	{
		$strDocumentRoot = '';
		$arrUriSegments = array();

		// Fallback to DOCUMENT_ROOT if SCRIPT_FILENAME and SCRIPT_NAME point to different files
		if (basename($this->scriptName) != basename($this->scriptFilename))
		{
			return str_replace('//', '/', str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])));
		}

		if (substr($this->scriptFilename, 0, 1) == '/')
		{
			$strDocumentRoot = '/';
		}

		$arrSnSegments = explode('/', strrev($this->scriptName));
		$arrSfnSegments = explode('/', strrev($this->scriptFilename));

		foreach ($arrSfnSegments as $k=>$v)
		{
			if ($arrSnSegments[$k] != $v)
			{
				$arrUriSegments[] = $v;
			}
		}

		$strDocumentRoot .= strrev(implode('/', $arrUriSegments));

		if (strlen($strDocumentRoot) < 2)
		{
			$strDocumentRoot = substr($this->scriptFilename, 0, -(strlen($strDocumentRoot) + 1));
		}

		return str_replace('//', '/', str_replace('\\', '/', realpath($strDocumentRoot)));
	}


	/**
	 * Return the request URI [path]?[query] (e.g. /contao/index.php?id=2)
	 * @return string
	 */
	protected function requestUri()
	{
		if (!empty($_SERVER['REQUEST_URI']))
		{
			return $_SERVER['REQUEST_URI'];
		}
		else
		{
			return '/' . preg_replace('/^\//i', '', $this->scriptName) . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
		}
	}


	/**
	 * Return the first eight user languages as array
	 * @return array
	 */
	protected function httpAcceptLanguage()
	{
		$arrAccepted = array();
		$arrLanguages = explode(',', strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']));

		foreach ($arrLanguages as $strLanguage)
		{
			$strTag = substr($strLanguage, 0, 2);

			if ($strTag != '' && preg_match('/^[a-z]{2}$/', $strTag))
			{
				$arrAccepted[] = $strTag;
			}
		}

		return array_slice(array_unique($arrAccepted), 0, 8);
	}


	/**
	 * Return accepted encoding types as array
	 * @return array
	 */
	protected function httpAcceptEncoding()
	{
		return array_values(array_unique(explode(',', strtolower($_SERVER['HTTP_ACCEPT_ENCODING']))));
	}


	/**
	 * Return the user agent as string
	 * @return string
	 */
	protected function httpUserAgent()
	{
		$ua = strip_tags($_SERVER['HTTP_USER_AGENT']);
		$ua = preg_replace('/javascript|vbscri?pt|script|applet|alert|document|write|cookie/i', '', $ua);

		return substr($ua, 0, 255);
	}


	/**
	 * Return the HTTP Host
	 * @return string
	 */
	protected function httpHost()
	{
		$host = $_SERVER['HTTP_HOST'];

		if (empty($host))
		{
			$host = $_SERVER['SERVER_NAME'];

			if ($_SERVER['SERVER_PORT'] != 80)
			{
				$host .= ':' . $_SERVER['SERVER_PORT'];
			}
		}

		return preg_replace('/[^A-Za-z0-9\[\]\.:-]/', '', $host);
	}


	/**
	 * Return the HTTP X-Forwarded-Host
	 * @return string
	 */
	protected function httpXForwardedHost()
	{
		return preg_replace('/[^A-Za-z0-9\[\]\.:-]/', '', $_SERVER['HTTP_X_FORWARDED_HOST']);
	}


	/**
	 * Return true if the current page was requested via an SSL connection
	 * @return boolean
	 */
	protected function ssl()
	{
		return ($_SERVER['SSL_SESSION_ID'] || $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1);
	}


	/**
	 * Return the current URL without path or query string
	 * @return string
	 */
	protected function url()
	{
		$xhost = $this->httpXForwardedHost;
		$protocol = $this->ssl ? 'https://' : 'http://';

		return $protocol . (!empty($xhost) ? $xhost . '/' : '') . $this->httpHost;
	}


	/**
	 * Return the real REMOTE_ADDR even if a proxy server is used
	 * @return string
	 */
	protected function ip()
	{
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match('/^[A-Fa-f0-9, \.\:]+$/', $_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}

		return substr($_SERVER['REMOTE_ADDR'], 0, 64);
	}


	/**
	 * Return the SERVER_ADDR
	 * @return string
	 */
	protected function server()
	{
		$strServer = !empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];

		// Special workaround for Strato users
		if (empty($strServer))
		{
			$strServer = @gethostbyname($_SERVER['SERVER_NAME']);
		}

		return $strServer;
	}


	/**
	 * Return the relative path to the base directory (e.g. /path)
	 * @return string
	 */
	protected function path()
	{
		return TL_PATH;
	}


	/**
	 * Return the relativ path to the script (e.g. index.php)
	 * @return string
	 */
	protected function script()
	{
		return preg_replace('/^' . preg_quote(TL_PATH, '/') . '\/?/i', '', $this->scriptName);
	}


	/**
	 * Return the relativ path to the script and include the request (e.g. index.php?id=2)
	 * @return string
	 */
	protected function request()
	{
		$strRequest = preg_replace('/^' . preg_quote(TL_PATH, '/') . '\/?/i', '', $this->requestUri);

		// From version 2.9, do not fallback to $this->script
		// anymore if the request string is empty (see #1844).

		// IE security fix (thanks to Michiel Leideman)
		$strRequest = str_replace(array('<', '>', '"'), array('%3C', '%3E', '%22'), $strRequest);

		// Do not urldecode() here (thanks to Russ McRee)!
		return $strRequest;
	}


	/**
	 * Return the current URL and path that can be used in a <base> tag
	 * @return string
	 */
	protected function base()
	{
		return $this->url . TL_PATH . '/';
	}


	/**
	 * Return the current host name
	 * @return string
	 */
	protected function host()
	{
		$xhost = $this->httpXForwardedHost;
		return ($xhost != '') ? $xhost : $this->httpHost;
	}


	/**
	 * Return true on Ajax requests
	 * @return boolean
	 */
	protected function isAjaxRequest()
	{
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
	}


	/**
	 * Return the operating system and the browser name and version
	 * @return array
	 */
	protected function agent()
	{
		$ua = $this->httpUserAgent;

		$return = new stdClass();
		$return->string = $ua;

		// Operating system (check Windows CE before Windows and Android before Linux!)
		switch (true)
		{
			case $this->has($ua, 'Macintosh'):
				$os = 'mac';
				$mobile = false;
				break;

			case $this->has($ua, 'Windows CE'):
			case $this->has($ua, 'Windows Phone'):
				$os = 'win-ce';
				$mobile = true;
				break;

			case $this->has($ua, 'Windows'):
				$os = 'win';
				$mobile = false;
				break;

			case $this->has($ua, 'iPad'):
			case $this->has($ua, 'iPhone'):
			case $this->has($ua, 'iPod'):
				$os = 'ios';
				$mobile = true;
				break;

			case $this->has($ua, 'Android'):
				$os = 'android';
				$mobile = true;
				break;

			case $this->has($ua, 'Blackberry'):
				$os = 'blackberry';
				$mobile = true;
				break;

			case $this->has($ua, 'Symbian'):
				$os = 'symbian';
				$mobile = true;
				break;

			case $this->has($ua, 'WebOS'):
				$os = 'webos';
				$mobile = true;
				break;

			case $this->has($ua, 'Linux'):
			case $this->has($ua, 'FreeBSD'):
			case $this->has($ua, 'OpenBSD'):
			case $this->has($ua, 'NetBSD'):
				$os = 'unix';
				$mobile = false;
				break;

			default;
				$os = 'unknown';
				$mobile = false;
				break;
		}

		$return->os = $os;

		// Browser and version (check OmniWeb before Safari and Opera Mini/Mobi before Opera!)
		switch (true)
		{
			case $this->has($ua, 'MSIE'):
				$browser = 'ie';
				$shorty  = 'ie';
				$version = preg_replace('/^.*?MSIE (\d+).*$/', '$1', $ua);
				break;

			case $this->has($ua, 'Firefox'):
				$browser = 'firefox';
				$shorty  = 'fx';
				$version = preg_replace('/^.*Firefox\/(\d+).*$/', '$1', $ua);
				break;

			case $this->has($ua, 'Chrome'):
				$browser = 'chrome';
				$shorty  = 'ch';
				$version = preg_replace('/^.*Chrome\/(\d+).*$/', '$1', $ua);
				break;

			case $this->has($ua, 'OmniWeb'):
				$browser = 'omniweb';
				$shorty  = 'ow';
				$version = preg_replace('/^.*Version\/(\d+).*$/', '$1', $ua);
				break;

			case $this->has($ua, 'Safari'):
				$browser = 'safari';
				$shorty  = 'sf';
				$version = preg_replace('/^.*Version\/(\d+).*$/', '$1', $ua);
				break;

			case $this->has($ua, 'Opera Mini'):
				$browser = 'opera-mini';
				$shorty  = 'oi';
				$version = preg_replace('/^.*Opera Mini\/(\d+).*$/', '$1', $ua);
				$mobile = true;
				break;

			case $this->has($ua, 'Opera Mobi'):
				$browser = 'opera-mobile';
				$shorty  = 'om';
				$version = preg_replace('/^.*Version\/(\d+).*$/', '$1', $ua);
				$mobile = true;
				break;

			case $this->has($ua, 'Opera'):
				$browser = 'opera';
				$shorty  = 'op';
				$version = preg_replace('/^.*Version\/(\d+).*$/', '$1', $ua);
				break;

			case $this->has($ua, 'IEMobile'):
				$browser = 'ie-mobile';
				$shorty  = 'im';
				$version = preg_replace('/^.*IEMobile (\d+).*$/', '$1', $ua);
				$mobile = true;
				break;

			case $this->has($ua, 'Camino'):
				$browser = 'camino';
				$shorty  = 'ca';
				$version = preg_replace('/^.*Camino\/(\d+).*$/', '$1', $ua);
				break;

			case $this->has($ua, 'Konqueror'):
				$browser = 'konqueror';
				$shorty  = 'ko';
				$version = preg_replace('/^.*Konqueror\/(\d+).*$/', '$1', $ua);
				break;

			default:
				$browser = 'other';
				$shorty  = '';
				$version = '';
		}

		$return->class = $os . ' ' . $browser;

		// Add the version number if available
		if ($version != '')
		{
			$return->class .= ' ' . $shorty . $version;
		}

		// Mark mobile devices
		if ($mobile)
		{
			$return->class .= ' mobile';
		}

		$return->browser = $browser;
		$return->shorty  = $shorty;
		$return->version = $version;
		$return->mobile  = $mobile;

		return $return;
	}


	/**
	 * Test the user agent string for a certain keyword
	 * @param string
	 * @param string
	 */
	protected function has($haystack, $needle)
	{
		return (stripos($haystack, $needle) !== false);
	}
}

?>