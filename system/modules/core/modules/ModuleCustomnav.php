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
 * Class ModuleCustomnav
 *
 * Front end module "custom navigation".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ModuleCustomnav extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_navigation';


	/**
	 * Redirect to the selected page
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['customnav'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Always return an array (see #4616)
		$this->pages = deserialize($this->pages, true);

		if (empty($this->pages) || $this->pages[0] == '')
		{
			return '';
		}

		$strBuffer = parent::generate();
		return ($this->Template->items != '') ? $strBuffer : '';
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		global $objPage;

		$items = array();
		$groups = array();

		// Get all groups of the current front end user
		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
			$groups = $this->User->groups;
		}

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
			$arrPages = array_flip(trimsplit(',', $this->orderPages));
		}

		$i = 0;

		// Add the items to the pre-sorted array
		while ($objPages->next())
		{
			$arrPages[$i++] = $objPages->current()->loadDetails()->row(); // see #3765
		}

		// Set default template
		if ($this->navigationTpl == '')
		{
			$this->navigationTpl = 'nav_default';
		}

		$objTemplate = new \FrontendTemplate($this->navigationTpl);

		$objTemplate->type = get_class($this);
		$objTemplate->level = 'level_1';

		foreach ($arrPages as $arrPage)
		{
			$_groups = deserialize($arrPage['groups']);

			// Do not show protected pages unless a back end or front end user is logged in
			if (!$arrPage['protected'] || BE_USER_LOGGED_IN || (is_array($_groups) && count(array_intersect($_groups, $groups))) || $this->showProtected)
			{
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

							// Check the target page language (see #4706)
							if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'])
							{
								$objNext->loadDetails(); // see #3983
								$strForceLang = $objNext->language;
							}

							$href = $this->generateFrontendUrl($objNext->row(), null, $strForceLang);
							break;
						}
						// DO NOT ADD A break; STATEMENT

					default:
						$href = $this->generateFrontendUrl($arrPage, null, $arrPage['rootLanguage']);
						break;
				}

				// Active page
				if ($objPage->id == $arrPage['id'])
				{
					$strClass = trim($arrPage['cssClass']);
					$row = $arrPage;

					$row['isActive'] = true;
					$row['class'] = trim('active ' . $strClass);
					$row['title'] = specialchars($arrPage['title'], true);
					$row['pageTitle'] = specialchars($arrPage['pageTitle'], true);
					$row['link'] = $arrPage['title'];
					$row['href'] = $href;
					$row['nofollow'] = (strncmp($arrPage['robots'], 'noindex', 7) === 0);
					$row['target'] = '';
					$row['description'] = str_replace(array("\n", "\r"), array(' ' , ''), $arrPage['description']);

					// Override the link target
					if ($arrPage['type'] == 'redirect' && $arrPage['target'])
					{
						$row['target'] = ($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"';
					}

					$items[] = $row;
				}

				// Regular page
				else
				{
					$strClass = trim($arrPage['cssClass'] . (in_array($arrPage['id'], $objPage->trail) ? ' trail' : ''));
					$row = $arrPage;

					$row['isActive'] = false;
					$row['class'] = $strClass;
					$row['title'] = specialchars($arrPage['title'], true);
					$row['pageTitle'] = specialchars($arrPage['pageTitle'], true);
					$row['link'] = $arrPage['title'];
					$row['href'] = $href;
					$row['nofollow'] = (strncmp($arrPage['robots'], 'noindex', 7) === 0);
					$row['target'] = '';
					$row['description'] = str_replace(array("\n", "\r"), array(' ' , ''), $arrPage['description']);

					// Override the link target
					if ($arrPage['type'] == 'redirect' && $arrPage['target'])
					{
						$row['target'] = ($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"';
					}

					$items[] = $row;
				}
			}
		}

		// Add classes first and last
		$items[0]['class'] = trim($items[0]['class'] . ' first');
		$last = count($items) - 1;
		$items[$last]['class'] = trim($items[$last]['class'] . ' last');

		$objTemplate->items = $items;

		$this->Template->request = \Environment::get('indexFreeRequest');
		$this->Template->skipId = 'skipNavigation' . $this->id;
		$this->Template->skipNavigation = specialchars($GLOBALS['TL_LANG']['MSC']['skipNavigation']);
		$this->Template->items = !empty($items) ? $objTemplate->parse() : '';
	}
}
