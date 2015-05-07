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
 * Front end content element "hyperlink".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ContentHyperlink extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_hyperlink';


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		/** @var \PageModel $objPage */
		global $objPage;

		if (substr($this->url, 0, 7) == 'mailto:')
		{
			$this->url = \String::encodeEmail($this->url);
		}
		else
		{
			$this->url = ampersand($this->url);
		}

		$embed = explode('%s', $this->embed);

		if ($this->linkTitle == '')
		{
			$this->linkTitle = $this->url;
		}

		// Use an image instead of the title
		if ($this->useImage && $this->singleSRC != '')
		{
			$objModel = \FilesModel::findByUuid($this->singleSRC);

			if ($objModel === null)
			{
				if (!\Validator::isUuid($this->singleSRC))
				{
					$this->Template->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
			}
			elseif (is_file(TL_ROOT . '/' . $objModel->path))
			{
				/** @var \FrontendTemplate|object $objTemplate */
				$objTemplate = new \FrontendTemplate('ce_hyperlink_image');

				$this->Template = $objTemplate;
				$this->Template->setData($this->arrData);

				$this->singleSRC = $objModel->path;
				$this->addImageToTemplate($this->Template, $this->arrData);

				$this->Template->linkTitle = specialchars($this->linkTitle);
			}
		}

		if (strncmp($this->rel, 'lightbox', 8) !== 0 || $objPage->outputFormat == 'xhtml')
		{
			$this->Template->attribute = ' rel="'. $this->rel .'"';
		}
		else
		{
			$this->Template->attribute = ' data-lightbox="'. substr($this->rel, 9, -1) .'"';
		}

		$this->Template->rel = $this->rel; // Backwards compatibility
		$this->Template->href = $this->url;
		$this->Template->embed_pre = $embed[0];
		$this->Template->embed_post = $embed[1];
		$this->Template->link = $this->linkTitle;
		$this->Template->linkTitle = specialchars($this->titleText ?: $this->linkTitle);
		$this->Template->target = '';

		// Override the link target
		if ($this->target)
		{
			$this->Template->target = ($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"';
		}

		// Unset the title attributes in the back end (see #6258)
		if (TL_MODE == 'BE')
		{
			$this->Template->title = '';
			$this->Template->linkTitle = '';
		}
	}
}
