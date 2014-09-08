<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;


// @TODO: Adjust this description
/**
 * Resizes images
 *
 * The class resizes images and stores them in the assets/images folder.
 *
 * The following resize modes are supported:
 *
 * * Proportional:  proportional resize
 * * Fit-the-box:   proportional resize that fits in the given dimensions
 * * Crop:          the image will be cropped to fit
 *
 * You can specify which part of the image will be preserved:
 *
 * * left_top:      the left side of a landscape image and the top of a portrait image
 * * center_top:    the center of a landscape image and the top of a portrait image
 * * right_top:     the right side of a landscape image and the top of a portrait image
 * * left_center:   the left side of a landscape image and the center of a portrait image
 * * center_center: the center of a landscape image and the center of a portrait image
 * * right_center:  the right side of a landscape image and the center of a portrait image
 * * left_bottom:   the left side of a landscape image and the bottom of a portrait image
 * * center_bottom: the center of a landscape image and the bottom of a portrait image
 * * right_bottm:   the right side of a landscape image and the bottom of a portrait image
 *
 * Usage:
 *
 *     // Stores the image in the assets/images folder
 *     $src = Image::get('example.jpg', 640, 480, 'center_center');
 *
 *     // Resizes the original image
 *     Image::resize('example.jpg', 640, 480);
 *
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2014
 */
class Image
{
	/**
	 * The File instance of the original image
	 *
	 * @var \File
	 */
	protected $fileObj = null;

	/**
	 * The resized image path
	 *
	 * @var string
	 */
	protected $resizedPath = '';

	/**
	 * The target resize width
	 *
	 * @var int
	 */
	protected $targetWidth = 0;

	/**
	 * The target height
	 *
	 * @var int
	 */
	protected $targetHeight = 0;

	/**
	 * The resize mode
	 * Default is crop for BC
	 *
	 * @var string
	 */
	protected $resizeMode = 'crop';

	/**
	 * The target Path
	 *
	 * @var string
	 */
	protected $targetPath = '';

	/**
	 * Whether to force overriding an existing target
	 *
	 * @var boolean
	 */
	protected $forceOverride = false;

	/**
	 * Zoom level (between 0 and 100)
	 *
	 * @var int
	 */
	protected $zoomLevel = 0;

	/**
	 * Important part of the image settings
	 *
	 * @var array
	 */
	protected $importantPart = array();

	/**
	 * Create a new object to handle an image
	 *
	 * @param string $path  The image path
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function __construct($path)
	{
		// @todo should throw specific exceptions so they can be caught properly
		if ($path === '')
		{
			throw new \InvalidArgumentException('Cannot create image object from empty path!');
		}

		$path = rawurldecode($path);

		// Check whether the file exists
		if (!is_file(TL_ROOT . '/' . $path))
		{
			throw new \InvalidArgumentException('Image "' . $path . '" could not be found');
		}

		$this->fileObj = new \File($path, true);
		$arrAllowedTypes = array_map('trim', explode(',', Config::get('validImageTypes')));

		// Check the file type
		if (!in_array($this->fileObj->extension, $arrAllowedTypes))
		{
			throw new \InvalidArgumentException('Image type "' . $this->fileObj->extension . '" was not allowed to be processed');
		}

		// Set default important part
		$this->setImportantPart(array(
			'x' => 0,
			'y' => 0,
			'width' => $this->fileObj->width,
			'height' => $this->fileObj->height,
		));
	}

	/**
	 * Whether to force overriding the target image or not (default false)
	 *
	 * @param boolean $forceOverride
	 * @return $this
	 */
	public function setForceOverride($forceOverride)
	{
		$this->forceOverride = (bool) $forceOverride;

		return $this;
	}

	/**
	 * Get force override setting
	 *
	 * @return boolean
	 */
	public function getForceOverride()
	{
		return $this->forceOverride;
	}

	/**
	 * Set the important part settings
	 *
	 * @param array $importantPart
	 * @return $this
	 */
	public function setImportantPart(array $importantPart)
	{
		$this->importantPart = $importantPart;

		return $this;
	}

	/**
	 * Set the important part settings
	 *
	 * @return array
	 */
	public function getImportantPart()
	{
		return $this->importantPart;
	}

