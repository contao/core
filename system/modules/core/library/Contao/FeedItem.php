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
 * @property string  $title       The item title
 * @property string  $link        The item link
 * @property integer $published   The publication status
 * @property integer $begin       The start date
 * @property integer $end         The end date
 * @property string  $author      The item author
 * @property string  $description The item description
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FeedItem
{

	/**
	 * Data
	 * @var array
	 */
	protected $arrData = array();


	/**
	 * Set the data from an array
	 *
	 * @param array $arrData An optional data array
	 */
	public function __construct($arrData=null)
	{
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
		$this->arrData[$strKey] = str_replace(array('[-]', '&shy;', '[nbsp]', '&nbsp;'), array('', '', ' ', ' '), $varValue);
	}


	/**
	 * Return an object property
	 *
	 * @param string $strKey The property name
	 *
	 * @return mixed|null The property value
	 */
	public function __get($strKey)
	{
		if (isset($this->arrData[$strKey]))
		{
			return $this->arrData[$strKey];
		}

		return null;
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

		$objFile = new \File($strFile, true);

		$this->arrData['enclosure'][] = array
		(
			'url' => \Environment::get('base') . \System::urlEncode($strFile),
			'length' => $objFile->size,
			'type' => $objFile->mime
		);
	}
}
