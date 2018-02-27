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
 * Front end content element "article alias".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ContentArticle extends \ContentElement
{

	/**
	 * Parse the template
	 *
	 * @return string
	 */
	public function generate()
	{
		return $this->getArticle($this->articleAlias, false, true);
	}


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		return;
	}
}
