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
 * File management
 */
$GLOBALS['TL_DCA']['tl_files'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Folder',
		'onload_callback' => array
		(
			array('tl_files', 'checkPermission')
		)
	),

	// List
	'list' => array
	(
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'toggleNodes' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['toggleNodes'],
				'href'                => 'tg=all',
				'class'               => 'header_toggle'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_files']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif',
				'button_callback'     => array('tl_files', 'editFile')
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_files']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
				'button_callback'     => array('tl_files', 'copyFile')
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_files']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
				'button_callback'     => array('tl_files', 'cutFile')
			),
			'source' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_files']['source'],
				'href'                => 'act=source',
				'icon'                => 'editor.gif',
				'button_callback'     => array('tl_files', 'editSource')
			),
			'protect' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_files']['protect'],
				'href'                => 'act=protect',
				'icon'                => 'protect.gif',
				'button_callback'     => array('tl_files', 'protectFolder')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_files']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_files', 'deleteFile')
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'name'
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_files']['name'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'decodeEntities'=>true)
		)
	)
);



/**
 * Class tl_files
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_files extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Check permissions to edit the file system
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set filemounts
		$GLOBALS['TL_DCA']['tl_files']['list']['sorting']['root'] = $this->User->filemounts;

		// Disable upload button if uploads are not allowed
		if (!is_array($this->User->fop) || !in_array('f1', $this->User->fop))
		{
			$GLOBALS['TL_DCA']['tl_files']['config']['closed'] = true;
		}

		// Disable edit_all button
		if (!is_array($this->User->fop) || !in_array('f2', $this->User->fop))
		{
			$GLOBALS['TL_DCA']['tl_files']['config']['notEditable'] = true;

			if ($this->Input->get('act') == 'editAll')
			{
				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array();
				$this->Session->setData($session);
			}
		}

		// Set allowed page IDs (delete all)
		if ($this->Input->get('act') == 'deleteAll')
		{
			$session = $this->Session->getData();

			if (is_array($session['CURRENT']['IDS']))
			{
				$folders = array();
				$delete_all = array();

				foreach ($session['CURRENT']['IDS'] as $id)
				{
					if (is_dir(TL_ROOT . '/' . $id))
					{
						$folders[] = $id;

						if ((in_array('f4', $this->User->fop) && count(scan(TL_ROOT . '/' . $id)) < 1) || in_array('f4', $this->User->fop))
						{
							$delete_all[] = $id;
						}
					}

					elseif ((in_array('f3', $this->User->fop) || in_array('f4', $this->User->fop)) && !in_array(dirname($id), $folders))
					{
						$delete_all[] = $id;
					}
				}

				$session['CURRENT']['IDS'] = $delete_all;
				$this->Session->setData($session);
			}
		}

		// Check current action
		if ($this->Input->get('act') && $this->Input->get('act') != 'paste')
		{
			// No permissions at all
			if (!is_array($this->User->fop))
			{
				$this->log('No permission to manipulate files', 'tl_files checkPermission()', TL_ERROR);
				$this->redirect('typolight/main.php?act=error');
			}

			// Upload permission
			if ($this->Input->get('act') == 'move' && !in_array('f1', $this->User->fop))
			{
				$this->log('No permission to upload files', 'tl_files checkPermission()', TL_ERROR);
				$this->redirect('typolight/main.php?act=error');
			}

			// New, edit, copy or cut permission
			if (in_array($this->Input->get('act'), array('create', 'edit', 'copy', 'cut')) && !in_array('f2', $this->User->fop))
			{
				$this->log('No permission to create, edit, copy or move files', 'tl_files checkPermission()', TL_ERROR);
				$this->redirect('typolight/main.php?act=error');
			}

			// Delete permission
			if ($this->Input->get('act') == 'delete')
			{
				// Folders
				if (is_dir(TL_ROOT . '/' . $this->Input->get('id')))
				{
					$files = scan(TL_ROOT . '/' . $this->Input->get('id'));

					if (count($files) && !in_array('f4', $this->User->fop))
					{
						$this->log('No permission to delete folder "'.$this->Input->get('id').'" recursively', 'tl_files checkPermission()', TL_ERROR);
						$this->redirect('typolight/main.php?act=error');
					}

					elseif (!in_array('f3', $this->User->fop))
					{
						$this->log('No permission to delete folder "'.$this->Input->get('id').'"', 'tl_files checkPermission()', TL_ERROR);
						$this->redirect('typolight/main.php?act=error');
					}
				}

				// Files
				elseif (!in_array('f3', $this->User->fop))
				{
					$this->log('No permission to delete file "'.$this->Input->get('id').'"', 'tl_files checkPermission()', TL_ERROR);
					$this->redirect('typolight/main.php?act=error');
				}
			}
		}
	}


	/**
	 * Return the edit file button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editFile($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || is_array($this->User->fop) && in_array('f2', $this->User->fop)) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the copy file button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyFile($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || is_array($this->User->fop) && in_array('f2', $this->User->fop)) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the cut file button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function cutFile($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || is_array($this->User->fop) && in_array('f2', $this->User->fop)) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the delete file button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteFile($row, $href, $label, $title, $icon, $attributes)
	{
		if (is_dir(TL_ROOT . '/' . $row['id']) && count(scan(TL_ROOT . '/' . $row['id'])))
		{
			return ($this->User->isAdmin || is_array($this->User->fop) && in_array('f4', $this->User->fop)) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
		}

		return ($this->User->isAdmin || is_array($this->User->fop) && (in_array('f3', $this->User->fop) || in_array('f4', $this->User->fop))) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the edit file source button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editSource($row, $href, $label, $title, $icon, $attributes)
	{
		if (!$this->User->isAdmin && !in_array('f5', $this->User->fop))
		{
			return '';
		}

		$strDecoded = urldecode($row['id']);

		if (is_dir(TL_ROOT . '/' . $strDecoded))
		{
			return '';
		}

		$objFile = new File($strDecoded);

		if (!in_array($objFile->extension, trimsplit(',', $GLOBALS['TL_CONFIG']['editableFiles'])))
		{
			return $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
		}

		return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Return the edit file source button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function protectFolder($row, $href, $label, $title, $icon, $attributes)
	{
		$strDecoded = urldecode($row['id']);

		if (!is_dir(TL_ROOT . '/' . $strDecoded))
		{
			return '';
		}

		// Remove protection
		if (count(preg_grep('/^\.htaccess/i', scan(TL_ROOT . '/' . $strDecoded))) > 0)
		{
			$label = $GLOBALS['TL_LANG']['tl_files']['unlock'][0];
			$title = sprintf($GLOBALS['TL_LANG']['tl_files']['unlock'][1], $row['id']);

			return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon), $label).'</a> ';
		}

		// Protect folder
		return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}
}

?>