	/**
	 * Set the target height
	 *
	 * @param int $targetHeight
	 * @return $this
	 */
	public function setTargetHeight($targetHeight)
	{
		$this->targetHeight = (int) $targetHeight;

		return $this;
	}

	/**
	 * Get the target height
	 *
	 * @return int
	 */
	public function getTargetHeight()
	{
		return $this->targetHeight;
	}

	/**
	 * Set the target width
	 *
	 * @param int $targetWidth
	 * @return $this
	 */
	public function setTargetWidth($targetWidth)
	{
		$this->targetWidth = (int) $targetWidth;

		return $this;
	}

	/**
	 * Get the target width
	 *
	 * @return int
	 */
	public function getTargetWidth()
	{
		return $this->targetWidth;
	}

	/**
	 * Set the target path
	 *
	 * @param string $targetPath
	 * @return $this
	 */
	public function setTargetPath($targetPath)
	{
		// @todo validate?
		$this->targetPath = (string) $targetPath;

		return $this;
	}

	/**
	 * Get the target path
	 *
	 * @return string
	 */
	public function getTargetPath()
	{
		return $this->targetPath;
	}

	/**
	 * Set the zoom level
	 *
	 * @param int $zoomLevel
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function setZoomLevel($zoomLevel)
	{
		$zoomLevel = (int) $zoomLevel;

		if ($zoomLevel < 0 || $zoomLevel > 100)
		{
			throw new \InvalidArgumentException('Zoom level must be between 0 and 100!');
		}

		$this->zoomLevel = $zoomLevel;

		return $this;
	}

	/**
	 * Get the zoom level
	 *
	 * @return int
	 */
	public function getZoomLevel()
	{
		return $this->zoomLevel;
	}

	/**
	 * Set the resize mode
	 *
	 * @param string $resizeMode
	 * @return $this
	 */
	public function setResizeMode($resizeMode)
	{
		// @todo validate
		$this->resizeMode = $resizeMode;

		return $this;
	}

	/**
	 * Get the resize mode
	 *
	 * @return string
	 */
	public function getResizeMode()
	{
		return $this->resizeMode;
	}

	/**
	 * Get the original path
	 *
	 * @return string
	 */
	public function getOriginalPath()
	{
		return $this->fileObj->path;
	}

	/**
	 * Get the path of the resized image
	 *
	 * @return string
	 */
	public function getResizedPath()
	{
		return $this->resizedPath;
	}

	/**
	 * Get the cache name
	 *
	 * @return string
	 */
	public function getCacheName()
	{
		$importantPart = $this->getImportantPart();

		$strCacheKey = substr(md5('-w' . $this->getTargetWidth()
			. '-h' . $this->getTargetHeight()
			. '-' . $this->getOriginalPath()
			. '-' . $this->getResizeMode()
			. '-' . $this->getZoomLevel()
			. '-' . $importantPart['x']
			. '-' . $importantPart['y']
			. '-' . $importantPart['width']
			. '-' . $importantPart['height']
			. '-' . $this->fileObj->mtime), 0, 8);

		return 'assets/images/'
		. substr($strCacheKey, -1)
		. '/'
		. $this->fileObj->filename
		. '-'
		. $strCacheKey
		. '.'
		. $this->fileObj->extension;
	}

