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
 * Front end content element "headline".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ContentHeadline extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_headline';


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		return;
	}
}
