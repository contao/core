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
 * Provide methods to manage back end controllers.
 *
 * @property \Ajax $objAjax
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
	 * Return the current theme as string
	 *
	 * @return string The name of the theme
	 */
	public static function getTheme()
	{
		if (\Config::get('coreOnlyMode'))
		{
			return 'default'; // see #6505
		}

		$theme = \Config::get('backendTheme');

		if ($theme != '' && $theme != 'default' && is_dir(TL_ROOT . '/system/themes/' . $theme))
		{
			return $theme;
		}

		return 'default';
	}


	/**
	 * Return the back end themes as array
	 *
	 * @return array An array of available back end themes
	 */
	public static function getThemes()
	{
		$arrReturn = array();
		$arrThemes = scan(TL_ROOT . '/system/themes');

		foreach ($arrThemes as $strTheme)
		{
			if (strncmp($strTheme, '.', 1) === 0 || !is_dir(TL_ROOT . '/system/themes/' . $strTheme))
			{
				continue;
			}

			$arrReturn[$strTheme] = $strTheme;
		}

		return $arrReturn;
	}


	/**
	 * Return the TinyMCE language
	 *
	 * @return string
	 */
	public static function getTinyMceLanguage()
	{
		$lang = $GLOBALS['TL_LANGUAGE'];

		if ($lang == '')
		{
			return 'en';
		}

		$lang = str_replace('-', '_', $lang);

		// The translation exists
		if (file_exists(TL_ROOT . '/assets/tinymce4/langs/' . $lang . '.js'))
		{
			return $lang;
		}

		if (($short = substr($GLOBALS['TL_LANGUAGE'], 0, 2)) != $lang)
		{
			// Try the short tag, e.g. "de" instead of "de_CH"
			if (file_exists(TL_ROOT . '/assets/tinymce4/langs/' . $short . '.js'))
			{
				return $short;
			}
		}
		elseif (($long = $short . '_' . strtoupper($short)) != $lang)
		{
			// Try the long tag, e.g. "fr_FR" instead of "fr" (see #6952)
			if (file_exists(TL_ROOT . '/assets/tinymce4/langs/' . $long . '.js'))
			{
				return $long;
			}
		}

		// Fallback to English
		return 'en';
	}


	/**
	 * Validate an ACE type
	 *
	 * @param string $type
	 *
	 * @return string
	 */
	public static function getAceType($type)
	{
		switch ($type)
		{
			case 'css':
			case 'diff':
			case 'html':
			case 'ini':
			case 'java':
			case 'json':
			case 'less':
			case 'php':
			case 'scss':
			case 'mysql':
			case 'sql':
			case 'xml':
			case 'yaml':
				return $type;
				break;

			case 'js':
			case 'javascript':
				return 'javascript';
				break;

			case 'md':
			case 'markdown':
				return 'markdown';
				break;

			case 'cgi':
			case 'pl':
				return 'perl';
				break;

			case 'py':
				return 'python';
				break;

			case 'txt':
				return 'text';
				break;

			case 'c': case 'cc': case 'cpp': case 'c++':
			case 'h': case 'hh': case 'hpp': case 'h++':
				return 'c_cpp';
				break;

			case 'html5':
			case 'xhtml':
				return 'php';
				break;

			case 'svg':
			case 'svgz':
				return 'xml';
				break;

			default:
				return 'text';
				break;
		}
	}


	/**
	 * Return a list of TinyMCE templates as JSON string
	 *
	 * @return string
	 */
	public static function getTinyTemplates()
	{
		$strDir = \Config::get('uploadPath') . '/tiny_templates';

		if (!is_dir(TL_ROOT . '/' . $strDir))
		{
			return '';
		}

		$arrFiles = array();
		$arrTemplates = scan(TL_ROOT . '/' . $strDir);

		foreach ($arrTemplates as $strFile)
		{
			if (strncmp('.', $strFile, 1) !== 0 && is_file(TL_ROOT . '/' . $strDir . '/' . $strFile))
			{
				$arrFiles[] = '{ title: "' . $strFile . '", url: "' . $strDir . '/' . $strFile . '" }';
			}
		}

		return implode(",\n", $arrFiles) . "\n";
	}


	/**
	 * Add the request token to the URL
	 *
	 * @param string  $strRequest
	 * @param boolean $blnAddRef
	 * @param array   $arrUnset
	 *
	 * @return string
	 */
	public static function addToUrl($strRequest, $blnAddRef=true, $arrUnset=array())
	{
		// Unset the "no back button" flag
		$arrUnset[] = 'nb';

		return parent::addToUrl($strRequest . (($strRequest != '') ? '&amp;' : '') . 'rt=' . REQUEST_TOKEN, $blnAddRef, $arrUnset);
	}


	/**
	 * Handle "runonce" files
	 *
	 * @throws \Exception
	 */
	protected function handleRunOnce()
	{
		$this->import('Files');
		$arrFiles = array('system/runonce.php');

		// Always scan all folders and not just the active modules (see #4200)
		foreach (scan(TL_ROOT . '/system/modules') as $strModule)
		{
			if (strncmp($strModule, '.', 1) === 0 || !is_dir(TL_ROOT . '/system/modules/' . $strModule))
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

				$this->log("File $strFile ran once and has then been removed successfully", __METHOD__, TL_GENERAL);
			}
		}
	}


	/**
	 * Open a back end module and return it as HTML
	 *
	 * @param string $module
	 *
	 * @return string
	 */
	protected function getBackendModule($module)
	{
		$arrModule = array();

		foreach ($GLOBALS['BE_MOD'] as &$arrGroup)
		{
			if (isset($arrGroup[$module]))
			{
				$arrModule =& $arrGroup[$module];
				break;
			}
		}

		$arrInactiveModules = \ModuleLoader::getDisabled();

		// Check whether the module is active
		if (is_array($arrInactiveModules) && in_array($module, $arrInactiveModules))
		{
			$this->log('Attempt to access the inactive back end module "' . $module . '"', __METHOD__, TL_ACCESS);
			$this->redirect('contao/main.php?act=error');
		}

		$this->import('BackendUser', 'User');

		// Dynamically add the "personal data" module (see #4193)
		if (\Input::get('do') == 'login')
		{
			$arrModule = array('tables'=>array('tl_user'), 'callback'=>'ModuleUser');
		}

		// Check whether the current user has access to the current module
		elseif ($module != 'undo' && !$this->User->hasAccess($module, 'modules'))
		{
			$this->log('Back end module "' . $module . '" was not allowed for user "' . $this->User->username . '"', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$arrTables = (array) $arrModule['tables'];
		$strTable = \Input::get('table') ?: $arrTables[0];
		$id = (!\Input::get('act') && \Input::get('id')) ? \Input::get('id') : $this->Session->get('CURRENT_ID');

		// Store the current ID in the current session
		if ($id != $this->Session->get('CURRENT_ID'))
		{
			$this->Session->set('CURRENT_ID', $id);
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
			if (!in_array($strTable, $arrTables))
			{
				$this->log('Table "' . $strTable . '" is not allowed in module "' . $module . '"', __METHOD__, TL_ERROR);
				$this->redirect('contao/main.php?act=error');
			}

			// Load the language and DCA file
			\System::loadLanguageFile($strTable);
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
				$this->log('Missing data container for table "' . $strTable . '"', __METHOD__, TL_ERROR);
				trigger_error('Could not create a data container object', E_USER_ERROR);
			}

			$dataContainer = 'DC_' . $GLOBALS['TL_DCA'][$strTable]['config']['dataContainer'];

			/** @var \DataContainer $dc */
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
			/** @var \Module $objCallback */
			$objCallback = new $arrModule['callback']($dc);

			$this->Template->main .= $objCallback->generate();
		}

		// Custom action (if key is not defined in config.php the default action will be called)
		elseif (\Input::get('key') && isset($arrModule[\Input::get('key')]))
		{
			$objCallback = new $arrModule[\Input::get('key')][0]();
			$this->Template->main .= $objCallback->$arrModule[\Input::get('key')][1]($dc);

			// Add the name of the parent element
			if (isset($_GET['table']) && in_array(\Input::get('table'), $arrTables) && \Input::get('table') != $arrTables[0])
			{
				if ($GLOBALS['TL_DCA'][$strTable]['config']['ptable'] != '')
				{
					$objRow = $this->Database->prepare("SELECT * FROM " . $GLOBALS['TL_DCA'][$strTable]['config']['ptable'] . " WHERE id=?")
											 ->limit(1)
											 ->execute(CURRENT_ID);

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
						$this->log('Data container ' . $strTable . ' is not listable', __METHOD__, TL_ERROR);
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
						$this->log('Data container ' . $strTable . ' is not editable', __METHOD__, TL_ERROR);
						trigger_error('The current data container is not editable', E_USER_ERROR);
					}
					break;
			}

			$strFirst = null;
			$strSecond = null;

			// Handle child child tables (e.g. tl_style)
			if (isset($GLOBALS['TL_DCA'][$strTable]['config']['ptable']))
			{
				$ptable = $GLOBALS['TL_DCA'][$strTable]['config']['ptable'];

				if (in_array($ptable, $arrTables))
				{
					$this->loadDataContainer($ptable);

					if (isset($GLOBALS['TL_DCA'][$ptable]['config']['ptable']))
					{
						$ftable = $GLOBALS['TL_DCA'][$ptable]['config']['ptable'];

						if (in_array($ftable, $arrTables))
						{
							$strFirst = $ftable;
							$strSecond = $ptable;
						}
					}
				}
			}

			// Build the breadcrumb trail
			if ($strFirst !== null && $strSecond !== null)
			{
				if (!isset($_GET['act']) || \Input::get('act') == 'paste' && \Input::get('mode') == 'create' || \Input::get('act') == 'select' || \Input::get('act') == 'editAll' || \Input::get('act') == 'overrideAll')
				{
					if ($strTable == $strSecond)
					{
						$strQuery = "SELECT * FROM $strFirst WHERE id=?";
					}
					else
					{
						$strQuery = "SELECT * FROM $strFirst WHERE id=(SELECT pid FROM $strSecond WHERE id=?)";
					}
				}
				else
				{
					if ($strTable == $strSecond)
					{
						$strQuery = "SELECT * FROM $strFirst WHERE id=(SELECT pid FROM $strSecond WHERE id=?)";
					}
					else
					{
						$strQuery = "SELECT * FROM $strFirst WHERE id=(SELECT pid FROM $strSecond WHERE id=(SELECT pid FROM $strTable WHERE id=?))";
					}
				}

				// Add the first level name
				$objRow = $this->Database->prepare($strQuery)
										 ->limit(1)
										 ->execute($dc->id);

				if ($objRow->title != '')
				{
					$this->Template->headline .= ' » ' . $objRow->title;
				}
				elseif ($objRow->name != '')
				{
					$this->Template->headline .= ' » ' . $objRow->name;
				}

				$this->Template->headline .= ' » ' . $GLOBALS['TL_LANG']['MOD'][$strSecond];

				// Add the second level name
				$objRow = $this->Database->prepare("SELECT * FROM $strSecond WHERE id=?")
										 ->limit(1)
										 ->execute(CURRENT_ID);

				if ($objRow->title != '')
				{
					$this->Template->headline .= ' » ' . $objRow->title;
				}
				elseif ($objRow->name != '')
				{
					$this->Template->headline .= ' » ' . $objRow->name;
				}
			}
			else
			{
				// Add the name of the parent element
				if ($strTable && in_array($strTable, $arrTables) && $strTable != $arrTables[0])
				{
					if ($GLOBALS['TL_DCA'][$strTable]['config']['ptable'] != '')
					{
						$objRow = $this->Database->prepare("SELECT * FROM " . $GLOBALS['TL_DCA'][$strTable]['config']['ptable'] . " WHERE id=?")
												 ->limit(1)
												 ->execute(CURRENT_ID);

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
				if ($strTable && isset($GLOBALS['TL_LANG']['MOD'][$strTable]))
				{
					$this->Template->headline .= ' » ' . $GLOBALS['TL_LANG']['MOD'][$strTable];
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
			else
			{
				if (\Input::get('id'))
				{
					if (\Input::get('do') == 'files' || \Input::get('do') == 'tpl_editor')
					{
						$this->Template->headline .= ' » ' . \Input::get('id');
					}
					elseif (is_array($GLOBALS['TL_LANG'][$strTable][$act]))
					{
						$this->Template->headline .= ' » ' . sprintf($GLOBALS['TL_LANG'][$strTable][$act][1], \Input::get('id'));
					}
				}
				elseif (\Input::get('pid'))
				{
					if (\Input::get('do') == 'files' || \Input::get('do') == 'tpl_editor')
					{
						$this->Template->headline .= ' » ' . \Input::get('pid');
					}
					elseif (is_array($GLOBALS['TL_LANG'][$strTable][$act]))
					{
						$this->Template->headline .= ' » ' . sprintf($GLOBALS['TL_LANG'][$strTable][$act][1], \Input::get('pid'));
					}
				}
			}

			return $dc->$act();
		}

		return null;
	}


	/**
	 * Get all searchable pages and return them as array
	 *
	 * @param integer $pid
	 * @param string  $domain
	 * @param boolean $blnIsSitemap
	 * @param string  $strLanguage
	 *
	 * @return array
	 */
	public static function findSearchablePages($pid=0, $domain='', $blnIsSitemap=false, $strLanguage='')
	{
		$time = \Date::floorToMinute();
		$objDatabase = \Database::getInstance();

		// Get published pages
		$objPages = $objDatabase->prepare("SELECT * FROM tl_page WHERE pid=? AND (start='' OR start<='$time') AND (stop='' OR stop>'" . ($time + 60) . "') AND published='1' ORDER BY sorting")
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
		while ($objPages->next())
		{
			// Set domain
			if ($objPages->type == 'root')
			{
				if ($objPages->dns != '')
				{
					$domain = ($objPages->useSSL ? 'https://' : 'http://') . $objPages->dns . TL_PATH . '/';
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
				if ((!$objPages->noSearch || $blnIsSitemap) && (!$objPages->protected || \Config::get('indexProtected') && (!$blnIsSitemap || $objPages->sitemap == 'map_always')) && (!$blnIsSitemap || $objPages->sitemap != 'map_never'))
				{
					// Published
					if ($objPages->published && ($objPages->start == '' || $objPages->start <= $time) && ($objPages->stop == '' || $objPages->stop > ($time + 60)))
					{
						$arrPages[] = $domain . static::generateFrontendUrl($objPages->row(), null, $strLanguage);

						// Get articles with teaser
						$objArticle = $objDatabase->prepare("SELECT * FROM tl_article WHERE pid=? AND (start='' OR start<='$time') AND (stop='' OR stop>'" . ($time + 60) . "') AND published='1' AND showTeaser='1' ORDER BY sorting")
												  ->execute($objPages->id);

						while ($objArticle->next())
						{
							$arrPages[] = $domain . static::generateFrontendUrl($objPages->row(), '/articles/' . (($objArticle->alias != '' && !\Config::get('disableAlias')) ? $objArticle->alias : $objArticle->id), $strLanguage);
						}
					}
				}
			}

			// Get subpages
			if ((!$objPages->protected || \Config::get('indexProtected')) && ($arrSubpages = static::findSearchablePages($objPages->id, $domain, $blnIsSitemap, $strLanguage)) != false)
			{
				$arrPages = array_merge($arrPages, $arrSubpages);
			}
		}

		return $arrPages;
	}


	/**
	 * Add a breadcrumb menu to the page tree
	 *
	 * @param string $strKey
	 *
	 * @throws \RuntimeException
	 */
	public static function addPagesBreadcrumb($strKey='tl_page_node')
	{
		$objSession = \Session::getInstance();

		// Set a new node
		if (isset($_GET['node']))
		{
			// Check the path (thanks to Arnaud Buchoux)
			if (\Validator::isInsecurePath(\Input::get('node', true)))
			{
				throw new \RuntimeException('Insecure path ' . \Input::get('node', true));
			}

			$objSession->set($strKey, \Input::get('node', true));
			\Controller::redirect(preg_replace('/&node=[^&]*/', '', \Environment::get('request')));
		}

		$intNode = $objSession->get($strKey);

		if ($intNode < 1)
		{
			return;
		}

		// Check the path (thanks to Arnaud Buchoux)
		if (\Validator::isInsecurePath($intNode))
		{
			throw new \RuntimeException('Insecure path ' . $intNode);
		}

		$arrIds   = array();
		$arrLinks = array();
		$objUser  = \BackendUser::getInstance();

		// Generate breadcrumb trail
		if ($intNode)
		{
			$intId = $intNode;
			$objDatabase = \Database::getInstance();

			do
			{
				$objPage = $objDatabase->prepare("SELECT * FROM tl_page WHERE id=?")
									   ->limit(1)
									   ->execute($intId);

				if ($objPage->numRows < 1)
				{
					// Currently selected page does not exits
					if ($intId == $intNode)
					{
						$objSession->set($strKey, 0);

						return;
					}

					break;
				}

				$arrIds[] = $intId;

				// No link for the active page
				if ($objPage->id == $intNode)
				{
					$arrLinks[] = \Backend::addPageIcon($objPage->row(), '', null, '', true) . ' ' . $objPage->title;
				}
				else
				{
					$arrLinks[] = \Backend::addPageIcon($objPage->row(), '', null, '', true) . ' <a href="' . \Controller::addToUrl('node='.$objPage->id) . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectNode']).'">' . $objPage->title . '</a>';
				}

				// Do not show the mounted pages
				if (!$objUser->isAdmin && $objUser->hasAccess($objPage->id, 'pagemounts'))
				{
					break;
				}

				$intId = $objPage->pid;
			}
			while ($intId > 0 && $objPage->type != 'root');
		}

		// Check whether the node is mounted
		if (!$objUser->hasAccess($arrIds, 'pagemounts'))
		{
			$objSession->set($strKey, 0);

			\System::log('Page ID '.$intNode.' was not mounted', __METHOD__, TL_ERROR);
			\Controller::redirect('contao/main.php?act=error');
		}

		// Limit tree
		$GLOBALS['TL_DCA']['tl_page']['list']['sorting']['root'] = array($intNode);

		// Add root link
		$arrLinks[] = '<img src="' . TL_FILES_URL . 'system/themes/' . \Backend::getTheme() . '/images/pagemounts.gif" width="18" height="18" alt=""> <a href="' . \Controller::addToUrl('node=0') . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectAllNodes']).'">' . $GLOBALS['TL_LANG']['MSC']['filterAll'] . '</a>';
		$arrLinks = array_reverse($arrLinks);

		// Insert breadcrumb menu
		$GLOBALS['TL_DCA']['tl_page']['list']['sorting']['breadcrumb'] .= '

<ul id="tl_breadcrumb">
  <li>' . implode(' &gt; </li><li>', $arrLinks) . '</li>
</ul>';
	}


	/**
	 * Add an image to each page in the tree
	 *
	 * @param array          $row
	 * @param string         $label
	 * @param \DataContainer $dc
	 * @param string         $imageAttribute
	 * @param boolean        $blnReturnImage
	 * @param boolean        $blnProtected
	 *
	 * @return string
	 */
	public static function addPageIcon($row, $label, \DataContainer $dc=null, $imageAttribute='', $blnReturnImage=false, $blnProtected=false)
	{
		if ($blnProtected)
		{
			$row['protected'] = true;
		}

		$image = \Controller::getPageStatusIcon((object) $row);

		// Return the image only
		if ($blnReturnImage)
		{
			return \Image::getHtml($image, '', $imageAttribute);
		}

		// Mark root pages
		if ($row['type'] == 'root' || \Input::get('do') == 'article')
		{
			$label = '<strong>' . $label . '</strong>';
		}

		// Add the breadcrumb link
		$label = '<a href="' . \Controller::addToUrl('node='.$row['id']) . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectNode']).'">' . $label . '</a>';

		// Return the image
		return '<a href="contao/main.php?do=feRedirect&amp;page='.$row['id'].'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['view']).'"' . (($dc->table != 'tl_page') ? ' class="tl_gray"' : '') . ' target="_blank">'.\Image::getHtml($image, '', $imageAttribute).'</a> '.$label;
	}


	/**
	 * Add a breadcrumb menu to the file tree
	 *
	 * @param string $strKey
	 *
	 * @throws \RuntimeException
	 */
	public static function addFilesBreadcrumb($strKey='tl_files_node')
	{
		$objSession = \Session::getInstance();

		// Set a new node
		if (isset($_GET['node']))
		{
			// Check the path (thanks to Arnaud Buchoux)
			if (\Validator::isInsecurePath(\Input::get('node', true)))
			{
				throw new \RuntimeException('Insecure path ' . \Input::get('node', true));
			}

			$objSession->set($strKey, \Input::get('node', true));
			\Controller::redirect(preg_replace('/(&|\?)node=[^&]*/', '', \Environment::get('request')));
		}

		$strNode = $objSession->get($strKey);

		if ($strNode == '')
		{
			return;
		}

		// Check the path (thanks to Arnaud Buchoux)
		if (\Validator::isInsecurePath($strNode))
		{
			throw new \RuntimeException('Insecure path ' . $strNode);
		}

		// Currently selected folder does not exist
		if (!is_dir(TL_ROOT . '/' . $strNode))
		{
			$objSession->set($strKey, '');

			return;
		}

		$objUser  = \BackendUser::getInstance();
		$strPath  = \Config::get('uploadPath');
		$arrNodes = explode('/', preg_replace('/^' . preg_quote(\Config::get('uploadPath'), '/') . '\//', '', $strNode));
		$arrLinks = array();

		// Add root link
		$arrLinks[] = '<img src="' . TL_FILES_URL . 'system/themes/' . \Backend::getTheme() . '/images/filemounts.gif" width="18" height="18" alt=""> <a href="' . \Controller::addToUrl('node=') . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectAllNodes']).'">' . $GLOBALS['TL_LANG']['MSC']['filterAll'] . '</a>';

		// Generate breadcrumb trail
		foreach ($arrNodes as $strFolder)
		{
			$strPath .= '/' . $strFolder;

			// Do not show pages which are not mounted
			if (!$objUser->hasAccess($strPath, 'filemounts'))
			{
				continue;
			}

			// No link for the active folder
			if ($strFolder == basename($strNode))
			{
				$arrLinks[] = '<img src="' . TL_FILES_URL . 'system/themes/' . \Backend::getTheme() . '/images/folderC.gif" width="18" height="18" alt=""> ' . $strFolder;
			}
			else
			{
				$arrLinks[] = '<img src="' . TL_FILES_URL . 'system/themes/' . \Backend::getTheme() . '/images/folderC.gif" width="18" height="18" alt=""> <a href="' . \Controller::addToUrl('node='.$strPath) . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectNode']).'">' . $strFolder . '</a>';
			}
		}

		// Check whether the node is mounted
		if (!$objUser->hasAccess($strNode, 'filemounts'))
		{
			$objSession->set($strKey, '');

			\System::log('Folder ID '.$strNode.' was not mounted', __METHOD__, TL_ERROR);
			\Controller::redirect('contao/main.php?act=error');
		}

		// Limit tree
		$GLOBALS['TL_DCA']['tl_files']['list']['sorting']['root'] = array($strNode);

		// Insert breadcrumb menu
		$GLOBALS['TL_DCA']['tl_files']['list']['sorting']['breadcrumb'] .= '

<ul id="tl_breadcrumb">
  <li>' . implode(' &gt; </li><li>', $arrLinks) . '</li>
</ul>';
	}


	/**
	 * Get all allowed pages and return them as string
	 *
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
			$objPage = \PageModel::findWithDetails($page);

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
	 *
	 * @param integer $intId
	 * @param integer $level
	 *
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
	 *
	 * @param string  $strFilter
	 * @param boolean $filemount
	 *
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
			return $this->doCreateFileList(\Config::get('uploadPath'), -1, $strFilter);
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
	 *
	 * @param string  $strFolder
	 * @param integer $level
	 * @param string  $strFilter
	 *
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
			if (strncmp($strFile, '.', 1) === 0)
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
