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
 * Class ContentArticle
 *
 * Front end content element "article alias".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ContentArticle extends \ContentElement
{

	/**
	 * Parse the template
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
