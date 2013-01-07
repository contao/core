<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class Messages
 *
 * Add system messages to the welcome screen.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class Messages extends \Backend
{

	/**
	 * Check for the latest Contao version
	 * @return string
	 */
	public function versionCheck()
	{
		if (!empty($GLOBALS['TL_CONFIG']['latestVersion']) && version_compare(VERSION . '.' . BUILD, $GLOBALS['TL_CONFIG']['latestVersion'], '<'))
		{
			return '<p class="tl_info"><a href="contao/main.php?do=maintenance">' . sprintf($GLOBALS['TL_LANG']['MSC']['updateVersion'], $GLOBALS['TL_CONFIG']['latestVersion']) . '</a></p>';
		}

		return '';
	}


	/**
	 * Return the date of the last login
	 * @return string
	 */
	public function lastLogin()
	{
		$this->import('BackendUser', 'User');

		if ($this->User->lastLogin > 0)
		{
			return '<p class="tl_info">' . sprintf($GLOBALS['TL_LANG']['MSC']['lastLogin'][1], $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $this->User->lastLogin)) . '</p>';
		}

		return '';
	}


	/**
	 * Show a warning if there is no language fallback page
	 * @return string
	 */
	public function languageFallback()
	{
		$arrRoots = array();
		$time = time();
		$objRoots = $this->Database->execute("SELECT fallback, dns FROM tl_page WHERE type='root' AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1 ORDER BY dns");

		while ($objRoots->next())
		{
			$strDns = $objRoots->dns ?: '*';

			if (isset($arrRoots[$strDns]) && $arrRoots[$strDns] == 1)
			{
				continue;
			}

			$arrRoots[$strDns] = $objRoots->fallback;
		}

		$arrReturn = array();

		foreach ($arrRoots as $k=>$v)
		{
			if ($v != '')
			{
				continue;
			}

			if ($k == '*')
			{
				$arrReturn[] = '<p class="tl_error">' . $GLOBALS['TL_LANG']['ERR']['noFallbackEmpty'] . '</p>';
			}
			else
			{
				$arrReturn[] = '<p class="tl_error">' . sprintf($GLOBALS['TL_LANG']['ERR']['noFallbackDns'], $k) . '</p>';
			}
		}

		return implode("\n", $arrReturn);
	}


	/**
	 * Show a warning if there are non-root pages on the top-level
	 * @return string
	 */
	public function topLevelRoot()
	{
		$objCount = $this->Database->execute("SELECT COUNT(*) AS count FROM tl_page WHERE pid=0 AND type!='root'");

		if ($objCount->count > 0)
		{
			return '<p class="tl_error">' . $GLOBALS['TL_LANG']['ERR']['topLevelRegular'] . '</p>';
		}

		return '';
	}
}
