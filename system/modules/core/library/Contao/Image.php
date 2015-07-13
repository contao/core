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
 * Resizes images
 *
 * The class resizes images and stores them in the assets/images folder.
 *
 * Usage:
 *
 *     $imageObj = new Image(new File('example.jpg'));
 *
 *     $src = $imageObj->setTargetWidth(640)
 *                     ->setTargetHeight(480)
 *                     ->setResizeMode('center_center')
 *                     ->executeResize()
 *                     ->getResizedPath();
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 * @author Martin Auswöger <https://github.com/ausi>
 * @author Yanick Witschi <https://github.com/Toflar>
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
	 * The target width
	 *
	 * @var integer
	 */
	protected $targetWidth = 0;

	/**
	 * The target height
	 *
	 * @var integer
	 */
	protected $targetHeight = 0;

	/**
	 * The resize mode (defaults to crop for BC)
	 *
	 * @var string
	 */
	protected $resizeMode = 'crop';

	/**
	 * The target path
	 *
	 * @var string
	 */
	protected $targetPath = '';

	/**
	 * Override an existing target
	 *
	 * @var boolean
	 */
	protected $forceOverride = false;

	/**
	 * Zoom level (between 0 and 100)
	 *
	 * @var integer
	 */
	protected $zoomLevel = 0;

	/**
	 * Important part settings
	 *
	 * @var array
	 */
	protected $importantPart = array();


	/**
	 * Create a new object to handle an image
	 *
	 * @param \File $file A file instance of the original image
	 *
	 * @throws \InvalidArgumentException If the file does not exists or cannot be processed
	 */
	public function __construct(\File $file)
	{
		// Check whether the file exists
		if (!$file->exists())
		{
			throw new \InvalidArgumentException('Image "' . $file->path . '" could not be found');
		}

		$this->fileObj = $file;
		$arrAllowedTypes = array_map('trim', explode(',', \Config::get('validImageTypes')));

		// Check the file type
		if (!in_array($this->fileObj->extension, $arrAllowedTypes))
		{
			throw new \InvalidArgumentException('Image type "' . $this->fileObj->extension . '" was not allowed to be processed');
		}
	}


	/**
	 * Override the target image
	 *
	 * @param boolean $forceOverride True to override the target image
	 *
	 * @return $this The image object
	 */
	public function setForceOverride($forceOverride)
	{
		$this->forceOverride = (bool) $forceOverride;

		return $this;
	}


	/**
	 * Get force override setting
	 *
	 * @return boolean True if the target image will be overridden
	 */
	public function getForceOverride()
	{
		return $this->forceOverride;
	}


	/**
	 * Set the important part settings
	 *
	 * @param array $importantPart The settings array
	 *
	 * @return $this The image object
	 *
	 * @throws \InvalidArgumentException If the settings array is malformed
	 */
	public function setImportantPart(array $importantPart = null)
	{
		if ($importantPart !== null)
		{
			if (!isset($importantPart['x']) || !isset($importantPart['y']) || !isset($importantPart['width']) || !isset($importantPart['height']))
			{
				throw new \InvalidArgumentException('Malformed array for setting the important part!');
			}

			$this->importantPart = array
			(
				'x' => max(0, min($this->fileObj->width - 1, (int) $importantPart['x'])),
				'y' => max(0, min($this->fileObj->height - 1, (int) $importantPart['y'])),
			);

			$this->importantPart['width'] = max(1, min($this->fileObj->width - $this->importantPart['x'], (int) $importantPart['width']));
			$this->importantPart['height'] = max(1, min($this->fileObj->height - $this->importantPart['y'], (int) $importantPart['height']));

		}
		else
		{
			$this->importantPart = null;
		}

		return $this;
	}


	/**
	 * Get the important part settings
	 *
	 * @return array The settings array
	 */
	public function getImportantPart()
	{
		if ($this->importantPart)
		{
			return $this->importantPart;
		}

		return array('x'=>0, 'y'=>0, 'width'=>$this->fileObj->width, 'height'=>$this->fileObj->height,);
	}


	/**
	 * Set the target height
	 *
	 * @param integer $targetHeight The target height
	 *
	 * @return $this The image object
	 */
	public function setTargetHeight($targetHeight)
	{
		$this->targetHeight = (int) $targetHeight;

		return $this;
	}


	/**
	 * Get the target height
	 *
	 * @return integer The target height
	 */
	public function getTargetHeight()
	{
		return $this->targetHeight;
	}


	/**
	 * Set the target width
	 *
	 * @param integer $targetWidth The target width
	 *
	 * @return $this The image object
	 */
	public function setTargetWidth($targetWidth)
	{
		$this->targetWidth = (int) $targetWidth;

		return $this;
	}


	/**
	 * Get the target width
	 *
	 * @return integer The target width
	 */
	public function getTargetWidth()
	{
		return $this->targetWidth;
	}


	/**
	 * Set the target path
	 *
	 * @param string $targetPath The target path
	 *
	 * @return $this The image object
	 */
	public function setTargetPath($targetPath)
	{
		$this->targetPath = (string) $targetPath;

		return $this;
	}


	/**
	 * Get the target path
	 *
	 * @return string The target path
	 */
	public function getTargetPath()
	{
		return $this->targetPath;
	}


	/**
	 * Set the zoom level
	 *
	 * @param integer $zoomLevel The zoom level
	 *
	 * @return $this The object instance
	 *
	 * @throws \InvalidArgumentException If the zoom level is out of bounds
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
	 * @return integer The zoom level
	 */
	public function getZoomLevel()
	{
		return $this->zoomLevel;
	}


	/**
	 * Set the resize mode
	 *
	 * @param string $resizeMode The resize mode
	 *
	 * @return $this The image object
	 */
	public function setResizeMode($resizeMode)
	{
		$this->resizeMode = $resizeMode;

		return $this;
	}


	/**
	 * Get the resize mode
	 *
	 * @return string The resize mode
	 */
	public function getResizeMode()
	{
		return $this->resizeMode;
	}


	/**
	 * Get the path of the original image
	 *
	 * @return string The path of the original image
	 */
	public function getOriginalPath()
	{
		return $this->fileObj->path;
	}


	/**
	 * Get the path of the resized image
	 *
	 * @return string The path of the resized image
	 */
	public function getResizedPath()
	{
		return $this->resizedPath;
	}


	/**
	 * Get the cache name
	 *
	 * @return string The cache name
	 */
	public function getCacheName()
	{
		$importantPart = $this->getImportantPart();

		$strCacheKey = substr(md5
		(
			  '-w' . $this->getTargetWidth()
			. '-h' . $this->getTargetHeight()
			. '-o' . $this->getOriginalPath()
			. '-m' . $this->getResizeMode()
			. '-z' . $this->getZoomLevel()
			. '-x' . $importantPart['x']
			. '-y' . $importantPart['y']
			. '-i' . $importantPart['width']
			. '-e' . $importantPart['height']
			. '-t' . $this->fileObj->mtime
		), 0, 8);

		return 'assets/images/' . substr($strCacheKey, -1) . '/' . $this->fileObj->filename . '-' . $strCacheKey . '.' . $this->fileObj->extension;
	}


	/**
	 * Resize the image
	 *
	 * @return $this The image object
	 */
	public function executeResize()
	{
		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['executeResize']) && is_array($GLOBALS['TL_HOOKS']['executeResize']))
		{
			foreach ($GLOBALS['TL_HOOKS']['executeResize'] as $callback)
			{
				$return = \System::importStatic($callback[0])->$callback[1]($this);

				if (is_string($return))
				{
					$this->resizedPath = \System::urlEncode($return);

					return $this;
				}
			}
		}

		$importantPart = $this->getImportantPart();

		$widthMatches = ($this->fileObj->width == $this->getTargetWidth() || !$this->getTargetWidth());
		$heightMatches = ($this->fileObj->height == $this->getTargetHeight() || !$this->getTargetHeight());
		$zoomMatches = (($importantPart['x'] === 0 && $importantPart['y'] === 0 && $importantPart['width'] === $this->fileObj->width && $importantPart['height'] === $this->fileObj->height) || !$this->getZoomLevel());

		// No resizing required
		if ($widthMatches && $heightMatches && $zoomMatches)
		{
			// Return the target image (thanks to Tristan Lins) (see #4166)
			if ($this->getTargetPath())
			{
				// Copy the source image if the target image does not exist or is older than the source image
				if (!file_exists(TL_ROOT . '/' . $this->getTargetPath()) || $this->fileObj->mtime > filemtime(TL_ROOT . '/' . $this->getTargetPath()))
				{
					\Files::getInstance()->copy($this->getOriginalPath(), $this->getTargetPath());
				}

				$this->resizedPath = \System::urlEncode($this->getTargetPath());

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
				if (file_exists(TL_ROOT . '/' . $this->getTargetPath()) && $this->fileObj->mtime <= filemtime(TL_ROOT . '/' . $this->getTargetPath()))
				{
					$this->resizedPath = \System::urlEncode($this->getOriginalPath());

					return $this;
				}
			}

			// Regular cache file
			if (file_exists(TL_ROOT . '/' . $this->getCacheName()))
			{
				// Copy the cached file if it exists
				if ($this->getTargetPath())
				{
					\Files::getInstance()->copy($this->getCacheName(), $this->getTargetPath());
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
				$return = \System::importStatic($callback[0])->$callback[1]($this->getOriginalPath(), $this->getTargetWidth(), $this->getTargetHeight(), $this->getResizeMode(), $this->getCacheName(), $this->fileObj, $this->getTargetPath(), $this);

				if (is_string($return))
				{
					$this->resizedPath = \System::urlEncode($return);

					return $this;
				}
			}
		}

		$svgNotPossible = ($this->fileObj->isSvgImage && !extension_loaded('dom'));
		$gdNotPossible = ($this->fileObj->isGdImage && (!extension_loaded('gd') || $this->fileObj->width > \Config::get('gdMaxImgWidth') || $this->fileObj->height > \Config::get('gdMaxImgHeight') || $this->getTargetWidth() > \Config::get('gdMaxImgWidth') || $this->getTargetHeight() > \Config::get('gdMaxImgHeight')));

		// Return the path to the original image if it cannot be handled
		if (!$this->fileObj->isImage || $svgNotPossible || $gdNotPossible)
		{
			$this->resizedPath = \System::urlEncode($this->getOriginalPath());

			return $this;
		}

		// Create the resized image
		if ($this->fileObj->isSvgImage)
		{
			$this->executeResizeSvg();
		}
		else
		{
			$this->executeResizeGd();
		}

		// Set the file permissions when the Safe Mode Hack is used
		if (\Config::get('useFTP'))
		{
			\Files::getInstance()->chmod($this->getCacheName(), \Config::get('defaultFileChmod'));
		}

		// Resize the original image
		if ($this->getTargetPath())
		{
			\Files::getInstance()->copy($this->getCacheName(), $this->getTargetPath());
			$this->resizedPath = \System::urlEncode($this->getTargetPath());

			return $this;
		}

		$this->resizedPath = \System::urlEncode($this->getCacheName());

		return $this;
	}


	/**
	 * Resize an GD image
	 *
	 * @return boolean False if the target image cannot be created, otherwise true
	 */
	protected function executeResizeGd()
	{
		$sourceImage = \GdImage::fromFile($this->fileObj);

		$coordinates = $this->computeResize();
		$newImage = \GdImage::fromDimensions($coordinates['width'], $coordinates['height']);

		$sourceImage->copyTo($newImage, $coordinates['target_x'], $coordinates['target_y'], $coordinates['target_width'], $coordinates['target_height']);

		$newImage->saveToFile(TL_ROOT . '/' . $this->getCacheName());
	}


	/**
	 * Resize an SVG image
	 */
	protected function executeResizeSvg()
	{
		$doc = new \DOMDocument();

		if ($this->fileObj->extension == 'svgz')
		{
			$doc->loadXML(gzdecode($this->fileObj->getContent()));
		}
		else
		{
			$doc->loadXML($this->fileObj->getContent());
		}

		$svgElement = $doc->documentElement;

		// Set the viewBox attribute from the original dimensions
		if (!$svgElement->hasAttribute('viewBox'))
		{
			$origWidth = $svgElement->getAttribute('width');
			$origHeight = $svgElement->getAttribute('height');

			$svgElement->setAttribute('viewBox', '0 0 ' . floatval($origWidth) . ' ' . floatval($origHeight));
		}

		$coordinates = $this->computeResize();

		$svgElement->setAttribute('x', $coordinates['target_x']);
		$svgElement->setAttribute('y', $coordinates['target_y']);
		$svgElement->setAttribute('width', $coordinates['target_width']);
		$svgElement->setAttribute('height', $coordinates['target_height']);

		$svgWrapElement = $doc->createElementNS('http://www.w3.org/2000/svg', 'svg');
		$svgWrapElement->setAttribute('version', '1.1');
		$svgWrapElement->setAttribute('width', $coordinates['width']);
		$svgWrapElement->setAttribute('height', $coordinates['height']);
		$svgWrapElement->appendChild($svgElement);

		$doc->appendChild($svgWrapElement);

		if ($this->fileObj->extension == 'svgz')
		{
			$xml = gzencode($doc->saveXML());
		}
		else
		{
			$xml = $doc->saveXML();
		}

		$objCacheFile = new \File($this->getCacheName(), true);
		$objCacheFile->write($xml);
		$objCacheFile->close();
	}


	/**
	 * Calculate the resize coordinates
	 *
	 * @return array The resize coordinates (width, height, target_x, target_y, target_width, target_height)
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

		// Backwards compatibility for old modes
		// left_top, center_top, right_top, left_center, center_center, right_center, left_bottom, center_bottom, right_bottom
		if ($mode && substr_count($mode, '_') === 1)
		{
			$zoom = 0;
			$importantPart = array('x'=>0, 'y'=>0, 'width'=>$originalWidth, 'height'=>$originalHeight);

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

		$zoom = max(0, min(1, (int) $zoom / 100));

		$zoomedImportantPart = array
		(
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
			$leastZoomed = array
			(
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
					$leastZoomed['x'] = ($originalWidth - $leastZoomed['width']) * $importantPart['x'] / ($originalWidth - $importantPart['width']);
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
					$leastZoomed['y'] = ($originalHeight - $leastZoomed['height']) * $importantPart['y'] / ($originalHeight - $importantPart['height']);
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
					$mostZoomed['y'] -= ($mostZoomed['height'] - $importantPart['height']) * $importantPart['y'] / ($originalHeight - $importantPart['height']);
				}
			}
			else
			{
				$mostZoomed['width'] = $width * $mostZoomed['height'] / $height;

				if ($originalWidth > $importantPart['width'])
				{
					$mostZoomed['x'] -= ($mostZoomed['width'] - $importantPart['width']) * $importantPart['x'] / ($originalWidth - $importantPart['width']);
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

		return array
		(
			'width' => (int) round($width),
			'height' => (int) round($height),
			'target_x' => (int) round($targetX),
			'target_y' => (int) round($targetY),
			'target_width' => (int) round($targetWidth),
			'target_height' => (int) round($targetHeight),
		);
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

		$objFile = new \File($src, true);

		return '<img src="' . $static . \System::urlEncode($src) . '" width="' . $objFile->width . '" height="' . $objFile->height . '" alt="' . specialchars($alt) . '"' . (($attributes != '') ? ' ' . $attributes : '') . '>';
	}


	/**
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
		return static::get($image, $width, $height, $mode, $image, true) ? true : false;
	}


	/**
	 * Create an image instance from the given image path and size
	 *
	 * @param string|File   $image The image path or File instance
	 * @param array|integer $size  The image size as array (width, height, resize mode) or an tl_image_size ID
	 *
	 * @return static The created image instance
	 */
	public static function create($image, $size=null)
	{
		if (is_string($image))
		{
			$image = new \File(rawurldecode($image), true);
		}

		/** @var \Image $imageObj */
		$imageObj = new static($image);

		// tl_image_size ID as resize mode
		if (is_array($size) && !empty($size[2]) && is_numeric($size[2]))
		{
			$size = (int) $size[2];
		}

		if (is_array($size))
		{
			$size = $size + array(0, 0, 'crop');

			$imageObj->setTargetWidth($size[0])
					 ->setTargetHeight($size[1])
					 ->setResizeMode($size[2]);
		}

		// Load the image size from the database if $size is an ID
		elseif (($imageSize = \ImageSizeModel::findByPk($size)) !== null)
		{
			$imageObj->setTargetWidth($imageSize->width)
					 ->setTargetHeight($imageSize->height)
					 ->setResizeMode($imageSize->resizeMode)
					 ->setZoomLevel($imageSize->zoom);
		}

		$fileRecord = \FilesModel::findByPath($image->path);

		// Set the important part
		if ($fileRecord !== null && $fileRecord->importantPartWidth && $fileRecord->importantPartHeight)
		{
			$imageObj->setImportantPart(array
			(
				'x' => (int) $fileRecord->importantPartX,
				'y' => (int) $fileRecord->importantPartY,
				'width' => (int) $fileRecord->importantPartWidth,
				'height' => (int) $fileRecord->importantPartHeight,
			));
		}

		return $imageObj;
	}


	/**
	 * Resize an image and store the resized version in the assets/images folder
	 *
	 * @param string  $image        The image path
	 * @param integer $width        The target width
	 * @param integer $height       The target height
	 * @param string  $mode         The resize mode
	 * @param string  $target       An optional target path
	 * @param boolean $force        Override existing target images
	 *
	 * @return string|null The path of the resized image or null
	 */
	public static function get($image, $width, $height, $mode='', $target=null, $force=false)
	{
		if ($image == '')
		{
			return null;
		}

		try
		{
			$imageObj = static::create($image, array($width, $height, $mode));
			$imageObj->setTargetPath($target);
			$imageObj->setForceOverride($force);

			return $imageObj->executeResize()->getResizedPath() ?: null;
		}
		catch (\Exception $e)
		{
			\System::log('Image "' . $image . '" could not be processed: ' . $e->getMessage(), __METHOD__, TL_ERROR);

			return null;
		}
	}


	/**
	 * Convert sizes like 2em, 10% or 12pt to pixels
	 *
	 * @param string $size The size string
	 *
	 * @return integer The pixel value
	 */
	public static function getPixelValue($size)
	{
		$value = preg_replace('/[^0-9\.-]+/', '', $size);
		$unit = preg_replace('/[^acehimnprtvwx%]/', '', $size);

		// Convert 12pt = 16px = 1em = 100%
		switch ($unit)
		{
			case '':
			case 'px':
				return (int) round($value);
				break;

			case 'em':
				return (int) round($value * 16);
				break;

			case 'pt':
				return (int) round($value * (16 / 12));
				break;

			case '%':
				return (int) round($value * (16 / 100));
				break;
		}

		return 0;
	}
}
