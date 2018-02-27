<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
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
			$this->text = \StringUtil::toXhtml($this->text);
		}
		else
		{
			$this->text = \StringUtil::toHtml5($this->text);
		}

		return $this->text;
	}
}
