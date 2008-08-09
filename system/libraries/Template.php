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
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class Template
 *
 * Provide methods to handle templates.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class Template extends Controller
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
	 * Print all template variables to the screen
	 */
	public function showTemplateVars()
	{
		print_r($this->arrData);
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

		$arrEncoding = $this->Environment->httpAcceptEncoding;

		// Activate gzip compression
		if ($GLOBALS['TL_CONFIG']['enableGZip'] && (in_array('gzip', $arrEncoding) || in_array('x-gzip', $arrEncoding)) && function_exists('ob_gzhandler') && !ini_get('zlib.output_compression'))
		{
			ob_start('ob_gzhandler');
		}

		header('Content-Type: ' . $this->strContentType . '; charset=' . $GLOBALS['TL_CONFIG']['characterSet']);
		echo $this->strBuffer;

		if ($GLOBALS['TL_CONFIG']['debugMode'])
		{
			echo "\n\n<pre>\n";
			print_r($GLOBALS['TL_DEBUG']);
			echo "\n</pre>";
		}
	}
}

?>