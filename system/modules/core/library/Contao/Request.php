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
 * Sends HTTP requests and reads the response
 *
 * The class can be used to communicate with services that are available via
 * HTTP (e.g. the Contao Extension Repository or the Live Update Service). It
 * uses some of Drupal's HTTP request class methods ({@see http://drupal.org}).
 *
 * Usage:
 *
 *     $request = new Request();
 *     $request->send('http://www.inetrobots.com/liveupdate/version.txt');
 *
 *     if (!$request->hasError())
 *     {
 *         echo "The latest Contao version is " . $request->response;
 *     }
 *
 * @property string  $error    The error message
 * @property integer $code     The response code
 * @property string  $request  The request string
 * @property string  $response The response string
 * @property array   $headers  The response headers array
 * @property string  $username The auth username
 * @property boolean $redirect The follow redirects status
 * @property integer $rlimit   The maximum number of redirects
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Request
{

	/**
	 * Request data
	 * @var string
	 */
	protected $strData;

	/**
	 * Request method (defaults to GET)
	 * @var string
	 */
	protected $strMethod;

	/**
	 * Error string
	 * @var string
	 */
	protected $strError;

	/**
	 * Response code
	 * @var integer
	 */
	protected $intCode;

	/**
	 * Response string
	 * @var string
	 */
	protected $strResponse;

	/**
	 * Request string
	 * @var string
	 */
	protected $strRequest;

	/**
	 * Headers array (these headers will be sent)
	 * @var array
	 */
	protected $arrHeaders = array();

	/**
	 * Response headers (these headers are returned)
	 * @var array
	 */
	protected $arrResponseHeaders = array();

	/**
	 * Request username
	 * @var string
	 */
	protected $strUsername;

	/**
	 * Request password
	 * @var string
	 */
	protected $strPassword;

	/**
	 * Follow redirects
	 * @var boolean
	 */
	protected $blnFollowRedirects = false;


	/**
	 * Number of redirects
	 * @var integer
	 */
	protected $intRedirects = 0;


	/**
	 * Maximum number of redirects
	 * @var integer
	 */
	protected $intRedirectLimit = 3;


	/**
	 * Set the default values
	 */
	public function __construct()
	{
		$this->strData = '';
		$this->strMethod = 'get';
	}


	/**
	 * Set an object property
	 *
	 * @param string $strKey   The property name
	 * @param mixed  $varValue The property value
	 *
	 * @throws \Exception If $strKey is unknown
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'data':
				$this->strData = $varValue;
				break;

			case 'method':
				$this->strMethod = $varValue;
				break;

			case 'username':
				$this->strUsername = $varValue;
				break;

			case 'password':
				$this->strPassword = $varValue;
				break;

			case 'redirect':
				$this->blnFollowRedirects = $varValue;
				break;

			case 'rlimit':
				$this->intRedirectLimit = $varValue;
				break;

			default:
				throw new \Exception(sprintf('Invalid argument "%s"', $strKey));
				break;
		}
	}


	/**
	 * Return an object property
	 *
	 * @param string $strKey The property key
	 *
	 * @return mixed|null The property value or null
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'error':
				return $this->strError;
				break;

			case 'code':
				return $this->intCode;
				break;

			case 'request':
				return $this->strRequest;
				break;

			case 'response':
				return $this->strResponse;
				break;

			case 'headers':
				return $this->arrResponseHeaders;
				break;

			case 'username':
				return $this->strUsername;
				break;

			case 'redirect':
				return $this->blnFollowRedirects;
				break;

			case 'rlimit':
				return $this->intRedirectLimit;
				break;
		}

		return null;
	}


	/**
	 * Add request headers
	 *
	 * @param string $strKey   The header name
	 * @param mixed  $varValue The header value
	 */
	public function setHeader($strKey, $varValue)
	{
		$this->arrHeaders[$strKey] = $varValue;
	}


	/**
	 * Return true if there was an error
	 *
	 * @return boolean True if there was an error
	 */
	public function hasError()
	{
		return ($this->strError != '');
	}


	/**
	 * Send the HTTP request
	 *
	 * @param string $strUrl    The target URL
	 * @param string $strData   Optional request data
	 * @param string $strMethod An optional request method
	 */
	public function send($strUrl, $strData=null, $strMethod=null)
	{
		if ($strData !== null)
		{
			$this->strData = $strData;
		}

		if ($strMethod !== null)
		{
			$this->strMethod = $strMethod;
		}

		$errstr = '';
		$errno = null;
		$uri = parse_url($strUrl);

		switch ($uri['scheme'])
		{
			case 'http':
				$port = isset($uri['port']) ? $uri['port'] : 80;
				$host = $uri['host'] . (($port != 80) ? ':' . $port : '');
				$fp = @fsockopen($uri['host'], $port, $errno, $errstr, 10);
				break;

			case 'https':
				$port = isset($uri['port']) ? $uri['port'] : 443;
				$host = $uri['host'] . (($port != 443) ? ':' . $port : '');
				$fp = @fsockopen('ssl://' . $uri['host'], $port, $errno, $errstr, 15);
				break;

			default:
				$this->strError = 'Invalid schema ' . $uri['scheme'];
				return;
				break;
		}

		if (!is_resource($fp))
		{
			$this->strError = trim($errno .' '. $errstr);

			return;
		}

		$path = isset($uri['path']) ? $uri['path'] : '/';

		if (isset($uri['query']))
		{
			$path .= '?' . $uri['query'];
		}

		$default = array
		(
			'Host' => 'Host: ' . $host,
			'User-Agent' => 'User-Agent: Contao (+https://contao.org/)',
			'Content-Length' => 'Content-Length: '. strlen($this->strData),
			'Connection' => 'Connection: close'
		);

		if (isset($uri['user']))
		{
			$default['Authorization'] = 'Authorization: Basic ' . base64_encode($uri['user'] . ':' . $uri['pass']);
		}
		elseif ($this->strUsername != '')
		{
			$default['Authorization'] = 'Authorization: Basic ' . base64_encode($this->strUsername . ':' . $this->strPassword);
		}

		foreach ($this->arrHeaders as $header=>$value)
		{
			$default[$header] = $header . ': ' . $value;
		}

		$request = strtoupper($this->strMethod) .' '. $path ." HTTP/1.0\r\n";
		$request .= implode("\r\n", $default);
		$request .= "\r\n\r\n";

		if ($this->strData != '')
		{
			$request .= $this->strData . "\r\n";
		}

		$this->strRequest = $request;
		fwrite($fp, $request);
		$response = '';

		while (!feof($fp) && ($chunk = fread($fp, 1024)) != false)
		{
			$response .= $chunk;
		}

		fclose($fp);

		list($split, $this->strResponse) = explode("\r\n\r\n", $response, 2);
		$split = preg_split("/\r\n|\n|\r/", $split);
		$this->arrResponseHeaders = array();
		list(, $code, $text) = explode(' ', trim(array_shift($split)), 3);

		while (($line = trim(array_shift($split))) != false)
		{
			list($header, $value) = explode(':', $line, 2);

			if (isset($this->arrResponseHeaders[$header]) && $header == 'Set-Cookie')
			{
				$this->arrResponseHeaders[$header] .= ',' . trim($value);
			}
			else
			{
				$this->arrResponseHeaders[$header] = trim($value);
			}
		}

		$responses = array
		(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Large',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported'
		);

		if (!isset($responses[$code]))
		{
			$code = floor($code / 100) * 100;
		}

		$this->intCode = intval($code);

		switch ($this->intCode)
		{
			case 200:
			case 304:
				// Ok
				break;

			case 301:
			case 302:
			case 303:
			case 307:
				if ($this->blnFollowRedirects && $this->intRedirects < $this->intRedirectLimit && !empty($this->arrResponseHeaders['Location']))
				{
					++$this->intRedirects;
					$this->send($this->arrResponseHeaders['Location']);
				}
				break;

			default:
				$this->strError = $text ?: $responses[$this->intCode];
				break;
		}
	}
}
