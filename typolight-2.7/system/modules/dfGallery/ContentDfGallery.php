<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    DfGallery
 * @license    LGPL
 * @filesource
 */


/**
 * Class ContentDfGallery
 *
 * Provide methods to render the dfGallery content element.
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer
 * @package    Controller
 */
class ContentDfGallery extends ContentElement
{

	/**
	 * Albums array
	 * @var array
	 */
	protected $arrAlbums = array();

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_dfgallery';


	/**
	 * Make sure the SWFobject plugin is available
	 * @return string
	 */
	public function generate()
	{
		if (!file_exists(TL_ROOT . '/plugins/swfobject/swfobject.js'))
		{
			throw new Exception('Plugin "swfobject" required');
		}

		if (!in_array('dfGallery', $this->Config->getActiveModules()))
		{
			throw new Exception('Module "dfGallery" required');
		}

		if (!strlen($this->singleSRC) || !is_dir(TL_ROOT . '/' . $this->singleSRC))
		{
			return '';
		}

		if (TL_MODE == 'BE')
		{
			return $this->alt;
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		$jsonName = 'system/html/gallery-' . $this->id . $GLOBALS['TL_LANGUAGE'] . '.json';

		// Generate JSON file
		if (!file_exists(TL_ROOT . '/' . $jsonName))
		{
			$this->fetchDfAlbums($this->singleSRC);

			// Load template file
			$dfConfig = array();
			require(TL_ROOT . '/system/modules/dfGallery/templates/' . ($this->dfTemplate ? $this->dfTemplate : 'df_default') . '.tpl');

			// Set meta data
			$dfGallery = array
			(
				'albums' => array(),
				'meta' => array
				(
					'generator' => 'TYPOlight webCMS',
					'version' => VERSION . '.' . BUILD,
					'description' => 'dfGallery configuration file',
					'author' => 'Leo Feyer',
					'timestamp' => time()
				),
				'config' => $dfConfig
			);

			// Add albums
			foreach ($this->arrAlbums as $folder=>$images)
			{
				$dfGallery['albums'][] = array
				(
					'properties' => array
					(
						'album_type' => 'custom',
						'title' => $folder,
						'description' => '',
						'icon' => '',
						'exif-type' => 'none'
					),
					'images' => $images
				);
			}

			// Load theme XML file
			$strXmlUrl = TL_ROOT . '/system/modules/dfGallery/resources/themes/' . $dfGallery['config']['global']['theme'] . '/skins/' . str_replace('.png', '.xml', $dfGallery['config']['global']['skin']);
			$dfGallery['config']['skin']['config_xml'] = file_get_contents($strXmlUrl);

			// Then set absolute path to theme file
			$dfGallery['config']['global']['theme'] = $this->Environment->base . 'system/modules/dfGallery/resources/themes/' . $dfGallery['config']['global']['theme'];

			// Convert to JSON and write to file
			$objFile = new File($jsonName);
			$objFile->write(json_encode($dfGallery));
			$objFile->close();
		}
		
		$this->Template->alt = $this->alt;
		$this->Template->href = TL_PATH . '/system/modules/dfGallery/DfGallery.swf';
		$this->Template->flashId = strlen($this->flashID) ? $this->flashID : 'swf_' . $this->id;
		$this->Template->flashvars = 'xmlUrl=' . $jsonName;
		$this->Template->src = $this->singleSRC;

		$size = deserialize($this->dfSize);

		$this->Template->width = $size[0];
		$this->Template->height = $size[1];

		// Add JavaScript
		$GLOBALS['TL_JAVASCRIPT'][] = 'plugins/swfobject/swfobject.js';
	}


	/**
	 * Browse the directory structure and fetch all albums
	 * @param string
	 */
	private function fetchDfAlbums($url)
	{
		$this->parseMetaFile($url);

		// Scan folder an sort resources
		$images = scan(TL_ROOT . '/' . $url);
		natcasesort($images);
		$images = array_values($images);

		// Determin the relative path
		$key = preg_replace('/^' . preg_quote($GLOBALS['TL_CONFIG']['uploadPath'], '/') . '\//i', '', $url);

		for ($i=0; $i<count($images); $i++)
		{
			// Skip hidden files or folders
			if (strncmp($images[$i], '.', 1) === 0)
			{
				continue;
			}

			// Add subfolders as separate albums
			if (is_dir(TL_ROOT . '/' . $url . '/' . $images[$i]))
			{
				$this->fetchDfAlbums($url . '/' . $images[$i]);
				continue;
			}

			$objFile = new File($url . '/' . $images[$i]);

			// Skip non-image files
			if (!$objFile->isGdImage)
			{
				continue;
			}

			$this->arrAlbums[$key][] = array
			(
				'title' => strlen($this->arrMeta[$objFile->basename][0]) ? $this->arrMeta[$objFile->basename][0] : ucfirst(str_replace('_', ' ', preg_replace('/^[0-9]+_/', '', $objFile->filename))),
				'timestamp' => $objFile->mtime,
				'thumbnail' => $this->getImage($url . '/' . $images[$i], 100, 80),
				'image' => $url . '/' . $images[$i]
			);
		}
	}
}

?>