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
	 * Template data
	 * @var array
	 */
	protected $arrData = array();


	/**
	 * Set current template file
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
		ob_start();
		include($this->getTemplate($this->strTemplate));
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

		$this->strBuffer = $this->minify($this->strBuffer);

		/**
		 * Copyright notice
		 * 
		 * ACCORDING TO THE LESSER GENERAL PUBLIC LICENSE (LGPL),YOU ARE NOT
		 * PERMITTED TO RUN CONTAO WITHOUT THIS COPYRIGHT NOTICE. CHANGING,
		 * REMOVING OR OBSTRUCTING IT IS PROHIBITED BY LAW!
		 */
		$this->strBuffer = preg_replace
		(
			'/([ \t]*<head[^>]*>)\n*/',
			"$1<!--\n\n"
			. "\tThis website is powered by Contao Open Source CMS :: Licensed under GNU/LGPL\n"
			. "\tCopyright ©2005-" . date('Y') . " by Leo Feyer :: Extensions are copyright of their respective owners\n"
			. "\tVisit the project website at http://www.contao.org for more information\n\n"
			. "//-->",
			$this->strBuffer, 1
		);

		header('Content-Type: ' . $this->strContentType . '; charset=' . $GLOBALS['TL_CONFIG']['characterSet']);
		echo $this->strBuffer;

		if ($GLOBALS['TL_CONFIG']['debugMode'])
		{
			echo "\n\n<pre>\n";
			print_r($GLOBALS['TL_DEBUG']);
			echo "\n</pre>";
		}
	}


	/**
	 * Minify the HTML markup
	 * @param string
	 * @return string
	 */
	protected function minify($strMarkup)
	{
		if (!$GLOBALS['TL_CONFIG']['minifyMarkup'])
		{
			return $strMarkup;
		}

		if (!class_exists('tidy', false))
		{
			trigger_error('The PHP tidy extension is required to minify the HTML markup', E_USER_ERROR);
			return $strMarkup;
		}

		// Tidy uses different character set names
		switch ($GLOBALS['TL_CONFIG']['characterSet'])
		{
			case 'UTF-8':
				$cs = 'utf8';
				break;

			case 'ISO-8859-1':
				$cs = 'latin1';
				break;

			default:
				$cs = 'raw';
				break;
		}

		// Tidy configuration
		$config = array
		(
			'clean' => 1,
			'bare' => 1,
			'hide-comments' => 0,
			'indent-spaces' => 0,
			'indent' => 0,
			'tab-size' => 1,
			'wrap' => 0,
			'merge-divs' => 0,
			'break-before-br' => 0,
			'preserve-entities' => 1,
			'output-xhtml' => 1,
			'input-encoding' => $cs,
			'output-encoding' => $cs,
			'char-encoding' => $cs
		);

		// Run tidy
		$tidy = new tidy;
		$tidy->parseString($strMarkup, $config);
		$tidy->cleanRepair();
		$strMarkup = $tidy;

		// Replace line breaks between tags and after br-tags
		$strMarkup = str_replace(array(">\n<", "<br />\n"), array('><', '<br />'), $strMarkup);

		// Split the content to isolate inline scripts
		$arrChunks = preg_split('@(/\*<!\[CDATA\[\*/)|(/\*\]\]>\*/)@', $strMarkup, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
		$strMarkup = '';

		// Handle IE conditional comments
		$arrChunks[0] = preg_replace('/endif\]-->\n+/', 'endif]-->', $arrChunks[0]);
		$strMinimizeNext = false;

		// Recombine the markup
		foreach ($arrChunks as $strChunk)
		{
			if ($strChunk == '/*<![CDATA[*/')
			{
				$strMinimizeNext = true;
			}
			elseif ($strMinimizeNext)
			{
				// Try to remove not required whitespace 
				$strChunk = "\n" . preg_replace('/[ \n\t]*(;|=|\{|\}|&&|,|<|>|\',|",|\':|":|\|\|)[ \n\t]*/', '$1', trim($strChunk)) . "\n";
				$strMinimizeNext = false;
			}

			$strMarkup .= $strChunk;
		}

		unset($arrChunks, $strChunk);
		return $strMarkup;
	}
}

?>