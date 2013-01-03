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
 * Class ModuleFaqPage
 *
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Faq
 */
class ModuleFaqPage extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_faqpage';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### FAQ PAGE ###';
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

		global $objPage;
		$arrFaq = array_fill_keys($this->faq_categories, array());

		// Add FAQs
		while ($objFaq->next())
		{
			$objTemp = (object) $objFaq->row();

			// Clean RTE output
			if ($objPage->outputFormat == 'xhtml')
			{
				$objFaq->answer = \String::toXhtml($objFaq->answer);
			}
			else
			{
				$objFaq->answer = \String::toHtml5($objFaq->answer);
			}

			$objTemp->answer = \String::encodeEmail($objFaq->answer);
			$objTemp->addImage = false;

			// Add an image
			if ($objFaq->addImage && $objFaq->singleSRC != '')
			{
				if (!is_numeric($objFaq->singleSRC))
				{
					$objTemp->answer = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
				else
				{
					$objModel = \FilesModel::findByPk($objFaq->singleSRC);

					if ($objModel !== null && is_file(TL_ROOT . '/' . $objModel->path))
					{
						$objFaq->singleSRC = $objModel->path;
						$this->addImageToTemplate($objTemp, $objFaq->row());
					}
				}
			}

			$objTemp->enclosure = array();

			// Add enclosure
			if ($objFaq->addEnclosure)
			{
				$this->addEnclosuresToTemplate($objTemp, $objFaq->row());
			}

			$objTemp->info = sprintf($GLOBALS['TL_LANG']['MSC']['faqCreatedBy'], $this->parseDate($objPage->dateFormat, $objFaq->tstamp), $objFaq->getRelated('author')->name);

			// Order by PID
			$arrFaq[$objFaq->pid]['items'][] = $objTemp;
			$arrFaq[$objFaq->pid]['headline'] = $objFaq->getRelated('pid')->headline;
		}

		$arrFaq = array_values(array_filter($arrFaq));
		$limit_i = count($arrFaq) - 1;

		// Add classes first, last, even and odd
		for ($i=0; $i<=$limit_i; $i++)
		{
			$class = (($i == 0) ? 'first ' : '') . (($i == $limit_i) ? 'last ' : '') . (($i%2 == 0) ? 'even' : 'odd');
			$arrFaq[$i]['class'] = trim($class);
			$limit_j = count($arrFaq[$i]['items']) - 1;

			for ($j=0; $j<=$limit_j; $j++)
			{
				$class = (($j == 0) ? 'first ' : '') . (($j == $limit_j) ? 'last ' : '') . (($j%2 == 0) ? 'even' : 'odd');
				$arrFaq[$i]['items'][$j]->class = trim($class);
			}
		}

		$this->Template->faq = $arrFaq;
		$this->Template->request = $this->getIndexFreeRequest(true);
		$this->Template->topLink = $GLOBALS['TL_LANG']['MSC']['backToTop'];
	}
}
