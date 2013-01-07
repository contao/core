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
 * Class ModuleFaq
 *
 * Provide methods regarding FAQs.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Faq
 */
class ModuleFaq extends \Frontend
{

	/**
	 * Add FAQs to the indexer
	 * @param array
	 * @param integer
	 * @param boolean
	 * @return array
	 */
	public function getSearchablePages($arrPages, $intRoot=0, $blnIsSitemap=false)
	{
		$arrRoot = array();

		if ($intRoot > 0)
		{
			$arrRoot = $this->Database->getChildRecords($intRoot, 'tl_page');
		}

		$arrProcessed = array();

		// Get all categories
		$objFaq = \FaqCategoryModel::findAll();

		// Walk through each category
		if ($objFaq !== null)
		{
			while ($objFaq->next())
			{
				// Skip FAQs without target page
				if (!$objFaq->jumpTo)
				{
					continue;
				}

				// Skip FAQs outside the root nodes
				if (!empty($arrRoot) && !in_array($objFaq->jumpTo, $arrRoot))
				{
					continue;
				}

				// Get the URL of the jumpTo page
				if (!isset($arrProcessed[$objFaq->jumpTo]))
				{
					$domain = \Environment::get('base');
					$objParent = $this->getPageDetails($objFaq->jumpTo);

					// The target page does not exist
					if ($objParent === null)
					{
						continue;
					}

					if ($objParent->domain != '')
					{
						$domain = (\Environment::get('ssl') ? 'https://' : 'http://') . $objParent->domain . TL_PATH . '/';
					}

					$arrProcessed[$objFaq->jumpTo] = $domain . $this->generateFrontendUrl($objParent->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/items/%s'), $objParent->language);
				}

				$strUrl = $arrProcessed[$objFaq->jumpTo];

				// Get the items
				$objItems = \FaqModel::findByPid($objFaq->id, array('order'=>'sorting'));

				if ($objItems !== null)
				{
					while ($objItems->next())
					{
						$arrPages[] = sprintf($strUrl, (($objItems->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objItems->alias : $objItems->id));
					}
				}
			}
		}

		return $arrPages;
	}
}
