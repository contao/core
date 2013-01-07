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
 * Class ModuleFaq
 *
 * Provide methods regarding FAQs.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class ModuleFaq extends Frontend
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

		$time = time();
		$arrProcessed = array();

		// Get all categories
		$objFaq = $this->Database->execute("SELECT id, jumpTo FROM tl_faq_category");

		// Walk through each category
		while ($objFaq->next())
		{
			if (!empty($arrRoot) && !in_array($objFaq->jumpTo, $arrRoot))
			{
				continue;
			}

			// Get the URL of the jumpTo page
			if (!isset($arrProcessed[$objFaq->jumpTo]))
			{
				$arrProcessed[$objFaq->jumpTo] = false;

				// Get the target page
				$objParent = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=? AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1 AND noSearch!=1" . ($blnIsSitemap ? " AND sitemap!='map_never'" : ""))
											->limit(1)
											->execute($objFaq->jumpTo);

				// Determin the domain
				if ($objParent->numRows)
				{
					$domain = $this->Environment->base;
					$objParent = $this->getPageDetails($objParent->id);

					if ($objParent->domain != '')
					{
						$domain = ($this->Environment->ssl ? 'https://' : 'http://') . $objParent->domain . TL_PATH . '/';
					}

					$arrProcessed[$objFaq->jumpTo] = $domain . $this->generateFrontendUrl($objParent->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/items/%s'), $objParent->language);
				}
			}

			// Skip FAQs without target page
			if ($arrProcessed[$objFaq->jumpTo] === false)
			{
				continue;
			}

			$strUrl = $arrProcessed[$objFaq->jumpTo];

			// Get items
			$objItem = $this->Database->prepare("SELECT * FROM tl_faq WHERE pid=? AND published=1 ORDER BY sorting")
									  ->execute($objFaq->id);

			// Add items to the indexer
			while ($objItem->next())
			{
				$arrPages[] = sprintf($strUrl, (($objItem->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objItem->alias : $objItem->id));
			}
		}

		return $arrPages;
	}
}

?>