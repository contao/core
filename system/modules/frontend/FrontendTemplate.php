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
 * Class FrontendTemplate
 *
 * Provide methods to handle front end templates.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class FrontendTemplate extends Template
{

	/**
	 * Add a hook to modify the template output
	 * @return string
	 */
	public function parse()
	{
		$strBuffer = parent::parse();

		// HOOK: add custom parse filters
		if (isset($GLOBALS['TL_HOOKS']['parseFrontendTemplate']) && is_array($GLOBALS['TL_HOOKS']['parseFrontendTemplate']))
		{
			foreach ($GLOBALS['TL_HOOKS']['parseFrontendTemplate'] as $callback)
			{
				$this->import($callback[0]);
				$strBuffer = $this->$callback[0]->$callback[1]($strBuffer, $this->strTemplate);
			}
		}

		return $strBuffer;
	}


	/**
	 * Parse the template file, replace insert tags and print it to the screen
	 */
	public function output()
	{
		global $objPage;

		// Ignore certain URL parameters 
		$arrIgnore = array('id', 'file', 'token', 'page', 'day', 'month', 'year');

		if ($GLOBALS['TL_CONFIG']['useAutoItem'])
		{
			$arrIgnore[] = 'auto_item';
		}
		if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'])
		{
			$arrIgnore[] = 'language';
		}

		$strParams = '';

		// Rebuild the URL to eliminate duplicate parameters
		foreach (array_keys($_GET) as $key)
		{
			if (!in_array($key, $arrIgnore))
			{
				if ($GLOBALS['TL_CONFIG']['useAutoItem'] && in_array($key, $GLOBALS['TL_AUTO_ITEM']))
				{
					$strParams .= '/' . $this->Input->get($key);
				}
				else
				{
					$strParams .= '/' . $key . '/' . $this->Input->get($key);
				}
			}
		}

		$strUrl = $this->generateFrontendUrl($objPage->row(), $strParams);

		// Add the page number
		if (isset($_GET['page']))
		{
			$strUrl .= ($GLOBALS['TL_CONFIG']['disableAlias'] ? '&page=' : '?page=') . $this->Input->get('page');
		}

		$this->keywords = '';
		$arrKeywords = array_map('trim', explode(',', $GLOBALS['TL_KEYWORDS']));

		// Add the meta keywords
		if (strlen($arrKeywords[0]))
		{
			$this->keywords = str_replace(array("\n", "\r", '"'), array(' ' , '', ''), implode(', ', array_unique($arrKeywords)));
		}

		// Parse the template
		$strBuffer = str_replace(' & ', ' &amp; ', $this->parse());

		// HOOK: add custom output filters
		if (isset($GLOBALS['TL_HOOKS']['outputFrontendTemplate']) && is_array($GLOBALS['TL_HOOKS']['outputFrontendTemplate']))
		{
			foreach ($GLOBALS['TL_HOOKS']['outputFrontendTemplate'] as $callback)
			{
				$this->import($callback[0]);
				$strBuffer = $this->$callback[0]->$callback[1]($strBuffer, $this->strTemplate);
			}
		}

		$intCache = 0;

		// Decide whether the page shall be cached
		if (empty($_POST) && !BE_USER_LOGGED_IN && !FE_USER_LOGGED_IN && !$_SESSION['DISABLE_CACHE'] && !isset($_SESSION['LOGIN_ERROR']) && intval($objPage->cache) > 0 && !$objPage->protected)
		{
			$intCache = time() + intval($objPage->cache);
		}

		// Server-side cache
		if ($intCache > 0 && ($GLOBALS['TL_CONFIG']['cacheMode'] == 'both' || $GLOBALS['TL_CONFIG']['cacheMode'] == 'server'))
		{
			// If the request string is empty, use a special cache tag which considers the page language
			if ($this->Environment->request == '' || $this->Environment->request == 'index.php')
			{
				$strCacheKey = $this->Environment->base . 'empty.' . $objPage->language;
			}
			else
			{
				$strCacheKey = $this->Environment->base . $strUrl;
			}

			// HOOK: add custom logic
			if (isset($GLOBALS['TL_HOOKS']['getCacheKey']) && is_array($GLOBALS['TL_HOOKS']['getCacheKey']))
			{
				foreach ($GLOBALS['TL_HOOKS']['getCacheKey'] as $callback)
				{
					$this->import($callback[0]);
					$strCacheKey = $this->$callback[0]->$callback[1]($strCacheKey);
				}
			}

			// Replace insert tags for caching
			$strBuffer = $this->replaceInsertTags($strBuffer, true);

			// Create the cache file
			$objFile = new File('system/tmp/' . md5($strCacheKey) . '.html');
			$objFile->write('<?php $expire = ' . $intCache . '; /* ' . $strCacheKey . " */ ?>\n");
			$objFile->append($this->minifyHtml($strBuffer), '');
			$objFile->close();
		}

		// Client-side cache
		if (!headers_sent())
		{
			if ($intCache > 0 && ($GLOBALS['TL_CONFIG']['cacheMode'] == 'both' || $GLOBALS['TL_CONFIG']['cacheMode'] == 'browser'))
			{
				header('Cache-Control: public, max-age=' . ($intCache - time()));
				header('Expires: ' . gmdate('D, d M Y H:i:s', $intCache) . ' GMT');
				header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
				header('Pragma: public');
			}
			else
			{
				header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
				header('Pragma: no-cache');
				header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
				header('Expires: Fri, 06 Jun 1975 15:10:00 GMT');
			}
		}

		// Replace insert tags and then re-replace the request_token tag in case a form element has been loaded via insert tag
		$this->strBuffer = $this->replaceInsertTags($strBuffer);
		$this->strBuffer = str_replace(array('{{request_token}}', '[{]', '[}]'), array(REQUEST_TOKEN, '{{', '}}'), $this->strBuffer);

		// Index page if searching is allowed and there is no back end user
		if ($GLOBALS['TL_CONFIG']['enableSearch'] && $objPage->type == 'regular' && !BE_USER_LOGGED_IN && !$objPage->noSearch)
		{
			// Index protected pages if enabled
			if ($GLOBALS['TL_CONFIG']['indexProtected'] || (!FE_USER_LOGGED_IN && !$objPage->protected))
			{
				$this->import('Search');

				$arrData = array
				(
					'url' => $strUrl,
					'content' => $this->strBuffer,
					'title' => (strlen($objPage->pageTitle) ? $objPage->pageTitle : $objPage->title),
					'protected' => ($objPage->protected ? '1' : ''),
					'groups' => $objPage->groups,
					'pid' => $objPage->id,
					'language' => $objPage->language
				);

				$this->Search->indexPage($arrData);
			}
		}

		parent::output();
	}


	/**
	 * Return a custom layout section
	 * @param string
	 * @return string
	 */
	public function getCustomSection($strKey)
	{
		return sprintf("\n<div id=\"%s\">\n%s\n</div>\n", $strKey, (strlen($this->sections[$strKey]) ? $this->sections[$strKey] : '&nbsp;'));
	}


	/**
	 * Return all custom layout sections
	 * @param string
	 * @return string
	 */
	public function getCustomSections($strKey=null)
	{
		if ($strKey != '' && $this->sPosition != $strKey)
		{
			return '';
		}

		$tag = 'div';

		if ($strKey == 'main')
		{
			global $objPage;

			// Use the section tag in HTML5
			if ($objPage->outputFormat == 'html5')
			{
				$tag = 'section';
			}
		}

		$sections = '';

		// Standardize the IDs (thanks to Tsarma) (see #4251)
		foreach ($this->sections as $k=>$v)
		{
			$sections .= "\n" . '<' . $tag . ' id="' . standardize($k, true) . '">' . "\n" . '<div class="inside">' . "\n" . $v . "\n" . '</div>' . "\n" . '</' . $tag . '>' . "\n";
		}

		if ($sections == '')
		{
			return '';
		}

		return "\n" . '<div class="custom">' . "\n" . $sections . "\n" . '</div>' . "\n";
	}
}

?>