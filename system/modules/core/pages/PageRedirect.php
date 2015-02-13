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
 * Provide methods to handle a redirect page.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class PageRedirect extends \Frontend
{

	/**
	 * Redirect to an external page
	 *
	 * @param \PageModel $objPage
	 */
	public function generate($objPage)
	{
		$this->redirect($this->replaceInsertTags($objPage->url, false), (($objPage->redirect == 'temporary') ? 302 : 301));
	}
}
