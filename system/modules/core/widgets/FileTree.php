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
 * @property string  $orderField
 * @property boolean $multiple
 * @property boolean $isGallery
 * @property boolean $isDownloads
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
	 * Load the database object
	 *
	 * @param array $arrAttributes
	 */
	public function __construct($arrAttributes=null)
	{
		$this->import('Database');
		parent::__construct($arrAttributes);

		// Prepare the order field
		if ($this->orderField != '')
		{
			$this->strOrderId = $this->orderField . str_replace($this->strField, '', $this->strId);
			$this->strOrderName = $this->orderField . str_replace($this->strField, '', $this->strName);

			// Retrieve the order value
			$objRow = $this->Database->prepare("SELECT {$this->orderField} FROM {$this->strTable} WHERE id=?")
									 ->limit(1)
									 ->execute($this->activeRecord->id);

			$tmp = deserialize($objRow->{$this->orderField});
			$this->{$this->orderField} = (!empty($tmp) && is_array($tmp)) ? array_filter($tmp) : array();
		}
	}


	/**
	 * Return an array if the "multiple" attribute is set
	 *
	 * @param mixed $varInput
	 *
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		// Store the order value
		if ($this->orderField != '')
		{
			$arrNew = array_map('String::uuidToBin', explode(',', \Input::post($this->strOrderName)));

			// Only proceed if the value has changed
			if ($arrNew !== $this->{$this->orderField})
			{
				$this->Database->prepare("UPDATE {$this->strTable} SET tstamp=?, {$this->orderField}=? WHERE id=?")
							   ->execute(time(), serialize($arrNew), $this->activeRecord->id);

				$this->objDca->createNewVersion = true; // see #6285
			}
		}

		// Return the value as usual
		if ($varInput == '')
		{
			if ($this->mandatory)
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory'], $this->strLabel));
			}

			return '';
		}
		elseif (strpos($varInput, ',') === false)
		{
			$varInput = \String::uuidToBin($varInput);

			return $this->multiple ? array($varInput) : $varInput;
		}
		else
		{
			$arrValue = array_filter(explode(',', $varInput));

			return $this->multiple ? array_map('String::uuidToBin', $arrValue) : \String::uuidToBin($arrValue[0]);
		}
	}


	/**
	 * Generate the widget and return it as string
	 *
	 * @return string
	 */
	public function generate()
	{
		$arrSet = array();
		$arrValues = array();
		$blnHasOrder = ($this->orderField != '' && is_array($this->{$this->orderField}));

		if (!empty($this->varValue)) // Can be an array
		{
			$objFiles = \FilesModel::findMultipleByUuids((array)$this->varValue);
			$allowedDownload = trimsplit(',', strtolower(\Config::get('allowedDownload')));

			if ($objFiles !== null)
			{
				while ($objFiles->next())
				{
					// File system and database seem not in sync
					if (!file_exists(TL_ROOT . '/' . $objFiles->path))
					{
						continue;
					}

					$arrSet[$objFiles->id] = $objFiles->uuid;

					// Show files and folders
					if (!$this->isGallery && !$this->isDownloads)
					{
						if ($objFiles->type == 'folder')
						{
							$arrValues[$objFiles->uuid] = \Image::getHtml('folderC.gif') . ' ' . $objFiles->path;
						}
						else
						{
							$objFile = new \File($objFiles->path, true);
							$strInfo = $objFiles->path . ' <span class="tl_gray">(' . $this->getReadableSize($objFile->size) . ($objFile->isImage ? ', ' . $objFile->width . 'x' . $objFile->height . ' px' : '') . ')</span>';

							if ($objFile->isImage)
							{
								$image = 'placeholder.png';

								if ($objFile->isSvgImage || $objFile->height <= \Config::get('gdMaxImgHeight') && $objFile->width <= \Config::get('gdMaxImgWidth'))
								{
									$image = \Image::get($objFiles->path, 80, 60, 'center_center');
								}

								$arrValues[$objFiles->uuid] = \Image::getHtml($image, '', 'class="gimage" title="' . specialchars($strInfo) . '"');
							}
							else
							{
								$arrValues[$objFiles->uuid] = \Image::getHtml($objFile->icon) . ' ' . $strInfo;
							}
						}
					}

					// Show a sortable list of files only
					else
					{
						if ($objFiles->type == 'folder')
						{
							$objSubfiles = \FilesModel::findByPid($objFiles->uuid);

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

								$objFile = new \File($objSubfiles->path, true);
								$strInfo = '<span class="dirname">' . dirname($objSubfiles->path) . '/</span>' . $objFile->basename . ' <span class="tl_gray">(' . $this->getReadableSize($objFile->size) . ($objFile->isImage ? ', ' . $objFile->width . 'x' . $objFile->height . ' px' : '') . ')</span>';

								if ($this->isGallery)
								{
									// Only show images
									if ($objFile->isImage)
									{
										$image = 'placeholder.png';

										if ($objFile->isSvgImage || $objFile->height <= \Config::get('gdMaxImgHeight') && $objFile->width <= \Config::get('gdMaxImgWidth'))
										{
											$image = \Image::get($objSubfiles->path, 80, 60, 'center_center');
										}

										$arrValues[$objSubfiles->uuid] = \Image::getHtml($image, '', 'class="gimage" title="' . specialchars($strInfo) . '"');
									}
								}
								else
								{
									// Only show allowed download types
									if (in_array($objFile->extension, $allowedDownload) && !preg_match('/^meta(_[a-z]{2})?\.txt$/', $objFile->basename))
									{
										$arrValues[$objSubfiles->uuid] = \Image::getHtml($objFile->icon) . ' ' . $strInfo;
									}
								}
							}
						}
						else
						{
							$objFile = new \File($objFiles->path, true);
							$strInfo = '<span class="dirname">' . dirname($objFiles->path) . '/</span>' . $objFile->basename . ' <span class="tl_gray">(' . $this->getReadableSize($objFile->size) . ($objFile->isImage ? ', ' . $objFile->width . 'x' . $objFile->height . ' px' : '') . ')</span>';

							if ($this->isGallery)
							{
								// Only show images
								if ($objFile->isImage)
								{
									$image = 'placeholder.png';

									if ($objFile->isSvgImage || $objFile->height <= \Config::get('gdMaxImgHeight') && $objFile->width <= \Config::get('gdMaxImgWidth'))
									{
										$image = \Image::get($objFiles->path, 80, 60, 'center_center');
									}

									$arrValues[$objFiles->uuid] = \Image::getHtml($image, '', 'class="gimage" title="' . specialchars($strInfo) . '"');
								}
							}
							else
							{
								// Only show allowed download types
								if (in_array($objFile->extension, $allowedDownload) && !preg_match('/^meta(_[a-z]{2})?\.txt$/', $objFile->basename))
								{
									$arrValues[$objFiles->uuid] = \Image::getHtml($objFile->icon) . ' ' . $strInfo;
								}
							}
						}
					}
				}
			}

			// Apply a custom sort order
			if ($blnHasOrder)
			{
				$arrNew = array();

				foreach ($this->{$this->orderField} as $i)
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

		// Load the fonts for the drag hint (see #4838)
		\Config::set('loadGoogleFonts', true);

		// Convert the binary UUIDs
		$strSet = implode(',', array_map('String::binToUuid', $arrSet));
		$strOrder = $blnHasOrder ? implode(',', array_map('String::binToUuid', $this->{$this->orderField})) : '';

		$return = '<input type="hidden" name="'.$this->strName.'" id="ctrl_'.$this->strId.'" value="'.$strSet.'">' . ($blnHasOrder ? '
  <input type="hidden" name="'.$this->strOrderName.'" id="ctrl_'.$this->strOrderId.'" value="'.$strOrder.'">' : '') . '
  <div class="selector_container">' . (($blnHasOrder && count($arrValues) > 1) ? '
    <p class="sort_hint">' . $GLOBALS['TL_LANG']['MSC']['dragItemsHint'] . '</p>' : '') . '
    <ul id="sort_'.$this->strId.'" class="'.trim(($blnHasOrder ? 'sortable ' : '').($this->isGallery ? 'sgallery' : '')).'">';

		foreach ($arrValues as $k=>$v)
		{
			$return .= '<li data-id="'.\String::binToUuid($k).'">'.$v.'</li>';
		}

		$return .= '</ul>
    <p><a href="contao/file.php?do='.\Input::get('do').'&amp;table='.$this->strTable.'&amp;field='.$this->strField.'&amp;act=show&amp;id='.$this->activeRecord->id.'&amp;value='.implode(',', array_keys($arrSet)).'&amp;rt='.REQUEST_TOKEN.'" class="tl_submit" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':768,\'title\':\''.specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MSC']['filepicker'])).'\',\'url\':this.href,\'id\':\''.$this->strId.'\'});return false">'.$GLOBALS['TL_LANG']['MSC']['changeSelection'].'</a></p>' . ($blnHasOrder ? '
    <script>Backend.makeMultiSrcSortable("sort_'.$this->strId.'", "ctrl_'.$this->strOrderId.'")</script>' : '') . '
  </div>';

		if (!\Environment::get('isAjaxRequest'))
		{
			$return = '<div>' . $return . '</div>';
		}

		return $return;
	}
}
