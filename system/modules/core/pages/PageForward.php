<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Core
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class PageForward
 *
 * Provide methods to handle a forward page.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Core
 */
class PageForward extends \Frontend
{

	/**
	 * Redirect to an internal page
	 * @param object
	 */
	public function generate($objPage)
	{
		// Forward to the jumpTo or first published page
		if ($objPage->jumpTo)
		{
			$objNextPage = \PageModel::findPublishedById($objPage->jumpTo);
		}
		else
		{
			$objNextPage = \PageModel::findFirstPublishedRegularByPid($objPage->id);
		}

		// Forward page does not exist
		if ($objNextPage === null)
		{
			header('HTTP/1.1 404 Not Found');
			$this->log('Forward page ID "' . $objPage->jumpTo . '" does not exist', 'PageForward generate()', TL_ERROR);
			die('Forward page not found');
		}

		$strGet = '';

		// Add $_GET parameters
		if (is_array($_GET) && !empty($_GET))
		{
			foreach (array_keys($_GET) as $key)
			{
				if ($GLOBALS['TL_CONFIG']['disableAlias'] && $key == 'id')
				{
					continue;
				}

				if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'] && $key == 'language')
				{
					continue;
				}

				$strGet .= '/' . $key . '/' . \Input::get($key);
			}
		}

		$this->redirect($this->generateFrontendUrl($objNextPage->row(), $strGet), (($objPage->redirect == 'temporary') ? 302 : 301));
	}
}
