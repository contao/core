<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
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
 * Class PageForward
 *
 * Provide methods to handle a forward page.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
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
			$objNextPage = $objPage->getRelated('jumpTo');
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
			die_nicely('be_no_forward', 'Forward page not found');
		}

		$strForceLang = null;

		// Check the target page language (see #4706)
		if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'])
		{
			$objNextPage->loadDetails(); // see #3983
			$strForceLang = $objNextPage->language;
		}

		$strGet = '';
		$strQuery = \Environment::get('queryString');
		$arrQuery = array();

		// Extract the query string keys (see #5867)
		if ($strQuery != '')
		{
			$arrChunks = explode('&', $strQuery);

			foreach ($arrChunks as $strChunk)
			{
				list($k,) = explode('=', $strChunk, 2);
				$arrQuery[] = $k;
			}
		}

		// Add $_GET parameters
		if (!empty($_GET))
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

				// Ignore the query string parameters (see #5867)
				if (in_array($key, $arrQuery))
				{
					continue;
				}

				// Ignore the auto_item parameter (see #5886)
				if ($key == 'auto_item')
				{
					$strGet .= '/' . \Input::get($key);
				}
				else
				{
					$strGet .= '/' . $key . '/' . \Input::get($key);
				}
			}
		}

		// Append the query string (see #5867)
		if ($strQuery != '')
		{
			$strQuery = '?' . $strQuery;
		}

		$this->redirect($this->generateFrontendUrl($objNextPage->row(), $strGet, $strForceLang) . $strQuery, (($objPage->redirect == 'temporary') ? 302 : 301));
	}
}
