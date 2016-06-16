<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Provide methods to modify the file system.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class DC_Folder extends \DataContainer implements \listable, \editable
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
	 * Messages
	 * @var array
	 */
	protected $arrMessages = array();

	/**
	 * Counts
	 * @var array
	 */
	protected $arrCounts = array();

	/**
	 * True if a new version has to be created
	 * @var boolean
	 */
	protected $blnCreateNewVersion = false;

	/**
	 * Database assisted
	 * @var boolean
	 */
	protected $blnIsDbAssisted = false;


	/**
	 * Initialize the object
	 *
	 * @param string $strTable
	 */
	public function __construct($strTable)
	{
		parent::__construct();

		// Check the request token (see #4007)
		if (isset($_GET['act']))
		{
			if (!isset($_GET['rt']) || !\RequestToken::validate(\Input::get('rt')))
			{
				$this->Session->set('INVALID_TOKEN_URL', \Environment::get('request'));
				$this->redirect('contao/confirm.php');
			}
		}

		$this->intId = \Input::get('id', true);

		// Clear the clipboard
		if (isset($_GET['clipboard']))
		{
			$this->Session->set('CLIPBOARD', array());
			$this->redirect($this->getReferer());
		}

		// Check whether the table is defined
		if ($strTable == '' || !isset($GLOBALS['TL_DCA'][$strTable]))
		{
			$this->log('Could not load data container configuration for "' . $strTable . '"', __METHOD__, TL_ERROR);
			trigger_error('Could not load data container configuration', E_USER_ERROR);
		}

		// Check permission to create new folders
		if (\Input::get('act') == 'paste' && \Input::get('mode') == 'create' && isset($GLOBALS['TL_DCA'][$strTable]['list']['new']))
		{
			$this->log('Attempt to create a new folder although the method has been overwritten in the data container', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Set IDs and redirect
		if (\Input::post('FORM_SUBMIT') == 'tl_select')
		{
			$ids = \Input::post('IDS');

			if (empty($ids) || !is_array($ids))
			{
				$this->reload();
			}

			// Decode the values (see #5764)
			$ids = array_map('rawurldecode', $ids);

			$session = $this->Session->getData();
			$session['CURRENT']['IDS'] = $ids;
			$this->Session->setData($session);

			if (isset($_POST['edit']))
			{
				$this->redirect(str_replace('act=select', 'act=editAll', \Environment::get('request')));
			}
			elseif (isset($_POST['delete']))
			{
				$this->redirect(str_replace('act=select', 'act=deleteAll', \Environment::get('request')));
			}
			elseif (isset($_POST['cut']) || isset($_POST['copy']))
			{
				$arrClipboard = $this->Session->get('CLIPBOARD');

				$arrClipboard[$strTable] = array
				(
					'id' => $ids,
					'mode' => (isset($_POST['cut']) ? 'cutAll' : 'copyAll')
				);

				$this->Session->set('CLIPBOARD', $arrClipboard);
				$this->redirect($this->getReferer());
			}
		}

		$this->strTable = $strTable;
		$this->blnIsDbAssisted = $GLOBALS['TL_DCA'][$strTable]['config']['databaseAssisted'];

		// Check for valid file types
		if ($GLOBALS['TL_DCA'][$this->strTable]['config']['validFileTypes'])
		{
			$this->arrValidFileTypes = trimsplit(',', strtolower($GLOBALS['TL_DCA'][$this->strTable]['config']['validFileTypes']));
		}

		// Call onload_callback (e.g. to check permissions)
		if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['onload_callback']))
		{
			foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['onload_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$this->{$callback[0]}->{$callback[1]}($this);
				}
				elseif (is_callable($callback))
				{
					$callback($this);
				}
			}
		}

		// Get all filemounts (root folders)
		if (is_array($GLOBALS['TL_DCA'][$strTable]['list']['sorting']['root']))
		{
			$this->arrFilemounts = $this->eliminateNestedPaths($GLOBALS['TL_DCA'][$strTable]['list']['sorting']['root']);
		}
	}


	/**
	 * Set an object property
	 *
	 * @param string $strKey
	 * @param mixed  $varValue
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'createNewVersion':
				$this->blnCreateNewVersion = (bool) $varValue;
				break;

			default;
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Return an object property
	 *
	 * @param string $strKey
	 *
	 * @return mixed
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'path':
				return $this->strPath;
				break;

			case 'extension':
				return $this->strExtension;
				break;

			case 'createNewVersion':
				return $this->blnCreateNewVersion;
				break;

			case 'isDbAssisted':
				return $this->blnIsDbAssisted;
				break;
		}

		return parent::__get($strKey);
	}


	/**
	 * List all files and folders of the file system
	 *
	 * @return string
	 */
	public function showAll()
	{
		$return = '';

		// Add to clipboard
		if (\Input::get('act') == 'paste')
		{
			if (\Input::get('mode') != 'create' && \Input::get('mode') != 'move')
			{
				$this->isValid($this->intId);
			}

			$arrClipboard = $this->Session->get('CLIPBOARD');

			$arrClipboard[$this->strTable] = array
			(
				'id' => $this->urlEncode($this->intId),
				'childs' => \Input::get('childs'),
				'mode' => \Input::get('mode')
			);

			$this->Session->set('CLIPBOARD', $arrClipboard);
		}

		// Get the session data and toggle the nodes
		if (\Input::get('tg') == 'all')
		{
			$session = $this->Session->getData();

			// Expand tree
			if (!is_array($session['filetree']) || empty($session['filetree']) || current($session['filetree']) != 1)
			{
				$session['filetree'] = $this->getMD5Folders(\Config::get('uploadPath'));
			}
			// Collapse tree
			else
			{
				$session['filetree'] = array();
			}

			$this->Session->setData($session);
			$this->redirect(preg_replace('/(&(amp;)?|\?)tg=[^& ]*/i', '', \Environment::get('request')));
		}

		$blnClipboard = false;
		$arrClipboard = $this->Session->get('CLIPBOARD');

		// Check clipboard
		if (!empty($arrClipboard[$this->strTable]))
		{
			$blnClipboard = true;
			$arrClipboard = $arrClipboard[$this->strTable];
		}

		// Load the fonts to display the paste hint
		\Config::set('loadGoogleFonts', $blnClipboard);

		$this->import('Files');
		$this->import('BackendUser', 'User');

		// Call recursive function tree()
		if (empty($this->arrFilemounts) && !is_array($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['root']) && $GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['root'] !== false)
		{
			$return .= $this->generateTree(TL_ROOT . '/' . \Config::get('uploadPath'), 0, false, false, ($blnClipboard ? $arrClipboard : false));
		}
		else
		{
			for ($i=0, $c=count($this->arrFilemounts); $i<$c; $i++)
			{
				if ($this->arrFilemounts[$i] != '' && is_dir(TL_ROOT . '/' . $this->arrFilemounts[$i]))
				{
					$return .= $this->generateTree(TL_ROOT . '/' . $this->arrFilemounts[$i], 0, true, false, ($blnClipboard ? $arrClipboard : false));
				}
			}
		}

		// Check for the "create new" button
		$clsNew = 'header_new_folder';
		$lblNew = $GLOBALS['TL_LANG'][$this->strTable]['new'][0];
		$ttlNew = $GLOBALS['TL_LANG'][$this->strTable]['new'][1];
		$hrfNew = '&amp;act=paste&amp;mode=create';

		if (isset($GLOBALS['TL_DCA'][$this->strTable]['list']['new']))
		{
			$clsNew = $GLOBALS['TL_DCA'][$this->strTable]['list']['new']['class'];
			$lblNew = $GLOBALS['TL_DCA'][$this->strTable]['list']['new']['label'][0];
			$ttlNew = $GLOBALS['TL_DCA'][$this->strTable]['list']['new']['label'][1];
			$hrfNew = $GLOBALS['TL_DCA'][$this->strTable]['list']['new']['href'];
		}

		$imagePasteInto = \Image::getHtml('pasteinto.gif', $GLOBALS['TL_LANG'][$this->strTable]['pasteinto'][0]);

		// Build the tree
		$return = '
<div id="tl_buttons">'.((\Input::get('act') == 'select') ? '
<a href="'.$this->getReferer(true).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']).'" accesskey="b" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a> ' : '') . ((\Input::get('act') != 'select' && !$blnClipboard && !$GLOBALS['TL_DCA'][$this->strTable]['config']['closed'] && !$GLOBALS['TL_DCA'][$this->strTable]['config']['notCreatable']) ? '
<a href="'.$this->addToUrl($hrfNew).'" class="'.$clsNew.'" title="'.specialchars($ttlNew).'" accesskey="n" onclick="Backend.getScrollOffset()">'.$lblNew.'</a>
<a href="'.$this->addToUrl('&amp;act=paste&amp;mode=move').'" class="header_new" title="'.specialchars($GLOBALS['TL_LANG'][$this->strTable]['move'][1]).'" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG'][$this->strTable]['move'][0].'</a> ' : '') . ($blnClipboard ? '
<a href="'.$this->addToUrl('clipboard=1').'" class="header_clipboard" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['clearClipboard']).'" accesskey="x">'.$GLOBALS['TL_LANG']['MSC']['clearClipboard'].'</a> ' : $this->generateGlobalButtons()) . '
</div>' . \Message::generate(true) . ((\Input::get('act') == 'select') ? '

<form action="'.ampersand(\Environment::get('request'), true).'" id="tl_select" class="tl_form'.((\Input::get('act') == 'select') ? ' unselectable' : '').'" method="post" novalidate>
<div class="tl_formbody">
<input type="hidden" name="FORM_SUBMIT" value="tl_select">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">' : '').($blnClipboard ? '

<div id="paste_hint">
  <p>'.$GLOBALS['TL_LANG']['MSC']['selectNewPosition'].'</p>
</div>' : '').'

