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
 * @package    Newsletter
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleUnsubscribe
 *
 * Front end module "newsletter unsubscribe".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class ModuleUnsubscribe extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'nl_default';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### NEWSLETTER UNSUBSCRIBE ###';
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
		// Overwrite default template
		if ($this->nl_template)
		{
			$this->Template = new FrontendTemplate($this->nl_template);
			$this->Template->setData($this->arrData);
		}

		// Unsubscribe
		if ($this->Input->post('FORM_SUBMIT') == 'tl_unsubscribe')
		{
			$this->removeRecipient();
		}

		$blnHasError = false;

		// Error message
		if (strlen($_SESSION['UNSUBSCRIBE_ERROR']))
		{
			$blnHasError = true;
			$this->Template->mclass = 'error';
			$this->Template->message = $_SESSION['UNSUBSCRIBE_ERROR'];
			$_SESSION['UNSUBSCRIBE_ERROR'] = '';
		}

		// Confirmation message
		if (strlen($_SESSION['UNSUBSCRIBE_CONFIRM']))
		{
			$this->Template->mclass = 'confirm';
			$this->Template->message = $_SESSION['UNSUBSCRIBE_CONFIRM'];
			$_SESSION['UNSUBSCRIBE_CONFIRM'] = '';
		}

		$arrChannels = array();
		$objChannel = $this->Database->execute("SELECT id, title FROM tl_newsletter_channel WHERE id IN(" . implode(',', array_map('intval', $this->nl_channels)) . ") ORDER BY title");

		// Get titles
		while ($objChannel->next())
		{
			$arrChannels[$objChannel->id] = $objChannel->title;
		}

		// Default template variables
		$this->Template->channels = $arrChannels;
		$this->Template->showChannels = !$this->nl_hideChannels;
		$this->Template->email = urldecode($this->Input->get('email'));
		$this->Template->submit = specialchars($GLOBALS['TL_LANG']['MSC']['unsubscribe']);
		$this->Template->channelsLabel = $GLOBALS['TL_LANG']['MSC']['nl_channels'];
		$this->Template->emailLabel = $GLOBALS['TL_LANG']['MSC']['emailAddress'];
		$this->Template->action = $this->getIndexFreeRequest();
		$this->Template->formId = 'tl_unsubscribe';
		$this->Template->id = $this->id;
		$this->Template->hasError = $blnHasError;
	}


	/**
	 * Add a new recipient
	 */
	protected function removeRecipient()
	{
		$arrChannels = $this->Input->post('channels');
		$arrChannels = array_intersect($arrChannels, $this->nl_channels); // see #3240

		// Check the selection
		if (!is_array($arrChannels) || empty($arrChannels))
		{
			$_SESSION['UNSUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['noChannels'];
			$this->reload();
		}

		$varInput = $this->idnaEncodeEmail($this->Input->post('email', true));

		// Validate e-mail address
		if (!$this->isValidEmailAddress($varInput))
		{
			$_SESSION['UNSUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['email'];
			$this->reload();
		}

		$arrSubscriptions = array();

		// Get active subscriptions
		$objSubscription = $this->Database->prepare("SELECT pid FROM tl_newsletter_recipients WHERE email=? AND active=1")
										  ->execute($varInput);

		if ($objSubscription->numRows)
		{
			$arrSubscriptions = $objSubscription->fetchEach('pid');
		}

		$arrRemove = array_intersect($arrChannels, $arrSubscriptions);

		// Return if there are no subscriptions to remove
		if (!is_array($arrRemove) || empty($arrRemove))
		{
			$_SESSION['UNSUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['unsubscribed'];
			$this->reload();
		}

		// Remove subscriptions
		$this->Database->prepare("DELETE FROM tl_newsletter_recipients WHERE email=? AND pid IN(" . implode(',', array_map('intval', $arrRemove)) . ")")
					   ->execute($varInput);

		// Get channels
		$objChannels = $this->Database->execute("SELECT title FROM tl_newsletter_channel WHERE id IN(" . implode(',', array_map('intval', $arrRemove)) . ")");
		$arrChannels = $objChannels->fetchEach('title');

		// Log activity
		$this->log($varInput . ' unsubscribed from ' . implode(', ', $arrChannels), 'ModuleUnsubscribe removeRecipient()', TL_NEWSLETTER);

		// HOOK: post unsubscribe callback
		if (isset($GLOBALS['TL_HOOKS']['removeRecipient']) && is_array($GLOBALS['TL_HOOKS']['removeRecipient']))
		{
			foreach ($GLOBALS['TL_HOOKS']['removeRecipient'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($varInput, $arrRemove);
			}
		}

		// Confirmation e-mail
		$objEmail = new Email();

		$strText = str_replace('##domain##', $this->Environment->host, $this->nl_unsubscribe);
		$strText = str_replace(array('##channel##', '##channels##'), implode("\n", $arrChannels), $strText);

		$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
		$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
		$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['nl_subject'], $this->Environment->host);
		$objEmail->text = $strText;

		$objEmail->sendTo($varInput);
		global $objPage;

		// Redirect to jumpTo page
		if (strlen($this->jumpTo) && $this->jumpTo != $objPage->id)
		{
			$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
										  ->limit(1)
										  ->execute($this->jumpTo);

			if ($objNextPage->numRows)
			{
				$this->redirect($this->generateFrontendUrl($objNextPage->fetchAssoc()));
			}
		}

		$_SESSION['UNSUBSCRIBE_CONFIRM'] = $GLOBALS['TL_LANG']['MSC']['nl_removed'];
		$this->reload();
	}
}

?>