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
 * Class Request
 *
 * Provide methods to handle HTTP request. This class uses some functions of
 * Drupal's HTTP request class that you can find on http://drupal.org.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Request
{

	/**
	 * Data to be added to the request
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
	 * Response headers array (these headers are returned)
	 * @var array
	 */
	protected $arrResponseHeaders = array();


	/**
	 * Set default values
	 */
	public function __construct()
	{
		$this->strData = '';
		$this->strMethod = 'get';
	}


	/**
	 * Set an object property
	 * @param string
	 * @param mixed
	 * @throws Exception
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

			default:
				throw new Exception(sprintf('Invalid argument "%s"', $strKey));
				break;
		}
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

			default:
				return null;
				break;
		}
	}


	/**
	 * Set additional request headers
	 * @param string
	 * @param mixed
	 */
	public function setHeader($strKey, $varValue)
	{
		$this->arrHeaders[$strKey] = $varValue;
	}


	/**
	 * Return true if there has been an error
	 * @return boolean
	 */
	public function hasError()
	{
		return strlen($this->strError) ? true : false;
	}


	/**
	 * Perform an HTTP request (handle GET, POST, PUT and any other HTTP request)
	 * @param string
	 * @param string
	 * @param string
	 */
	public function send($strUrl, $strData=false, $strMethod=false)
	{
		if ($strData)
		{
			$this->strData = $strData;
		}

		if ($strMethod)
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
			'User-Agent' => 'User-Agent: Contao (+http://www.contao.org/)',
			'Content-Length' => 'Content-Length: '. strlen($this->strData),
			'Connection' => 'Connection: close'
		);

		foreach ($this->arrHeaders as $header=>$value)
		{
			$default[$header] = $header . ': ' . $value;
		}

		$request = strtoupper($this->strMethod) .' '. $path ." HTTP/1.0\r\n";
		$request .= implode("\r\n", $default);
		$request .= "\r\n\r\n";

		if (strlen($this->strData))
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

		$this->intCode = $code;

		if (!in_array(intval($code), array(200, 304)))
		{
			$this->strError = strlen($text) ? $text : $responses[$code];
		}
	}
}

?>