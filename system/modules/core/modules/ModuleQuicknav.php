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
 * Front end module "quick navigation".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleQuicknav extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_quicknav';


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

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['quicknav'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		if (\Input::post('FORM_SUBMIT') == 'tl_quicknav')
		{
			$this->redirect(\Input::post('target', true));
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		/** @var \PageModel $objPage */
		global $objPage;

		$lang = null;
		$host = null;

		// Start from the website root if there is no reference page
		if (!$this->rootPage)
		{
			$this->rootPage = $objPage->rootId;
		}

		// Overwrite the domain and language if the reference page belongs to a differnt root page (see #3765)
		else
		{
			$objRootPage = \PageModel::findWithDetails($this->rootPage);

			// Set the language
			if (\Config::get('addLanguageToUrl') && $objRootPage->rootLanguage != $objPage->rootLanguage)
			{
				$lang = $objRootPage->rootLanguage;
			}

			// Set the domain
			if ($objRootPage->rootId != $objPage->rootId && $objRootPage->domain != '' && $objRootPage->domain != $objPage->domain)
			{
				$host = $objRootPage->domain;
			}
		}

		$this->Template->targetPage = $GLOBALS['TL_LANG']['MSC']['targetPage'];
		$this->Template->button = specialchars($GLOBALS['TL_LANG']['MSC']['go']);
		$this->Template->title = $this->customLabel ?: $GLOBALS['TL_LANG']['MSC']['quicknav'];
		$this->Template->request = ampersand(\Environment::get('request'), true);
		$this->Template->items = $this->getQuicknavPages($this->rootPage, 1, $host, $lang);
	}


	/**
	 * Recursively get all quicknav pages and return them as array
	 *
	 * @param integer $pid
	 * @param integer $level
	 * @param string  $host
	 * @param string  $language
	 *
	 * @return array
	 */
	protected function getQuicknavPages($pid, $level=1, $host=null, $language=null)
	{
		/** @var \PageModel $objPage */
		global $objPage;

		$groups = array();
		$arrPages = array();

		// Get all groups of the current front end user
		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
			$groups = $this->User->groups;
		}

		// Get all active subpages
		$objSubpages = \PageModel::findPublishedRegularWithoutGuestsByPid($pid);

		if ($objSubpages === null)
		{
			return array();
		}

		++$level;

		foreach ($objSubpages as $objSubpage)
		{
			$_groups = deserialize($objSubpage->groups);

			// Override the domain (see #3765)
			if ($host !== null)
			{
				$objSubpage->domain = $host;
			}

			// Do not show protected pages unless a back end or front end user is logged in
			if (!$objSubpage->protected || (!is_array($_groups) && FE_USER_LOGGED_IN) || BE_USER_LOGGED_IN || (is_array($_groups) && array_intersect($_groups, $groups)) || $this->showProtected)
			{
				// Do not skip the current page here! (see #4523)

				// Check hidden pages
				if (!$objSubpage->hide || $this->showHidden)
				{
					$arrPages[] = array
					(
						'level' => ($level - 2),
						'title' => specialchars(strip_insert_tags($objSubpage->pageTitle ?: $objSubpage->title)),
						'href' => $objSubpage->getFrontendUrl(),
						'link' => strip_insert_tags($objSubpage->title)
					);

					// Subpages
					if (!$this->showLevel || $this->showLevel >= $level || (!$this->hardLimit && ($objPage->id == $objSubpage->id || in_array($objPage->id, $this->Database->getChildRecords($objSubpage->id, 'tl_page')))))
					{
						$subpages = $this->getQuicknavPages($objSubpage->id, $level);

						if (is_array($subpages))
						{
							$arrPages = array_merge($arrPages, $subpages);
						}
					}
				}
			}
		}

		return $arrPages;
	}
}
