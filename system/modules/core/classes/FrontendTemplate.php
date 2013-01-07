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
 * Class FrontendTemplate
 *
 * Provide methods to handle front end templates.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class FrontendTemplate extends \Template
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
		$arrIgnore = array('id', 'file', 'token', 'day', 'month', 'year');

		if ($GLOBALS['TL_CONFIG']['useAutoItem'])
		{
			$arrIgnore[] = 'auto_item';
		}
		if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'])
		{
			$arrIgnore[] = 'language';
		}

		$strParams = '';
		$arrPageParams = array();

		// Rebuild the URL to eliminate duplicate parameters
		foreach (array_keys($_GET) as $key)
		{
			if (!in_array($key, $arrIgnore))
			{
				if (preg_match('/^page/', $key))
				{
					$arrPageParams[] = $key;
					continue; // see #4141
				}

				if ($GLOBALS['TL_CONFIG']['useAutoItem'] && in_array($key, $GLOBALS['TL_AUTO_ITEM']))
				{
					$strParams .= '/' . \Input::get($key);
				}
				else
				{
					$strParams .= '/' . $key . '/' . \Input::get($key);
				}
			}
		}

		$strUrl = $this->generateFrontendUrl($objPage->row(), $strParams);

		sort($arrPageParams); // see #4141
		$strGlue = (!$GLOBALS['TL_CONFIG']['disableAlias'] && strpos($strUrl, '?') === false) ? '?' : '&';

		// Re-add the page numbers
		foreach ($arrPageParams as $key)
		{
			$strUrl .= $strGlue . $key . '=' . \Input::get($key);
			$strGlue = '&';
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

		$intCache = null;

		// Cache the page if it is not protected
		if (!isset($_GET['file']) && !isset($_GET['token']) && empty($_POST) && !BE_USER_LOGGED_IN && !FE_USER_LOGGED_IN && !$_SESSION['DISABLE_CACHE'] && !isset($_SESSION['LOGIN_ERROR']) && ($GLOBALS['TL_CONFIG']['cacheMode'] == 'both' || $GLOBALS['TL_CONFIG']['cacheMode'] == 'server') && intval($objPage->cache) > 0 && !$objPage->protected)
		{
			// If the request string is empty, use a special cache tag which considers the page language
			if (\Environment::get('request') == '' || \Environment::get('request') == 'index.php')
			{
				$strCacheKey = \Environment::get('base') . 'empty.' . $objPage->language;
			}
			else
			{
				$strCacheKey = \Environment::get('base') . $strUrl;
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

			// Store mobile pages separately
			if ($objPage->mobileLayout && \Environment::get('agent')->mobile)
			{
				$strCacheKey .= '.mobile';
			}

			// Replace insert tags for caching
			$strBuffer = $this->replaceInsertTags($strBuffer);
			$strBuffer = $this->replaceDynamicScriptTags($strBuffer); // see #4203

			$intCache = intval($objPage->cache) + time();
			$strMd5CacheKey = md5($strCacheKey);

			// Create the cache file
			$objFile = new \File('system/cache/html/' . substr($strMd5CacheKey, 0, 1) . '/' . $strMd5CacheKey . '.html');
			$objFile->write('<?php' . " /* $strCacheKey */ \$expire = $intCache; \$content = '{$this->strContentType}'; ?>\n");
			$objFile->append($this->minifyHtml($strBuffer), '');
			$objFile->close();
		}

		// Send cache headers
		if (!headers_sent())
		{
			if ($intCache !== null && ($GLOBALS['TL_CONFIG']['cacheMode'] == 'both' || $GLOBALS['TL_CONFIG']['cacheMode'] == 'browser'))
			{
				header('Cache-Control: public, max-age=' . ($intCache -  time()));
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
		$this->strBuffer = $this->replaceInsertTags($strBuffer, false);
		$this->strBuffer = str_replace(array('{{request_token}}', '[{]', '[}]'), array(REQUEST_TOKEN, '{{', '}}'), $this->strBuffer);
		$this->strBuffer = $this->replaceDynamicScriptTags($this->strBuffer); // see #4203

		// Index page if searching is allowed and there is no back end user
		if ($GLOBALS['TL_CONFIG']['enableSearch'] && $objPage->type == 'regular' && !BE_USER_LOGGED_IN && !$objPage->noSearch)
		{
			// Index protected pages if enabled
			if ($GLOBALS['TL_CONFIG']['indexProtected'] || (!FE_USER_LOGGED_IN && !$objPage->protected))
			{
				$arrData = array
				(
					'url' => $strUrl,
					'content' => $this->strBuffer,
					'title' => $objPage->pageTitle ?: $objPage->title,
					'protected' => ($objPage->protected ? '1' : ''),
					'groups' => $objPage->groups,
					'pid' => $objPage->id,
					'language' => $objPage->language
				);

				\Search::indexPage($arrData);
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
		return sprintf("\n<div id=\"%s\">\n%s\n</div>\n", $strKey, $this->sections[$strKey] ?: '&nbsp;');
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
