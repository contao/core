<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Import the library
 */
if (!class_exists('idna_convert', false))
{
	require_once TL_ROOT . '/system/library/IDNA/idna_convert.class.php';
}


/**
 * Class Idna
 *
 * Provide methods to convert IDNA domain names.
 * @copyright  Leo Feyer 2011-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Idna
{

	/**
	 * Encode an internationalized domain name
	 * @param string
	 * @return string
	 */
	public static function encode($strDomain)
	{
		$objIdn = new \idna_convert();
		return $objIdn->encode($strDomain);
	}


	/**
	 * Decode an internationalized domain name
	 * @param string
	 * @return string
	 */
	public static function decode($strDomain)
	{
		$objIdn = new \idna_convert();
		return $objIdn->decode($strDomain);
	}


	/**
	 * Encode an e-mail address
	 * @param string
	 * @return string
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
	 * Encode an URL
	 * @param string
	 * @return string
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
