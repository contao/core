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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ContentDownload
 *
 * Front end content element "download".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ContentDownload extends ContentElement
{

	/**
	 * File object
	 * @var object
	 */
	protected $objFile;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_download';


	/**
	 * Return if the file does not exist
	 * @return string
	 */
	public function generate()
	{
		// Return if there is no file
		if (!strlen($this->singleSRC) || !is_file(TL_ROOT . '/' . $this->singleSRC))
		{
			return '';
		}

		$objFile = new File($this->singleSRC);
		$allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

		// Return if the file type is not allowed
		if (!in_array($objFile->extension, $allowedDownload))
		{
			return '';
		}

		$this->objFile = $objFile;

		// Send the file to the browser
		if (strlen($this->Input->get('file', true)) && $this->Input->get('file', true) == $this->singleSRC)
		{
			$this->sendFileToBrowser($this->Input->get('file', true));
		}

		return parent::generate();
	}


	/**
	 * Generate content element
	 */
	protected function compile()
	{
		if (!strlen($this->linkTitle))
		{
			$this->linkTitle = $this->objFile->basename;
		}

		$this->Template->link = $this->linkTitle;
		$this->Template->title = specialchars($this->linkTitle);
		$this->Template->href = $this->Environment->request . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos($this->Environment->request, '?') !== false) ? '&amp;' : '?') . 'file=' . $this->urlEncode($this->singleSRC);
		$this->Template->filesize = $this->getReadableSize($this->objFile->filesize, 1);
		$this->Template->icon = TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/' . $this->objFile->icon;
		$this->Template->mime = $this->objFile->mime;
		$this->Template->extension = $this->objFile->extension;
	}
}

?>