<div class="tl_listing_container tree_view" id="tl_listing">'.(isset($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['breadcrumb']) ? $GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['breadcrumb'] : '').((\Input::get('act') == 'select') ? '

<div class="tl_select_trigger">
<label for="tl_select_trigger" class="tl_select_label">'.$GLOBALS['TL_LANG']['MSC']['selectAll'].'</label> <input type="checkbox" id="tl_select_trigger" onclick="Backend.toggleCheckboxes(this)" class="tl_tree_checkbox">
</div>' : '').'

<ul class="tl_listing">
  <li class="tl_folder_top" onmouseover="Theme.hoverDiv(this,1)" onmouseout="Theme.hoverDiv(this,0)"><div class="tl_left">'.\Image::getHtml('filemounts.gif').' '.$GLOBALS['TL_LANG']['MSC']['filetree'].'</div> <div class="tl_right">'.(($blnClipboard && empty($this->arrFilemounts) && !is_array($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['root']) && $GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['root'] !== false) ? '<a href="'.$this->addToUrl('&amp;act='.$arrClipboard['mode'].'&amp;mode=2&amp;pid='.\Config::get('uploadPath').(!is_array($arrClipboard['id']) ? '&amp;id='.$arrClipboard['id'] : '')).'" title="'.specialchars($GLOBALS['TL_LANG'][$this->strTable]['pasteinto'][1]).'" onclick="Backend.getScrollOffset()">'.$imagePasteInto.'</a>' : '&nbsp;').'</div><div style="clear:both"></div></li>'.$return.'
</ul>

