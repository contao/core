<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Front end module "quick link".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			/** @var \BackendTemplate|object $objTemplate */
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
				$arrPages = array_map(function () {}, array_flip($tmp));
			}
		}

		// Add the items to the pre-sorted array
		while ($objPages->next())
		{
			$arrPages[$objPages->id] = $objPages->current();
		}

		$items = array();
		$arrPages = array_values(array_filter($arrPages));

		/** @var \PageModel[] $arrPages */
		foreach ($arrPages as $objPage)
		{
			$objPage->title = strip_insert_tags($objPage->title);
			$objPage->pageTitle = strip_insert_tags($objPage->pageTitle);

			// Get href
			switch ($objPage->type)
			{
				case 'redirect':
					$href = $objPage->url;
					break;

				case 'forward':
					if (($objNext = $objPage->getRelated('jumpTo')) !== null || ($objNext = \PageModel::findFirstPublishedRegularByPid($objPage->id)) !== null)
					{
						/** @var \PageModel $objNext */
						$href = $objNext->getFrontendUrl();
						break;
					}
					// DO NOT ADD A break; STATEMENT

				default:
					$href = $objPage->getFrontendUrl();
					break;
			}

			$items[] = array
			(
				'href' => $href,
				'title' => specialchars($objPage->pageTitle ?: $objPage->title),
				'link' => $objPage->title
			);
		}

		$this->Template->items = $items;
		$this->Template->request = ampersand(\Environment::get('request'), true);
		$this->Template->title = $this->customLabel ?: $GLOBALS['TL_LANG']['MSC']['quicklink'];
		$this->Template->button = specialchars($GLOBALS['TL_LANG']['MSC']['go']);
	}
}
