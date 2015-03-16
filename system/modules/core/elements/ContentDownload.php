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
 * Front end content element "download".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
	 *
	 * @return string
	 */
	public function generate()
	{
		// Return if there is no file
		if ($this->singleSRC == '')
		{
			return '';
		}

		$objFile = \FilesModel::findByUuid($this->singleSRC);

		if ($objFile === null)
		{
			if (!\Validator::isUuid($this->singleSRC))
			{
				return '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
			}

			return '';
		}

		$allowedDownload = trimsplit(',', strtolower(\Config::get('allowedDownload')));

		// Return if the file type is not allowed
		if (!in_array($objFile->extension, $allowedDownload))
		{
			return '';
		}

		$file = \Input::get('file', true);

		// Send the file to the browser and do not send a 404 header (see #4632)
		if ($file != '' && $file == $objFile->path)
		{
			\Controller::sendFileToBrowser($file);
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
			$this->linkTitle = specialchars($objFile->basename);
		}

		$strHref = \Environment::get('request');

		// Remove an existing file parameter (see #5683)
		if (preg_match('/(&(amp;)?|\?)file=/', $strHref))
		{
			$strHref = preg_replace('/(&(amp;)?|\?)file=[^&]+/', '', $strHref);
		}

		$strHref .= ((\Config::get('disableAlias') || strpos($strHref, '?') !== false) ? '&amp;' : '?') . 'file=' . \System::urlEncode($objFile->value);

		$this->Template->link = $this->linkTitle;
		$this->Template->title = specialchars($this->titleText ?: sprintf($GLOBALS['TL_LANG']['MSC']['download'], $objFile->basename));
		$this->Template->href = $strHref;
		$this->Template->filesize = $this->getReadableSize($objFile->filesize, 1);
		$this->Template->icon = TL_ASSETS_URL . 'assets/contao/images/' . $objFile->icon;
		$this->Template->mime = $objFile->mime;
		$this->Template->extension = $objFile->extension;
		$this->Template->path = $objFile->dirname;
	}
}
