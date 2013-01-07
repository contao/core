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
 * Creates items to be appended to RSS or Atom feeds
 * 
 * The class provides an interface to create RSS or Atom feed items. You can
 * then add the items to a Feed object.
 * 
 * Usage:
 * 
 *     $feed = new Feed('news');
 *     $feed->title = 'News feed';
 * 
 *     $item = new FeedItem();
 *     $item->title = 'Latest news';
 *     $item->author = 'Leo Feyer';
 * 
 *     $feed->addItem($item);
 *     echo $feed->generateRss();
 * 
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
class FeedItem extends \System
{

	/**
	 * Data
	 * @var array
	 */
	protected $arrData = array();


	/**
	 * Set the data from an array
	 * 
	 * @param array An optional data array
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
	 * Add an enclosure
	 * 
	 * @param string $strFile The file path
	 */
	public function addEnclosure($strFile)
	{
		if ($strFile == '' || !file_exists(TL_ROOT . '/' . $strFile))
		{
			return;
		}

		$objFile = new \File($strFile);

		$this->arrData['enclosure'][] = array
		(
			'url' => \Environment::get('base') . \System::urlEncode($strFile),
			'length' => $objFile->size,
			'type' => $objFile->mime
		);
	}
}
