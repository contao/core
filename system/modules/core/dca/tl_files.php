<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
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
			array('tl_files', 'checkImportantPart')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index',
				'uuid' => 'unique',
				'path' => 'index(333)', // not unique (see #7725)
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
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_files']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirmFile'] . '\'))return false;Backend.getScrollOffset()"',
				'button_callback'     => array('tl_files', 'deleteFile')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_files']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif',
				'button_callback'     => array('tl_files', 'showFile')
			),
			'source' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_files']['source'],
				'href'                => 'act=source',
				'icon'                => 'editor.gif',
				'button_callback'     => array('tl_files', 'editSource')
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'name,protected,importantPartX,importantPartY,importantPartWidth,importantPartHeight;meta'
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
			'sql'                     => "binary(16) NULL"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'uuid' => array
		(
			'sql'                     => "binary(16) NULL"
		),
		'type' => array
		(
			'sql'                     => "varchar(16) NOT NULL default ''"
		),
		'path' => array
		(
			'eval'                    => array('unique'=>true),
			'sql'                     => "varchar(1022) NOT NULL default ''",
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
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'wizard' => array
			(
				array('tl_files', 'addFileLocation')
			),
			'save_callback' => array
			(
				array('tl_files', 'checkFilename')
			),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'protected' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_files']['protected'],
			'input_field_callback'    => array('tl_files', 'protectFolder'),
			'eval'                    => array('tl_class'=>'w50 m12')
		),
		'importantPartX' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_files']['importantPartX'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50 clr'),
			'sql'                     => "int(10) NOT NULL default '0'"
		),
		'importantPartY' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_files']['importantPartY'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50'),
			'sql'                     => "int(10) NOT NULL default '0'"
		),
		'importantPartWidth' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_files']['importantPartWidth'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50'),
			'sql'                     => "int(10) NOT NULL default '0'"
		),
		'importantPartHeight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_files']['importantPartHeight'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50'),
			'sql'                     => "int(10) NOT NULL default '0'"
		),
		'meta' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_files']['meta'],
			'inputType'               => 'metaWizard',
			'eval'                    => array('allowHtml'=>true, 'metaFields'=>array('title', 'link', 'caption')),
			'sql'                     => "blob NULL"
		)
	)
);



