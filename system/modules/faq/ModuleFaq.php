<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Faq
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ModuleFaq
 *
 * Provide methods regarding FAQs.
 * @copyright  Leo Feyer 2008-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
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
			$arrRoot = $this->getChildRecords($intRoot, 'tl_page');
		}

		$arrProcessed = array();

		// Get all categories
		$objFaq = \FaqCategoryCollection::findAll();

		// Walk through each category
		if ($objFaq !== null)
		{
			while ($objFaq->next())
			{
				// Skip FAQs without target page
				if ($objFaq->jumpTo['id'] < 1)
				{
					continue;
				}

				// Skip FAQs outside the root nodes
				if (!empty($arrRoot) && !in_array($objFaq->jumpTo['id'], $arrRoot))
				{
					continue;
				}

				// Get the URL of the jumpTo page
				if (!isset($arrProcessed[$objFaq->jumpTo['id']]))
				{
					$domain = $this->Environment->base;
					$objParent = $this->getPageDetails($objFaq->jumpTo['id']);

					if ($objParent->domain != '')
					{
						$domain = ($this->Environment->ssl ? 'https://' : 'http://') . $objParent->domain . TL_PATH . '/';
					}

					$arrProcessed[$objFaq->jumpTo['id']] = $domain . $this->generateFrontendUrl($objParent->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/items/%s'), $objParent->language);
				}

				$strUrl = $arrProcessed[$objFaq->jumpTo['id']];

				// Get the items
				$objItems = \FaqCollection::findByPid($objFaq->id, array('order'=>'sorting'));

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
