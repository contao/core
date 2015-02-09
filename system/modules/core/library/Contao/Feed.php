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
 * Creates RSS or Atom feeds
 *
 * The class provides an interface to create RSS or Atom feeds. You can add the
 * feed item objects and the class will generate the XML markup.
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
 * @property string  $title       The feed title
 * @property string  $description The feed description
 * @property string  $language    The feed language
 * @property string  $link        The feed link
 * @property integer $published   The publication date
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Feed
{

	/**
	 * Feed name
	 * @var string
	 */
	protected $strName;

	/**
	 * Data
	 * @var array
	 */
	protected $arrData = array();

	/**
	 * Items
	 * @var array
	 */
	protected $arrItems = array();


	/**
	 * Store the feed name (without file extension)
	 *
	 * @param string $strName The feed name
	 */
	public function __construct($strName)
	{
		$this->strName = $strName;
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
	 * Add an item
	 *
	 * @param \FeedItem $objItem The feed item object
	 */
	public function addItem(\FeedItem $objItem)
	{
		$this->arrItems[] = $objItem;
	}


	/**
	 * Generate an RSS 2.0 feed and return it as XML string
	 *
	 * @return string The RSS feed markup
	 */
	public function generateRss()
	{
		$this->adjustPublicationDate();

		$xml  = '<?xml version="1.0" encoding="' . \Config::get('characterSet') . '"?>';
		$xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
		$xml .= '<channel>';
		$xml .= '<title>' . specialchars($this->title) . '</title>';
		$xml .= '<description>' . specialchars($this->description) . '</description>';
		$xml .= '<link>' . specialchars($this->link) . '</link>';
		$xml .= '<language>' . $this->language . '</language>';
		$xml .= '<pubDate>' . date('r', $this->published) . '</pubDate>';
		$xml .= '<generator>Contao Open Source CMS</generator>';
		$xml .= '<atom:link href="' . specialchars(\Environment::get('base') . 'share/' . $this->strName) . '.xml" rel="self" type="application/rss+xml" />';

		foreach ($this->arrItems as $objItem)
		{
			$xml .= '<item>';
			$xml .= '<title>' . specialchars(strip_tags($objItem->title)) . '</title>';
			$xml .= '<description><![CDATA[' . preg_replace('/[\n\r]+/', ' ', $objItem->description) . ']]></description>';
			$xml .= '<link>' . specialchars($objItem->link) . '</link>';
			$xml .= '<pubDate>' . date('r', $objItem->published) . '</pubDate>';

			// Add the GUID
			if ($objItem->guid)
			{
				// Add the isPermaLink attribute if the guid is not a link (see #4930)
				if (strncmp($objItem->guid, 'http://', 7) !== 0 && strncmp($objItem->guid, 'https://', 8) !== 0)
				{
					$xml .= '<guid isPermaLink="false">' . $objItem->guid . '</guid>';
				}
				else
				{
					$xml .= '<guid>' . $objItem->guid . '</guid>';
				}
			}
			else
			{
				$xml .= '<guid>' . specialchars($objItem->link) . '</guid>';
			}

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
	 *
	 * @return string The Atom feed markup
	 */
	public function generateAtom()
	{
		$this->adjustPublicationDate();

		$xml  = '<?xml version="1.0" encoding="' . \Config::get('characterSet') . '"?>';
		$xml .= '<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="' . $this->language . '">';
		$xml .= '<title>' . specialchars($this->title) . '</title>';
		$xml .= '<subtitle>' . specialchars($this->description) . '</subtitle>';
		$xml .= '<link rel="alternate" href="' . specialchars($this->link) . '" />';
		$xml .= '<id>' . specialchars($this->link) . '</id>';
		$xml .= '<updated>' . preg_replace('/00$/', ':00', date('Y-m-d\TH:i:sO', $this->published)) . '</updated>';
		$xml .= '<generator>Contao Open Source CMS</generator>';
		$xml .= '<link href="' . specialchars(\Environment::get('base') . 'share/' . $this->strName) . '.xml" rel="self" />';

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
	 */
	protected function adjustPublicationDate()
	{
		if (!empty($this->arrItems) && $this->arrItems[0]->published > $this->published)
		{
			$this->published = $this->arrItems[0]->published;
		}
	}
}
