<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   Repository
 * @author    Peter Koch, IBK Software AG
 * @license   See accompaning file LICENSE.txt
 * @copyright Peter Koch 2008-2010
 */


/**
 * Contao Repository :: Base back end module
 */
require_once dirname(__FILE__).'/RepositorySettings.php';


/**
 * Basic general functions of the central repository
 * @copyright  Peter Koch 2008-2010
 * @author     Peter Koch, IBK Software AG
 * @author Leo Feyer <https://github.com/leofeyer>
 * @package    Repository
 */
class Repository
{

	/**
	 * Array with status detail names.
	 */
	private static $mStatusName =
		array(
			'alpha1', 'alpha2', 'alpha3',
			'beta1', 'beta2', 'beta3',
			'RC1', 'RC2', 'RC3',
			'stable'
		);

	private static $mShortStatusName =
		array(
			'&#945;1', '&#945;2', '&#945;3',
			'&#946;1', '&#946;2', '&#946;3',
			'r1', 'r2', 'r3',
			'st'
		);

	/**
	 * Format a version number to human readable with long status text
	 *
	 * Example:
	 * <code>
	 * echo Repository::formatVersion(10030042);
	 * // will output: 1.3.4-alpha3
	 * </code>
	 * @param int $aVersion The encoded version
	 * @return string The version in human readable format
	 */
	public static function formatVersion($aVersion)
	{
		$aVersion	= (int)$aVersion;
		if (!$aVersion) return '';
		$status		= $aVersion % 10;
		$aVersion	= (int)($aVersion / 10);
		$micro		= $aVersion % 1000;
		$aVersion	= (int)($aVersion / 1000);
		$minor		= $aVersion % 1000;
		$major		= (int)($aVersion / 1000);
		return $status < 9 ? "$major.$minor.$micro-".self::$mStatusName[$status] : "$major.$minor.$micro";
	} // formatVersion

	/**
	 * Format a version number to human readable with short status text
	 *
	 * Example:
	 * <code>
	 * echo Repository::formatShortVersion(10030042);
	 * // will output: 1.3.4 a3
	 * </code>
	 * @param int $aVersion The encoded version
	 * @return string The version in human readable format
	 */
	public static function formatShortVersion($aVersion)
	{
		$aVersion	= (int)$aVersion;
		if (!$aVersion) return '';
		$status		= $aVersion % 10;
		$aVersion	= (int)($aVersion / 10);
		$micro		= $aVersion % 1000;
		$aVersion	= (int)($aVersion / 1000);
		$minor		= $aVersion % 1000;
		$major		= (int)($aVersion / 1000);
		return $status < 9 ? "$major.$minor.$micro ".self::$mShortStatusName[$status] : "$major.$minor.$micro";
	} // formatShortVersion

	/**
	 * Format a compatibility version number to human readable
	 *
	 * Example:
	 * <code>
	 * echo Repository::formatCompatVersion(20090039);
	 * // version 2.9.3 stable will output: 2.9
	 * </code>
	 * @param int $aVersion The encoded version
	 * @return string The version in human readable format
	 */
	public static function formatCompatVersion($aVersion)
	{
		$aVersion	= (int)$aVersion;
		if (!$aVersion) return '';
		$aVersion	= (int)($aVersion / 10000);
		return intval($aVersion / 1000) . '.' . ($aVersion % 1000);
	} // formatCompatVersion

	/**
	 * Remap and format a core version number to human readable.
	 *
	 * Example:
	 * <code>
	 * echo Repository::formatCoreVersion(20050119);
	 * // will output: 2.6.0 beta2
	 * </code>
	 * @param int $aVersion The encoded version
	 * @return string The version in human readable format
	 */
	public static function formatCoreVersion($aVersion)
	{
		$aVersion = (int)$aVersion;
		if (!$aVersion) return '';
		foreach (explode(';',REPOSITORY_COREVERSIONS) as $v) {
			$v = explode(',',$v);
			if ((int)$v[0] == $aVersion) {
				$aVersion = $v[1];
				break;
			} // if
		} // foreach
		return self::formatVersion($aVersion);
	} // formatCoreVersion

	/**
	 * Remap and format a core version number to human readable, short version.
	 *
	 * Example:
	 * <code>
	 * echo Repository::formatShortCoreVersion(20050119);
	 * // will output: 2.6.0 b2
	 * </code>
	 * @param int $aVersion The encoded version
	 * @return string The version in human readable format
	 */
	public static function formatShortCoreVersion($aVersion)
	{
		$aVersion = (int)$aVersion;
		if (!$aVersion) return '';
		foreach (explode(';',REPOSITORY_COREVERSIONS) as $v) {
			$v = explode(',',$v);
			if ((int)$v[0] == $aVersion) {
				$aVersion = $v[1];
				break;
			} // if
		} // foreach
		return self::formatShortVersion($aVersion);
	} // formatShortCoreVersion

	/**
	 * Encode version from human readable format.
	 *
	 * Example:
	 * <code>
	 * echo Repository::encodeVersion('2.9.21 beta2');
	 * // will output: 20090214
	 * </code>
	 * @param string $aVersion Human readable representation of a version.
	 * @return int The encoded version number
	 */
	public static function encodeVersion($aVersion)
	{
		$matches = array();
		if (preg_match('/(\d{1,3})\.(\d{1,3})\.(\d{1,3})([ \-](\w+))?/', $aVersion, $matches)) {
			$stat = strtolower($matches[5]);
			$v = array_search($stat, array_map('strtolower', self::$mStatusName));
			if ($v === false) $v = 9; // assume stable
			return (($matches[1] * 1000 + $matches[2]) * 1000 + $matches[3]) * 10 + $v;
		} elseif (preg_match('/(\d{1,3})\.(\d{1,3})\.(\w*)/', $aVersion, $matches)) {
			$stat = strtolower($matches[3]);
			$v = array_search($stat, array_map('strtolower', self::$mStatusName));
			if ($v === false) $v = 9; // assume stable
			return (($matches[1] * 1000 + $matches[2]) * 1000) * 10 + $v;
		} // if
		return 0;
	} // encodeVersion

	/**
	 * Shorten text adding ellipsis when it is too long.
	 * The text is enclosed in a span element with the full length text as title, so when
	 * hoovering with the mouse the full text is visible.
	 * @param string $aText The text to process.
	 * @param int $aLength The maximum length.
	 * @return string The (probably) shortened text packed into a span element.
	 */
	public static function ellipsisText($aText, $aLength = 32)
	{
		return
			'<span title="'.$aText.'">'.
			(mb_strlen($aText)<=$aLength ? $aText : mb_substr($aText,0,$aLength-2).' …').
			'</span>';
	} // ellipsisText

} // class Repository
