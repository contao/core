<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
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
 * Class BackendPreview
 *
 * Set up the front end preview bar.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class BackendPreview extends Controller
{

	/**
	 * Initialize the controller
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}

	/**
	 * Genrate the preview bar and parse the template
	 *
	 * @return String
	 */
	public function generate()
	{
		$objUser = $this->getCurrentBackendUser();

		if ($objUser === null)
		{
			return;
		}

		if (Environment::get('isAjaxRequest') && $objUser->admin)
		{
			$this->getDatalistOptions($objUser);
		}

		// Create the template object
		$this->Template = new BackendTemplate('be_preview');
		$this->Template->user = $this->getCurrentMemberName();
		$this->Template->show = Input::cookie('FE_PREVIEW');
		$this->Template->update = false;

		// Switch
		if (Input::post('FORM_SUBMIT') == 'tl_switch')
		{
			$this->doFormSubmit($objUser);
		}

		// Default variables
		$this->Template->theme = Backend::getTheme();
		$this->Template->base = Environment::get('base');
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->apply = $GLOBALS['TL_LANG']['MSC']['apply'];
		$this->Template->reload = $GLOBALS['TL_LANG']['MSC']['reload'];
		$this->Template->feUser = $GLOBALS['TL_LANG']['MSC']['feUser'];
		$this->Template->username = $GLOBALS['TL_LANG']['MSC']['username'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->lblHide = $GLOBALS['TL_LANG']['MSC']['hiddenHide'];
		$this->Template->lblShow = $GLOBALS['TL_LANG']['MSC']['hiddenShow'];
		$this->Template->fePreview = $GLOBALS['TL_LANG']['MSC']['fePreview'];
		$this->Template->hiddenElements = $GLOBALS['TL_LANG']['MSC']['hiddenElements'];
		$this->Template->closeSrc = TL_FILES_URL . 'system/themes/' . Backend::getTheme() . '/images/close.gif';
		$this->Template->action = ampersand(Environment::get('request'));

		// Display member switch
		$this->Template->isAdmin = false;
		$objResult = $this->Database->execute("SELECT id FROM tl_member");
		if ($objResult->count() > 0)
		{
			$this->Template->isAdmin = $objUser->admin;
		}

		$objCurrentPage = $this->getCurrentPageModel();
		$this->Template->editBar = false;
		if ($objCurrentPage !== null)
		{
			$objCurrentPage->loadDetails();
			$this->Template->editBar = $this->renderNavigation($GLOBALS['TL_PREVIEW'], $objCurrentPage);
		}

		return $this->Template->parse();
	}

	/**
	 * Find ten matching usernames and return them as JSON
	 *
	 * @param \UserModel $objUser
	 */
	protected function getDatalistOptions($objUser)
	{
		if (!$objUser->admin)
		{
			header('HTTP/1.1 400 Bad Request');
			die('You must be an administrator to use the script');
		}

		$time = time();
		$arrUsers = array();

		// Get the active front end users
		$objUsers = $this->Database->prepare("SELECT username FROM tl_member WHERE username LIKE ? AND login=1 AND disable!=1 AND (start='' OR start<$time) AND (stop='' OR stop>$time) ORDER BY username")
			->limit(10)
			->execute(str_replace('%', '', Input::post('value')) . '%');

		if ($objUsers->numRows)
		{
			$arrUsers = $objUsers->fetchEach('username');
		}

		header('Content-type: application/json');
		die(json_encode($arrUsers));
	}

	/**
	 * get the current logged in back end user
	 *
	 * @return null|\UserModel
	 */
	protected function getCurrentBackendUser()
	{
		$strHash = sha1(session_id() . (!$GLOBALS['TL_CONFIG']['disableIpCheck'] ? Environment::get('ip') : '') . 'BE_USER_AUTH');

		// Get the back end user
		$objSession = $this->Database->prepare("SELECT * FROM tl_session WHERE hash=? AND name='BE_USER_AUTH'")
			->limit(1)
			->execute($strHash);

		// Try to find the session in the database
		if ($objSession->numRows < 1)
		{
			$this->log('Could not find the session record', __METHOD__, TL_ACCESS);
			return null;
		}

		$time = time();
		$objSession = $objSession->fetchAssoc();

		// Validate the session
		if (
			$objSession['sessionID'] != session_id() || (!$GLOBALS['TL_CONFIG']['disableIpCheck'] && $objSession['ip'] != Environment::get('ip')) || $objSession['hash'] != $strHash || ($objSession['tstamp'] + $GLOBALS['TL_CONFIG']['sessionTimeout']) < $time
		)
		{
			$this->log('Could not verify the session', __METHOD__, TL_ACCESS);
			return null;
		}

		$objUser = \UserModel::findByPk($objSession['pid']);

		if ($objUser === null)
		{
			return null;
		}
		return $objUser;
	}

	/**
	 * get the current logged in front end member username
	 *
	 * @return String
	 */
	protected function getCurrentMemberName()
	{
		$strHash = sha1(session_id() . (!$GLOBALS['TL_CONFIG']['disableIpCheck'] ? Environment::get('ip') : '') . 'FE_USER_AUTH');

		// Get the front end user
		if (FE_USER_LOGGED_IN)
		{
			$objMember = $this->Database->prepare("SELECT username FROM tl_member WHERE id=(SELECT pid FROM tl_session WHERE hash=?)")
				->limit(1)
				->execute($strHash);

			if ($objMember->numRows)
			{
				return $objMember->username;
			}
		}
		return '';
	}

	/**
	 * Do the preview form submit
	 *
	 * @param \UserModel $objUser
	 */
	public function doFormSubmit($objUser)
	{
		$time = time();
		$strHash = sha1(session_id() . (!$GLOBALS['TL_CONFIG']['disableIpCheck'] ? Environment::get('ip') : '') . 'FE_USER_AUTH');

		// Hide unpublished elements
		if (Input::post('preview_unpublished') == 'hide')
		{
			$this->setCookie('FE_PREVIEW', 0, ($time - 86400));
			$this->Template->show = 0;
		}

		// Show unpublished elements
		else
		{
			$this->setCookie('FE_PREVIEW', 1, ($time + $GLOBALS['TL_CONFIG']['sessionTimeout']));
			$this->Template->show = 1;
		}

		// Allow admins to switch user accounts
		if ($objUser->admin)
		{
			// Remove old sessions
			$this->Database->prepare("DELETE FROM tl_session WHERE tstamp<? OR hash=?")
				->execute(($time - $GLOBALS['TL_CONFIG']['sessionTimeout']), $strHash);

			// Log in the front end user
			if (Input::post('preview_user') != '')
			{
				if (($objMember = MemberModel::findByUsername(Input::post('preview_user'))) !== null)
				{
					// Insert the new session
					$this->Database->prepare("INSERT INTO tl_session (pid, tstamp, name, sessionID, ip, hash) VALUES (?, ?, ?, ?, ?, ?)")
						->execute($objMember->id, $time, 'FE_USER_AUTH', session_id(), Environment::get('ip'), $strHash);

					// Set the cookie
					$this->setCookie('FE_USER_AUTH', $strHash, ($time + $GLOBALS['TL_CONFIG']['sessionTimeout']), null, null, false, true);
					$this->Template->user = Input::post('preview_user');
				}
			}

			// Log out the front end user
			else
			{
				// Remove cookie
				$this->setCookie('FE_USER_AUTH', $strHash, ($time - 86400), null, null, false, true);
				$this->Template->user = '';
			}
		}
		$this->reload();
	}

	/**
	 * get the current page model
	 *
	 * @return \PageModel
	 */
	protected function getCurrentPageModel()
	{
		$pageId = Frontend::getPageIdFromUrl();
		$objRootPage = null;

		// Load a website root page object if there is no page ID
		if ($pageId === null)
		{
			$objRootPage = Frontend::getRootPageFromUrl();
			$objHandler = new $GLOBALS['TL_PTY']['root']();
			$pageId = $objHandler->generate($objRootPage->id, true);
		}
		// Throw a 404 error if the request is not a Contao request (see #2864)
		else if ($pageId === false)
		{
			$objRootPage = Frontend::getRootPageFromUrl();
			return PageModel::find404ByPid($objRootPage->id);
		}

		// Get the current page object(s)
		return PageModel::findByIdOrAlias($pageId);
	}

	/**
	 * Render teh navigation template
	 *
	 * @param array $arrNavigation
	 * @param \Model $objCurrentModel
	 * @param int $level
	 *
	 * @return String
	 */
	protected function renderNavigation($arrNavigation, $objCurrentModel, $level = 1)
	{
		$objTemplate = new BackendTemplate('be_preview_edit');
		$objTemplate->level = 'contao_preview_level_' . $level++;
		$arrResult = array();
		foreach ($arrNavigation as $module => $config)
		{
			$this->loadLanguageFile($config['table']);

			switch ($module)
			{
				case 'page':
					$arrTmp = $this->generateLink($objCurrentModel->id, $config, 'edit', '', $objCurrentModel->title);
					$arrTmp['subitems'] = $this->renderNavigation($config['subitems'], $objCurrentModel, $level);
					$arrTmp['module'] = $module;
					$arrResult[] = $arrTmp;
					break;
				case 'article':
					if (($objArticle = ArticleModel::findByPid($objCurrentModel->id)) !== null)
					{
						while ($objArticle->next())
						{
							$objArticleCurrent = $objArticle->current();
							$arrTmp = $this->generateLink($objArticleCurrent->id, $config, 'editheader', '', $objArticleCurrent->title);
							$arrTmp['subitems'] = $this->renderNavigation($config['subitems'], $objArticleCurrent, $level);
							$arrTmp['module'] = $module;
							$arrResult[] = $arrTmp;
						}
					}
					break;
				case 'content':
					if (($objContent = ContentModel::findByPid($objCurrentModel->id)) !== null)
					{
						while ($objContent->next())
						{
							$objContentCurrent = $objContent->current();
							$arrTmp = $this->generateLink($objContentCurrent->id, $config, 'edit', '', $objContentCurrent->headline);
							$arrTmp['module'] = $module;
							$arrResult[] = $arrTmp;
						}
					}
					break;
				case 'themes':
					if (($objTheme = ThemeModel::findByPk($objCurrentModel->pid)) !== null)
					{
						$arrTmp = $this->generateLink($objTheme->id, $config, 'edit', '', $objTheme->name);
						$arrTmp['module'] = $module;
						$arrResult[] = $arrTmp;
					}
					break;
				case 'style_sheet':
					break;
				case 'module':
					$arrModuleIds = deserialize($objCurrentModel->modules, true);
					foreach ($arrModuleIds as $arrModule)
					{

						$objModule = \ModuleModel::findByPk($arrModule['mod']);
						if ($objModule !== null)
						{
							$arrTmp = $this->generateLink($objModule->id, $config, 'edit', '', $objModule->name);
							$arrTmp['module'] = $module;
							$arrResult[] = $arrTmp;
						}
					}
					break;
				case 'layout':
					$objLayout = $objCurrentModel->getRelated('layout');
					if ($objLayout !== null)
					{
						$arrTmp = $this->generateLink($objLayout->id, $config, 'edit', '', $objLayout->title);
						$arrTmp['subitems'] = $this->renderNavigation($config['subitems'], $objLayout, $level);
						$arrTmp['module'] = $module;
						$arrResult[] = $arrTmp;
					}
					break;
				default;
					// not implemented -> User action | Hook
					break;
			}
		}
		$objTemplate->items = $arrResult;
		return !empty($arrResult) ? $objTemplate->parse() : '';
	}

	/**
	 * generate the link array
	 *
	 * @param int $intId
	 * @param array $arrConfig
	 * @param String $strAct
	 * @param String $strClass
	 * @param String $strLinkText
	 * @param String $strLinkTitle
	 *
	 * @return array
	 */
	protected function generateLink($intId, $arrConfig, $strAct, $strClass, $strLinkText = '', $strLinkTitle = '')
	{
		if ($strLinkText == '')
		{
			$strLinkText = sprintf($GLOBALS['TL_LANG'][$arrConfig['table']][$strAct][1], $intId);
		}
		else
		{
			$arrLinkText = deserialize($strLinkText);
			if (!empty($arrLinkText) && isset($arrLinkText['value']) && $arrLinkText['value'] != '')
			{
				$strLinkText = $arrLinkText['value'];
			}
			elseif (is_array($arrLinkText))
			{
				$strLinkText = sprintf($GLOBALS['TL_LANG'][$arrConfig['table']][$strAct][1], $intId);
			}
		}
		$strTitle = $strLinkTitle == '' ? $GLOBALS['TL_LANG'][$arrConfig['table']][$strAct][0] : $strLinkTitle;

		return array(
			'href' => Environment::get('base') . 'contao/main.php?' . $arrConfig[$strAct] . '&id=' . $intId . '&popup=1&nb=1&nm=1&rt=' . REQUEST_TOKEN,
			'link' => $strLinkText,
			'title' => $strTitle,
			'class' => $strClass,
			'onclick' => "contaoPreviewModal({'width':765,'title':'" . sprintf($GLOBALS['TL_LANG'][$arrConfig['table']][$strAct][1], $intId) . "','url':this.href});return false"
		);
	}

}