	/**
	 * Resize the image
	 *
	 * @return $this
	 */
	public function executeResize()
	{
		$importantPart = $this->getImportantPart();

		// No resizing required
		if (
			($this->fileObj->width == $this->getTargetWidth() || !$this->getTargetWidth())
			&& ($this->fileObj->height == $this->getTargetHeight() || !$this->getTargetHeight())
			&& (empty($importantPart) || !$this->getZoomLevel()))
		{
			// Return the target image (thanks to Tristan Lins) (see #4166)
			if ($this->getTargetPath())
			{
				// Copy the source image if the target image does not exist or is older than the source image
				if (!file_exists(TL_ROOT . '/' . $this->getTargetPath())
					|| $this->fileObj->mtime > filemtime(TL_ROOT . '/' . $this->getTargetPath()))
				{
					\Files::getInstance()->copy($this->getOriginalPath(), $this->getTargetPath());
				}

				$this->resizedPath = \System::urlEncode($this->getOriginalPath());

				return $this;
			}

			$this->resizedPath = \System::urlEncode($this->getOriginalPath());

			return $this;
		}

		// Check whether the image exists already
		if (!\Config::get('debugMode'))
		{
			// Custom target (thanks to Tristan Lins) (see #4166)
			if ($this->getTargetPath() && !$this->getForceOverride())
			{
				if (file_exists(TL_ROOT . '/' . $this->getTargetPath())
					&& $this->fileObj->mtime <= filemtime(TL_ROOT . '/' . $this->getTargetPath())
				)
				{
					$this->resizedPath = \System::urlEncode($this->getOriginalPath());

					return $this;
				}
			}

			// Regular cache file
			if (file_exists(TL_ROOT . '/' . $this->getCacheName()))
			{
				// Copy the cached file if it exists
				if ($this->getOriginalPath())
				{
					\Files::getInstance()->copy(
						$this->getCacheName(),
						$this->getTargetPath()
					);
					$this->resizedPath = \System::urlEncode($this->getTargetPath());

					return $this;
				}

				$this->resizedPath = \System::urlEncode($this->getCacheName());

				return $this;
			}
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getImage']) && is_array($GLOBALS['TL_HOOKS']['getImage']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getImage'] as $callback)
			{
				$return = \System::importStatic($callback[0])->$callback[1](
					$this->getOriginalPath(),
					$this->getTargetWidth(),
					$this->getTargetHeight(),
					$this->getResizeMode(),
					$this->getCacheName(),
					$this->fileObj,
					$this->getTargetPath(),
					$this->getZoomLevel(),
					$this->getImportantPart(),
					$this
				);

				if (is_string($return))
				{
					$this->resizedPath = \System::urlEncode($return);

					return $this;
				}
			}
		}

		// Return the path to the original image if the GDlib cannot handle it
		if (!extension_loaded('gd')
			|| !$this->fileObj->isGdImage
			|| $this->fileObj->width > \Config::get('gdMaxImgWidth')
			|| $this->fileObj->height > \Config::get('gdMaxImgHeight')
			|| (!$this->getTargetWidth() && !$this->getTargetHeight())
			|| $this->getTargetWidth() > \Config::get('gdMaxImgWidth')
			|| $this->getTargetHeight() > \Config::get('gdMaxImgHeight'))
		{
			$this->resizedPath = \System::urlEncode($this->getOriginalPath());

			return $this;
		}

		$coordinates = $this->computeResize();

		$strNewImage = static::createGdImage($coordinates['width'], $coordinates['height']);

		$strSourceImage = static::getGdImageFromFile($this->fileObj);

		// The new image could not be created
		if (!$strSourceImage)
		{
			imagedestroy($strNewImage);
			\System::log('Image "' . $this->getOriginalPath() . '" could not be processed', __METHOD__, TL_ERROR);
			$this->resizedPath = '';

			return $this;
		}

		imagecopyresampled(
			$strNewImage,
			$strSourceImage,
			$coordinates['target_x'],
			$coordinates['target_y'],
			0,
			0,
			$coordinates['target_width'],
			$coordinates['target_height'],
			$this->fileObj->width,
			$this->fileObj->height
		);

		static::saveGdImageToFile($strNewImage, TL_ROOT . '/' . $this->getCacheName(), $this->fileObj->extension);

		// Destroy the temporary images
		imagedestroy($strSourceImage);
		imagedestroy($strNewImage);

		// Resize the original image
		if ($this->getTargetPath())
		{
			\Files::getInstance()->copy($this->getCacheName(), $this->getTargetPath());
			return \System::urlEncode($this->getTargetPath());
		}

		// Set the file permissions when the Safe Mode Hack is used
		if (\Config::get('useFTP'))
		{
			\Files::getInstance()->chmod($this->getCacheName(), \Config::get('defaultFileChmod'));
		}

		$this->resizedPath = \System::urlEncode($this->getCacheName());

		return $this;
	}


	/**
	 * Resize an image and create a picture element definition
	 *
	 * @param integer $imageSizeId
	 *
	 * @return array The picture element definition
	 */
	public function getPicture($imageSizeId)
	{
		$image = rawurldecode($this->getOriginalPath());

		// Check whether the file exists
		if (!is_file(TL_ROOT . '/' . $image))
		{
			\System::log('Image "' . $image . '" could not be found', __METHOD__, TL_ERROR);
			return null;
		}

		$imageSize = \ImageSizeModel::findByPk($imageSizeId);

		if (!$imageSize)
		{
			\System::log('Image size ID "' . $imageSizeId . '" could not be found', __METHOD__, TL_ERROR);
			return null;
		}

		$importantPart = null;

		$fileRecord = \FilesModel::findByPath($image);
		if ($fileRecord && $fileRecord->importantPartWidth && $fileRecord->importantPartHeight)
		{
			$importantPart = array(
				'x' => (int)$fileRecord->importantPartX,
				'y' => (int)$fileRecord->importantPartY,
				'width' => (int)$fileRecord->importantPartWidth,
				'height' => (int)$fileRecord->importantPartHeight,
			);
		}

		$mainSource = $this->getPictureSource($importantPart, $imageSize);
		$sources = array();

		$imageSizeItems = \ImageSizeItemModel::findByPid($imageSize->id, array('order' => 'sorting ASC'));

		foreach ($imageSizeItems as $imageSizeItem)
		{
			$sources[] = $this->getPictureSource($importantPart, $imageSizeItem);
		}

		return array(
			'img' => $mainSource,
			'sources' => $sources,
		);
	}


	/**
	 * Get the attributes for one picture source element
	 *
	 * @param array   $importantPart Important part of the image, keys: x, y, width, height
	 * @param \Model  $imageSize     The image size or image size item model
	 *
	 * @return array The source element attributes
	 */
	public function getPictureSource($importantPart, $imageSize)
	{
		$densities = array();

		if (!empty($imageSize->densities) && ($imageSize->width || $imageSize->height))
		{
			$densities = array_filter(array_map('floatval', explode(',', $imageSize->densities)));
		}

		array_unshift($densities, 1);
		$densities = array_values(array_unique($densities));

		$attributes = array();
		$srcset = array();

		foreach ($densities as $density)
		{
			$imageObj = new static($this->getOriginalPath());
			$src = $imageObj->setTargetWidth($imageSize->width * $density)
				->setTargetHeight($imageSize->height * $density)
				->setResizeMode($imageSize->resizeMode)
				->setTargetPath('')
				->setForceOverride(false)
				->setZoomLevel($imageSize->zoom)
				->setImportantPart($importantPart)
				->executeResize()
				->getResizedPath();

			if (empty($attributes['src']))
			{
				$attributes['src'] = htmlspecialchars(TL_FILES_URL . $src, ENT_QUOTES);
				$size = getimagesize(TL_ROOT .'/'. $src);
				$attributes['width'] = $size[0];
				$attributes['height'] = $size[1];
			}

			if (count($densities) > 1)
			{
				if (empty($imageSize->sizes))
				{
					$src .= ' ' . $density . 'x';
				}
				else
				{
					$size = getimagesize(TL_ROOT .'/'. $src);
					$src .= ' ' . $size[0] . 'w';
				}
			}

			$srcset[] = TL_FILES_URL . $src;
		}

		$attributes['srcset'] = htmlspecialchars(implode(', ', $srcset), ENT_QUOTES);

		if (!empty($imageSize->sizes))
		{
			$attributes['sizes'] = htmlspecialchars($imageSize->sizes, ENT_QUOTES);
		}

		if (!empty($imageSize->media))
		{
			$attributes['media'] = htmlspecialchars($imageSize->media, ENT_QUOTES);
		}

		return $attributes;
	}


	/**
	 * Calculate the resize coordinates
	 *
	 * @param integer $width          The target width
	 * @param integer $height         The target height
	 * @param integer $originalWidth  The original width
	 * @param integer $originalHeight The original height
	 * @param string  $mode           The resize mode
	 * @param integer $zoom           Zoom between 0 and 100
	 * @param array   $importantPart  Important part of the image, keys: x, y, width, height
	 *
	 * @return array The resize coordinates: width, height, target_x, target_y, target_width, target_height
	 */
	public function computeResize()
	{
		$width = $this->getTargetWidth();
		$height = $this->getTargetHeight();
		$originalWidth = $this->fileObj->width;
		$originalHeight = $this->fileObj->height;
		$mode = $this->getResizeMode();
		$zoom = $this->getZoomLevel();
		$importantPart = $this->getImportantPart();

		// Backwards compatibility for old modes:
		// left_top, center_top, right_top,
		// left_center, center_center, right_center,
		// left_bottom, center_bottom, right_bottom
		if ($mode && substr_count($mode, '_') === 1)
		{
			$zoom = 0;
			$importantPart = array('x' => 0, 'y' => 0, 'width' => $originalWidth, 'height' => $originalHeight);

			$mode = explode('_', $mode);

			if ($mode[0] === 'left')
			{
				$importantPart['width'] = 1;
			}
			elseif ($mode[0] === 'right')
			{
				$importantPart['x'] = $originalWidth - 1;
				$importantPart['width'] = 1;
			}

			if ($mode[1] === 'top')
			{
				$importantPart['height'] = 1;
			}
			elseif ($mode[1] === 'bottom')
			{
				$importantPart['y'] = $originalHeight - 1;
				$importantPart['height'] = 1;
			}
		}

		$zoom = max(0, min(1, (int)$zoom / 100));

		$zoomedImportantPart = array(
			'x' => $importantPart['x'] * $zoom,
			'y' => $importantPart['y'] * $zoom,
			'width' => $originalWidth - (($originalWidth - $importantPart['width'] - $importantPart['x']) * $zoom) - ($importantPart['x'] * $zoom),
			'height' => $originalHeight - (($originalHeight - $importantPart['height'] - $importantPart['y']) * $zoom) - ($importantPart['y'] * $zoom),
		);

		// If no dimensions are specified, use the zoomed original width
		if (!$width && !$height)
		{
			$width = $zoomedImportantPart['width'];
		}

		if ($mode === 'proportional' && $width && $height)
		{
			if ($zoomedImportantPart['width'] >= $zoomedImportantPart['height'])
			{
				$height = null;
			}
			else
			{
				$width = null;
			}
		}

		elseif ($mode === 'box' && $width && $height)
		{
			if ($zoomedImportantPart['height'] * $width / $zoomedImportantPart['width'] <= $height)
			{
				$height = null;
			}
			else
			{
				$width = null;
			}
		}

		// Crop mode
		if ($width && $height)
		{
			// Calculate the image part for zoom 0
			$leastZoomed = array(
				'x' => 0,
				'y' => 0,
				'width' => $originalWidth,
				'height' => $originalHeight,
			);
			if ($originalHeight * $width / $originalWidth <= $height)
			{
				$leastZoomed['width'] = $originalHeight * $width / $height;
				if ($leastZoomed['width'] > $importantPart['width'])
				{
					$leastZoomed['x'] = ($originalWidth - $leastZoomed['width'])
						* $importantPart['x'] / ($originalWidth - $importantPart['width']);
				}
				else
				{
					$leastZoomed['x'] = $importantPart['x'] + (($importantPart['width'] - $leastZoomed['width']) / 2);
				}
			}
			else
			{
				$leastZoomed['height'] = $originalWidth * $height / $width;
				if ($leastZoomed['height'] > $importantPart['height'])
				{
					$leastZoomed['y'] = ($originalHeight - $leastZoomed['height'])
						* $importantPart['y'] / ($originalHeight - $importantPart['height']);
				}
				else
				{
					$leastZoomed['y'] = $importantPart['y'] + (($importantPart['height'] - $leastZoomed['height']) / 2);
				}
			}

			// Calculate the image part for zoom 100
			$mostZoomed = $importantPart;
			if ($importantPart['height'] * $width / $importantPart['width'] <= $height)
			{
				$mostZoomed['height'] = $height * $importantPart['width'] / $width;
				if ($originalHeight > $importantPart['height'])
				{
					$mostZoomed['y'] -= ($mostZoomed['height'] - $importantPart['height'])
						* $importantPart['y'] / ($originalHeight - $importantPart['height']);
				}
			}
			else
			{
				$mostZoomed['width'] = $width * $mostZoomed['height'] / $height;
				if ($originalWidth > $importantPart['width'])
				{
					$mostZoomed['x'] -= ($mostZoomed['width'] - $importantPart['width'])
						* $importantPart['x'] / ($originalWidth - $importantPart['width']);
				}
			}

			if ($mostZoomed['width'] > $leastZoomed['width'])
			{
				$mostZoomed = $leastZoomed;
			}

			// Apply zoom
			foreach (array('x', 'y', 'width', 'height') as $key)
			{
				$zoomedImportantPart[$key] = ($mostZoomed[$key] * $zoom) + ($leastZoomed[$key] * (1 - $zoom));
			}

			$targetX = -$zoomedImportantPart['x'] * $width / $zoomedImportantPart['width'];
			$targetY = -$zoomedImportantPart['y'] * $height / $zoomedImportantPart['height'];
			$targetWidth = $originalWidth * $width / $zoomedImportantPart['width'];
			$targetHeight = $originalHeight * $height / $zoomedImportantPart['height'];
		}

		else
		{
			// Calculate the height if only the width is given
			if ($width)
			{
				$height = max($zoomedImportantPart['height'] * $width / $zoomedImportantPart['width'], 1);
			}
			// Calculate the width if only the height is given
			elseif ($height)
			{
				$width = max($zoomedImportantPart['width'] * $height / $zoomedImportantPart['height'], 1);
			}

			// Apply zoom
			$targetWidth = $originalWidth / $zoomedImportantPart['width'] * $width;
			$targetHeight = $originalHeight / $zoomedImportantPart['height'] * $height;
			$targetX = -$zoomedImportantPart['x'] * $targetWidth / $originalWidth;
			$targetY = -$zoomedImportantPart['y'] * $targetHeight / $originalHeight;
		}

		return array(
			'width' => (int)round($width),
			'height' => (int)round($height),
			'target_x' => (int)round($targetX),
			'target_y' => (int)round($targetY),
			'target_width' => (int)round($targetWidth),
			'target_height' => (int)round($targetHeight),
		);
	}


	/**
	 * Create a GD image
	 *
	 * @param integer $width
	 * @param integer $height
	 *
	 * @return resource GD image
	 */
	public static function createGdImage($width, $height)
	{
		$gdImage = imagecreatetruecolor($width, $height);

		$arrGdinfo = gd_info();
		$strGdVersion = preg_replace('/[^0-9\.]+/', '', $arrGdinfo['GD Version']);

		// Handle transparency (GDlib >= 2.0 required)
		if (version_compare($strGdVersion, '2.0', '>='))
		{
			imagealphablending($gdImage, false);
			imagefill($gdImage, 0, 0, imagecolorallocatealpha($gdImage, 0, 0, 0, 127));
			imagesavealpha($gdImage, true);
		}

		return $gdImage;
	}


	/**
	 * Get the GD image representation from a file
	 *
	 * @param \File $objFile
	 *
	 * @return resource GD image
	 */
	public static function getGdImageFromFile(\File $objFile)
	{
		$arrGdinfo = gd_info();
		$strGdImage = null;

		switch ($objFile->extension)
		{
			case 'gif':
				if ($arrGdinfo['GIF Read Support'])
				{
					$strGdImage = imagecreatefromgif(TL_ROOT . '/' . $objFile->path);
				}
				break;

			case 'jpg':
			case 'jpeg':
				if ($arrGdinfo['JPG Support'] || $arrGdinfo['JPEG Support'])
				{
					$strGdImage = imagecreatefromjpeg(TL_ROOT . '/' . $objFile->path);
				}
				break;

			case 'png':
				if ($arrGdinfo['PNG Support'])
				{
					$strGdImage = imagecreatefrompng(TL_ROOT . '/' . $objFile->path);
				}
				break;
		}

		return $strGdImage;
	}


	/**
	 * Save a GD image resource to a file
	 *
	 * @param resource $strGdImage
	 * @param string   $path
	 * @param string   $extension  The file extension
	 *
	 * @return void
	 */
	public static function saveGdImageToFile($strGdImage, $path, $extension)
	{
		$arrGdinfo = gd_info();

		// Fallback to PNG if GIF ist not supported
		if ($extension == 'gif' && !$arrGdinfo['GIF Create Support'])
		{
			$extension = 'png';
		}

		// Create the new image
		switch ($extension)
		{
			case 'gif':
				$strGdImage = static::convertGdImageToPaletteImage($strGdImage);
				imagegif($strGdImage, $path);
				break;

			case 'jpg':
			case 'jpeg':
				imageinterlace($strGdImage, 1); // see #6529
				imagejpeg($strGdImage, $path, (\Config::get('jpgQuality') ?: 80));
				break;

			case 'png':
				if (static::countGdImageColors($strGdImage, 256) <= 256 && !static::isGdImageSemitransparent($strGdImage))
				{
					$strGdImage = static::convertGdImageToPaletteImage($strGdImage);
				}
				imagepng($strGdImage, $path);
				break;
		}
	}


	/**
	 * Convert a true color image to a palette image with 256 colors
	 * and preserve transparency
	 *
	 * @param resource $image GD true color image
	 *
	 * @return resource The GD palette image
	 */
	public static function convertGdImageToPaletteImage($image)
	{
		$width = imagesx($image);
		$height = imagesy($image);

		$transparentColor = null;

		if (static::countGdImageColors($image, 256) <= 256)
		{
			$paletteImage = imagecreate($width, $height);
			$colors = array();
			$isTransparent = false;
			for ($x = 0; $x < $width; $x++)
			{
				for ($y = 0; $y < $height; $y++)
				{
					$color = imagecolorat($image, $x, $y);
					// Check if the pixel is fully transparent
					if ((($color >> 24) & 0x7F) === 127)
					{
						$isTransparent = true;
					}
					else
					{
						$colors[$color & 0xFFFFFF] = true;
					}
				}
			}
			$colors = array_keys($colors);
			foreach ($colors as $index => $color)
			{
				imagecolorset($paletteImage, $index, ($color >> 16) & 0xFF, ($color >> 8) & 0xFF, $color & 0xFF);
			}

			if ($isTransparent)
			{
				$transparentColor = imagecolorallocate($paletteImage, 0, 0, 0);
				imagecolortransparent($paletteImage, $transparentColor);
			}

			imagecopy($paletteImage, $image, 0, 0, 0, 0, $width, $height);
		}
		else
		{
			$paletteImage = imagecreatetruecolor($width, $height);
			imagealphablending($paletteImage, false);
			imagesavealpha($paletteImage, true);
			imagecopy($paletteImage, $image, 0, 0, 0, 0, $width, $height);

			// 256 minus 1 for the transparent color
			imagetruecolortopalette($paletteImage, false, 255);
			$transparentColor = imagecolorallocate($paletteImage, 0, 0, 0);
			imagecolortransparent($paletteImage, $transparentColor);
		}

		if ($transparentColor !== null)
		{
			// Fix fully transparent pixels
			for ($x = 0; $x < $width; $x++)
			{
				for ($y = 0; $y < $height; $y++)
				{
					// Check if the pixel is fully transparent
					if (((imagecolorat($image, $x, $y) >> 24) & 0x7F) === 127)
					{
						imagefilledrectangle($paletteImage, $x, $y, $x, $y, $transparentColor);
					}
				}
			}
		}

		return $paletteImage;
	}


	/**
	 * Count the number of colors in a true color image
	 *
	 * @param resource $image
	 * @param integer  $max   Stop parsing the image if more colors than $max were found
	 *
	 * @return integer
	 */
	public static function countGdImageColors($image, $max = null)
	{
		$colors = array();
		$width = imagesx($image);
		$height = imagesy($image);

		for ($x = 0; $x < $width; $x++)
		{
			for ($y = 0; $y < $height; $y++)
			{
				$colors[imagecolorat($image, $x, $y)] = true;
				if ($max !== null && count($colors) > $max)
				{
					break 2;
				}
			}
		}

		return count($colors);
	}


	/**
	 * Detect if the image contains semitransparent pixels
	 *
	 * @param resource $image
	 *
	 * @return boolean
	 */
	public static function isGdImageSemitransparent($image)
	{
		$width = imagesx($image);
		$height = imagesy($image);

		for ($x = 0; $x < $width; $x++)
		{
			for ($y = 0; $y < $height; $y++)
			{
				// Check if the pixel is semitransparent
				$alpha = (imagecolorat($image, $x, $y) >> 24) & 0x7F;
				if ($alpha > 0 && $alpha < 127)
				{
					return true;
				}
			}
		}

		return false;
	}


	/**
	 * Generate an image tag and return it as string
	 *
	 * @param string $src        The image path
	 * @param string $alt        An optional alt attribute
	 * @param string $attributes A string of other attributes
	 *
	 * @return string The image HTML tag
	 */
	public static function getHtml($src, $alt='', $attributes='')
	{
		$static = TL_FILES_URL;
		$src = rawurldecode($src);

		if (strpos($src, '/') === false)
		{
			if (strncmp($src, 'icon', 4) === 0)
			{
				$static = TL_ASSETS_URL;
				$src = 'assets/contao/images/' . $src;
			}
			else
			{
				$src = 'system/themes/' . \Backend::getTheme() . '/images/' . $src;
			}
		}

		if (!file_exists(TL_ROOT .'/'. $src))
		{
			return '';
		}

		$size = getimagesize(TL_ROOT .'/'. $src);
		return '<img src="' . $static . \System::urlEncode($src) . '" ' . $size[3] . ' alt="' . specialchars($alt) . '"' . (($attributes != '') ? ' ' . $attributes : '') . '>';
	}


	/**
	 * @deprecated
	 * Resize or crop an image and replace the original with the resized version
	 *
	 * @param string  $image  The image path
	 * @param integer $width  The target width
	 * @param integer $height The target height
	 * @param string  $mode   The resize mode
	 *
	 * @return boolean True if the image could be resized successfully
	 */
	public static function resize($image, $width, $height, $mode='')
	{
		try {
			$imageObj = new static($image);
			$resizedPath = $imageObj->setTargetWidth($width)
				->setTargetHeight($height)
				->setResizeMode($mode)
				->setTargetPath($image)
				->setForceOverride(true)
				->executeResize()
				->getResizedPath();

			return (bool) $resizedPath;
		}
		catch (\InvalidArgumentException $e)
		{
			return false;
		}
	}


	/**
	 * @deprecated
	 * Resize an image and store the resized version in the assets/images folder
	 *
	 * @param string  $image         The image path
	 * @param integer $width         The target width
	 * @param integer $height        The target height
	 * @param string  $mode          The resize mode
	 * @param string  $target        An optional target path
	 * @param boolean $force         Override existing target images
	 * @param integer $zoom          Zoom between 0 and 100
	 * @param array   $importantPart Important part of the image, keys: x, y, width, height
	 *
	 * @return string|null The path of the resized image or null
	 */
	public static function get($image, $width, $height, $mode='', $target=null, $force=false, $zoom=0, $importantPart=null)
	{
		try
		{
			$imageObj = new Image($image);
		}
		catch (\InvalidArgumentException $e)
		{
			// @todo catch specific exceptions to log properly
			\System::log('Image "' . $image . '" could not be found', __METHOD__, TL_ERROR);
			return null;
		}

		// Load the image size from the database if $mode is an id
		if (is_numeric($mode) && $imageSize = \ImageSizeModel::findByPk($mode))
		{
			$fileRecord = \FilesModel::findByPath($image);
			if ($fileRecord && $fileRecord->importantPartWidth && $fileRecord->importantPartHeight)
			{
				$importantPart = array(
					'x' => (int)$fileRecord->importantPartX,
					'y' => (int)$fileRecord->importantPartY,
					'width' => (int)$fileRecord->importantPartWidth,
					'height' => (int)$fileRecord->importantPartHeight,
				);
			}

			try
			{
				$resizedPath = $imageObj->setTargetWidth($width)
					->setTargetHeight($height)
					->setResizeMode($mode)
					->setTargetPath($target)
					->setForceOverride($force)
					->setZoomLevel($zoom)
					->setImportantPart($importantPart)
					->executeResize()
					->getResizedPath();

				// BC
				if ($resizedPath == '')
				{
					return null;
				}

				return $resizedPath;
			}
			catch (\InvalidArgumentException $e)
			{
				return null;
			}
		}
	}
}
