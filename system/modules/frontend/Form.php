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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class Form
 *
 * Provide methods to handle front end forms.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class Form extends Hybrid
{

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
	 * Current record
	 * @var array
	 */
	protected $arrData = array();


	/**
	 * Remove name attributes in the back end so the form is not validated
	 * @return string
	 */
	public function generate()
	{
		$str = parent::generate();

		if (TL_MODE == 'BE')
		{
			$str = preg_replace('/name="[^"]+" ?/i', '', $str);
		}

		return $str;
	}


	/**
	 * Generate the form
	 * @return string
	 */
	protected function compile()
	{
		$hasUpload = false;
		$doNotSubmit = false;
		$arrSubmitted = array();

		$this->loadDataContainer('tl_form_field');
		$formId = strlen($this->formID) ? 'auto_'.$this->formID : 'auto_form_'.$this->id;

		$this->Template->fields = '';
		$this->Template->hidden = '';
		$this->Template->formSubmit = $formId;
		$this->Template->tableless = $this->tableless ? true : false;
		$this->Template->method = ($this->method == 'GET') ? 'get' : 'post';

		$this->initializeSession($formId);
		$this->getMaxFileSize();

		// Get all form fields
		$objFields = $this->Database->prepare("SELECT * FROM tl_form_field WHERE pid=? AND invisible!=1 ORDER BY sorting")
									->execute($this->id);

		$row = 0;
		$max_row = $objFields->numRows;

		while ($objFields->next())
		{
			$strClass = $GLOBALS['TL_FFL'][$objFields->type];

			// Continue if the class is not defined
			if (!$this->classFileExists($strClass))
			{
				continue;
			}

			$arrData = $objFields->row();

			$arrData['decodeEntities'] = true;
			$arrData['allowHtml'] = $this->allowTags;
			$arrData['rowClass'] = 'row_'.$row . (($row == 0) ? ' row_first' : (($row == ($max_row - 1)) ? ' row_last' : '')) . ((($row % 2) == 0) ? ' even' : ' odd');
			$arrData['tableless'] = $this->tableless;

			// Increase the row count if its a password field
			if ($objFields->type == 'password')
			{
				++$row;
				++$max_row;

				$arrData['rowClassConfirm'] = 'row_'.$row . (($row == ($max_row - 1)) ? ' row_last' : '') . ((($row % 2) == 0) ? ' even' : ' odd');
			}

			// Submit buttons do not use the name attribute
			if ($objFields->type == 'submit')
			{
				$arrData['name'] = '';
			}

			$objWidget = new $strClass($arrData);
			$objWidget->required = $objFields->mandatory ? true : false;

			// HOOK: load form field callback
			if (isset($GLOBALS['TL_HOOKS']['loadFormField']) && is_array($GLOBALS['TL_HOOKS']['loadFormField']))
			{
				foreach ($GLOBALS['TL_HOOKS']['loadFormField'] as $callback)
				{
					$this->import($callback[0]);
					$objWidget = $this->$callback[0]->$callback[1]($objWidget, $formId, $this->arrData);
				}
			}

			// Validate input
			if ($this->Input->post('FORM_SUBMIT') == $formId)
			{
				$objWidget->validate();

				// HOOK: validate form field callback
				if (isset($GLOBALS['TL_HOOKS']['validateFormField']) && is_array($GLOBALS['TL_HOOKS']['validateFormField']))
				{
					foreach ($GLOBALS['TL_HOOKS']['validateFormField'] as $callback)
					{
						$this->import($callback[0]);
						$objWidget = $this->$callback[0]->$callback[1]($objWidget, $formId, $this->arrData);
					}
				}

				if ($objWidget->hasErrors())
				{
					$doNotSubmit = true;
				}

				// Store current value in the session
				elseif ($objWidget->submitInput())
				{
					$arrSubmitted[$objFields->name] = $objWidget->value;
					$_SESSION['FORM_DATA'][$objFields->name] = $objWidget->value;
				}

				unset($_POST[$objFields->name]);
			}

			if ($objWidget instanceof uploadable)
			{
				$hasUpload = true;
			}

			if ($objWidget instanceof FormHidden)
			{
				$this->Template->hidden .= $objWidget->parse();
				--$max_row;
				continue;
			}

			$this->Template->fields .= $objWidget->parse();
			++$row;
		}

		// Process form data
		if ($this->Input->post('FORM_SUBMIT') == $formId && !$doNotSubmit)
		{
			$this->processFormData($arrSubmitted);
		}

		// Add a warning to the page title
		if ($doNotSubmit)
		{
			global $objPage;
			$title = ($objPage->pageTitle != '') ? $objPage->pageTitle : $objPage->title;
			$objPage->pageTitle = $GLOBALS['TL_LANG']['ERR']['form'] . ' - ' . $title;
		}

		$strAttributes = '';
		$arrAttributes = deserialize($this->attributes, true);

		if (strlen($arrAttributes[1]))
		{
			$strAttributes .= ' class="' . $arrAttributes[1] . '"';
		}

		$this->Template->hasError = $doNotSubmit;
		$this->Template->attributes = $strAttributes;
		$this->Template->enctype = $hasUpload ? 'multipart/form-data' : 'application/x-www-form-urlencoded';
		$this->Template->formId = strlen($arrAttributes[0]) ? $arrAttributes[0] : 'f' . $this->id;
		$this->Template->action = $this->getIndexFreeRequest();

		// Get target URL
		if ($this->method == 'GET')
		{
			$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
										  ->limit(1)
										  ->execute($this->jumpTo);

			if ($objNextPage->numRows)
			{
				$this->Template->action = $this->generateFrontendUrl($objNextPage->fetchAssoc());
			}
		}

		return $this->Template->parse();
	}


	/**
	 * Process form data, store it in the session and redirect to the jumpTo page
	 * @param array
	 */
	protected function processFormData($arrSubmitted)
	{
		// Send form data via e-mail
		if ($this->sendViaEmail)
		{
			$this->import('String');

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
				$message .= ucfirst($k) . ': ' . (is_array($v) ? implode(', ', $v) : $v) . "\n";

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

			$recipients = $this->String->splitCsv($this->recipient);

			// Format recipients
			foreach ($recipients as $k=>$v)
			{
				$recipients[$k] = str_replace(array('[', ']', '"'), array('<', '>', ''), $v);
			}

			$email = new Email();

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
			if (strlen($this->Input->post('email', true)))
			{
				$replyTo = $this->Input->post('email', true);

				// Add name
				if (strlen($this->Input->post('name')))
				{
					$replyTo = '"' . $this->Input->post('name') . '" <' . $replyTo . '>';
				}

				$email->replyTo($replyTo);
			}

			// Fallback to default subject
			if (!strlen($email->subject))
			{
				$email->subject = $this->replaceInsertTags($this->subject);
			}

			// Send copy to sender
			if (strlen($arrSubmitted['cc']))
			{
				$email->sendCc($this->Input->post('email', true));
				unset($_SESSION['FORM_DATA']['cc']);
			}

			// Attach XML file
			if ($this->format == 'xml')
			{
				$objTemplate = new FrontendTemplate('form_xml');

				$objTemplate->fields = $fields;
				$objTemplate->charset = $GLOBALS['TL_CONFIG']['characterSet'];

				$email->attachFileFromString($objTemplate->parse(), 'form.xml', 'application/xml');
			}

			// Attach CSV file
			if ($this->format == 'csv')
			{
				$email->attachFileFromString($this->String->decodeEntities('"' . implode('";"', $keys) . '"' . "\n" . '"' . implode('";"', $values) . '"'), 'form.csv', 'text/comma-separated-values');
			}

			$uploaded = '';

			// Attach uploaded files
			if (count($_SESSION['FILES']))
			{
				foreach ($_SESSION['FILES'] as $file)
				{
					// Add a link to the uploaded file
					if ($file['uploaded'])
					{
						$uploaded .= "\n" . $this->Environment->base . str_replace(TL_ROOT . '/', '', dirname($file['tmp_name'])) . '/' . rawurlencode($file['name']);
						continue;
					}

					$email->attachFileFromString(file_get_contents($file['tmp_name']), $file['name'], $file['type']);
				}
			}

			$uploaded = strlen(trim($uploaded)) ? "\n\n---\n" . $uploaded : '';

			// Send e-mail
			$email->text = $this->String->decodeEntities(trim($message)) . $uploaded . "\n\n";
			$email->sendTo($recipients);
		}

		// Store values in the database
		if ($this->storeValues && strlen($this->targetTable))
		{
			$arrSet = array();

			// Add timestamp
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
			if (count($_SESSION['FILES']))
			{
				foreach ($_SESSION['FILES'] as $k=>$v)
				{
					if ($v['uploaded'])
					{
						$arrSet[$k] = str_replace(TL_ROOT . '/', '', $v['tmp_name']);
					}
				}
			}

			$this->Database->prepare("INSERT INTO " . $this->targetTable . " %s")->set($arrSet)->execute();
		}

		// Store all values in the session
		foreach (array_keys($_POST) as $key)
		{
			$_SESSION['FORM_DATA'][$key] = $this->allowTags ? $this->Input->postHtml($key, true) : $this->Input->post($key, true);
		}

		$arrFiles = $_SESSION['FILES'];
		$arrData = $_SESSION['FORM_DATA'];

		// HOOK: process form data callback
		if (isset($GLOBALS['TL_HOOKS']['processFormData']) && is_array($GLOBALS['TL_HOOKS']['processFormData']))
		{
			foreach ($GLOBALS['TL_HOOKS']['processFormData'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($arrData, $this->arrData, $arrFiles);
			}
		}

		// Reset form data in case it has been modified in a callback function
		$_SESSION['FORM_DATA'] = $arrData;
		$_SESSION['FILES'] = array(); // DO NOT CHANGE

		// Add a log entry
		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
			$this->log('Form "' . $this->title . '" has been submitted by "' . $this->User->username . '".', 'Form processFormData()', TL_FORMS);
		}
		else
		{
			$this->log('Form "' . $this->title . '" has been submitted by ' . $this->Environment->ip . '.', 'Form processFormData()', TL_FORMS);
		}

		$this->jumpToOrReload($this->jumpTo);
	}


	/**
	 * Get the maximum file size that is allowed for file uploads
	 */
	protected function getMaxFileSize()
	{
		$this->Template->maxFileSize = $GLOBALS['TL_CONFIG']['maxFileSize'];

		$objMaxSize = $this->Database->prepare("SELECT MAX(maxlength) AS maxlength FROM tl_form_field WHERE pid=? AND type=? AND maxlength>?")
									 ->execute($this->id, 'upload', 0);

		if ($objMaxSize->maxlength > 0)
		{
			$this->Template->maxFileSize = $objMaxSize->maxlength;
		}
	}


	/**
	 * Initialize the form in the current session
	 * @param string
	 */
	protected function initializeSession($formId)
	{
		if ($this->Input->post('FORM_SUBMIT') != $formId)
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
					$objTemplate = new FrontendTemplate('form_message');

					$objTemplate->message = $message;
					$objTemplate->class = strtolower($tl);

					$this->Template->fields .= $objTemplate->parse() . "\n";
				}

				$_SESSION[$formId][$tl] = array();
			}
		}
	}
}

?>