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
 * Class ModuleFaqList
 *
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Faq
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
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### FAQ LIST ###';
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
		if ($this->faq_readerModule > 0 && (isset($_GET['items']) || ($GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))))
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

			$arrFaq[$objFaq->pid]['items'][] = $arrTemp;
			$arrFaq[$objFaq->pid]['headline'] = $objFaq->getRelated('pid')->headline;
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
	 * @param object
	 * @return string
	 */
	protected function generateFaqLink($objFaq)
	{
		$jumpTo = intval($objFaq->getRelated('pid')->jumpTo);

		// Get the URL from the jumpTo page of the category
		if (!isset($this->arrTargets[$jumpTo]))
		{
			$this->arrTargets[$jumpTo] = ampersand(\Environment::get('request'), true);

			if ($jumpTo > 0)
			{
				$objTarget = \PageModel::findByPk($jumpTo);

				if ($objTarget !== null)
				{
					$this->arrTargets[$jumpTo] = ampersand($this->generateFrontendUrl($objTarget->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/items/%s')));
				}
			}
		}

		return sprintf($this->arrTargets[$jumpTo], ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objFaq->alias != '') ? $objFaq->alias : $objFaq->id));
	}
}
