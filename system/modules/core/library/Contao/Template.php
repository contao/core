<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;


/**
 * Parses and outputs template files
 *
 * The class supports loading template files, adding variables to them and then
 * printing them to the screen. It functions as abstract parent class for the
 * two core classes "BackendTemplate" and "FrontendTemplate".
 *
 * Usage:
 *
 *     $template = new BackendTemplate();
 *     $template->name = 'Leo Feyer';
 *     $template->output();
 *
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2014
 */
abstract class Template extends \Controller
{

	/**
	 * Template file
	 * @var string
	 */
	protected $strTemplate;

	/**
	 * Output buffer
	 * @var string
	 */
	protected $strBuffer;

	/**
	 * Content type
	 * @var string
	 */
	protected $strContentType;

	/**
	 * Output format
	 * @var string
	 */
	protected $strFormat = 'html5';

	/**
	 * Tag ending
	 * @var string
	 */
	protected $strTagEnding = '>';

	/**
	 * Template data
	 * @var array
	 */
	protected $arrData = array();


	/**
	 * Create a new template object
	 *
	 * @param string $strTemplate    The template name
	 * @param string $strContentType The content type (defaults to "text/html")
	 */
	public function __construct($strTemplate='', $strContentType='text/html')
	{
		parent::__construct();

		$this->strTemplate = $strTemplate;
		$this->strContentType = $strContentType;
	}


	/**
	 * Set an object property
	 *
	 * @param string $strKey   The property name
	 * @param mixed  $varValue The property value
	 */
	public function __set($strKey, $varValue)
	{
		$this->arrData[$strKey] = $varValue;
	}


	/**
	 * Return an object property
	 *
	 * @param string $strKey The property name
	 *
	 * @return mixed The property value
	 */
	public function __get($strKey)
	{
		if (isset($this->arrData[$strKey]))
		{
			return $this->arrData[$strKey];
		}

		return parent::__get($strKey);
	}


	/**
	 * Execute a callable and return the result
	 *
	 * @param string $strKey    The name of the key
	 * @param array  $arrParams The parameters array
	 *
	 * @return mixed The callable return value
	 *
	 * @throws \InvalidArgumentException If the callable does not exist
	 */
	public function __call($strKey, $arrParams)
	{
		if (!isset($this->arrData[$strKey]) || !is_callable($this->arrData[$strKey]))
		{
			throw new \InvalidArgumentException("$strKey is not set or not a callable");
		}

		return call_user_func_array($this->arrData[$strKey], $arrParams);
	}


	/**
	 * Check whether a property is set
	 *
	 * @param string $strKey The property name
	 *
	 * @return boolean True if the property is set
	 */
	public function __isset($strKey)
	{
		return isset($this->arrData[$strKey]);
	}


	/**
	 * Set the template data from an array
	 *
	 * @param array $arrData The data array
	 */
	public function setData($arrData)
	{
		$this->arrData = $arrData;
	}


	/**
	 * Return the template data as array
	 *
	 * @return array The data array
	 */
	public function getData()
	{
		return $this->arrData;
	}


	/**
	 * Set the template name
	 *
	 * @param string $strTemplate The template name
	 */
	public function setName($strTemplate)
	{
		$this->strTemplate = $strTemplate;
	}


	/**
	 * Return the template name
	 *
	 * @return string The template name
	 */
	public function getName()
	{
		return $this->strTemplate;
	}


	/**
	 * Set the output format
	 *
	 * @param string $strFormat The output format
	 */
	public function setFormat($strFormat)
	{
		$this->strFormat = $strFormat;
	}


	/**
	 * Return the output format
	 *
	 * @return string The output format
	 */
	public function getFormat()
	{
		return $this->strFormat;
	}


	/**
	 * Print all template variables to the screen using print_r
	 */
	public function showTemplateVars()
	{
		echo "<pre>\n";
		print_r($this->arrData);
		echo "</pre>\n";
	}


	/**
	 * Print all template variables to the screen using var_dump
	 */
	public function dumpTemplateVars()
	{
		echo "<pre>\n";
		var_dump($this->arrData);
		echo "</pre>\n";
	}