</div>';

		// Close the form
		if (\Input::get('act') == 'select')
		{
			// Submit buttons
			$arrButtons = array();

			if (!$GLOBALS['TL_DCA'][$this->strTable]['config']['notDeletable'])
			{
				$arrButtons['delete'] = '<input type="submit" name="delete" id="delete" class="tl_submit" accesskey="d" onclick="return confirm(\''.$GLOBALS['TL_LANG']['MSC']['delAllConfirmFile'].'\')" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['deleteSelected']).'">';
			}

			if (!$GLOBALS['TL_DCA'][$this->strTable]['config']['notSortable'])
			{
				$arrButtons['cut'] = '<input type="submit" name="cut" id="cut" class="tl_submit" accesskey="x" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['moveSelected']).'">';
			}

			if (!$GLOBALS['TL_DCA'][$this->strTable]['config']['notCopyable'])
			{
				$arrButtons['copy'] = '<input type="submit" name="copy" id="copy" class="tl_submit" accesskey="c" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['copySelected']).'">';
			}

			if (!$GLOBALS['TL_DCA'][$this->strTable]['config']['notEditable'])
			{
				$arrButtons['edit'] = '<input type="submit" name="edit" id="edit" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['editSelected']).'">';
			}

			// Call the buttons_callback (see #4691)
			if (is_array($GLOBALS['TL_DCA'][$this->strTable]['select']['buttons_callback']))
			{
				foreach ($GLOBALS['TL_DCA'][$this->strTable]['select']['buttons_callback'] as $callback)
				{
					if (is_array($callback))
					{
						$this->import($callback[0]);
						$arrButtons = $this->{$callback[0]}->{$callback[1]}($arrButtons, $this);
					}
					elseif (is_callable($callback))
					{
						$arrButtons = $callback($arrButtons, $this);
					}
				}
			}

			$return .= '

<div class="tl_formbody_submit" style="text-align:right">

<div class="tl_submit_container">
  ' . implode(' ', $arrButtons) . '
</div>

</div>
</div>
</form>';
		}

		return $return;
	}


	/**
	 * Automatically switch to showAll
	 *
	 * @return string
	 */
	public function show()
	{
		return $this->showAll();
	}


	/**
	 * Create a new folder
	 */
	public function create()
	{
		if ($GLOBALS['TL_DCA'][$this->strTable]['config']['notCreatable'])
		{
			$this->log('Table "'.$this->strTable.'" is not creatable', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->import('Files');
		$strFolder = \Input::get('pid', true);

		if ($strFolder == '' || !file_exists(TL_ROOT . '/' . $strFolder) || !$this->isMounted($strFolder))
		{
			$this->log('Folder "'.$strFolder.'" was not mounted or is not a directory', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Empty clipboard
		$arrClipboard = $this->Session->get('CLIPBOARD');
		$arrClipboard[$this->strTable] = array();
		$this->Session->set('CLIPBOARD', $arrClipboard);

		$this->Files->mkdir($strFolder . '/__new__');
		$this->redirect(html_entity_decode($this->switchToEdit($this->urlEncode($strFolder) . '/__new__')));
	}


	/**
	 * Move an existing file or folder
	 *
	 * @param string $source
	 */
	public function cut($source=null)
	{
		if ($GLOBALS['TL_DCA'][$this->strTable]['config']['notSortable'])
		{
			$this->log('Table "'.$this->strTable.'" is not sortable', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$strFolder = \Input::get('pid', true);
		$blnDoNotRedirect = ($source !== null);

		if ($source === null)
		{
			$source = $this->intId;
		}

		$this->isValid($source);

		if (!file_exists(TL_ROOT . '/' . $source) || !$this->isMounted($source))
		{
			$this->log('File or folder "'.$source.'" was not mounted or could not be found', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		if (!file_exists(TL_ROOT . '/' . $strFolder) || !$this->isMounted($strFolder))
		{
			$this->log('Parent folder "'.$strFolder.'" was not mounted or is not a directory', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Avoid a circular reference
		if (preg_match('/^' . preg_quote($source, '/') . '/i', $strFolder))
		{
			$this->log('Attempt to move the folder "'.$source.'" to "'.$strFolder.'" (circular reference)', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Empty clipboard
		$arrClipboard = $this->Session->get('CLIPBOARD');
		$arrClipboard[$this->strTable] = array();
		$this->Session->set('CLIPBOARD', $arrClipboard);

		$this->import('Files');

		// Calculate the destination path
		$destination = str_replace(dirname($source), $strFolder, $source);

		// Do not move if the target exists and would be overriden (not possible for folders anyway)
		if (file_exists(TL_ROOT . '/' . $destination))
		{
			\Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['filetarget'], basename($source), dirname($destination)));
		}
		else
		{
			$this->Files->rename($source, $destination);

			// Update the database AFTER the file has been moved
			if ($this->blnIsDbAssisted)
			{
				$syncSource = \Dbafs::shouldBeSynchronized($source);
				$syncTarget = \Dbafs::shouldBeSynchronized($destination);

				if ($syncSource && $syncTarget)
				{
					\Dbafs::moveResource($source, $destination);
				}
				elseif ($syncSource)
				{
					\Dbafs::deleteResource($source);
				}
				elseif ($syncTarget)
				{
					\Dbafs::addResource($destination);
				}
			}

			// Call the oncut_callback
			if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['oncut_callback']))
			{
				foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['oncut_callback'] as $callback)
				{
					if (is_array($callback))
					{
						$this->import($callback[0]);
						$this->{$callback[0]}->{$callback[1]}($source, $destination, $this);
					}
					elseif (is_callable($callback))
					{
						$callback($source, $destination, $this);
					}
				}
			}

			// Add a log entry
			$this->log('File or folder "'.$source.'" has been moved to "'.$destination.'"', __METHOD__, TL_FILES);
		}

		// Redirect
		if (!$blnDoNotRedirect)
		{
			$this->redirect($this->getReferer());
		}
	}


	/**
	 * Move all selected files and folders
	 */
	public function cutAll()
	{
		if ($GLOBALS['TL_DCA'][$this->strTable]['config']['notSortable'])
		{
			$this->log('Table "'.$this->strTable.'" is not sortable', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// PID is mandatory
		if (!strlen(\Input::get('pid', true)))
		{
			$this->redirect($this->getReferer());
		}

		$arrClipboard = $this->Session->get('CLIPBOARD');

		if (isset($arrClipboard[$this->strTable]) && is_array($arrClipboard[$this->strTable]['id']))
		{
			foreach ($arrClipboard[$this->strTable]['id'] as $id)
			{
				$this->cut($id); // do not urldecode() here (see #6840)
			}
		}

		$this->redirect($this->getReferer());
	}


	/**
	 * Recursively duplicate files and folders
	 *
	 * @param string $source
	 * @param string $destination
	 */
	public function copy($source=null, $destination=null)
	{
		if ($GLOBALS['TL_DCA'][$this->strTable]['config']['notCopyable'])
		{
			$this->log('Table "'.$this->strTable.'" is not copyable', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$strFolder = \Input::get('pid', true);
		$blnDoNotRedirect = ($source !== null);

		if ($source === null)
		{
			$source = $this->intId;
		}

		if ($destination === null)
		{
			$destination = str_replace(dirname($source), $strFolder, $source);
		}

		$this->isValid($source);
		$this->isValid($destination);

		if (!file_exists(TL_ROOT . '/' . $source) || !$this->isMounted($source))
		{
			$this->log('File or folder "'.$source.'" was not mounted or could not be found', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		if (!file_exists(TL_ROOT . '/' . $strFolder) || !$this->isMounted($strFolder))
		{
			$this->log('Parent folder "'.$strFolder.'" was not mounted or is not a directory', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Avoid a circular reference
		if (preg_match('/^' . preg_quote($source, '/') . '/i', $strFolder))
		{
			$this->log('Attempt to copy the folder "'.$source.'" to "'.$strFolder.'" (circular reference)', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Empty clipboard
		$arrClipboard = $this->Session->get('CLIPBOARD');
		$arrClipboard[$this->strTable] = array();
		$this->Session->set('CLIPBOARD', $arrClipboard);

		$this->import('Files');

		// Copy folders
		if (is_dir(TL_ROOT . '/' . $source))
		{
			$count = 1;
			$new = $destination;

			// Add a suffix if the folder exists
			while (is_dir(TL_ROOT . '/' . $new) && $count < 12)
			{
				$new = $destination . '_' . $count++;
			}

			$destination = $new;
			$this->Files->rcopy($source, $destination);
		}

		// Copy a file
		else
		{
			$count = 1;
			$new = $destination;
			$ext = strtolower(substr($destination, strrpos($destination, '.') + 1));

			// Add a suffix if the file exists
			while (file_exists(TL_ROOT . '/' . $new) && $count < 12)
			{
				$new = str_replace('.' . $ext, '_' . $count++ . '.' . $ext, $destination);
			}

			$destination = $new;
			$this->Files->copy($source, $destination);
		}

		// Update the database AFTER the file has been copied
		if ($this->blnIsDbAssisted)
		{
			$syncSource = \Dbafs::shouldBeSynchronized($source);
			$syncTarget = \Dbafs::shouldBeSynchronized($destination);

			if ($syncSource && $syncTarget)
			{
				\Dbafs::copyResource($source, $destination);
			}
			elseif ($syncTarget)
			{
				\Dbafs::addResource($destination);
			}
		}

		// Call the oncopy_callback
		if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['oncopy_callback']))
		{
			foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['oncopy_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$this->{$callback[0]}->{$callback[1]}($source, $destination, $this);
				}
				elseif (is_callable($callback))
				{
					$callback($source, $destination, $this);
				}
			}
		}

		// Add a log entry
		$this->log('File or folder "'.$source.'" has been duplicated', __METHOD__, TL_FILES);

		// Redirect
		if (!$blnDoNotRedirect)
		{
			$this->redirect($this->getReferer());
		}
	}


	/**
	 * Move all selected files and folders
	 */
	public function copyAll()
	{
		if ($GLOBALS['TL_DCA'][$this->strTable]['config']['notCopyable'])
		{
			$this->log('Table "'.$this->strTable.'" is not copyable', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// PID is mandatory
		if (!strlen(\Input::get('pid', true)))
		{
			$this->redirect($this->getReferer());
		}

		$arrClipboard = $this->Session->get('CLIPBOARD');

		if (isset($arrClipboard[$this->strTable]) && is_array($arrClipboard[$this->strTable]['id']))
		{
			foreach ($arrClipboard[$this->strTable]['id'] as $id)
			{
				$this->copy($id); // do not urldecode() here (see #6840)
			}
		}

		$this->redirect($this->getReferer());
	}


	/**
	 * Recursively delete files and folders
	 *
	 * @param string $source
	 */
	public function delete($source=null)
	{
		if ($GLOBALS['TL_DCA'][$this->strTable]['config']['notDeletable'])
		{
			$this->log('Table "'.$this->strTable.'" is not deletable', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$blnDoNotRedirect = ($source !== null);

		if ($source === null)
		{
			$source = $this->intId;
		}

		$this->isValid($source);

		// Delete the file or folder
		if (!file_exists(TL_ROOT . '/' . $source) || !$this->isMounted($source))
		{
			$this->log('File or folder "'.$source.'" was not mounted or could not be found', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Call the ondelete_callback
		if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['ondelete_callback']))
		{
			foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['ondelete_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$this->{$callback[0]}->{$callback[1]}($source, $this);
				}
				elseif (is_callable($callback))
				{
					$callback($source, $this);
				}
			}
		}

		$this->import('Files');

		// Delete the folder or file
		if (is_dir(TL_ROOT . '/' . $source))
		{
			$this->Files->rrdir($source);
		}
		else
		{
			$this->Files->delete($source);
		}

		// Update the database AFTER the resource has been deleted
		if ($this->blnIsDbAssisted && \Dbafs::shouldBeSynchronized($source))
		{
			\Dbafs::deleteResource($source);
		}

		// Add a log entry
		$this->log('File or folder "' . $source . '" has been deleted', __METHOD__, TL_FILES);

		// Redirect
		if (!$blnDoNotRedirect)
		{
			$this->redirect($this->getReferer());
		}
	}


	/**
	 * Delete all files and folders that are currently shown
	 */
	public function deleteAll()
	{
		if ($GLOBALS['TL_DCA'][$this->strTable]['config']['notDeletable'])
		{
			$this->log('Table "'.$this->strTable.'" is not deletable', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$session = $this->Session->getData();
		$ids = $session['CURRENT']['IDS'];

		if (is_array($ids) && strlen($ids[0]))
		{
			foreach ($ids as $id)
			{
				$this->delete($id); // do not urldecode() here (see #6840)
			}
		}

		$this->redirect($this->getReferer());
	}


	/**
	 * Automatically switch to showAll
	 *
	 * @return string
	 */
	public function undo()
	{
		return $this->showAll();
	}


	/**
	 * Move one or more local files to the server
	 *
	 * @param boolean $blnIsAjax
	 *
	 * @return string
	 */
	public function move($blnIsAjax=false)
	{
		$strFolder = \Input::get('pid', true);

		if (!file_exists(TL_ROOT . '/' . $strFolder) || !$this->isMounted($strFolder))
		{
			$this->log('Folder "'.$strFolder.'" was not mounted or is not a directory', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		if (!preg_match('/^'.preg_quote(\Config::get('uploadPath'), '/').'/i', $strFolder))
		{
			$this->log('Parent folder "'.$strFolder.'" is not within the files directory', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Empty clipboard
		if (!$blnIsAjax)
		{
			$arrClipboard = $this->Session->get('CLIPBOARD');
			$arrClipboard[$this->strTable] = array();
			$this->Session->set('CLIPBOARD', $arrClipboard);
		}

		// Instantiate the uploader
		$this->import('BackendUser', 'User');
		$class = $this->User->uploader;

		// See #4086
		if (!class_exists($class))
		{
			$class = 'FileUpload';
		}

		/** @var \FileUpload $objUploader */
		$objUploader = new $class();

		// Process the uploaded files
		if (\Input::post('FORM_SUBMIT') == 'tl_upload')
		{
			// Generate the DB entries
			if ($this->blnIsDbAssisted && \Dbafs::shouldBeSynchronized($strFolder))
			{
				// Upload the files
				$arrUploaded = $objUploader->uploadTo($strFolder);

				if (empty($arrUploaded))
				{
					\Message::addError($GLOBALS['TL_LANG']['ERR']['emptyUpload']);
					$this->reload();
				}

				foreach ($arrUploaded as $strFile)
				{
					$objFile = \FilesModel::findByPath($strFile);

					// Existing file is being replaced (see #4818)
					if ($objFile !== null)
					{
						$objFile->tstamp = time();
						$objFile->path   = $strFile;
						$objFile->hash   = md5_file(TL_ROOT . '/' . $strFile);
						$objFile->save();
					}
					else
					{
						\Dbafs::addResource($strFile);
					}
				}
			}
			else
			{
				// Not DB-assisted, so just upload the file
				$arrUploaded = $objUploader->uploadTo($strFolder);
			}

			// HOOK: post upload callback
			if (isset($GLOBALS['TL_HOOKS']['postUpload']) && is_array($GLOBALS['TL_HOOKS']['postUpload']))
			{
				foreach ($GLOBALS['TL_HOOKS']['postUpload'] as $callback)
				{
					if (is_array($callback))
					{
						$this->import($callback[0]);
						$this->{$callback[0]}->{$callback[1]}($arrUploaded);
					}
					elseif (is_callable($callback))
					{
						$callback($arrUploaded);
					}
				}
			}

			// Update the hash of the target folder
			if ($this->blnIsDbAssisted && \Dbafs::shouldBeSynchronized($strFolder))
			{
				\Dbafs::updateFolderHashes($strFolder);
			}

			// Redirect or reload
			if (!$objUploader->hasError())
			{
				// Do not purge the html folder (see #2898)
				if (\Input::post('uploadNback') && !$objUploader->hasResized())
				{
					\Message::reset();
					$this->redirect($this->getReferer());
				}

				$this->reload();
			}
		}

		// Submit buttons
		$arrButtons = array();
		$arrButtons['upload'] = '<input type="submit" name="upload" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG'][$this->strTable]['upload']).'">';
		$arrButtons['uploadNback'] = '<input type="submit" name="uploadNback" class="tl_submit" accesskey="c" value="'.specialchars($GLOBALS['TL_LANG'][$this->strTable]['uploadNback']).'">';

		// Call the buttons_callback (see #4691)
		if (is_array($GLOBALS['TL_DCA'][$this->strTable]['edit']['buttons_callback']))
		{
			foreach ($GLOBALS['TL_DCA'][$this->strTable]['edit']['buttons_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$arrButtons = $this->{$callback[0]}->{$callback[1]}($arrButtons, $this);
				}
				elseif (is_callable($callback))
				{
					$arrButtons = $callback($arrButtons, $this);
				}
			}
		}

		// Display the upload form
		return '
<div id="tl_buttons">
<a href="'.$this->getReferer(true).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']).'" accesskey="b" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>
'.\Message::generate().'
<form action="'.ampersand(\Environment::get('request'), true).'" id="'.$this->strTable.'" class="tl_form" method="post"'.(!empty($this->onsubmit) ? ' onsubmit="'.implode(' ', $this->onsubmit).'"' : '').' enctype="multipart/form-data">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_upload">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
<input type="hidden" name="MAX_FILE_SIZE" value="'.\Config::get('maxFileSize').'">

<div class="tl_tbox">
  <h3>'.$GLOBALS['TL_LANG'][$this->strTable]['fileupload'][0].'</h3>'.$objUploader->generateMarkup().'
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  ' . implode(' ', $arrButtons) . '
</div>

</div>

</form>';
	}


	/**
	 * Auto-generate a form to rename a file or folder
	 *
	 * @return string
	 */
	public function edit()
	{
		$return = '';
		$this->noReload = false;
		$this->isValid($this->intId);

		if (!file_exists(TL_ROOT . '/' . $this->intId) || !$this->isMounted($this->intId))
		{
			$this->log('File or folder "'.$this->intId.'" was not mounted or could not be found', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$objModel = null;
		$objVersions = null;

		// Add the versioning routines
		if ($this->blnIsDbAssisted && \Dbafs::shouldBeSynchronized($this->intId))
		{
			if (stristr($this->intId, '__new__') === false)
			{
				$objModel = \FilesModel::findByPath($this->intId);

				if ($objModel === null)
				{
					$objModel = \Dbafs::addResource($this->intId);
				}

				$this->objActiveRecord = $objModel;
			}

			$this->blnCreateNewVersion = false;

			/** @var \FilesModel $objModel */
			$objVersions = new \Versions($this->strTable, $objModel->id);

			if (!$GLOBALS['TL_DCA'][$this->strTable]['config']['hideVersionMenu'])
			{
				// Compare versions
				if (\Input::get('versions'))
				{
					$objVersions->compare();
				}

				// Restore a version
				if (\Input::post('FORM_SUBMIT') == 'tl_version' && \Input::post('version') != '')
				{
					$objVersions->restore(\Input::post('version'));
					$this->reload();
				}
			}

			$objVersions->initialize();
		}
		else
		{
			// Unset the database fields
			$GLOBALS['TL_DCA'][$this->strTable]['fields'] = array_intersect_key($GLOBALS['TL_DCA'][$this->strTable]['fields'], array('name' => true, 'protected' => true));
		}

		// Build an array from boxes and rows (do not show excluded fields)
		$this->strPalette = $this->getPalette();
		$boxes = trimsplit(';', $this->strPalette);

		if (!empty($boxes))
		{
			// Get fields
			foreach ($boxes as $k=>$v)
			{
				$boxes[$k] = trimsplit(',', $v);

				foreach ($boxes[$k] as $kk=>$vv)
				{
					if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$vv]['exclude'] || !isset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$vv]))
					{
						unset($boxes[$k][$kk]);
					}
				}

				// Unset a box if it does not contain any fields
				if (empty($boxes[$k]))
				{
					unset($boxes[$k]);
				}
			}

			// Render boxes
			$class = 'tl_tbox';
			$blnIsFirst = true;

			foreach ($boxes as $v)
			{
				$return .= '
<div class="'.$class.'">';

				// Build rows of the current box
				foreach ($v as $vv)
				{
					$this->strField = $vv;
					$this->strInputName = $vv;

					// Load the current value
					if ($vv == 'name')
					{
						$objFile = is_dir(TL_ROOT . '/' . $this->intId) ? new \Folder($this->intId) : new \File($this->intId, true);

						$this->strPath = str_replace(TL_ROOT . '/', '', $objFile->dirname);
						$this->strExtension = ($objFile->origext != '') ? '.'.$objFile->origext : '';
						$this->varValue = $objFile->filename;

						// Fix Unix system files like .htaccess
						if (strncmp($this->varValue, '.', 1) === 0)
						{
							$this->strExtension = '';
						}

						// Clear the current value if it is a new folder
						if (\Input::post('FORM_SUBMIT') != 'tl_files' && \Input::post('FORM_SUBMIT') != 'tl_templates' && $this->varValue == '__new__')
						{
							$this->varValue = '';
						}
					}
					else
					{
						$this->varValue = ($objModel !== null) ? $objModel->$vv : null;
					}

					// Autofocus the first field
					if ($blnIsFirst && $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'] == 'text')
					{
						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['autofocus'] = 'autofocus';
						$blnIsFirst = false;
					}

					// Call load_callback
					if (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['load_callback']))
					{
						foreach ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['load_callback'] as $callback)
						{
							if (is_array($callback))
							{
								$this->import($callback[0]);
								$this->varValue = $this->{$callback[0]}->{$callback[1]}($this->varValue, $this);
							}
							elseif (is_callable($callback))
							{
								$this->varValue = $callback($this->varValue, $this);
							}
						}
					}

					// Build row
					$return .= $this->row();
				}

				$class = 'tl_box';
				$return .= '
  <input type="hidden" name="FORM_FIELDS[]" value="'.specialchars($this->strPalette).'">
  <div class="clear"></div>
</div>';
			}
		}

		// Versions overview
		if ($GLOBALS['TL_DCA'][$this->strTable]['config']['enableVersioning'] && !$GLOBALS['TL_DCA'][$this->strTable]['config']['hideVersionMenu'] && $this->blnIsDbAssisted && \Dbafs::shouldBeSynchronized($this->intId))
		{
			$version = $objVersions->renderDropdown();
		}
		else
		{
			$version = '';
		}

		// Submit buttons
		$arrButtons = array();
		$arrButtons['save'] = '<input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['save']).'">';
		$arrButtons['saveNclose'] = '<input type="submit" name="saveNclose" id="saveNclose" class="tl_submit" accesskey="c" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['saveNclose']).'">';

		// Call the buttons_callback (see #4691)
		if (is_array($GLOBALS['TL_DCA'][$this->strTable]['edit']['buttons_callback']))
		{
			foreach ($GLOBALS['TL_DCA'][$this->strTable]['edit']['buttons_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$arrButtons = $this->{$callback[0]}->{$callback[1]}($arrButtons, $this);
				}
				elseif (is_callable($callback))
				{
					$arrButtons = $callback($arrButtons, $this);
				}
			}
		}

		// Add the buttons and end the form
		$return .= '
</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  ' . implode(' ', $arrButtons) . '
</div>

</div>
</form>

<script>
  window.addEvent(\'domready\', function() {
    Theme.focusInput("'.$this->strTable.'");
  });
</script>';

		// Begin the form (-> DO NOT CHANGE THIS ORDER -> this way the onsubmit attribute of the form can be changed by a field)
		$return = $version . '
<div id="tl_buttons">
<a href="'.$this->getReferer(true).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']).'" accesskey="b" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>
'.\Message::generate().'
<form action="'.ampersand(\Environment::get('request'), true).'" id="'.$this->strTable.'" class="tl_form" method="post"'.(!empty($this->onsubmit) ? ' onsubmit="'.implode(' ', $this->onsubmit).'"' : '').'>
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="'.specialchars($this->strTable).'">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">'.($this->noReload ? '
<p class="tl_error">'.$GLOBALS['TL_LANG']['ERR']['general'].'</p>' : '').$return;

		// Reload the page to prevent _POST variables from being sent twice
		if (\Input::post('FORM_SUBMIT') == $this->strTable && !$this->noReload)
		{
			// Trigger the onsubmit_callback
			if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['onsubmit_callback']))
			{
				foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['onsubmit_callback'] as $callback)
				{
					if (is_array($callback))
					{
						$this->import($callback[0]);
						$this->{$callback[0]}->{$callback[1]}($this);
					}
					elseif (is_callable($callback))
					{
						$callback($this);
					}
				}
			}

			// Save the current version
			if ($this->blnCreateNewVersion && $objModel !== null)
			{
				$objVersions->create();

				// Call the onversion_callback
				if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['onversion_callback']))
				{
					@trigger_error('Using the onversion_callback has been deprecated and will no longer work in Contao 5.0. Use the oncreate_version_callback instead.', E_USER_DEPRECATED);

					foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['onversion_callback'] as $callback)
					{
						if (is_array($callback))
						{
							$this->import($callback[0]);
							$this->{$callback[0]}->{$callback[1]}($this->strTable, $objModel->id, $this);
						}
						elseif (is_callable($callback))
						{
							$callback($this->strTable, $objModel->id, $this);
						}
					}
				}
			}

			// Set the current timestamp (-> DO NOT CHANGE THE ORDER version - timestamp)
			if ($this->blnIsDbAssisted && $objModel !== null)
			{
				$this->Database->prepare("UPDATE " . $this->strTable . " SET tstamp=? WHERE id=?")
							   ->execute(time(), $objModel->id);
			}

			// Redirect
			if (\Input::post('saveNclose'))
			{
				\Message::reset();
				\System::setCookie('BE_PAGE_OFFSET', 0, 0);
				$this->redirect($this->getReferer());
			}

			// Reload
			if ($this->blnIsDbAssisted && $this->objActiveRecord !== null)
			{
				$this->redirect($this->addToUrl('id='.$this->urlEncode($this->objActiveRecord->path)));
			}
			else
			{
				$this->redirect($this->addToUrl('id='.$this->urlEncode($this->strPath.'/'.$this->varValue).$this->strExtension));
			}
		}

		// Set the focus if there is an error
		if ($this->noReload)
		{
			$return .= '

<script>
  window.addEvent(\'domready\', function() {
    Backend.vScrollTo(($(\'' . $this->strTable . '\').getElement(\'label.error\').getPosition().y - 20));
  });
</script>';
		}

		return $return;
	}


	/**
	 * Auto-generate a form to edit all records that are currently shown
	 *
	 * @return string
	 */
	public function editAll()
	{
		$return = '';

		if ($GLOBALS['TL_DCA'][$this->strTable]['config']['notEditable'])
		{
			$this->log('Table "'.$this->strTable.'" is not editable', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Get current IDs from session
		$session = $this->Session->getData();
		$ids = $session['CURRENT']['IDS'];

		// Save field selection in session
		if (\Input::post('FORM_SUBMIT') == $this->strTable.'_all' && \Input::get('fields'))
		{
			$session['CURRENT'][$this->strTable] = \Input::post('all_fields');
			$this->Session->setData($session);
		}

		$fields = $session['CURRENT'][$this->strTable];

		// Add fields
		if (!empty($fields) && is_array($fields) && \Input::get('fields'))
		{
			$class = 'tl_tbox';

			// Walk through each record
			foreach ($ids as $id)
			{
				$this->intId = md5($id);
				$this->strPalette = trimsplit('[;,]', $this->getPalette());

				$objModel = null;
				$objVersions = null;

				// Get the DB entry
				if ($this->blnIsDbAssisted && \Dbafs::shouldBeSynchronized($id))
				{
					$objModel = \FilesModel::findByPath($id);

					if ($objModel === null)
					{
						$objModel = \Dbafs::addResource($id);
					}

					$this->objActiveRecord = $objModel;
					$this->blnCreateNewVersion = false;

					/** @var \FilesModel $objModel */
					$objVersions = new \Versions($this->strTable, $objModel->id);
					$objVersions->initialize();
				}
				else
				{
					// Unset the database fields
					$this->strPalette = array_filter($this->strPalette, function ($val) { return $val == 'name' || $val == 'protected'; });
				}

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

					// Load the current value
					if ($v == 'name')
					{
						$objFile = is_dir(TL_ROOT . '/' . $id) ? new \Folder($id) : new \File($id, true);

						$this->strPath = str_replace(TL_ROOT . '/', '', $objFile->dirname);
						$this->strExtension = ($objFile->origext != '') ? '.'.$objFile->origext : '';
						$this->varValue = $objFile->filename;

						// Fix Unix system files like .htaccess
						if (strncmp($this->varValue, '.', 1) === 0)
						{
							$this->strExtension = '';
						}
					}
					else
					{
						$this->varValue = ($objModel !== null) ? $objModel->$v : null;
					}

					// Call load_callback
					if (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['load_callback']))
					{
						foreach ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['load_callback'] as $callback)
						{
							if (is_array($callback))
							{
								$this->import($callback[0]);
								$this->varValue = $this->{$callback[0]}->{$callback[1]}($this->varValue, $this);
							}
							elseif (is_callable($callback))
							{
								$this->varValue = $callback($this->varValue, $this);
							}
						}
					}

					// Build the current row
					$return .= $this->row();
				}

				// Close box
				$return .= '
  <input type="hidden" name="FORM_FIELDS_'.$this->intId.'[]" value="'.specialchars(implode(',', $formFields)).'">
</div>';

				// Save the record
				if (\Input::post('FORM_SUBMIT') == $this->strTable && !$this->noReload)
				{
					// Call onsubmit_callback
					if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['onsubmit_callback']))
					{
						foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['onsubmit_callback'] as $callback)
						{
							if (is_array($callback))
							{
								$this->import($callback[0]);
								$this->{$callback[0]}->{$callback[1]}($this);
							}
							elseif (is_callable($callback))
							{
								$callback($this);
							}
						}
					}

					// Create a new version
					if ($this->blnCreateNewVersion && $objModel !== null)
					{
						$objVersions->create();

						// Call the onversion_callback
						if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['onversion_callback']))
						{
							@trigger_error('Using the onversion_callback has been deprecated and will no longer work in Contao 5.0. Use the oncreate_version_callback instead.', E_USER_DEPRECATED);

							foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['onversion_callback'] as $callback)
							{
								if (is_array($callback))
								{
									$this->import($callback[0]);
									$this->{$callback[0]}->{$callback[1]}($this->strTable, $objModel->id, $this);
								}
								elseif (is_callable($callback))
								{
									$callback($this->strTable, $objModel->id, $this);
								}
							}
						}
					}

					// Set the current timestamp (-> DO NOT CHANGE ORDER version - timestamp)
					if ($this->blnIsDbAssisted && $objModel !== null)
					{
						$this->Database->prepare("UPDATE " . $this->strTable . " SET tstamp=? WHERE id=?")
									   ->execute(time(), $objModel->id);
					}
				}
			}

			// Submit buttons
			$arrButtons = array();
			$arrButtons['save'] = '<input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['save']).'">';
			$arrButtons['saveNclose'] = '<input type="submit" name="saveNclose" id="saveNclose" class="tl_submit" accesskey="c" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['saveNclose']).'">';

			// Call the buttons_callback (see #4691)
			if (is_array($GLOBALS['TL_DCA'][$this->strTable]['edit']['buttons_callback']))
			{
				foreach ($GLOBALS['TL_DCA'][$this->strTable]['edit']['buttons_callback'] as $callback)
				{
					if (is_array($callback))
					{
						$this->import($callback[0]);
						$arrButtons = $this->{$callback[0]}->{$callback[1]}($arrButtons, $this);
					}
					elseif (is_callable($callback))
					{
						$arrButtons = $callback($arrButtons, $this);
					}
				}
			}

			// Add the form
			$return = '

<form action="'.ampersand(\Environment::get('request'), true).'" id="'.$this->strTable.'" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="'.$this->strTable.'">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">'.($this->noReload ? '

<p class="tl_error">'.$GLOBALS['TL_LANG']['ERR']['general'].'</p>' : '').$return.'

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  ' . implode(' ', $arrButtons) . '
</div>

</div>
</form>

<script>
  window.addEvent(\'domready\', function() {
    Theme.focusInput("'.$this->strTable.'");
  });
</script>';

			// Set the focus if there is an error
			if ($this->noReload)
			{
				$return .= '

<script>
  window.addEvent(\'domready\', function() {
    Backend.vScrollTo(($(\'' . $this->strTable . '\').getElement(\'label.error\').getPosition().y - 20));
  });
</script>';
			}

			// Reload the page to prevent _POST variables from being sent twice
			if (\Input::post('FORM_SUBMIT') == $this->strTable && !$this->noReload)
			{
				if (\Input::post('saveNclose'))
				{
					\System::setCookie('BE_PAGE_OFFSET', 0, 0);
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
  <input type="checkbox" name="all_fields[]" id="all_'.$field.'" class="tl_checkbox" value="'.specialchars($field).'"> <label for="all_'.$field.'" class="tl_checkbox_label">'.($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['label'][0] ?: $GLOBALS['TL_LANG']['MSC'][$field][0]).'</label><br>';
				}
			}

			$blnIsError = ($_POST && empty($_POST['all_fields']));

			// Return the select menu
			$return .= '

<form action="'.ampersand(\Environment::get('request'), true).'&amp;fields=1" id="'.$this->strTable.'_all" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="'.$this->strTable.'_all">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">'.($blnIsError ? '

<p class="tl_error">'.$GLOBALS['TL_LANG']['ERR']['general'].'</p>' : '').'

<div class="tl_tbox">
<fieldset class="tl_checkbox_container">
  <legend'.($blnIsError ? ' class="error"' : '').'>'.$GLOBALS['TL_LANG']['MSC']['all_fields'][0].'</legend>
  <input type="checkbox" id="check_all" class="tl_checkbox" onclick="Backend.toggleCheckboxes(this)"> <label for="check_all" style="color:#a6a6a6"><em>'.$GLOBALS['TL_LANG']['MSC']['selectAll'].'</em></label><br>'.$options.'
</fieldset>'.($blnIsError ? '
<p class="tl_error">'.$GLOBALS['TL_LANG']['ERR']['all_fields'].'</p>' : ((\Config::get('showHelp') && strlen($GLOBALS['TL_LANG']['MSC']['all_fields'][1])) ? '
<p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['MSC']['all_fields'][1].'</p>' : '')).'
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  <input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['continue']).'">
</div>

</div>
</form>';
		}

		// Return
		return '
<div id="tl_buttons">
<a href="'.$this->getReferer(true).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']).'" accesskey="b" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>'.$return;
	}


	/**
	 * Load the source editor
	 *
	 * @return string
	 */
	public function source()
	{
		$this->isValid($this->intId);

		if (is_dir(TL_ROOT .'/'. $this->intId))
		{
			$this->log('Folder "'.$this->intId.'" cannot be edited', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
		elseif (!file_exists(TL_ROOT .'/'. $this->intId))
		{
			$this->log('File "'.$this->intId.'" does not exist', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->import('BackendUser', 'User');

		// Check user permission
		if (!$this->User->hasAccess('f5', 'fop'))
		{
			$this->log('Not enough permissions to edit the file source of file "'.$this->intId.'"', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$objFile = new \File($this->intId, true);

		// Check whether file type is editable
		if (!in_array($objFile->extension, trimsplit(',', strtolower(\Config::get('editableFiles')))))
		{
			$this->log('File type "'.$objFile->extension.'" ('.$this->intId.') is not allowed to be edited', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$objMeta = null;
		$objVersions = null;

		// Add the versioning routines
		if ($this->blnIsDbAssisted && \Dbafs::shouldBeSynchronized($this->intId))
		{
			$objMeta = \FilesModel::findByPath($objFile->value);

			if ($objMeta === null)
			{
				$objMeta = \Dbafs::addResource($objFile->value);
			}

			$objVersions = new \Versions($this->strTable, $objMeta->id);

			if (!$GLOBALS['TL_DCA'][$this->strTable]['config']['hideVersionMenu'])
			{
				// Compare versions
				if (\Input::get('versions'))
				{
					$objVersions->compare();
				}

				// Restore a version
				if (\Input::post('FORM_SUBMIT') == 'tl_version' && \Input::post('version') != '')
				{
					$objVersions->restore(\Input::post('version'));

					// Purge the script cache (see #7005)
					if ($objFile->extension == 'css' || $objFile->extension == 'scss' || $objFile->extension == 'less')
					{
						$this->import('Automator');
						$this->Automator->purgeScriptCache();
					}

					$this->reload();
				}
			}

			$objVersions->initialize();
		}

		$strContent = $objFile->getContent();

		if ($objFile->extension == 'svgz')
		{
			$strContent = gzdecode($strContent);
		}

		// Process the request
		if (\Input::post('FORM_SUBMIT') == 'tl_files')
		{
			// Restore the basic entities (see #7170)
			$strSource = \StringUtil::restoreBasicEntities(\Input::postRaw('source'));

			// Save the file
			if (md5($strContent) != md5($strSource))
			{
				if ($objFile->extension == 'svgz')
				{
					$strSource = gzencode($strSource);
				}

				// Write the file
				$objFile->write($strSource);
				$objFile->close();

				// Update the database
				if ($this->blnIsDbAssisted && $objMeta !== null)
				{
					/** @var \FilesModel $objMeta */
					$objMeta->hash = $objFile->hash;
					$objMeta->save();

					$objVersions->create();
				}

				// Purge the script cache (see #7005)
				if ($objFile->extension == 'css' || $objFile->extension == 'scss' || $objFile->extension == 'less')
				{
					$this->import('Automator');
					$this->Automator->purgeScriptCache();
				}
			}

			if (\Input::post('saveNclose'))
			{
				\System::setCookie('BE_PAGE_OFFSET', 0, 0);
				$this->redirect($this->getReferer());
			}

			$this->reload();
		}

		$codeEditor = '';

		// Prepare the code editor
		if (\Config::get('useCE'))
		{
			$selector = 'ctrl_source';
			$type = $objFile->extension;

			// Load the code editor configuration
			ob_start();
			include TL_ROOT . '/system/config/ace.php';
			$codeEditor = ob_get_contents();
			ob_end_clean();

			unset($selector, $type);
		}

		// Versions overview
		if ($GLOBALS['TL_DCA'][$this->strTable]['config']['enableVersioning'] && !$GLOBALS['TL_DCA'][$this->strTable]['config']['hideVersionMenu'] && $this->blnIsDbAssisted && $objVersions !== null)
		{
			$version = $objVersions->renderDropdown();
		}
		else
		{
			$version = '';
		}

		// Submit buttons
		$arrButtons = array();
		$arrButtons['save'] = '<input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['save']).'">';
		$arrButtons['saveNclose'] = '<input type="submit" name="saveNclose" id="saveNclose" class="tl_submit" accesskey="c" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['saveNclose']).'">';

		// Call the buttons_callback (see #4691)
		if (is_array($GLOBALS['TL_DCA'][$this->strTable]['edit']['buttons_callback']))
		{
			foreach ($GLOBALS['TL_DCA'][$this->strTable]['edit']['buttons_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$arrButtons = $this->{$callback[0]}->{$callback[1]}($arrButtons, $this);
				}
				elseif (is_callable($callback))
				{
					$arrButtons = $callback($arrButtons, $this);
				}
			}
		}

		// Add the form
		return $version . '
<div id="tl_buttons">
<a href="'.$this->getReferer(true).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']).'" accesskey="b" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>
'.\Message::generate().'
<form action="'.ampersand(\Environment::get('request'), true).'" id="tl_files" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_files">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
<div class="tl_tbox">
  <h3><label for="ctrl_source">'.$GLOBALS['TL_LANG']['tl_files']['editor'][0].'</label></h3>
  <textarea name="source" id="ctrl_source" class="tl_textarea monospace" rows="12" cols="80" style="height:400px" onfocus="Backend.getScrollOffset()">' . "\n" . htmlspecialchars($strContent) . '</textarea>' . ((\Config::get('showHelp') && strlen($GLOBALS['TL_LANG']['tl_files']['editor'][1])) ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['tl_files']['editor'][1].'</p>' : '') . '
</div>
</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  ' . implode(' ', $arrButtons) . '
</div>

</div>
</form>' . "\n\n" . $codeEditor;
	}


	/**
	 * Protect a folder
	 */
	public function protect()
	{
		if (!is_dir(TL_ROOT . '/' . $this->intId))
		{
			$this->log('Resource "'.$this->intId.'" is not a directory', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Remove the protection
		if (file_exists(TL_ROOT . '/' . $this->intId . '/.htaccess'))
		{
			$objFolder = new \Folder($this->intId);
			$objFolder->unprotect();
			$this->log('The protection from folder "'.$this->intId.'" has been removed', __METHOD__, TL_FILES);
			$this->redirect($this->getReferer());
		}
		// Protect the folder
		else
		{
			$objFolder = new \Folder($this->intId);
			$objFolder->protect();
			$this->log('Folder "'.$this->intId.'" has been protected', __METHOD__, TL_FILES);
			$this->redirect($this->getReferer());
		}
	}


	/**
	 * Save the current value
	 *
	 * @param mixed $varValue
	 *
	 * @throws \Exception
	 */
	protected function save($varValue)
	{
		if (\Input::post('FORM_SUBMIT') != $this->strTable)
		{
			return;
		}

		$arrData = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField];

		// File names
		if ($this->strField == 'name')
		{
			if (!file_exists(TL_ROOT . '/' . $this->strPath . '/' . $this->varValue . $this->strExtension) || !$this->isMounted($this->strPath . '/' . $this->varValue . $this->strExtension) || $this->varValue === $varValue)
			{
				return;
			}

			$this->import('Files');
			$varValue = utf8_romanize($varValue);

			// Trigger the save_callback
			if (is_array($arrData['save_callback']))
			{
				foreach ($arrData['save_callback'] as $callback)
				{
					if (is_array($callback))
					{
						$this->import($callback[0]);
						$varValue = $this->{$callback[0]}->{$callback[1]}($varValue, $this);
					}
					elseif (is_callable($callback))
					{
						$varValue = $callback($varValue, $this);
					}
				}
			}

			// The target exists
			if (strcasecmp($this->strPath . '/' . $this->varValue . $this->strExtension, $this->strPath . '/' . $varValue . $this->strExtension) !== 0 && file_exists(TL_ROOT . '/' . $this->strPath . '/' . $varValue . $this->strExtension))
			{
				throw new \Exception(sprintf($GLOBALS['TL_LANG']['ERR']['fileExists'], $varValue));
			}

			$arrImageTypes = trimsplit(',', strtolower(\Config::get('validImageTypes')));

			// Remove potentially existing thumbnails (see #6641)
			if (in_array(substr($this->strExtension, 1), $arrImageTypes))
			{
				foreach (glob(TL_ROOT . '/assets/images/*/' . $this->varValue . '-*' . $this->strExtension) as $strThumbnail)
				{
					$this->Files->delete(str_replace(TL_ROOT . '/', '', $strThumbnail));
				}
			}

			// Rename the file
			$this->Files->rename($this->strPath . '/' . $this->varValue . $this->strExtension, $this->strPath . '/' . $varValue . $this->strExtension);

			// New folders
			if (stristr($this->intId, '__new__') !== false)
			{
				// Update the database
				if ($this->blnIsDbAssisted && \Dbafs::shouldBeSynchronized($this->strPath . '/' . $varValue . $this->strExtension))
				{
					$this->objActiveRecord = \Dbafs::addResource($this->strPath . '/' . $varValue . $this->strExtension);
				}

				$this->log('Folder "'.$this->strPath.'/'.$varValue.$this->strExtension.'" has been created', __METHOD__, TL_FILES);
			}
			else
			{
				// Update the database
				if ($this->blnIsDbAssisted)
				{
					$syncSource = \Dbafs::shouldBeSynchronized($this->strPath . '/' . $this->varValue . $this->strExtension);
					$syncTarget = \Dbafs::shouldBeSynchronized($this->strPath . '/' . $varValue . $this->strExtension);

					if ($syncSource && $syncTarget)
					{
						\Dbafs::moveResource($this->strPath . '/' . $this->varValue . $this->strExtension, $this->strPath . '/' . $varValue . $this->strExtension);
					}
					elseif ($syncSource)
					{
						\Dbafs::deleteResource($this->strPath . '/' . $this->varValue . $this->strExtension);
					}
					elseif ($syncTarget)
					{
						\Dbafs::addResource($this->strPath . '/' . $varValue . $this->strExtension);
					}
				}

				$this->log('File or folder "'.$this->strPath.'/'.$this->varValue.$this->strExtension.'" has been renamed to "'.$this->strPath.'/'.$varValue.$this->strExtension.'"', __METHOD__, TL_FILES);
			}

			// Set the new value so the input field can show it
			if (\Input::get('act') == 'editAll')
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
		elseif ($this->blnIsDbAssisted && $this->objActiveRecord !== null)
		{
			// Convert date formats into timestamps
			if ($varValue != '' && in_array($arrData['eval']['rgxp'], array('date', 'time', 'datim')))
			{
				$objDate = new \Date($varValue, \Date::getFormatFromRgxp($arrData['eval']['rgxp']));
				$varValue = $objDate->tstamp;
			}

			// Make sure unique fields are unique
			if ($arrData['eval']['unique'] && $varValue != '' && !$this->Database->isUniqueValue($this->strTable, $this->strField, $varValue, $this->objActiveRecord->id))
			{
				throw new \Exception(sprintf($GLOBALS['TL_LANG']['ERR']['unique'], $arrData['label'][0] ?: $this->strField));
			}

			// Handle multi-select fields in "override all" mode
			if (\Input::get('act') == 'overrideAll' && ($arrData['inputType'] == 'checkbox' || $arrData['inputType'] == 'checkboxWizard') && $arrData['eval']['multiple'])
			{
				if ($this->objActiveRecord !== null)
				{
					$new = deserialize($varValue, true);
					$old = deserialize($this->objActiveRecord->{$this->strField}, true);

					switch (\Input::post($this->strInputName . '_update'))
					{
						case 'add':
							$varValue = array_values(array_unique(array_merge($old, $new)));
							break;

						case 'remove':
							$varValue = array_values(array_diff($old, $new));
							break;

						case 'replace':
							$varValue = $new;
							break;
					}

					if (!is_array($varValue) || empty($varValue))
					{
						$varValue = '';
					}
					elseif (isset($arrData['eval']['csv']))
					{
						$varValue = implode($arrData['eval']['csv'], $varValue); // see #2890
					}
					else
					{
						$varValue = serialize($varValue);
					}
				}
			}

			// Convert arrays (see #2890)
			if ($arrData['eval']['multiple'] && isset($arrData['eval']['csv']))
			{
				$varValue = implode($arrData['eval']['csv'], deserialize($varValue, true));
			}

			// Trigger the save_callback
			if (is_array($arrData['save_callback']))
			{
				foreach ($arrData['save_callback'] as $callback)
				{
					if (is_array($callback))
					{
						$this->import($callback[0]);
						$varValue = $this->{$callback[0]}->{$callback[1]}($varValue, $this);
					}
					elseif (is_callable($callback))
					{
						$varValue = $callback($varValue, $this);
					}
				}
			}

			// Save the value if there was no error
			if (($varValue != '' || !$arrData['eval']['doNotSaveEmpty']) && ($this->varValue != $varValue || $arrData['eval']['alwaysSave']))
			{
				// If the field is a fallback field, empty all other columns
				if ($arrData['eval']['fallback'] && $varValue != '')
				{
					$this->Database->execute("UPDATE " . $this->strTable . " SET " . $this->strField . "=''");
				}

				// Set the correct empty value (see #6284, #6373)
				if ($varValue === '')
				{
					$varValue = \Widget::getEmptyValueByFieldType($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['sql']);
				}

				$this->objActiveRecord->{$this->strField} = $varValue;
				$this->objActiveRecord->save();

				$this->blnCreateNewVersion = true;
				$this->varValue = deserialize($varValue);
			}
		}
	}


	/**
	 * Synchronize the file system with the database
	 *
	 * @return string
	 */
	public function sync()
	{
		if (!$this->blnIsDbAssisted)
		{
			return '';
		}

		$this->import('BackendUser', 'User');
		$this->loadLanguageFile('tl_files');

		// Check the permission to synchronize
		if (!$this->User->hasAccess('f6', 'fop'))
		{
			$this->log('Not enough permissions to synchronize the file system', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Synchronize
		$strLog = \Dbafs::syncFiles();

		// Show the results
		$arrMessages = array();
		$arrCounts   = array('Added'=>0, 'Changed'=>0, 'Unchanged'=>0, 'Moved'=>0, 'Deleted'=>0);

		// Read the log file
		$fh = fopen(TL_ROOT . '/' . $strLog, 'rb');

		while (($buffer = fgets($fh)) !== false)
		{
			list($type, $file) = explode('] ', trim(substr($buffer, 1)), 2);

			// Add a message depending on the type
			switch ($type)
			{
				case 'Added';
					$arrMessages[] = '<p class="tl_new">' . sprintf($GLOBALS['TL_LANG']['tl_files']['syncAdded'], specialchars($file)) . '</p>';
					break;

				case 'Changed';
					$arrMessages[] = '<p class="tl_info">' . sprintf($GLOBALS['TL_LANG']['tl_files']['syncChanged'], specialchars($file)) . '</p>';
					break;

				case 'Unchanged';
					$arrMessages[] = '<p class="tl_confirm hidden">' . sprintf($GLOBALS['TL_LANG']['tl_files']['syncUnchanged'], specialchars($file)) . '</p>';
					break;

				case 'Moved';
					list($source, $target) = explode(' to ', $file, 2);
					$arrMessages[] = '<p class="tl_info">' . sprintf($GLOBALS['TL_LANG']['tl_files']['syncMoved'], specialchars($source), specialchars($target)) . '</p>';
					break;

				case 'Deleted';
					$arrMessages[] = '<p class="tl_error">' . sprintf($GLOBALS['TL_LANG']['tl_files']['syncDeleted'], specialchars($file)) . '</p>';
					break;
			}

			++$arrCounts[$type];
		}

		// Close the log file
		unset($buffer);
		fclose($fh);

		// Confirm
		\Message::addConfirmation($GLOBALS['TL_LANG']['tl_files']['syncComplete']);

		$return = '
<div id="tl_buttons">
<a href="'.$this->getReferer(true).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']).'" accesskey="b" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>
'.\Message::generate().'
<div id="sync-results">
  <p class="left">' . sprintf($GLOBALS['TL_LANG']['tl_files']['syncResult'], \System::getFormattedNumber($arrCounts['Added'], 0), \System::getFormattedNumber($arrCounts['Changed'], 0), \System::getFormattedNumber($arrCounts['Unchanged'], 0), \System::getFormattedNumber($arrCounts['Moved'], 0), \System::getFormattedNumber($arrCounts['Deleted'], 0)) . '</p>
  <p class="right"><input type="checkbox" id="show-hidden" class="tl_checkbox" onclick="Backend.toggleUnchanged()"> <label for="show-hidden">' . $GLOBALS['TL_LANG']['tl_files']['syncShowUnchanged'] . '</label></p>
  <div class="clear"></div>
</div>
<div class="tl_message nobg" id="result-list" style="margin-bottom:2em">';

		// Add the messages
		foreach ($arrMessages as $strMessage)
		{
			$return .= "\n  " . $strMessage;
		}

		$return .= '
</div>

<div class="tl_submit_container">
  <a href="'.$this->getReferer(true).'" class="tl_submit" style="display:inline-block">'.$GLOBALS['TL_LANG']['MSC']['continue'].'</a>
</div>
';

		return $return;
	}


	/**
	 * Return the name of the current palette
	 *
	 * @return string
	 */
	public function getPalette()
	{
		return $GLOBALS['TL_DCA'][$this->strTable]['palettes']['default'];
	}


	/**
	 * Generate a particular subpart of the tree and return it as HTML string
	 *
	 * @param string  $strFolder
	 * @param integer $level
	 *
	 * @return string
	 */
	public function ajaxTreeView($strFolder, $level)
	{
		if (!\Environment::get('isAjaxRequest'))
		{
			return '';
		}

		$blnClipboard = false;
		$arrClipboard = $this->Session->get('CLIPBOARD');

		// Check clipboard
		if (!empty($arrClipboard[$this->strTable]))
		{
			$blnClipboard = true;
			$arrClipboard = $arrClipboard[$this->strTable];
		}

		$blnProtected = false;
		$strPath = $strFolder;

		// Check for public parent folders (see #213)
		while ($strPath != '' && $strPath != '.')
		{
			if (file_exists(TL_ROOT . '/' . $strPath . '/.htaccess'))
			{
				$blnProtected = true;
				break;
			}

			$strPath = dirname($strPath);
		}

		$this->import('Files');
		$this->import('BackendUser', 'User');

		return $this->generateTree(TL_ROOT.'/'.$strFolder, ($level * 20), false, $blnProtected, ($blnClipboard ? $arrClipboard : false));
	}


	/**
	 * Render the file tree and return it as HTML string
	 *
	 * @param string  $path
	 * @param integer $intMargin
	 * @param boolean $mount
	 * @param boolean $blnProtected
	 * @param array   $arrClipboard
	 *
	 * @return string
	 */
	protected function generateTree($path, $intMargin, $mount=false, $blnProtected=false, $arrClipboard=null)
	{
		static $session;
		$session = $this->Session->getData();

		// Get the session data and toggle the nodes
		if (\Input::get('tg'))
		{
			$session['filetree'][\Input::get('tg')] = (isset($session['filetree'][\Input::get('tg')]) && $session['filetree'][\Input::get('tg')] == 1) ? 0 : 1;
			$this->Session->setData($session);
			$this->redirect(preg_replace('/(&(amp;)?|\?)tg=[^& ]*/i', '', \Environment::get('request')));
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
				if (strncmp($v, '.', 1) === 0)
				{
					continue;
				}

				if (is_file($path . '/' . $v))
				{
					$files[] = $path . '/' . $v;
				}
				else
				{
					if ($v == '__new__')
					{
						$this->Files->rmdir(str_replace(TL_ROOT . '/', '', $path) . '/' . $v);
					}
					else
					{
						$folders[] = $path . '/' . $v;
					}
				}
			}

			natcasesort($folders);
			$folders = array_values($folders);

			natcasesort($files);
			$files = array_values($files);
		}

		// Folders
		for ($f=0, $c=count($folders); $f<$c; $f++)
		{
			$md5 = substr(md5($folders[$f]), 0, 8);
			$content = scan($folders[$f]);
			$currentFolder = str_replace(TL_ROOT . '/', '', $folders[$f]);
			$session['filetree'][$md5] = is_numeric($session['filetree'][$md5]) ? $session['filetree'][$md5] : 0;
			$currentEncoded = $this->urlEncode($currentFolder);
			$countFiles = count($content);

			// Subtract files that will not be shown
			foreach ($content as $file)
			{
				if (strncmp($file, '.', 1) === 0)
				{
					--$countFiles;
				}
				elseif (!empty($this->arrValidFileTypes) && is_file($folders[$f] . '/' . $file) && !in_array(strtolower(substr($file, (strrpos($file, '.') + 1))), $this->arrValidFileTypes))
				{
					--$countFiles;
				}
			}

			$return .= "\n  " . '<li class="tl_folder click2edit toggle_select" onmouseover="Theme.hoverDiv(this,1)" onmouseout="Theme.hoverDiv(this,0)"><div class="tl_left" style="padding-left:'.($intMargin + (($countFiles < 1) ? 20 : 0)).'px">';

			// Add a toggle button if there are childs
			if ($countFiles > 0)
			{
				$img = ($session['filetree'][$md5] == 1) ? 'folMinus.gif' : 'folPlus.gif';
				$alt = ($session['filetree'][$md5] == 1) ? $GLOBALS['TL_LANG']['MSC']['collapseNode'] : $GLOBALS['TL_LANG']['MSC']['expandNode'];
				$return .= '<a href="'.$this->addToUrl('tg='.$md5).'" title="'.specialchars($alt).'" onclick="Backend.getScrollOffset(); return AjaxRequest.toggleFileManager(this, \'filetree_'.$md5.'\', \''.$currentFolder.'\', '.$level.')">'.\Image::getHtml($img, '', 'style="margin-right:2px"').'</a>';
			}

			$protected = ($blnProtected === true || array_search('.htaccess', $content) !== false) ? true : false;
			$folderImg = $protected ? 'folderCP.gif' : 'folderC.gif';

			// Add the current folder
			$strFolderNameEncoded = utf8_convert_encoding(specialchars(basename($currentFolder)), \Config::get('characterSet'));
			$return .= \Image::getHtml($folderImg, '').' <a href="' . $this->addToUrl('fn='.$currentEncoded) . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectNode']).'"><strong>'.$strFolderNameEncoded.'</strong></a></div> <div class="tl_right">';

			// Paste buttons
			if ($arrClipboard !== false && \Input::get('act') != 'select')
			{
				$imagePasteInto = \Image::getHtml('pasteinto.gif', $GLOBALS['TL_LANG'][$this->strTable]['pasteinto'][0]);
				$return .= (($arrClipboard['mode'] == 'cut' || $arrClipboard['mode'] == 'copy') && preg_match('/^' . preg_quote($arrClipboard['id'], '/') . '/i', $currentFolder)) ? \Image::getHtml('pasteinto_.gif') : '<a href="'.$this->addToUrl('act='.$arrClipboard['mode'].'&amp;mode=2&amp;pid='.$currentEncoded.(!is_array($arrClipboard['id']) ? '&amp;id='.$arrClipboard['id'] : '')).'" title="'.specialchars($GLOBALS['TL_LANG'][$this->strTable]['pasteinto'][1]).'" onclick="Backend.getScrollOffset()">'.$imagePasteInto.'</a> ';
			}
			// Default buttons
			else
			{
				// Do not display buttons for mounted folders
				if ($this->User->isAdmin || !in_array($currentFolder, $this->User->filemounts))
				{
					$return .= (\Input::get('act') == 'select') ? '<input type="checkbox" name="IDS[]" id="ids_'.md5($currentEncoded).'" class="tl_tree_checkbox" value="'.$currentEncoded.'">' : $this->generateButtons(array('id'=>$currentEncoded, 'popupWidth'=>640, 'popupHeight'=>132, 'fileNameEncoded'=>$strFolderNameEncoded), $this->strTable);
				}

				// Upload button
				if (!$GLOBALS['TL_DCA'][$this->strTable]['config']['closed'] && !$GLOBALS['TL_DCA'][$this->strTable]['config']['notCreatable'] && \Input::get('act') != 'select')
				{
					$return .= ' <a href="'.$this->addToUrl('&amp;act=move&amp;mode=2&amp;pid='.$currentEncoded).'" title="'.specialchars(sprintf($GLOBALS['TL_LANG']['tl_files']['uploadFF'], $currentEncoded)).'">'.\Image::getHtml('new.gif', $GLOBALS['TL_LANG'][$this->strTable]['move'][0]).'</a>';
				}
			}

			$return .= '</div><div style="clear:both"></div></li>';

			// Call the next node
			if (!empty($content) && $session['filetree'][$md5] == 1)
			{
				$return .= '<li class="parent" id="filetree_'.$md5.'"><ul class="level_'.$level.'">';
				$return .= $this->generateTree($folders[$f], ($intMargin + $intSpacing), false, $protected, $arrClipboard);
				$return .= '</ul></li>';
			}
		}

		// Process files
		for ($h=0, $c=count($files); $h<$c; $h++)
		{
			$thumbnail = '';
			$popupWidth = 600;
			$popupHeight = 161;
			$currentFile = str_replace(TL_ROOT . '/', '', $files[$h]);

			$objFile = new \File($currentFile, true);

			if (!empty($this->arrValidFileTypes) && !in_array($objFile->extension, $this->arrValidFileTypes))
			{
				continue;
			}

			$currentEncoded = $this->urlEncode($currentFile);
			$return .= "\n  " . '<li class="tl_file click2edit toggle_select" onmouseover="Theme.hoverDiv(this,1)" onmouseout="Theme.hoverDiv(this,0)"><div class="tl_left" style="padding-left:'.($intMargin + $intSpacing).'px">';

			// Generate the thumbnail
			if ($objFile->isImage)
			{
				if ($objFile->viewHeight > 0)
				{
					if ($objFile->width && $objFile->height)
					{
						$popupWidth = ($objFile->width > 600) ? ($objFile->width + 61) : 661;
						$popupHeight = ($objFile->height + 210);
					}
					else
					{
						$popupWidth = 661;
						$popupHeight = 625 / $objFile->viewWidth * $objFile->viewHeight + 210;
					}

					$thumbnail .= ' <span class="tl_gray">('.$this->getReadableSize($objFile->filesize);

					if ($objFile->width && $objFile->height)
					{
						$thumbnail .= ', '.$objFile->width.'x'.$objFile->height.' px';
					}

					$thumbnail .= ')</span>';

					if (\Config::get('thumbnails') && ($objFile->isSvgImage || $objFile->height <= \Config::get('gdMaxImgHeight') && $objFile->width <= \Config::get('gdMaxImgWidth')))
					{
						$thumbnail .= '<br>' . \Image::getHtml(\Image::get($currentEncoded, 400, (($objFile->height && $objFile->height < 50) ? $objFile->height : 50), 'box'), '', 'style="margin:0 0 2px -19px"');
					}
				}
				else
				{
					$popupHeight = 360; // dimensionless SVGs are rendered at 300x150px, so the popup needs to be 150px + 210px high
				}
			}
			else
			{
				$thumbnail .= ' <span class="tl_gray">('.$this->getReadableSize($objFile->filesize).')</span>';
			}

			$strFileNameEncoded = utf8_convert_encoding(specialchars(basename($currentFile)), \Config::get('characterSet'));

			// No popup links for templates and in the popup file manager
			if ($this->strTable == 'tl_templates' || \Input::get('popup'))
			{
				$return .= \Image::getHtml($objFile->icon).' '.$strFileNameEncoded.$thumbnail.'</div> <div class="tl_right">';
			}
			else
			{
				$return .= '<a href="'. $currentEncoded.'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['view']).'" target="_blank">' . \Image::getHtml($objFile->icon, $objFile->mime).'</a> '.$strFileNameEncoded.$thumbnail.'</div> <div class="tl_right">';
			}

			// Buttons
			if ($arrClipboard !== false && \Input::get('act') != 'select')
			{
				$_buttons = '&nbsp;';
			}
			else
			{
				$_buttons = (\Input::get('act') == 'select') ? '<input type="checkbox" name="IDS[]" id="ids_'.md5($currentEncoded).'" class="tl_tree_checkbox" value="'.$currentEncoded.'">' : $this->generateButtons(array('id'=>$currentEncoded, 'popupWidth'=>$popupWidth, 'popupHeight'=>$popupHeight, 'fileNameEncoded'=>$strFileNameEncoded), $this->strTable);
			}

			$return .= $_buttons . '</div><div style="clear:both"></div></li>';
		}

		return $return;
	}


	/**
	 * Return true if the current folder is mounted
	 *
	 * @param string $strFolder
	 *
	 * @return boolean
	 */
	protected function isMounted($strFolder)
	{
		if ($strFolder == '')
		{
			return false;
		}

		if (empty($this->arrFilemounts))
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
	 * Check a file operation
	 *
	 * @param string $strFile
	 *
	 * @return boolean
	 */
	protected function isValid($strFile)
	{
		$strFolder = \Input::get('pid', true);

		// Check the path
		if (\Validator::isInsecurePath($strFile))
		{
			$this->log('Invalid file name "'.$strFile.'" (hacking attempt)', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
		elseif (\Validator::isInsecurePath($strFolder))
		{
			$this->log('Invalid folder name "'.$strFolder.'" (hacking attempt)', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Check for valid file types
		if (!empty($this->arrValidFileTypes) && is_file(TL_ROOT . '/' . $strFile))
		{
			$fileinfo = preg_replace('/.*\.(.*)$/ui', '$1', $strFile);

			if (!in_array(strtolower($fileinfo), $this->arrValidFileTypes))
			{
				$this->log('File "'.$strFile.'" is not an allowed file type', __METHOD__, TL_ERROR);
				$this->redirect('contao/main.php?act=error');
			}
		}

		// Check whether the file is within the files directory
		if (!preg_match('/^'.preg_quote(\Config::get('uploadPath'), '/').'/i', $strFile))
		{
			$this->log('File or folder "'.$strFile.'" is not within the files directory', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Check whether the parent folder is within the files directory
		if ($strFolder && !preg_match('/^'.preg_quote(\Config::get('uploadPath'), '/').'/i', $strFolder))
		{
			$this->log('Parent folder "'.$strFolder.'" is not within the files directory', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Do not allow file operations on root folders
		if (\Input::get('act') == 'edit' || \Input::get('act') == 'paste' || \Input::get('act') == 'delete')
		{
			$this->import('BackendUser', 'User');

			if (!$this->User->isAdmin && in_array($strFile, $this->User->filemounts))
			{
				$this->log('Attempt to edit, copy, move or delete the root folder "'.$strFile.'"', __METHOD__, TL_ERROR);
				$this->redirect('contao/main.php?act=error');
			}
		}

		return true;
	}


	/**
	 * Return an array of encrypted folder names
	 *
	 * @param string $strPath
	 *
	 * @return array
	 */
	protected function getMD5Folders($strPath)
	{
		$arrFiles = array();

		foreach (scan(TL_ROOT . '/' . $strPath) as $strFile)
		{
			if (!is_dir(TL_ROOT . '/' . $strPath . '/' . $strFile))
			{
				continue;
			}

			$arrFiles[substr(md5(TL_ROOT . '/' . $strPath . '/' . $strFile), 0, 8)] = 1;

			// Do not use array_merge() here (see #8105)
			foreach ($this->getMD5Folders($strPath . '/' . $strFile) as $k=>$v)
			{
				$arrFiles[$k] = $v;
			}
		}

		return $arrFiles;
	}
}
