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
 * Class ModuleFaqList
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleFaqList extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_faqlist';

	/**
	 * Target pages
	 * @var array
	 */
	protected $arrTargets = array();


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

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['faqlist'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->faq_categories = deserialize($this->faq_categories);

		// Return if there are no categories
		if (!is_array($this->faq_categories) || empty($this->faq_categories))
		{
			return '';
		}

		// Show the FAQ reader if an item has been selected
		if ($this->faq_readerModule > 0 && (isset($_GET['items']) || (\Config::get('useAutoItem') && isset($_GET['auto_item']))))
		{
			return $this->getFrontendModule($this->faq_readerModule, $this->strColumn);
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$objFaq = \FaqModel::findPublishedByPids($this->faq_categories);

		if ($objFaq === null)
		{
			$this->Template->faq = array();

			return;
		}

		$arrFaq = array_fill_keys($this->faq_categories, array());

		// Add FAQs
		while ($objFaq->next())
		{
			$arrTemp = $objFaq->row();
			$arrTemp['title'] = specialchars($objFaq->question, true);
			$arrTemp['href'] = $this->generateFaqLink($objFaq);

			/** @var \FaqCategoryModel $objPid */
			$objPid = $objFaq->getRelated('pid');

			$arrFaq[$objFaq->pid]['items'][] = $arrTemp;
			$arrFaq[$objFaq->pid]['headline'] = $objPid->headline;
			$arrFaq[$objFaq->pid]['title'] = $objPid->title;
		}

		$arrFaq = array_values(array_filter($arrFaq));

		$cat_count = 0;
		$cat_limit = count($arrFaq);

		// Add classes
		foreach ($arrFaq as $k=>$v)
		{
			$count = 0;
			$limit = count($v['items']);

			for ($i=0; $i<$limit; $i++)
			{
				$arrFaq[$k]['items'][$i]['class'] = trim(((++$count == 1) ? ' first' : '') . (($count >= $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even'));
			}

			$arrFaq[$k]['class'] = trim(((++$cat_count == 1) ? ' first' : '') . (($cat_count >= $cat_limit) ? ' last' : '') . ((($cat_count % 2) == 0) ? ' odd' : ' even'));
		}

		$this->Template->faq = $arrFaq;
	}


	/**
	 * Create links and remember pages that have been processed
	 *
	 * @param \FaqModel $objFaq
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	protected function generateFaqLink($objFaq)
	{
		/** @var \FaqCategoryModel $objCategory */
		$objCategory = $objFaq->getRelated('pid');
		$jumpTo = intval($objCategory->jumpTo);

		// A jumpTo page is not mandatory for FAQ categories (see #6226) but required for the FAQ list module
		if ($jumpTo < 1)
		{
			throw new \Exception("FAQ categories without redirect page cannot be used in an FAQ list");
		}

		// Get the URL from the jumpTo page of the category
		if (!isset($this->arrTargets[$jumpTo]))
		{
			$this->arrTargets[$jumpTo] = ampersand(\Environment::get('request'), true);

			if ($jumpTo > 0)
			{
				$objTarget = \PageModel::findByPk($jumpTo);

				if ($objTarget !== null)
				{
					$this->arrTargets[$jumpTo] = ampersand($this->generateFrontendUrl($objTarget->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/%s' : '/items/%s')));
				}
			}
		}

		return sprintf($this->arrTargets[$jumpTo], ((!\Config::get('disableAlias') && $objFaq->alias != '') ? $objFaq->alias : $objFaq->id));
	}
}
