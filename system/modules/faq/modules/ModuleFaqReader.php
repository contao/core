<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Faq
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ModuleFaqReader
 *
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Faq
 */
class ModuleFaqReader extends \Module
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
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### FAQ READER ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Set the item from the auto_item parameter
		if ($GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			\Input::setGet('items', \Input::get('auto_item'));
		}

		// Do not index or cache the page if no FAQ has been specified
		if (!\Input::get('items'))
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;
			return '';
		}

		$this->faq_categories = deserialize($this->faq_categories);

		// Do not index or cache the page if there are no categories
		if (!is_array($this->faq_categories) || empty($this->faq_categories))
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		global $objPage;

		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
		$this->Template->referer = 'javascript:history.go(-1)';

		$objFaq = \FaqModel::findPublishedByParentAndIdOrAlias(\Input::get('items'), $this->faq_categories);

		if ($objFaq === null)
		{
			// Do not index or cache the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			// Send a 404 header
			header('HTTP/1.1 404 Not Found');
			$this->Template->error = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['invalidPage'], \Input::get('items')) . '</p>';
			return;
		}

		// Overwrite the page title and description (see #2853 and #4955)
		if ($objFaq->question != '')
		{
			$objPage->pageTitle = strip_tags(strip_insert_tags($objFaq->question));
			$objPage->description = $this->prepareMetaDescription($objFaq->question);
		}

		$this->Template->question = $objFaq->question;

		// Clean RTE output
		if ($objPage->outputFormat == 'xhtml')
		{
			$objFaq->answer = \String::toXhtml($objFaq->answer);
		}
		else
		{
			$objFaq->answer = \String::toHtml5($objFaq->answer);
		}

		$this->Template->answer = \String::encodeEmail($objFaq->answer);
		$this->Template->addImage = false;

		// Add image
		if ($objFaq->addImage && $objFaq->singleSRC != '')
		{
			if (!is_numeric($objFaq->singleSRC))
			{
				$this->Template->answer = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
			}
			else
			{
				$objModel = \FilesModel::findByPk($objFaq->singleSRC);

				if ($objModel !== null && is_file(TL_ROOT . '/' . $objModel->path))
				{
					$objFaq->singleSRC = $objModel->path;
					$this->addImageToTemplate($this->Template, $objFaq->row());
				}
			}
		}

		$this->Template->enclosure = array();

		// Add enclosure
		if ($objFaq->addEnclosure)
		{
			$this->addEnclosuresToTemplate($this->Template, $objFaq->row());
		}

		$this->Template->info = sprintf($GLOBALS['TL_LANG']['MSC']['faqCreatedBy'], $this->parseDate($objPage->dateFormat, $objFaq->tstamp), $objFaq->getRelated('author')->name);

		// HOOK: comments extension required
		if ($objFaq->noComments || !in_array('comments', $this->Config->getActiveModules()))
		{
			$this->Template->allowComments = false;
			return;
		}

		$objCategory = $objFaq->getRelated('pid');
		$this->Template->allowComments = $objCategory->allowComments;

		// Comments are not allowed
		if (!$objCategory->allowComments)
		{
			return;
		}

		// Adjust the comments headline level
		$intHl = min(intval(str_replace('h', '', $this->hl)), 5);
		$this->Template->hlc = 'h' . ($intHl + 1);

		$this->import('Comments');
		$arrNotifies = array();

		// Notify the system administrator
		if ($objCategory->notify != 'notify_author')
		{
			$arrNotifies[] = $GLOBALS['TL_ADMIN_EMAIL'];
		}

		// Notify the author
		if ($objCategory->notify != 'notify_admin')
		{
			if (($objAuthor = $objFaq->getRelated('author')) !== null && $objAuthor->email != '')
			{
				$arrNotifies[] = $objAuthor->email;
			}
		}

		$objConfig = new \stdClass();

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
