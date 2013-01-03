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
 * Class PageRoot
 *
 * Provide methods to handle a website root page.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class PageRoot extends \Frontend
{

	/**
	 * Redirect to the first active regular page
	 * @param integer
	 * @param boolean
	 * @return integer
	 * @return integer
	 */
	public function generate($pageId, $blnReturn=false)
	{
		$objNextPage = \PageModel::findFirstPublishedByPid($pageId);

		// No published pages yet
		if ($objNextPage === null)
		{
			header('HTTP/1.1 404 Not Found');
			$this->log('No active page found under root page "' . $pageId . '")', 'PageRoot generate()', TL_ERROR);
			die('No active pages found');
		}

		if (!$blnReturn)
		{
			$this->redirect($this->generateFrontendUrl($objNextPage->row()));
		}

		return $objNextPage->id;
	}
}
