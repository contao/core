<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleNewsletterList
 *
 * Front end module "newsletter list".
 * @copyright  Leo Feyer 2005-2011
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
		$objNewsletter = $this->Database->execute("SELECT *, (SELECT title FROM tl_newsletter_channel WHERE tl_newsletter_channel.id=tl_newsletter.pid) AS channel, (SELECT jumpTo FROM tl_newsletter_channel WHERE tl_newsletter_channel.id=tl_newsletter.pid) AS jumpTo FROM tl_newsletter WHERE pid IN(" . implode(',', array_map('intval', $this->nl_channels)) . ")" . (!BE_USER_LOGGED_IN ? " AND sent=1" : "") . " ORDER BY date DESC");

		$arrJumpTo = array();
		$arrNewsletter = array();
		$time = time();

		while ($objNewsletter->next())
		{
			if ($objNewsletter->jumpTo < 1)
			{
				continue;
			}

			if (!isset($arrJumpTo[$objNewsletter->jumpTo]))
			{
				$objJumpTo = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : ""))
											->limit(1)
											->execute($objNewsletter->jumpTo);
	
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
				'title' => strip_insert_tags($objNewsletter->subject),
				'href' => sprintf($strUrl, $strAlias),
				'date' => $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objNewsletter->date),
				'datim' => $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $objNewsletter->date),
				'time' => $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $objNewsletter->date),
				'channel' => $objNewsletter->channel
			);
		}

		$this->Template->newsletters = $arrNewsletter;
	}
}

?>