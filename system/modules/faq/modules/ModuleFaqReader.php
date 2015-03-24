<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Class ModuleFaqReader
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			/** @var \BackendTemplate|object $objTemplate */
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['faqreader'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Set the item from the auto_item parameter
		if (!isset($_GET['items']) && \Config::get('useAutoItem') && isset($_GET['auto_item']))
		{
			\Input::setGet('items', \Input::get('auto_item'));
		}

		// Do not index or cache the page if no FAQ has been specified
		if (!\Input::get('items'))
		{
			/** @var \PageModel $objPage */
			global $objPage;

			$objPage->noSearch = 1;
			$objPage->cache = 0;

			return '';
		}

		$this->faq_categories = deserialize($this->faq_categories);

		// Do not index or cache the page if there are no categories
		if (!is_array($this->faq_categories) || empty($this->faq_categories))
		{
			/** @var \PageModel $objPage */
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
		/** @var \PageModel $objPage */
		global $objPage;

		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
		$this->Template->referer = 'javascript:history.go(-1)';

		$objFaq = \FaqModel::findPublishedByParentAndIdOrAlias(\Input::get('items'), $this->faq_categories);

		if (null === $objFaq)
		{
			/** @var \PageError404 $objHandler */
			$objHandler = new $GLOBALS['TL_PTY']['error_404']();
			$objHandler->generate($objPage->id);
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
			$objModel = \FilesModel::findByUuid($objFaq->singleSRC);

			if ($objModel === null)
			{
				if (!\Validator::isUuid($objFaq->singleSRC))
				{
					$this->Template->answer = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
			}
			elseif (is_file(TL_ROOT . '/' . $objModel->path))
			{
				// Do not override the field now that we have a model registry (see #6303)
				$arrFaq = $objFaq->row();
				$arrFaq['singleSRC'] = $objModel->path;

				$this->addImageToTemplate($this->Template, $arrFaq);
			}
		}

		$this->Template->enclosure = array();

		// Add enclosure
		if ($objFaq->addEnclosure)
		{
			$this->addEnclosuresToTemplate($this->Template, $objFaq->row());
		}

		$strAuthor = '';

		// Add the author
		if (($objAuthor = $objFaq->getRelated('author')) !== null)
		{
			$strAuthor = $objAuthor->name;
		}

		$this->Template->info = sprintf($GLOBALS['TL_LANG']['MSC']['faqCreatedBy'], \Date::parse($objPage->dateFormat, $objFaq->tstamp), $strAuthor);

		// HOOK: comments extension required
		if ($objFaq->noComments || !in_array('comments', \ModuleLoader::getActive()))
		{
			$this->Template->allowComments = false;

			return;
		}

		/** @var \FaqCategoryModel $objCategory */
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
			/** @var \UserModel $objAuthor */
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
