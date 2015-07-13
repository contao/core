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
 * Abstract parent class for Controllers
 *
 * Some of the methods have been made static in Contao 3 and can be used in
 * non-object context as well.
 *
 * Usage:
 *
 *     echo Controller::getTheme();
 *
 * Inside a controller:
 *
 *     public function generate()
 *     {
 *         return $this->getArticle(2);
 *     }
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
abstract class Controller extends \System
{

	/**
	 * Find a particular template file and return its path
	 *
	 * @param string $strTemplate The name of the template
	 * @param string $strFormat   The file extension
	 *
	 * @return string The path to the template file
	 *
	 * @throws \InvalidArgumentException If $strFormat is unknown
	 * @throws \RuntimeException         If the template group folder is insecure
	 */
	public static function getTemplate($strTemplate, $strFormat='html5')
	{
		$arrAllowed = trimsplit(',', \Config::get('templateFiles'));
		array_push($arrAllowed, 'html5'); // see #3398

		if (!in_array($strFormat, $arrAllowed))
		{
			throw new \InvalidArgumentException('Invalid output format ' . $strFormat);
		}

		$strTemplate = basename($strTemplate);

		// Check for a theme folder
		if (TL_MODE == 'FE')
		{
			/** @var \PageModel $objPage */
			global $objPage;

			if ($objPage->templateGroup != '')
			{
				if (\Validator::isInsecurePath($objPage->templateGroup))
				{
					throw new \RuntimeException('Invalid path ' . $objPage->templateGroup);
				}

				return \TemplateLoader::getPath($strTemplate, $strFormat, $objPage->templateGroup);
			}
		}

		return \TemplateLoader::getPath($strTemplate, $strFormat);
	}


	/**
	 * Return all template files of a particular group as array
	 *
	 * @param string $strPrefix The template name prefix (e.g. "ce_")
	 *
	 * @return array An array of template names
	 */
	public static function getTemplateGroup($strPrefix)
	{
		$arrTemplates = array();

		// Get the default templates
		foreach (\TemplateLoader::getPrefixedFiles($strPrefix) as $strTemplate)
		{
			$arrTemplates[$strTemplate][] = 'root';
		}

		$arrCustomized = glob(TL_ROOT . '/templates/' . $strPrefix . '*');

		// Add the customized templates
		if (is_array($arrCustomized))
		{
			foreach ($arrCustomized as $strFile)
			{
				$strTemplate = basename($strFile, strrchr($strFile, '.'));
				$arrTemplates[$strTemplate][] = $GLOBALS['TL_LANG']['MSC']['global'];
			}
		}

		// Do not look for back end templates in theme folders (see #5379)
		if ($strPrefix != 'be_' && $strPrefix != 'mail_')
		{
			// Try to select the themes (see #5210)
			try
			{
				$objTheme = \ThemeModel::findAll(array('order'=>'name'));
			}
			catch (\Exception $e)
			{
				$objTheme = null;
			}

			// Add the theme templates
			if ($objTheme !== null)
			{
				while ($objTheme->next())
				{
					if ($objTheme->templates != '')
					{
						$arrThemeTemplates = glob(TL_ROOT . '/' . $objTheme->templates . '/' . $strPrefix . '*');

						if (is_array($arrThemeTemplates))
						{
							foreach ($arrThemeTemplates as $strFile)
							{
								$strTemplate = basename($strFile, strrchr($strFile, '.'));

								if (!isset($arrTemplates[$strTemplate]))
								{
									$arrTemplates[$strTemplate][] = $objTheme->name;
								}
								else
								{
									$arrTemplates[$strTemplate][] = $objTheme->name;
								}
							}
						}
					}
				}
			}
		}

		// Show the template sources (see #6875)
		foreach ($arrTemplates as $k=>$v)
		{
			$v = array_filter($v, function($a) {
				return $a != 'root';
			});

			if (empty($v))
			{
				$arrTemplates[$k] = $k;
			}
			else
			{
				$arrTemplates[$k] = $k . ' (' . implode(', ', $v) . ')';
			}
		}

		// Sort the template names
		ksort($arrTemplates);

		return $arrTemplates;
	}


	/**
	 * Generate a front end module and return it as string
	 *
	 * @param mixed  $intId     A module ID or a Model object
	 * @param string $strColumn The name of the column
	 *
	 * @return string The module HTML markup
	 */
	public static function getFrontendModule($intId, $strColumn='main')
	{
		if (!is_object($intId) && !strlen($intId))
		{
			return '';
		}

		/** @var \PageModel $objPage */
		global $objPage;

		// Articles
		if (!is_object($intId) && $intId == 0)
		{
			// Show a particular article only
			if ($objPage->type == 'regular' && \Input::get('articles'))
			{
				list($strSection, $strArticle) = explode(':', \Input::get('articles'));

				if ($strArticle === null)
				{
					$strArticle = $strSection;
					$strSection = 'main';
				}

				if ($strSection == $strColumn)
				{
					$objArticle = \ArticleModel::findByIdOrAliasAndPid($strArticle, $objPage->id);

					// Send a 404 header if the article does not exist
					if (null === $objArticle)
					{
						/** @var \PageError404 $objHandler */
						$objHandler = new $GLOBALS['TL_PTY']['error_404']();
						$objHandler->generate($objPage->id);
					}

					// Add the "first" and "last" classes (see #2583)
					$objArticle->classes = array('first', 'last');

					return static::getArticle($objArticle);
				}
			}

			// HOOK: trigger the article_raster_designer extension
			if (in_array('article_raster_designer', \ModuleLoader::getActive()))
			{
				return \RasterDesigner::load($objPage->id, $strColumn);
			}

			// Show all articles (no else block here, see #4740)
			$objArticles = \ArticleModel::findPublishedByPidAndColumn($objPage->id, $strColumn);

			if ($objArticles === null)
			{
				return '';
			}

			$return = '';
			$intCount = 0;
			$blnMultiMode = ($objArticles->count() > 1);
			$intLast = $objArticles->count() - 1;

			while ($objArticles->next())
			{
				/** @var \ArticleModel $objRow */
				$objRow = $objArticles->current();

				// Add the "first" and "last" classes (see #2583)
				if ($intCount == 0 || $intCount == $intLast)
				{
					$arrCss = array();

					if ($intCount == 0)
					{
						$arrCss[] = 'first';
					}

					if ($intCount == $intLast)
					{
						$arrCss[] = 'last';
					}

					$objRow->classes = $arrCss;
				}

				$return .= static::getArticle($objRow, $blnMultiMode, false, $strColumn);
				++$intCount;
			}

			return $return;
		}

		// Other modules
		else
		{
			if (is_object($intId))
			{
				$objRow = $intId;
			}
			else
			{
				$objRow = \ModuleModel::findByPk($intId);

				if ($objRow === null)
				{
					return '';
				}
			}

			// Check the visibility (see #6311)
			if (!static::isVisibleElement($objRow))
			{
				return '';
			}

			$strClass = \Module::findClass($objRow->type);

			// Return if the class does not exist
			if (!class_exists($strClass))
			{
				static::log('Module class "'.$strClass.'" (module "'.$objRow->type.'") does not exist', __METHOD__, TL_ERROR);

				return '';
			}

			$objRow->typePrefix = 'mod_';

			/** @var \Module $objModule */
			$objModule = new $strClass($objRow, $strColumn);
			$strBuffer = $objModule->generate();

			// HOOK: add custom logic
			if (isset($GLOBALS['TL_HOOKS']['getFrontendModule']) && is_array($GLOBALS['TL_HOOKS']['getFrontendModule']))
			{
				foreach ($GLOBALS['TL_HOOKS']['getFrontendModule'] as $callback)
				{
					$strBuffer = static::importStatic($callback[0])->$callback[1]($objRow, $strBuffer, $objModule);
				}
			}

			// Disable indexing if protected
			if ($objModule->protected && !preg_match('/^\s*<!-- indexer::stop/', $strBuffer))
			{
				$strBuffer = "\n<!-- indexer::stop -->". $strBuffer ."<!-- indexer::continue -->\n";
			}

			return $strBuffer;
		}
	}


