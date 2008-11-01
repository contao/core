<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');
/**
 * TYPOlight Repository :: Class Repository
 *
 * NOTE: this file was edited with tabs set to 4.
 * @package Repository
 * @copyright Copyright (C) 2008 by Peter Koch, IBK Software AG
 * @license See accompaning file LICENSE.txt
 */

require_once(dirname(__FILE__).'/RepositorySettings.php');

/**
 * Basic general functions of the central repository.
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
			'rc1', 'rc2', 'rc3',
			'stable'
		);
		
	/**
	 * Format a version number to human readable.
	 *
	 * Example:
	 * <code>
	 * echo Repository::formatVersion(10030042); 
	 * // will output: 1.3.4 alpha3
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
		return "$major.$minor.$micro ".self::$mStatusName[$status];
	} // formatVersion

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
		if (preg_match('/(\d{1,3})\.?(\d{1,3})\.?(\d{1,3})\s*(\w*)/', $aVersion, $matches)) {
			$stat = mb_strtolower($matches[4]);
			$v = array_search($stat, self::$mStatusName);
			if ($v === false) $v = 9; // assume stable
			return (($matches[1] * 1000 + $matches[2]) * 1000 + $matches[3]) * 10 + $v;
		} // if
		return 0;
	} // encodeVersion
	
} // class Repository

?>