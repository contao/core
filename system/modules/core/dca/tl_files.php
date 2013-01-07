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
 * File management
 */
$GLOBALS['TL_DCA']['tl_files'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Folder',
		'enableVersioning'            => true,
		'databaseAssisted'            => true,
		'onload_callback' => array
		(
			array('tl_files', 'checkPermission'),
			array('tl_files', 'addBreadcrumb'),
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index',
				'path' => 'unique',
				'extension' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'global_operations' => array
		(
			'sync' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_files']['sync'],
				'href'                => 'act=sync',
				'class'               => 'header_sync',
				'button_callback'     => array('tl_files', 'syncFiles')
			),
			'toggleNodes' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['toggleAll'],
				'href'                => 'tg=all',
				'class'               => 'header_toggle'
			),
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
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
				'attributes'          => 'onclick="Backend.getScrollOffset()"',
				'button_callback'     => array('tl_files', 'copyFile')
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_files']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"',
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
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
				'button_callback'     => array('tl_files', 'deleteFile')
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'name,meta'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'type' => array
		(
			'sql'                     => "varchar(16) NOT NULL default ''"
		),
		'path' => array
		(
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'extension' => array
		(
			'sql'                     => "varchar(16) NOT NULL default ''"
		),
		'hash' => array
		(
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'found' => array
		(
			'sql'                     => "char(1) NOT NULL default '1'"
		),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_files']['name'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'decodeEntities'=>true),
			'save_callback' => array
			(
				array('tl_files', 'checkFilename')
			),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'meta' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_files']['meta'],
			'inputType'               => 'metaWizard',
			'eval'                    => array('allowHtml'=>true),
			'sql'                     => "blob NULL"
		)
	)
);



