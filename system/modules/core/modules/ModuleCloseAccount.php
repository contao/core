<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ModuleCloseAccount
 *
 * Front end module "close account".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ModuleCloseAccount extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_password';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### CLOSE ACCOUNT ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Return if there is no logged in user
		if (!FE_USER_LOGGED_IN)
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
		$this->import('FrontendUser', 'User');

		// Initialize the password widget
		$arrField = array
		(
			'name' => 'password',
			'inputType' => 'text',
			'label' => $GLOBALS['TL_LANG']['MSC']['password'][0],
			'eval' => array('hideInput'=>true, 'mandatory'=>true, 'required'=>true, 'tableless'=>$this->tableless)
		);

		$objWidget = new \FormTextField($this->prepareForWidget($arrField, $arrField['name']));
		$objWidget->rowClass = 'row_0 row_first even';

		// Validate widget
		if (\Input::post('FORM_SUBMIT') == 'tl_close_account')
		{
			$objWidget->validate();

			// Validate the password
			if (!$objWidget->hasErrors())
			{
				// The password has been generated with crypt()
				if (\Encryption::test($this->User->password))
				{
					$blnAuthenticated = (crypt($objWidget->value, $this->User->password) == $this->User->password);
				}
				else
				{
					list(, $strSalt) = explode(':', $this->User->password);
					$blnAuthenticated = ($strSalt == '') ? ($strPassword == sha1($objWidget->value)) : ($strPassword == sha1($strSalt . $objWidget->value));
				}

				if (!$blnAuthenticated)
				{
					$objWidget->value = '';
					$objWidget->addError($GLOBALS['TL_LANG']['ERR']['invalidPass']);
				}
			}

			// Close account
			if (!$objWidget->hasErrors())
			{
				// HOOK: send account ID
				if (isset($GLOBALS['TL_HOOKS']['closeAccount']) && is_array($GLOBALS['TL_HOOKS']['closeAccount']))
				{
					foreach ($GLOBALS['TL_HOOKS']['closeAccount'] as $callback)
					{
						$this->import($callback[0]);
						$this->$callback[0]->$callback[1]($this->User->id, $this->reg_close, $this);
					}
				}

				$objMember = \MemberModel::findByPk($this->User->id);

				// Remove the account
				if ($this->reg_close == 'close_delete')
				{
					$objMember->delete();
					$this->log('User account ID ' . $this->User->id . ' (' . $this->User->email . ') has been deleted', 'ModuleCloseAccount compile()', TL_ACCESS);
				}
				// Deactivate the account
				else
				{
					$objMember->disable = 1;
					$objMember->save();
					$this->log('User account ID ' . $this->User->id . ' (' . $this->User->email . ') has been deactivated', 'ModuleCloseAccount compile()', TL_ACCESS);
				}

				$this->User->logout();
				$this->jumpToOrReload($this->objModel->getRelated('jumpTo')->row());
			}
		}

		$this->Template->fields = $objWidget->parse();

		$this->Template->formId = 'tl_close_account';
		$this->Template->action = $this->getIndexFreeRequest();
		$this->Template->slabel = specialchars($GLOBALS['TL_LANG']['MSC']['closeAccount']);
		$this->Template->rowLast = 'row_1 row_last odd';
		$this->Template->tableless = $this->tableless;
	}
}
