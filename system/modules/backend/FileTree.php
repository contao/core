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
 * Class FileTree
 *
 * Provide methods to handle input field "file tree".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class FileTree extends Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Skip the field if "change selection" is not checked
	 * @param mixed
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		if (!$this->Input->post($this->strName.'_save'))
		{
			$this->mandatory = false;
			$this->blnSubmitInput = false;
		}

		return parent::validator($varInput);
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$this->import('BackendUser', 'User');

		$tree = '';
		$path = $GLOBALS['TL_CONFIG']['uploadPath'];

		// Set custom path
		if (strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['path']))
		{
			$path = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['path'];
		}

		// Start from root
		if ($this->User->isAdmin)
		{
			$tree = $this->renderFiletree(TL_ROOT . '/' . $path, 0);
		}

		// Set filemounts
		else
		{
			foreach ($this->eliminateNestedPaths($this->User->filemounts) as $node)
			{
				$tree .= $this->renderFiletree(TL_ROOT . '/' . $node, 0, true);
			}
		}

		$strReset = '';

		// Reset radio button selection
		if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['fieldType'] == 'radio')
		{
			$strReset = "\n" . '    <li class="tl_folder"><div class="tl_left">&nbsp;</div> <div class="tl_right"><label for="ctrl_'.$this->strId.'_0" class="tl_change_selected">'.$GLOBALS['TL_LANG']['MSC']['resetSelected'].'</label> <input type="radio" name="'.$this->strName.'" id="'.$this->strName.'_0" class="tl_tree_radio" value="" onfocus="Backend.getScrollOffset();" /></div><div style="clear:both;"></div></li>';
		}

		return '  <ul class="tl_listing'.(strlen($this->strClass) ? ' ' . $this->strClass : '').'" id="'.$this->strName.'">
    <li class="tl_folder_top"><div class="tl_left">'.$this->generateImage('filemounts.gif').' '.(strlen($GLOBALS['TL_LANG']['MSC']['filetree']) ? $GLOBALS['TL_LANG']['MSC']['filetree'] : 'Files directory').'</div> <div class="tl_right"><label for="ctrl_'.$this->strId.'" class="tl_change_selected">'.$GLOBALS['TL_LANG']['MSC']['changeSelected'].'</label> <input type="checkbox" name="'.$this->strName.'_save" id="ctrl_'.$this->strId.'" class="tl_tree_checkbox" value="1" onclick="Backend.showTreeBody(this, \''.$this->strId.'_parent\');" /></div><div style="clear:both;"></div></li><li class="parent" id="'.$this->strId.'_parent"><ul>'.$tree.$strReset.'
  </ul></li></ul>';
	}


	/**
	 * Generate a particular subpart of the file tree and return it as HTML string
	 * @param string
	 * @param string
	 * @param integer
	 * @return string
	 */
	public function generateAjax($folder, $strField, $level)
	{
		if (!$this->Input->post('isAjax'))
		{
			return '';
		}

		$this->strField = $strField;

		if ($GLOBALS['TL_DCA'][$this->strTable]['config']['dataContainer'] == 'File')
		{
			return $this->renderFiletree(TL_ROOT.'/'.$folder, ($level * 20));
		}

		$this->import('Database');

		if ($this->Database->fieldExists($strField, $this->strTable))
		{
			$objField = $this->Database->prepare("SELECT " . $strField . " FROM " . $this->strTable . " WHERE id=?")
									   ->limit(1)
									   ->execute($this->strId);

			if ($objField->numRows)
			{
				$this->varValue = deserialize($objField->$strField);
			}
		}

		return $this->renderFiletree(TL_ROOT.'/'.$folder, ($level * 20));
	}


	/**
	 * Recursively render the file tree
	 * @param string
	 * @param integer
	 * @param boolean
	 * @return string
	 */
	private function renderFiletree($path, $intMargin, $mount=false)
	{
		static $session;
		$session = $this->Session->getData();

		$flag = substr($this->strField, 0, 2);
		$node = 'tree_' . $this->strTable . '_' . $this->strField;
		$xtnode = 'tree_' . $this->strTable . '_' . $this->strName;

		// Get session data and toggle nodes
		if ($this->Input->get($flag.'tg'))
		{
			$session[$node][$this->Input->get($flag.'tg')] = (isset($session[$node][$this->Input->get($flag.'tg')]) && $session[$node][$this->Input->get($flag.'tg')] == 1) ? 0 : 1;
			$this->Session->setData($session);

			$this->redirect(preg_replace('/(&(amp;)?|\?)'.$flag.'tg=[^& ]*/i', '', $this->Environment->request));
		}

		$return = '';
		$intSpacing = 20;
		$files = array();
		$folders = array();
		$level = ($intMargin / $intSpacing + 1);

		// Mount folder
		if ($mount)
		{
			$folders = array($path);
		}

		// Scan directory and sort the result
		else
		{
			foreach (scan($path) as $v)
			{
				if (!is_dir($path.'/'.$v))
				{
					$files[] = $path.'/'.$v;
					continue;
				}

				if (substr($v, 0, 1) != '.')
				{
					$folders[] = $path.'/'.$v;
				}
			}
		}

		natcasesort($folders);
		$folders = array_values($folders);

		natcasesort($files);
		$files = array_values($files);

		$folderClass = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['files'] ? 'tl_folder' : 'tl_file';

		// Process folders
		for ($f=0; $f<count($folders); $f++)
		{
			$countFiles = 0;
			$return .= "\n    " . '<li class="'.$folderClass.'" onmouseover="Theme.hoverDiv(this, 1);" onmouseout="Theme.hoverDiv(this, 0);"><div class="tl_left" style="padding-left:'.$intMargin.'px;">';

			// Check whether there are subfolders or files
			foreach (scan($folders[$f]) as $v)
			{
				if (is_dir($folders[$f].'/'.$v) || $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['files'])
				{
					$countFiles++;
				}
			}

			$folderAttribute = 'style="margin-left:20px;"';
			$session[$node][md5($folders[$f])] = is_numeric($session[$node][md5($folders[$f])]) ? $session[$node][md5($folders[$f])] : 0;
			$currentFolder = str_replace(TL_ROOT.'/', '', $folders[$f]);
			$tid = md5($folders[$f]);

			// Add a toggle button if there are childs
			if ($countFiles > 0)
			{
				$folderAttribute = '';
				$img = ($session[$node][$tid] == 1) ? 'folMinus.gif' : 'folPlus.gif';
				$return .= '<a href="'.$this->addToUrl($flag.'tg='.$tid).'" onclick="Backend.getScrollOffset(); return AjaxRequest.toggleFiletree(this, \''.$xtnode.'_'.$tid.'\', \''.$currentFolder.'\', \''.$this->strField.'\', \''.$this->strName.'\', '.$level.');">'.$this->generateImage($img, '', 'style="margin-right:2px;"').'</a>';
			}

			$folderImg = ($session[$node][$tid] == 1 && $countFiles > 0) ? 'folderO.gif' : 'folderC.gif';
			$folderLabel = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['files'] ? '<strong>'.basename($currentFolder).'</strong>' : basename($currentFolder);

			// Add the current folder
			$return .= $this->generateImage($folderImg, '', $folderAttribute).' <label for="'.$this->strName.'_'.md5($currentFolder).'">'.$folderLabel.'</label></div> <div class="tl_right">';

			// Prevent folder selection
			if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['filesOnly'])
			{
				$return .= '&nbsp;';
			}

			// Add checkbox or radio button
			else
			{
				switch ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['fieldType'])
				{
					case 'checkbox':
						$return .= '<input type="checkbox" name="'.$this->strName.'[]" id="'.$this->strName.'_'.md5($currentFolder).'" class="tl_tree_checkbox" value="'.specialchars($currentFolder).'" onfocus="Backend.getScrollOffset();"'.$this->optionChecked($currentFolder, $this->varValue).' />';
						break;

					case 'radio':
						$return .= '<input type="radio" name="'.$this->strName.'" id="'.$this->strName.'_'.md5($currentFolder).'" class="tl_tree_radio" value="'.specialchars($currentFolder).'" onfocus="Backend.getScrollOffset();"'.$this->optionChecked($currentFolder, $this->varValue).' />';
						break;
				}
			}

			$return .= '</div><div style="clear:both;"></div></li>';

			// Call next node
			if ($countFiles > 0 && $session[$node][$tid] == 1)
			{
				$return .= '<li class="parent" id="'.$xtnode.'_'.$tid.'"><ul class="level_'.$level.'">';
				$return .= $this->renderFiletree($folders[$f], ($intMargin + $intSpacing));
				$return .= '</ul></li>';
			}
		}

		// Process files
		if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['files'])
		{
			$allowedExtensions = null;

			if (strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['extensions']))
			{
				$allowedExtensions = trimsplit(',', $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['extensions']);
			}

			for ($h=0; $h<count($files); $h++)
			{
				$thumbnail = '';
				$popupWidth = 400;
				$popupHeight = 204;

				$currentFile = str_replace(TL_ROOT . '/', '', $files[$h]);
				$currentEncoded = $this->urlEncode($currentFile);

				$objFile = new File($currentFile);

				// Check file extension
				if (is_array($allowedExtensions) && !in_array($objFile->extension, $allowedExtensions))
				{
					continue;
				}

				$return .= "\n    " . '<li class="tl_file" onmouseover="Theme.hoverDiv(this, 1);" onmouseout="Theme.hoverDiv(this, 0);"><div class="tl_left" style="padding-left:'.($intMargin + $intSpacing).'px;">';

				// Generate thumbnail
				if ($objFile->isGdImage && $objFile->height > 0)
				{
					$popupWidth = ($objFile->width > 400) ? ($objFile->width + 61) : 461;
					$popupHeight = ($objFile->height + 252);

					if ($GLOBALS['TL_CONFIG']['thumbnails'])
					{
						$_height = ($objFile->height < 70) ? $objFile->height : 70;
						$_width = (($objFile->width * $_height / $objFile->height) > 400) ? 90 : '';

						$thumbnail = '<br /><img src="' . $this->getImage($currentEncoded, $_width, $_height) . '" alt="" style="margin:0px 0px 2px 23px;" />';
					}
				}

				$return .= '<a href="typolight/popup.php?src='.$currentEncoded.'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['view']).'" onclick="this.blur(); Backend.openWindow(this, '.$popupWidth.', '.$popupHeight.'); return false;" >' . $this->generateImage($objFile->icon).'</a> <label for="'.$this->strName.'_'.md5($currentFile).'">'.utf8_convert_encoding(basename($currentFile), $GLOBALS['TL_CONFIG']['characterSet']).'</label>'.$thumbnail.'</div> <div class="tl_right">';

				// Add checkbox or radio button
				switch ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['fieldType'])
				{
					case 'checkbox':
						$return .= '<input type="checkbox" name="'.$this->strName.'[]" id="'.$this->strName.'_'.md5($currentFile).'" class="tl_tree_checkbox" value="'.specialchars($currentFile).'" onfocus="Backend.getScrollOffset();"'.$this->optionChecked($currentFile, $this->varValue).' />';
						break;

					case 'radio':
						$return .= '<input type="radio" name="'.$this->strName.'" id="'.$this->strName.'_'.md5($currentFile).'" class="tl_tree_radio" value="'.specialchars($currentFile).'" onfocus="Backend.getScrollOffset();"'.$this->optionChecked($currentFile, $this->varValue).' />';
						break;
				}

				$return .= '</div><div style="clear:both;"></div></li>';
			}
		}

		return $return;
	}
}

?>