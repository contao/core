<?php

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
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class DC_Folder
 *
 * Provide methods to modify the file system.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class DC_Folder extends DataContainer implements listable, editable
{

	/**
	 * Current path
	 * @var string
	 */
	protected $strPath;

	/**
	 * Current file extension
	 * @var string
	 */
	protected $strExtension;

	/**
	 * Current filemounts
	 * @var array
	 */
	protected $arrFilemounts = array();

	/**
	 * Valid file types
	 * @var array
	 */
	protected $arrValidFileTypes = array();


	/**
	 * Initialize the object
	 * @param string
	 */
	public function __construct($strTable)
	{
		parent::__construct();
		$this->import('String');

		$this->intId = $this->Input->get('id', DECODE_ENTITIES);

		// Check whether the table is defined
		if (!strlen($strTable) || !count($GLOBALS['TL_DCA'][$strTable]))
		{
			$this->log('Could not load data container configuration for "' . $strTable . '"', 'DC_Folder __construct()', TL_ERROR);
			trigger_error('Could not load data container configuration', E_USER_ERROR);
		}

		// Check permission to create new folders
		if ($this->Input->get('act') == 'paste' && $this->Input->get('mode') == 'create' && array_key_exists('new', $GLOBALS['TL_DCA'][$strTable]['list']))
		{
			$this->log('Attempt to create new folder although the method has been overwritten in the data container', 'DC_Folder __construct()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		// Set IDs and redirect
		if ($this->Input->post('FORM_SUBMIT') == 'tl_select')
		{
			$ids = deserialize($this->Input->post('IDS'));

			if (!is_array($ids) || count($ids) < 1)
			{
				$this->reload();
			}

			$sids = array();

			foreach ($ids as $id)
			{
				$sids[] = urldecode(html_entity_decode($id));
			}

			$session = $this->Session->getData();
			$session['CURRENT']['IDS'] = $sids;
			$this->Session->setData($session);

			$next = array_key_exists('edit', $_POST) ? 'editAll' : (array_key_exists('delete', $_POST) ? 'deleteAll' : 'select');
			$this->redirect(str_replace('act=select', 'act='.$next, $this->Environment->request));
		}

		$this->strTable = $strTable;

		// Check for valid file types
		if ($GLOBALS['TL_DCA'][$this->strTable]['config']['validFileTypes'])
		{
			$this->arrValidFileTypes = trimsplit(',', $GLOBALS['TL_DCA'][$this->strTable]['config']['validFileTypes']);

			if ($this->Input->get('id'))
			{
				$fileinfo = preg_replace('/.*\.(.*)$/ui', '$1', $this->Input->get('id'));

				if (!in_array(strtolower($fileinfo), $this->arrValidFileTypes))
				{
					$this->log('File "'.$this->Input->get('id').'" is not an allowed file type', 'DC_Folder __construct()', TL_ERROR);
					$this->redirect('typolight/main.php?act=error');
				}
			}
		}

		// Call onload_callback (e.g. to check permissions)
		if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['onload_callback']))
		{
			foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['onload_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($this);
				}
			}
		}

		// Redirect if a file is outside the files directory
		if ($this->Input->get('id') && !preg_match('/^'.preg_quote($GLOBALS['TL_CONFIG']['uploadPath'], '/').'/i', $this->Input->get('id', DECODE_ENTITIES)))
		{
			$this->log('File or folder "'.$this->Input->get('id').'" is not in the files directory', 'DC_Folder __construct()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		if ($this->Input->get('pid') && !preg_match('/^'.preg_quote($GLOBALS['TL_CONFIG']['uploadPath'], '/').'/i', $this->Input->get('pid')))
		{
			$this->log('File or folder "'.$this->Input->get('pid').'" is not in the files directory', 'DC_Folder __construct()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		// Get all filemounts (root folders)
		if (is_array($GLOBALS['TL_DCA'][$strTable]['list']['sorting']['root']))
		{
			$this->arrFilemounts = $this->eliminateNestedPaths($GLOBALS['TL_DCA'][$strTable]['list']['sorting']['root']);

			// Prevent changing root folders
			if (in_array($this->Input->get('act'), array('edit', 'paste', 'delete')) && in_array($this->Input->get('id', DECODE_ENTITIES), $this->arrFilemounts))
			{
				$this->log('Attempt to edit, copy, move or delete root folder "'.$this->Input->get('id').'"', 'DC_Folder __construct()', TL_ERROR);
				$this->redirect('typolight/main.php?act=error');
			}
		}
	}


	/**
	 * List all files and folders of the file system
	 * @return string
	 */
	public function showAll()
	{
		$return = '';
		$imagePasteInto = $this->generateImage('pasteinto.gif', $GLOBALS['TL_LANG'][$this->strTable]['pasteinto'][0], 'class="blink"');

		// Get session data and toggle nodes
		if ($this->Input->get('tg') == 'all')
		{
			$session = $this->Session->getData();

			// Expand tree
			if (!is_array($session['filetree']) || count($session['filetree']) < 1 || current($session['filetree']) != 1)
			{
				$session['filetree'] = $this->getMD5Folders($GLOBALS['TL_CONFIG']['uploadPath']);
			}

			// Collapse tree
			else
			{
				$session['filetree'] = array();
			}

			$this->Session->setData($session);
			$this->redirect(preg_replace('/(&(amp;)?|\?)tg=[^& ]*/i', '', $this->Environment->request));
		}

		$this->import('Files');

		// Call recursive function tree()
		if (!count($this->arrFilemounts) && !is_array($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['root']) && $GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['root'] !== false)
		{
			$return .= $this->generateTree(TL_ROOT . '/' . $GLOBALS['TL_CONFIG']['uploadPath'], 0);
		}
		else
		{
			for ($i=0; $i<count($this->arrFilemounts); $i++)
			{
				$return .= $this->generateTree(TL_ROOT . '/' . $this->arrFilemounts[$i], 0, true);
			}
		}

		// Check for "create new" button
		$clsNew = 'header_new_folder';
		$lblNew = $GLOBALS['TL_LANG'][$this->strTable]['new'][0];
		$ttlNew = $GLOBALS['TL_LANG'][$this->strTable]['new'][1];
		$hrfNew = '&amp;act=paste&amp;mode=create';

		if (array_key_exists('new', $GLOBALS['TL_DCA'][$this->strTable]['list']))
		{
			$clsNew = $GLOBALS['TL_DCA'][$this->strTable]['list']['new']['class'];
			$lblNew = $GLOBALS['TL_DCA'][$this->strTable]['list']['new']['label'][0];
			$ttlNew = $GLOBALS['TL_DCA'][$this->strTable]['list']['new']['label'][1];
			$hrfNew = $GLOBALS['TL_DCA'][$this->strTable]['list']['new']['href'];
		}

		// Build tree
		$return = '
<div id="tl_buttons">
'.(($this->Input->get('act') == 'paste' || $this->Input->get('act') == 'select') ? '<a href="'.$this->getReferer(ENCODE_AMPERSANDS).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b" onclick="Backend.getScrollOffset();">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>' : '').(($this->Input->get('act') != 'paste' && $this->Input->get('act') != 'select' && !$GLOBALS['TL_DCA'][$this->strTable]['config']['closed']) ? '<a href="'.$this->addToUrl('&amp;act=paste&amp;mode=move').'" class="header_new" title="'.specialchars($GLOBALS['TL_LANG'][$this->strTable]['move'][1]).'" onclick="Backend.getScrollOffset();">'.$GLOBALS['TL_LANG'][$this->strTable]['move'][0].'</a> &#160; :: &#160; ' : '').(($this->Input->get('act') != 'paste' && $this->Input->get('act') != 'select') ? '<a href="'.$this->addToUrl($hrfNew).'" class="'.$clsNew.'" title="'.specialchars($ttlNew).'" accesskey="n" onclick="Backend.getScrollOffset();">'.$lblNew.'</a>' . $this->generateGlobalButtons(true) : '') . '
</div>' . (($this->Input->get('act') == 'select') ? '

<form action="'.ampersand($this->Environment->request, true).'" id="tl_select" class="tl_form" method="post">
<div class="tl_formbody">
<input type="hidden" name="FORM_SUBMIT" value="tl_select" />' : '').'

<div class="tl_listing_container" id="tl_listing">'.(($this->Input->get('act') == 'select') ? '

<div class="tl_select_trigger">
<label for="tl_select_trigger" class="tl_select_label">'.$GLOBALS['TL_LANG']['MSC']['selectAll'].'</label> <input type="checkbox" id="tl_select_trigger" onclick="Backend.toggleCheckboxes(this)" class="tl_tree_checkbox" />
</div>' : '').'

<ul class="tl_listing">
  <li class="tl_folder_top" onmouseover="Theme.hoverDiv(this, 1);" onmouseout="Theme.hoverDiv(this, 0);"><div class="tl_left">'.$this->generateImage('filemounts.gif').' '.$GLOBALS['TL_LANG']['MSC']['filetree'].'</div> <div class="tl_right">'.(($this->Input->get('act') == 'paste' && !count($this->arrFilemounts) && !is_array($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['root']) && $GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['root'] !== false) ? '<a href="'.$this->addToUrl('&amp;act='.$this->Input->get('mode').'&amp;mode=2&amp;pid='.$GLOBALS['TL_CONFIG']['uploadPath'].'&amp;id='.$this->Input->get('id')).'" title="'.specialchars($GLOBALS['TL_LANG'][$this->strTable]['pasteinto'][1]).'" onclick="Backend.getScrollOffset();">'.$imagePasteInto.'</a>' : '&nbsp;').'</div><div style="clear:both;"></div></li>'.$return.'
</ul>

</div>';

		// Close form
		if ($this->Input->get('act') == 'select')
		{
			$return .= '

<div class="tl_formbody_submit" style="text-align:right;">

<div class="tl_submit_container">
  <input type="submit" name="delete" id="delete" class="tl_submit" alt="delete selected records" accesskey="d" onclick="return confirm(\''.$GLOBALS['TL_LANG']['MSC']['delAllConfirm'].'\');" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['deleteSelected']).'" />' . (!$GLOBALS['TL_DCA'][$this->strTable]['config']['notEditable'] ? '
  <input type="submit" name="edit" id="edit" class="tl_submit" alt="edit selected records" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['editSelected']).'" />' : '') . '
</div>

</div>
</div>
</form>';
		}

		return $return;
	}


	/**
	 * Automatically switch to showAll
	 * @return string
	 */
	public function show()
	{
		return $this->showAll();
	}


	/**
	 * Create a new folder
	 * @param array
	 */
	public function create()
	{
		$this->import('Files');

		if (!strlen($this->Input->get('pid')) || !file_exists(TL_ROOT . '/' . $this->Input->get('pid', DECODE_ENTITIES)) || (count($this->arrFilemounts) && !$this->isMounted($this->Input->get('pid', DECODE_ENTITIES))))
		{
			$this->log('Folder "'.$this->Input->get('pid').'" was not mounted or is not a directory', 'DC_Folder create()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		$this->Files->mkdir($this->Input->get('pid', DECODE_ENTITIES) . '/__new__');
		$this->redirect(html_entity_decode($this->switchToEdit($this->Input->get('pid') . '/__new__')));
	}


	/**
	 * Move an existing file or folder
	 */
	public function cut()
	{
		if (!file_exists(TL_ROOT . '/' . $this->Input->get('id', DECODE_ENTITIES)) || !$this->isMounted($this->Input->get('id', DECODE_ENTITIES)))
		{
			$this->log('File or folder "'.$this->Input->get('id').'" was not mounted or could not be found', 'DC_Folder cut()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		if (!file_exists(TL_ROOT . '/' . $this->Input->get('pid', DECODE_ENTITIES)) || !$this->isMounted($this->Input->get('pid', DECODE_ENTITIES)))
		{
			$this->log('Folder "'.$this->Input->get('pid').'" was not mounted or is not a directory', 'DC_Folder cut()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		// Avoid a circular reference
		if (preg_match('/^' . preg_quote($this->Input->get('id', DECODE_ENTITIES), '/') . '/i', $this->Input->get('pid', DECODE_ENTITIES)))
		{
			$this->log('Attempt to move folder "'.$this->Input->get('id').'" to "'.$this->Input->get('pid').'" (circular reference)!', 'DC_Folder cut()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		$this->import('Files');

		$source = $this->Input->get('id', DECODE_ENTITIES);
		$destination = str_replace(dirname($source), $this->Input->get('pid', DECODE_ENTITIES), $source);

		$this->Files->rename($source, $destination);

		$this->log('File or folder "' . $source . '" has been moved to "' . $destination . '"', 'DC_Folder cut()', TL_FILES);
		$this->redirect($this->getReferer());
	}


	/**
	 * Recursively duplicate files and folders
	 * @param string
	 * @param string
	 */
	public function copy($source='', $destination='')
	{
		$noReload = strlen($source);

		// Get source and destination
		$source = strlen($source) ? $source : $this->Input->get('id', DECODE_ENTITIES);
		$destination = strlen($destination) ? $destination : str_replace(dirname($source), $this->Input->get('pid', DECODE_ENTITIES), $source);

		// Duplicate file or folder
		if (!file_exists(TL_ROOT . '/' . $this->Input->get('id', DECODE_ENTITIES)) || !$this->isMounted($this->Input->get('id', DECODE_ENTITIES)))
		{
			$this->log('File or folder "'.$this->Input->get('id').'" was not mounted or could not be found', 'DC_Folder copy()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		if (!file_exists(TL_ROOT . '/' . $this->Input->get('pid', DECODE_ENTITIES)) || !$this->isMounted($this->Input->get('pid', DECODE_ENTITIES)))
		{
			$this->log('Folder "'.$this->Input->get('pid').'" was not mounted or is not a directory', 'DC_Folder copy()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		// Avoid a circular reference
		if (preg_match('/^' . preg_quote($this->Input->get('id', DECODE_ENTITIES), '/') . '/i', $this->Input->get('pid', DECODE_ENTITIES)))
		{
			$this->log('Attempt to copy folder "'.$this->Input->get('id').'" to "'.$this->Input->get('pid').'" (circular reference)!', 'DC_Folder copy()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		$this->import('Files');

		// Copy folders
		if (is_dir(TL_ROOT . '/' . $source))
		{
			$this->Files->mkdir($destination);
			$files = scan(TL_ROOT . '/' . $source);

			foreach ($files as $file)
			{
				if (is_dir(TL_ROOT . '/' . $source .'/'. $file))
				{
					$this->copy($source . '/' . $file, $destination . '/' . $file);
				}
				else
				{
					$this->Files->copy($source . '/' . $file, $destination . '/' . $file);
				}
			}
		}

		// Copy file
		else
		{
			$this->Files->copy($source, $destination);
		}

		// Do not reload on recursive calls
		if (!$noReload)
		{
			if (file_exists(TL_ROOT . '/' . $this->Input->get('id', DECODE_ENTITIES)) && (!is_array($this->arrFilemounts) || $this->isMounted($this->Input->get('id', DECODE_ENTITIES))))
			{
				$this->log('File or folder "'.$this->Input->get('id').'" has been duplicated', 'DC_Folder copy()', TL_FILES);
			}

			$this->redirect($this->getReferer());
		}
	}


	/**
	 * Recursively delete files and folders
	 * @param string
	 */
	public function delete($source='')
	{
		$noReload = strlen($source);

		// Get source
		$source = strlen($source) ? $source : $this->Input->get('id', DECODE_ENTITIES);

		// Delete file or folder
		if (!file_exists(TL_ROOT . '/' . $source) || !$this->isMounted($source))
		{
			$this->log('File or folder "' . $source . '" was not mounted or could not be found', 'DC_Folder delete()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		$this->import('Files');

		// Delete folders
		if (is_dir(TL_ROOT . '/' . $source))
		{
			$files = scan(TL_ROOT . '/' . $source);

			foreach ($files as $file)
			{
				if (is_dir(TL_ROOT . '/' . $source . '/' . $file))
				{
					$this->delete($source . '/' . $file);
				}
				else
				{
					$this->Files->delete($source . '/' . $file);
				}
			}

			$this->Files->rmdir($source);
		}

		// Delete file
		else
		{
			$this->Files->delete($source);
		}

		// Do not reload on recursive calls
		if (!$noReload)
		{
			$this->log('File or folder "' . str_replace(TL_ROOT.'/', '', $source) . '" has been deleted', 'DC_Folder delete()', TL_FILES);
			$this->redirect($this->getReferer());
		}
	}


	/**
	 * Delete all files and folders that are currently shown
	 */
	public function deleteAll()
	{
		$session = $this->Session->getData();
		$ids = $session['CURRENT']['IDS'];

		if (is_array($ids) && strlen($ids[0]))
		{
			foreach ($ids as $id)
			{
				$this->delete($id);
			}
		}

		$this->redirect($this->getReferer());
	}


	/**
	 * Automatically switch to showAll
	 * @return string
	 */
	public function undo()
	{
		return $this->showAll();
	}


	/**
	 * Move one or more local files to the server
	 */
	public function move()
	{
		$error = false;

		if (!file_exists(TL_ROOT . '/' . $this->Input->get('pid', DECODE_ENTITIES)) || !$this->isMounted($this->Input->get('pid', DECODE_ENTITIES)))
		{
			$this->log('Folder "' . $this->Input->get('pid') . '" was not mounted or is not a directory', 'DC_Folder move()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		// Upload files
		if ($this->Input->post('FORM_SUBMIT') == 'tl_upload')
		{
			$maxlength_kb = number_format(($GLOBALS['TL_CONFIG']['maxFileSize']/1024), 1, $GLOBALS['TL_LANG']['MSC']['decimalSeparator'], $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']);

			foreach ($_FILES as $file)
			{
				// Romanize the filename
				$file['name'] = utf8_romanize($file['name']);

				// File was not uploaded
				if (!is_uploaded_file($file['tmp_name']))
				{
					if (in_array($file['error'], array(1, 2)))
					{
						$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['filesize'], $maxlength_kb);
						$this->log('File "'.$file['name'].'" exceeds the maximum file size of '.$maxlength_kb.' kB' , 'DC_Folder move()', TL_ERROR);

						$error = true;
					}

					if ($file['error'] == 3)
					{
						$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['filepartial'], $file['name']);
						$this->log('File "'.$file['name'].'" was only partially uploaded' , 'DC_Folder move()', TL_ERROR);

						$error = true;
					}

					continue;
				}

				// File is too big
				if ($file['size'] > $GLOBALS['TL_CONFIG']['maxFileSize'])
				{
					$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['filesize'], $maxlength_kb);
					$this->log('File "'.$file['name'].'" exceeds the maximum file size of '.$maxlength_kb.' kB', 'DC_Folder move()', TL_ERROR);

					$error = true;
					continue;
				}

				$uploadTypes = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['uploadTypes']));
				$pathinfo = pathinfo($file['name']);

				// File type not allowed
				if (!in_array(strtolower($pathinfo['extension']), $uploadTypes))
				{
					$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $pathinfo['extension']);
					$this->log('File type "'.$pathinfo['extension'].'" is not allowed to be uploaded ('.$file['name'].')', 'DC_Folder move()', TL_ERROR);

					$error = true;
					continue;
				}

				$this->import('Files');
				$strNewFile = $this->Input->get('pid', true) . '/' . $file['name'];

				// Move file to destination
				if ($this->Files->move_uploaded_file($file['tmp_name'], $strNewFile))
				{
					$blnResized = false;
					$this->Files->chmod($strNewFile, 0644);

					// Resize image if necessary
					if (($arrImageSize = @getimagesize(TL_ROOT . '/' . $strNewFile)) !== false)
					{
						// Image exceeds maximum image width
						if ($arrImageSize[0] > $GLOBALS['TL_CONFIG']['imageWidth'])
						{
							$blnResized = true;
							$this->resizeImage($strNewFile, $GLOBALS['TL_CONFIG']['imageWidth'], 0);
						}

						// Image exceeds maximum image height
						if ($arrImageSize[1] > $GLOBALS['TL_CONFIG']['imageHeight'])
						{
							$blnResized = true;
							$this->resizeImage($strNewFile, 0, $GLOBALS['TL_CONFIG']['imageHeight']);
						}
					}

					// Notify user
					if ($blnResized)
					{
						$_SESSION['TL_INFO'][] = sprintf($GLOBALS['TL_LANG']['MSC']['fileResized'], $file['name']);
						$this->log('File "'.$file['name'].'" uploaded and resized successfully', 'DC_Folder move()', TL_FILES);
					}
					else
					{
						$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['MSC']['fileUploaded'], $file['name']);
						$this->log('File "'.$file['name'].'" uploaded successfully', 'DC_Folder move()', TL_FILES);
					}
				}
			}

			// Redirect or reload
			if (!$error)
			{
				if ($this->Input->post('uploadNback') && !$blnResized)
				{
					$_SESSION['TL_INFO'] = '';
					$_SESSION['TL_ERROR'] = '';
					$_SESSION['TL_CONFIRM'] = '';

					$this->redirect($this->getReferer());
				}

				$this->reload();
			}
		}

		$fields = '';

		// Upload fields
		for ($i=0; $i<$GLOBALS['TL_CONFIG']['uploadFields']; $i++)
		{
			$fields .= '
  <input type="file" name="'.$i.'" class="tl_upload_field" maxlength="'.$GLOBALS['TL_CONFIG']['maxFileSize'].'" onfocus="Backend.getScrollOffset();" /><br />';
		}

		// Display upload form
		return '
<div id="tl_buttons">
<a href="'.$this->getReferer(ENCODE_AMPERSANDS).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b" onclick="Backend.getScrollOffset();">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.sprintf($GLOBALS['TL_LANG']['tl_files']['uploadFF'], basename($this->Input->get('pid'))).'</h2>'.$this->getMessages().'

<form action="'.ampersand($this->Environment->request, ENCODE_AMPERSANDS).'" id="'.$this->strTable.'" class="tl_form" method="post"'.(count($this->onsubmit) ? ' onsubmit="'.implode(' ', $this->onsubmit).'"' : '').' enctype="multipart/form-data">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_upload" />
<input type="hidden" name="MAX_FILE_SIZE" value="'.$GLOBALS['TL_CONFIG']['maxFileSize'].'" />

<div class="tl_tbox">
  <h3>'.$GLOBALS['TL_LANG'][$this->strTable]['fileupload'][0].'</h3>'.$fields.(strlen($GLOBALS['TL_LANG'][$this->strTable]['fileupload'][1]) ? '
  <p class="tl_help">'.$GLOBALS['TL_LANG'][$this->strTable]['fileupload'][1].'</p>' : '').'
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="upload" class="tl_submit" alt="upload files" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG'][$this->strTable]['upload']).'" />
<input type="submit" name="uploadNback" class="tl_submit" alt="upload files and go back" accesskey="c" value="'.specialchars($GLOBALS['TL_LANG'][$this->strTable]['uploadNback']).'" />
</div>

</div>
</form>';
	}


	/**
	 * Autogenerate a form to rename a file or folder
	 * @return string
	 */
	public function edit()
	{
		$return = '';
		$this->noReload = false;

		// Check whether the current file exists
		if (!file_exists(TL_ROOT . '/' . $this->Input->get('id', DECODE_ENTITIES)) || !$this->isMounted($this->Input->get('id', DECODE_ENTITIES)))
		{
			$this->log('File or folder "'.$this->Input->get('id').'" was not mounted or could not be found', 'DC_Folder edit()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		// Build an array from boxes and rows (do not show excluded fields)
		$this->strPalette = $this->getPalette();
		$boxes = trimsplit(';', $this->strPalette);

		if (count($boxes))
		{
			// Get fields
			foreach ($boxes as $k=>$v)
			{
				$boxes[$k] = trimsplit(',', $v);

				foreach ($boxes[$k] as $kk=>$vv)
				{
					if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$vv]['exclude'] || !count($GLOBALS['TL_DCA'][$this->strTable]['fields'][$vv]))
					{
						unset($boxes[$k][$kk]);
					}
				}

				// Unset a box if it does not contain any fields
				if (count($boxes[$k]) < 1)
				{
					unset($boxes[$k]);
				}
			}

			// Render boxes
			$class = 'tl_tbox';

			foreach ($boxes as $k=>$v)
			{
				$return .= '
<div class="'.$class.'">';

				// Build rows of the current box
				foreach ($v as $kk=>$vv)
				{
					$this->strField = $vv;
					$this->strInputName = $vv;

					// Load current value
					$pathinfo = pathinfo($this->Input->get('id', DECODE_ENTITIES));

					$this->strPath = $pathinfo['dirname'];
					$this->strExtension = strlen($pathinfo['extension']) ? '.'.$pathinfo['extension'] : '';
					$this->varValue = basename($pathinfo['basename'], $this->strExtension);

					// Fix Unix system files like .htaccess
					if (strncmp($this->varValue, '.', 1) === 0)
					{
						$this->strExtension = '';
					}

					// Clear current value if it is a new folder
					if ($this->Input->post('FORM_SUBMIT') != 'tl_files' && $this->varValue == '__new__')
					{
						$this->varValue = '';
					}

					// Call load_callback
					if (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['load_callback']))
					{
						foreach ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['load_callback'] as $callback)
						{
							if (is_array($callback))
							{
								$this->import($callback[0]);
								$this->varValue = $this->$callback[0]->$callback[1]($this->varValue, $this);
							}
						}
					}

					// Build row
					$return .= $this->row();
				}

				$class = 'tl_box';
				$return .= '
  <input type="hidden" name="FORM_FIELDS[]" value="'.specialchars($this->strPalette).'" />
</div>';
			}
		}

		// Add some buttons and end the form
		$return .= '
</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="save" id="save" class="tl_submit" alt="save all changes" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['save']).'" />
<input type="submit" name="saveNclose" id="saveNclose" class="tl_submit" alt="save all changes and return" accesskey="c" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['saveNclose']).'" />
</div>

</div>
</form>';

		// Begin the form (-> DO NOT CHANGE THIS ORDER -> this way the onsubmit attribute of the form can be changed by a field)
		$return = '
<div id="tl_buttons">
<a href="'.$this->getReferer(ENCODE_AMPERSANDS).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b" onclick="Backend.getScrollOffset();">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_files']['editFF'].'</h2>'.$this->getMessages().'

<form action="'.ampersand($this->Environment->request, ENCODE_AMPERSANDS).'" id="'.$this->strTable.'" class="tl_form" method="post"'.(count($this->onsubmit) ? ' onsubmit="'.implode(' ', $this->onsubmit).'"' : '').'>
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="'.specialchars($this->strTable).'" />'.($this->noReload ? '
<p class="tl_error">'.$GLOBALS['TL_LANG']['ERR']['general'].'</p>' : '').$return;

		// Reload the page to prevent _POST variables from being sent twice
		if ($this->Input->post('FORM_SUBMIT') == $this->strTable && !$this->noReload)
		{
			// Call onsubmit_callback
			if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['onsubmit_callback']))
			{
				foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['onsubmit_callback'] as $callback)
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($this);
				}
			}

			// Reload
			if ($this->Input->post('saveNclose'))
			{
				$_SESSION['TL_INFO'] = '';
				$_SESSION['TL_ERROR'] = '';
				$_SESSION['TL_CONFIRM'] = '';

				setcookie('BE_PAGE_OFFSET', 0, 0, '/');
				$this->redirect($this->getReferer());
			}

			$this->redirect($this->addToUrl('id=' . $this->strPath . '/' . $this->varValue . $this->strExtension));
		}

		// Set the focus if there is an error
		if ($this->noReload)
		{
			$return .= '

<script type="text/javascript">
<!--//--><![CDATA[//><!--
window.addEvent(\'domready\', function()
{
    Backend.vScrollTo(($(\'' . $this->strTable . '\').getElement(\'div.tl_error\').getPosition().y - 20));
});
//--><!]]>
</script>';
		}

		return $return;
	}


	/**
	 * Autogenerate a form to edit all records that are currently shown
	 * @param integer
	 * @param integer
	 * @return string
	 */
	public function editAll()
	{
		$return = '';

		if ($GLOBALS['TL_DCA'][$this->strTable]['config']['notEditable'])
		{
			$this->log('Table ' . $this->strTable . ' is not editable', 'DC_Folder editAll()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		// Get current IDs from session
		$session = $this->Session->getData();
		$ids = $session['CURRENT']['IDS'];

		// Save field selection in session
		if ($this->Input->post('FORM_SUBMIT') == $this->strTable.'_all' && $this->Input->get('fields'))
		{
			$session['CURRENT'][$this->strTable] = deserialize($this->Input->post('all_fields'));
			$this->Session->setData($session);
		}

		$fields = $session['CURRENT'][$this->strTable];

		// Add fields
		if (is_array($fields) && count($fields) && $this->Input->get('fields'))
		{
			$class = 'tl_tbox';

			// Walk through each record
			foreach ($ids as $id)
			{
				$this->intId = md5($id);
				$this->strPalette = trimsplit('[;,]', $this->getPalette());

				$return .= '
<div class="'.$class.'">';

				$class = 'tl_box';
				$formFields = array();

				foreach ($this->strPalette as $v)
				{
					// Check whether field is excluded
					if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['exclude'])
					{
						continue;
					}

					if (!in_array($v, $fields))
					{
						continue;
					}

					$this->strField = $v;
					$this->strInputName = $v.'_'.$this->intId;
					$formFields[] = $v.'_'.$this->intId;

					// Load current value
					$pathinfo = pathinfo($id);

					$this->strPath = $pathinfo['dirname'];
					$this->strExtension = strlen($pathinfo['extension']) ? '.'.$pathinfo['extension'] : '';
					$this->varValue = basename($pathinfo['basename'], $this->strExtension);

					// Fix Unix system files like .htaccess
					if (strncmp($this->varValue, '.', 1) === 0)
					{
						$this->strExtension = '';
					}

					// Call load_callback
					if (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['load_callback']))
					{
						foreach ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['load_callback'] as $callback)
						{
							$this->import($callback[0]);
							$this->varValue = $this->$callback[0]->$callback[1]($this->varValue, $this);
						}
					}

					// Build the current row
					$return .= $this->row();
				}

				// Close box
				$return .= '
  <input type="hidden" name="FORM_FIELDS_'.$this->intId.'[]" value="'.specialchars(implode(',', $formFields)).'" />
</div>';
			}

			// Add the form
			$return = '

<h2 class="sub_headline_all">'.sprintf($GLOBALS['TL_LANG']['MSC']['all_info'], $this->strTable).'</h2>

<form action="'.ampersand($this->Environment->request, true).'" id="'.$this->strTable.'" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="'.$this->strTable.'" />'.($this->noReload ? '

<p class="tl_error">'.$GLOBALS['TL_LANG']['ERR']['general'].'</p>' : '').$return.'

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="save" id="save" class="tl_submit" alt="save all changes" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['save']).'" />
<input type="submit" name="saveNclose" id="saveNclose" class="tl_submit" alt="save all changes and return" accesskey="c" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['saveNclose']).'" />
</div>

</div>
</form>';

			// Set the focus if there is an error
			if ($this->noReload)
			{
				$return .= '

<script type="text/javascript">
<!--//--><![CDATA[//><!--
window.addEvent(\'domready\', function()
{
    Backend.vScrollTo(($(\'' . $this->strTable . '\').getElement(\'div.tl_error\').getPosition().y - 20));
});
//--><!]]>
</script>';
			}

			// Reload the page to prevent _POST variables from being sent twice
			if ($this->Input->post('FORM_SUBMIT') == $this->strTable && !$this->noReload)
			{
				if ($this->Input->post('saveNclose'))
				{
					setcookie('BE_PAGE_OFFSET', 0, 0, '/');
					$this->redirect($this->getReferer());
				}

				$this->reload();
			}
		}

		// Else show a form to select the fields
		else
		{
			$options = '';
			$fields = array();

			// Add fields of the current table
			$fields = array_merge($fields, array_keys($GLOBALS['TL_DCA'][$this->strTable]['fields']));

			// Show all non-excluded fields
			foreach ($fields as $field)
			{
				if (!$GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['exclude'] && !$GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['eval']['doNotShow'] && (strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['inputType']) || is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['input_field_callback'])))
				{
					$options .= '
<input type="checkbox" name="all_fields[]" id="all_'.$field.'" class="tl_checkbox" value="'.specialchars($field).'" /> <label for="all_'.$field.'" class="tl_checkbox_label">'.(strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['label'][0]) ? $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['label'][0] : $GLOBALS['TL_LANG']['MSC'][$field][0]).'</label><br />';
				}
			}

			// Return select menu
			$return .= (($_POST && !count($_POST['all_fields'])) ? '

<p class="tl_error">'.$GLOBALS['TL_LANG']['ERR']['general'].'</p>' : '').'

<h2 class="sub_headline_all">'.sprintf($GLOBALS['TL_LANG']['MSC']['all_info'], $this->strTable).'</h2>

<form action="'.ampersand($this->Environment->request, true).'&amp;fields=1" id="'.$this->strTable.'_all" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="'.$this->strTable.'_all" />

<div class="tl_tbox">
<h3><label for="fields">'.$GLOBALS['TL_LANG']['MSC']['all_fields'][0].'</label></h3>'.(($_POST && !count($_POST['all_fields'])) ? '
<p class="tl_error">'.$GLOBALS['TL_LANG']['ERR']['all_fields'].'</p>' : '').'
<div id="fields" class="tl_checkbox_container">
<input type="checkbox" id="check_all" class="tl_checkbox" onclick="Backend.toggleCheckboxes(this);" /> <label for="check_all" style="color:#a6a6a6;"><em>'.$GLOBALS['TL_LANG']['MSC']['selectAll'].'</em></label><br />'.$options.'
</div>'.(($GLOBALS['TL_CONFIG']['showHelp'] && strlen($GLOBALS['TL_LANG']['MSC']['all_fields'][1])) ? '
<p class="tl_help">'.$GLOBALS['TL_LANG']['MSC']['all_fields'][1].'</p>' : '').'
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="save" id="save" class="tl_submit" alt="continue" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['continue']).'" />
</div>

</div>
</form>';
		}

		// Return
		return '
<div id="tl_buttons">
<a href="'.$this->getReferer(ENCODE_AMPERSANDS).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b" onclick="Backend.getScrollOffset();">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>'.$return;
	}


	/**
	 * Load the source editor
	 * @return string
	 */
	public function source()
	{
		if (is_dir(TL_ROOT . '/' . $this->intId))
		{
			$this->log('Directory "'.$this->intId.'" cannot be edited', 'DC_Folder source()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		$this->import('BackendUser', 'User');

		// Check user permission
		if (!$this->User->isAdmin && !in_array('f5', $this->User->fop))
		{
			$this->log('Not enough permissions to edit file source of file "'.$this->intId.'"', 'DC_Folder source()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		$objFile = new File($this->intId);

		// Check whether file type is editable
		if (!in_array($objFile->extension, trimsplit(',', $GLOBALS['TL_CONFIG']['editableFiles'])))
		{
			$this->log('File type "'.$objFile->extension.'" ('.$this->intId.') is not allowed to be edited', 'DC_Folder source()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		$strContent = $objFile->getContent();

		// Process request
		if ($this->Input->post('FORM_SUBMIT') == 'tl_files')
		{
			// Save file
			if (md5($strContent) != md5($this->Input->postRaw('source')))
			{
				$objFile->write($this->Input->postRaw('source'));
				$objFile->close();
			}

			if ($this->Input->post('saveNclose'))
			{
				setcookie('BE_PAGE_OFFSET', 0, 0, '/');
				$this->redirect($this->getReferer());
			}

			$this->reload();
		}

		return'
<div id="tl_buttons">
<a href="'.$this->getReferer(ENCODE_AMPERSANDS).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b" onclick="Backend.getScrollOffset();">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.sprintf($GLOBALS['TL_LANG']['tl_files']['editFile'], $objFile->basename).'</h2>'.$this->getMessages().'

<form action="'.ampersand($this->Environment->request, ENCODE_AMPERSANDS).'" id="tl_files" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_files" />
<div class="tl_tbox">
  <h3><label for="ctrl_source">'.$GLOBALS['TL_LANG']['tl_files']['editor'][0].'</label> ' . $this->generateImage('wrap.gif', $GLOBALS['TL_LANG']['MSC']['wordWrap'], 'title="'.specialchars($GLOBALS['TL_LANG']['MSC']['wordWrap']).'" class="toggleWrap" onclick="Backend.toggleWrap(\'ctrl_source\');"') . '</h3>
  <textarea name="source" id="ctrl_source" class="tl_textarea monospace" rows="12" cols="80" style="height:400px;" onfocus="Backend.getScrollOffset();">' . "\n" . specialchars($strContent) . '</textarea>' . (($GLOBALS['TL_CONFIG']['showHelp'] && strlen($GLOBALS['TL_LANG']['tl_files']['editor'][1])) ? '
  <p class="tl_help">'.$GLOBALS['TL_LANG']['tl_files']['editor'][1].'</p>' : '') . '
</div>
</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="save" id="save" class="tl_submit" alt="save all changes" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['save']).'" />
<input type="submit" name="saveNclose" id="saveNclose" class="tl_submit" alt="save all changes and return" accesskey="c" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['saveNclose']).'" />
</div>

</div>
</form>';
	}


	/**
	 * Protect a folder
	 * @return string
	 */
	public function protect()
	{
		if (!is_dir(TL_ROOT . '/' . $this->intId))
		{
			$this->log('Resource "' . $this->intId . '" is not a directory', 'DC_Folder protect()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		// Remove protection
		if (file_exists(TL_ROOT . '/' . $this->intId . '/.htaccess'))
		{
			$objFile = new File($this->intId . '/.htaccess');
			$objFile->delete();

			$this->log('Protection from folder "' . $this->intId . '" has been removed', 'DC_Folder protect()', TL_FILES);
			$this->redirect($this->getReferer());
		}

		// Protect folder
		else
		{
			$objFile = new File($this->intId . '/.htaccess');
			$objFile->write("order deny,allow\ndeny from all");
			$objFile->close();

			$this->log('Folder "' . $this->intId . '" has been protected', 'DC_Folder protect()', TL_FILES);
			$this->redirect($this->getReferer());
		}
	}


	/**
	 * Save the current value
	 * @param mixed
	 * @throws Exception
	 */
	protected function save($varValue)
	{
		if ($this->Input->post('FORM_SUBMIT') != $this->strTable || !file_exists(TL_ROOT . '/' . $this->strPath . '/' . $this->varValue . $this->strExtension) || (is_array($this->arrFilemounts) && !$this->isMounted($this->strPath . '/' . $this->varValue . $this->strExtension)) || $this->varValue == $varValue)
		{
			return;
		}

		$this->import('Files');
		$arrData = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField];
		$varValue = utf8_romanize($varValue);

		// Call save_callback
		if (is_array($arrData['save_callback']))
		{
			foreach ($arrData['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$varValue = $this->$callback[0]->$callback[1]($varValue, $this);
			}
		}

		$this->Files->rename($this->strPath . '/' . $this->varValue . $this->strExtension, $this->strPath . '/' . $varValue . $this->strExtension);

		// Add a log entry
		if (stristr($this->Input->get('id'), '__new__') == true)
		{
			$this->log('Folder "'.$this->strPath.'/'.$varValue.$this->strExtension.'" has been created', 'DC_Folder save()', TL_FILES);
		}
		else
		{
			$this->log('File or folder "'.$this->strPath.'/'.$this->varValue.$this->strExtension.'" has been renamed to "'.$this->strPath.'/'.$varValue.$this->strExtension.'"', 'DC_Folder save()', TL_FILES);
		}

		// Set the new value so the input field can show it
		if ($this->Input->get('act') == 'editAll')
		{
			$session = $this->Session->getData();

			if (($index = array_search($this->strPath.'/'.$this->varValue.$this->strExtension, $session['CURRENT']['IDS'])) !== false)
			{
				$session['CURRENT']['IDS'][$index] = $this->strPath.'/'.$varValue.$this->strExtension;
				$this->Session->setData($session);
			}
		}

		$this->varValue = $varValue;
	}


	/**
	 * Return the name of the current palette
	 * @return string
	 */
	public function getPalette()
	{
		return $GLOBALS['TL_DCA'][$this->strTable]['palettes']['default'];
	}


	/**
	 * Generate a particular subpart of the tree and return it as HTML string
	 * @param string
	 * @param integer
	 * @return string
	 */
	public function ajaxTreeView($strFolder, $level)
	{
		if (!$this->Input->post('isAjax'))
		{
			return '';
		}

		$this->import('Files');
		return $this->generateTree(TL_ROOT.'/'.$strFolder, ($level * 20));
	}


	/**
	 * Render the file tree and return it as HTML string
	 * @param string
	 * @param integer
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	private function generateTree($path, $intMargin, $mount=false, $blnProtected=false)
	{
		static $session;
		$session = $this->Session->getData();

		// Get session data and toggle nodes
		if ($this->Input->get('tg'))
		{
			$session['filetree'][$this->Input->get('tg')] = (isset($session['filetree'][$this->Input->get('tg')]) && $session['filetree'][$this->Input->get('tg')] == 1) ? 0 : 1;
			$this->Session->setData($session);

			$this->redirect(preg_replace('/(&(amp;)?|\?)tg=[^& ]*/i', '', $this->Environment->request));
		}

		$return = '';
		$files = array();
		$folders = array();
		$intSpacing = 20;
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
				if (!is_dir($path . '/' . $v))
				{
					$files[] = $path.'/'.$v;
					continue;
				}

				if ($v == '__new__')
				{
					$this->Files->rmdir($path . '/' . $v);
					continue;
				}

				if (substr($v, 0, 1) != '.')
				{
					$folders[] = $path . '/' . $v;
				}
			}

			natcasesort($folders);
			$folders = array_values($folders);

			natcasesort($files);
			$files = array_values($files);
		}

		// Folders
		for ($f=0; $f<count($folders); $f++)
		{
			$return .= "\n  " . '<li class="tl_folder" onmouseover="Theme.hoverDiv(this, 1);" onmouseout="Theme.hoverDiv(this, 0);"><div class="tl_left" style="padding-left:'.$intMargin.'px;">';

			$md5 = md5($folders[$f]);
			$content = scan($folders[$f]);
			$countFiles = count($content);
			$folderAttribute = 'style="margin-left:20px;"';
			$currentFolder = str_replace(TL_ROOT.'/', '', $folders[$f]);
			$session['filetree'][$md5] = is_numeric($session['filetree'][$md5]) ? $session['filetree'][$md5] : 0;

			// Add a toggle button if there are childs
			if ($countFiles > 0)
			{
				$folderAttribute = '';
				$img = ($session['filetree'][$md5] == 1) ? 'folMinus.gif' : 'folPlus.gif';
				$alt = ($session['filetree'][$md5] == 1) ? $GLOBALS['TL_LANG']['MSC']['collapseNode'] : $GLOBALS['TL_LANG']['MSC']['expandNode'];
				$return .= '<a href="'.$this->addToUrl('tg='.$md5).'" title="'.specialchars($alt).'" onclick="Backend.getScrollOffset(); return AjaxRequest.toggleFileManager(this, \'filetree_'.$md5.'\', \''.$currentFolder.'\', '.$level.');">'.$this->generateImage($img, specialchars($alt), 'style="margin-right:2px;"').'</a>';
			}

			$protected = ($blnProtected === true || array_search('.htaccess', $content) !== false) ? true : false;
			$folderImg = ($session['filetree'][$md5] == 1 && $countFiles > 0) ? ($protected ? 'folderOP.gif' : 'folderO.gif') : ($protected ? 'folderCP.gif' : 'folderC.gif');

			// Add the current folder
			$return .= $this->generateImage($folderImg, '', $folderAttribute).' <strong>'.basename($currentFolder).'</strong></div> <div class="tl_right">';

			// Paste buttons
			if ($this->Input->get('act') == 'paste')
			{
				$imagePasteInto = $this->generateImage('pasteinto.gif', $GLOBALS['TL_LANG'][$this->strTable]['pasteinto'][0], 'class="blink"');
				$return .= (in_array($this->Input->get('mode'), array('cut', 'copy')) && preg_match('/^' . preg_quote($this->Input->get('id', DECODE_ENTITIES), '/') . '/i', $currentFolder)) ? $this->generateImage('pasteinto_.gif', '', 'class="blink"') : '<a href="'.$this->addToUrl('act='.$this->Input->get('mode').'&amp;mode=2&amp;pid='.$currentFolder.'&amp;id='.$this->Input->get('id', DECODE_ENTITIES)).'" title="'.specialchars($GLOBALS['TL_LANG'][$this->strTable]['pasteinto'][1]).'" onclick="Backend.getScrollOffset();">'.$imagePasteInto.'</a> ';
			}

			// Default buttons (do not display buttons for mounted folders)
			elseif (!$mount)
			{
				$return .= ($this->Input->get('act') == 'select') ? '<input type="checkbox" name="IDS[]" id="ids_'.md5($currentFolder).'" class="tl_tree_checkbox" value="'.$currentFolder.'" />' : $this->generateButtons(array('id'=>$currentFolder), $this->strTable);
			}

			$return .= '</div><div style="clear:both;"></div></li>';

			// Call next node
			if ($countFiles > 0 && $session['filetree'][$md5] == 1)
			{
				$return .= '<li class="parent" id="filetree_'.$md5.'"><ul class="level_'.$level.'">';
				$return .= $this->generateTree($folders[$f], ($intMargin + $intSpacing), false, $protected);
				$return .= '</ul></li>';
			}
		}

		// Process files
		for ($h=0; $h<count($files); $h++)
		{
			$thumbnail = '';
			$popupWidth = 400;
			$popupHeight = 204;
			$currentFile = str_replace(TL_ROOT.'/', '', $files[$h]);

			$objFile = new File($currentFile);

			if (is_array($this->arrValidFileTypes) && count($this->arrValidFileTypes) && !in_array($objFile->extension, $this->arrValidFileTypes))
			{
				continue;
			}

			$currentEncoded = $this->urlEncode($currentFile);
			$return .= "\n  " . '<li class="tl_file" onmouseover="Theme.hoverDiv(this, 1);" onmouseout="Theme.hoverDiv(this, 0);"><div class="tl_left" style="padding-left:'.($intMargin + $intSpacing).'px;">';

			// Generate thumbnail
			if ($objFile->isGdImage && $objFile->height > 0)
			{
				$popupWidth = ($objFile->width > 400) ? ($objFile->width + 61) : 461;
				$popupHeight = ($objFile->height + 252);

				if ($GLOBALS['TL_CONFIG']['thumbnails'])
				{
					$_height = ($objFile->height < 70) ? $objFile->height : 70;
					$_width = (($objFile->width * $_height / $objFile->height) > 400) ? 90 : '';

					$thumbnail = '<br /><a href="typolight/popup.php?src='.$currentEncoded.'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['view']).'" onclick="this.blur(); Backend.openWindow(this, '.$popupWidth.', '.$popupHeight.'); return false;" ><img src="' . $this->getImage($currentEncoded, $_width, $_height) . '" alt="" style="margin:0px 0px 2px 23px;" /></a>';
				}
			}

			$_buttons = '&nbsp;';
			$return .= '<a href="typolight/popup.php?src='.$currentEncoded.'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['view']).'" onclick="this.blur(); Backend.openWindow(this, '.$popupWidth.', '.$popupHeight.'); return false;" >' . $this->generateImage($objFile->icon).' '.utf8_convert_encoding(basename($currentFile), $GLOBALS['TL_CONFIG']['characterSet']).'</a>'.$thumbnail.'</div> <div class="tl_right">';

			// Buttons
			if ($this->Input->get('act') != 'paste')
			{
				$_buttons = ($this->Input->get('act') == 'select') ? '<input type="checkbox" name="IDS[]" id="ids_'.md5($currentEncoded).'" class="tl_tree_checkbox" value="'.$currentEncoded.'" />' : $this->generateButtons(array('id'=>urldecode($currentEncoded)), $this->strTable);
			}

			$return .= $_buttons . '</div><div style="clear:both;"></div></li>';
		}

		return $return;
	}


	/**
	 * Return true if the current folder is mounted
	 * @param string
	 * @return boolean
	 */
	private function isMounted($strFolder)
	{
		if (!count($this->arrFilemounts))
		{
			return true;
		}

		$path = $strFolder;

		while (is_array($this->arrFilemounts) && substr_count($path, '/') > 0)
		{
			if (in_array($path, $this->arrFilemounts))
			{
				return true;
			}

			$path = dirname($path);
		}

		return false;
	}


	/**
	 * Return an array of encrypted folder names
	 * @param string
	 * @return array
	 */
	private function getMD5Folders($strPath)
	{
		$arrFiles = array();

		foreach (scan(TL_ROOT . '/' . $strPath) as $strFile)
		{
			if (!is_dir(TL_ROOT . '/' . $strPath . '/' . $strFile))
			{
				continue;
			}

			$arrFiles[md5(TL_ROOT . '/' . $strPath . '/' . $strFile)] = 1;
			$arrFiles = array_merge($arrFiles, $this->getMD5Folders($strPath . '/' . $strFile));
		}

		return $arrFiles;
	}
}

?>