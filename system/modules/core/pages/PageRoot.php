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
 * Provide methods to handle a website root page.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class PageRoot extends \Frontend
{

	/**
	 * Redirect to the first active regular page
	 *
	 * @param integer $pageId
	 * @param boolean $blnReturn
	 *
	 * @return integer
	 */
	public function generate($pageId, $blnReturn=false)
	{
		$objNextPage = \PageModel::findFirstPublishedByPid($pageId);

		// No published pages yet
		if ($objNextPage === null)
		{
			header('HTTP/1.1 404 Not Found');
			$this->log('No active page found under root page "' . $pageId . '")', __METHOD__, TL_ERROR);
			die_nicely('be_no_active', 'No active pages found');
		}

		/** @var \PageModel $objNextPage */
		if (!$blnReturn)
		{
			global $objPage;
			$this->redirect($this->generateFrontendUrl($objNextPage->row(), null, $objPage->language));
		}

		return $objNextPage->id;
	}
}
