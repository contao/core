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
 * Front end module "newsletter list".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			/** @var \BackendTemplate|object $objTemplate */
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['nl_list'][0]) . ' ###';
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
		/** @var \PageModel $objPage */
		global $objPage;

		$arrJumpTo = array();
		$arrNewsletter = array();

		$strRequest = ampersand(\Environment::get('request'), true);
		$objNewsletter = \NewsletterModel::findSentByPids($this->nl_channels);

		if ($objNewsletter !== null)
		{
			while ($objNewsletter->next())
			{
				/** @var \NewsletterModel $objNewsletter */
				if (($objTarget = $objNewsletter->getRelated('pid')) === null)
				{
					continue;
				}

				$jumpTo = intval($objTarget->jumpTo);

				// A jumpTo page is not mandatory for newsletter channels (see #6521) but required for the list module
				if ($jumpTo < 1)
				{
					throw new \Exception("Newsletter channels without redirect page cannot be used in a newsletter list");
				}

				$strUrl = $strRequest;

				if (!isset($arrJumpTo[$objTarget->jumpTo]))
				{
					/** @var \PageModel $objModel */
					$objModel = $objTarget->getRelated('jumpTo');
					$objJumpTo = $objModel->loadDetails();

					if ($objJumpTo !== null)
					{
						$arrJumpTo[$objTarget->jumpTo] = $this->generateFrontendUrl($objJumpTo->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/%s' : '/items/%s'));
					}
					else
					{
						$arrJumpTo[$objTarget->jumpTo] = $strUrl;
					}
				}

				$strUrl = $arrJumpTo[$objTarget->jumpTo];
				$strAlias = ($objNewsletter->alias != '' && !\Config::get('disableAlias')) ? $objNewsletter->alias : $objNewsletter->id;

				$arrNewsletter[] = array
				(
					'subject' => $objNewsletter->subject,
					'title' => strip_insert_tags($objNewsletter->subject),
					'href' => sprintf($strUrl, $strAlias),
					'date' => \Date::parse($objPage->dateFormat, $objNewsletter->date),
					'datim' => \Date::parse($objPage->datimFormat, $objNewsletter->date),
					'time' => \Date::parse($objPage->timeFormat, $objNewsletter->date),
					'channel' => $objNewsletter->channel
				);
			}
		}

		$this->Template->newsletters = $arrNewsletter;
	}
}
