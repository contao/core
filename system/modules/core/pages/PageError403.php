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
 * Class PageError403
 *
 * Provide methods to handle an error 403 page.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class PageError403 extends \Frontend
{

	/**
	 * Generate an error 403 page
	 * @param integer
	 * @param object
	 */
	public function generate($pageId, $objRootPage=null)
	{
		// Add a log entry
		$this->log('Access to page ID "' . $pageId . '" denied', 'PageError403 generate()', TL_ERROR);

		// Use the given root page object if available (thanks to Andreas Schempp)
		if ($objRootPage === null)
		{
			$objRootPage = $this->getRootPageFromUrl();
		}
		else
		{
			$objRootPage = \PageModel::findPublishedById(is_integer($objRootPage) ? $objRootPage : $objRootPage->id);
		}

		// Look for an error_403 page
		$obj403 = \PageModel::find403ByPid($objRootPage->id);

		// Die if there is no page at all
		if ($obj403 === null)
		{
			header('HTTP/1.1 403 Forbidden');
			die('Forbidden');
		}

		// Generate the error page
		if (!$obj403->autoforward || !$obj403->jumpTo)
		{
			global $objPage;

			$objPage = $this->getPageDetails($obj403);
			$objHandler = new $GLOBALS['TL_PTY']['regular']();

			header('HTTP/1.1 403 Forbidden');
			$objHandler->generate($objPage);

			exit;
		}

		// Forward to another page
		$objNextPage = \PageModel::findPublishedById($obj403->jumpTo);

		if ($objNextPage === null)
		{
			header('HTTP/1.1 403 Forbidden');
			$this->log('Forward page ID "' . $obj403->jumpTo . '" does not exist', 'PageError403 generate()', TL_ERROR);
			die('Forward page not found');
		}

		$this->redirect($this->generateFrontendUrl($objNextPage->row(), null, $objRootPage->language), (($obj403->redirect == 'temporary') ? 302 : 301));
	}
}
