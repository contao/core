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
 * Class FrontendTemplate
 *
 * @property integer $id
 * @property string  $keywords
 * @property string  $content
 * @property array   $sections
 * @property string  $sPosition
 * @property string  $tag
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FrontendTemplate extends \Template
{

	/**
	 * Add a hook to modify the template output
	 *
	 * @return string The template markup
	 */
	public function parse()
	{
		/** @var \PageModel $objPage */
		global $objPage;

		// Adjust the output format
		if ($objPage->outputFormat != '')
		{
			$this->strFormat = $objPage->outputFormat;
			$this->strTagEnding = ($this->strFormat == 'xhtml') ? ' />' : '>';
		}

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
	 *
	 * @param boolean $blnCheckRequest If true, check for unsued $_GET parameters
	 *
	 * @throws \UnusedArgumentsException If there are unused $_GET parameters
	 */
	public function output($blnCheckRequest=false)
	{
		$this->keywords = '';
		$arrKeywords = array_map('trim', explode(',', $GLOBALS['TL_KEYWORDS']));

		// Add the meta keywords
		if (strlen($arrKeywords[0]))
		{
			$this->keywords = str_replace(array("\n", "\r", '"'), array(' ' , '', ''), implode(', ', array_unique($arrKeywords)));
		}

		// Parse the template
		$this->strBuffer = $this->parse();

		// HOOK: add custom output filters
		if (isset($GLOBALS['TL_HOOKS']['outputFrontendTemplate']) && is_array($GLOBALS['TL_HOOKS']['outputFrontendTemplate']))
		{
			foreach ($GLOBALS['TL_HOOKS']['outputFrontendTemplate'] as $callback)
			{
				$this->import($callback[0]);
				$this->strBuffer = $this->$callback[0]->$callback[1]($this->strBuffer, $this->strTemplate);
			}
		}

		// Add the output to the cache
		$this->addToCache();

		// Unset only after the output has been cached (see #7824)
		unset($_SESSION['LOGIN_ERROR']);

		// Replace insert tags and then re-replace the request_token tag in case a form element has been loaded via insert tag
		$this->strBuffer = $this->replaceInsertTags($this->strBuffer, false);
		$this->strBuffer = str_replace(array('{{request_token}}', '[{]', '[}]'), array(REQUEST_TOKEN, '{{', '}}'), $this->strBuffer);
		$this->strBuffer = $this->replaceDynamicScriptTags($this->strBuffer); // see #4203

		// HOOK: allow to modify the compiled markup (see #4291)
		if (isset($GLOBALS['TL_HOOKS']['modifyFrontendPage']) && is_array($GLOBALS['TL_HOOKS']['modifyFrontendPage']))
		{
			foreach ($GLOBALS['TL_HOOKS']['modifyFrontendPage'] as $callback)
			{
				$this->import($callback[0]);
				$this->strBuffer = $this->$callback[0]->$callback[1]($this->strBuffer, $this->strTemplate);
			}
		}

		// Check whether all $_GET parameters have been used (see #4277)
		if ($blnCheckRequest && \Input::hasUnusedGet())
		{
			throw new \UnusedArgumentsException();
		}

		// Send the response to the client
		parent::output();

		// Add the output to the search index
		$this->addToSearchIndex();
	}


	/**
	 * Return a custom layout section
	 *
	 * @param string $key      The section name
	 * @param string $template An optional template name
	 */
	public function section($key, $template=null)
	{
		$this->id = $key;
		$this->content = $this->sections[$key];

		if ($template === null)
		{
			$template = 'block_section';
		}

		include $this->getTemplate($template, $this->strFormat);
	}


	/**
	 * Return the custom layout sections
	 *
	 * @param string $key      An optional section name
	 * @param string $template An optional template name
	 */
	public function sections($key=null, $template=null)
	{
		if (empty($this->sections))
		{
			return;
		}

		// The key does not match
		if ($key && $this->sPosition != $key)
		{
			return;
		}

		// Use the section tag in HTML5
		$this->tag = ($key == 'main' && $this->strFormat != 'xhtml') ? 'section' : 'div';

		if ($template === null)
		{
			$template = 'block_sections';
		}

		include $this->getTemplate($template, $this->strFormat);
	}


	/**
	 * Point to `Frontend::addToUrl()` in front end templates (see #6736)
	 *
	 * @param string  $strRequest      The request string to be added
	 * @param boolean $blnIgnoreParams If true, the $_GET parameters will be ignored
	 * @param array   $arrUnset        An optional array of keys to unset
	 *
	 * @return string The new URI string
	 */
	public static function addToUrl($strRequest, $blnIgnoreParams=false, $arrUnset=array())
	{
		return \Frontend::addToUrl($strRequest, $blnIgnoreParams, $arrUnset);
	}


	/**
	 * Add the template output to the cache and add the cache headers
	 */
	protected function addToCache()
	{
		/** @var \PageModel $objPage */
		global $objPage;

		$intCache = 0;

		// Decide whether the page shall be cached
		if (!isset($_GET['file']) && !isset($_GET['token']) && empty($_POST) && !BE_USER_LOGGED_IN && !FE_USER_LOGGED_IN && !$_SESSION['DISABLE_CACHE'] && !isset($_SESSION['LOGIN_ERROR']) && intval($objPage->cache) > 0 && !$objPage->protected)
		{
			$intCache = time() + intval($objPage->cache);
		}

		// Server-side cache
		if ($intCache > 0 && (\Config::get('cacheMode') == 'both' || \Config::get('cacheMode') == 'server'))
		{
			// If the request string is empty, use a special cache tag which considers the page language
			if (\Environment::get('request') == '' || \Environment::get('request') == 'index.php')
			{
				$strCacheKey = \Environment::get('host') . '/empty.' . $objPage->language;
			}
			else
			{
				$strCacheKey = \Environment::get('host') . '/' . \Environment::get('request');
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

			// Add a suffix if there is a mobile layout (see #7826)
			if ($objPage->mobileLayout > 0)
			{
				if (\Input::cookie('TL_VIEW') == 'mobile' || (\Environment::get('agent')->mobile && \Input::cookie('TL_VIEW') != 'desktop'))
				{
					$strCacheKey .= '.mobile';
				}
				else
				{
					$strCacheKey .= '.desktop';
				}
			}

			// Replace insert tags for caching
			$strBuffer = $this->replaceInsertTags($this->strBuffer);
			$strBuffer = $this->replaceDynamicScriptTags($strBuffer); // see #4203

			// Create the cache file
			$strMd5CacheKey = md5($strCacheKey);
			$objFile = new \File('system/cache/html/' . substr($strMd5CacheKey, 0, 1) . '/' . $strMd5CacheKey . '.html', true);
			$objFile->write('<?php' . " /* $strCacheKey */ \$expire = $intCache; \$content = '{$this->strContentType}'; \$type = '{$objPage->type}'; ?>\n");
			$objFile->append($this->minifyHtml($strBuffer), '');
			$objFile->close();
		}

		// Client-side cache
		if (!headers_sent())
		{
			if ($intCache > 0 && (\Config::get('cacheMode') == 'both' || \Config::get('cacheMode') == 'browser'))
			{
				header('Cache-Control: public, max-age=' . ($intCache - time()));
				header('Pragma: public');
				header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
				header('Expires: ' . gmdate('D, d M Y H:i:s', $intCache) . ' GMT');
			}
			else
			{
				header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
				header('Pragma: no-cache');
				header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
				header('Expires: Fri, 06 Jun 1975 15:10:00 GMT');
			}
		}
	}


	/**
	 * Add the template output to the search index
	 */
	protected function addToSearchIndex()
	{
		/** @var \PageModel $objPage */
		global $objPage;

		// Index page if searching is allowed and there is no back end user
		if (\Config::get('enableSearch') && $objPage->type == 'regular' && !BE_USER_LOGGED_IN && !$objPage->noSearch)
		{
			// Index protected pages if enabled
			if (\Config::get('indexProtected') || (!FE_USER_LOGGED_IN && !$objPage->protected))
			{
				$blnIndex = true;

				// Do not index the page if certain parameters are set
				foreach (array_keys($_GET) as $key)
				{
					if (in_array($key, $GLOBALS['TL_NOINDEX_KEYS']) || strncmp($key, 'page_', 5) === 0)
					{
						$blnIndex = false;
						break;
					}
				}

				if ($blnIndex)
				{
					$arrData = array
					(
						'url' => \Environment::get('request'),
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
		}
	}


	/**
	 * Return a custom layout section
	 *
	 * @param string $strKey The section name
	 *
	 * @return string The section markup
	 *
	 * @deprecated Use FrontendTemplate::section() instead
	 */
	public function getCustomSection($strKey)
	{
		return '<div id="' . $strKey . '">' . $this->sections[$strKey] . '</div>' . "\n";
	}


	/**
	 * Return all custom layout sections
	 *
	 * @param string $strKey An optional section name
	 *
	 * @return string The section markup
	 *
	 * @deprecated Use FrontendTemplate::sections() instead
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
			/** @var \PageModel $objPage */
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

		return '<div class="custom">' . "\n" . $sections . "\n" . '</div>' . "\n";
	}
}
