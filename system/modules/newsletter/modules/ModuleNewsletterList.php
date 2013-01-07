<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Newsletter
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ModuleNewsletterList
 *
 * Front end module "newsletter list".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Newsletter
 */
class ModuleNewsletterList extends \Module
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
			$objTemplate = new \BackendTemplate('be_wildcard');

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
	 */
	protected function compile()
	{
		global $objPage;
		$arrJumpTo = array();
		$arrNewsletter = array();

		$objNewsletter = \NewsletterModel::findSentByPids($this->nl_channels);

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
					$objJumpTo = \PageModel::findPublishedById($objTarget->jumpTo);

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
