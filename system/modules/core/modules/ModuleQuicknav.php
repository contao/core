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
 * Class ModuleQuicknav
 *
 * Front end module "quick navigation".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
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
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
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
		$lang = null;
		$host = null;

		// Start from the website root if there is no reference page
		if (!$this->rootPage)
		{
			global $objPage;
			$this->rootPage = $objPage->rootId;
		}

		// Overwrite the domain and language if the reference page belongs to a differnt root page (see #3765)
		else
		{
			$objRootPage = \PageModel::findWithDetails($this->rootPage);

			// Set the language
			if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'] && $objRootPage->rootLanguage != $objPage->rootLanguage)
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
	 * @param integer
	 * @param integer
	 * @param sting
	 * @param sting
	 * @return array
	 */
	protected function getQuicknavPages($pid, $level=1, $host=null, $language=null)
	{
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

		while($objSubpages->next())
		{
			$_groups = deserialize($objSubpages->groups);

			// Override the domain (see #3765)
			if ($host !== null)
			{
				$objSubpages->domain = $host;
			}

			// Do not show protected pages unless a back end or front end user is logged in
			if (!$objSubpages->protected || (!is_array($_groups) && FE_USER_LOGGED_IN) || BE_USER_LOGGED_IN || (is_array($_groups) && array_intersect($_groups, $groups)) || $this->showProtected)
			{
				// Do not skip the current page here! (see #4523)

				// Check hidden pages
				if (!$objSubpages->hide || $this->showHidden)
				{
					$objSubpages->title = strip_insert_tags($objSubpages->title);
					$objSubpages->pageTitle = strip_insert_tags($objSubpages->pageTitle);

					$arrPages[] = array
					(
						'level' => ($level - 2),
						'title' => specialchars($objSubpages->pageTitle ?: $objSubpages->title),
						'href' => $this->generateFrontendUrl($objSubpages->row(), null, $language),
						'link' => $objSubpages->title
					);

					// Subpages
					if (!$this->showLevel || $this->showLevel >= $level || (!$this->hardLimit && ($objPage->id == $objSubpages->id || in_array($objPage->id, $this->Database->getChildRecords($objSubpages->id, 'tl_page')))))
					{
						$subpages = $this->getQuicknavPages($objSubpages->id, $level);

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
