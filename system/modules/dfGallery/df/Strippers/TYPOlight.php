<?php

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
 * @package    DfGallery
 * @license    LGPL
 * @filesource
 */


/**
 * Initialize the TYPOlight framework
 */
require "../../../../system/initialize.php";


/**
 * Class Strippers_TYPOlight
 * 
 * Provide methods to strip images from a TYPOlight folder.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class Strippers_TYPOlight extends Frontend
{

	/**
	 * Photos array
	 * @param array
	 */
	protected $photos = array();


	/**
	 * Initialize the image stripper
	 * @param string
	 */
	public function __construct($url)
	{
   		$this->fetchAlbums(preg_replace('~/$~', '', $url));
		ksort($this->photos);
	}


	/**
	 * Return an XML document containing all the photos
	 * @return object
	 */
	public function getResult()
	{
		$doc = new DOMDocument;
		$root = $doc->createElement('gallery');
		$doc->appendChild($root);

		// Albums
		foreach($this->photos as $k=>$v)
		{
			$album = $doc->createElement('album');
			$album->setAttribute('title', $k);

			// Photos
			foreach ($v as $photos)
			{
				$photo = $doc->createElement('photo');

				foreach ($photos as $kk=>$vv)
				{
					$photo->setAttribute($kk, $vv);
				}

				$album->appendChild($photo);
			}

			$root->appendChild($album);
		}

		return $doc;
	}


	/**
	 * Browse the directory structure and fetch all albums
	 * @param string
	 */
	private function fetchAlbums($url)
	{
		$this->parseMetaFile($url);

		// Scan folder an sort resources
		$albums = scan(TL_ROOT . '/' . $url);
		natcasesort($albums);
		$albums = array_values($albums);

		// Determin the relative path
		$key = preg_replace('/^' . preg_quote($GLOBALS['TL_CONFIG']['uploadPath'], '/') . '\//i', '', $url);

		for ($i=0; $i<count($albums); $i++)
		{
			// Hidden files or folders
			if (strncmp($albums[$i], '.', 1) === 0)
			{
				continue;
			}

			// Subfolders
			if (is_dir(TL_ROOT . '/' . $url . '/' . $albums[$i]))
			{
				$this->fetchAlbums($url . '/' . $albums[$i]);
				continue;
			}

			$objFile = new File($url . '/' . $albums[$i]);

			// Currently only JPGs seem to be supported
			if ($objFile->extension != 'jpg' && $objFile->extension != 'jpeg')
			{
				continue;
			}

			$this->photos[$key][$i]['id']	 = '';
			$this->photos[$key][$i]['link']  = $url . '/' . $albums[$i];
			$this->photos[$key][$i]['image'] = $url . '/' . $albums[$i];
			$this->photos[$key][$i]['thumb'] = $this->getImage($url . '/' . $albums[$i], 150, 100);
			$this->photos[$key][$i]['title'] = strlen($this->arrMeta[$objFile->basename][0]) ? $this->arrMeta[$objFile->basename][0] : ucfirst(str_replace('_', ' ', preg_replace('/^[0-9]+_/', '', $objFile->filename)));
			$this->photos[$key][$i]['date']  = date($GLOBALS['TL_CONFIG']['datimFormat'], $objFile->mtime);
		}
	}
}

?>