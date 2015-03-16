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
 * Class FormHeadline
 *
 * @property string $text
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FormHeadline extends \Widget
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_headline';

	/**
	 * The CSS class prefix
	 *
	 * @var string
	 */
	protected $strPrefix = 'widget widget-headline';


	/**
	 * Do not validate
	 */
	public function validate()
	{
		return;
	}


	/**
	 * Generate the widget and return it as string
	 *
	 * @return string The widget markup
	 */
	public function generate()
	{
		/** @var \PageModel $objPage */
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

		return $this->text;
	}
}
