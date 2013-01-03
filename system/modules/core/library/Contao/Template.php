<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright Leo Feyer 2005-2013
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

		ob_start();
		include $this->getTemplate($this->strTemplate, $this->strFormat);
		$strBuffer = ob_get_contents();
		ob_end_clean();

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
		header('Content-Type: ' . $this->strContentType . '; charset=' . $GLOBALS['TL_CONFIG']['characterSet']);

		// Debug information
		if ($GLOBALS['TL_CONFIG']['debugMode'])
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

			// Switch to milliseconds if less than one second
			if ($intElapsed > 1)
			{
				$intTime = $intElapsed;
				$strUnit = 's';
			}
			else
			{
				$intTime = $intElapsed * 1000;
				$strUnit = 'ms';
			}

			$strDebug = '<div id="debug" class="' . \Input::cookie('CONTAO_CONSOLE') . '">' . "\n"
				. '<p><span class="info">Contao debug information</span> <span class="time">Execution time: ' . $this->getFormattedNumber($intTime, 0) . ' ' . $strUnit . '</span> <span class="memory">Memory usage: ' . $this->getReadableSize(memory_get_peak_usage()) . '</span> <span class="db">Database queries: ' . count($GLOBALS['TL_DEBUG']['database_queries']) . '</span> <span class="rows">Rows: ' . $intReturned . ' returned, ' . $intAffected . ' affected</span> <span id="tog">&nbsp;</span></p>' . "\n"
				. '<div><pre>' . "\n";

			ob_start();
			ksort($GLOBALS['TL_DEBUG']);
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
						. "$$('#debug p','#debug div').setStyle('width',window.getSize().x);"
						. "$(document.body).setStyle('margin-bottom', $('debug').hasClass('closed')?'60px':'320px');"
						. "$('tog').addEvent('click',function(e) {"
							. "$('debug').toggleClass('closed');"
							. "Cookie.write('CONTAO_CONSOLE',$('debug').hasClass('closed')?'closed':'');"
							. "$(document.body).setStyle('margin-bottom', $('debug').hasClass('closed')?'60px':'320px');"
						. "});"
						. "window.addEvent('resize',function() {"
							. "$$('#debug p','#debug div').setStyle('width',window.getSize().x);"
						. "});"
					. "})(document.id);"
				. $strScriptClose . "\n\n";

			$this->strBuffer = str_replace('</body>', $strDebug . '</body>', $this->strBuffer);
		}

		echo $this->strBuffer;
		exit; // see #4565
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
		if (!$GLOBALS['TL_CONFIG']['minifyMarkup'] || $GLOBALS['TL_CONFIG']['debugMode'])
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
				$strChunk = preg_replace(array('@(?<!:)//(?!W3C|DTD|EN).*@', '/[ \n\t]*(;|=|\{|\}|\[|\]|&&|,|<|>|\',|",|\':|":|: |\|\|)[ \n\t]*/'), array('', '$1'), $strChunk);
				$strChunk = trim($strChunk);
			}
			else
			{
				$arrReplace = array
				(
					'/\n ?\n+/'      => "\n",   // Convert multiple line-breaks
					'/^[\t ]+</m'    => '<',    // Remove tag indentation
					'/>( )?\n</'     => '>$1<', // Remove line-breaks between tags
					'/\n/'           => '',     // Remove all remaining line-breaks
					'/ <\/(div|p)>/' => '</$1>' // Remove spaces before closing DIV and P tags
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
