<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Library
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;

use \Environment, \File, \System;


/**
 * Class FeedItem
 *
 * Provide methods to generate RSS/Atom feed items.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class FeedItem extends System
{

	/**
	 * Data array
	 * @var array
	 */
	protected $arrData = array();


	/**
	 * Take an array of arguments and initialize the object
	 * @param array
	 */
	public function __construct($arrData=null)
	{
		parent::__construct();

		if (is_array($arrData))
		{
			$this->arrData = $arrData;
		}
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
		if (isset($this->arrData[$strKey]))
		{
			return $this->arrData[$strKey];
		}

		return parent::__get($strKey);
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
	 * Add an enclosure
	 * @param string
	 */
	public function addEnclosure($strFile)
	{
		if ($strFile == '' || !file_exists(TL_ROOT . '/' . $strFile))
		{
			return;
		}

		$objFile = new File($strFile);

		$this->arrData['enclosure'][] = array
		(
			'url' => Environment::get('base') . $strFile,
			'length' => $objFile->size,
			'type' => $objFile->mime
		);
	}
}
