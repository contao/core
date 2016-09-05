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
 * Front end module "custom navigation".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			/** @var \BackendTemplate|object $objTemplate */
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
		/** @var \PageModel $objPage */
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

		$arrPages = array_values(array_filter($arrPages));

		// Set default template
		if ($this->navigationTpl == '')
		{
			$this->navigationTpl = 'nav_default';
		}

		/** @var \FrontendTemplate|object $objTemplate */
		$objTemplate = new \FrontendTemplate($this->navigationTpl);

		$objTemplate->type = get_class($this);
		$objTemplate->cssID = $this->cssID; // see #4897 and 6129
		$objTemplate->level = 'level_1';

		/** @var \PageModel[] $arrPages */
		foreach ($arrPages as $objModel)
		{
			$_groups = deserialize($objModel->groups);

			// Do not show protected pages unless a back end or front end user is logged in
			if (!$objModel->protected || BE_USER_LOGGED_IN || (is_array($_groups) && count(array_intersect($_groups, $groups))) || $this->showProtected)
			{
				// Get href
				switch ($objModel->type)
				{
					case 'redirect':
						$href = $objModel->url;
						break;

					case 'forward':
						if (($objNext = $objModel->getRelated('jumpTo')) !== null || ($objNext = \PageModel::findFirstPublishedRegularByPid($objModel->id)) !== null)
						{
							/** @var \PageModel $objNext */
							$href = $objNext->getFrontendUrl();
							break;
						}
						// DO NOT ADD A break; STATEMENT

					default:
						$href = $objModel->getFrontendUrl();
						break;
				}

				$trail = in_array($objModel->id, $objPage->trail);

				// Active page
				if ($objPage->id == $objModel->id && $href == \Environment::get('request'))
				{
					$strClass = trim($objModel->cssClass);
					$row = $objModel->row();

					$row['isActive'] = true;
					$row['isTrail'] = false;
					$row['class'] = trim('active ' . $strClass);
					$row['title'] = specialchars($objModel->title, true);
					$row['pageTitle'] = specialchars($objModel->pageTitle, true);
					$row['link'] = $objModel->title;
					$row['href'] = $href;
					$row['nofollow'] = (strncmp($objModel->robots, 'noindex,nofollow', 16) === 0);
					$row['target'] = '';
					$row['description'] = str_replace(array("\n", "\r"), array(' ' , ''), $objModel->description);

					// Override the link target
					if ($objModel->type == 'redirect' && $objModel->target)
					{
						$row['target'] = ($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"';
					}

					$items[] = $row;
				}

				// Regular page
				else
				{
					$strClass = trim($objModel->cssClass . ($trail ? ' trail' : ''));
					$row = $objModel->row();

					$row['isActive'] = false;
					$row['isTrail'] = $trail;
					$row['class'] = $strClass;
					$row['title'] = specialchars($objModel->title, true);
					$row['pageTitle'] = specialchars($objModel->pageTitle, true);
					$row['link'] = $objModel->title;
					$row['href'] = $href;
					$row['nofollow'] = (strncmp($objModel->robots, 'noindex,nofollow', 16) === 0);
					$row['target'] = '';
					$row['description'] = str_replace(array("\n", "\r"), array(' ' , ''), $objModel->description);

					// Override the link target
					if ($objModel->type == 'redirect' && $objModel->target)
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
