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
 * Front end content element "HTML".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ContentHtml extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_html';


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		if (TL_MODE == 'FE')
		{
			$this->Template->html = $this->html;
		}
		else
		{
			$this->Template->html = '<pre>' . htmlspecialchars($this->html) . '</pre>';
		}
	}
}
