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
 * Provide methods to manage back end users.
 *
 * @property boolean $isAdmin
 * @property string  $groups
 * @property array   $pagemounts
 * @property array   $filemounts
 * @property array   $filemountIds
 * @property string  $fop
 * @property string  $alexf
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class BackendUser extends \User
{

	/**
	 * Edit page flag
	 * @var string
	 */
	const CAN_EDIT_PAGE = 1;

	/**
	 * Edit page hierarchy flag
	 * @var string
	 */
	const CAN_EDIT_PAGE_HIERARCHY = 2;

	/**
	 * Delete page flag
	 * @var string
	 */
	const CAN_DELETE_PAGE = 3;

	/**
	 * Edit articles flag
	 * @var string
	 */
	const CAN_EDIT_ARTICLES = 4;

	/**
	 * Edit article hierarchy flag
	 * @var string
	 */
	const CAN_EDIT_ARTICLE_HIERARCHY = 5;

	/**
	 * Delete articles flag
	 * @var string
	 */
	const CAN_DELETE_ARTICLES = 6;

	/**
	 * Current object instance (do not remove)
	 * @var object
	 */
	protected static $objInstance;

	/**
	 * Name of the corresponding table
	 * @var string
	 */
	protected $strTable = 'tl_user';

	/**
	 * Name of the current cookie
	 * @var string
	 */
	protected $strCookie = 'BE_USER_AUTH';

	/**
	 * Allowed excluded fields
	 * @var array
	 */
	protected $alexf = array();

	/**
	 * File mount IDs
	 * @var array
	 */
	protected $arrFilemountIds;


	/**
	 * Initialize the object
	 */
	protected function __construct()
	{
		parent::__construct();

		$this->strIp = \Environment::get('ip');
		$this->strHash = \Input::cookie($this->strCookie);
	}


	/**
	 * Set the current referer and save the session
	 */
	public function __destruct()
	{
		$session = $this->Session->getData();

		if (!isset($_GET['act']) && !isset($_GET['key']) && !isset($_GET['token']) && !isset($_GET['state']) && \Input::get('do') != 'feRedirect' && !\Environment::get('isAjaxRequest'))
		{
			$key = null;

			if (TL_SCRIPT == 'contao/main.php')
			{
				$key = \Input::get('popup') ? 'popupReferer' : 'referer';
			}

			if ($key !== null)
			{
				if (!is_array($session[$key]) || !is_array($session[$key][TL_REFERER_ID]))
				{
					$session[$key][TL_REFERER_ID]['last'] = '';
				}

				while (count($session[$key]) >= 25)
				{
					array_shift($session[$key]);
				}

				$ref = \Input::get('ref');

				if ($ref != '' && isset($session[$key][$ref]))
				{
					if (!isset($session[$key][TL_REFERER_ID]))
					{
						$session[$key][TL_REFERER_ID] = array();
					}

					$session[$key][TL_REFERER_ID] = array_merge($session[$key][TL_REFERER_ID], $session[$key][$ref]);
					$session[$key][TL_REFERER_ID]['last'] = $session[$key][$ref]['current'];
				}
				elseif (count($session[$key]) > 1)
				{
					$session[$key][TL_REFERER_ID] = end($session[$key]);
				}

				$session[$key][TL_REFERER_ID]['current'] = substr(\Environment::get('requestUri'), strlen(TL_PATH) + 1);
			}
		}

		// Store the session data
		if ($this->intId != '')
		{
			$this->Database->prepare("UPDATE " . $this->strTable . " SET session=? WHERE id=?")
						   ->execute(serialize($session), $this->intId);
		}
	}


	/**
	 * Extend parent getter class and modify some parameters
	 *
	 * @param string $strKey
	 *
	 * @return mixed
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'isAdmin':
				return $this->arrData['admin'] ? true : false;
				break;

			case 'groups':
				return is_array($this->arrData['groups']) ? $this->arrData['groups'] : array($this->arrData['groups']);
				break;

			case 'pagemounts':
				return is_array($this->arrData['pagemounts']) ? $this->arrData['pagemounts'] : (($this->arrData['pagemounts'] != '') ? array($this->arrData['pagemounts']) : false);
				break;

			case 'filemounts':
				return is_array($this->arrData['filemounts']) ? $this->arrData['filemounts'] : (($this->arrData['filemounts'] != '') ? array($this->arrData['filemounts']) : false);
				break;

			case 'filemountIds':
				return $this->arrFilemountIds;
				break;

			case 'fop':
				return is_array($this->arrData['fop']) ? $this->arrData['fop'] : (($this->arrData['fop'] != '') ? array($this->arrData['fop']) : false);
				break;

			case 'alexf':
				return $this->alexf;
				break;
		}

		return parent::__get($strKey);
	}


	/**
	 * Redirect to contao/index.php if authentication fails
	 */
	public function authenticate()
	{
		// Do not redirect if authentication is successful
 		if (parent::authenticate())
 		{
 			return true;
 		}
		elseif (TL_SCRIPT == 'contao/index.php')
		{
			return false;
		}

		$strRedirect = 'contao/';

		// Redirect to the last page visited upon login
		if (TL_SCRIPT == 'contao/main.php' || TL_SCRIPT == 'contao/preview.php')
		{
			$strRedirect .= '?referer=' . base64_encode(\Environment::get('request'));
		}

		\Controller::redirect($strRedirect);

		return false;
	}


	/**
	 * Check whether the current user has a certain access right
	 *
	 * @param string $field
	 * @param array  $array
	 *
	 * @return boolean
	 */
	public function hasAccess($field, $array)
	{
		if ($this->isAdmin)
		{
			return true;
		}

		if (!is_array($field))
		{
			$field = array($field);
		}

		if (is_array($this->$array) && array_intersect($field, $this->$array))
		{
			return true;
		}
		elseif ($array == 'filemounts')
		{
			// Check the subfolders (filemounts)
			foreach ($this->filemounts as $folder)
			{
				if (preg_match('/^'. preg_quote($folder, '/') .'/i', $field[0]))
				{
					return true;
				}
			}
		}

		return false;
	}


	/**
	 * Return true if the current user is allowed to do the current operation on the current page
	 *
	 * @param integer $int
	 * @param array   $row
	 *
	 * @return boolean
	 */
	public function isAllowed($int, $row)
	{
		if ($this->isAdmin)
		{
			return true;
		}

		// Inherit CHMOD settings
		if (!$row['includeChmod'])
		{
			$pid = $row['pid'];

			$row['chmod'] = false;
			$row['cuser'] = false;
			$row['cgroup'] = false;

			$objParentPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
											->limit(1)
											->execute($pid);

			while ($row['chmod'] === false && $pid > 0 && $objParentPage->numRows)
			{
				$pid = $objParentPage->pid;

				$row['chmod'] = $objParentPage->includeChmod ? $objParentPage->chmod : false;
				$row['cuser'] = $objParentPage->includeChmod ? $objParentPage->cuser : false;
				$row['cgroup'] = $objParentPage->includeChmod ? $objParentPage->cgroup : false;

				$objParentPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
												->limit(1)
												->execute($pid);
			}

			// Set default values
			if ($row['chmod'] === false)
			{
				$row['chmod'] = \Config::get('defaultChmod');
			}
			if ($row['cuser'] === false)
			{
				$row['cuser'] = intval(\Config::get('defaultUser'));
			}
			if ($row['cgroup'] === false)
			{
				$row['cgroup'] = intval(\Config::get('defaultGroup'));
			}
		}

		// Set permissions
		$chmod = deserialize($row['chmod']);
		$chmod = is_array($chmod) ? $chmod : array($chmod);
		$permission = array('w'.$int);

		if (in_array($row['cgroup'], $this->groups))
		{
			$permission[] = 'g'.$int;
		}

		if ($row['cuser'] == $this->id)
		{
			$permission[] = 'u'.$int;
		}

		return (count(array_intersect($permission, $chmod)) > 0);
	}


	/**
	 * Return true if there is at least one allowed excluded field
	 *
	 * @param string $table
	 *
	 * @return boolean
	 */
	public function canEditFieldsOf($table)
	{
		if ($this->isAdmin)
		{
			return true;
		}

		return (count(preg_grep('/^' . preg_quote($table, '/') . '::/', $this->alexf)) > 0);
	}


	/**
	 * Restore the original numeric file mounts (see #5083)
	 */
	public function save()
	{
		$filemounts = $this->filemounts;

		if (!empty($this->arrFilemountIds))
		{
			$this->arrData['filemounts'] = $this->arrFilemountIds;
		}

		parent::save();
		$this->filemounts = $filemounts;
	}


	/**
	 * Set all user properties from a database record
	 */
	protected function setUserFromDb()
	{
		$this->intId = $this->id;

		// Unserialize values
		foreach ($this->arrData as $k=>$v)
		{
			if (!is_numeric($v))
			{
				$this->$k = deserialize($v);
			}
		}

		$GLOBALS['TL_USERNAME'] = $this->username;
		$GLOBALS['TL_LANGUAGE'] = str_replace('_', '-', $this->language);

		\Config::set('showHelp', $this->showHelp);
		\Config::set('useRTE', $this->useRTE);
		\Config::set('useCE', $this->useCE);
		\Config::set('thumbnails', $this->thumbnails);
		\Config::set('backendTheme', $this->backendTheme);

		// Inherit permissions
		$always = array('alexf');
		$depends = array('modules', 'themes', 'pagemounts', 'alpty', 'filemounts', 'fop', 'forms', 'formp');

		// HOOK: Take custom permissions
		if (!empty($GLOBALS['TL_PERMISSIONS']) && is_array($GLOBALS['TL_PERMISSIONS']))
		{
		    $depends = array_merge($depends, $GLOBALS['TL_PERMISSIONS']);
		}

		// Overwrite user permissions if only group permissions shall be inherited
		if ($this->inherit == 'group')
		{
			foreach ($depends as $field)
			{
				$this->$field = array();
			}
		}

		// Merge permissions
		$inherit = in_array($this->inherit, array('group', 'extend')) ? array_merge($always, $depends) : $always;
		$time = \Date::floorToMinute();

		foreach ((array) $this->groups as $id)
		{
			$objGroup = $this->Database->prepare("SELECT * FROM tl_user_group WHERE id=? AND disable!='1' AND (start='' OR start<='$time') AND (stop='' OR stop>'" . ($time + 60) . "')")
									   ->limit(1)
									   ->execute($id);

			if ($objGroup->numRows > 0)
			{
				foreach ($inherit as $field)
				{
					$value = deserialize($objGroup->$field, true);

					// The new page/file picker can return integers instead of arrays, so use empty() instead of is_array() and deserialize(true) here
					if (!empty($value))
					{
						$this->$field = array_merge((is_array($this->$field) ? $this->$field : (($this->$field != '') ? array($this->$field) : array())), $value);
						$this->$field = array_unique($this->$field);
					}
				}
			}
		}

		// Restore session
		if (is_array($this->session))
		{
			$this->Session->setData($this->session);
		}
		else
		{
			$this->session = array();
		}

		// Make sure pagemounts and filemounts are set!
		if (!is_array($this->pagemounts))
		{
			$this->pagemounts = array();
		}
		else
		{
			$this->pagemounts = array_filter($this->pagemounts);
		}

		if (!is_array($this->filemounts))
		{
			$this->filemounts = array();
		}
		else
		{
			$this->filemounts = array_filter($this->filemounts);
		}

		// Store the numeric file mounts
		$this->arrFilemountIds = $this->filemounts;

		// Convert the file mounts into paths (backwards compatibility)
		if (!$this->isAdmin && !empty($this->filemounts))
		{
			$objFiles = \FilesModel::findMultipleByUuids($this->filemounts);

			if ($objFiles !== null)
			{
				$this->filemounts = $objFiles->fetchEach('path');
			}
		}
	}


	/**
	 * Generate the navigation menu and return it as array
	 *
	 * @param boolean $blnShowAll
	 *
	 * @return array
	 */
	public function navigation($blnShowAll=false)
	{
		$arrModules = array();
		$session = $this->Session->getData();

		// Toggle nodes
		if (\Input::get('mtg'))
		{
			$session['backend_modules'][\Input::get('mtg')] = (isset($session['backend_modules'][\Input::get('mtg')]) && $session['backend_modules'][\Input::get('mtg')] == 0) ? 1 : 0;
			$this->Session->setData($session);
			\Controller::redirect(preg_replace('/(&(amp;)?|\?)mtg=[^& ]*/i', '', \Environment::get('request')));
		}

		$arrInactiveModules = \ModuleLoader::getDisabled();
		$blnCheckInactiveModules = is_array($arrInactiveModules);

		foreach ($GLOBALS['BE_MOD'] as $strGroupName=>$arrGroupModules)
		{
			if (!empty($arrGroupModules) && ($strGroupName == 'system' || $this->hasAccess(array_keys($arrGroupModules), 'modules')))
			{
				$arrModules[$strGroupName]['icon'] = 'modMinus.gif';
				$arrModules[$strGroupName]['title'] = specialchars($GLOBALS['TL_LANG']['MSC']['collapseNode']);
				$arrModules[$strGroupName]['label'] = (($label = is_array($GLOBALS['TL_LANG']['MOD'][$strGroupName]) ? $GLOBALS['TL_LANG']['MOD'][$strGroupName][0] : $GLOBALS['TL_LANG']['MOD'][$strGroupName]) != false) ? $label : $strGroupName;
				$arrModules[$strGroupName]['href'] = \Controller::addToUrl('mtg=' . $strGroupName);

				// Do not show the modules if the group is closed
				if (!$blnShowAll && isset($session['backend_modules'][$strGroupName]) && $session['backend_modules'][$strGroupName] < 1)
				{
					$arrModules[$strGroupName]['modules'] = false;
					$arrModules[$strGroupName]['icon'] = 'modPlus.gif';
					$arrModules[$strGroupName]['title'] = specialchars($GLOBALS['TL_LANG']['MSC']['expandNode']);
				}
				else
				{
					foreach ($arrGroupModules as $strModuleName=>$arrModuleConfig)
					{
						// Exclude inactive modules
						if ($blnCheckInactiveModules && in_array($strModuleName, $arrInactiveModules))
						{
							continue;
						}

						// Check access
						if ($strModuleName == 'undo' || $this->hasAccess($strModuleName, 'modules'))
						{
							$arrModules[$strGroupName]['modules'][$strModuleName] = $arrModuleConfig;
							$arrModules[$strGroupName]['modules'][$strModuleName]['title'] = specialchars($GLOBALS['TL_LANG']['MOD'][$strModuleName][1]);
							$arrModules[$strGroupName]['modules'][$strModuleName]['label'] = (($label = is_array($GLOBALS['TL_LANG']['MOD'][$strModuleName]) ? $GLOBALS['TL_LANG']['MOD'][$strModuleName][0] : $GLOBALS['TL_LANG']['MOD'][$strModuleName]) != false) ? $label : $strModuleName;
							$arrModules[$strGroupName]['modules'][$strModuleName]['icon'] = !empty($arrModuleConfig['icon']) ? sprintf(' style="background-image:url(\'%s%s\')"', TL_ASSETS_URL, $arrModuleConfig['icon']) : '';
							$arrModules[$strGroupName]['modules'][$strModuleName]['class'] = 'navigation ' . $strModuleName;
							$arrModules[$strGroupName]['modules'][$strModuleName]['href'] = TL_SCRIPT . '?do=' . $strModuleName . '&amp;ref=' . TL_REFERER_ID;

							// Mark the active module and its group
							if (\Input::get('do') == $strModuleName)
							{
								$arrModules[$strGroupName]['class'] = ' trail';
								$arrModules[$strGroupName]['modules'][$strModuleName]['class'] .= ' active';
							}
						}
					}
				}
			}
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getUserNavigation']) && is_array($GLOBALS['TL_HOOKS']['getUserNavigation']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getUserNavigation'] as $callback)
			{
				$this->import($callback[0]);
				$arrModules = $this->$callback[0]->$callback[1]($arrModules, $blnShowAll);
			}
		}

		return $arrModules;
	}
}
