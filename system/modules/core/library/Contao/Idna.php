<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;

use True\Punycode;


/**
 * An idna_encode adapter class
 *
 * The class encodes and decodes internationalized domain names according to RFC
 * 3490. It also contains two helper methods to encode e-mails and URLs.
 *
 * Usage:
 *
 *     echo Idna::encode('bürger.de');
 *     echo Idna::encodeEmail('mit@bürger.de');
 *     echo Idna::encodeUrl('http://www.bürger.de');
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Idna
{

	/**
	 * Encode an internationalized domain name
	 *
	 * @param string $strDomain The domain name
	 *
	 * @return string The encoded domain name
	 */
	public static function encode($strDomain)
	{
		$objPunycode = new Punycode();

		return $objPunycode->encode($strDomain);
	}


	/**
	 * Decode an internationalized domain name
	 *
	 * @param string $strDomain The domain name
	 *
	 * @return string The decoded domain name
	 */
	public static function decode($strDomain)
	{
		$objPunycode = new Punycode();

		return $objPunycode->decode($strDomain);
	}


	/**
	 * Encode the domain in an e-mail address
	 *
	 * @param string $strEmail The e-mail address
	 *
	 * @return string The encoded e-mail address
	 */
	public static function encodeEmail($strEmail)
	{
		if ($strEmail == '')
		{
			return '';
		}

		if (strpos($strEmail, '@') === false)
		{
			return $strEmail; // see #6241
		}

		list($strLocal, $strHost) = explode('@', $strEmail);

		return $strLocal . '@' . static::encode($strHost);
	}


	/**
	 * Encode the domain in an URL
	 *
	 * @param string $strUrl The URL
	 *
	 * @return string The encoded URL
	 */
	public static function encodeUrl($strUrl)
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
			return static::encodeEmail($strUrl);
		}

		$arrUrl = parse_url($strUrl);

		// Add the scheme to ensure that parse_url works correctly
		if (!isset($arrUrl['scheme']) && strncmp($strUrl, '{{', 2) !== 0)
		{
			$arrUrl = parse_url('http://' . $strUrl);
			unset($arrUrl['scheme']);
		}

		// Scheme
		if (isset($arrUrl['scheme']))
		{
			if ($arrUrl['scheme'] == 'tel' || $arrUrl['scheme'] == 'sms')
			{
				$arrUrl['scheme'] .= ':'; // see #6148
			}
			else
			{
				$arrUrl['scheme'] .= '://';
			}
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
			$arrUrl['host'] = static::encode($arrUrl['host']);
		}

		// Port
		if (isset($arrUrl['port']))
		{
			$arrUrl['port'] = ':' . $arrUrl['port'];
		}

		// Path does not have to be altered

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
}
