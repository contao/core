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
 * Class BackendUser
 *
 * Provide methods to manage back end users.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class BackendUser extends \User
{

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

		if (!isset($_GET['act']) && !isset($_GET['key']) && !isset($_GET['token']) && !isset($_GET['state']) && \Input::get('do') != 'feRedirect' && $session['referer']['current'] != \Environment::get('requestUri'))
		{
			// Main script
			if (\Environment::get('script') == 'contao/main.php')
			{
				$session['referer']['last'] = $session['referer']['current'];
				$session['referer']['current'] = \Environment::get('requestUri');
			}
			// File manager
			elseif (\Environment::get('script') == 'contao/files.php')
			{
				$session['fileReferer']['last'] = $session['referer']['current'];
				$session['fileReferer']['current'] = \Environment::get('requestUri');
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
	 * @param string
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
		if (parent::authenticate() || \Environment::get('script') == 'contao/index.php')
		{
			return;
		}

		$strRedirect = 'contao/';

		// Redirect to the last page visited upon login
		if (\Environment::get('script') == 'contao/main.php' || \Environment::get('script') == 'contao/preview.php')
		{
			$strRedirect .= '?referer=' . base64_encode(\Environment::get('request'));
		}

		// Force JavaScript redirect on Ajax requests (IE requires an absolute link)
		if (\Environment::get('isAjaxRequest'))
		{
			echo '<script>location.replace("' . \Environment::get('base') . $strRedirect . '")</script>';
			exit;
		}

		$this->redirect($strRedirect);
	}


	/**
	 * Check whether the current user has a certain access right
	 * @param string
	 * @param array
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

		// Enable all subfolders (filemounts)
		elseif ($array == 'filemounts')
		{
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
	 * @param integer
	 * @param array
	 * @return boolean
	 */
	public function isAllowed($int, $row)
	{
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

			while (!$row['chmod'] && $pid > 0 && $objParentPage->numRows)
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
			if (!$row['chmod'])
			{
				$row['chmod'] = $GLOBALS['TL_CONFIG']['defaultChmod'];
			}
			if (!$row['cuser'])
			{
				$row['cuser'] = $GLOBALS['TL_CONFIG']['defaultUser'];
			}
			if (!$row['cgroup'])
			{
				$row['cgroup'] = $GLOBALS['TL_CONFIG']['defaultGroup'];
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
	 * Restore the original numeric file mounts (see #5083)
	 */
	public function save()
	{
		$filemounts = $this->filemounts;
		$this->arrData['filemounts'] = $this->arrFilemountIds;
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

		$GLOBALS['TL_LANGUAGE'] = $this->language;
		$GLOBALS['TL_USERNAME'] = $this->username;

		$GLOBALS['TL_CONFIG']['showHelp'] = $this->showHelp;
		$GLOBALS['TL_CONFIG']['useRTE'] = $this->useRTE;
		$GLOBALS['TL_CONFIG']['useCE'] = $this->useCE;
		$GLOBALS['TL_CONFIG']['thumbnails'] = $this->thumbnails;
		$GLOBALS['TL_CONFIG']['backendTheme'] = $this->backendTheme;

		// Inherit permissions
		$always = array('alexf');
		$depends = array('modules', 'themes', 'pagemounts', 'alpty', 'filemounts', 'fop', 'forms', 'formp');

		// HOOK: Take custom permissions
		if (is_array($GLOBALS['TL_PERMISSIONS']) && !empty($GLOBALS['TL_PERMISSIONS']))
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
		$time = time();

		foreach ((array) $this->groups as $id)
		{
			$objGroup = $this->Database->prepare("SELECT * FROM tl_user_group WHERE id=? AND disable!=1 AND (start='' OR start<$time) AND (stop='' OR stop>$time)")
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
			$this->filemounts = array_filter($this->filemounts);
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
			$objFiles = \FilesModel::findMultipleByIds($this->filemounts);

			if ($objFiles !== null)
			{
				$this->filemounts = $objFiles->fetchEach('path');
			}
		}
	}


	/**
	 * Generate the navigation menu and return it as array
	 * @param boolean
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
			$this->redirect(preg_replace('/(&(amp;)?|\?)mtg=[^& ]*/i', '', \Environment::get('request')));
		}

		$arrInactiveModules = deserialize($GLOBALS['TL_CONFIG']['inactiveModules']);
		$blnCheckInactiveModules = is_array($arrInactiveModules);

		foreach ($GLOBALS['BE_MOD'] as $strGroupName=>$arrGroupModules)
		{
			if (!empty($arrGroupModules) && ($strGroupName == 'system' || $this->hasAccess(array_keys($arrGroupModules), 'modules')))
			{
				$arrModules[$strGroupName]['icon'] = 'modMinus.gif';
				$arrModules[$strGroupName]['title'] = specialchars($GLOBALS['TL_LANG']['MSC']['collapseNode']);
				$arrModules[$strGroupName]['label'] = (($label = is_array($GLOBALS['TL_LANG']['MOD'][$strGroupName]) ? $GLOBALS['TL_LANG']['MOD'][$strGroupName][0] : $GLOBALS['TL_LANG']['MOD'][$strGroupName]) != false) ? $label : $strGroupName;
				$arrModules[$strGroupName]['href'] = $this->addToUrl('mtg=' . $strGroupName);

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
							$arrModules[$strGroupName]['modules'][$strModuleName]['icon'] = ($arrModuleConfig['icon'] != '') ? sprintf(' style="background-image:url(\'%s%s\')"', TL_ASSETS_URL, $arrModuleConfig['icon']) : '';
							$arrModules[$strGroupName]['modules'][$strModuleName]['class'] = 'navigation ' . $strModuleName;
							$arrModules[$strGroupName]['modules'][$strModuleName]['href']  = \Environment::get('script') . '?do=' . $strModuleName;

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
