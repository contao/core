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
 * Front end module "HTML".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleHtml extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_html';


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$this->Template->html = (TL_MODE == 'FE') ? $this->html : htmlspecialchars($this->html);
	}
}
