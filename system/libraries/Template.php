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
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class Template
 *
 * Provide methods to handle templates.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
abstract class Template extends Controller
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
	 * Create a new template instance
	 * @param string
	 * @param string
	 * @throws Exception
	 */
	public function __construct($strTemplate='', $strContentType='text/html')
	{
		parent::__construct();

		$this->strTemplate = $strTemplate;
		$this->strContentType = $strContentType;
	}


	/**
	 * Set an object property
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		$this->arrData[$strKey] = $varValue;
	}


	/**
	 * Return an object property
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		return $this->arrData[$strKey];
	}


	/**
	 * Check whether a property is set
	 * @param string
	 * @return boolean
	 */
	public function __isset($strKey)
	{
		return isset($this->arrData[$strKey]);
	}


	/**
	 * Set the template data from an array
	 * @param array
	 */
	public function setData($arrData)
	{
		$this->arrData = $arrData;
	}


	/**
	 * Return the template data as array
	 * @return array
	 */
	public function getData()
	{
		return $this->arrData;
	}


	/**
	 * Set the template name
	 * @param string
	 */
	public function setName($strTemplate)
	{
		$this->strTemplate = $strTemplate;
	}


	/**
	 * Return the template name
	 * @return string
	 */
	public function getName()
	{
		return $this->strTemplate;
	}


	/**
	 * Set the output format
	 * @param string
	 */
	public function setFormat($strFormat)
	{
		$this->strFormat = $strFormat;
	}


	/**
	 * Return the output format
	 * @return string
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
	 * @return string
	 * @throws Exception
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
		include($this->getTemplate($this->strTemplate, $this->strFormat));
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
		$lb = $GLOBALS['TL_CONFIG']['minifyMarkup'] ? '' : "\n";

		/**
		 * Copyright notice
		 * 
		 * ACCORDING TO THE LESSER GENERAL PUBLIC LICENSE (LGPL),YOU ARE NOT
		 * PERMITTED TO RUN CONTAO WITHOUT THIS COPYRIGHT NOTICE. CHANGING,
		 * REMOVING OR OBSTRUCTING IT IS PROHIBITED BY LAW!
		 */
		$this->strBuffer = preg_replace
		(
			'/([ \t]*<title[^>]*>)\n*/',
			"<!--\n\n"
			. "\tThis website is powered by Contao Open Source CMS :: Licensed under GNU/LGPL\n"
			. "\tCopyright ©2005-" . date('Y') . " by Leo Feyer :: Extensions are copyright of their respective owners\n"
			. "\tVisit the project website at http://www.contao.org for more information\n\n"
			. "//-->$lb$1",
			$this->strBuffer, 1
		);

		header('Vary: User-Agent', false);
		header('Content-Type: ' . $this->strContentType . '; charset=' . $GLOBALS['TL_CONFIG']['characterSet']);

		echo $this->strBuffer;

		// Debug information
		if ($GLOBALS['TL_CONFIG']['debugMode'])
		{
			echo "\n\n" . '<pre id="debug" style="width:80%; overflow:auto; margin:24px auto; padding:9px; background:#fff;">' . "\n";
			echo "<strong>Debug information</strong>\n\n";
			print_r($GLOBALS['TL_DEBUG']);
			echo "</pre>";
		}
	}


	/**
	 * Minify HTML markup preserving pre, script, style and textarea tags
	 * @param string
	 * @return string
	 */
	public function minifyHtml($strHtml)
	{
		// The feature has been disabled
		if (!$GLOBALS['TL_CONFIG']['minifyMarkup'])
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
				$strChunk = preg_replace(array('@(?<!:)//.*@', '/[ \n\t]*(;|=|\{|\}|\[|\]|&&|,|<|>|\',|",|\':|":|: |\|\|)[ \n\t]*/'), array('', '$1'), $strChunk);
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
	 */
	public function showIE6warning()
	{
		echo "\n<!--[if lte IE 6]>\n";
		printf($GLOBALS['TL_LANG']['ERR']['ie6warning'], '<div style="background:#ffc;padding:12px;border-bottom:1px solid #e4790f;font-size:14px;color:#000;text-align:center;">', '<a href="http://ie6countdown.com" style="font-size:14px;color:#e4790f;">', '</a>', '</div>');
		echo "\n<![endif]-->\n";
	}
}

?>