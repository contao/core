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
 * Class Feed
 *
 * Provide methods to generate RSS/Atom feeds.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Library
 */
class Feed extends System
{

	/**
	 * Feed name
	 * @var string
	 */
	protected $strName;

	/**
	 * Data array
	 * @var array
	 */
	protected $arrData = array();

	/**
	 * Items
	 * @var array
	 */
	protected $arrItems = array();


	/**
	 * Take an array of arguments and initialize the object
	 * @param string
	 */
	public function __construct($strName)
	{
		parent::__construct();
		$this->strName = $strName;
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
	 * @return mixed
	 */
	public function __get($strKey)
	{
		return $this->arrData[$strKey];
	}


	/**
	 * Add an item
	 * @param object
	 */
	public function addItem(FeedItem $objItem)
	{
		$this->arrItems[] = $objItem;
	}


	/**
	 * Generate an RSS 2.0 feed and return it as XML string
	 * @return string
	 */
	public function generateRss()
	{
		$xml  = '<?xml version="1.0" encoding="' . $GLOBALS['TL_CONFIG']['characterSet'] . '"?>' . "\n";
		$xml .= '<rss version="2.0">' . "\n";
		$xml .= '  <channel>' . "\n";
		$xml .= '    <title>' . htmlspecialchars($this->title) . '</title>' . "\n";
		$xml .= '    <description>' . htmlspecialchars($this->description) . '</description>' . "\n";
		$xml .= '    <link>' . htmlspecialchars($this->link) . '</link>' . "\n";
		$xml .= '    <language>' . $this->language . '</language>' . "\n";
		$xml .= '    <pubDate>' . date('r', $this->published) . '</pubDate>' . "\n";
		$xml .= '    <generator>TYPOlight webCMS</generator>' . "\n";

		foreach ($this->arrItems as $objItem)
		{
			$xml .= '    <item>' . "\n";
			$xml .= '      <title>' . htmlspecialchars($objItem->title) . '</title>' . "\n";
			$xml .= '      <description><![CDATA[' . preg_replace('/[\n\r]+/', ' ', $objItem->description) . ']]></description>' . "\n";
			$xml .= '      <link>' . htmlspecialchars($objItem->link) . '</link>' . "\n";
			$xml .= '      <pubDate>' . date('r', $objItem->published) . '</pubDate>' . "\n";
			$xml .= '      <guid>' . ($objItem->guid ? $objItem->guid : htmlspecialchars($objItem->link)) . '</guid>' . "\n";

			// Enclosures
			if (is_array($objItem->enclosure))
			{
				foreach ($objItem->enclosure as $arrEnclosure)
				{
					$xml .= '      <enclosure url="' . $arrEnclosure['url'] . '" length="' . $arrEnclosure['length'] . '" type="' . $arrEnclosure['type'] . '" />' . "\n";
				}
			}

			$xml .= '    </item>' . "\n";
		}

		$xml .= '  </channel>' . "\n";
		$xml .= '</rss>';

		return $xml;
	}


	/**
	 * Generate an Atom feed and return it as XML string
	 * @return string
	 */
	public function generateAtom()
	{
		$xml  = '<?xml version="1.0" encoding="' . $GLOBALS['TL_CONFIG']['characterSet'] . '"?>' . "\n";
		$xml .= '<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="' . $this->language . '">' . "\n";
		$xml .= '  <title>' . htmlspecialchars($this->title) . '</title>' . "\n";
		$xml .= '  <subtitle>' . htmlspecialchars($this->description) . '</subtitle>' . "\n";
		$xml .= '  <link rel="alternate" href="' . htmlspecialchars($this->link) . '" />' . "\n";
		$xml .= '  <id>' . htmlspecialchars($this->link) . '</id>' . "\n";
		$xml .= '  <updated>' . preg_replace('/00$/', ':00', date('Y-m-d\TH:i:sO', $this->published)) . '</updated>' . "\n";
		$xml .= '  <generator>TYPOlight webCMS</generator>' . "\n";
		$xml .= '  <link rel="self" href="' . htmlspecialchars($this->Environment->base . $this->strName) . '.atom" />' . "\n";

		foreach ($this->arrItems as $objItem)
		{
			$xml .= '  <entry>' . "\n";
			$xml .= '    <title>' . htmlspecialchars($objItem->title) . '</title>' . "\n";
			$xml .= '    <summary type="xhtml"><div xmlns="http://www.w3.org/1999/xhtml">' . preg_replace('/[\n\r]+/', ' ', $objItem->description) . '</div></summary>' . "\n";
			$xml .= '    <content type="xhtml"><div xmlns="http://www.w3.org/1999/xhtml">' . preg_replace('/[\n\r]+/', ' ', $objItem->content) . '</div></content>' . "\n";
			$xml .= '    <link rel="alternate" href="' . htmlspecialchars($objItem->link) . '" />' . "\n";
			$xml .= '    <updated>' . preg_replace('/00$/', ':00', date('Y-m-d\TH:i:sO', $objItem->published)) . '</updated>' . "\n";
			$xml .= '    <id>' . ($objItem->guid ? $objItem->guid : htmlspecialchars($objItem->link)) . '</id>' . "\n";

			// Enclosures
			if (is_array($objItem->enclosure))
			{
				foreach ($objItem->enclosure as $arrEnclosure)
				{
					$xml .= '    <link rel="enclosure" type="' . $arrEnclosure['type'] . '" href="' . $arrEnclosure['url'] . '" length="' . $arrEnclosure['length'] . '" />' . "\n";
				}
			}

			$xml .= '  </entry>' . "\n";
		}

		return $xml . '</feed>';
	}
}


/**
 * Class FeedItem
 *
 * Provide methods to generate RSS/Atom feed items.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
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
	public function __construct($arrData=false)
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
	 * @return mixed
	 */
	public function __get($strKey)
	{
		return $this->arrData[$strKey];
	}


	/**
	 * Add an enclosure
	 * @param string
	 */
	public function addEnclosure($strFile)
	{
		if (!file_exists(TL_ROOT . '/' . $strFile))
		{
			return;
		}

		$objFile = new File($strFile);

		$this->arrData['enclosure'][] = array
		(
			'url' => $this->Environment->base . $strFile,
			'length' => $objFile->size,
			'type' => $objFile->mime
		);
	}
}

?>