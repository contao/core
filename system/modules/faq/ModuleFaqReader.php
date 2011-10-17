<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Faq
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleFaqReader
 *
 * @copyright  Leo Feyer 2008-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ModuleFaqReader extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_faqreader';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### FAQ READER ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Return if no news item has been specified
		if (!$this->Input->get('items'))
		{
			global $objPage;

			// Do not index the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			return '';
		}

		$this->faq_categories = deserialize($this->faq_categories);

		// Return if there are no categories
		if (!is_array($this->faq_categories) || count($this->faq_categories) < 1)
		{
			global $objPage;

			// Do not index the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		global $objPage;

		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
		$this->Template->referer = 'javascript:history.go(-1)';

		$objFaq = $this->Database->prepare("SELECT *, author AS authorId, (SELECT title FROM tl_faq_category WHERE tl_faq_category.id=tl_faq.pid) AS category, (SELECT name FROM tl_user WHERE tl_user.id=tl_faq.author) AS author FROM tl_faq WHERE pid IN(" . implode(',', array_map('intval', $this->faq_categories)) . ") AND (id=? OR alias=?)" . (!BE_USER_LOGGED_IN ? " AND published=1" : ""))
								 ->limit(1)
								 ->execute((is_numeric($this->Input->get('items')) ? $this->Input->get('items') : 0), $this->Input->get('items'));

		if ($objFaq->numRows < 1)
		{
			$this->Template->error = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['invalidPage'], $this->Input->get('items')) . '</p>';

			// Do not index the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			// Send 404 header
			header('HTTP/1.1 404 Not Found');
			return;
		}

		// Overwrite the page title and description
		if ($objFaq->question != '')
		{
			$objPage->pageTitle = strip_insert_tags($objFaq->question);
			$objPage->description = $this->prepareMetaDescription($objFaq->question); 
		}

		$this->import('String');
		$this->Template->question = $objFaq->question;
		$this->Template->answer = $objFaq->answer;

		// Clean RTE output
		if ($objPage->outputFormat == 'xhtml')
		{
			$this->Template->answer = $this->String->toXhtml($this->Template->answer);
		}
		else
		{
			$this->Template->answer = $this->String->toHtml5($this->Template->answer);
		}

		$this->Template->answer = $this->String->encodeEmail($this->Template->answer);
		$this->Template->addImage = false;

		// Add image
		if ($objFaq->addImage && is_file(TL_ROOT . '/' . $objFaq->singleSRC))
		{
			$this->addImageToTemplate($this->Template, $objFaq->row());
		}

		$this->Template->enclosure = array();

		// Add enclosure
		if ($objFaq->addEnclosure)
		{
			$this->addEnclosuresToTemplate($this->Template, $objFaq->row());
		}

		$this->Template->info = sprintf($GLOBALS['TL_LANG']['MSC']['faqCreatedBy'], $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objFaq->tstamp), $objFaq->author);

		// HOOK: comments extension required
		if ($objFaq->noComments || !in_array('comments', $this->Config->getActiveModules()))
		{
			$this->Template->allowComments = false;
			return;
		}

		// Check whether comments are allowed
		$objCategory = $this->Database->prepare("SELECT * FROM tl_faq_category WHERE id=?")
									  ->limit(1)
									  ->execute($objFaq->pid);

		if ($objCategory->numRows < 1 || !$objCategory->allowComments)
		{
			$this->Template->allowComments = false;
			return;
		}

		$this->Template->allowComments = true;

		// Adjust the comments headline level
		$intHl = min(intval(str_replace('h', '', $this->hl)), 5);
		$this->Template->hlc = 'h' . ($intHl + 1);

		$this->import('Comments');
		$arrNotifies = array();

		// Notify system administrator
		if ($objCategory->notify != 'notify_author')
		{
			$arrNotifies[] = $GLOBALS['TL_ADMIN_EMAIL'];
		}

		// Notify author
		if ($objCategory->notify != 'notify_admin')
		{
			$objAuthor = $this->Database->prepare("SELECT email FROM tl_user WHERE id=?")
										->limit(1)
										->execute($objFaq->authorId);

			if ($objAuthor->numRows)
			{
				$arrNotifies[] = $objAuthor->email;
			}
		}

		$objConfig = new stdClass();

		$objConfig->perPage = $objCategory->perPage;
		$objConfig->order = $objCategory->sortOrder;
		$objConfig->template = $this->com_template;
		$objConfig->requireLogin = $objCategory->requireLogin;
		$objConfig->disableCaptcha = $objCategory->disableCaptcha;
		$objConfig->bbcode = $objCategory->bbcode;
		$objConfig->moderate = $objCategory->moderate;

		$this->Comments->addCommentsToTemplate($this->Template, $objConfig, 'tl_faq', $objFaq->id, $arrNotifies);
	}
}

?>