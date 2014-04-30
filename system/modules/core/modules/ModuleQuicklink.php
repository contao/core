<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
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
 * Class ModuleQuicklink
 *
 * Front end module "quick link".
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ModuleQuicklink extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_quicklink';


	/**
	 * Redirect to the selected page
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['quicklink'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Redirect to selected page
		if (\Input::post('FORM_SUBMIT') == 'tl_quicklink')
		{
			$this->redirect(\Input::post('target', true));
		}

		// Always return an array (see #4616)
		$this->pages = deserialize($this->pages, true);

		if (empty($this->pages) || $this->pages[0] == '')
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		// Get all active pages
		$objPages = \PageModel::findPublishedRegularWithoutGuestsByIds($this->pages);

		// Return if there are no pages
		if ($objPages === null)
		{
			return;
		}

		$arrPages = array();

		// Sort the array keys according to the given order
		if ($this->orderPages != '')
		{
			$tmp = deserialize($this->orderPages);

			if (!empty($tmp) && is_array($tmp))
			{
				$arrPages = array_map(function(){}, array_flip($tmp));
			}
		}

		// Add the items to the pre-sorted array
		while ($objPages->next())
		{
			$arrPages[$objPages->id] = $objPages->current()->loadDetails()->row(); // see #3765
		}

		$items = array();

		foreach ($arrPages as $arrPage)
		{
			$arrPage['title'] = strip_insert_tags($arrPage['title']);
			$arrPage['pageTitle'] = strip_insert_tags($arrPage['pageTitle']);

			// Get href
			switch ($arrPage['type'])
			{
				case 'redirect':
					$href = $arrPage['url'];
					break;

				case 'forward':
					if (($objNext = \PageModel::findPublishedById($arrPage['jumpTo'])) !== null)
					{
						$strForceLang = null;
						$objNext->loadDetails();

						// Check the target page language (see #4706)
						if (\Config::get('addLanguageToUrl'))
						{
							$strForceLang = $objNext->language;
						}

						$href = $this->generateFrontendUrl($objNext->row(), null, $strForceLang);
						break;
					}
					// DO NOT ADD A break; STATEMENT

				default:
					$href = $this->generateFrontendUrl($arrPage, null, $arrPage['rootLanguage']);

					// Add the domain if it differs from the current one (see #3765)
					if ($arrPage['domain'] != '' && $arrPage['domain'] != \Environment::get('host'))
					{
						$href = (\Environment::get('ssl') ? 'https://' : 'http://') . $arrPage['domain'] . TL_PATH . '/' . $href;
					}
					break;
			}

			$items[] = array
			(
				'href' => $href,
				'title' => specialchars($arrPage['pageTitle'] ?: $arrPage['title']),
				'link' => $arrPage['title']
			);
		}

		$this->Template->items = $items;
		$this->Template->request = ampersand(\Environment::get('request'), true);
		$this->Template->title = $this->customLabel ?: $GLOBALS['TL_LANG']['MSC']['quicklink'];
		$this->Template->button = specialchars($GLOBALS['TL_LANG']['MSC']['go']);
	}
}
