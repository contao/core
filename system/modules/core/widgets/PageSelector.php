<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \Environment, \Input, \Widget;


/**
 * Class PageSelector
 *
 * Provide methods to handle input field "page tree".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class PageSelector extends Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Path nodes
	 * @var array
	 */
	protected $arrNodes = array();

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';


	/**
	 * Load the database object
	 * @param array
	 */
	public function __construct($arrAttributes=null)
	{
		$this->import('Database');
		parent::__construct($arrAttributes);
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$this->import('BackendUser', 'User');

		// Store the keyword
		if (Input::post('FORM_SUBMIT') == 'item_selector')
		{
			$this->Session->set('page_selector_search', Input::post('keyword'));
			$this->reload();
		}

		$tree = '';
		$this->getPathNodes();
		$for = $this->Session->get('page_selector_search');
		$arrIds = array();

		// Search for a specific page
		if ($for != '')
		{
			$objRoot = $this->Database->prepare("SELECT id FROM tl_page WHERE CAST(title AS CHAR) REGEXP ?")
									  ->execute($for);

			if ($objRoot->numRows > 0)
			{
				// Respect existing limitations
				if ($this->User->isAdmin)
				{
					$arrIds = $objRoot->fetchEach('id');
				}
				else
				{
					$arrRoot = array();

					while ($objRoot->next())
					{
						if (count(array_intersect($this->User->pagemounts, $this->getParentRecords($objRoot->id, 'tl_page'))) > 0)
						{
							$arrRoot[] = $objRoot->id;
						}
					}

					$arrIds = $arrRoot;
				}
			}

			// Build the tree
			foreach ($arrIds as $id)
			{
				$tree .= $this->renderPagetree($id, -20, false, true);
			}
		}
		else
		{
			// Show all pages to admins
			if ($this->User->isAdmin)
			{
				$objPage = $this->Database->prepare("SELECT id FROM tl_page WHERE pid=? ORDER BY sorting")
										  ->execute(0);

				while ($objPage->next())
				{
					$tree .= $this->renderPagetree($objPage->id, -20);
				}
			}

			// Show mounted pages to regular users
			else
			{
				foreach ($this->eliminateNestedPages($this->User->pagemounts) as $node)
				{
					$tree .= $this->renderPagetree($node, -20);
				}
			}
		}

		// Select all checkboxes
		if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['fieldType'] == 'checkbox')
		{
			$strReset = "\n" . '    <li class="tl_folder"><div class="tl_left">&nbsp;</div> <div class="tl_right"><label for="check_all_' . $this->strId . '" class="tl_change_selected">' . $GLOBALS['TL_LANG']['MSC']['selectAll'] . '</label> <input type="checkbox" id="check_all_' . $this->strId . '" class="tl_tree_checkbox" value="" onclick="Backend.toggleCheckboxGroup(this,\'' . $this->strName . '\')"></div><div style="clear:both"></div></li>';
		}
		// Reset radio button selection
		else
		{
			$strReset = "\n" . '    <li class="tl_folder"><div class="tl_left">&nbsp;</div> <div class="tl_right"><label for="reset_' . $this->strId . '" class="tl_change_selected">' . $GLOBALS['TL_LANG']['MSC']['resetSelected'] . '</label> <input type="radio" name="' . $this->strName . '" id="reset_' . $this->strName . '" class="tl_tree_radio" value="" onfocus="Backend.getScrollOffset()"></div><div style="clear:both"></div></li>';
		}

		// Return the tree
		return '<ul class="tl_listing tree_view'.(($this->strClass != '') ? ' ' . $this->strClass : '').'" id="'.$this->strId.'">
    <li class="tl_folder_top"><div class="tl_left">'.$this->generateImage($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['icon'] ?: 'pagemounts.gif').' '.($GLOBALS['TL_CONFIG']['websiteTitle'] ?: 'Contao Open Source CMS').'</div> <div class="tl_right">&nbsp;</div><div style="clear:both"></div></li><li class="parent" id="'.$this->strId.'_parent"><ul>'.$tree.$strReset.'
  </ul></li></ul>';
	}


	/**
	 * Generate a particular subpart of the page tree and return it as HTML string
	 * @param integer
	 * @param string
	 * @param integer
	 * @return string
	 */
	public function generateAjax($id, $strField, $level)
	{
		if (!Environment::get('isAjaxRequest'))
		{
			return '';
		}

		$this->strField = $strField;
		$this->loadDataContainer($this->strTable);

		// Load current values
		switch ($GLOBALS['TL_DCA'][$this->strTable]['config']['dataContainer'])
		{
			case 'File':
				if ($GLOBALS['TL_CONFIG'][$this->strField] != '')
				{
					$this->varValue = $GLOBALS['TL_CONFIG'][$this->strField];
				}
				break;

			case 'Table':
				if (!$this->Database->fieldExists($this->strField, $this->strTable))
				{
					break;
				}

				$objField = $this->Database->prepare("SELECT " . $this->strField . " FROM " . $this->strTable . " WHERE id=?")
										   ->limit(1)
										   ->execute($this->strId);

				if ($objField->numRows)
				{
					$this->varValue = deserialize($objField->{$this->strField});
				}
				break;
		}

		$this->getPathNodes();

		// Load the requested nodes
		$tree = '';
		$level = $level * 20;

		$objPage = $this->Database->prepare("SELECT id FROM tl_page WHERE pid=? ORDER BY sorting")
								  ->execute($id);

		while ($objPage->next())
		{
			$tree .= $this->renderPagetree($objPage->id, $level);
		}

		return $tree;
	}


	/**
	 * Recursively render the pagetree
	 * @param int
	 * @param integer
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	protected function renderPagetree($id, $intMargin, $protectedPage=false, $blnNoRecursion=false)
	{
		static $session;
		$session = $this->Session->getData();

		$flag = substr($this->strField, 0, 2);
		$node = 'tree_' . $this->strTable . '_' . $this->strField;
		$xtnode = 'tree_' . $this->strTable . '_' . $this->strName;

		// Get the session data and toggle the nodes
		if (Input::get($flag.'tg'))
		{
			$session[$node][Input::get($flag.'tg')] = (isset($session[$node][Input::get($flag.'tg')]) && $session[$node][Input::get($flag.'tg')] == 1) ? 0 : 1;
			$this->Session->setData($session);
			$this->redirect(preg_replace('/(&(amp;)?|\?)'.$flag.'tg=[^& ]*/i', '', Environment::get('request')));
		}

		$objPage = $this->Database->prepare("SELECT id, alias, type, protected, published, start, stop, hide, title FROM tl_page WHERE id=?")
								  ->limit(1)
								  ->execute($id);

		// Return if there is no result
		if ($objPage->numRows < 1)
		{
			return '';
		}

		$return = '';
		$intSpacing = 20;
		$childs = array();

		// Check whether there are child records
		if (!$blnNoRecursion)
		{
			$objNodes = $this->Database->prepare("SELECT id FROM tl_page WHERE pid=? ORDER BY sorting")
									   ->execute($id);

			if ($objNodes->numRows)
			{
				$childs = $objNodes->fetchEach('id');
			}
		}

		$return .= "\n    " . '<li class="'.(($objPage->type == 'root') ? 'tl_folder' : 'tl_file').'" onmouseover="Theme.hoverDiv(this, 1)" onmouseout="Theme.hoverDiv(this, 0)"><div class="tl_left" style="padding-left:'.($intMargin + $intSpacing).'px">';

		$folderAttribute = 'style="margin-left:20px"';
		$session[$node][$id] = is_numeric($session[$node][$id]) ? $session[$node][$id] : 0;
		$level = ($intMargin / $intSpacing + 1);
		$blnIsOpen = ($session[$node][$id] == 1 || in_array($id, $this->arrNodes));

		if (!empty($childs))
		{
			$folderAttribute = '';
			$img = $blnIsOpen ? 'folMinus.gif' : 'folPlus.gif';
			$alt = $blnIsOpen ? $GLOBALS['TL_LANG']['MSC']['collapseNode'] : $GLOBALS['TL_LANG']['MSC']['expandNode'];
			$return .= '<a href="'.$this->addToUrl($flag.'tg='.$id).'" title="'.specialchars($alt).'" onclick="Backend.getScrollOffset();return AjaxRequest.togglePagetree(this,\''.$xtnode.'_'.$id.'\',\''.$this->strField.'\',\''.$this->strName.'\','.$level.')">'.$this->generateImage($img, '', 'style="margin-right:2px"').'</a>';
		}

		// Add the page name
		$objPage->protected = ($objPage->protected || $protectedPage);
		$return .= $this->generateImage($this->getPageStatusIcon($objPage), '', $folderAttribute).' <label title="'.specialchars($objPage->title . ' (' . $objPage->alias . $GLOBALS['TL_CONFIG']['urlSuffix'] . ')').'" for="'.$this->strName.'_'.$id.'">'.(($objPage->type == 'root') ? '<strong>' : '').$objPage->title.(($objPage->type == 'root') ? '</strong>' : '').'</label></div> <div class="tl_right">';

		// Add checkbox or radio button
		switch ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['fieldType'])
		{
			case 'checkbox':
				$return .= '<input type="checkbox" name="'.$this->strName.'[]" id="'.$this->strName.'_'.$id.'" class="tl_tree_checkbox" value="'.specialchars($id).'" onfocus="Backend.getScrollOffset()"'.$this->optionChecked($id, $this->varValue).'>';
				break;

			default:
			case 'radio':
				$return .= '<input type="radio" name="'.$this->strName.'" id="'.$this->strName.'_'.$id.'" class="tl_tree_radio" value="'.specialchars($id).'" onfocus="Backend.getScrollOffset()"'.$this->optionChecked($id, $this->varValue).'>';
				break;
		}

		$return .= '</div><div style="clear:both"></div></li>';

		// Begin a new submenu
		if (!empty($childs) && ($blnIsOpen || $this->Session->get('page_selector_search') != ''))
		{
			$return .= '<li class="parent" id="'.$node.'_'.$id.'"><ul class="level_'.$level.'">';

			for ($k=0; $k<count($childs); $k++)
			{
				$return .= $this->renderPagetree($childs[$k], ($intMargin + $intSpacing), $objPage->protected);
			}

			$return .= '</ul></li>';
		}

		return $return;
	}


	/**
	 * Get the IDs of all parent pages of the selected pages, so they are expanded automatically
	 * @return void
	 */
	protected function getPathNodes()
	{
		if (!$this->varValue)
		{
			return;
		}

		if (!is_array($this->varValue))
		{
			$this->varValue = array($this->varValue);
		}

		foreach ($this->varValue as $id)
		{
			$arrPids = $this->getParentRecords($id, 'tl_page');
			array_shift($arrPids); // the first element is the ID of the page itself
			$this->arrNodes = array_merge($this->arrNodes, $arrPids);
		}
	}
}
