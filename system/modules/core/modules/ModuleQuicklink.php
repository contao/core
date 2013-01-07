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
 * Class ModuleQuicklink
 *
 * Front end module "quick link".
 * @copyright  Leo Feyer 2005-2013
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

			$objTemplate->wildcard = '### QUICK LINK ###';
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

		while ($objPages->next())
		{
			$arrPages[] = $objPages->row();
		}

		$items = array();

		foreach ($arrPages as $arrPage)
		{
			$arrPage['title'] = strip_insert_tags($arrPage['title']);
			$arrPage['pageTitle'] = strip_insert_tags($arrPage['pageTitle']);

			$items[] = array
			(
				'href' => $this->generateFrontendUrl($arrPage),
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
