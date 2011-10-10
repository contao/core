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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class Frontend
 *
 * Provide methods to manage front end controllers.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
abstract class Frontend extends Controller
{

	/**
	 * Meta array
	 * @var array
	 */
	protected $arrMeta = array();

	/**
	 * Aux array
	 * @var array
	 */
	protected $arrAux = array();

	/**
	 * Processed files array
	 * @var array
	 */
	protected $arrProcessed = array();


	/**
	 * Load database object
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}


	/**
	 * Split the current request into fragments, strip the URL suffix, recreate the $_GET array and return the page ID
	 * @return mixed
	 */
	protected function getPageIdFromUrl()
	{
		if ($GLOBALS['TL_CONFIG']['disableAlias'])
		{
			return is_numeric($this->Input->get('id')) ? $this->Input->get('id') : null;
		}

		if ($this->Environment->request == '')
		{
			return null;
		}

		$strRequest = preg_replace('/\?.*$/i', '', $this->Environment->request);
		$strRequest = preg_replace('/' . preg_quote($GLOBALS['TL_CONFIG']['urlSuffix'], '/') . '$/i', '', $strRequest);
		$arrFragments = explode('/', $strRequest);

		// Skip index.php
		if (strtolower($arrFragments[0]) == 'index.php')
		{
			array_shift($arrFragments);
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getPageIdFromUrl']) && is_array($GLOBALS['TL_HOOKS']['getPageIdFromUrl']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getPageIdFromUrl'] as $callback)
			{
				$this->import($callback[0]);
				$arrFragments = $this->$callback[0]->$callback[1]($arrFragments);
			}
		}

		$arrFragments = array_map('urldecode', $arrFragments);

		// Add the fragments to the $_GET array
		for ($i=1; $i<count($arrFragments); $i+=2)
		{
			$this->Input->setGet($arrFragments[$i], $arrFragments[$i+1]);
		}

		return ($arrFragments[0] != '') ? $arrFragments[0] : null;
	}


	/**
	 * Try to find a root page based on language and URL and return its ID
	 * @return integer
	 */
	protected function getRootIdFromUrl()
	{
		$host = $this->Environment->host;
		$accept_language = $this->Environment->httpAcceptLanguage;
		$time = time();

		// Find the matching root pages (thanks to Andreas Schempp)
		$objRootPage = $this->Database->prepare("SELECT id, dns, language, fallback FROM tl_page WHERE type='root' AND (dns=? OR dns='')" . ((count($accept_language) > 0) ? " AND (language IN('". implode("','", $accept_language) ."') OR fallback=1)" : " AND fallback=1") . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : "") . " ORDER BY dns DESC" . ((count($accept_language) > 0) ? ", " . $this->Database->findInSet('language', array_reverse($accept_language)) . " DESC" : "") . ", sorting")
									  ->limit(1)
									  ->execute($host);

		return $objRootPage->numRows ? $objRootPage->id : 0;
	}


	/**
	 * Overwrite the parent method as front end URLs are handled differently
	 * @param string
	 * @param boolean
	 * @return string
	 */
	protected function addToUrl($strRequest, $blnIgnoreParams=false)
	{
		$arrGet = $blnIgnoreParams ? array() : $_GET;

		// Clean the $_GET values (thanks to thyon)
		foreach (array_keys($arrGet) as $key)
		{
			$arrGet[$key] = $this->Input->get($key, true);
		}

		$arrFragments = preg_split('/&(amp;)?/i', $strRequest);

		// Merge the new request string
		foreach ($arrFragments as $strFragment)
		{
			list($key, $value) = explode('=', $strFragment);

			if ($value == '')
			{
				unset($arrGet[$key]);
			}
			else
			{
				$arrGet[$key] = $value;
			}
		}

		$strParams = '';

		// Determine connector and separator
		if ($GLOBALS['TL_CONFIG']['disableAlias'])
		{
			$strConnector = '&amp;';
			$strSeparator = '=';
		}
		else
		{
			$strConnector = '/';
			$strSeparator = '/';
		}

		// Compile the parameters string
		foreach ($arrGet as $k=>$v)
		{
			$strParams .= $strConnector . urlencode($k) . $strSeparator . urlencode($v);
		}

		// Do not use aliases
		if ($GLOBALS['TL_CONFIG']['disableAlias'])
		{
			return 'index.php?' . preg_replace('/^&(amp;)?/i', '', $strParams);
		}

		global $objPage;
		$pageId = strlen($objPage->alias) ? $objPage->alias : $objPage->id;

		// Get page ID from URL if not set
		if (empty($pageId))
		{
			$pageId = $this->getPageIdFromUrl();
		}

		return ($GLOBALS['TL_CONFIG']['rewriteURL'] ? '' : 'index.php/') . $pageId . $strParams . $GLOBALS['TL_CONFIG']['urlSuffix'];
	}


	/**
	 * Redirect to a jumpTo page or reload the current page
	 * @param integer
	 */
	protected function jumpToOrReload($intId)
	{
		global $objPage;

		if (strlen($intId) && $intId != $objPage->id)
		{
			$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
										  ->limit(1)
										  ->execute($intId);

			if ($objNextPage->numRows)
			{
				$this->redirect($this->generateFrontendUrl($objNextPage->fetchAssoc()));
			}
		}

		$this->reload();
	}


	/**
	 * Check whether a back end or front end user is logged in
	 * @param string
	 * @return boolean
	 */
	protected function getLoginStatus($strCookie)
	{
		$hash = sha1(session_id() . (!$GLOBALS['TL_CONFIG']['disableIpCheck'] ? $this->Environment->ip : '') . $strCookie);

		// Validate the cookie hash
		if ($this->Input->cookie($strCookie) == $hash)
		{
			// Try to find the session
			$objSession = $this->Database->prepare("SELECT * FROM tl_session WHERE hash=? AND name=?")
										 ->limit(1)
										 ->execute($hash, $strCookie);

			// Validate the session ID and timeout
			if ($objSession->numRows && $objSession->sessionID == session_id() && ($GLOBALS['TL_CONFIG']['disableIpCheck'] || $objSession->ip == $this->Environment->ip) && ($objSession->tstamp + $GLOBALS['TL_CONFIG']['sessionTimeout']) > time())
			{
				// Disable the cache if a back end user is logged in
				if (TL_MODE == 'FE' && $strCookie == 'BE_USER_AUTH')
				{
					$_SESSION['DISABLE_CACHE'] = true;

					// Always return false if we are not in preview mode (show hidden elements)
					if (!$this->Input->cookie('FE_PREVIEW'))
					{
						$_SESSION['TL_USER_LOGGED_IN'] = false;
						return false;
					}
				}

				// The session could be verified
				$_SESSION['TL_USER_LOGGED_IN'] = true;
				return true;
			}
		}

		// Reset the cache settings
		if (TL_MODE == 'FE' && $strCookie == 'BE_USER_AUTH')
		{
			$_SESSION['DISABLE_CACHE'] = false;
		}

		// The session could not be verified
		$_SESSION['TL_USER_LOGGED_IN'] = false;
		return false;
	}


	/**
	 * Parse the meta.txt file of a folder
	 * @param string
	 * @param boolean
	 */
	protected function parseMetaFile($strPath, $blnIsFile=false)
	{
		if (in_array($strPath, $this->arrProcessed))
		{
			return;
		}

		$strFile = $strPath . '/meta_' . $GLOBALS['TL_LANGUAGE'] . '.txt';

		if (!file_exists(TL_ROOT . '/' . $strFile))
		{
			$strFile = $strPath . '/meta.txt';

			if (!file_exists(TL_ROOT . '/' . $strFile))
			{
				return;
			}
		}

		$strBuffer = file_get_contents(TL_ROOT . '/' . $strFile);
		$strBuffer = utf8_convert_encoding($strBuffer, $GLOBALS['TL_CONFIG']['characterSet']);
		$arrBuffer = array_filter(trimsplit('[\n\r]+', $strBuffer));

		foreach ($arrBuffer as $v)
		{
			list($strLabel, $strValue) = array_map('trim', explode('=', $v, 2));
			$this->arrMeta[$strLabel] = array_map('trim', explode('|', $strValue));

			if (!$blnIsFile || in_array($strPath . '/' . $strLabel, $this->multiSRC))
			{
				$this->arrAux[] = $strPath . '/' . $strLabel;
			}
		}

		$this->arrProcessed[] = $strPath;
	}


	/**
	 * Prepare a text to be used in the meta description tag
	 * @param string
	 * @return boolean
	 */
	protected function prepareMetaDescription($strText)
	{
		$this->import('String');

		$strText = $this->replaceInsertTags($strText);
		$strText = strip_tags($strText);
		$strText = str_replace("\n", ' ', $strText);
		$strText = $this->String->substr($strText, 180);

		return trim($strText);
	}
}

?>