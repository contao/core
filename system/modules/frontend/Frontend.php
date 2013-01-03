<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class Frontend
 *
 * Provide methods to manage front end controllers.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
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
	 * Load the database object
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

		$strRequest = preg_replace(array('/^index.php\/?/', '/\?.*$/'), '', $this->Environment->request);

		// Remove the URL suffix if not just a language root (e.g. en/) is requested
		if ($strRequest != '' && (!$GLOBALS['TL_CONFIG']['addLanguageToUrl'] || !preg_match('@^[a-z]{2}/$@', $strRequest)))
		{
			$intSuffixLength = strlen($GLOBALS['TL_CONFIG']['urlSuffix']);

			// Return false if the URL suffix does not match (see #2864)
			if ($intSuffixLength > 0)
			{
				if (substr($strRequest, -$intSuffixLength) != $GLOBALS['TL_CONFIG']['urlSuffix'])
				{
					return false;
				}

				$strRequest = substr($strRequest, 0, -$intSuffixLength);
			}
		}

		$arrFragments = explode('/', $strRequest);

		// Extract the language
		if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'])
		{
			// No language provided if there is only one fragment (see #4043)
			if (count($arrFragments) == 1 || !preg_match('/^[a-z]{2}$/', $arrFragments[0]))
			{
				return false; // Language not provided
			}
			else
			{
				$this->Input->setGet('language', $arrFragments[0]);
				array_shift($arrFragments);
			}
		}

		// Add the second fragment as auto_item if the number of fragments is even
		if ($GLOBALS['TL_CONFIG']['useAutoItem'] && count($arrFragments) % 2 == 0)
		{
			array_insert($arrFragments, 1, array('auto_item'));
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
			// Return false the request contains an auto_item keyword (duplicate content) (see #4012)
			if ($GLOBALS['TL_CONFIG']['useAutoItem'] && in_array($arrFragments[$i], $GLOBALS['TL_AUTO_ITEM']))
			{
				return false;
			}

			$this->Input->setGet($arrFragments[$i], $arrFragments[$i+1]);
		}

		return ($arrFragments[0] != '') ? $arrFragments[0] : null;
	}


	/**
	 * Return the root page ID (backwards compatibility)
	 * @return integer
	 */
	protected function getRootIdFromUrl()
	{
		$objRootPage = $this->getRootPageFromUrl();
		return $objRootPage->numRows ? $objRootPage->id : 0;
	}


	/**
	 * Try to find a root page based on language and URL
	 * @return Database_Result
	 */
	protected function getRootPageFromUrl()
	{
		$time = time();
		$host = $this->Environment->host;

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getRootPageFromUrl']) && is_array($GLOBALS['TL_HOOKS']['getRootPageFromUrl']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getRootPageFromUrl'] as $callback)
			{
				$this->import($callback[0]);
				$objRootPage = $this->$callback[0]->$callback[1]();

				if ($objRootPage instanceof Database_Result)
				{
					return $objRootPage;
				}
			}
		}

		// The language is set in the URL
		if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'] && !empty($_GET['language']))
		{
			$objRootPage = $this->Database->prepare("SELECT id, dns, language, fallback FROM tl_page WHERE type='root' AND (dns=? OR dns='') AND language=?" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : "") . " ORDER BY dns DESC, fallback")
										  ->limit(1)
										  ->execute($host, $this->Input->get('language'));

			// No matching root page found
			if ($objRootPage->numRows < 1)
			{
				header('HTTP/1.1 404 Not Found');
				$this->log('No root page found (host "' . $host . '", language "'. $this->Input->get('language') .'"', 'Frontend getRootPageFromUrl()', TL_ERROR);
				die('No root page found');
			}
		}

		// No language given
		else
		{
			$accept_language = $this->Environment->httpAcceptLanguage;

			// Find the matching root pages (thanks to Andreas Schempp)
			$objRootPage = $this->Database->prepare("SELECT id, dns, language, fallback FROM tl_page WHERE type='root' AND (dns=? OR dns='')" . (!empty($accept_language) ? " AND (language IN('". implode("','", $accept_language) ."') OR fallback=1)" : " AND fallback=1") . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : "") . " ORDER BY dns DESC" . (!empty($accept_language) ? ", " . $this->Database->findInSet('language', array_reverse($accept_language)) . " DESC" : "") . ", sorting")
										  ->limit(1)
										  ->execute($host);

			// No matching root page found
			if ($objRootPage->numRows < 1)
			{
				header('HTTP/1.1 404 Not Found');
				$this->log('No root page found (host "' . $this->Environment->host . '", languages "'.implode(', ', $this->Environment->httpAcceptLanguage).'")', 'Frontend getRootPageFromUrl()', TL_ERROR);
				die('No root page found');
			}

			// Redirect to the language root (e.g. en/)
			if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'] && !$GLOBALS['TL_CONFIG']['doNotRedirectEmpty'] && $this->Environment->request == '')
			{
				$this->redirect((!$GLOBALS['TL_CONFIG']['rewriteURL'] ? 'index.php/' : '') . $objRootPage->language . '/', 302);
			}
		}

		return $objRootPage;
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

		// Unset the language parameter
		if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'])
		{
			unset($arrGet['language']);
		}

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

		$strParams = '';

		// Compile the parameters string
		foreach ($arrGet as $k=>$v)
		{
			// Omit the key if it is an auto_item key (see #5037)
			if (!$GLOBALS['TL_CONFIG']['disableAlias'] && $GLOBALS['TL_CONFIG']['useAutoItem'] && in_array($k, $GLOBALS['TL_AUTO_ITEM']))
			{
				$strParams .= $strConnector . urlencode($v);
			}
			else
			{
				$strParams .= $strConnector . urlencode($k) . $strSeparator . urlencode($v);
			}
		}

		// Do not use aliases
		if ($GLOBALS['TL_CONFIG']['disableAlias'])
		{
			return 'index.php?' . preg_replace('/^&(amp;)?/i', '', $strParams);
		}

		global $objPage;
		$pageId = ($objPage->alias != '') ? $objPage->alias : $objPage->id;

		// Get the page ID from URL if not set
		if (empty($pageId))
		{
			$pageId = $this->getPageIdFromUrl();
		}

		$pageLanguage = '';

		// Add the language
		if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'])
		{
			$pageLanguage = $objPage->rootLanguage . '/';
		}

		return ($GLOBALS['TL_CONFIG']['rewriteURL'] ? '' : 'index.php/') . $pageLanguage . $pageId . $strParams . $GLOBALS['TL_CONFIG']['urlSuffix'];
	}


	/**
	 * Redirect to a jumpTo page or reload the current page
	 * @param integer
	 * @param string
	 * @param string
	 */
	protected function jumpToOrReload($intId, $strParams=null, $strForceLang=null)
	{
		global $objPage;

		if (strlen($intId) && $intId != $objPage->id)
		{
			$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
										  ->limit(1)
										  ->execute($intId);

			if ($objNextPage->numRows)
			{
				$this->redirect($this->generateFrontendUrl($objNextPage->fetchAssoc(), $strParams, $strForceLang));
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
	 * @return string
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