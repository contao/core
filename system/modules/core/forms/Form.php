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
 * Provide methods to handle front end forms.
 *
 * @property integer $id
 * @property string  $title
 * @property string  $formID
 * @property string  $method
 * @property boolean $tableless
 * @property boolean $allowTags
 * @property string  $attributes
 * @property boolean $novalidate
 * @property integer $jumpTo
 * @property boolean $sendViaEmail
 * @property boolean $skipEmpty
 * @property string  $format
 * @property string  $recipient
 * @property string  $subject
 * @property boolean $storeValues
 * @property string  $targetTable
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Form extends \Hybrid
{

	/**
	 * Model
	 * @var \FormModel
	 */
	protected $objModel;

	/**
	 * Key
	 * @var string
	 */
	protected $strKey = 'form';

	/**
	 * Table
	 * @var string
	 */
	protected $strTable = 'tl_form';

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form';


	/**
	 * Remove name attributes in the back end so the form is not validated
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			/** @var \BackendTemplate|object $objTemplate */
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['CTE']['form'][0]) . ' ###';
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->title;
			$objTemplate->href = 'contao/main.php?do=form&amp;table=tl_form_field&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate the form
	 *
	 * @return string
	 */
	protected function compile()
	{
		$hasUpload = false;
		$doNotSubmit = false;
		$arrSubmitted = array();

		$this->loadDataContainer('tl_form_field');
		$formId = ($this->formID != '') ? 'auto_'.$this->formID : 'auto_form_'.$this->id;

		$this->Template->fields = '';
		$this->Template->hidden = '';
		$this->Template->formSubmit = $formId;
		$this->Template->tableless = $this->tableless ? true : false;
		$this->Template->method = ($this->method == 'GET') ? 'get' : 'post';

		$this->initializeSession($formId);
		$arrLabels = array();

		// Get all form fields
		$arrFields = array();
		$objFields = \FormFieldModel::findPublishedByPid($this->id);

		if ($objFields !== null)
		{
			while ($objFields->next())
			{
				$arrFields[] = $objFields->current();
			}
		}

		// HOOK: compile form fields
		if (isset($GLOBALS['TL_HOOKS']['compileFormFields']) && is_array($GLOBALS['TL_HOOKS']['compileFormFields']))
		{
			foreach ($GLOBALS['TL_HOOKS']['compileFormFields'] as $callback)
			{
				$this->import($callback[0]);
				$arrFields = $this->$callback[0]->$callback[1]($arrFields, $formId, $this);
			}
		}

		// Process the fields
		if (!empty($arrFields) && is_array($arrFields))
		{
			$row = 0;
			$max_row = count($arrFields);

			foreach ($arrFields as $objField)
			{
				/** @var \FormFieldModel $objField */
				$strClass = $GLOBALS['TL_FFL'][$objField->type];

				// Continue if the class is not defined
				if (!class_exists($strClass))
				{
					continue;
				}

				$arrData = $objField->row();

				$arrData['decodeEntities'] = true;
				$arrData['allowHtml'] = $this->allowTags;
				$arrData['rowClass'] = 'row_'.$row . (($row == 0) ? ' row_first' : (($row == ($max_row - 1)) ? ' row_last' : '')) . ((($row % 2) == 0) ? ' even' : ' odd');
				$arrData['tableless'] = $this->tableless;

				// Increase the row count if its a password field
				if ($objField->type == 'password')
				{
					++$row;
					++$max_row;

					$arrData['rowClassConfirm'] = 'row_'.$row . (($row == ($max_row - 1)) ? ' row_last' : '') . ((($row % 2) == 0) ? ' even' : ' odd');
				}

				// Submit buttons do not use the name attribute
				if ($objField->type == 'submit')
				{
					$arrData['name'] = '';
				}

				// Unset the default value depending on the field type (see #4722)
				if (!empty($arrData['value']))
				{
					if (!in_array('value', trimsplit('[,;]', $GLOBALS['TL_DCA']['tl_form_field']['palettes'][$objField->type])))
					{
						$arrData['value'] = '';
					}
				}

				/** @var \Widget $objWidget */
				$objWidget = new $strClass($arrData);
				$objWidget->required = $objField->mandatory ? true : false;

				// HOOK: load form field callback
				if (isset($GLOBALS['TL_HOOKS']['loadFormField']) && is_array($GLOBALS['TL_HOOKS']['loadFormField']))
				{
					foreach ($GLOBALS['TL_HOOKS']['loadFormField'] as $callback)
					{
						$this->import($callback[0]);
						$objWidget = $this->$callback[0]->$callback[1]($objWidget, $formId, $this->arrData, $this);
					}
				}

				// Validate the input
				if (\Input::post('FORM_SUBMIT') == $formId)
				{
					$objWidget->validate();

					// HOOK: validate form field callback
					if (isset($GLOBALS['TL_HOOKS']['validateFormField']) && is_array($GLOBALS['TL_HOOKS']['validateFormField']))
					{
						foreach ($GLOBALS['TL_HOOKS']['validateFormField'] as $callback)
						{
							$this->import($callback[0]);
							$objWidget = $this->$callback[0]->$callback[1]($objWidget, $formId, $this->arrData, $this);
						}
					}

					if ($objWidget->hasErrors())
					{
						$doNotSubmit = true;
					}

					// Store current value in the session
					elseif ($objWidget->submitInput())
					{
						$arrSubmitted[$objField->name] = $objWidget->value;
						$_SESSION['FORM_DATA'][$objField->name] = $objWidget->value;
						unset($_POST[$objField->name]); // see #5474
					}
				}

				if ($objWidget instanceof \uploadable)
				{
					$hasUpload = true;
				}

				if ($objWidget instanceof \FormHidden)
				{
					$this->Template->hidden .= $objWidget->parse();
					--$max_row;
					continue;
				}

				if ($objWidget->name != '' && $objWidget->label != '')
				{
					$arrLabels[$objWidget->name] = $this->replaceInsertTags($objWidget->label); // see #4268
				}

				$this->Template->fields .= $objWidget->parse();
				++$row;
			}
		}

		// Process the form data
		if (\Input::post('FORM_SUBMIT') == $formId && !$doNotSubmit)
		{
			$this->processFormData($arrSubmitted, $arrLabels, $arrFields);
		}

		// Add a warning to the page title
		if ($doNotSubmit && !\Environment::get('isAjaxRequest'))
		{
			/** @var \PageModel $objPage */
			global $objPage;

			$title = $objPage->pageTitle ?: $objPage->title;
			$objPage->pageTitle = $GLOBALS['TL_LANG']['ERR']['form'] . ' - ' . $title;
			$_SESSION['FILES'] = array(); // see #3007
		}

		$strAttributes = '';
		$arrAttributes = deserialize($this->attributes, true);

		if ($arrAttributes[1] != '')
		{
			$strAttributes .= ' class="' . $arrAttributes[1] . '"';
		}

		$this->Template->hasError = $doNotSubmit;
		$this->Template->attributes = $strAttributes;
		$this->Template->enctype = $hasUpload ? 'multipart/form-data' : 'application/x-www-form-urlencoded';
		$this->Template->formId = $arrAttributes[0] ?: 'f'.$this->id;
		$this->Template->action = \Environment::get('indexFreeRequest');
		$this->Template->maxFileSize = $hasUpload ? $this->objModel->getMaxUploadFileSize() : false;
		$this->Template->novalidate = $this->novalidate ? ' novalidate' : '';

		// Get the target URL
		if ($this->method == 'GET' && $this->jumpTo && ($objTarget = $this->objModel->getRelated('jumpTo')) !== null)
		{
			$this->Template->action = $this->generateFrontendUrl($objTarget->row());
		}

		return $this->Template->parse();
	}


	/**
	 * Process form data, store it in the session and redirect to the jumpTo page
	 *
	 * @param array $arrSubmitted
	 * @param array $arrLabels
	 * @param array $arrFields
	 */
	protected function processFormData($arrSubmitted, $arrLabels, $arrFields)
	{
		// HOOK: prepare form data callback
		if (isset($GLOBALS['TL_HOOKS']['prepareFormData']) && is_array($GLOBALS['TL_HOOKS']['prepareFormData']))
		{
			foreach ($GLOBALS['TL_HOOKS']['prepareFormData'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($arrSubmitted, $arrLabels, $this, $arrFields);
			}
		}

		// Send form data via e-mail
		if ($this->sendViaEmail)
		{
			$keys = array();
			$values = array();
			$fields = array();
			$message = '';

			foreach ($arrSubmitted as $k=>$v)
			{
				if ($k == 'cc')
				{
					continue;
				}

				$v = deserialize($v);

				// Skip empty fields
				if ($this->skipEmpty && !is_array($v) && !strlen($v))
				{
					continue;
				}

				// Add field to message
				$message .= (isset($arrLabels[$k]) ? $arrLabels[$k] : ucfirst($k)) . ': ' . (is_array($v) ? implode(', ', $v) : $v) . "\n";

				// Prepare XML file
				if ($this->format == 'xml')
				{
					$fields[] = array
					(
						'name' => $k,
						'values' => (is_array($v) ? $v : array($v))
					);
				}

				// Prepare CSV file
				if ($this->format == 'csv')
				{
					$keys[] = $k;
					$values[] = (is_array($v) ? implode(',', $v) : $v);
				}
			}

			$recipients = \String::splitCsv($this->recipient);

			// Format recipients
			foreach ($recipients as $k=>$v)
			{
				$recipients[$k] = str_replace(array('[', ']', '"'), array('<', '>', ''), $v);
			}

			$email = new \Email();

			// Get subject and message
			if ($this->format == 'email')
			{
				$message = $arrSubmitted['message'];
				$email->subject = $arrSubmitted['subject'];
			}

			// Set the admin e-mail as "from" address
			$email->from = $GLOBALS['TL_ADMIN_EMAIL'];
			$email->fromName = $GLOBALS['TL_ADMIN_NAME'];

			// Get the "reply to" address
			if (strlen(\Input::post('email', true)))
			{
				$replyTo = \Input::post('email', true);

				// Add name
				if (strlen(\Input::post('name')))
				{
					$replyTo = '"' . \Input::post('name') . '" <' . $replyTo . '>';
				}

				$email->replyTo($replyTo);
			}

			// Fallback to default subject
			if (!strlen($email->subject))
			{
				$email->subject = $this->replaceInsertTags($this->subject, false);
			}

			// Send copy to sender
			if (strlen($arrSubmitted['cc']))
			{
				$email->sendCc(\Input::post('email', true));
				unset($_SESSION['FORM_DATA']['cc']);
			}

			// Attach XML file
			if ($this->format == 'xml')
			{
				/** @var \FrontendTemplate|object $objTemplate */
				$objTemplate = new \FrontendTemplate('form_xml');

				$objTemplate->fields = $fields;
				$objTemplate->charset = \Config::get('characterSet');

				$email->attachFileFromString($objTemplate->parse(), 'form.xml', 'application/xml');
			}

			// Attach CSV file
			if ($this->format == 'csv')
			{
				$email->attachFileFromString(\String::decodeEntities('"' . implode('";"', $keys) . '"' . "\n" . '"' . implode('";"', $values) . '"'), 'form.csv', 'text/comma-separated-values');
			}

			$uploaded = '';

			// Attach uploaded files
			if (!empty($_SESSION['FILES']))
			{
				foreach ($_SESSION['FILES'] as $file)
				{
					// Add a link to the uploaded file
					if ($file['uploaded'])
					{
						$uploaded .= "\n" . \Environment::get('base') . str_replace(TL_ROOT . '/', '', dirname($file['tmp_name'])) . '/' . rawurlencode($file['name']);
						continue;
					}

					$email->attachFileFromString(file_get_contents($file['tmp_name']), $file['name'], $file['type']);
				}
			}

			$uploaded = strlen(trim($uploaded)) ? "\n\n---\n" . $uploaded : '';
			$email->text = \String::decodeEntities(trim($message)) . $uploaded . "\n\n";

			// Send the e-mail
			try
			{
				$email->sendTo($recipients);
			}
			catch (\Swift_SwiftException $e)
			{
				$this->log('Form "' . $this->title . '" could not be sent: ' . $e->getMessage(), __METHOD__, TL_ERROR);
			}
		}

		// Store the values in the database
		if ($this->storeValues && $this->targetTable != '')
		{
			$arrSet = array();

			// Add the timestamp
			if ($this->Database->fieldExists('tstamp', $this->targetTable))
			{
				$arrSet['tstamp'] = time();
			}

			// Fields
			foreach ($arrSubmitted as $k=>$v)
			{
				if ($k != 'cc' && $k != 'id')
				{
					$arrSet[$k] = $v;
				}
			}

			// Files
			if (!empty($_SESSION['FILES']))
			{
				foreach ($_SESSION['FILES'] as $k=>$v)
				{
					if ($v['uploaded'])
					{
						$arrSet[$k] = str_replace(TL_ROOT . '/', '', $v['tmp_name']);
					}
				}
			}

			// HOOK: store form data callback
			if (isset($GLOBALS['TL_HOOKS']['storeFormData']) && is_array($GLOBALS['TL_HOOKS']['storeFormData']))
			{
				foreach ($GLOBALS['TL_HOOKS']['storeFormData'] as $callback)
				{
					$this->import($callback[0]);
					$arrSet = $this->$callback[0]->$callback[1]($arrSet, $this);
				}
			}

			// Set the correct empty value (see #6284, #6373)
			foreach ($arrSet as $k=>$v)
			{
				if ($v === '')
				{
					$arrSet[$k] = \Widget::getEmptyValueByFieldType($GLOBALS['TL_DCA'][$this->targetTable]['fields'][$k]['sql']);
				}
			}

			// Do not use Models here (backwards compatibility)
			$this->Database->prepare("INSERT INTO " . $this->targetTable . " %s")->set($arrSet)->execute();
		}

		// Store all values in the session
		foreach (array_keys($_POST) as $key)
		{
			$_SESSION['FORM_DATA'][$key] = $this->allowTags ? \Input::postHtml($key, true) : \Input::post($key, true);
		}

		$arrFiles = $_SESSION['FILES'];

		// HOOK: process form data callback
		if (isset($GLOBALS['TL_HOOKS']['processFormData']) && is_array($GLOBALS['TL_HOOKS']['processFormData']))
		{
			foreach ($GLOBALS['TL_HOOKS']['processFormData'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($arrSubmitted, $this->arrData, $arrFiles, $arrLabels, $this);
			}
		}

		$_SESSION['FILES'] = array(); // DO NOT CHANGE

		// Add a log entry
		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
			$this->log('Form "' . $this->title . '" has been submitted by "' . $this->User->username . '".', __METHOD__, TL_FORMS);
		}
		else
		{
			$this->log('Form "' . $this->title . '" has been submitted by ' . \System::anonymizeIp(\Environment::get('ip')) . '.', __METHOD__, TL_FORMS);
		}

		// Check whether there is a jumpTo page
		if (($objJumpTo = $this->objModel->getRelated('jumpTo')) !== null)
		{
			$this->jumpToOrReload($objJumpTo->row());
		}

		$this->reload();
	}


	/**
	 * Get the maximum file size that is allowed for file uploads
	 *
	 * @return integer
	 */
	protected function getMaxFileSize()
	{
		return $this->objModel->getMaxUploadFileSize(); // Backwards compatibility
	}


	/**
	 * Initialize the form in the current session
	 *
	 * @param string $formId
	 */
	protected function initializeSession($formId)
	{
		if (\Input::post('FORM_SUBMIT') != $formId)
		{
			return;
		}

		$arrMessageBox = array('TL_ERROR', 'TL_CONFIRM', 'TL_INFO');
		$_SESSION['FORM_DATA'] = is_array($_SESSION['FORM_DATA']) ? $_SESSION['FORM_DATA'] : array();

		foreach ($arrMessageBox as $tl)
		{
			if (is_array($_SESSION[$formId][$tl]))
			{
				$_SESSION[$formId][$tl] = array_unique($_SESSION[$formId][$tl]);

				foreach ($_SESSION[$formId][$tl] as $message)
				{
					/** @var \FrontendTemplate|object $objTemplate */
					$objTemplate = new \FrontendTemplate('form_message');

					$objTemplate->message = $message;
					$objTemplate->class = strtolower($tl);
					$objTemplate->tableless = $this->tableless ? true : false;

					$this->Template->fields .= $objTemplate->parse() . "\n";
				}

				$_SESSION[$formId][$tl] = array();
			}
		}
	}
}
