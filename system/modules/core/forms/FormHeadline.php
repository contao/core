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
 * Class FormHeadline
 *
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class FormHeadline extends \Widget
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_headline';


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

		return parent::parse($arrAttributes);
	}


	/**
	 * Generate the widget and return it as string
	 *
	 * @return string The widget markup
	 *
	 * @deprecated The logic has been moved into the template (see #6834)
	 */
	public function generate()
	{
		return $this->text;
	}
}
