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
 * Class FileSelector
 *
 * Provide methods to handle input field "file tree".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class FileSelector extends \Widget
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
	 * Extension filter
	 * @var string
	 */
	protected $strExtensions = '';

	/**
	 * Sort flag
	 * @var string
	 */
	protected $strSortFlag = '';

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
		if (\Input::post('FORM_SUBMIT') == 'item_selector')
		{
			$this->Session->set('file_selector_search', \Input::post('keyword'));
			$this->reload();
		}

		// Extension filter
		if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['extensions'] != '')
		{
			$this->strExtensions = " AND (type='folder' OR extension IN('" . implode("','", trimsplit(',', $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['extensions'])) . "'))";
		}

		// Sort descending
		if (isset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['flag']) && ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['flag'] % 2) == 0)
		{
			$this->strSortFlag = ' DESC';
		}

		$tree = '';
		$this->getPathNodes();
		$for = $this->Session->get('file_selector_search');
		$arrIds = array();

		// Search for a specific file
		if ($for != '')
		{
			// The keyword must not start with a wildcard (see #4910)
			if (strncmp($for, '*', 1) === 0)
			{
				$for = substr($for, 1);
			}

			$objRoot = $this->Database->prepare("SELECT id FROM tl_files WHERE CAST(name AS CHAR) REGEXP ?{$this->strExtensions} ORDER BY type='file', name{$this->strSortFlag}")
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
						if (count(array_intersect($this->User->filemounts, $this->Database->getParentRecords($objRoot->id, 'tl_files'))) > 0)
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
				$tree .= $this->renderFiletree($id, -20, false, true);
			}
		}
		else
		{
			// Show a custom path (see #4926)
			if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['path'] != '')
			{
				$objFolder = \FilesModel::findByPath($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['path']);

				if ($objFolder !== null)
				{
					$tree .= $this->renderFiletree($objFolder->id, -20);
				}
			}

			// Show all files to admins
			elseif ($this->User->isAdmin)
			{
				$objFile = $this->Database->prepare("SELECT id FROM tl_files WHERE pid=?{$this->strExtensions} ORDER BY type='file', name{$this->strSortFlag}")
										  ->execute(0);

				while ($objFile->next())
				{
					$tree .= $this->renderFiletree($objFile->id, -20);
				}
			}

			// Show mounted files to regular users
			else
			{
				foreach ($this->eliminateNestedPages($this->User->filemountIds, 'tl_files') as $node)
				{
					$tree .= $this->renderFiletree($node, -20);
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
    <li class="tl_folder_top"><div class="tl_left">'.$this->generateImage((($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['icon'] != '') ? $GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['icon'] : 'filemounts.gif')).' '.($GLOBALS['TL_CONFIG']['websiteTitle'] ?: 'Contao Open Source CMS').'</div> <div class="tl_right">&nbsp;</div><div style="clear:both"></div></li><li class="parent" id="'.$this->strId.'_parent"><ul>'.$tree.$strReset.'
  </ul></li></ul>';
	}


	/**
	 * Generate a particular subpart of the file tree and return it as HTML string
	 * @param integer
	 * @param string
	 * @param integer
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

		// Load the current values
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

		// Extension filter
		if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['extensions'] != '')
		{
			$this->strExtensions = " AND (type='folder' OR extension IN('" . implode("','", trimsplit(',', $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['extensions'])) . "'))";
		}

		// Sort descending
		if (isset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['flag']) && ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['flag'] % 2) == 0)
		{
			$this->strSortFlag = ' DESC';
		}

		$this->getPathNodes();

		// Load the requested nodes
		$tree = '';
		$level = $level * 20;

		$objFile = $this->Database->prepare("SELECT id FROM tl_files WHERE pid=?{$this->strExtensions} ORDER BY type='file', name{$this->strSortFlag}")
								  ->execute($id);

		while ($objFile->next())
		{
			$tree .= $this->renderFiletree($objFile->id, $level);
		}

		return $tree;
	}


	/**
	 * Recursively render the filetree
	 * @param integer
	 * @param integer
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	protected function renderFiletree($id, $intMargin, $protectedPage=false, $blnNoRecursion=false)
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

		$strWhere = (($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['files'] || $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['filesOnly']) ? "" : " AND type='folder'") . $this->strExtensions;

		// Get the current element
		$objFile = $this->Database->prepare("SELECT id, type, name, path FROM tl_files WHERE id=?$strWhere")
								  ->limit(1)
								  ->execute($id);

		// Return if there is no result
		if ($objFile->numRows < 1)
		{
			return '';
		}

		$return = '';
		$intSpacing = 20;
		$childs = array();

		// Check whether there are child records
		if (!$blnNoRecursion)
		{
			$objNodes = $this->Database->prepare("SELECT id FROM tl_files WHERE pid=?$strWhere ORDER BY type='file', name{$this->strSortFlag}")
									   ->execute($id);

			if ($objNodes->numRows)
			{
				$childs = $objNodes->fetchEach('id');
			}
		}

		$return .= "\n    " . '<li class="'.(($objFile->type == 'folder') ? 'tl_folder' : 'tl_file').'" onmouseover="Theme.hoverDiv(this, 1)" onmouseout="Theme.hoverDiv(this, 0)"><div class="tl_left" style="padding-left:'.($intMargin + $intSpacing).'px">';

		$folderAttribute = 'style="margin-left:20px"';
		$session[$node][$id] = is_numeric($session[$node][$id]) ? $session[$node][$id] : 0;
		$level = ($intMargin / $intSpacing + 1);
		$blnIsOpen = ($session[$node][$id] == 1 || in_array($id, $this->arrNodes));

		if (!empty($childs))
		{
			$folderAttribute = '';
			$img = $blnIsOpen ? 'folMinus.gif' : 'folPlus.gif';
			$alt = $blnIsOpen ? $GLOBALS['TL_LANG']['MSC']['collapseNode'] : $GLOBALS['TL_LANG']['MSC']['expandNode'];
			$return .= '<a href="'.$this->addToUrl($flag.'tg='.$id).'" title="'.specialchars($alt).'" onclick="Backend.getScrollOffset();return AjaxRequest.toggleFiletree(this,\''.$xtnode.'_'.$id.'\',\''.$this->strField.'\',\''.$this->strName.'\','.$level.')">'.$this->generateImage($img, '', 'style="margin-right:2px"').'</a>';
		}

		// Get the icon
		if ($objFile->type == 'folder')
		{
			$file = null;
			$image = !empty($childs) ? 'folderC.gif' : 'folderO.gif';
		}
		else
		{
			$file = new \File($objFile->path);
			$image = $file->icon;
		}

		$thumbnail = '';

		// Generate the thumbnail
		if ($objFile->type == 'file')
		{
			if ($file->isGdImage && $file->height > 0)
			{
				$thumbnail = ' <span class="tl_gray">('.$this->getReadableSize($file->filesize).', '.$file->width.'x'.$file->height.' px)</span>';

				if ($GLOBALS['TL_CONFIG']['thumbnails'] && $file->height <= $GLOBALS['TL_CONFIG']['gdMaxImgHeight'] && $file->width <= $GLOBALS['TL_CONFIG']['gdMaxImgWidth'])
				{
					$_height = ($file->height < 50) ? $file->height : 50;
					$_width = (($file->width * $_height / $file->height) > 400) ? 90 : '';
					$thumbnail .= '<br><img src="' . TL_FILES_URL . \Image::get($objFile->path, $_width, $_height) . '" alt="" style="margin-bottom:2px">';
				}
			}
			else
			{
				$thumbnail = ' <span class="tl_gray">('.$this->getReadableSize($file->filesize).')</span>';
			}
		}

		// Add the file name
		$return .= $this->generateImage($image, '', $folderAttribute).' <label title="'.specialchars($objFile->path).'" for="'.$this->strName.'_'.$id.'">'.(($objFile->type == 'folder') ? '<strong>' : '').$objFile->name.(($objFile->type == 'folder') ? '</strong>' : '').'</label>'.$thumbnail.'</div> <div class="tl_right">';

		// Add a checkbox or radio button
		if ($objFile->type == 'file' || !$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['filesOnly'])
		{
			$value = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['paths'] ? $objFile->path : $id;

			switch ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['fieldType'])
			{
				case 'checkbox':
					$return .= '<input type="checkbox" name="'.$this->strName.'[]" id="'.$this->strName.'_'.$id.'" class="tl_tree_checkbox" value="'.specialchars($value).'" onfocus="Backend.getScrollOffset()"'.$this->optionChecked($value, $this->varValue).'>';
					break;

				default:
				case 'radio':
					$return .= '<input type="radio" name="'.$this->strName.'" id="'.$this->strName.'_'.$id.'" class="tl_tree_radio" value="'.specialchars($value).'" onfocus="Backend.getScrollOffset()"'.$this->optionChecked($value, $this->varValue).'>';
					break;
			}
		}

		$return .= '</div><div style="clear:both"></div></li>';

		// Begin a new submenu
		if (!empty($childs) && ($blnIsOpen || $this->Session->get('file_selector_search') != ''))
		{
			$return .= '<li class="parent" id="'.$node.'_'.$id.'"><ul class="level_'.$level.'">';

			for ($k=0; $k<count($childs); $k++)
			{
				$return .= $this->renderFiletree($childs[$k], ($intMargin + $intSpacing), $objFile->protected);
			}

			$return .= '</ul></li>';
		}

		return $return;
	}


	/**
	 * Get the IDs of all parent folders of the selected files, so they are expanded automatically
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
			$arrPids = $this->Database->getParentRecords($id, 'tl_files');
			array_shift($arrPids); // the first element is the ID of the page itself
			$this->arrNodes = array_merge($this->arrNodes, $arrPids);
		}
	}
}
