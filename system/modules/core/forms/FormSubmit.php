<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class FormSubmit extends \Widget
{

	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'form_submit';

	/**
	 * The CSS class prefix
	 *
	 * @var string
	 */
	protected $strPrefix = 'widget widget-submit';


	/**
	 * Add specific attributes
	 *
	 * @param string $strKey   The attribute name
	 * @param mixed  $varValue The attribute value
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'singleSRC':
				$this->arrConfiguration['singleSRC'] = $varValue;
				break;

			case 'imageSubmit':
				$this->arrConfiguration['imageSubmit'] = $varValue ? true : false;
				break;

			case 'name':
				$this->arrAttributes['name'] = $varValue;
				break;

			case 'label':
				$this->slabel = $varValue;
				break;

			case 'required':
			case 'mandatory':
			case 'minlength':
			case 'maxlength':
				// Ignore
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
	 * Parse the template file and return it as string
	 *
	 * @param array $arrAttributes An optional attributes array
	 *
	 * @return string The template markup
	 */
	public function parse($arrAttributes=null)
	{
		if ($this->tableless)
		{
			$this->strClass = trim('submit_container ' . $this->strClass);
		}

		if ($this->imageSubmit && $this->singleSRC != '')
		{
			$objModel = \FilesModel::findByUuid($this->singleSRC);

			if ($objModel === null)
			{
				if (!\Validator::isUuid($this->singleSRC))
				{
					return '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
			}
			elseif (is_file(TL_ROOT . '/' . $objModel->path))
			{
				$this->src = $objModel->path;
			}
		}

		return parent::parse($arrAttributes);
	}


	/**
	 * Generate the widget and return it as string
	 *
	 * @return string The widget markup
	 */
	public function generate()
	{
		if ($this->src)
		{
			return sprintf('<input type="image" src="%s" id="ctrl_%s" class="submit%s" title="%s" alt="%s"%s%s',
							$this->src,
							$this->strId,
							(($this->strClass != '') ? ' ' . $this->strClass : ''),
							specialchars($this->slabel),
							specialchars($this->slabel),
							$this->getAttributes(),
							$this->strTagEnding);
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