	/**
	 * Generate an article and return it as string
	 *
	 * @param mixed   $varId          The article ID or a Model object
	 * @param boolean $blnMultiMode   If true, only teasers will be shown
	 * @param boolean $blnIsInsertTag If true, there will be no page relation
	 * @param string  $strColumn      The name of the column
	 *
	 * @return string|boolean The article HTML markup or false
	 */
	public static function getArticle($varId, $blnMultiMode=false, $blnIsInsertTag=false, $strColumn='main')
	{
		/** @var \PageModel $objPage */
		global $objPage;

		if (is_object($varId))
		{
			$objRow = $varId;
		}
		else
		{
			if (!$varId)
			{
				return '';
			}

			$objRow = \ArticleModel::findByIdOrAliasAndPid($varId, (!$blnIsInsertTag ? $objPage->id : null));

			if ($objRow === null)
			{
				return false;
			}
		}

		// Check the visibility (see #6311)
		if (!static::isVisibleElement($objRow))
		{
			return '';
		}

		// Print the article as PDF
		if (isset($_GET['pdf']) && \Input::get('pdf') == $objRow->id)
		{
			// Backwards compatibility
			if ($objRow->printable == 1)
			{
				$objArticle = new \ModuleArticle($objRow);
				$objArticle->generatePdf();
			}
			elseif ($objRow->printable != '')
			{
				$options = deserialize($objRow->printable);

				if (is_array($options) && in_array('pdf', $options))
				{
					$objArticle = new \ModuleArticle($objRow);
					$objArticle->generatePdf();
				}
			}
		}

		$objRow->headline = $objRow->title;
		$objRow->multiMode = $blnMultiMode;

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getArticle']) && is_array($GLOBALS['TL_HOOKS']['getArticle']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getArticle'] as $callback)
			{
				static::importStatic($callback[0])->$callback[1]($objRow);
			}
		}

		$objArticle = new \ModuleArticle($objRow, $strColumn);
		$strBuffer = $objArticle->generate($blnIsInsertTag);

		// Disable indexing if protected
		if ($objArticle->protected && !preg_match('/^\s*<!-- indexer::stop/', $strBuffer))
		{
			$strBuffer = "\n<!-- indexer::stop -->". $strBuffer ."<!-- indexer::continue -->\n";
		}

		return $strBuffer;
	}


	/**
	 * Generate a content element and return it as string
	 *
	 * @param mixed  $intId     A content element ID or a Model object
	 * @param string $strColumn The column the element is in
	 *
	 * @return string The content element HTML markup
	 */
	public static function getContentElement($intId, $strColumn='main')
	{
		if (is_object($intId))
		{
			$objRow = $intId;
		}
		else
		{
			if (!strlen($intId) || $intId < 1)
			{
				return '';
			}

			$objRow = \ContentModel::findByPk($intId);

			if ($objRow === null)
			{
				return '';
			}
		}

		// Check the visibility (see #6311)
		if (!static::isVisibleElement($objRow))
		{
			return '';
		}

		// Remove the spacing in the back end preview
		if (TL_MODE == 'BE')
		{
			$objRow->space = null;
		}

		$strClass = \ContentElement::findClass($objRow->type);

		// Return if the class does not exist
		if (!class_exists($strClass))
		{
			static::log('Content element class "'.$strClass.'" (content element "'.$objRow->type.'") does not exist', __METHOD__, TL_ERROR);

			return '';
		}

		$objRow->typePrefix = 'ce_';

		/** @var \ContentElement $objElement */
		$objElement = new $strClass($objRow, $strColumn);
		$strBuffer = $objElement->generate();

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getContentElement']) && is_array($GLOBALS['TL_HOOKS']['getContentElement']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getContentElement'] as $callback)
			{
				$strBuffer = static::importStatic($callback[0])->$callback[1]($objRow, $strBuffer, $objElement);
			}
		}

		// Disable indexing if protected
		if ($objElement->protected && !preg_match('/^\s*<!-- indexer::stop/', $strBuffer))
		{
			$strBuffer = "\n<!-- indexer::stop -->". $strBuffer ."<!-- indexer::continue -->\n";
		}

		return $strBuffer;
	}


	/**
	 * Generate a form and return it as string
	 *
	 * @param mixed  $varId     A form ID or a Model object
	 * @param string $strColumn The column the form is in
	 *
	 * @return string The form HTML markup
	 */
	public static function getForm($varId, $strColumn='main')
	{
		if (is_object($varId))
		{
			$objRow = $varId;
		}
		else
		{
			if ($varId == '')
			{
				return '';
			}

			$objRow = \FormModel::findByIdOrAlias($varId);

			if ($objRow === null)
			{
				return '';
			}
		}

		$objRow->typePrefix = 'ce_';
		$objRow->form = $objRow->id;
		$objElement = new \Form($objRow, $strColumn);
		$strBuffer = $objElement->generate();

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getForm']) && is_array($GLOBALS['TL_HOOKS']['getForm']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getForm'] as $callback)
			{
				$strBuffer = static::importStatic($callback[0])->$callback[1]($objRow, $strBuffer, $objElement);
			}
		}

		return $strBuffer;
	}


	/**
	 * Return the languages for the TinyMCE spellchecker
	 *
	 * @return string The TinyMCE spellchecker language string
	 */
	protected function getSpellcheckerString()
	{
		\System::loadLanguageFile('languages');

		$return = array();
		$langs = scan(TL_ROOT . '/system/modules/core/languages');
		array_unshift($langs, $GLOBALS['TL_LANGUAGE']);

		foreach ($langs as $lang)
		{
			$lang = substr($lang, 0, 2);

			if (isset($GLOBALS['TL_LANG']['LNG'][$lang]))
			{
				$return[$lang] = $GLOBALS['TL_LANG']['LNG'][$lang] . '=' . $lang;
			}
		}

		return '+' . implode(',', array_unique($return));
	}


	/**
	 * Calculate the page status icon name based on the page parameters
	 *
	 * @param object $objPage The page object
	 *
	 * @return string The status icon name
	 */
	public static function getPageStatusIcon($objPage)
	{
		$sub = 0;
		$image = $objPage->type.'.gif';

		// Page not published or not active
		if (!$objPage->published || ($objPage->start != '' && $objPage->start > time()) || ($objPage->stop != '' && $objPage->stop < time()))
		{
			$sub += 1;
		}

		// Page hidden from menu
		if ($objPage->hide && !in_array($objPage->type, array('redirect', 'forward', 'root', 'error_403', 'error_404')))
		{
			$sub += 2;
		}

		// Page protected
		if ($objPage->protected && !in_array($objPage->type, array('root', 'error_403', 'error_404')))
		{
			$sub += 4;
		}

		// Get the image name
		if ($sub > 0)
		{
			$image = $objPage->type.'_'.$sub.'.gif';
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getPageStatusIcon']) && is_array($GLOBALS['TL_HOOKS']['getPageStatusIcon']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getPageStatusIcon'] as $callback)
			{
				$image = static::importStatic($callback[0])->$callback[1]($objPage, $image);
			}
		}

		return $image;
	}


	/**
	 * Check whether an element is visible in the front end
	 *
	 * @param \Model|\ContentModel|\ModuleModel $objElement The element model
	 *
	 * @return boolean True if the element is visible
	 */
	public static function isVisibleElement(\Model $objElement)
	{
		// Only apply the restrictions in the front end
		if (TL_MODE != 'FE' || BE_USER_LOGGED_IN)
		{
			return true;
		}

		$blnReturn = true;

		// Protected element
		if ($objElement->protected)
		{
			if (!FE_USER_LOGGED_IN)
			{
				$blnReturn = false;
			}
			else
			{
				$groups = deserialize($objElement->groups);

				if (empty($groups) || !is_array($groups) || !count(array_intersect($groups, \FrontendUser::getInstance()->groups)))
				{
					$blnReturn = false;
				}
			}
		}

		// Show to guests only
		elseif ($objElement->guests && FE_USER_LOGGED_IN)
		{
			$blnReturn = false;
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['isVisibleElement']) && is_array($GLOBALS['TL_HOOKS']['isVisibleElement']))
		{
			foreach ($GLOBALS['TL_HOOKS']['isVisibleElement'] as $callback)
			{
				$blnReturn = static::importStatic($callback[0])->$callback[1]($objElement, $blnReturn);
			}
		}

		return $blnReturn;
	}


	/**
	 * Replace insert tags with their values
	 *
	 * @param string  $strBuffer The text with the tags to be replaced
	 * @param boolean $blnCache  If false, non-cacheable tags will be replaced
	 *
	 * @return string The text with the replaced tags
	 */
	public static function replaceInsertTags($strBuffer, $blnCache=true)
	{
		$objIt = new InsertTags();

		return $objIt->replace($strBuffer, $blnCache);
	}


	/**
	 * Replace the dynamic script tags (see #4203)
	 *
	 * @param string $strBuffer The string with the tags to be replaced
	 *
	 * @return string The string with the replaced tags
	 */
	public static function replaceDynamicScriptTags($strBuffer)
	{
		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['replaceDynamicScriptTags']) && is_array($GLOBALS['TL_HOOKS']['replaceDynamicScriptTags']))
		{
			foreach ($GLOBALS['TL_HOOKS']['replaceDynamicScriptTags'] as $callback)
			{
				$strBuffer = static::importStatic($callback[0])->$callback[1]($strBuffer);
			}
		}

		/** @var \PageModel $objPage */
		global $objPage;

		$arrReplace = array();
		$blnXhtml = ($objPage->outputFormat == 'xhtml');
		$strScripts = '';

		// Add the internal jQuery scripts
		if (!empty($GLOBALS['TL_JQUERY']) && is_array($GLOBALS['TL_JQUERY']))
		{
			foreach (array_unique($GLOBALS['TL_JQUERY']) as $script)
			{
				$strScripts .= "\n" . trim($script) . "\n";
			}
		}

		$arrReplace['[[TL_JQUERY]]'] = $strScripts;
		$strScripts = '';

		// Add the internal MooTools scripts
		if (!empty($GLOBALS['TL_MOOTOOLS']) && is_array($GLOBALS['TL_MOOTOOLS']))
		{
			foreach (array_unique($GLOBALS['TL_MOOTOOLS']) as $script)
			{
				$strScripts .= "\n" . trim($script) . "\n";
			}
		}

		$arrReplace['[[TL_MOOTOOLS]]'] = $strScripts;
		$strScripts = '';

		// Add the internal <body> tags
		if (!empty($GLOBALS['TL_BODY']) && is_array($GLOBALS['TL_BODY']))
		{
			foreach (array_unique($GLOBALS['TL_BODY']) as $script)
			{
				$strScripts .= trim($script) . "\n";
			}
		}

		// Add the syntax highlighter scripts
		if (!empty($GLOBALS['TL_HIGHLIGHTER']) && is_array($GLOBALS['TL_HIGHLIGHTER']))
		{
			$objCombiner = new \Combiner();

			foreach (array_unique($GLOBALS['TL_HIGHLIGHTER']) as $script)
			{
				$objCombiner->add($script);
			}

			$strScripts .= "\n" . \Template::generateScriptTag($objCombiner->getCombinedFile(), $blnXhtml);
			$strScripts .= "\n" . \Template::generateInlineScript('SyntaxHighlighter.defaults.toolbar=false;SyntaxHighlighter.all()', $blnXhtml) . "\n";
		}

		// Command scheduler
		if (!\Config::get('disableCron'))
		{
			$strScripts .= "\n" . \Template::generateInlineScript('setTimeout(function(){var e=function(e,t){try{var n=new XMLHttpRequest}catch(r){return}n.open("GET",e,!0),n.onreadystatechange=function(){this.readyState==4&&this.status==200&&typeof t=="function"&&t(this.responseText)},n.send()},t="system/cron/cron.";e(t+"txt",function(n){parseInt(n||0)<Math.round(+(new Date)/1e3)-' . \Frontend::getCronTimeout() . '&&e(t+"php")})},5e3);', $blnXhtml) . "\n";
		}

		$arrReplace['[[TL_BODY]]'] = $strScripts;
		$strScripts = '';

		$objCombiner = new \Combiner();

		// Add the CSS framework style sheets
		if (!empty($GLOBALS['TL_FRAMEWORK_CSS']) && is_array($GLOBALS['TL_FRAMEWORK_CSS']))
		{
			foreach (array_unique($GLOBALS['TL_FRAMEWORK_CSS']) as $stylesheet)
			{
				$objCombiner->add($stylesheet);
			}
		}

		// Add the internal style sheets
		if (!empty($GLOBALS['TL_CSS']) && is_array($GLOBALS['TL_CSS']))
		{
			foreach (array_unique($GLOBALS['TL_CSS']) as $stylesheet)
			{
				$options = \String::resolveFlaggedUrl($stylesheet);

				if ($options->static)
				{
					$objCombiner->add($stylesheet, filemtime(TL_ROOT . '/' . $stylesheet), $options->media);
				}
				else
				{
					$strScripts .= \Template::generateStyleTag(static::addStaticUrlTo($stylesheet), $options->media, $blnXhtml) . "\n";
				}
			}
		}

		// Add the user style sheets
		if (!empty($GLOBALS['TL_USER_CSS']) && is_array($GLOBALS['TL_USER_CSS']))
		{
			foreach (array_unique($GLOBALS['TL_USER_CSS']) as $stylesheet)
			{
				$options = \String::resolveFlaggedUrl($stylesheet);

				if ($options->static)
				{
					if ($options->mtime === null)
					{
						$options->mtime = filemtime(TL_ROOT . '/' . $stylesheet);
					}

					$objCombiner->add($stylesheet, $options->mtime, $options->media);
				}
				else
				{
					$strScripts .= \Template::generateStyleTag(static::addStaticUrlTo($stylesheet), $options->media, $blnXhtml) . "\n";
				}
			}
		}

		// Create the aggregated style sheet
		if ($objCombiner->hasEntries())
		{
			$strScripts .= \Template::generateStyleTag($objCombiner->getCombinedFile(), 'all', $blnXhtml) . "\n";
		}

		$arrReplace['[[TL_CSS]]'] = $strScripts;
		$strScripts = '';

		// Add the internal scripts
		if (!empty($GLOBALS['TL_JAVASCRIPT']) && is_array($GLOBALS['TL_JAVASCRIPT']))
		{
			$objCombiner = new \Combiner();
			$objCombinerAsync = new \Combiner();

			foreach (array_unique($GLOBALS['TL_JAVASCRIPT']) as $javascript)
			{
				$options = \String::resolveFlaggedUrl($javascript);

				if ($options->static)
				{
					if ($options->async)
					{
						$objCombinerAsync->add($javascript, filemtime(TL_ROOT . '/' . $javascript));
					}
					else
					{
						$objCombiner->add($javascript, filemtime(TL_ROOT . '/' . $javascript));
					}
				}
				else
				{
					$strScripts .= \Template::generateScriptTag(static::addStaticUrlTo($javascript), $blnXhtml, $options->async) . "\n";
				}
			}

			// Create the aggregated script and add it before the non-static scripts (see #4890)
			if ($objCombiner->hasEntries())
			{
				$strScripts = \Template::generateScriptTag($objCombiner->getCombinedFile(), $blnXhtml) . "\n" . $strScripts;
			}

			if ($objCombinerAsync->hasEntries())
			{
				$strScripts = \Template::generateScriptTag($objCombinerAsync->getCombinedFile(), $blnXhtml, true) . "\n" . $strScripts;
			}
		}

		// Add the internal <head> tags
		if (!empty($GLOBALS['TL_HEAD']) && is_array($GLOBALS['TL_HEAD']))
		{
			foreach (array_unique($GLOBALS['TL_HEAD']) as $head)
			{
				$strScripts .= trim($head) . "\n";
			}
		}

		$arrReplace['[[TL_HEAD]]'] = $strScripts;

		return str_replace(array_keys($arrReplace), array_values($arrReplace), $strBuffer);
	}


	/**
	 * Compile the margin format definition based on an array of values
	 *
	 * @param array  $arrValues An array of four values and a unit
	 * @param string $strType   Either "margin" or "padding"
	 *
	 * @return string The CSS markup
	 */
	public static function generateMargin($arrValues, $strType='margin')
	{
		// Initialize an empty array (see #5217)
		if (!is_array($arrValues))
		{
			$arrValues = array('top'=>'', 'right'=>'', 'bottom'=>'', 'left'=>'', 'unit'=>'');
		}

		$top = $arrValues['top'];
		$right = $arrValues['right'];
		$bottom = $arrValues['bottom'];
		$left = $arrValues['left'];

		// Try to shorten the definition
		if ($top != '' && $right != '' && $bottom != '' && $left != '')
		{
			if ($top == $right && $top == $bottom && $top == $left)
			{
				return $strType . ':' . $top . $arrValues['unit'] . ';';
			}
			elseif ($top == $bottom && $right == $left)
			{
				return $strType . ':' . $top . $arrValues['unit'] . ' ' . $left . $arrValues['unit'] . ';';
			}
			elseif ($top != $bottom && $right == $left)
			{
				return $strType . ':' . $top . $arrValues['unit'] . ' ' . $right . $arrValues['unit'] . ' ' . $bottom . $arrValues['unit'] . ';';
			}
			else
			{
				return $strType . ':' . $top . $arrValues['unit'] . ' ' . $right . $arrValues['unit'] . ' ' . $bottom . $arrValues['unit'] . ' ' . $left . $arrValues['unit'] . ';';
			}
		}

		$return = array();
		$arrDir = array('top'=>$top, 'right'=>$right, 'bottom'=>$bottom, 'left'=>$left);

		foreach ($arrDir as $k=>$v)
		{
			if ($v != '')
			{
				$return[] = $strType . '-' . $k . ':' . $v . $arrValues['unit'] . ';';
			}
		}

		return implode($return);
	}


	/**
	 * Add a request string to the current URL
	 *
	 * @param string  $strRequest The string to be added
	 * @param boolean $blnAddRef  Add the referer ID
	 * @param array   $arrUnset   An optional array of keys to unset
	 *
	 * @return string The new URL
	 */
	public static function addToUrl($strRequest, $blnAddRef=true, $arrUnset=array())
	{
		$strRequest = preg_replace('/^&(amp;)?/i', '', $strRequest);

		if ($strRequest != '' && $blnAddRef)
		{
			$strRequest .= '&amp;ref=' . TL_REFERER_ID;
		}

		$queries = preg_split('/&(amp;)?/i', \Environment::get('queryString'));

		// Overwrite existing parameters
		foreach ($queries as $k=>$v)
		{
			list($key) = explode('=', $v);

			if (in_array($key, $arrUnset) || preg_match('/(^|&(amp;)?)' . preg_quote($key, '/') . '=/i', $strRequest))
			{
				unset($queries[$k]);
			}
		}

		$href = '?';

		if (!empty($queries))
		{
			$href .= implode('&amp;', $queries) . '&amp;';
		}

		return TL_SCRIPT . $href . str_replace(' ', '%20', $strRequest);
	}


	/**
	 * Reload the current page
	 */
	public static function reload()
	{
		if (headers_sent())
		{
			exit;
		}

		$strLocation = \Environment::get('uri');

		// Ajax request
		if (\Environment::get('isAjaxRequest'))
		{
			header('HTTP/1.1 204 No Content');
			header('X-Ajax-Location: ' . $strLocation);
		}
		else
		{
			header('HTTP/1.1 303 See Other');
			header('Location: ' . $strLocation);
		}

		exit;
	}


	/**
	 * Redirect to another page
	 *
	 * @param string  $strLocation The target URL
	 * @param integer $intStatus   The HTTP status code (defaults to 303)
	 */
	public static function redirect($strLocation, $intStatus=303)
	{
		if (headers_sent())
		{
			exit;
		}

		$strLocation = str_replace('&amp;', '&', $strLocation);

		// Make the location an absolute URL
		if (!preg_match('@^https?://@i', $strLocation))
		{
			$strLocation = \Environment::get('base') . $strLocation;
		}

		// Ajax request
		if (\Environment::get('isAjaxRequest'))
		{
			header('HTTP/1.1 204 No Content');
			header('X-Ajax-Location: ' . $strLocation);
		}
		else
		{
			// Add the HTTP header
			switch ($intStatus)
			{
				case 301:
					header('HTTP/1.1 301 Moved Permanently');
					break;

				case 302:
					header('HTTP/1.1 302 Found');
					break;

				case 303:
					header('HTTP/1.1 303 See Other');
					break;

				case 307:
					header('HTTP/1.1 307 Temporary Redirect');
					break;
			}

			header('Location: ' . $strLocation);
		}

		exit;
	}


	/**
	 * Generate an URL depending on the current rewriteURL setting
	 *
	 * @param array   $arrRow       An array of page parameters
	 * @param string  $strParams    An optional string of URL parameters
	 * @param string  $strForceLang Force a certain language
	 * @param boolean $blnFixDomain Check the domain of the target page and append it if necessary
	 *
	 * @return string An URL that can be used in the front end
	 */
	public static function generateFrontendUrl(array $arrRow, $strParams=null, $strForceLang=null, $blnFixDomain=false)
	{
		if (!\Config::get('disableAlias'))
		{
			$strLanguage = '';

			if (\Config::get('addLanguageToUrl'))
			{
				if ($strForceLang != '')
				{
					$strLanguage = $strForceLang . '/';
				}
				elseif (isset($arrRow['language']) && $arrRow['type'] == 'root')
				{
					$strLanguage = $arrRow['language'] . '/';
				}
				elseif (TL_MODE == 'FE')
				{
					/** @var \PageModel $objPage */
					global $objPage;

					$strLanguage = $objPage->rootLanguage . '/';
				}
			}

			// Correctly handle the "index" alias (see #3961)
			if ($arrRow['alias'] == 'index' && $strParams == '')
			{
				if ($strLanguage != '') // see #7757
				{
					$strUrl = (\Config::get('rewriteURL') ? '' : 'index.php/') . $strLanguage;
				}
			}
			else
			{
				$strUrl = (\Config::get('rewriteURL') ? '' : 'index.php/') . $strLanguage . ($arrRow['alias'] ?: $arrRow['id']) . $strParams . \Config::get('urlSuffix');
			}
		}
		else
		{
			$strRequest = '';

			if ($strParams != '')
			{
				$arrChunks = explode('/', preg_replace('@^/@', '', $strParams));

				for ($i=0, $c=count($arrChunks); $i<$c; $i=($i+2))
				{
					$strRequest .= sprintf('&%s=%s', $arrChunks[$i], $arrChunks[($i+1)]);
				}
			}

			$strUrl = 'index.php?id=' . $arrRow['id'] . $strRequest;
		}

		// Add the domain if it differs from the current one (see #3765 and #6927)
		if ($blnFixDomain && $arrRow['domain'] != '' && $arrRow['domain'] != \Environment::get('host'))
		{
			$strUrl = ($arrRow['rootUseSSL'] ? 'https://' : 'http://') . $arrRow['domain'] . TL_PATH . '/' . $strUrl;
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['generateFrontendUrl']) && is_array($GLOBALS['TL_HOOKS']['generateFrontendUrl']))
		{
			foreach ($GLOBALS['TL_HOOKS']['generateFrontendUrl'] as $callback)
			{
				$strUrl = static::importStatic($callback[0])->$callback[1]($arrRow, $strParams, $strUrl);
			}
		}

		return $strUrl;
	}


	/**
	 * Convert relative URLs in href and src attributes to absolute URLs
	 *
	 * @param string  $strContent  The text with the URLs to be converted
	 * @param string  $strBase     An optional base URL
	 * @param boolean $blnHrefOnly If true, only href attributes will be converted
	 *
	 * @return string The text with the replaced URLs
	 */
	public static function convertRelativeUrls($strContent, $strBase='', $blnHrefOnly=false)
	{
		if ($strBase == '')
		{
			$strBase = \Environment::get('base');
		}

		$search = $blnHrefOnly ? 'href' : 'href|src';
		$arrUrls = preg_split('/(('.$search.')="([^"]+)")/i', $strContent, -1, PREG_SPLIT_DELIM_CAPTURE);
		$strContent = '';

		for ($i=0, $c=count($arrUrls); $i<$c; $i=$i+4)
		{
			$strContent .= $arrUrls[$i];

			if (!isset($arrUrls[$i+2]))
			{
				continue;
			}

			$strAttribute = $arrUrls[$i+2];
			$strUrl = $arrUrls[$i+3];

			if (!preg_match('@^(https?://|ftp://|mailto:|#)@i', $strUrl))
			{
				$strUrl = $strBase . (($strUrl != '/') ? $strUrl : '');
			}

			$strContent .= $strAttribute . '="' . $strUrl . '"';
		}

		return $strContent;
	}


	/**
	 * Send a file to the browser so the "save as …" dialogue opens
	 *
	 * @param string $strFile The file path
	 */
	public static function sendFileToBrowser($strFile)
	{
		// Make sure there are no attempts to hack the file system
		if (preg_match('@^\.+@i', $strFile) || preg_match('@\.+/@i', $strFile) || preg_match('@(://)+@i', $strFile))
		{
			header('HTTP/1.1 404 Not Found');
			die('Invalid file name');
		}

		// Limit downloads to the files directory
		if (!preg_match('@^' . preg_quote(\Config::get('uploadPath'), '@') . '@i', $strFile))
		{
			header('HTTP/1.1 404 Not Found');
			die('Invalid path');
		}

		// Check whether the file exists
		if (!file_exists(TL_ROOT . '/' . $strFile))
		{
			header('HTTP/1.1 404 Not Found');
			die('File not found');
		}

		$objFile = new \File($strFile, true);
		$arrAllowedTypes = trimsplit(',', strtolower(\Config::get('allowedDownload')));

		// Check whether the file type is allowed to be downloaded
		if (!in_array($objFile->extension, $arrAllowedTypes))
		{
			header('HTTP/1.1 403 Forbidden');
			die(sprintf('File type "%s" is not allowed', $objFile->extension));
		}

		// HOOK: post download callback
		if (isset($GLOBALS['TL_HOOKS']['postDownload']) && is_array($GLOBALS['TL_HOOKS']['postDownload']))
		{
			foreach ($GLOBALS['TL_HOOKS']['postDownload'] as $callback)
			{
				static::importStatic($callback[0])->$callback[1]($strFile);
			}
		}

		// Send the file (will stop the script execution)
		$objFile->sendToBrowser();
	}


	/**
	 * Load a set of DCA files
	 *
	 * @param string  $strTable   The table name
	 * @param boolean $blnNoCache If true, the cache will be bypassed
	 */
	public static function loadDataContainer($strTable, $blnNoCache=false)
	{
		$loader = new \DcaLoader($strTable);
		$loader->load($blnNoCache);
	}


	/**
	 * Redirect to a front end page
	 *
	 * @param integer $intPage    The page ID
	 * @param mixed   $varArticle An optional article alias
	 * @param boolean $blnReturn  If true, return the URL and don't redirect
	 *
	 * @return string The URL of the target page
	 */
	protected function redirectToFrontendPage($intPage, $varArticle=null, $blnReturn=false)
	{
		if (($intPage = intval($intPage)) <= 0)
		{
			return '';
		}

		$objPage = \PageModel::findWithDetails($intPage);

		if ($varArticle !== null)
		{
			$varArticle = '/articles/' . $varArticle;
		}

		$strUrl = $this->generateFrontendUrl($objPage->row(), $varArticle, $objPage->language, true);

		// Make sure the URL is absolute (see #4332)
		if (strncmp($strUrl, 'http://', 7) !== 0 && strncmp($strUrl, 'https://', 8) !== 0)
		{
			$strUrl = \Environment::get('base') . $strUrl;
		}

		if (!$blnReturn)
		{
			$this->redirect($strUrl);
		}

		return $strUrl;
	}


	/**
	 * Get the parent records of an entry and return them as string which can
	 * be used in a log message
	 *
	 * @param string  $strTable The table name
	 * @param integer $intId    The record ID
	 *
	 * @return string A string that can be used in a log message
	 */
	protected function getParentEntries($strTable, $intId)
	{
		// No parent table
		if (!isset($GLOBALS['TL_DCA'][$strTable]['config']['ptable']))
		{
			return '';
		}

		$arrParent = array();

		do
		{
			// Get the pid
			$objParent = $this->Database->prepare("SELECT pid FROM " . $strTable . " WHERE id=?")
										->limit(1)
										->execute($intId);

			if ($objParent->numRows < 1)
			{
				break;
			}

			// Store the parent table information
			$strTable = $GLOBALS['TL_DCA'][$strTable]['config']['ptable'];
			$intId = $objParent->pid;

			// Add the log entry
			$arrParent[] = $strTable .'.id=' . $intId;

			// Load the data container of the parent table
			$this->loadDataContainer($strTable);
		}
		while ($intId && isset($GLOBALS['TL_DCA'][$strTable]['config']['ptable']));

		if (empty($arrParent))
		{
			return '';
		}

		return ' (parent records: ' . implode(', ', $arrParent) . ')';
	}


	/**
	 * Take an array of file paths and eliminate the nested ones
	 *
	 * @param array $arrPaths The array of file paths
	 *
	 * @return array The file paths array without the nested paths
	 */
	protected function eliminateNestedPaths($arrPaths)
	{
		if (!is_array($arrPaths) || empty($arrPaths))
		{
			return array();
		}

		$nested = array();

		foreach ($arrPaths as $path)
		{
			$nested = array_merge($nested, preg_grep('/^' . preg_quote($path, '/') . '\/.+/', $arrPaths));
		}

		return array_values(array_diff($arrPaths, $nested));
	}


	/**
	 * Take an array of pages and eliminate the nested ones
	 *
	 * @param array   $arrPages   The array of page IDs
	 * @param string  $strTable   The table name
	 * @param boolean $blnSorting True if the table has a sorting field
	 *
	 * @return array The page IDs array without the nested IDs
	 */
	protected function eliminateNestedPages($arrPages, $strTable=null, $blnSorting=false)
	{
		if (!is_array($arrPages) || empty($arrPages))
		{
			return array();
		}

		if (!$strTable)
		{
			$strTable = 'tl_page';
		}

		// Thanks to Andreas Schempp (see #2475 and #3423)
		$arrPages = array_intersect($this->Database->getChildRecords(0, $strTable, $blnSorting), $arrPages);
		$arrPages = array_values(array_diff($arrPages, $this->Database->getChildRecords($arrPages, $strTable, $blnSorting)));

		return $arrPages;
	}


	/**
	 * Add an image to a template
	 *
	 * @param object  $objTemplate   The template object to add the image to
	 * @param array   $arrItem       The element or module as array
	 * @param integer $intMaxWidth   An optional maximum width of the image
	 * @param string  $strLightboxId An optional lightbox ID
	 */
	public static function addImageToTemplate($objTemplate, $arrItem, $intMaxWidth=null, $strLightboxId=null)
	{
		/** @var \PageModel $objPage */
		global $objPage;

		try
		{
			$objFile = new \File($arrItem['singleSRC'], true);
		}
		catch (\Exception $e)
		{
			$objFile = new \stdClass();
			$objFile->imageSize = false;
		}

		$imgSize = $objFile->imageSize;
		$size = deserialize($arrItem['size']);

		if ($intMaxWidth === null)
		{
			$intMaxWidth = (TL_MODE == 'BE') ? 320 : \Config::get('maxImageWidth');
		}

		$arrMargin = (TL_MODE == 'BE') ? array() : deserialize($arrItem['imagemargin']);

		// Store the original dimensions
		$objTemplate->width = $imgSize[0];
		$objTemplate->height = $imgSize[1];

		// Adjust the image size
		if ($intMaxWidth > 0)
		{
			// Subtract the margins before deciding whether to resize (see #6018)
			if (is_array($arrMargin) && $arrMargin['unit'] == 'px')
			{
				$intMargin = $arrMargin['left'] + $arrMargin['right'];

				// Reset the margin if it exceeds the maximum width (see #7245)
				if ($intMaxWidth - $intMargin < 1)
				{
					$arrMargin['left'] = '';
					$arrMargin['right'] = '';
				}
				else
				{
					$intMaxWidth = $intMaxWidth - $intMargin;
				}
			}

			if ($size[0] > $intMaxWidth || (!$size[0] && !$size[1] && $imgSize[0] > $intMaxWidth))
			{
				// See #2268 (thanks to Thyon)
				$ratio = ($size[0] && $size[1]) ? $size[1] / $size[0] : $imgSize[1] / $imgSize[0];

				$size[0] = $intMaxWidth;
				$size[1] = floor($intMaxWidth * $ratio);
			}
		}

		try
		{
			$src = \Image::create($arrItem['singleSRC'], $size)->executeResize()->getResizedPath();
			$picture = \Picture::create($arrItem['singleSRC'], $size)->getTemplateData();

			if ($src !== $arrItem['singleSRC'])
			{
				$objFile = new \File(rawurldecode($src), true);
			}
		}
		catch (\Exception $e)
		{
			\System::log('Image "' . $arrItem['singleSRC'] . '" could not be processed: ' . $e->getMessage(), __METHOD__, TL_ERROR);

			$src = '';
			$picture = array('img'=>array('src'=>'', 'srcset'=>''), 'sources'=>array());
		}

		// Image dimensions
		if (($imgSize = $objFile->imageSize) !== false)
		{
			$objTemplate->arrSize = $imgSize;
			$objTemplate->imgSize = ' width="' . $imgSize[0] . '" height="' . $imgSize[1] . '"';
		}

		$picture['alt'] = specialchars($arrItem['alt']);
		$picture['title'] = specialchars($arrItem['title']);

		$objTemplate->picture = $picture;

		// Provide an ID for single lightbox images in HTML5 (see #3742)
		if ($strLightboxId === null && $arrItem['fullsize'])
		{
			if ($objPage->outputFormat == 'xhtml')
			{
				$strLightboxId = 'lightbox';
			}
			else
			{
				$strLightboxId = 'lightbox[' . substr(md5($objTemplate->getName() . '_' . $arrItem['id']), 0, 6) . ']';
			}
		}

		// Float image
		if ($arrItem['floating'] != '')
		{
			$objTemplate->floatClass = ' float_' . $arrItem['floating'];
		}

		// Do not override the "href" key (see #6468)
		$strHrefKey = ($objTemplate->href != '') ? 'imageHref' : 'href';

		// Image link
		if ($arrItem['imageUrl'] != '' && TL_MODE == 'FE')
		{
			$objTemplate->$strHrefKey = $arrItem['imageUrl'];
			$objTemplate->attributes = '';

			if ($arrItem['fullsize'])
			{
				// Open images in the lightbox
				if (preg_match('/\.(jpe?g|gif|png)$/', $arrItem['imageUrl']))
				{
					// Do not add the TL_FILES_URL to external URLs (see #4923)
					if (strncmp($arrItem['imageUrl'], 'http://', 7) !== 0 && strncmp($arrItem['imageUrl'], 'https://', 8) !== 0)
					{
						$objTemplate->$strHrefKey = TL_FILES_URL . \System::urlEncode($arrItem['imageUrl']);
					}

					$objTemplate->attributes = ($objPage->outputFormat == 'xhtml') ? ' rel="' . $strLightboxId . '"' : ' data-lightbox="' . substr($strLightboxId, 9, -1) . '"';
				}
				else
				{
					$objTemplate->attributes = ($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"';
				}
			}
		}

		// Fullsize view
		elseif ($arrItem['fullsize'] && TL_MODE == 'FE')
		{
			$objTemplate->$strHrefKey = TL_FILES_URL . \System::urlEncode($arrItem['singleSRC']);
			$objTemplate->attributes = ($objPage->outputFormat == 'xhtml') ? ' rel="' . $strLightboxId . '"' : ' data-lightbox="' . substr($strLightboxId, 9, -1) . '"';
		}

		// Do not urlEncode() here because getImage() already does (see #3817)
		$objTemplate->src = TL_FILES_URL . $src;
		$objTemplate->alt = specialchars($arrItem['alt']);
		$objTemplate->title = specialchars($arrItem['title']);
		$objTemplate->linkTitle = $objTemplate->title;
		$objTemplate->fullsize = $arrItem['fullsize'] ? true : false;
		$objTemplate->addBefore = ($arrItem['floating'] != 'below');
		$objTemplate->margin = static::generateMargin($arrMargin);
		$objTemplate->caption = $arrItem['caption'];
		$objTemplate->singleSRC = $arrItem['singleSRC'];
		$objTemplate->addImage = true;
	}


	/**
	 * Add enclosures to a template
	 *
	 * @param object $objTemplate The template object to add the enclosures to
	 * @param array  $arrItem     The element or module as array
	 * @param string $strKey      The name of the enclosures field in $arrItem
	 */
	public static function addEnclosuresToTemplate($objTemplate, $arrItem, $strKey='enclosure')
	{
		$arrEnclosures = deserialize($arrItem[$strKey]);

		if (!is_array($arrEnclosures) || empty($arrEnclosures))
		{
			return;
		}

		$objFiles = \FilesModel::findMultipleByUuids($arrEnclosures);

		if ($objFiles === null)
		{
			if (!\Validator::isUuid($arrEnclosures[0]))
			{
				foreach (array('details', 'answer', 'text') as $key)
				{
					if (isset($objTemplate->$key))
					{
						$objTemplate->$key = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
					}
				}
			}

			return;
		}

		$file = \Input::get('file', true);

		// Send the file to the browser and do not send a 404 header (see #5178)
		if ($file != '')
		{
			while ($objFiles->next())
			{
				if ($file == $objFiles->path)
				{
					static::sendFileToBrowser($file);
				}
			}

			$objFiles->reset();
		}

		/** @var \PageModel $objPage */
		global $objPage;

		$arrEnclosures = array();
		$allowedDownload = trimsplit(',', strtolower(\Config::get('allowedDownload')));

		// Add download links
		while ($objFiles->next())
		{
			if ($objFiles->type == 'file')
			{
				if (!in_array($objFiles->extension, $allowedDownload) || !is_file(TL_ROOT . '/' . $objFiles->path))
				{
					continue;
				}

				$objFile = new \File($objFiles->path, true);
				$strHref = \Environment::get('request');

				// Remove an existing file parameter (see #5683)
				if (preg_match('/(&(amp;)?|\?)file=/', $strHref))
				{
					$strHref = preg_replace('/(&(amp;)?|\?)file=[^&]+/', '', $strHref);
				}

				$strHref .= ((\Config::get('disableAlias') || strpos($strHref, '?') !== false) ? '&amp;' : '?') . 'file=' . \System::urlEncode($objFiles->path);

				$arrMeta = \Frontend::getMetaData($objFiles->meta, $objPage->language);

				if (empty($arrMeta) && $objPage->rootFallbackLanguage !== null)
				{
					$arrMeta = \Frontend::getMetaData($objFiles->meta, $objPage->rootFallbackLanguage);
				}

				// Use the file name as title if none is given
				if ($arrMeta['title'] == '')
				{
					$arrMeta['title'] = specialchars($objFile->basename);
				}

				$arrEnclosures[] = array
				(
					'link'      => $arrMeta['title'],
					'filesize'  => static::getReadableSize($objFile->filesize),
					'title'     => specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['download'], $objFile->basename)),
					'href'      => $strHref,
					'enclosure' => $objFiles->path,
					'icon'      => TL_ASSETS_URL . 'assets/contao/images/' . $objFile->icon,
					'mime'      => $objFile->mime,
					'meta'      => $arrMeta
				);
			}
		}

		$objTemplate->enclosure = $arrEnclosures;
	}


	/**
	 * Set the static URL constants
	 *
	 * @param \PageModel $objPage An optional page object
	 */
	public static function setStaticUrls($objPage=null)
	{
		if (defined('TL_FILES_URL'))
		{
			return;
		}

		// Use the global object (see #5906)
		if ($objPage === null)
		{
			global $objPage;
		}

		$arrConstants = array
		(
			'staticFiles'   => 'TL_FILES_URL',
			'staticPlugins' => 'TL_ASSETS_URL'
		);

		foreach ($arrConstants as $strKey=>$strConstant)
		{
			$url = ($objPage !== null) ? $objPage->$strKey : \Config::get($strKey);

			if ($url == '' || \Config::get('debugMode'))
			{
				define($strConstant, '');
			}
			else
			{
				define($strConstant, '//' . preg_replace('@https?://@', '', $url) . TL_PATH . '/');
			}
		}

		// Backwards compatibility
		define('TL_SCRIPT_URL', TL_ASSETS_URL);
		define('TL_PLUGINS_URL', TL_ASSETS_URL);
	}


	/**
	 * Add a static URL to a script
	 *
	 * @param string $script The script path
	 *
	 * @return string The script path with the static URL
	 */
	public static function addStaticUrlTo($script)
	{
		// The feature is not used
		if (TL_ASSETS_URL == '')
		{
			return $script;
		}

		// Absolut URLs
		if (preg_match('@^https?://@', $script))
		{
			return $script;
		}

		return TL_ASSETS_URL . $script;
	}


	/**
	 * Return the current theme as string
	 *
	 * @return string The name of the theme
	 *
	 * @deprecated Use Backend::getTheme() instead
	 */
	public static function getTheme()
	{
		return \Backend::getTheme();
	}


	/**
	 * Return the back end themes as array
	 *
	 * @return array An array of available back end themes
	 *
	 * @deprecated Use Backend::getThemes() instead
	 */
	public static function getBackendThemes()
	{
		return \Backend::getThemes();
	}


	/**
	 * Get the details of a page including inherited parameters
	 *
	 * @param mixed $intId A page ID or a Model object
	 *
	 * @return \PageModel The page model or null
	 *
	 * @deprecated Use PageModel::findWithDetails() or PageModel->loadDetails() instead
	 */
	public static function getPageDetails($intId)
	{
		if ($intId instanceof \PageModel)
		{
			return $intId->loadDetails();
		}
		elseif ($intId instanceof \Model\Collection)
		{
			/** @var \PageModel $objPage */
			$objPage = $intId->current();

			return $objPage->loadDetails();
		}
		elseif (is_object($intId))
		{
			$strKey = __METHOD__ . '-' . $intId->id;

			// Try to load from cache
			if (\Cache::has($strKey))
			{
				return \Cache::get($strKey);
			}

			// Create a model from the database result
			$objPage = new \PageModel();
			$objPage->setRow($intId->row());
			$objPage->loadDetails();

			\Cache::set($strKey, $objPage);

			return $objPage;
		}
		else
		{
			// Invalid ID
			if (!strlen($intId) || $intId < 1)
			{
				return null;
			}

			$strKey = __METHOD__ . '-' . $intId;

			// Try to load from cache
			if (\Cache::has($strKey))
			{
				return \Cache::get($strKey);
			}

			$objPage = \PageModel::findWithDetails($intId);

			\Cache::set($strKey, $objPage);

			return $objPage;
		}
	}


	/**
	 * Remove old XML files from the share directory
	 *
	 * @param boolean $blnReturn If true, only return the finds and don't delete
	 *
	 * @return array An array of old XML files
	 *
	 * @deprecated Use Automator::purgeXmlFiles() instead
	 */
	protected function removeOldFeeds($blnReturn=false)
	{
		$this->import('Automator');
		$this->Automator->purgeXmlFiles($blnReturn);
	}


	/**
	 * Return true if a class exists (tries to autoload the class)
	 *
	 * @param string $strClass The class name
	 *
	 * @return boolean True if the class exists
	 *
	 * @deprecated Use the PHP function class_exists() instead
	 */
	protected function classFileExists($strClass)
	{
		return class_exists($strClass);
	}


	/**
	 * Restore basic entities
	 *
	 * @param string $strBuffer The string with the tags to be replaced
	 *
	 * @return string The string with the original entities
	 *
	 * @deprecated Use String::restoreBasicEntities() instead
	 */
	public static function restoreBasicEntities($strBuffer)
	{
		return \String::restoreBasicEntities($strBuffer);
	}


	/**
	 * Resize an image and crop it if necessary
	 *
	 * @param string  $image  The image path
	 * @param integer $width  The target width
	 * @param integer $height The target height
	 * @param string  $mode   An optional resize mode
	 *
	 * @return boolean True if the image has been resized correctly
	 *
	 * @deprecated Use Image::resize() instead
	 */
	protected function resizeImage($image, $width, $height, $mode='')
	{
		return \Image::resize($image, $width, $height, $mode);
	}


	/**
	 * Resize an image and crop it if necessary
	 *
	 * @param string  $image  The image path
	 * @param integer $width  The target width
	 * @param integer $height The target height
	 * @param string  $mode   An optional resize mode
	 * @param string  $target An optional target to be replaced
	 * @param boolean $force  Override existing target images
	 *
	 * @return string|null The image path or null
	 *
	 * @deprecated Use Image::get() instead
	 */
	protected function getImage($image, $width, $height, $mode='', $target=null, $force=false)
	{
		return \Image::get($image, $width, $height, $mode, $target, $force);
	}


	/**
	 * Generate an image tag and return it as string
	 *
	 * @param string $src        The image path
	 * @param string $alt        An optional alt attribute
	 * @param string $attributes A string of other attributes
	 *
	 * @return string The image HTML tag
	 *
	 * @deprecated Use Image::getHtml() instead
	 */
	public static function generateImage($src, $alt='', $attributes='')
	{
		return \Image::getHtml($src, $alt, $attributes);
	}


	/**
	 * Return true for backwards compatibility (see #3218)
	 *
	 * @return boolean
	 *
	 * @deprecated Specify 'datepicker'=>true in your DCA file instead
	 */
	protected function getDatePickerString()
	{
		return true;
	}


	/**
	 * Return the installed back end languages as array
	 *
	 * @return array An array of available back end languages
	 *
	 * @deprecated Use System::getLanguages(true) instead
	 */
	protected function getBackendLanguages()
	{
		return $this->getLanguages(true);
	}


	/**
	 * Parse simple tokens that can be used to personalize newsletters
	 *
	 * @param string $strBuffer The text with the tokens to be replaced
	 * @param array  $arrData   The replacement data as array
	 *
	 * @return string The text with the replaced tokens
	 *
	 * @deprecated Use String::parseSimpleTokens() instead
	 */
	protected function parseSimpleTokens($strBuffer, $arrData)
	{
		return \String::parseSimpleTokens($strBuffer, $arrData);
	}


	/**
	 * Convert a DCA file configuration to be used with widgets
	 *
	 * @param array  $arrData  The field configuration array
	 * @param string $strName  The field name in the form
	 * @param mixed  $varValue The field value
	 * @param string $strField The field name in the database
	 * @param string $strTable The table name
	 *
	 * @return array An array that can be passed to a widget
	 *
	 * @deprecated Use Widget::getAttributesFromDca() instead
	 */
	protected function prepareForWidget($arrData, $strName, $varValue=null, $strField='', $strTable='')
	{
		return \Widget::getAttributesFromDca($arrData, $strName, $varValue, $strField, $strTable);
	}


	/**
	 * Return the IDs of all child records of a particular record (see #2475)
	 *
	 * @author Andreas Schempp
	 *
	 * @param mixed   $arrParentIds An array of parent IDs
	 * @param string  $strTable     The table name
	 * @param boolean $blnSorting   True if the table has a sorting field
	 * @param array   $arrReturn    The array to be returned
	 * @param string  $strWhere     Additional WHERE condition
	 *
	 * @return array An array of child record IDs
	 *
	 * @deprecated Use Database::getChildRecords() instead
	 */
	protected function getChildRecords($arrParentIds, $strTable, $blnSorting=false, $arrReturn=array(), $strWhere='')
	{
		return $this->Database->getChildRecords($arrParentIds, $strTable, $blnSorting, $arrReturn, $strWhere);
	}


	/**
	 * Return the IDs of all parent records of a particular record
	 *
	 * @param integer $intId    The ID of the record
	 * @param string  $strTable The table name
	 *
	 * @return array An array of parent record IDs
	 *
	 * @deprecated Use Database::getParentRecords() instead
	 */
	protected function getParentRecords($intId, $strTable)
	{
		return $this->Database->getParentRecords($intId, $strTable);
	}


	/**
	 * Print an article as PDF and stream it to the browser
	 *
	 * @param \ModuleModel $objArticle An article object
	 *
	 * @deprecated Use ModuleArticle->generatePdf() instead
	 */
	protected function printArticleAsPdf($objArticle)
	{
		$objArticle = new \ModuleArticle($objArticle);
		$objArticle->generatePdf();
	}


	/**
	 * Return all page sections as array
	 *
	 * @return array An array of active page sections
	 *
	 * @deprecated See #4693
	 */
	public static function getPageSections()
	{
		return array('header', 'left', 'right', 'main', 'footer');
	}


	/**
	 * Return a "selected" attribute if the option is selected
	 *
	 * @param string $strOption The option to check
	 * @param mixed  $varValues One or more values to check against
	 *
	 * @return string The attribute or an empty string
	 *
	 * @deprecated Use Widget::optionSelected() instead
	 */
	public static function optionSelected($strOption, $varValues)
	{
		return \Widget::optionSelected($strOption, $varValues);
	}


	/**
	 * Return a "checked" attribute if the option is checked
	 *
	 * @param string $strOption The option to check
	 * @param mixed  $varValues One or more values to check against
	 *
	 * @return string The attribute or an empty string
	 *
	 * @deprecated Use Widget::optionChecked() instead
	 */
	public static function optionChecked($strOption, $varValues)
	{
		return \Widget::optionChecked($strOption, $varValues);
	}


	/**
	 * Find a content element in the TL_CTE array and return the class name
	 *
	 * @param string $strName The content element name
	 *
	 * @return string The class name
	 *
	 * @deprecated Use ContentElement::findClass() instead
	 */
	public static function findContentElement($strName)
	{
		return \ContentElement::findClass($strName);
	}


	/**
	 * Find a front end module in the FE_MOD array and return the class name
	 *
	 * @param string $strName The front end module name
	 *
	 * @return string The class name
	 *
	 * @deprecated Use Module::findClass() instead
	 */
	public static function findFrontendModule($strName)
	{
		return \Module::findClass($strName);
	}


	/**
	 * Create an initial version of a record
	 *
	 * @param string  $strTable The table name
	 * @param integer $intId    The ID of the element to be versioned
	 *
	 * @deprecated Use Versions->initialize() instead
	 */
	protected function createInitialVersion($strTable, $intId)
	{
		$objVersions = new \Versions($strTable, $intId);
		$objVersions->initialize();
	}


	/**
	 * Create a new version of a record
	 *
	 * @param string  $strTable The table name
	 * @param integer $intId    The ID of the element to be versioned
	 *
	 * @deprecated Use Versions->create() instead
	 */
	protected function createNewVersion($strTable, $intId)
	{
		$objVersions = new \Versions($strTable, $intId);
		$objVersions->create();
	}
}
