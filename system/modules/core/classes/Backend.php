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
 * Class Backend
 *
 * Provide methods to manage back end controllers.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
abstract class Backend extends \Controller
{

	/**
	 * Load the database object
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->import('Database');
		$this->setStaticUrls();
	}


	/**
	 * Add the request token to the URL
	 * @param string
	 * @return string
	 */
	public static function addToUrl($strRequest)
	{
		return parent::addToUrl($strRequest . (($strRequest != '') ? '&amp;' : '') . 'rt=' . REQUEST_TOKEN);
	}


	/**
	 * Handle "runonce" files
	 * @throws \Exception
	 */
	protected function handleRunOnce()
	{
		$this->import('Files');
		$arrFiles = array('system/runonce.php');

		// Always scan all folders and not just the active modules (see #4200)
		foreach (scan(TL_ROOT . '/system/modules') as $strModule)
		{
			if (substr($strModule, 0, 1) == '.' || !is_dir(TL_ROOT . '/system/modules/' . $strModule))
			{
				continue;
			}

			$arrFiles[] = 'system/modules/' . $strModule . '/config/runonce.php';
		}

		// Check whether a runonce file exists
		foreach ($arrFiles as $strFile)
		{
			if (file_exists(TL_ROOT . '/' . $strFile))
			{
				try
				{
					include TL_ROOT . '/' . $strFile;
				}
				catch (\Exception $e) {}

				if (!$this->Files->delete($strFile))
				{
					throw new \Exception("The $strFile file cannot be deleted. Please remove the file manually and correct the file permission settings on your server.");
				}

				$this->log("File $strFile ran once and has then been removed successfully", 'Backend handleRunOnce()', TL_GENERAL);
			}
		}
	}


