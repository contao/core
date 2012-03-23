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
 * @package    Newsletter
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ModuleSubscribe
 *
 * Front end module "newsletter subscribe".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ModuleSubscribe extends \Module
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
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### NEWSLETTER SUBSCRIBE ###';
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
		// Overwrite default template
		if ($this->nl_template)
		{
			$this->Template = new \FrontendTemplate($this->nl_template);
			$this->Template->setData($this->arrData);
		}

		// Activate e-mail address
		if ($this->Input->get('token'))
		{
			$this->activateRecipient();
			return;
		}

		// Subscribe
		if ($this->Input->post('FORM_SUBMIT') == 'tl_subscribe')
		{
			$this->addRecipient();
		}

		$blnHasError = false;

		// Error message
		if (strlen($_SESSION['SUBSCRIBE_ERROR']))
		{
			$blnHasError  = true;
			$this->Template->mclass = 'error';
			$this->Template->message = $_SESSION['SUBSCRIBE_ERROR'];
			$_SESSION['SUBSCRIBE_ERROR'] = '';
		}

		// Confirmation message
		if (strlen($_SESSION['SUBSCRIBE_CONFIRM']))
		{
			$this->Template->mclass = 'confirm';
			$this->Template->message = $_SESSION['SUBSCRIBE_CONFIRM'];
			$_SESSION['SUBSCRIBE_CONFIRM'] = '';
		}

		$arrChannels = array();
		$objChannel = \NewsletterChannelCollection::findByIds($this->nl_channels);

		// Get the titles
		if ($objChannel !== null)
		{
			while ($objChannel->next())
			{
				$arrChannels[$objChannel->id] = $objChannel->title;
			}
		}

		// Default template variables
		$this->Template->email = '';
		$this->Template->channels = $arrChannels;
		$this->Template->showChannels = !$this->nl_hideChannels;
		$this->Template->submit = specialchars($GLOBALS['TL_LANG']['MSC']['subscribe']);
		$this->Template->channelsLabel = $GLOBALS['TL_LANG']['MSC']['nl_channels'];
		$this->Template->emailLabel = $GLOBALS['TL_LANG']['MSC']['emailAddress'];
		$this->Template->action = $this->getIndexFreeRequest();
		$this->Template->formId = 'tl_subscribe';
		$this->Template->id = $this->id;
		$this->Template->hasError = $blnHasError;
	}


	/**
	 * Activate a recipient
	 * @return void
	 */
	protected function activateRecipient()
	{
		$this->Template = new \FrontendTemplate('mod_newsletter');

		// Check the token
		$objRecipient = \NewsletterRecipientsModel::findByToken($this->Input->get('token'));

		if ($objRecipient === null)
		{
			$this->Template->mclass = 'error';
			$this->Template->message = $GLOBALS['TL_LANG']['ERR']['invalidToken'];
			return;
		}

		$arrAdd = array();
		$arrChannels = array();
		$arrCids = array();

		// Update the subscriptions
		while ($objRecipient->next())
		{
			$objRecipient->getRelated('pid');

			$arrAdd[] = $objRecipient->id;
			$arrChannels[] = $objRecipient->pid['title'];
			$arrCids[] = $objRecipient->pid['id'];

			$objRecipient->active = 1;
			$objRecipient->token = '';
			$objRecipient->pid = $objRecipient->pid['id'];
			$objRecipient->save();
		}

		// Log activity
		$this->log($objRecipient->email . ' has subscribed to the following channels: ' . implode(', ', $arrChannels), 'ModuleSubscribe activateRecipient()', TL_NEWSLETTER);

		// HOOK: post activation callback
		if (isset($GLOBALS['TL_HOOKS']['activateRecipient']) && is_array($GLOBALS['TL_HOOKS']['activateRecipient']))
		{
			foreach ($GLOBALS['TL_HOOKS']['activateRecipient'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($objRecipient->email, $arrAdd, $arrCids);
			}
		}

		// Confirm activation
		$this->Template->mclass = 'confirm';
		$this->Template->message = $GLOBALS['TL_LANG']['MSC']['nl_activate'];
	}


	/**
	 * Add a new recipient
	 * @return void
	 */
	protected function addRecipient()
	{
		$arrChannels = $this->Input->post('channels');

		if (!is_array($arrChannels))
		{
			$_SESSION['UNSUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['noChannels'];
			$this->reload();
		}

		$arrChannels = array_intersect($arrChannels, $this->nl_channels); // see #3240

		// Check the selection
		if (!is_array($arrChannels) || empty($arrChannels))
		{
			$_SESSION['SUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['noChannels'];
			$this->reload();
		}

		$varInput = $this->idnaEncodeEmail($this->Input->post('email', true));

		// Validate the e-mail address
		if (!$this->isValidEmailAddress($varInput))
		{
			$_SESSION['SUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['email'];
			$this->reload();
		}

		$arrSubscriptions = array();

		// Get the existing active subscriptions
		if (($objSubscription = \NewsletterRecipientsModel::findBy(array("email=? AND active=1"), $varInput)) !== null)
		{
			$arrSubscriptions = $objSubscription->fetchEach('pid');
		}

		$arrNew = array_diff($arrChannels, $arrSubscriptions);

		// Return if there are no new subscriptions
		if (!is_array($arrNew) || empty($arrNew))
		{
			$_SESSION['SUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['subscribed'];
			$this->reload();
		}

		// Remove old subscriptions that have not been activated yet
		if (($objOld = \NewsletterRecipientsModel::findBy(array("email=? AND active=''"), $varInput)) !== null)
		{
			while ($objOld->next())
			{
				$objOld->delete();
			}
		}

		$time = time();
		$strToken = md5(uniqid(mt_rand(), true));

		// Add the new subscriptions
		foreach ($arrNew as $id)
		{
			$objRecipient = new \NewsletterRecipientsModel();

			$objRecipient->pid = $id;
			$objRecipient->tstamp = $time;
			$objRecipient->email = $varInput;
			$objRecipient->active = '';
			$objRecipient->addedOn = $time;
			$objRecipient->ip = $this->anonymizeIp($this->Environment->ip);
			$objRecipient->token = $strToken;

			$objRecipient->save();
		}

		// Get the channels
		$objChannel = \NewsletterChannelCollection::findByIds($arrChannels);

		// Prepare the e-mail text
		$strText = str_replace('##token##', $strToken, $this->nl_subscribe);
		$strText = str_replace('##domain##', $this->Environment->host, $strText);
		$strText = str_replace('##link##', $this->Environment->base . $this->Environment->request . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos($this->Environment->request, '?') !== false) ? '&' : '?') . 'token=' . $strToken, $strText);
		$strText = str_replace(array('##channel##', '##channels##'), implode("\n", $objChannel->fetchEach('title')), $strText);

		// Activation e-mail
		$objEmail = new \Email();
		$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
		$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
		$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['nl_subject'], $this->Environment->host);
		$objEmail->text = $strText;
		$objEmail->sendTo($varInput);

		// Redirect to the jumpTo page
		if ($this->jumpTo['id'])
		{
			$this->redirect($this->generateFrontendUrl($this->jumpTo));
		}

		$_SESSION['SUBSCRIBE_CONFIRM'] = $GLOBALS['TL_LANG']['MSC']['nl_confirm'];
		$this->reload();
	}
}
