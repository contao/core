<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ContentDownload
 *
 * Front end content element "download".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ContentDownload extends \ContentElement
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

		$objFile = \FilesModel::findByPk($this->singleSRC);

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

		$file = \Input::get('file', true);

		// Send the file to the browser and do not send a 404 header (see #4632)
		if ($file != '' && $file == $objFile->path)
		{
			$this->sendFileToBrowser($file);
		}

		$this->singleSRC = $objFile->path;
		return parent::generate();
	}


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		$objFile = new \File($this->singleSRC, true);

		if ($this->linkTitle == '')
		{
			$this->linkTitle = $objFile->basename;
		}

		$strHref = \Environment::get('request');

		// Remove an existing file parameter (see #5683)
		if (preg_match('/(&(amp;)?|\?)file=/', $strHref))
		{
			$strHref = preg_replace('/(&(amp;)?|\?)file=[^&]+/', '', $strHref);
		}

		$strHref .= (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos($strHref, '?') !== false) ? '&amp;' : '?') . 'file=' . \System::urlEncode($objFile->value);

		$this->Template->link = $this->linkTitle;
		$this->Template->title = specialchars($this->titleText ?: $this->linkTitle);
		$this->Template->href = $strHref;
		$this->Template->filesize = $this->getReadableSize($objFile->filesize, 1);
		$this->Template->icon = TL_ASSETS_URL . 'assets/contao/images/' . $objFile->icon;
		$this->Template->mime = $objFile->mime;
		$this->Template->extension = $objFile->extension;
		$this->Template->path = $objFile->dirname;
	}
}
