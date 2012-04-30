<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    System
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \Environment, \FeedItem, \System;


/**
 * Class Feed
 *
 * Provide methods to generate RSS/Atom feeds.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
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
	 * @return void
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
	 * Add an item
	 * @param \FeedItem
	 * @return void
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
		$this->adjustPublicationDate();

		$xml  = '<?xml version="1.0" encoding="' . $GLOBALS['TL_CONFIG']['characterSet'] . '"?>';
		$xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
		$xml .= '<channel>';
		$xml .= '<title>' . specialchars($this->title) . '</title>';
		$xml .= '<description>' . specialchars($this->description) . '</description>';
		$xml .= '<link>' . specialchars($this->link) . '</link>';
		$xml .= '<language>' . $this->language . '</language>';
		$xml .= '<pubDate>' . date('r', $this->published) . '</pubDate>';
		$xml .= '<generator>Contao Open Source CMS</generator>';
		$xml .= '<atom:link href="' . specialchars(Environment::get('base') . $this->strName) . '.xml" rel="self" type="application/rss+xml" />';

		foreach ($this->arrItems as $objItem)
		{
			$xml .= '<item>';
			$xml .= '<title>' . specialchars($objItem->title) . '</title>';
			$xml .= '<description><![CDATA[' . preg_replace('/[\n\r]+/', ' ', $objItem->description) . ']]></description>';
			$xml .= '<link>' . specialchars($objItem->link) . '</link>';
			$xml .= '<pubDate>' . date('r', $objItem->published) . '</pubDate>';
			$xml .= '<guid>' . ($objItem->guid ? $objItem->guid : specialchars($objItem->link)) . '</guid>';

			// Enclosures
			if (is_array($objItem->enclosure))
			{
				foreach ($objItem->enclosure as $arrEnclosure)
				{
					$xml .= '<enclosure url="' . $arrEnclosure['url'] . '" length="' . $arrEnclosure['length'] . '" type="' . $arrEnclosure['type'] . '" />';
				}
			}

			$xml .= '</item>';
		}

		$xml .= '</channel>';
		$xml .= '</rss>';

		return $xml;
	}


	/**
	 * Generate an Atom feed and return it as XML string
	 * @return string
	 */
	public function generateAtom()
	{
		$this->adjustPublicationDate();

		$xml  = '<?xml version="1.0" encoding="' . $GLOBALS['TL_CONFIG']['characterSet'] . '"?>';
		$xml .= '<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="' . $this->language . '">';
		$xml .= '<title>' . specialchars($this->title) . '</title>';
		$xml .= '<subtitle>' . specialchars($this->description) . '</subtitle>';
		$xml .= '<link rel="alternate" href="' . specialchars($this->link) . '" />';
		$xml .= '<id>' . specialchars($this->link) . '</id>';
		$xml .= '<updated>' . preg_replace('/00$/', ':00', date('Y-m-d\TH:i:sO', $this->published)) . '</updated>';
		$xml .= '<generator>Contao Open Source CMS</generator>';
		$xml .= '<link href="' . specialchars(Environment::get('base') . $this->strName) . '.xml" rel="self" />';

		foreach ($this->arrItems as $objItem)
		{
			$xml .= '<entry>';
			$xml .= '<title>' . specialchars($objItem->title) . '</title>';
			$xml .= '<content type="xhtml"><div xmlns="http://www.w3.org/1999/xhtml">' . preg_replace('/[\n\r]+/', ' ', $objItem->description) . '</div></content>';
			$xml .= '<link rel="alternate" href="' . specialchars($objItem->link) . '" />';
			$xml .= '<updated>' . preg_replace('/00$/', ':00', date('Y-m-d\TH:i:sO', $objItem->published)) . '</updated>';
			$xml .= '<id>' . ($objItem->guid ? $objItem->guid : specialchars($objItem->link)) . '</id>';
			$xml .= '<author><name>' . $objItem->author . '</name></author>';

			// Enclosures
			if (is_array($objItem->enclosure))
			{
				foreach ($objItem->enclosure as $arrEnclosure)
				{
					$xml .= '<link rel="enclosure" type="' . $arrEnclosure['type'] . '" href="' . $arrEnclosure['url'] . '" length="' . $arrEnclosure['length'] . '" />';
				}
			}

			$xml .= '</entry>';
		}

		return $xml . '</feed>';
	}


	/**
	 * Adjust the publication date
	 * @return void
	 */
	protected function adjustPublicationDate()
	{
		if (!empty($this->arrItems) && $this->arrItems[0]->published > $this->published)
		{
			$this->published = $this->arrItems[0]->published;
		}
	}
}
