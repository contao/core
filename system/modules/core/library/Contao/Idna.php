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


// Import the inda_convert class
if (!class_exists('idna_convert', false))
{
	require_once TL_ROOT . '/system/vendor/idna/idna_convert.class.php';
}


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
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
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
		$objIdn = new \idna_convert();
		return $objIdn->encode($strDomain);
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
		$objIdn = new \idna_convert();
		return $objIdn->decode($strDomain);
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

		list($strLocal, $strHost) = explode('@', $strEmail);
		return $strLocal .'@'. static::encode($strHost);
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

		$blnSchemeAdded = false;
		$arrUrl = parse_url($strUrl);

		// Add the scheme to ensure that parse_url works correctly
		if (!isset($arrUrl['scheme']) && strncmp($strUrl, '{{', 2) !== 0)
		{
			$blnSchemeAdded = true;
			$arrUrl = parse_url('http://' . $strUrl);
		}

		// Scheme
		if (isset($arrUrl['scheme']))
		{
			// Remove the scheme if it has been added above (see #3792)
			if ($blnSchemeAdded)
			{
				unset($arrUrl['scheme']);
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