	/**
	 * Parse the template file and return it as string
	 *
	 * @return string The template markup
	 */
	public function parse()
	{
		if ($this->strTemplate == '')
		{
			return '';
		}

		// Override the output format in the front end
		if (TL_MODE == 'FE')
		{
			global $objPage;

			if ($objPage->outputFormat != '')
			{
				$this->strFormat = $objPage->outputFormat;
			}

			$this->strTagEnding = ($this->strFormat == 'xhtml') ? ' />' : '>';
		}

		// HOOK: add custom parse filters
		if (isset($GLOBALS['TL_HOOKS']['parseTemplate']) && is_array($GLOBALS['TL_HOOKS']['parseTemplate']))
		{
			foreach ($GLOBALS['TL_HOOKS']['parseTemplate'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($this);
			}
		}

		$strPath = $this->getTemplate($this->strTemplate, $this->strFormat);
		$strRelPath = str_replace(TL_ROOT . '/', '', $strPath);

		ob_start();
		include $strPath;
		$strBuffer = ob_get_contents();
		ob_end_clean();

		if (\Config::get('debugMode'))
		{
			$strBuffer = "\n<!-- TEMPLATE START: $strRelPath -->\n$strBuffer\n<!-- TEMPLATE END: $strRelPath -->\n";
		}

		return $strBuffer;
	}


	/**
	 * Parse the template file and print it to the screen
	 */
	public function output()
	{
		if (!$this->strBuffer)
		{
			$this->strBuffer = $this->parse();
		}

		// Minify the markup if activated
		$this->strBuffer = $this->minifyHtml($this->strBuffer);

		// Send some headers
		header('Vary: User-Agent', false);
		header('Content-Type: ' . $this->strContentType . '; charset=' . \Config::get('characterSet'));

		// Debug information
		if (\Config::get('debugMode') && !isset($_GET['popup']))
		{
			$intReturned = 0;
			$intAffected = 0;

			// Count the totals (see #3884)
			if (is_array($GLOBALS['TL_DEBUG']['database_queries']))
			{
				foreach ($GLOBALS['TL_DEBUG']['database_queries'] as $k=>$v)
				{
					$intReturned += $v['return_count'];
					$intAffected += $v['affected_count'];
					unset($GLOBALS['TL_DEBUG']['database_queries'][$k]['return_count']);
					unset($GLOBALS['TL_DEBUG']['database_queries'][$k]['affected_count']);
				}
			}

			$intElapsed = (microtime(true) - TL_START);

			$strDebug = sprintf(
				'<div id="contao-debug" class="%s">'
				. '<p>'
					. '<span class="debug-time">Execution time: %s ms</span>'
					. '<span class="debug-memory">Memory usage: %s</span>'
					. '<span class="debug-db">Database queries: %d</span>'
					. '<span class="debug-rows">Rows: %d returned, %s affected</span>'
					. '<span class="debug-models">Registered models: %d</span>'
					. '<span id="debug-tog">&nbsp;</span>'
				. '</p>'
				. '<div><pre>',
				\Input::cookie('CONTAO_CONSOLE'),
				$this->getFormattedNumber(($intElapsed * 1000), 0),
				$this->getReadableSize(memory_get_peak_usage()),
				count($GLOBALS['TL_DEBUG']['database_queries']),
				$intReturned,
				$intAffected,
				\Model\Registry::getInstance()->count()
			);

			ksort($GLOBALS['TL_DEBUG']);

			ob_start();
			print_r($GLOBALS['TL_DEBUG']);
			$strDebug .= ob_get_contents();
			ob_end_clean();

			if ($this->strFormat == 'xhtml')
			{
				$strScriptOpen = '<script type="text/javascript">' . "\n/* <![CDATA[ */\n";
				$strScriptClose = "\n/* ]]> */\n" . '</script>';
			}
			else
			{
				$strScriptOpen = '<script>';
				$strScriptClose = '</script>';
			}

			$strDebug .= '</pre></div></div>'
				. $strScriptOpen
					. "(function($) {"
						. "$$('#contao-debug>*').setStyle('width',window.getSize().x);"
						. "$(document.body).setStyle('margin-bottom',$('contao-debug').hasClass('closed')?'60px':'320px');"
						. "$('debug-tog').addEvent('click',function(e) {"
							. "$('contao-debug').toggleClass('closed');"
							. "Cookie.write('CONTAO_CONSOLE',$('contao-debug').hasClass('closed')?'closed':'',{path:'" . (TL_PATH ?: '/') . "'});"
							. "$(document.body).setStyle('margin-bottom',$('contao-debug').hasClass('closed')?'60px':'320px');"
						. "});"
						. "window.addEvent('resize',function() {"
							. "$$('#contao-debug>*').setStyle('width',window.getSize().x);"
						. "});"
					. "})(document.id);"
				. $strScriptClose . "\n\n";

			$this->strBuffer = str_replace('</body>', $strDebug . '</body>', $this->strBuffer);
		}

		echo $this->strBuffer;
	}


	/**
	 * Minify the HTML markup preserving pre, script, style and textarea tags
	 *
	 * @param string $strHtml The HTML markup
	 *
	 * @return string The minified HTML markup
	 */
	public function minifyHtml($strHtml)
	{
		// The feature has been disabled
		if (!\Config::get('minifyMarkup') || \Config::get('debugMode'))
		{
			return $strHtml;
		}

		// Split the markup based on the tags that shall be preserved
		$arrChunks = preg_split('@(</?pre[^>]*>)|(</?script[^>]*>)|(</?style[^>]*>)|(</?textarea[^>]*>)@i', $strHtml, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);

		$strHtml = '';
		$blnPreserveNext = false;
		$blnOptimizeNext = false;

		// Recombine the markup
		foreach ($arrChunks as $strChunk)
		{
			if (strncasecmp($strChunk, '<pre', 4) === 0 || strncasecmp($strChunk, '<textarea', 9) === 0)
			{
				$blnPreserveNext = true;
			}
			elseif (strncasecmp($strChunk, '<script', 7) === 0 || strncasecmp($strChunk, '<style', 6) === 0)
			{
				$blnOptimizeNext = true;
			}
			elseif ($blnPreserveNext)
			{
				$blnPreserveNext = false;
			}
			elseif ($blnOptimizeNext)
			{
				$blnOptimizeNext = false;

				// Minify inline scripts
				$strChunk = str_replace(array("/* <![CDATA[ */\n", "<!--\n", "\n//-->"), array('/* <![CDATA[ */', '', ''), $strChunk);
				$strChunk = preg_replace(array('@(?<![:\'"])//(?!W3C|DTD|EN).*@', '/[ \n\t]*(;|=|\{|\}|\[|\]|&&|,|<|>|\',|",|\':|":|: |\|\|)[ \n\t]*/'), array('', '$1'), $strChunk);
				$strChunk = trim($strChunk);
			}
			else
			{
				$arrReplace = array
				(
					'/\n ?\n+/'                   => "\n",    // Convert multiple line-breaks
					'/^[\t ]+</m'                 => '<',     // Remove tag indentation
					'/>\n<(a|input|select|span)/' => '> <$1', // Remove line-breaks between tags
					'/([^>])\n/'                  => '$1 ',   // Remove line-breaks of wrapped text
					'/  +/'                       => ' ',     // Remove redundant whitespace characters
					'/\n/'                        => '',      // Remove all remaining line-breaks
					'/ <\/(div|p)>/'              => '</$1>'  // Remove spaces before closing DIV and P tags
				);

				$strChunk = str_replace("\r", '', $strChunk);
				$strChunk = preg_replace(array_keys($arrReplace), array_values($arrReplace), $strChunk);
				$strChunk = trim($strChunk);
			}

			$strHtml .= $strChunk;
		}

		return $strHtml;
	}


	/**
	 * Print the IE6 warning
	 *
	 * @return string The warning message
	 *
	 * @deprecated The IE6 warning is now in the templates (e.g. be_install)
	 */
	public function showIE6warning()
	{
		return ''; // Backwards compatibility
	}
}
