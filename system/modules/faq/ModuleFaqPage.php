<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Faq
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleFaqPage
 *
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class ModuleFaqPage extends Module
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
			$objTemplate = new BackendTemplate('be_wildcard');

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
		global $objPage;
		$this->import('String');

		$objFaq = $this->Database->execute("SELECT *, author AS authorId, (SELECT headline FROM tl_faq_category WHERE tl_faq_category.id=tl_faq.pid) AS category, (SELECT name FROM tl_user WHERE tl_user.id=tl_faq.author) AS author FROM tl_faq WHERE pid IN(" . implode(',', array_map('intval', $this->faq_categories)) . ")" . (!BE_USER_LOGGED_IN ? " AND published=1" : "") . " ORDER BY pid, sorting");

		if ($objFaq->numRows < 1)
		{
			$this->Template->faq = array();
			return;
		}

		$count = 0;
		$arrFaq = array_fill_keys($this->faq_categories, array());

		// Add FAQs
		while ($objFaq->next())
		{
			$objTemp = (object) $objFaq->row();

			// Clean RTE output
			if ($objPage->outputFormat == 'xhtml')
			{
				$objTemp->answer = $this->String->toXhtml($objTemp->answer);
			}
			else
			{
				$objTemp->answer = $this->String->toHtml5($objTemp->answer);
			}

			$objTemp->answer = $this->String->encodeEmail($objTemp->answer);
			$objTemp->addImage = false;

			// Add image
			if ($objFaq->addImage && is_file(TL_ROOT . '/' . $objFaq->singleSRC))
			{
				$this->addImageToTemplate($objTemp, $objFaq->row());
			}

			$objTemp->enclosure = array();

			// Add enclosure
			if ($objFaq->addEnclosure)
			{
				$this->addEnclosuresToTemplate($objTemp, $objFaq->row());
			}

			$objTemp->info = sprintf($GLOBALS['TL_LANG']['MSC']['faqCreatedBy'], $this->parseDate($objPage->dateFormat, $objFaq->tstamp), $objFaq->author);

			// Order by PID
			$arrFaq[$objFaq->pid]['items'][] = $objTemp;
			$arrFaq[$objFaq->pid]['headline'] = $objFaq->category;
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

?>