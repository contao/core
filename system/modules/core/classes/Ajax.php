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
 * Class Ajax
 *
 * Provide methods to handle Ajax requests.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class Ajax extends \Backend
{

	/**
	 * Ajax action
	 * @var string
	 */
	protected $strAction;

	/**
	 * Ajax id
	 * @var string
	 */
	protected $strAjaxId;

	/**
	 * Ajax key
	 * @var string
	 */
	protected $strAjaxKey;

	/**
	 * Ajax name
	 * @var string
	 */
	protected $strAjaxName;


	/**
	 * Get the current action
	 * @param string
	 * @throws \Exception
	 */
	public function __construct($strAction)
	{
		if ($strAction == '')
		{
			throw new \Exception('Missing Ajax action');
		}

		$this->strAction = $strAction;
		parent::__construct();
	}


	/**
	 * Ajax actions that do not require a data container object
	 */
	public function executePreActions()
	{
		switch ($this->strAction)
		{
			// Toggle navigation menu
			case 'toggleNavigation':
				$bemod = $this->Session->get('backend_modules');
				$bemod[\Input::post('id')] = intval(\Input::post('state'));
				$this->Session->set('backend_modules', $bemod);
				exit; break;

			// Load a navigation menu group
			case 'loadNavigation':
				$bemod = $this->Session->get('backend_modules');
				$bemod[\Input::post('id')] = intval(\Input::post('state'));
				$this->Session->set('backend_modules', $bemod);

				$this->import('BackendUser', 'User');

				$objTemplate = new \BackendTemplate('be_navigation');
				$navigation = $this->User->navigation();
				$objTemplate->modules = $navigation[\Input::post('id')]['modules'];

				echo $objTemplate->parse();
				exit; break;

			// Toggle nodes of the file or page tree
			case 'toggleStructure':
			case 'toggleFileManager':
			case 'togglePagetree':
			case 'toggleFiletree':
				$this->strAjaxId = preg_replace('/.*_([0-9a-zA-Z]+)$/', '$1', \Input::post('id'));
				$this->strAjaxKey = str_replace('_' . $this->strAjaxId, '', \Input::post('id'));

				if (\Input::get('act') == 'editAll')
				{
					$this->strAjaxKey = preg_replace('/(.*)_[0-9a-zA-Z]+$/', '$1', $this->strAjaxKey);
					$this->strAjaxName = preg_replace('/.*_([0-9a-zA-Z]+)$/', '$1', \Input::post('name'));
				}

				$nodes = $this->Session->get($this->strAjaxKey);
				$nodes[$this->strAjaxId] = intval(\Input::post('state'));
				$this->Session->set($this->strAjaxKey, $nodes);
				exit; break;

			// Load nodes of the file or page tree
			case 'loadStructure':
			case 'loadFileManager':
			case 'loadPagetree':
			case 'loadFiletree':
				$this->strAjaxId = preg_replace('/.*_([0-9a-zA-Z]+)$/', '$1', \Input::post('id'));
				$this->strAjaxKey = str_replace('_' . $this->strAjaxId, '', \Input::post('id'));

				if (\Input::get('act') == 'editAll')
				{
					$this->strAjaxKey = preg_replace('/(.*)_[0-9a-zA-Z]+$/', '$1', $this->strAjaxKey);
					$this->strAjaxName = preg_replace('/.*_([0-9a-zA-Z]+)$/', '$1', \Input::post('name'));
				}

				$nodes = $this->Session->get($this->strAjaxKey);
				$nodes[$this->strAjaxId] = intval(\Input::post('state'));
				$this->Session->set($this->strAjaxKey, $nodes);
				break;

			// Toggle the visibility of a fieldset
			case 'toggleFieldset':
				$fs = $this->Session->get('fieldset_states');
				$fs[\Input::post('table')][\Input::post('id')] = intval(\Input::post('state'));
				$this->Session->set('fieldset_states', $fs);
				exit; break;

			// Check whether the temporary directory is writeable
			case 'liveUpdate':
				$GLOBALS['TL_CONFIG']['liveUpdateId'] = \Input::post('id');
				$this->Config->update("\$GLOBALS['TL_CONFIG']['liveUpdateId']", \Input::post('id'));

				// Check whether the temp directory is writeable
				try
				{
					$objFile = new \File('system/tmp/' . md5(uniqid(mt_rand(), true)));
					$objFile->close();
					$objFile->delete();
				}
				catch (\Exception $e)
				{
					if ($e->getCode() == 0)
					{
						$this->loadLanguageFile('tl_maintenance');
						echo '<p class="tl_error">' . $GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] . '</p>';
						exit; break;
					}
				}

				// Empty live update ID
				if (!strlen(\Input::post('id')))
				{
					$this->loadLanguageFile('tl_maintenance');
					echo '<p class="tl_error">' . $GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] . '</p>';
					exit; break;
				}
				exit; break;

			// Toggle checkbox groups
			case 'toggleCheckboxGroup':
				$state = $this->Session->get('checkbox_groups');
				$state[\Input::post('id')] = intval(\Input::post('state'));
				$this->Session->set('checkbox_groups', $state);
				break;

			// HOOK: pass unknown actions to callback functions
			default:
				if (isset($GLOBALS['TL_HOOKS']['executePreActions']) && is_array($GLOBALS['TL_HOOKS']['executePreActions']))
				{
					foreach ($GLOBALS['TL_HOOKS']['executePreActions'] as $callback)
					{
						$this->import($callback[0]);
						$this->$callback[0]->$callback[1]($this->strAction);
					}
				}
				break;
		}
	}


	/**
	 * Ajax actions that do require a data container object
	 * @param \DataContainer
	 */
	public function executePostActions(\DataContainer $dc)
	{
		header('Content-Type: text/html; charset=' . $GLOBALS['TL_CONFIG']['characterSet']);

		switch ($this->strAction)
		{
			// Load nodes of the page structure tree
			case 'loadStructure':
				echo $dc->ajaxTreeView($this->strAjaxId, intval(\Input::post('level')));
				exit; break;

			// Load nodes of the file manager tree
			case 'loadFileManager':
				echo $dc->ajaxTreeView(\Input::post('folder', true), intval(\Input::post('level')));
				exit; break;

			// Load nodes of the page tree
			case 'loadPagetree':
				$arrData['strTable'] = $dc->table;
				$arrData['id'] = $this->strAjaxName ?: $dc->id;
				$arrData['name'] = \Input::post('name');

				$objWidget = new $GLOBALS['BE_FFL']['pageSelector']($arrData, $dc);
				echo $objWidget->generateAjax($this->strAjaxId, \Input::post('field'), intval(\Input::post('level')));
				exit; break;

			// Load nodes of the file tree
			case 'loadFiletree':
			case 'loadPagetree':
				$arrData['strTable'] = $dc->table;
				$arrData['id'] = $this->strAjaxName ?: $dc->id;
				$arrData['name'] = \Input::post('name');

				$objWidget = new $GLOBALS['BE_FFL']['fileSelector']($arrData, $dc);
				echo $objWidget->generateAjax($this->strAjaxId, \Input::post('field'), intval(\Input::post('level')));
				exit; break;

			// Feature/unfeature an element
			case 'toggleFeatured':
				if (class_exists($dc->table, false))
				{
					$dca = new $dc->table();

					if (method_exists($dca, 'toggleFeatured'))
					{
						$dca->toggleFeatured(\Input::post('id'), ((\Input::post('state') == 1) ? true : false));
					}
				}
				exit; break;

			// Toggle subpalettes
			case 'toggleSubpalette':
				$this->import('BackendUser', 'User');

				// Check whether the field is a selector field and allowed for regular users (thanks to Fabian Mihailowitsch) (see #4427)
				if (!is_array($GLOBALS['TL_DCA'][$dc->table]['palettes']['__selector__']) || !in_array($this->Input->post('field'), $GLOBALS['TL_DCA'][$dc->table]['palettes']['__selector__']) || ($GLOBALS['TL_DCA'][$dc->table]['fields'][$this->Input->post('field')]['exclude'] && !$this->User->hasAccess($dc->table . '::' . $this->Input->post('field'), 'alexf')))
				{
					$this->log('Field "' . $this->Input->post('field') . '" is not an allowed selector field (possible SQL injection attempt)', 'Ajax executePostActions()', TL_ERROR);
					header('HTTP/1.1 400 Bad Request');
					die('Bad Request');
				}

				if ($dc instanceof DC_Table)
				{
					if (\Input::get('act') == 'editAll')
					{
						$this->strAjaxId = preg_replace('/.*_([0-9a-zA-Z]+)$/', '$1', \Input::post('id'));
						$this->Database->prepare("UPDATE " . $dc->table . " SET " . \Input::post('field') . "='" . (intval(\Input::post('state') == 1) ? 1 : '') . "' WHERE id=?")->execute($this->strAjaxId);

						if (\Input::post('load'))
						{
							echo $dc->editAll($this->strAjaxId, \Input::post('id'));
						}
					}
					else
					{
						$this->Database->prepare("UPDATE " . $dc->table . " SET " . \Input::post('field') . "='" . (intval(\Input::post('state') == 1) ? 1 : '') . "' WHERE id=?")->execute($dc->id);

						if (\Input::post('load'))
						{
							echo $dc->edit(false, \Input::post('id'));
						}
					}
				}
				elseif ($dc instanceof \DC_File)
				{
					$val = (intval(\Input::post('state') == 1) ? true : false);
					$this->Config->update("\$GLOBALS['TL_CONFIG']['".\Input::post('field')."']", $val);

					if (\Input::post('load'))
					{
						$GLOBALS['TL_CONFIG'][\Input::post('field')] = $val;
						echo $dc->edit(false, \Input::post('id'));
					}
				}
				exit; break;

			// HOOK: pass unknown actions to callback functions
			default:
				if (isset($GLOBALS['TL_HOOKS']['executePostActions']) && is_array($GLOBALS['TL_HOOKS']['executePostActions']))
				{
					foreach ($GLOBALS['TL_HOOKS']['executePostActions'] as $callback)
					{
						$this->import($callback[0]);
						$this->$callback[0]->$callback[1]($this->strAction, $dc);
					}
				}
				exit; break;
		}
	}
}
