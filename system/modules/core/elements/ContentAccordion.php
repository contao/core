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
 * Class ContentAccordion
 *
 * Front end content element "accordion".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ContentAccordion extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_accordion';


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		global $objPage;

		// Clean RTE output
		if ($objPage->outputFormat == 'xhtml')
		{
			$this->text = \String::toXhtml($this->text);
		}
		else
		{
			$this->text = \String::toHtml5($this->text);
		}

		$this->Template->text = \String::encodeEmail($this->text);
		$this->Template->addImage = false;

		// Add an image
		if ($this->addImage && $this->singleSRC != '')
		{
			if (!is_numeric($this->singleSRC))
			{
				$this->Template->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
			}
			else
			{
				$objModel = \FilesModel::findByPk($this->singleSRC);

				if ($objModel !== null && is_file(TL_ROOT . '/' . $objModel->path))
				{
					$this->singleSRC = $objModel->path;
					$this->addImageToTemplate($this->Template, $this->arrData);
				}
			}
		}

		$classes = deserialize($this->mooClasses);

		$this->Template->toggler = $classes[0] ?: 'toggler';
		$this->Template->accordion = $classes[1] ?: 'accordion';
		$this->Template->headlineStyle = $this->mooStyle;
		$this->Template->headline = $this->mooHeadline;
	}
}
