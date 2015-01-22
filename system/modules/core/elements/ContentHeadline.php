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
