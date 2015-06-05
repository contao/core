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
 * Provide methods to manage front end controllers.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
abstract class Frontend extends \Controller
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
	 *
	 * Make the constructor public, so pages can be instantiated (see #6182)
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}


	/**
	 * Split the current request into fragments, strip the URL suffix, recreate the $_GET array and return the page ID
	 *
	 * @return mixed
	 */
	public static function getPageIdFromUrl()
	{
		if (\Config::get('disableAlias'))
		{
			return is_numeric(\Input::get('id')) ? \Input::get('id') : null;
		}

		if (\Environment::get('request') == '')
		{
			return null;
		}

		// Get the request string without the index.php fragment
		if (\Environment::get('request') == 'index.php')
		{
			$strRequest = '';
		}
		else
		{
			list($strRequest) = explode('?', str_replace('index.php/', '', \Environment::get('request')), 2);
		}

		// URL decode here (see #6232)
		$strRequest = rawurldecode($strRequest);

		// The request string must not contain "auto_item" (see #4012)
		if (strpos($strRequest, '/auto_item/') !== false)
		{
			return false;
		}

		// Remove the URL suffix if not just a language root (e.g. en/) is requested
		if ($strRequest != '' && (!\Config::get('addLanguageToUrl') || !preg_match('@^[a-z]{2}(\-[A-Z]{2})?/$@', $strRequest)))
		{
			$intSuffixLength = strlen(\Config::get('urlSuffix'));

			// Return false if the URL suffix does not match (see #2864)
			if ($intSuffixLength > 0)
			{
				if (substr($strRequest, -$intSuffixLength) != \Config::get('urlSuffix'))
				{
					return false;
				}

				$strRequest = substr($strRequest, 0, -$intSuffixLength);
			}
		}

		// Extract the language
		if (\Config::get('addLanguageToUrl'))
		{
			$arrMatches = array();

			// Use the matches instead of substr() (thanks to Mario Müller)
			if (preg_match('@^([a-z]{2}(\-[A-Z]{2})?)/(.*)$@', $strRequest, $arrMatches))
			{
				\Input::setGet('language', $arrMatches[1]);

				// Trigger the root page if only the language was given
				if ($arrMatches[3] == '')
				{
					return null;
				}

				$strRequest = $arrMatches[3];
			}
			else
			{
				return false; // Language not provided
			}
		}

		$arrFragments = null;

		// Use folder-style URLs
		if (\Config::get('folderUrl') && strpos($strRequest, '/') !== false)
		{
			$strAlias = $strRequest;
			$arrOptions = array($strAlias);

			// Compile all possible aliases by applying dirname() to the request (e.g. news/archive/item, news/archive, news)
			while ($strAlias != '/' && strpos($strAlias, '/') !== false)
			{
				$strAlias = dirname($strAlias);
				$arrOptions[] = $strAlias;
			}

			// Check if there are pages with a matching alias
			$objPages = \PageModel::findByAliases($arrOptions);

			if ($objPages !== null)
			{
				$arrPages = array();

				// Order by domain and language
				while ($objPages->next())
				{
					/** @var \PageModel $objModel */
					$objModel = $objPages->current();
					$objPage  = $objModel->loadDetails();

					$domain = $objPage->domain ?: '*';
					$arrPages[$domain][$objPage->rootLanguage][] = $objPage;

					// Also store the fallback language
					if ($objPage->rootIsFallback)
					{
						$arrPages[$domain]['*'][] = $objPage;
					}
				}

				$strHost = \Environment::get('host');

				// Look for a root page whose domain name matches the host name
				if (isset($arrPages[$strHost]))
				{
					$arrLangs = $arrPages[$strHost];
				}
				else
				{
					$arrLangs = $arrPages['*'] ?: array(); // empty domain
				}

				$arrAliases = array();

				// Use the first result (see #4872)
				if (!\Config::get('addLanguageToUrl'))
				{
					$arrAliases = current($arrLangs);
				}
				// Try to find a page matching the language parameter
				elseif (($lang = \Input::get('language')) != '' && isset($arrLangs[$lang]))
				{
					$arrAliases = $arrLangs[$lang];
				}

				// Return if there are no matches
				if (empty($arrAliases))
				{
					return false;
				}

				$objPage = $arrAliases[0];

				// The request consists of the alias only
				if ($strRequest == $objPage->alias)
				{
					$arrFragments = array($strRequest);
				}
				// Remove the alias from the request string, explode it and then re-insert the alias at the beginning
				else
				{
					$arrFragments = explode('/', substr($strRequest, strlen($objPage->alias) + 1));
					array_unshift($arrFragments, $objPage->alias);
				}
			}
		}

		// If folderUrl is deactivated or did not find a matching page
		if ($arrFragments === null)
		{
			if ($strRequest == '/')
			{
				return false;
			}
			else
			{
				$arrFragments = explode('/', $strRequest);
			}
		}

		// Add the second fragment as auto_item if the number of fragments is even
		if (\Config::get('useAutoItem') && count($arrFragments) % 2 == 0)
		{
			array_insert($arrFragments, 1, array('auto_item'));
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getPageIdFromUrl']) && is_array($GLOBALS['TL_HOOKS']['getPageIdFromUrl']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getPageIdFromUrl'] as $callback)
			{
				$arrFragments = static::importStatic($callback[0])->$callback[1]($arrFragments);
			}
		}

		// Return if the alias is empty (see #4702 and #4972)
		if ($arrFragments[0] == '' && count($arrFragments) > 1)
		{
			return false;
		}

		// Add the fragments to the $_GET array
		for ($i=1, $c=count($arrFragments); $i<$c; $i+=2)
		{
			// Skip key value pairs if the key is empty (see #4702)
			if ($arrFragments[$i] == '')
			{
				continue;
			}

			// Return false if there is a duplicate parameter (duplicate content) (see #4277)
			if (isset($_GET[$arrFragments[$i]]))
			{
				return false;
			}

			// Return false if the request contains an auto_item keyword (duplicate content) (see #4012)
			if (\Config::get('useAutoItem') && in_array($arrFragments[$i], $GLOBALS['TL_AUTO_ITEM']))
			{
				return false;
			}

			\Input::setGet(urldecode($arrFragments[$i]), urldecode($arrFragments[$i+1]), true);
		}

		return $arrFragments[0] ?: null;
	}


	/**
	 * Return the root page ID (backwards compatibility)
	 *
	 * @return integer
	 */
	public static function getRootIdFromUrl()
	{
		return static::getRootPageFromUrl()->id;
	}


	/**
	 * Try to find a root page based on language and URL
	 *
	 * @return \PageModel
	 */
	public static function getRootPageFromUrl()
	{
		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getRootPageFromUrl']) && is_array($GLOBALS['TL_HOOKS']['getRootPageFromUrl']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getRootPageFromUrl'] as $callback)
			{
				/** @var \PageModel $objRootPage */
				if (is_object(($objRootPage = static::importStatic($callback[0])->$callback[1]())))
				{
					return $objRootPage;
				}
			}
		}

		$host = \Environment::get('host');

		// The language is set in the URL
		if (\Config::get('addLanguageToUrl') && !empty($_GET['language']))
		{
			$objRootPage = \PageModel::findFirstPublishedRootByHostAndLanguage($host, \Input::get('language'));

			// No matching root page found
			if ($objRootPage === null)
			{
				header('HTTP/1.1 404 Not Found');
				\System::log('No root page found (host "' . $host . '", language "'. \Input::get('language') .'")', __METHOD__, TL_ERROR);
				die_nicely('be_no_root', 'No root page found');
			}
		}

		// No language given
		else
		{
			$accept_language = \Environment::get('httpAcceptLanguage');

			// Find the matching root pages (thanks to Andreas Schempp)
			$objRootPage = \PageModel::findFirstPublishedRootByHostAndLanguage($host, $accept_language);

			// No matching root page found
			if ($objRootPage === null)
			{
				header('HTTP/1.1 404 Not Found');
				\System::log('No root page found (host "' . \Environment::get('host') . '", languages "'.implode(', ', \Environment::get('httpAcceptLanguage')).'")', __METHOD__, TL_ERROR);
				die_nicely('be_no_root', 'No root page found');
			}

			// Redirect to the language root (e.g. en/)
			if (\Config::get('addLanguageToUrl') && !\Config::get('doNotRedirectEmpty') && \Environment::get('request') == '')
			{
				static::redirect((!\Config::get('rewriteURL') ? 'index.php/' : '') . $objRootPage->language . '/', 301);
			}
		}

		return $objRootPage;
	}


	/**
	 * Overwrite the parent method as front end URLs are handled differently
	 *
	 * @param string  $strRequest
	 * @param boolean $blnIgnoreParams
	 * @param array   $arrUnset
	 *
	 * @return string
	 */
	public static function addToUrl($strRequest, $blnIgnoreParams=false, $arrUnset=array())
	{
		$arrGet = $blnIgnoreParams ? array() : $_GET;

		// Clean the $_GET values (thanks to thyon)
		foreach (array_keys($arrGet) as $key)
		{
			$arrGet[$key] = \Input::get($key, true, true);
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
		if (\Config::get('addLanguageToUrl'))
		{
			unset($arrGet['language']);
		}

		// Determine connector and separator
		if (\Config::get('disableAlias'))
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
			if (!\Config::get('disableAlias') && \Config::get('useAutoItem') && ($k == 'auto_item' || in_array($k, $GLOBALS['TL_AUTO_ITEM'])))
			{
				$strParams .= $strConnector . urlencode($v);
			}
			else
			{
				$strParams .= $strConnector . urlencode($k) . $strSeparator . urlencode($v);
			}
		}

		// Do not use aliases
		if (\Config::get('disableAlias'))
		{
			return 'index.php?' . preg_replace('/^&(amp;)?/i', '', $strParams);
		}

		/** @var \PageModel $objPage */
		global $objPage;

		$pageId = $objPage->alias ?: $objPage->id;

		// Get the page ID from URL if not set
		if (empty($pageId))
		{
			$pageId = static::getPageIdFromUrl();
		}

		$pageLanguage = '';

		// Add the language
		if (\Config::get('addLanguageToUrl'))
		{
			$pageLanguage = $objPage->rootLanguage . '/';
		}

		return (\Config::get('rewriteURL') ? '' : 'index.php/') . $pageLanguage . $pageId . $strParams . \Config::get('urlSuffix');
	}


	/**
	 * Redirect to a jumpTo page or reload the current page
	 *
	 * @param integer|array $intId
	 * @param string        $strParams
	 * @param string        $strForceLang
	 */
	protected function jumpToOrReload($intId, $strParams=null, $strForceLang=null)
	{
		/** @var \PageModel $objPage */
		global $objPage;

		// Always redirect if there are additional arguments (see #5734)
		$blnForceRedirect = ($strParams !== null || $strForceLang !== null);

		if (is_array($intId))
		{
			if ($intId['id'] != '')
			{
				if ($intId['id'] != $objPage->id  || $blnForceRedirect)
				{
					$this->redirect($this->generateFrontendUrl($intId, $strParams, $strForceLang));
				}
			}
		}
		elseif ($intId > 0)
		{
			if ($intId != $objPage->id || $blnForceRedirect)
			{
				if (($objNextPage = \PageModel::findPublishedById($intId)) !== null)
				{
					$this->redirect($this->generateFrontendUrl($objNextPage->row(), $strParams, $strForceLang));
				}
			}
		}

		$this->reload();
	}


	/**
	 * Check whether a back end or front end user is logged in
	 *
	 * @param string $strCookie
	 *
	 * @return boolean
	 */
	protected function getLoginStatus($strCookie)
	{
		$hash = sha1(session_id() . (!\Config::get('disableIpCheck') ? \Environment::get('ip') : '') . $strCookie);

		// Validate the cookie hash
		if (\Input::cookie($strCookie) == $hash)
		{
			// Try to find the session
			$objSession = \SessionModel::findByHashAndName($hash, $strCookie);

			// Validate the session ID and timeout
			if ($objSession !== null && $objSession->sessionID == session_id() && (\Config::get('disableIpCheck') || $objSession->ip == \Environment::get('ip')) && ($objSession->tstamp + \Config::get('sessionTimeout')) > time())
			{
				// Disable the cache if a back end user is logged in
				if (TL_MODE == 'FE' && $strCookie == 'BE_USER_AUTH')
				{
					$_SESSION['DISABLE_CACHE'] = true;

					// Always return false if we are not in preview mode (show hidden elements)
					if (!\Input::cookie('FE_PREVIEW'))
					{
						$_SESSION['TL_USER_LOGGED_IN'] = false; // backwards compatibility
						return false;
					}
				}

				// The session could be verified
				$_SESSION['TL_USER_LOGGED_IN'] = true; // backwards compatibility
				return true;
			}
		}

		// Reset the cache settings
		if (TL_MODE == 'FE' && $strCookie == 'BE_USER_AUTH')
		{
			$_SESSION['DISABLE_CACHE'] = false;
		}

		// The session could not be verified
		$_SESSION['TL_USER_LOGGED_IN'] = false; // backwards compatibility

		// Remove the cookie if it is invalid to enable loading cached pages
		$this->setCookie($strCookie, $hash, (time() - 86400), null, null, false, true);

		return false;
	}


	/**
	 * Get the meta data from a serialized string
	 *
	 * @param string $strData
	 * @param string $strLanguage
	 *
	 * @return array
	 */
	public static function getMetaData($strData, $strLanguage)
	{
		$arrData = deserialize($strData);

		// Convert the language to a locale (see #5678)
		$strLanguage = str_replace('-', '_', $strLanguage);

		if (!is_array($arrData) || !isset($arrData[$strLanguage]))
		{
			return array();
		}

		return $arrData[$strLanguage];
	}


	/**
	 * Parse the meta.txt file of a folder
	 *
	 * @param string  $strPath
	 * @param boolean $blnIsFile
	 *
	 * @deprecated Meta data is now stored in the database
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
		$strBuffer = utf8_convert_encoding($strBuffer, \Config::get('characterSet'));
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
	 *
	 * @param string $strText
	 *
	 * @return string
	 */
	protected function prepareMetaDescription($strText)
	{
		$strText = $this->replaceInsertTags($strText);
		$strText = strip_tags($strText);
		$strText = str_replace("\n", ' ', $strText);
		$strText = \String::substr($strText, 180);

		return trim($strText);
	}


	/**
	 * Return the cron timeout in seconds
	 *
	 * @return integer
	 */
	public static function getCronTimeout()
	{
		if (!empty($GLOBALS['TL_CRON']['minutely']))
		{
			return 60;
		}
		elseif (!empty($GLOBALS['TL_CRON']['hourly']))
		{
			return 3600;
		}
		else
		{
			return 86400; // daily
		}
	}
}