/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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

		$canUpload = $this->User->hasAccess('f1', 'fop');
		$canEdit = $this->User->hasAccess('f2', 'fop');
		$canDeleteOne = $this->User->hasAccess('f3', 'fop');
		$canDeleteRecursive = $this->User->hasAccess('f4', 'fop');

		// Set the filemounts
		$GLOBALS['TL_DCA']['tl_files']['list']['sorting']['root'] = $this->User->filemounts;

		// Disable the upload button if uploads are not allowed
		if (!$canUpload)
		{
			$GLOBALS['TL_DCA']['tl_files']['config']['closed'] = true;
		}

		// Disable the edit_all button
		if (!$canEdit)
		{
			$GLOBALS['TL_DCA']['tl_files']['config']['notEditable'] = true;
		}

		// Disable the delete_all button
		if (!$canDeleteOne && !$canDeleteRecursive)
		{
			$GLOBALS['TL_DCA']['tl_files']['config']['notDeletable'] = true;
		}

		$session = $this->Session->getData();

		// Set allowed page IDs (edit multiple)
		if (is_array($session['CURRENT']['IDS']))
		{
			if (Input::get('act') == 'editAll')
			{
				if (!$canEdit)
				{
					$session['CURRENT']['IDS'] = array();
				}
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

						if ($canDeleteRecursive || ($canDeleteOne && count(scan(TL_ROOT . '/' . $id)) < 1))
						{
							$delete_all[] = $id;
						}
					}
					else
					{
						if (($canDeleteOne || $canDeleteRecursive) && !in_array(dirname($id), $folders))
						{
							$delete_all[] = $id;
						}
					}
				}

				$session['CURRENT']['IDS'] = $delete_all;
			}
		}

		// Set allowed clipboard IDs
		if (isset($session['CLIPBOARD']['tl_files']) && !$canEdit)
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
					if (!$canUpload)
					{
						$this->log('No permission to upload files', __METHOD__, TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}
					break;

				case 'edit':
				case 'create':
				case 'copy':
				case 'copyAll':
				case 'cut':
				case 'cutAll':
					if (!$canEdit)
					{
						$this->log('No permission to create, edit, copy or move files', __METHOD__, TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}
					break;

				case 'delete':
					$strFile = Input::get('id', true);
					if (is_dir(TL_ROOT . '/' . $strFile))
					{
						$files = scan(TL_ROOT . '/' . $strFile);
						if (!empty($files) && !$canDeleteRecursive)
						{
							$this->log('No permission to delete folder "'.$strFile.'" recursively', __METHOD__, TL_ERROR);
							$this->redirect('contao/main.php?act=error');
						}
						elseif (!$canDeleteOne)
						{
							$this->log('No permission to delete folder "'.$strFile.'"', __METHOD__, TL_ERROR);
							$this->redirect('contao/main.php?act=error');
						}
					}
					elseif (!$canDeleteOne)
					{
						$this->log('No permission to delete file "'.$strFile.'"', __METHOD__, TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}
					break;

				default:
					if (empty($this->User->fop))
					{
						$this->log('No permission to manipulate files', __METHOD__, TL_ERROR);
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
		Backend::addFilesBreadcrumb();
	}


	/**
	 * Only show the important part fields for images
	 *
	 * @param DataContainer $dc
	 */
	public function checkImportantPart(DataContainer $dc)
	{
		if (!$dc->id)
		{
			return;
		}

		if (is_dir(TL_ROOT . '/' . $dc->id) || !in_array(strtolower(pathinfo($dc->id, PATHINFO_EXTENSION)), trimsplit(',', Config::get('validImageTypes'))))
		{
			$GLOBALS['TL_DCA'][$dc->table]['palettes'] = str_replace(',importantPartX,importantPartY,importantPartWidth,importantPartHeight', '', $GLOBALS['TL_DCA'][$dc->table]['palettes']);
		}
	}


	/**
	 * Add the file location instead of the help text (see #6503)
	 *
	 * @param DataContainer $dc
	 *
	 * @return string
	 */
	public function addFileLocation(DataContainer $dc)
	{
		if ($dc->activeRecord === null)
		{
			return '';
		}

		// Unset the default help text
		unset($GLOBALS['TL_DCA'][$dc->table]['fields'][$dc->field]['label'][1]);

		return '<p class="tl_help tl_tip">' . sprintf($GLOBALS['TL_LANG']['tl_files']['fileLocation'], $dc->activeRecord->path) . '</p>';
	}


	/**
	 * Check a file name and romanize it
	 *
	 * @param mixed $varValue
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 */
	public function checkFilename($varValue)
	{
		$varValue = utf8_romanize($varValue);
		$varValue = str_replace('"', '', $varValue);

		if (strpos($varValue, '/') !== false || preg_match('/\.$/', $varValue))
		{
			throw new Exception($GLOBALS['TL_LANG']['ERR']['invalidName']);
		}

		return $varValue;
	}


	/**
	 * Return the sync files button
	 *
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $class
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function syncFiles($href, $label, $title, $class, $attributes)
	{
		return $this->User->hasAccess('f6', 'fop') ? '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'" class="'.$class.'"'.$attributes.'>'.$label.'</a> ' : '';
	}


	/**
	 * Return the edit file button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function editFile($row, $href, $label, $title, $icon, $attributes)
	{
		return $this->User->hasAccess('f2', 'fop') ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title, false, true).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the copy file button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function copyFile($row, $href, $label, $title, $icon, $attributes)
	{
		return $this->User->hasAccess('f2', 'fop') ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title, false, true).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the cut file button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function cutFile($row, $href, $label, $title, $icon, $attributes)
	{
		return $this->User->hasAccess('f2', 'fop') ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title, false, true).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the delete file button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function deleteFile($row, $href, $label, $title, $icon, $attributes)
	{
		if (is_dir(TL_ROOT . '/' . $row['id']) && count(scan(TL_ROOT . '/' . $row['id'])) > 0)
		{
			return $this->User->hasAccess('f4', 'fop') ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title, false, true).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
		}
		else
		{
			return ($this->User->hasAccess('f3', 'fop') || $this->User->hasAccess('f4', 'fop')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title, false, true).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
		}
	}


	/**
	 * Return the edit file source button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function editSource($row, $href, $label, $title, $icon, $attributes)
	{
		if (!$this->User->hasAccess('f5', 'fop'))
		{
			return '';
		}

		$strDecoded = rawurldecode($row['id']);

		if (is_dir(TL_ROOT . '/' . $strDecoded))
		{
			return '';
		}

		$objFile = new File($strDecoded, true);

		if (!in_array($objFile->extension, trimsplit(',', Config::get('editableFiles'))))
		{
			return Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
		}

		return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title, false, true).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
	}


	/**
	 * Return the show file button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function showFile($row, $href, $label, $title, $icon, $attributes)
	{
		if (Input::get('popup'))
		{
			return '';
		}
		else
		{
			return '<a href="contao/popup.php?src=' . base64_encode($row['id']) . '" title="'.specialchars($title, false, true).'"'.$attributes.' onclick="Backend.openModalIframe({\'width\':'.$row['popupWidth'].',\'title\':\''.str_replace("'", "\\'", specialchars($row['fileNameEncoded'], false, true)).'\',\'url\':this.href,\'height\':'.$row['popupHeight'].'});return false">'.Image::getHtml($icon, $label).'</a> ';
		}
	}


	/**
	 * Return a checkbox to delete session data
	 *
	 * @param DataContainer $dc
	 *
	 * @return string
	 */
	public function protectFolder(DataContainer $dc)
	{
		$count = 0;
		$strPath = $dc->id;

		// Check whether the temporary name has been replaced already (see #6432)
		if (Input::post('name') && ($strNewPath = str_replace('__new__', Input::post('name'), $strPath, $count)) && $count > 0 && is_dir(TL_ROOT . '/' . $strNewPath))
		{
			$strPath = $strNewPath;
		}

		// Only show for folders (see #5660)
		if (!is_dir(TL_ROOT . '/' . $strPath))
		{
			return '';
		}

		$blnProtected = file_exists(TL_ROOT . '/' . $strPath . '/.htaccess');

		// Protect or unprotect the folder
		if (Input::post('FORM_SUBMIT') == 'tl_files')
		{
			if (Input::post('protected'))
			{
				if (!$blnProtected)
				{
					$blnProtected = true;
					$objFolder = new Folder($strPath);
					$objFolder->protect();
				}
			}
			else
			{
				if ($blnProtected)
				{
					$blnProtected = false;
					$objFolder = new Folder($strPath);
					$objFolder->unprotect();
				}
			}
		}

		// Show a note for non-Apache servers
		if (strpos(Environment::get('serverSoftware'), 'Apache') === false)
		{
			Message::addInfo($GLOBALS['TL_LANG']['tl_files']['htaccessInfo']);
		}

		return '
<div class="' . $GLOBALS['TL_DCA'][$dc->table]['fields'][$dc->field]['eval']['tl_class'] . ' cbx">
  <div id="ctrl_' . $dc->field . '" class="tl_checkbox_single_container">
    <input type="hidden" name="' . $dc->inputName . '" value=""><input type="checkbox" name="' . $dc->inputName . '" id="opt_' . $dc->field . '_0" class="tl_checkbox" value="1"' . ($blnProtected ? ' checked="checked"' : '') . ' onfocus="Backend.getScrollOffset()"> <label for="opt_' . $dc->field . '_0">' . $GLOBALS['TL_LANG']['tl_files']['protected'][0] . '</label>
  </div>' . (Config::get('showHelp') ? '
  <p class="tl_help tl_tip">' . $GLOBALS['TL_LANG']['tl_files']['protected'][1] . '</p>' : '') . '
</div>';
	}
}
