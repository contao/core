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
 * Resizes images and creates picture data
 *
 * The class resizes images and prepares data for the `<picture>` element.
 *
 * Usage:
 *
 *     $picture = new Picture(new File('example.jpg'));
 *     $data = $picture->setImportantPart(array('x'=>10, 'y'=>10, 'width'=>100, 'height'=>100))
 *                     ->setImageSize(\ImageSizeModel::findByPk(1))
 *                     ->setImageSizeItems(\ImageSizeItemModel::findVisibleByPid(1, array('order'=>'sorting ASC')))
 *                     ->getTemplateData();
 *
 *     // Shortcut
 *     $data = Picture::create('example.jpg', 1)->getTemplateData();
 *     $data = Picture::create('example.jpg', array(100, 100, 'crop'))->getTemplateData();
 *
 * @author Martin Auswöger <https://github.com/ausi>
 * @author Yanick Witschi <https://github.com/Toflar>
 */
class Picture
{

	/**
	 * The Image instance of the source image
	 *
	 * @var \Image
	 */
	protected $image = null;

	/**
	 * The image size
	 *
	 * @var object|\ImageSizeModel
	 */
	protected $imageSize = null;

	/**
	 * The image size items collection
	 *
	 * @var array|\Model\Collection|\ImageSizeItemModel
	 */
	protected $imageSizeItems = array();


	/**
	 * Create a new object to handle a picture element
	 *
	 * @param \File $file A file instance of the source image
	 */
	public function __construct(\File $file)
	{
		$this->image = new \Image($file);
	}


	/**
	 * Create a picture instance from the given image path and size
	 *
	 * @param string|File   $file The image path or File instance
	 * @param array|integer $size  The image size as array (width, height, resize mode) or an tl_image_size ID
	 *
	 * @return static The created picture instance
	 */
	public static function create($file, $size=null)
	{
		if (is_string($file))
		{
			$file = new \File(rawurldecode($file), true);
		}

		$imageSize = null;
		$picture = new static($file);

		// tl_image_size ID as resize mode
		if (is_array($size) && !empty($size[2]) && is_numeric($size[2]))
		{
			$size = (int) $size[2];
		}

		$imageSize = null;

		if (!is_array($size))
		{
			$imageSize = \ImageSizeModel::findByPk($size);

			if ($imageSize === null)
			{
				$size = array();
			}
		}

		if (is_array($size))
		{
			$size = $size + array(0, 0, 'crop');

			$imageSize = new \stdClass();
			$imageSize->width = $size[0];
			$imageSize->height = $size[1];
			$imageSize->resizeMode = $size[2];
			$imageSize->zoom = 0;
		}

		$picture->setImageSize($imageSize);

		if ($imageSize !== null && !empty($imageSize->id))
		{
			$picture->setImageSizeItems(\ImageSizeItemModel::findVisibleByPid($imageSize->id, array('order'=>'sorting ASC')));
		}

		$fileRecord = \FilesModel::findByPath($file->path);

		if ($fileRecord !== null && $fileRecord->importantPartWidth && $fileRecord->importantPartHeight)
		{
			$picture->setImportantPart(array
			(
				'x' => (int) $fileRecord->importantPartX,
				'y' => (int) $fileRecord->importantPartY,
				'width' => (int) $fileRecord->importantPartWidth,
				'height' => (int) $fileRecord->importantPartHeight,
			));
		}

		return $picture;
	}


	/**
	 * Set the important part settings
	 *
	 * @param array $importantPart The settings array
	 *
	 * @return $this The picture object
	 */
	public function setImportantPart(array $importantPart = null)
	{
		$this->image->setImportantPart($importantPart);

		return $this;
	}


	/**
	 * Set the image size
	 *
	 * @param object|\ImageSizeModel $imageSize The image size
	 *
	 * @return $this The picture object
	 */
	public function setImageSize($imageSize)
	{
		$this->imageSize = $imageSize;

		return $this;
	}


	/**
	 * Set the image size items collection
	 *
	 * @param array|\ImageSizeItemModel|\Model\Collection $imageSizeItems The image size items collection
	 *
	 * @return $this The picture object
	 */
	public function setImageSizeItems($imageSizeItems)
	{
		if ($imageSizeItems === null)
		{
			$imageSizeItems = array();
		}

		$this->imageSizeItems = $imageSizeItems;

		return $this;
	}


	/**
	 * Get the picture element definition array
	 *
	 * @return array The picture element definition
	 */
	public function getTemplateData()
	{
		$mainSource = $this->getTemplateDataSource($this->imageSize);
		$sources = array();

		foreach ($this->imageSizeItems as $imageSizeItem)
		{
			$sources[] = $this->getTemplateDataSource($imageSizeItem);
		}

		return array
		(
			'img' => $mainSource,
			'sources' => $sources,
		);
	}


	/**
	 * Get the attributes for one picture source element
	 *
	 * @param object|\Model $imageSize The image size or image size item model
	 *
	 * @return array The source element attributes
	 */
	protected function getTemplateDataSource($imageSize)
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
			$imageObj = clone $this->image;

			$src = $imageObj->setTargetWidth($imageSize->width * $density)
							->setTargetHeight($imageSize->height * $density)
							->setResizeMode($imageSize->resizeMode)
							->setZoomLevel($imageSize->zoom)
							->executeResize()
							->getResizedPath();

			$fileObj = new \File(rawurldecode($src), true);

			if (empty($attributes['src']))
			{
				$attributes['src'] = htmlspecialchars(TL_FILES_URL . $src, ENT_QUOTES);
				$attributes['width'] = $fileObj->width;
				$attributes['height'] = $fileObj->height;
			}

			if (count($densities) > 1)
			{
				// Use pixel density descriptors if the sizes attribute is empty
				if (empty($imageSize->sizes))
				{
					$src .= ' ' . $density . 'x';
				}
				// Otherwise use width descriptors
				else
				{
					$src .= ' ' . $fileObj->width . 'w';
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
}
