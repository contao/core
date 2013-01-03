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
 * Class PageRedirect
 *
 * Provide methods to handle a redirect page.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class PageRedirect extends \Frontend
{

	/**
	 * Redirect to an external page
	 * @param object
	 */
	public function generate($objPage)
	{
		$this->redirect($this->replaceInsertTags($objPage->url), (($objPage->redirect == 'temporary') ? 302 : 301));
	}
}
