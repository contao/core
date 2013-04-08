<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
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
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$this->import('BackendUser', 'User');
		$this->convertValuesToPaths();

		// Add the breadcrumb menu
		if (\Input::get('do') != 'files')
		{
			\Backend::addFilesBreadcrumb();
		}

		// Root nodes (breadcrumb menu)
		if (!empty($GLOBALS['TL_DCA']['tl_files']['list']['sorting']['root']))
		{
			$tree = $this->renderFiletree(TL_ROOT . '/' . $GLOBALS['TL_DCA']['tl_files']['list']['sorting']['root'][0], 0, true);
		}

		// Show a custom path (see #4926)
		elseif (isset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['path']))
		{
			$tree = $this->renderFiletree(TL_ROOT . '/' . $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['path'], 0);
		}

		// Start from root
		elseif ($this->User->isAdmin)
		{
			$tree = $this->renderFiletree(TL_ROOT . '/' . $GLOBALS['TL_CONFIG']['uploadPath'], 0);
		}

		// Show mounted files to regular users
		else
		{
			$tree = '';

			foreach ($this->eliminateNestedPaths($this->User->filemounts) as $node)
			{
				$tree .= $this->renderFiletree(TL_ROOT . '/' . $node, 0, true);
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
    <li class="tl_folder_top"><div class="tl_left">'.\Image::getHtml((($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['icon'] != '') ? $GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['icon'] : 'filemounts.gif')).' '.($GLOBALS['TL_CONFIG']['websiteTitle'] ?: 'Contao Open Source CMS').'</div> <div class="tl_right">&nbsp;</div><div style="clear:both"></div></li><li class="parent" id="'.$this->strId.'_parent"><ul>'.$tree.$strReset.'
  </ul></li></ul>';
	}


	/**
	 * Generate a particular subpart of the file tree and return it as HTML string
	 * @param integer
	 * @param string
	 * @param integer
	 * @param boolean
	 * @return string
	 */
	public function generateAjax($folder, $strField, $level, $mount=false)
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
				$this->import('Database');

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

		$this->convertValuesToPaths();
		return $this->renderFiletree(TL_ROOT . '/' . $folder, ($level * 20), $mount);
	}


	/**
	 * Recursively render the filetree
	 * @param string
	 * @param integer
	 * @param boolean
	 * @return string
	 */
	protected function renderFiletree($path, $intMargin, $mount=false)
	{
		// Invalid path
		if (!is_dir($path))
		{
			return '';
		}

		// Make sure that $this->varValue is an array (see #3369)
		if (!is_array($this->varValue))
		{
			$this->varValue = array($this->varValue);
		}

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
			$this->redirect(preg_replace('/(&(amp;)?|\?)'.$flag.'tg=[^& ]*/i', '', \Environment::get('request')));
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
				if (!is_dir($path.'/'.$v) && $v != '.DS_Store')
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

		$folderClass = ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['files'] || $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['filesOnly']) ? 'tl_folder' : 'tl_file';

		// Process folders
		for ($f=0; $f<count($folders); $f++)
		{
			$countFiles = 0;
			$return .= "\n    " . '<li class="'.$folderClass.'" onmouseover="Theme.hoverDiv(this, 1)" onmouseout="Theme.hoverDiv(this, 0)"><div class="tl_left" style="padding-left:'.$intMargin.'px">';

			// Check whether there are subfolders or files
			foreach (scan($folders[$f]) as $v)
			{
				if (is_dir($folders[$f].'/'.$v) || $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['files'] || $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['filesOnly'])
				{
					$countFiles++;
				}
			}

			$tid = md5($folders[$f]);
			$folderAttribute = 'style="margin-left:20px"';
			$session[$node][$tid] = is_numeric($session[$node][$tid]) ? $session[$node][$tid] : 0;
			$currentFolder = str_replace(TL_ROOT.'/', '', $folders[$f]);
			$blnIsOpen = ($session[$node][$tid] == 1 || count(preg_grep('/^' . preg_quote($currentFolder, '/') . '\//', $this->varValue)) > 0);

			// Add a toggle button if there are childs
			if ($countFiles > 0)
			{
				$folderAttribute = '';
				$img = $blnIsOpen ? 'folMinus.gif' : 'folPlus.gif';
				$alt = $blnIsOpen ? $GLOBALS['TL_LANG']['MSC']['collapseNode'] : $GLOBALS['TL_LANG']['MSC']['expandNode'];
				$return .= '<a href="'.$this->addToUrl($flag.'tg='.$tid).'" title="'.specialchars($alt).'" onclick="return AjaxRequest.toggleFiletree(this,\''.$xtnode.'_'.$tid.'\',\''.$currentFolder.'\',\''.$this->strField.'\',\''.$this->strName.'\','.$level.')">'.\Image::getHtml($img, '', 'style="margin-right:2px"').'</a>';
			}

			$folderImg = ($blnIsOpen && $countFiles > 0) ? 'folderO.gif' : 'folderC.gif';
			$folderLabel = ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['files'] || $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['filesOnly']) ? '<strong>'.specialchars(basename($currentFolder)).'</strong>' : specialchars(basename($currentFolder));

			// Add the current folder
			$return .= \Image::getHtml($folderImg, '', $folderAttribute).' <a href="' . $this->addToUrl('node='.$this->urlEncode($currentFolder)) . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectNode']).'">'.$folderLabel.'</a></div> <div class="tl_right">';

			// Add a checkbox or radio button
			if (!$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['filesOnly'])
			{
				switch ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['fieldType'])
				{
					case 'checkbox':
						$return .= '<input type="checkbox" name="'.$this->strName.'[]" id="'.$this->strName.'_'.md5($currentFolder).'" class="tl_tree_checkbox" value="'.specialchars($currentFolder).'" onfocus="Backend.getScrollOffset()"'.$this->optionChecked($currentFolder, $this->varValue).'>';
						break;

					case 'radio':
						$return .= '<input type="radio" name="'.$this->strName.'" id="'.$this->strName.'_'.md5($currentFolder).'" class="tl_tree_radio" value="'.specialchars($currentFolder).'" onfocus="Backend.getScrollOffset()"'.$this->optionChecked($currentFolder, $this->varValue).'>';
						break;
				}
			}

			$return .= '</div><div style="clear:both"></div></li>';

			// Call the next node
			if ($countFiles > 0 && $blnIsOpen)
			{
				$return .= '<li class="parent" id="'.$xtnode.'_'.$tid.'"><ul class="level_'.$level.'">';
				$return .= $this->renderFiletree($folders[$f], ($intMargin + $intSpacing));
				$return .= '</ul></li>';
			}
		}

		// Process files
		if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['files'] || $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['filesOnly'])
		{
			$allowedExtensions = null;

			if (strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['extensions']))
			{
				$allowedExtensions = trimsplit(',', $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['extensions']);
			}

			for ($h=0; $h<count($files); $h++)
			{
				$thumbnail = '';
				$currentFile = str_replace(TL_ROOT . '/', '', $files[$h]);
				$currentEncoded = $this->urlEncode($currentFile);

				$objFile = new \File($currentFile);

				// Check file extension
				if (is_array($allowedExtensions) && !in_array($objFile->extension, $allowedExtensions))
				{
					continue;
				}

				$return .= "\n    " . '<li class="tl_file" onmouseover="Theme.hoverDiv(this, 1)" onmouseout="Theme.hoverDiv(this, 0)"><div class="tl_left" style="padding-left:'.($intMargin + $intSpacing).'px">';

				// Generate thumbnail
				if ($objFile->isGdImage && $objFile->height > 0)
				{
					$thumbnail .= ' <span class="tl_gray">(' . $objFile->width . 'x' . $objFile->height . ')</span>';

					if ($GLOBALS['TL_CONFIG']['thumbnails'] && $objFile->height <= $GLOBALS['TL_CONFIG']['gdMaxImgHeight'] && $objFile->width <= $GLOBALS['TL_CONFIG']['gdMaxImgWidth'])
					{
						$_height = ($objFile->height < 70) ? $objFile->height : 70;
						$_width = (($objFile->width * $_height / $objFile->height) > 400) ? 90 : '';
						$thumbnail .= '<br><img src="' . TL_FILES_URL . \Image::get($currentEncoded, $_width, $_height) . '" alt="" style="margin:0px 0px 2px 23px">';
					}
				}

				$return .= \Image::getHtml($objFile->icon, $objFile->mime).' '.utf8_convert_encoding(specialchars(basename($currentFile)), $GLOBALS['TL_CONFIG']['characterSet']).$thumbnail.'</div> <div class="tl_right">';

				// Add checkbox or radio button
				switch ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['fieldType'])
				{
					case 'checkbox':
						$return .= '<input type="checkbox" name="'.$this->strName.'[]" id="'.$this->strName.'_'.md5($currentFile).'" class="tl_tree_checkbox" value="'.specialchars($currentFile).'" onfocus="Backend.getScrollOffset()"'.$this->optionChecked($currentFile, $this->varValue).'>';
						break;

					case 'radio':
						$return .= '<input type="radio" name="'.$this->strName.'" id="'.$this->strName.'_'.md5($currentFile).'" class="tl_tree_radio" value="'.specialchars($currentFile).'" onfocus="Backend.getScrollOffset()"'.$this->optionChecked($currentFile, $this->varValue).'>';
						break;
				}

				$return .= '</div><div style="clear:both"></div></li>';
			}
		}

		return $return;
	}


	/**
	 * Translate the file IDs to file paths
	 */
	protected function convertValuesToPaths()
	{
		if (empty($this->varValue))
		{
			return;
		}

		if (!is_array($this->varValue))
		{
			$this->varValue = array($this->varValue);
		}
		elseif (empty($this->varValue[0]))
		{
			$this->varValue = array();
		}

		$objFiles = \FilesModel::findMultipleByIds($this->varValue);

		if ($objFiles !== null)
		{
			$this->varValue = array_values($objFiles->fetchEach('path'));
		}
	}
}
