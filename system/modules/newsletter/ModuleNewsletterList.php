<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleNewsletterList
 *
 * Front end module "newsletter list".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
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

			return $objTemplate->parse();
		}

		$this->nl_channels = deserialize($this->nl_channels, true);

		// Return if there are no channels
		if (!is_array($this->nl_channels) || count($this->nl_channels) < 1)
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		$objNewsletter = $this->Database->execute("SELECT *, (SELECT title FROM tl_newsletter_channel WHERE tl_newsletter_channel.id=tl_newsletter.pid) AS channel, (SELECT jumpTo FROM tl_newsletter_channel WHERE tl_newsletter_channel.id=tl_newsletter.pid) AS jumpTo FROM tl_newsletter WHERE pid IN(" . implode(',', $this->nl_channels) . ")" . (!BE_USER_LOGGED_IN ? " AND sent=1" : "") . " ORDER BY date DESC");

		$arrJumpTo = array();
		$arrNewsletter = array();
		$time = time();

		while ($objNewsletter->next())
		{
			if ($objNewsletter->jumpTo < 1)
			{
				continue;
			}

			if (!array_key_exists($objNewsletter->jumpTo, $arrJumpTo))
			{
				$objJumpTo = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=? AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1")
											->limit(1)
											->execute($objNewsletter->jumpTo, $time, $time);
	
				if ($objJumpTo->numRows)
				{
					$arrJumpTo[$objNewsletter->jumpTo] = $this->generateFrontendUrl($objJumpTo->fetchAssoc(), '/items/%s');
				}
				else
				{
					$arrJumpTo[$objNewsletter->jumpTo] = null;
				}
			}

			$strUrl = $arrJumpTo[$objNewsletter->jumpTo];

			if (is_null($strUrl))
			{
				continue;
			}

			$strAlias = (strlen($objNewsletter->alias) && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objNewsletter->alias : $objNewsletter->id;

			$arrNewsletter[] = array
			(
				'subject' => $objNewsletter->subject,
				'href' => sprintf($strUrl, $strAlias),
				'date' => date($GLOBALS['TL_CONFIG']['dateFormat'], $objNewsletter->date),
				'datim' => date($GLOBALS['TL_CONFIG']['datimFormat'], $objNewsletter->date),
				'time' => date($GLOBALS['TL_CONFIG']['timeFormat'], $objNewsletter->date),
				'channel' => $objNewsletter->channel
			);
		}

		$this->Template->newsletters = $arrNewsletter;
	}
}

?>