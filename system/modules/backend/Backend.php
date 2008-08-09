<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class Backend
 *
 * Provide methods to manage back end controllers.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
abstract class Backend extends Controller
{

	/**
	 * Load database object
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->import('Database');
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
			if (count($arrGroup) && in_array($module, array_keys($arrGroup)))
			{
				$arrModule =& $arrGroup[$module];
			}
		}

		$arrInactiveModules = deserialize($GLOBALS['TL_CONFIG']['inactiveModules']);

		// Check whether the module is active
		if (is_array($arrInactiveModules) && in_array($module, $arrInactiveModules))
		{
			$this->log('Attempt to access inactive back end module "' . $module . '"', 'Backend getBackendModule()', TL_ACCESS);
			$this->redirect('typolight/main.php?act=error');
		}

		$this->import('BackendUser', 'User');

		// Check whether the current user has access to the current module
		if (!in_array($module, array_keys($GLOBALS['BE_MOD']['profile'])) && !$this->User->isAdmin && !$this->User->hasAccess($module, 'modules'))
		{
			$this->log('Back end module "' . $module . '" was not allowed for user "' . $this->User->username . '"', 'Backend getBackendModule()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		$strTable = $this->Input->get('table') ? $this->Input->get('table') : $arrModule['tables'][0];
		$id = (!$this->Input->get('act') && $this->Input->get('id')) ? $this->Input->get('id') : $this->Session->get('CURRENT_ID');

		// Store the current ID in the current session
		if ($id != $this->Session->get('CURRENT_ID'))
		{
			$this->Session->set('CURRENT_ID', $id);
			$this->reload();
		}

		define('CURRENT_ID', ($this->Input->get('table') ? $id : $this->Input->get('id')));
		$this->Template->headline = $GLOBALS['TL_LANG']['MOD'][$module][0];

		// Add module style sheet
		if (array_key_exists('stylesheet', $arrModule))
		{
			$GLOBALS['TL_CSS'][] = $arrModule['stylesheet'];
		}

		// Add module javascript
		if (array_key_exists('javascript', $arrModule))
		{
			$GLOBALS['TL_JAVASCRIPT'][] = $arrModule['javascript'];
		}

		// Redirect if the current table does not belong to the current module
		if (strlen($strTable))
		{
			if (!in_array($strTable, (array) $arrModule['tables']))
			{
				$this->log('Table "' . $strTable . '" is not allowed in module "' . $module . '"', 'Backend getBackendModule()', TL_ERROR);
				$this->redirect('typolight/main.php?act=error');
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
							$GLOBALS['TL_DCA'][$strTable]['fields'][$k]['exclude'] = false;
						}
					}
				}
			}

			// Fabricate a new data container object
			if (!strlen($GLOBALS['TL_DCA'][$strTable]['config']['dataContainer']))
			{
				$this->log('Missing data container for table "' . $strTable . '"', 'Backend getBackendModule()', TL_ERROR);
				trigger_error('Could not create a data container object', E_USER_ERROR);
			}

			$dataContainer = 'DC_' . $GLOBALS['TL_DCA'][$strTable]['config']['dataContainer'];
			require(sprintf('%s/system/drivers/%s.php', TL_ROOT, $dataContainer));

			$dc = new $dataContainer($strTable);
		}

		// AJAX request
		if ($this->Input->post('isAjax'))
		{
			$this->objAjax->executePostActions($dc);
		}

		// Call module callback
		elseif ($this->classFileExists($arrModule['callback']))
		{
			$objCallback = new $arrModule['callback']($dc);
			$this->Template->main .= $objCallback->generate();
		}

		// Custom action (if key is not defined in config.php the default action will be called)
		elseif ($this->Input->get('key') && array_key_exists($this->Input->get('key'), $arrModule))
		{
			$objCallback = new $arrModule[$this->Input->get('key')][0]();
			$this->Template->main .= $objCallback->$arrModule[$this->Input->get('key')][1]($dc, $strTable, $arrModule);
		}

		// Default action
		elseif (is_object($dc))
		{
			$act = $this->Input->get('act');

			if (!strlen($act) || $act == 'paste' || $act == 'select')
			{
				$act = ($dc instanceof listable) ? 'showAll' : 'edit';
			}

			switch ($act)
			{
				case 'delete':
				case 'show':
				case 'showAll':
				case 'undo':
					if (!$dc instanceof listable)
					{
						$this->log('Data container ' . $strTable . ' is not listable', 'Backend getBackendModule()', TL_ERROR);
						trigger_error('The current data container is not listable', E_USER_ERROR);
					}
					break;

				case 'create':
				case 'cut':
				case 'copy':
				case 'move':
				case 'edit':
					if (!$dc instanceof editable)
					{
						$this->log('Data container ' . $strTable . ' is not editable', 'Backend getBackendModule()', TL_ERROR);
						trigger_error('The current data container is not editable', E_USER_ERROR);
					}
					break;
			}

			return $dc->$act();
		}

		return null;
	}


	/**
	 * Get all searchable pages and return them as array
	 * @param integer
	 * @param string
	 * @return array
	 */
	protected function getSearchablePages($pid=0, $domain='')
	{
		$time = time();

		// Get published pages
		$objPages = $this->Database->prepare("SELECT * FROM tl_page WHERE pid=? AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1 ORDER BY sorting")
								   ->execute($pid, $time, $time);

		if ($objPages->numRows < 1)
		{
			return array();
		}

		// Fallback domain
		if (!strlen($domain))
		{
			$domain = $this->Environment->base;
		}

		$arrPages = array();

		// Recursively walk through all subpages
		while($objPages->next())
		{
			// Set domain
			if ($objPages->type == 'root')
			{
				if (strlen($objPages->dns))
				{
					$domain = ($this->Environment->ssl ? 'https://' : 'http://') . $objPages->dns . TL_PATH . '/';
				}
				else
				{
					$domain = $this->Environment->base;
				}
			}

			// Add regular pages
			elseif ($objPages->type == 'regular')
			{
				// Searchable and not protected
				if (!$objPages->noSearch && (!$objPages->protected || $GLOBALS['TL_CONFIG']['indexProtected']))
				{
					// Published
					if ($objPages->published && (!$objPages->start || $objPages->start < $time) && (!$objPages->stop || $objPages->stop > $time))
					{
						$arrPages[] = $domain . $this->generateFrontendUrl($objPages->row());

						// Get articles with teaser
						$objArticle = $this->Database->prepare("SELECT * FROM tl_article WHERE pid=? AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1 AND showTeaser=1 ORDER BY sorting")
													 ->execute($objPages->id, $time, $time);

						while ($objArticle->next())
						{
							$arrPages[] = $domain . $this->generateFrontendUrl($objPages->row(), '/articles/' . ((strlen($objArticle->alias) && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objArticle->alias : $objArticle->id));
						}
					}
				}
			}

			// Get subpages
			if ((!$objPages->protected || $GLOBALS['TL_CONFIG']['indexProtected']) && ($arrSubpages = $this->getSearchablePages($objPages->id, $domain)) != false)
			{
				$arrPages = array_merge($arrPages, $arrSubpages);
			}
		}

		return $arrPages;
	}
}

?>