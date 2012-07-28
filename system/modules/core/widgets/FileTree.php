<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Core
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class FileTree
 *
 * Provide methods to handle input field "page tree".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Core
 */
class FileTree extends \Widget
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
	 * Order ID
	 * @var string
	 */
	protected $strOrderId;

	/**
	 * Order name
	 * @var string
	 */
	protected $strOrderName;

	/**
	 * Order field
	 * @var string
	 */
	protected $strOrderField;

	/**
	 * Show files
	 * @var boolean
	 */
	protected $blnIsDownloads = false;

	/**
	 * Gallery flag
	 * @var boolean
	 */
	protected $blnIsGallery = false;

	/**
	 * Multiple flag
	 * @var boolean
	 */
	protected $blnIsMultiple = false;


	/**
	 * Load the database object
	 * @param array
	 */
	public function __construct($arrAttributes=null)
	{
		$this->import('Database');
		parent::__construct($arrAttributes);

		$this->orderSRC = null;
		$this->strOrderField = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['orderField'];
		$this->blnIsMultiple = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['multiple'];

		// Prepare the orderSRC field
		if ($this->strOrderField != '')
		{
			$this->strOrderId = $this->strOrderField . str_replace($this->strField, '', $this->strId);
			$this->strOrderName = $this->strOrderField . str_replace($this->strField, '', $this->strName);

			// Retrieve the orderSRC value
			$objRow = $this->Database->prepare("SELECT {$this->strOrderField} FROM {$this->strTable} WHERE id=?")
						   ->limit(1)
						   ->execute($this->activeRecord->id);

			$this->orderSRC = $objRow->{$this->strOrderField};
		}

		$this->blnIsGallery = ($this->activeRecord->type == 'gallery');
		$this->blnIsDownloads = ($this->activeRecord->type == 'downloads');
	}


	/**
	 * Return an array if the "multiple" attribute is set
	 * @param mixed
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		// Store the orderSRC value
		if ($this->strOrderField != '')
		{
			$this->Database->prepare("UPDATE {$this->strTable} SET {$this->strOrderField}=? WHERE id=?")
						   ->execute(\Input::post($this->strOrderName), \Input::get('id'));
		}

		// Return the value as usual
		if (strpos($varInput, ',') === false)
		{
			return $this->blnIsMultiple ? array(intval($varInput)) : intval($varInput);
		}
		else
		{
			$arrValue = array_map('intval', array_filter(explode(',', $varInput)));
			return $this->blnIsMultiple ? $arrValue : $arrValue[0];
		}
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$strValues = '';
		$arrValues = array();

		if (!empty($this->varValue)) // Can be an array
		{
			$strValues = implode(',', array_map('intval', (array)$this->varValue));
			$objFiles = $this->Database->execute("SELECT id, path, type FROM tl_files WHERE id IN($strValues) ORDER BY " . $this->Database->findInSet('id', $strValues));
			$allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

			while ($objFiles->next())
			{
				// File system and database seem not in sync
				if (!file_exists(TL_ROOT . '/' . $objFiles->path))
				{
					continue;
				}

				// Show files and folders
				if (!$this->blnIsGallery && !$this->blnIsDownloads)
				{
					if ($objFiles->type == 'folder')
					{
						$arrValues[$objFiles->id] = $this->generateImage('folderC.gif') . ' ' . $objFiles->path;
					}
					else
					{
						$objFile = new \File($objFiles->path);
						$arrValues[$objFiles->id] = $this->generateImage($objFile->icon) . ' ' . $objFiles->path;
					}
				}

				// Show a sortable list of files only
				else
				{
					if ($objFiles->type == 'folder')
					{
						$objSubfiles = \FilesModel::findByPid($objFiles->id);

						if ($objSubfiles === null)
						{
							continue;
						}

						while ($objSubfiles->next())
						{
							// Skip subfolders
							if ($objSubfiles->type == 'folder')
							{
								continue;
							}

							$objFile = new \File($objSubfiles->path);
							$strInfo = $objSubfiles->path . ' <span class="tl_gray">(' . $this->getReadableSize($objFile->size) . ($objFile->isGdImage ? ', ' . $objFile->width . 'x' . $objFile->height . ' px' : '') . ')</span>';

							if ($this->blnIsGallery)
							{
								// Only show images
								if ($objFile->isGdImage)
								{
									$arrValues[$objSubfiles->id] = $this->generateImage(\Image::get($objSubfiles->path, 50, 50, 'center_center'), '', 'class="gimage" title="' . specialchars($strInfo) . '"');
								}
							}
							else
							{
								// Only show allowed download types
								if (in_array($objFile->extension, $allowedDownload) && !preg_match('/^meta(_[a-z]{2})?\.txt$/', $objFile->basename))
								{
									$arrValues[$objSubfiles->id] = $this->generateImage($objFile->icon) . ' ' . $strInfo;
								}
							}
						}
					}
					else
					{
						$objFile = new \File($objFiles->path);

						if ($this->blnIsGallery)
						{
							// Only show images
							if ($objFile->isGdImage)
							{
								$arrValues[$objFiles->id] = $this->generateImage(\Image::get($objFiles->path, 50, 50, 'center_center'), '', 'class="gimage"');
							}
						}
						else
						{
							// Only show allowed download types
							if (in_array($objFile->extension, $allowedDownload) && !preg_match('/^meta(_[a-z]{2})?\.txt$/', $objFile->basename))
							{
								$arrValues[$objFiles->id] = $this->generateImage($objFile->icon) . ' ' . $objFiles->path;
							}
						}
					}
				}
			}

			// Apply a custom sort order
			if ($this->strOrderField != '' && $this->orderSRC != '')
			{
				$arrNew = array();
				$arrOrder = array_map('intval', explode(',', $this->orderSRC));

				foreach ($arrOrder as $i)
				{
					if (isset($arrValues[$i]))
					{
						$arrNew[$i] = $arrValues[$i];
						unset($arrValues[$i]);
					}
				}

				if (!empty($arrValues))
				{
					foreach ($arrValues as $k=>$v)
					{
						$arrNew[$k] = $v;
					}
				}

				$arrValues = $arrNew;
				unset($arrNew);
			}
		}

		$return = '<input type="hidden" name="'.$this->strName.'" id="ctrl_'.$this->strId.'" value="'.$strValues.'">' . (($this->strOrderField != '') ? '
  <input type="hidden" name="'.$this->strOrderName.'" id="ctrl_'.$this->strOrderId.'" value="'.$this->orderSRC.'">' : '') . '
  <div class="selector_container" id="target_'.$this->strId.'">
    <ul id="sort_'.$this->strId.'" class="'.trim((($this->strOrderField != '') ? 'sortable ' : '').($this->blnIsGallery ? 'sgallery' : '')).'">';

		foreach ($arrValues as $k=>$v)
		{
			$return .= '<li data-id="'.$k.'">'.$v.'</li>';
		}

		$return .= '</ul>
    <p><a href="contao/file.php?do='.\Input::get('do').'&amp;table='.$this->strTable.'&amp;field='.$this->strField.'&amp;id='.\Input::get('id').'&amp;value='.$strValues.'" class="tl_submit" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\''.$GLOBALS['TL_LANG']['MOD']['files'][0].'\',\'url\':this.href,\'id\':\''.$this->strId.'\'});return false">'.$GLOBALS['TL_LANG']['MSC']['changeSelection'].'</a></p>' . (($this->strOrderField != '') ? '
    <script>Backend.makeMultiSrcSortable("sort_'.$this->strId.'", "ctrl_'.$this->strOrderId.'");</script>' : '') . '
  </div>';

		return $return;
	}
}
