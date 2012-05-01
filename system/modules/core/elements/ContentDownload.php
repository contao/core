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
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \ContentElement, \Environment, \File, \FilesModel, \Input;


/**
 * Class ContentDownload
 *
 * Front end content element "download".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Core
 */
class ContentDownload extends ContentElement
{

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
		if ($this->singleSRC == '')
		{
			return '';
		}

		// Check for version 3 format
		if (!is_numeric($this->singleSRC))
		{
			return '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
		}

		$objFile = FilesModel::findByPk($this->singleSRC);

		if ($objFile === null)
		{
			return '';
		}

		$allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

		// Return if the file type is not allowed
		if (!in_array($objFile->extension, $allowedDownload))
		{
			return '';
		}

		$file = Input::get('file', true);

		// Send the file to the browser
		if ($file != '')
		{
			if ($file == $objFile->path)
			{
				$this->sendFileToBrowser($file);
			}

			// Do not index or cache the page
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			// Send a 404 header
			header('HTTP/1.1 404 Not Found');
			return '<p class="error">' . sprintf($GLOBALS['TL_LANG']['ERR']['download'], $file) . '</p>';
		}

		$this->singleSRC = $objFile->path;
		return parent::generate();
	}


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		$objFile = new File($this->singleSRC);

		if ($this->linkTitle == '')
		{
			$this->linkTitle = $objFile->basename;
		}

		$this->Template->link = $this->linkTitle;
		$this->Template->title = specialchars($this->linkTitle);
		$this->Template->href = Environment::get('request') . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos(Environment::get('request'), '?') !== false) ? '&amp;' : '?') . 'file=' . $this->urlEncode($objFile->value);
		$this->Template->filesize = $this->getReadableSize($objFile->filesize, 1);
		$this->Template->icon = TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/' . $objFile->icon;
		$this->Template->mime = $objFile->mime;
		$this->Template->extension = $objFile->extension;
		$this->Template->path = $objFile->dirname;
	}
}
