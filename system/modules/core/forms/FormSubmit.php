<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
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
 * Class FormSubmit
 *
 * Form submit button.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class FormSubmit extends \Widget
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_submit';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'required':
			case 'mandatory':
				// Ignore
				break;

			case 'singleSRC':
				$this->arrConfiguration['singleSRC'] = $varValue;
				break;

			case 'imageSubmit':
				$this->arrConfiguration['imageSubmit'] = $varValue ? true : false;
				break;

			case 'name':
				$this->arrAttributes['name'] = $varValue;
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Do not validate
	 */
	public function validate()
	{
		return;
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		if ($this->imageSubmit)
		{
			// Check for version 3 format
			if ($this->singleSRC != '' && !is_numeric($this->singleSRC))
			{
				return '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
			}

			$objModel = \FilesModel::findByPk($this->singleSRC);

			if ($objModel !== null && is_file(TL_ROOT . '/' . $objModel->path))
			{
				return sprintf('<input type="image" src="%s" id="ctrl_%s" class="submit%s" title="%s" alt="%s"%s%s',
								$objModel->path,
								$this->strId,
								(($this->strClass != '') ? ' ' . $this->strClass : ''),
								specialchars($this->slabel),
								specialchars($this->slabel),
								$this->getAttributes(),
								$this->strTagEnding);
			}
		}

		// Return the regular button
		return sprintf('<input type="submit" id="ctrl_%s" class="submit%s" value="%s"%s%s',
						$this->strId,
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						specialchars($this->slabel),
						$this->getAttributes(),
						$this->strTagEnding);
	}
}
