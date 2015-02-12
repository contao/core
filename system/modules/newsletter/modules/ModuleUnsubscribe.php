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
 * Front end module "newsletter unsubscribe".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleUnsubscribe extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'nl_default';


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

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['unsubscribe'][0]) . ' ###';
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
			/** @var \FrontendTemplate|object $objTemplate */
			$objTemplate = new \FrontendTemplate($this->nl_template);

			$this->Template = $objTemplate;
			$this->Template->setData($this->arrData);
		}

		// Unsubscribe
		if (\Input::post('FORM_SUBMIT') == 'tl_unsubscribe')
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
		$objChannel = \NewsletterChannelModel::findByIds($this->nl_channels);

		// Get the titles
		if ($objChannel !== null)
		{
			while ($objChannel->next())
			{
				$arrChannels[$objChannel->id] = $objChannel->title;
			}
		}

		// Default template variables
		$this->Template->channels = $arrChannels;
		$this->Template->showChannels = !$this->nl_hideChannels;
		$this->Template->email = urldecode(\Input::get('email'));
		$this->Template->submit = specialchars($GLOBALS['TL_LANG']['MSC']['unsubscribe']);
		$this->Template->channelsLabel = $GLOBALS['TL_LANG']['MSC']['nl_channels'];
		$this->Template->emailLabel = $GLOBALS['TL_LANG']['MSC']['emailAddress'];
		$this->Template->action = \Environment::get('indexFreeRequest');
		$this->Template->formId = 'tl_unsubscribe';
		$this->Template->id = $this->id;
		$this->Template->hasError = $blnHasError;
	}


	/**
	 * Remove the recipient
	 */
	protected function removeRecipient()
	{
		$arrChannels = \Input::post('channels');

		if (!is_array($arrChannels))
		{
			$_SESSION['UNSUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['noChannels'];
			$this->reload();
		}

		$arrChannels = array_intersect($arrChannels, $this->nl_channels); // see #3240

		// Check the selection
		if (!is_array($arrChannels) || empty($arrChannels))
		{
			$_SESSION['UNSUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['noChannels'];
			$this->reload();
		}

		$varInput = \Idna::encodeEmail(\Input::post('email', true));

		// Validate e-mail address
		if (!\Validator::isEmail($varInput))
		{
			$_SESSION['UNSUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['email'];
			$this->reload();
		}

		$arrSubscriptions = array();

		// Get the existing active subscriptions
		if (($objSubscription = \NewsletterRecipientsModel::findBy(array("email=? AND active=1"), $varInput)) !== null)
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

		// Remove the subscriptions
		if (($objRemove = \NewsletterRecipientsModel::findByEmailAndPids($varInput, $arrRemove)) !== null)
		{
			while ($objRemove->next())
			{
				$objRemove->delete();
			}
		}

		// Get the channels
		$objChannels = \NewsletterChannelModel::findByIds($arrRemove);
		$arrChannels = $objChannels->fetchEach('title');

		// Log activity
		$this->log($varInput . ' unsubscribed from ' . implode(', ', $arrChannels), __METHOD__, TL_NEWSLETTER);

		// HOOK: post unsubscribe callback
		if (isset($GLOBALS['TL_HOOKS']['removeRecipient']) && is_array($GLOBALS['TL_HOOKS']['removeRecipient']))
		{
			foreach ($GLOBALS['TL_HOOKS']['removeRecipient'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($varInput, $arrRemove);
			}
		}

		// Prepare the simple token data
		$arrData = array();
		$arrData['domain'] = \Idna::decode(\Environment::get('host'));
		$arrData['channel'] = $arrData['channels'] = implode("\n", $arrChannels);

		// Confirmation e-mail
		$objEmail = new \Email();
		$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
		$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
		$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['nl_subject'], \Idna::decode(\Environment::get('host')));
		$objEmail->text = \String::parseSimpleTokens($this->nl_unsubscribe, $arrData);
		$objEmail->sendTo($varInput);

		// Redirect to the jumpTo page
		if ($this->jumpTo && ($objTarget = $this->objModel->getRelated('jumpTo')) !== null)
		{
			$this->redirect($this->generateFrontendUrl($objTarget->row()));
		}

		$_SESSION['UNSUBSCRIBE_CONFIRM'] = $GLOBALS['TL_LANG']['MSC']['nl_removed'];
		$this->reload();
	}
}