/**
 * Class tl_files
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
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

		// Permissions
		if (!is_array($this->User->fop))
		{
			$this->User->fop = array();
		}

		$f1 = $this->User->hasAccess('f1', 'fop');
		$f2 = $this->User->hasAccess('f2', 'fop');
		$f3 = $this->User->hasAccess('f3', 'fop');
		$f4 = $this->User->hasAccess('f4', 'fop');

		// Set the filemounts
		$GLOBALS['TL_DCA']['tl_files']['list']['sorting']['root'] = $this->User->filemounts;

		// Disable the upload button if uploads are not allowed
		if (!$f1)
		{
			$GLOBALS['TL_DCA']['tl_files']['config']['closed'] = true;
		}

		// Disable the edit_all button
		if (!$f2)
		{
			$GLOBALS['TL_DCA']['tl_files']['config']['notEditable'] = true;
		}

		// Disable the delete_all button
		if (!$f3 && !$f4)
		{
			$GLOBALS['TL_DCA']['tl_files']['config']['notDeletable'] = true;
		}

		$session = $this->Session->getData();

		// Set allowed page IDs (edit multiple)
		if (is_array($session['CURRENT']['IDS']))
		{
			if (Input::get('act') == 'editAll' && !$f2)
			{
				$session['CURRENT']['IDS'] = array();
			}

			// Check delete permissions
			else
			{
				$folders = array();
				$delete_all = array();

				foreach ($session['CURRENT']['IDS'] as $id)
				{
					if (is_dir(TL_ROOT . '/' . $id))
					{
						$folders[] = $id;

						if ($f4 || ($f3 && count(scan(TL_ROOT . '/' . $id)) < 1))
						{
							$delete_all[] = $id;
						}
					}
					else
					{
						if (($f3 || $f4) && !in_array(dirname($id), $folders))
						{
							$delete_all[] = $id;
						}
					}
				}

				$session['CURRENT']['IDS'] = $delete_all;
			}
		}

		// Set allowed clipboard IDs
		if (isset($session['CLIPBOARD']['tl_files']) && !$f2)
		{
			$session['CLIPBOARD']['tl_files'] = array();
		}

		// Overwrite session
		$this->Session->setData($session);

		// Check current action
		if (Input::get('act') && Input::get('act') != 'paste')
		{
			switch (Input::get('act'))
			{
				case 'move':
					if (!$f1)
					{
						$this->log('No permission to upload files', 'tl_files checkPermission()', TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}
					break;

				case 'edit':
				case 'create':
				case 'copy':
				case 'copyAll':
				case 'cut':
				case 'cutAll':
					if (!$f2)
					{
						$this->log('No permission to create, edit, copy or move files', 'tl_files checkPermission()', TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}
					break;

				case 'delete':
					$strFile = Input::get('id', true);
					if (is_dir(TL_ROOT . '/' . $strFile))
					{
						$files = scan(TL_ROOT . '/' . $strFile);
						if (!empty($files) && !$f4)
						{
							$this->log('No permission to delete folder "'.$strFile.'" recursively', 'tl_files checkPermission()', TL_ERROR);
							$this->redirect('contao/main.php?act=error');
						}
						elseif (!$f3)
						{
							$this->log('No permission to delete folder "'.$strFile.'"', 'tl_files checkPermission()', TL_ERROR);
							$this->redirect('contao/main.php?act=error');
						}
					}
					elseif (!$f3)
					{
						$this->log('No permission to delete file "'.$strFile.'"', 'tl_files checkPermission()', TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}
					break;

				default:
					if (empty($this->User->fop))
					{
						$this->log('No permission to manipulate files', 'tl_files checkPermission()', TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}
					break;
			}
		}
	}


	/**
	 * Add the breadcrumb menu
	 */
	public function addBreadcrumb()
	{
		// Set a new node
		if (isset($_GET['node']))
		{
			$this->Session->set('tl_files_node', Input::get('node', true));
			$this->redirect(preg_replace('/(&|\?)node=[^&]*/', '', Environment::get('request')));
		}

		$strNode = $this->Session->get('tl_files_node');

		if ($strNode == '')
		{
			return;
		}

		// Currently selected folder does not exist
		if (!is_dir(TL_ROOT . '/' . $strNode))
		{
			$this->Session->set('tl_files_node', '');
			return;
		}

		$strPath = $GLOBALS['TL_CONFIG']['uploadPath'];
		$arrNodes = explode('/', preg_replace('/^' . preg_quote($GLOBALS['TL_CONFIG']['uploadPath'], '/') . '\//', '', $strNode));
		$arrLinks = array();

		// Add root link
		$arrLinks[] = '<img src="' . TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/filemounts.gif" width="18" height="18" alt=""> <a href="' . $this->addToUrl('node=') . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectAllNodes']).'">' . $GLOBALS['TL_LANG']['MSC']['filterAll'] . '</a>';

		// Generate breadcrumb trail
		foreach ($arrNodes as $strFolder)
		{
			$strPath .= '/' . $strFolder;

			// Do not show pages which are not mounted
			if (!$this->User->isAdmin && !$this->User->hasAccess($strPath, 'filemounts'))
			{
				continue;
			}

			// No link for the active folder
			if ($strFolder == basename($strNode))
			{
				$arrLinks[] = '<img src="' . TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/folderC.gif" width="18" height="18" alt=""> ' . $strFolder;
			}
			else
			{
				$arrLinks[] = '<img src="' . TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/folderC.gif" width="18" height="18" alt=""> <a href="' . $this->addToUrl('node='.$strPath) . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectNode']).'">' . $strFolder . '</a>';
			}
		}

		// Check whether the node is mounted
		if (!$this->User->isAdmin && !$this->User->hasAccess($strNode, 'filemounts'))
		{
			$this->Session->set('tl_files_node', '');

			$this->log('Folder ID '.$strNode.' was not mounted', 'tl_files addBreadcrumb', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
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
	 * Check a file name and romanize it
	 * @param mixed
	 * @param \DataContainer
	 * @return mixed
	 * @throws \Exception
	 */
	public function checkFilename($varValue, DataContainer $dc)
	{
		$varValue = utf8_romanize($varValue);
		$varValue = str_replace('"', '', $varValue);

		if (strpos($varValue, '/') !== false || preg_match('/\.$/', $varValue))
		{
			throw new Exception($GLOBALS['TL_LANG']['ERR']['invalidName']);
		}

		if (is_file(TL_ROOT .'/'. $dc->path .'/'. $varValue . $dc->extension))
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['fileExists'], $dc->path .'/'. $varValue . $dc->extension));
		}

		return $varValue;
	}


	/**
	 * Return the sync files button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function syncFiles($href, $label, $title, $class, $attributes)
	{
		return $this->User->isAdmin ? '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'" class="'.$class.'"'.$attributes.'>'.$label.'</a> ' : '';
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
		return ($this->User->isAdmin || $this->User->hasAccess('f2', 'fop')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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
		return ($this->User->isAdmin || $this->User->hasAccess('f2', 'fop')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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
		return ($this->User->isAdmin || $this->User->hasAccess('f2', 'fop')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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
		if (is_dir(TL_ROOT . '/' . $row['id']) && count(scan(TL_ROOT . '/' . $row['id'])) > 0)
		{
			return ($this->User->isAdmin || $this->User->hasAccess('f4', 'fop')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
		}

		return ($this->User->isAdmin || $this->User->hasAccess('f3', 'fop') || $this->User->hasAccess('f4', 'fop')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('f5', 'fop'))
		{
			return '';
		}

		$strDecoded = rawurldecode($row['id']);

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
