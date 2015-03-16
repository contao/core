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
 * Provide methods to handle an error 403 page.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class PageError403 extends \Frontend
{

	/**
	 * Generate an error 403 page
	 *
	 * @param integer    $pageId
	 * @param \PageModel $objRootPage
	 */
	public function generate($pageId, $objRootPage=null)
	{
		// Add a log entry
		$this->log('Access to page ID "' . $pageId . '" denied', __METHOD__, TL_ERROR);

		// Use the given root page object if available (thanks to Andreas Schempp)
		if ($objRootPage === null)
		{
			$objRootPage = $this->getRootPageFromUrl();
		}
		else
		{
			$objRootPage = \PageModel::findPublishedById(is_integer($objRootPage) ? $objRootPage : $objRootPage->id);
		}

		// Look for a 403 page
		$obj403 = \PageModel::find403ByPid($objRootPage->id);

		// Die if there is no page at all
		if (null === $obj403)
		{
			header('HTTP/1.1 403 Forbidden');
			die_nicely('be_forbidden', 'Forbidden');
		}

		// Generate the error page
		if (!$obj403->autoforward || !$obj403->jumpTo)
		{
			/** @var \PageModel $objPage */
			global $objPage;

			$objPage = $obj403->loadDetails();

			/** @var \PageRegular $objHandler */
			$objHandler = new $GLOBALS['TL_PTY']['regular']();

			header('HTTP/1.1 403 Forbidden');
			$objHandler->generate($objPage);

			exit;
		}

		// Forward to another page
		$objNextPage = \PageModel::findPublishedById($obj403->jumpTo);

		if (null === $objNextPage)
		{
			header('HTTP/1.1 403 Forbidden');
			$this->log('Forward page ID "' . $obj403->jumpTo . '" does not exist', __METHOD__, TL_ERROR);
			die_nicely('be_no_forward', 'Forward page not found');
		}

		$this->redirect($this->generateFrontendUrl($objNextPage->row(), null, $objRootPage->language), (($obj403->redirect == 'temporary') ? 302 : 301));
	}
}
