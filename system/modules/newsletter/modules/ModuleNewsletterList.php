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
 * @package    News
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \BackendTemplate, \Module, \NewsletterModel, \PageModel;


/**
 * Class ModuleNewsletterList
 *
 * Front end module "newsletter list".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ModuleNewsletterList extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_newsletter_list';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### NEWSLETTER LIST ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->nl_channels = deserialize($this->nl_channels);

		// Return if there are no channels
		if (!is_array($this->nl_channels) || empty($this->nl_channels))
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 * @return void
	 */
	protected function compile()
	{
		global $objPage;
		$arrJumpTo = array();
		$arrNewsletter = array();

		$objNewsletter = NewsletterModel::findSentByPids($this->nl_channels);

		if ($objNewsletter !== null)
		{
			while ($objNewsletter->next())
			{
				if (($objTarget = $objNewsletter->getRelated('pid')) === null || !$objTarget->jumpTo)
				{
					continue;
				}

				if (!isset($arrJumpTo[$objTarget->jumpTo]))
				{
					$objJumpTo = PageModel::findPublishedById($objTarget->jumpTo);

					if ($objJumpTo !== null)
					{
						$arrJumpTo[$objTarget->jumpTo] = $this->generateFrontendUrl($objJumpTo->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/items/%s'));
					}
					else
					{
						$arrJumpTo[$objTarget->jumpTo] = null;
					}
				}

				$strUrl = $arrJumpTo[$objTarget->jumpTo];

				if ($strUrl === null)
				{
					continue;
				}

				$strAlias = ($objNewsletter->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objNewsletter->alias : $objNewsletter->id;

				$arrNewsletter[] = array
				(
					'subject' => $objNewsletter->subject,
					'title' => strip_insert_tags($objNewsletter->subject),
					'href' => sprintf($strUrl, $strAlias),
					'date' => $this->parseDate($objPage->dateFormat, $objNewsletter->date),
					'datim' => $this->parseDate($objPage->datimFormat, $objNewsletter->date),
					'time' => $this->parseDate($objPage->timeFormat, $objNewsletter->date),
					'channel' => $objNewsletter->channel
				);
			}
		}

		$this->Template->newsletters = $arrNewsletter;
	}
}
