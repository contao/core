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
 * Provide methods to handle input field "page tree".
 *
 * @property array  $rootNodes
 * @property string $fieldType
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class PageSelector extends \Widget
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
	 *
	 * @param array $arrAttributes
	 */
	public function __construct($arrAttributes=null)
	{
		$this->import('Database');
		parent::__construct($arrAttributes);
	}


	/**
	 * Generate the widget and return it as string
	 *
	 * @return string
	 */
	public function generate()
	{
		$this->import('BackendUser', 'User');

		// Store the keyword
		if (\Input::post('FORM_SUBMIT') == 'item_selector')
		{
			$this->Session->set('page_selector_search', \Input::post('keyword'));
			$this->reload();
		}

		$tree = '';
		$this->getPathNodes();
		$for = $this->Session->get('page_selector_search');
		$arrIds = array();

		// Search for a specific page
		if ($for != '')
		{
			// The keyword must not start with a wildcard (see #4910)
			if (strncmp($for, '*', 1) === 0)
			{
				$for = substr($for, 1);
			}

			// Wrap in a try catch block in case the regular expression is invalid (see #7743)
			try
			{
				$objRoot = $this->Database->prepare("SELECT id FROM tl_page WHERE CAST(title AS CHAR) REGEXP ?")
										  ->execute($for);

				if ($objRoot->numRows > 0)
				{
					// Respect existing limitations
					if (is_array($this->rootNodes))
					{
						$arrRoot = array();

						while ($objRoot->next())
						{
							// Predefined node set (see #3563)
							if (count(array_intersect($this->rootNodes, $this->Database->getParentRecords($objRoot->id, 'tl_page'))) > 0)
							{
								$arrRoot[] = $objRoot->id;
							}
						}

						$arrIds = $arrRoot;
					}
					elseif ($this->User->isAdmin)
					{
						// Show all pages to admins
						$arrIds = $objRoot->fetchEach('id');
					}
					else
					{
						$arrRoot = array();

						while ($objRoot->next())
						{
							// Show only mounted pages to regular users
							if (count(array_intersect($this->User->pagemounts, $this->Database->getParentRecords($objRoot->id, 'tl_page'))) > 0)
							{
								$arrRoot[] = $objRoot->id;
							}
						}

						$arrIds = $arrRoot;
					}
				}
			}
			catch (\Exception $e) {}

			// Build the tree
			foreach ($arrIds as $id)
			{
				$tree .= $this->renderPagetree($id, -20, false, true);
			}
		}
		else
		{
			$strNode = $this->Session->get('tl_page_picker');

			// Unset the node if it is not within the predefined node set (see #5899)
			if ($strNode > 0 && is_array($this->rootNodes))
			{
				if (!in_array($strNode, $this->Database->getChildRecords($this->rootNodes, 'tl_page')))
				{
					$this->Session->remove('tl_page_picker');
				}
			}

			// Add the breadcrumb menu
			if (\Input::get('do') != 'page')
			{
				\Backend::addPagesBreadcrumb('tl_page_picker');
			}

			// Root nodes (breadcrumb menu)
			if (!empty($GLOBALS['TL_DCA']['tl_page']['list']['sorting']['root']))
			{
				$nodes = $this->eliminateNestedPages($GLOBALS['TL_DCA']['tl_page']['list']['sorting']['root']);

				foreach ($nodes as $node)
				{
					$tree .= $this->renderPagetree($node, -20);
				}
			}

			// Predefined node set (see #3563)
			elseif (is_array($this->rootNodes))
			{
				$nodes = $this->eliminateNestedPages($this->rootNodes);

				foreach ($nodes as $node)
				{
					$tree .= $this->renderPagetree($node, -20);
				}
			}

			// Show all pages to admins
			elseif ($this->User->isAdmin)
			{
				$objPage = $this->Database->prepare("SELECT id FROM tl_page WHERE pid=? ORDER BY sorting")
										  ->execute(0);

				while ($objPage->next())
				{
					$tree .= $this->renderPagetree($objPage->id, -20);
				}
			}

			// Show only mounted pages to regular users
			else
			{
				$nodes = $this->eliminateNestedPages($this->User->pagemounts);

				foreach ($nodes as $node)
				{
					$tree .= $this->renderPagetree($node, -20);
				}
			}
		}

		// Select all checkboxes
		if ($this->fieldType == 'checkbox')
		{
			$strReset = "\n" . '    <li class="tl_folder"><div class="tl_left">&nbsp;</div> <div class="tl_right"><label for="check_all_' . $this->strId . '" class="tl_change_selected">' . $GLOBALS['TL_LANG']['MSC']['selectAll'] . '</label> <input type="checkbox" id="check_all_' . $this->strId . '" class="tl_tree_checkbox" value="" onclick="Backend.toggleCheckboxGroup(this,\'' . $this->strName . '\')"></div><div style="clear:both"></div></li>';
		}
		// Reset radio button selection
		else
		{
			$strReset = "\n" . '    <li class="tl_folder"><div class="tl_left">&nbsp;</div> <div class="tl_right"><label for="reset_' . $this->strId . '" class="tl_change_selected">' . $GLOBALS['TL_LANG']['MSC']['resetSelected'] . '</label> <input type="radio" name="' . $this->strName . '" id="reset_' . $this->strName . '" class="tl_tree_radio" value="" onfocus="Backend.getScrollOffset()"></div><div style="clear:both"></div></li>';
		}

		// Return the tree
		return '<ul class="tl_listing tree_view picker_selector'.(($this->strClass != '') ? ' ' . $this->strClass : '').'" id="'.$this->strId.'">
    <li class="tl_folder_top"><div class="tl_left">'.\Image::getHtml($GLOBALS['TL_DCA']['tl_page']['list']['sorting']['icon'] ?: 'pagemounts.gif').' '.(\Config::get('websiteTitle') ?: 'Contao Open Source CMS').'</div> <div class="tl_right">&nbsp;</div><div style="clear:both"></div></li><li class="parent" id="'.$this->strId.'_parent"><ul>'.$tree.$strReset.'
  </ul></li></ul>';
	}


	/**
	 * Generate a particular subpart of the page tree and return it as HTML string
	 *
	 * @param integer $id
	 * @param string  $strField
	 * @param integer $level
	 *
	 * @return string
	 */
	public function generateAjax($id, $strField, $level)
	{
		if (!\Environment::get('isAjaxRequest'))
		{
			return '';
		}

		$this->strField = $strField;
		$this->loadDataContainer($this->strTable);

		// Load current values
		switch ($GLOBALS['TL_DCA'][$this->strTable]['config']['dataContainer'])
		{
			case 'File':
				if (\Config::get($this->strField) != '')
				{
					$this->varValue = \Config::get($this->strField);
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
	 *
	 * @param integer $id
	 * @param integer $intMargin
	 * @param boolean $protectedPage
	 * @param boolean $blnNoRecursion
	 *
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
		if (\Input::get($flag.'tg'))
		{
			$session[$node][\Input::get($flag.'tg')] = (isset($session[$node][\Input::get($flag.'tg')]) && $session[$node][\Input::get($flag.'tg')] == 1) ? 0 : 1;
			$this->Session->setData($session);
			$this->redirect(preg_replace('/(&(amp;)?|\?)'.$flag.'tg=[^& ]*/i', '', \Environment::get('request')));
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

		$return .= "\n    " . '<li class="'.(($objPage->type == 'root') ? 'tl_folder' : 'tl_file').' toggle_select" onmouseover="Theme.hoverDiv(this, 1)" onmouseout="Theme.hoverDiv(this, 0)"><div class="tl_left" style="padding-left:'.($intMargin + $intSpacing).'px">';

		$folderAttribute = 'style="margin-left:20px"';
		$session[$node][$id] = is_numeric($session[$node][$id]) ? $session[$node][$id] : 0;
		$level = ($intMargin / $intSpacing + 1);
		$blnIsOpen = ($session[$node][$id] == 1 || in_array($id, $this->arrNodes));

		if (!empty($childs))
		{
			$folderAttribute = '';
			$img = $blnIsOpen ? 'folMinus.gif' : 'folPlus.gif';
			$alt = $blnIsOpen ? $GLOBALS['TL_LANG']['MSC']['collapseNode'] : $GLOBALS['TL_LANG']['MSC']['expandNode'];
			$return .= '<a href="'.$this->addToUrl($flag.'tg='.$id).'" title="'.specialchars($alt).'" onclick="return AjaxRequest.togglePagetree(this,\''.$xtnode.'_'.$id.'\',\''.$this->strField.'\',\''.$this->strName.'\','.$level.')">'.\Image::getHtml($img, '', 'style="margin-right:2px"').'</a>';
		}

		// Set the protection status
		$objPage->protected = ($objPage->protected || $protectedPage);

		// Add the current page
		if (!empty($childs))
		{
			$return .= \Image::getHtml($this->getPageStatusIcon($objPage), '', $folderAttribute).' <a href="' . $this->addToUrl('node='.$objPage->id) . '" title="'.specialchars($objPage->title . ' (' . $objPage->alias . \Config::get('urlSuffix') . ')').'">'.(($objPage->type == 'root') ? '<strong>' : '').$objPage->title.(($objPage->type == 'root') ? '</strong>' : '').'</a></div> <div class="tl_right">';
		}
		else
		{
			$return .= \Image::getHtml($this->getPageStatusIcon($objPage), '', $folderAttribute).' '.(($objPage->type == 'root') ? '<strong>' : '').$objPage->title.(($objPage->type == 'root') ? '</strong>' : '').'</div> <div class="tl_right">';
		}

		// Add checkbox or radio button
		switch ($this->fieldType)
		{
			case 'checkbox':
				$return .= '<input type="checkbox" name="'.$this->strName.'[]" id="'.$this->strName.'_'.$id.'" class="tl_tree_checkbox" value="'.specialchars($id).'" onfocus="Backend.getScrollOffset()"'.static::optionChecked($id, $this->varValue).'>';
				break;

			default:
			case 'radio':
				$return .= '<input type="radio" name="'.$this->strName.'" id="'.$this->strName.'_'.$id.'" class="tl_tree_radio" value="'.specialchars($id).'" onfocus="Backend.getScrollOffset()"'.static::optionChecked($id, $this->varValue).'>';
				break;
		}

		$return .= '</div><div style="clear:both"></div></li>';

		// Begin a new submenu
		if (!empty($childs) && ($blnIsOpen || $this->Session->get('page_selector_search') != ''))
		{
			$return .= '<li class="parent" id="'.$node.'_'.$id.'"><ul class="level_'.$level.'">';

			for ($k=0, $c=count($childs); $k<$c; $k++)
			{
				$return .= $this->renderPagetree($childs[$k], ($intMargin + $intSpacing), $objPage->protected);
			}

			$return .= '</ul></li>';
		}

		return $return;
	}


	/**
	 * Get the IDs of all parent pages of the selected pages, so they are expanded automatically
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
			$arrPids = $this->Database->getParentRecords($id, 'tl_page');
			array_shift($arrPids); // the first element is the ID of the page itself
			$this->arrNodes = array_merge($this->arrNodes, $arrPids);
		}
	}
}