	/**
	 * Open a back end module and return it as HTML
	 * @param string
	 * @return string
	 */
	protected function getBackendModule($module)
	{
		$arrModule = array();

		foreach ($GLOBALS['BE_MOD'] as $arrGroup)
		{
			if (isset($arrGroup[$module]))
			{
				$arrModule =& $arrGroup[$module];
				break;
			}
		}

		$arrInactiveModules = deserialize($GLOBALS['TL_CONFIG']['inactiveModules']);

		// Check whether the module is active
		if (is_array($arrInactiveModules) && in_array($module, $arrInactiveModules))
		{
			$this->log('Attempt to access the inactive back end module "' . $module . '"', 'Backend getBackendModule()', TL_ACCESS);
			$this->redirect('contao/main.php?act=error');
		}

		$this->import('BackendUser', 'User');

		// Dynamically add the "personal data" module (see #4193)
		if (\Input::get('do') == 'login')
		{
			$arrModule = array('tables'=>array('tl_user'), 'callback'=>'ModuleUser');
		}

		// Check whether the current user has access to the current module
		elseif ($module != 'undo' && !$this->User->isAdmin && !$this->User->hasAccess($module, 'modules'))
		{
			$this->log('Back end module "' . $module . '" was not allowed for user "' . $this->User->username . '"', 'Backend getBackendModule()', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$strTable = \Input::get('table') ?: $arrModule['tables'][0];
		$id = (!\Input::get('act') && \Input::get('id')) ? \Input::get('id') : $this->Session->get('CURRENT_ID');

		// Store the current ID in the current session
		if ($id != $this->Session->get('CURRENT_ID'))
		{
			$this->Session->set('CURRENT_ID', $id);
			$this->reload();
		}

		define('CURRENT_ID', (\Input::get('table') ? $id : \Input::get('id')));
		$this->Template->headline = $GLOBALS['TL_LANG']['MOD'][$module][0];

		// Add the module style sheet
		if (isset($arrModule['stylesheet']))
		{
			foreach ((array) $arrModule['stylesheet'] as $stylesheet)
			{
				$GLOBALS['TL_CSS'][] = $stylesheet;
			}
		}

		// Add module javascript
		if (isset($arrModule['javascript']))
		{
			foreach ((array) $arrModule['javascript'] as $javascript)
			{
				$GLOBALS['TL_JAVASCRIPT'][] = $javascript;
			}
		}

		$dc = null;

		// Redirect if the current table does not belong to the current module
		if ($strTable != '')
		{
			if (!in_array($strTable, (array)$arrModule['tables']))
			{
				$this->log('Table "' . $strTable . '" is not allowed in module "' . $module . '"', 'Backend getBackendModule()', TL_ERROR);
				$this->redirect('contao/main.php?act=error');
			}

			// Load the language and DCA file
			$this->loadLanguageFile($strTable);
			$this->loadDataContainer($strTable);

			// Include all excluded fields which are allowed for the current user
			if ($GLOBALS['TL_DCA'][$strTable]['fields'])
			{
				foreach ($GLOBALS['TL_DCA'][$strTable]['fields'] as $k=>$v)
				{
					if ($v['exclude'])
					{
						if ($this->User->hasAccess($strTable.'::'.$k, 'alexf'))
						{
							if ($strTable == 'tl_user_group')
							{
								$GLOBALS['TL_DCA'][$strTable]['fields'][$k]['orig_exclude'] = $GLOBALS['TL_DCA'][$strTable]['fields'][$k]['exclude'];
							}

							$GLOBALS['TL_DCA'][$strTable]['fields'][$k]['exclude'] = false;
						}
					}
				}
			}

			// Fabricate a new data container object
			if ($GLOBALS['TL_DCA'][$strTable]['config']['dataContainer'] == '')
			{
				$this->log('Missing data container for table "' . $strTable . '"', 'Backend getBackendModule()', TL_ERROR);
				trigger_error('Could not create a data container object', E_USER_ERROR);
			}

			$dataContainer = 'DC_' . $GLOBALS['TL_DCA'][$strTable]['config']['dataContainer'];
			$dc = new $dataContainer($strTable, $arrModule);
		}

		// AJAX request
		if ($_POST && \Environment::get('isAjaxRequest'))
		{
			$this->objAjax->executePostActions($dc);
		}

		// Trigger the module callback
		elseif (class_exists($arrModule['callback']))
		{
			$objCallback = new $arrModule['callback']($dc);
			$this->Template->main .= $objCallback->generate();
		}

		// Custom action (if key is not defined in config.php the default action will be called)
		elseif (\Input::get('key') && isset($arrModule[\Input::get('key')]))
		{
			$objCallback = new $arrModule[\Input::get('key')][0]();
			$this->Template->main .= $objCallback->$arrModule[\Input::get('key')][1]($dc);

			// Add the name of the parent element
			if (isset($_GET['table']) && in_array(\Input::get('table'), $arrModule['tables']) && \Input::get('table') != $arrModule['tables'][0])
			{
				if ($GLOBALS['TL_DCA'][$strTable]['config']['ptable'] != '')
				{
					$objRow = $this->Database->prepare("SELECT * FROM " . $GLOBALS['TL_DCA'][$strTable]['config']['ptable'] . " WHERE id=?")
											 ->limit(1)
											 ->executeUncached(CURRENT_ID);

					if ($objRow->title != '')
					{
						$this->Template->headline .= ' » ' . $objRow->title;
					}
					elseif ($objRow->name != '')
					{
						$this->Template->headline .= ' » ' . $objRow->name;
					}
				}
			}

			// Add the name of the submodule
			$this->Template->headline .= ' » ' . sprintf($GLOBALS['TL_LANG'][$strTable][\Input::get('key')][1], \Input::get('id'));
		}

		// Default action
		elseif (is_object($dc))
		{
			$act = \Input::get('act');

			if ($act == '' || $act == 'paste' || $act == 'select')
			{
				$act = ($dc instanceof \listable) ? 'showAll' : 'edit';
			}

			switch ($act)
			{
				case 'delete':
				case 'show':
				case 'showAll':
				case 'undo':
					if (!$dc instanceof \listable)
					{
						$this->log('Data container ' . $strTable . ' is not listable', 'Backend getBackendModule()', TL_ERROR);
						trigger_error('The current data container is not listable', E_USER_ERROR);
					}
					break;

				case 'create':
				case 'cut':
				case 'cutAll':
				case 'copy':
				case 'copyAll':
				case 'move':
				case 'edit':
					if (!$dc instanceof \editable)
					{
						$this->log('Data container ' . $strTable . ' is not editable', 'Backend getBackendModule()', TL_ERROR);
						trigger_error('The current data container is not editable', E_USER_ERROR);
					}
					break;
			}

			// Correctly add the theme name in the style sheets module
			if (strncmp(\Input::get('table'), 'tl_style', 8) === 0)
			{
				if (\Input::get('table') == 'tl_style_sheet' || !isset($_GET['act']))
				{
					$objRow = $this->Database->prepare("SELECT name FROM tl_theme WHERE id=(SELECT pid FROM tl_style_sheet WHERE id=?)")
											 ->limit(1)
											 ->executeUncached(\Input::get('id'));

					$this->Template->headline .= ' » ' . $objRow->name;
					$this->Template->headline .= ' » ' . $GLOBALS['TL_LANG']['MOD']['tl_style'];

					if (\Input::get('table') == 'tl_style')
					{
						$objRow = $this->Database->prepare("SELECT name FROM tl_style_sheet WHERE id=?")
												 ->limit(1)
												 ->executeUncached(CURRENT_ID);

						$this->Template->headline .= ' » ' . $objRow->name;
					}
				}
				elseif (\Input::get('table') == 'tl_style')
				{
					$objRow = $this->Database->prepare("SELECT name FROM tl_theme WHERE id=(SELECT pid FROM tl_style_sheet WHERE id=(SELECT pid FROM tl_style WHERE id=?))")
											 ->limit(1)
											 ->executeUncached(\Input::get('id'));

					$this->Template->headline .= ' » ' . $objRow->name;
					$this->Template->headline .= ' » ' . $GLOBALS['TL_LANG']['MOD']['tl_style'];

					$objRow = $this->Database->prepare("SELECT name FROM tl_style_sheet WHERE id=?")
											 ->limit(1)
											 ->executeUncached(CURRENT_ID);

					$this->Template->headline .= ' » ' . $objRow->name;
				}
			}
			else
			{
				// Add the name of the parent element
				if (\Input::get('table') && in_array(\Input::get('table'), $arrModule['tables']) && \Input::get('table') != $arrModule['tables'][0])
				{
					if ($GLOBALS['TL_DCA'][$strTable]['config']['ptable'] != '')
					{
						$objRow = $this->Database->prepare("SELECT * FROM " . $GLOBALS['TL_DCA'][$strTable]['config']['ptable'] . " WHERE id=?")
												 ->limit(1)
												 ->executeUncached(CURRENT_ID);

						if ($objRow->title != '')
						{
							$this->Template->headline .= ' » ' . $objRow->title;
						}
						elseif ($objRow->name != '')
						{
							$this->Template->headline .= ' » ' . $objRow->name;
						}
					}
				}

				// Add the name of the submodule
				if (\Input::get('table') && isset($GLOBALS['TL_LANG']['MOD'][\Input::get('table')]))
				{
					$this->Template->headline .= ' » ' . $GLOBALS['TL_LANG']['MOD'][\Input::get('table')];
				}
			}

			// Add the current action
			if (\Input::get('act') == 'editAll')
			{
				$this->Template->headline .= ' » ' . $GLOBALS['TL_LANG']['MSC']['all'][0];
			}
			elseif (\Input::get('act') == 'overrideAll')
			{
				$this->Template->headline .= ' » ' . $GLOBALS['TL_LANG']['MSC']['all_override'][0];
			}
			elseif (is_array($GLOBALS['TL_LANG'][$strTable][$act]) && \Input::get('id'))
			{
				if (\Input::get('do') == 'files')
				{
					$this->Template->headline .= ' » ' . \Input::get('id');
				}
				else
				{
					$this->Template->headline .= ' » ' . sprintf($GLOBALS['TL_LANG'][$strTable][$act][1], \Input::get('id'));
				}
			}

			return $dc->$act();
		}

		return null;
	}


	/**
	 * Get all searchable pages and return them as array
	 * @param integer
	 * @param string
	 * @param boolean
	 * @param string
	 * @return array
	 */
	protected function findSearchablePages($pid=0, $domain='', $blnIsSitemap=false, $strLanguage='')
	{
		$time = time();

		// Get published pages
		$objPages = $this->Database->prepare("SELECT * FROM tl_page WHERE pid=? AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1 ORDER BY sorting")
								   ->execute($pid);

		if ($objPages->numRows < 1)
		{
			return array();
		}

		// Fallback domain
		if ($domain == '')
		{
			$domain = \Environment::get('base');
		}

		$arrPages = array();

		// Recursively walk through all subpages
		while($objPages->next())
		{
			// Set domain
			if ($objPages->type == 'root')
			{
				if ($objPages->dns != '')
				{
					$domain = (\Environment::get('ssl') ? 'https://' : 'http://') . $objPages->dns . TL_PATH . '/';
				}
				else
				{
					$domain = \Environment::get('base');
				}

				$strLanguage = $objPages->language;
			}

			// Add regular pages
			elseif ($objPages->type == 'regular')
			{
				// Searchable and not protected
				if (!$objPages->noSearch && (!$objPages->protected || $GLOBALS['TL_CONFIG']['indexProtected']) && (!$blnIsSitemap || $objPages->sitemap != 'map_never'))
				{
					// Published
					if ($objPages->published && (!$objPages->start || $objPages->start < $time) && (!$objPages->stop || $objPages->stop > $time))
					{
						$arrPages[] = $domain . $this->generateFrontendUrl($objPages->row(), null, $strLanguage);

						// Get articles with teaser
						$objArticle = $this->Database->prepare("SELECT * FROM tl_article WHERE pid=? AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1 AND showTeaser=1 ORDER BY sorting")
													 ->execute($objPages->id);

						while ($objArticle->next())
						{
							$arrPages[] = $domain . $this->generateFrontendUrl($objPages->row(), '/articles/' . (($objArticle->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objArticle->alias : $objArticle->id), $strLanguage);
						}
					}
				}
			}

			// Get subpages
			if ((!$objPages->protected || $GLOBALS['TL_CONFIG']['indexProtected']) && ($arrSubpages = $this->findSearchablePages($objPages->id, $domain, $blnIsSitemap, $strLanguage)) != false)
			{
				$arrPages = array_merge($arrPages, $arrSubpages);
			}
		}

		return $arrPages;
	}


	/**
	 * Get all allowed pages and return them as string
	 * @return string
	 */
	public function createPageList()
	{
		$this->import('BackendUser', 'User');

		if ($this->User->isAdmin)
		{
			return $this->doCreatePageList();
		}

		$return = '';
		$processed = array();

		foreach ($this->eliminateNestedPages($this->User->pagemounts) as $page)
		{
			$objPage = $this->getPageDetails($page);

			// Root page mounted
			if ($objPage->type == 'root')
			{
				$title = $objPage->title;
				$start = $objPage->id;
			}

			// Regular page mounted
			else
			{
				$title = $objPage->rootTitle;
				$start = $objPage->rootId;
			}

			// Do not process twice
			if (in_array($start, $processed))
			{
				continue;
			}

			// Skip websites that run under a different domain (see #2387)
			if ($objPage->domain && $objPage->domain != \Environment::get('host'))
			{
				continue;
			}

			$processed[] = $start;
			$return .= '<optgroup label="' . $title . '">' . $this->doCreatePageList($start) . '</optgroup>';
		}

		return $return;
	}


	/**
	 * Recursively get all allowed pages and return them as string
	 * @param integer
	 * @param integer
	 * @return string
	 */
	protected function doCreatePageList($intId=0, $level=-1)
	{
		$objPages = $this->Database->prepare("SELECT id, title, type, dns FROM tl_page WHERE pid=? ORDER BY sorting")
								   ->execute($intId);

		if ($objPages->numRows < 1)
		{
			return '';
		}

		++$level;
		$strOptions = '';

		while ($objPages->next())
		{
			if ($objPages->type == 'root')
			{
				// Skip websites that run under a different domain
				if ($objPages->dns && $objPages->dns != \Environment::get('host'))
				{
					continue;
				}

				$strOptions .= '<optgroup label="' . $objPages->title . '">';
				$strOptions .= $this->doCreatePageList($objPages->id, -1);
				$strOptions .= '</optgroup>';
			}
			else
			{
				$strOptions .= sprintf('<option value="{{link_url::%s}}"%s>%s%s</option>', $objPages->id, (('{{link_url::' . $objPages->id . '}}' == \Input::get('value')) ? ' selected="selected"' : ''), str_repeat(' &nbsp; &nbsp; ', $level), specialchars($objPages->title));
				$strOptions .= $this->doCreatePageList($objPages->id, $level);
			}
		}

		return $strOptions;
	}


	/**
	 * Get all allowed files and return them as string
	 * @param string
	 * @param boolean
	 * @return string
	 */
	public function createFileList($strFilter='', $filemount=false)
	{
		// Backwards compatibility
		if ($strFilter === true)
		{
			$strFilter = 'gif,jpg,jpeg,png';
		}

		$this->import('BackendUser', 'User');

		if ($this->User->isAdmin)
		{
			return $this->doCreateFileList($GLOBALS['TL_CONFIG']['uploadPath'], -1, $strFilter);
		}

		$return = '';
		$processed = array();

		// Set custom filemount
		if ($filemount)
		{
			$this->User->filemounts = array($filemount);
		}

		// Limit nodes to the filemounts of the user
		foreach ($this->eliminateNestedPaths($this->User->filemounts) as $path)
		{
			if (in_array($path, $processed))
			{
				continue;
			}

			$processed[] = $path;
			$return .= $this->doCreateFileList($path, -1, $strFilter);
		}

		return $return;
	}


	/**
	 * Recursively get all allowed files and return them as string
	 * @param integer
	 * @param integer
	 * @param string
	 * @return string
	 */
	protected function doCreateFileList($strFolder=null, $level=-1, $strFilter='')
	{
		// Backwards compatibility
		if ($strFilter === true)
		{
			$strFilter = 'gif,jpg,jpeg,png';
		}

		$arrPages = scan(TL_ROOT . '/' . $strFolder);

		// Empty folder
		if (empty($arrPages))
		{
			return '';
		}

		// Protected folder
		if (array_search('.htaccess', $arrPages) !== false)
		{
			return '';
		}

		++$level;
		$strFolders = '';
		$strFiles = '';

		// Recursively list all files and folders
		foreach ($arrPages as $strFile)
		{
			if (substr($strFile, 0, 1) == '.')
			{
				continue;
			}

			// Folders
			if (is_dir(TL_ROOT . '/' . $strFolder . '/' . $strFile))
			{
				$strFolders .=  $this->doCreateFileList($strFolder . '/' . $strFile, $level, $strFilter);
			}

			// Files
			else
			{
				// Filter images
				if ($strFilter != '' && !preg_match('/\.(' . str_replace(',', '|', $strFilter) . ')$/i', $strFile))
				{
					continue;
				}

				$strFiles .= sprintf('<option value="%s"%s>%s</option>', $strFolder . '/' . $strFile, (($strFolder . '/' . $strFile == \Input::get('value')) ? ' selected="selected"' : ''), specialchars($strFile));
			}
		}

		if (strlen($strFiles))
		{
			return '<optgroup label="' . specialchars($strFolder) . '">' . $strFiles . $strFolders . '</optgroup>';
		}

		return $strFiles . $strFolders;
	}
}
