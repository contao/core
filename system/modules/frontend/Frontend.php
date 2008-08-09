<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class Frontend
 *
 * Provide methods to manage front end controllers.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
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
			return strlen($this->Input->get('id')) ? $this->Input->get('id') : null;
		}

		if (!strlen($this->Environment->request))
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
		if (array_key_exists('getPageIdFromUrl', $GLOBALS['TL_HOOKS']) && is_array($GLOBALS['TL_HOOKS']['getPageIdFromUrl']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getPageIdFromUrl'] as $callback)
			{
				$this->import($callback[0]);
				$arrFragments = $this->$callback[0]->$callback[1]($arrFragments);
			}
		}

		// Add fragments to $_GET array
		for ($i=1; $i<count($arrFragments); $i+=2)
		{
			$_GET[urldecode($arrFragments[$i])] = urldecode($arrFragments[$i+1]);
		}

		return strlen($arrFragments[0]) ? urldecode($arrFragments[0]) : null;
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

		// Current host and current user language match a record
		$objRootPage = $this->Database->prepare("SELECT id FROM tl_page WHERE type=? AND (dns=? OR dns=?) AND language=?" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1" : ""))
									  ->limit(1)
									  ->execute('root', $host, 'www.'.$host, $accept_language[0], $time, $time);

		if ($objRootPage->numRows < 1)
		{
			// Current host matches a record (look for fallback language)
			$objRootPage = $this->Database->prepare("SELECT id FROM tl_page WHERE type=? AND (dns=? OR dns=?) AND fallback=1" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1" : ""))
										  ->limit(1)
										  ->execute('root', $host, 'www.'.$host, $time, $time);
		}

		if ($objRootPage->numRows < 1)
		{
			// Current user language matches a record (and DNS is empty)
			$objRootPage = $this->Database->prepare("SELECT id FROM tl_page WHERE type=? AND dns='' AND language=?" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1" : ""))
										  ->limit(1)
										  ->execute('root', $accept_language[0], $time, $time);
		}

		if ($objRootPage->numRows < 1)
		{
			// Current fallback language matches a record (and DNS is empty)
			$objRootPage = $this->Database->prepare("SELECT id FROM tl_page WHERE type=? AND dns='' AND fallback=1" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1" : ""))
										  ->limit(1)
										  ->execute('root', $time, $time);
		}

		return $objRootPage->numRows ? $objRootPage->id : 0;
	}


	/**
	 * Overwrite parent method as front end URLs are handled differently
	 * @param string
	 * @return string
	 */
	protected function addToUrl($strRequest)
	{
		$arrGet = $_GET;
		$arrFragments = preg_split('/&(amp;)?/i', $strRequest);

		foreach ($arrFragments as $strFragment)
		{
			$arrParams = explode('=', $strFragment);
			$arrGet[$arrParams[0]] = $arrParams[1];
		}

		$strParams = '';

		foreach ($arrGet as $k=>$v)
		{
			$strParams .= $GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' . $k . '=' . $v  : '/' . $k . '/' . $v;
		}

		if ($GLOBALS['TL_CONFIG']['disableAlias'])
		{
			return 'index.php?' . preg_replace('/^&(amp;)?/i', '', $strParams);
		}

		return ($GLOBALS['TL_CONFIG']['rewriteURL'] ? '' : 'index.php/') . $this->getPageIdFromUrl() . $strParams . $GLOBALS['TL_CONFIG']['urlSuffix'];
	}


	/**
	 * Redirect to a jumpTo page or reload the current page
	 * @param integer
	 */
	protected function jumpToOrReload($intId)
	{
		if (strlen($intId))
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
		if (TL_MODE == 'FE' && $strCookie == 'BE_USER_AUTH' && !$this->Input->cookie('FE_PREVIEW'))
		{
			return false;
		}

		$hash = sha1(session_id() . $this->Environment->ip . $strCookie);

		if ($this->Input->cookie($strCookie) == $hash)
		{
			$objSession = $this->Database->prepare("SELECT * FROM tl_session WHERE hash=? AND name=?")
										 ->limit(1)
										 ->execute($hash, $strCookie);

			if ($objSession->numRows && $objSession->sessionID == session_id() && $objSession->ip == $this->Environment->ip && ($objSession->tstamp + $GLOBALS['TL_CONFIG']['sessionTimeout']) > time())
			{
				return true;
			}
		}

		return false;
	}


	/**
	 * Take an array of four margin values and the current unit and compile the margin style definition
	 * @param array
	 * @param string
	 * @return string
	 */
	protected function generateMargin($arrValues, $strType='margin')
	{
		$top = $arrValues['top'];
		$right = $arrValues['right'];
		$bottom = $arrValues['bottom'];
		$left = $arrValues['left'];

		// Try to shorten definition
		if (strlen($top) && strlen($right) && strlen($bottom) && strlen($left))
		{
			if ($top == $right && $top == $bottom && $top == $left)
			{
				return $strType . ':' . $top . $arrValues['unit'] . ';';
			}

			elseif ($top == $bottom && $right == $left)
			{
				return $strType . ':' . $top . $arrValues['unit'] . ' ' . $left . $arrValues['unit'] . ';';
			}

			else
			{
				return $strType . ':' . $top . $arrValues['unit'] . ' ' . $right . $arrValues['unit'] . ' ' . $bottom . $arrValues['unit'] . ' ' . $left . $arrValues['unit'] . ';';
			}
		}

		$arrDir = array
		(
			'top'=>$top,
			'right'=>$right,
			'bottom'=>$bottom,
			'left'=>$left
		);

		$return = array();

		foreach ($arrDir as $k=>$v)
		{
			if (strlen($v))
			{
				$return[] = $strType . '-' . $k . ':' . $v . $arrValues['unit'] . ';';
			}
		}

		return implode(' ', $return);
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
		$arrBuffer = trimsplit('[\n\r]+', $strBuffer);

		foreach ($arrBuffer as $v)
		{
			$pos = strpos($v, '=');

			$strLabel = trim(utf8_substr($v, 0, $pos));
			$strValue = utf8_substr($v, ($pos + 1));

			$this->arrMeta[$strLabel] = array_map('trim', explode('|', $strValue));

			if (!$blnIsFile || in_array($strPath . '/' . $strLabel, $this->multiSRC))
			{
				$this->arrAux[] = $strPath . '/' . $strLabel;
			}
		}

		$this->arrProcessed[] = $strPath;
	}
}

?>