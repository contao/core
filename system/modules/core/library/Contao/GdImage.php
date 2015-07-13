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
 * GD image class
 *
 * The class handles GD images.
 *
 * Usage:
 *
 *     $source = GdImage::fromFile($objFile);
 *     $target = GdImage::fromDimensions(100, 100);
 *
 *     $source->copyTo($target, 0, 0, 100, 100);
 *     $target->convertToPaletteImage()
 *            ->saveToFile('image.jpg');
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class GdImage
{

	/**
	 * The GD resource handle
	 *
	 * @var resource
	 */
	protected $gdResource = null;


	/**
	 * Create a new object to handle a GD image
	 *
	 * @param resource $gdResource The GD resource handle
	 */
	public function __construct($gdResource)
	{
		$this->setResource($gdResource);
	}


	/**
	 * Get the GD image object from an image file
	 *
	 * @param \File $file The file object
	 *
	 * @return static The GD image object
	 *
	 * @throws \InvalidArgumentException If the image type cannot be processed
	 * @throws \RuntimeException         If the image failed to be processed
	 */
	public static function fromFile(\File $file)
	{
		$extension = strtolower($file->extension);
		$function = null;

		if ($extension === 'jpg')
		{
			$extension = 'jpeg';
		}

		if (in_array($extension, array('gif', 'jpeg', 'png')))
		{
			$function = 'imagecreatefrom' . $extension;
		}

		if ($function === null || !is_callable($function))
		{
			throw new \InvalidArgumentException('Image type "' . $file->extension . '" cannot be processed by GD');
		}

		$image = $function(TL_ROOT . '/' . $file->path);

		if ($image === false)
		{
			throw new \RuntimeException('Image "' . $file->path . '" failed to be processed by GD');
		}

		return new static($image);
	}


	/**
	 * Get the GD image object for the specified dimensions
	 *
	 * @param integer $width  The image width
	 * @param integer $height The image height
	 *
	 * @return static The GD image object
	 */
	public static function fromDimensions($width, $height)
	{
		$image = imagecreatetruecolor($width, $height);

		$arrGdInfo = gd_info();
		$strGdVersion = preg_replace('/[^0-9\.]+/', '', $arrGdInfo['GD Version']);

		// Handle transparency (GDlib >= 2.0 required)
		if (version_compare($strGdVersion, '2.0', '>='))
		{
			imagealphablending($image, false);
			imagefill($image, 0, 0, imagecolorallocatealpha($image, 0, 0, 0, 127));
			imagesavealpha($image, true);
		}

		return new static($image);
	}


	/**
	 * Get the GD resource handle
	 *
	 * @return resource The GD resource handle
	 */
	public function getResource()
	{
		return $this->gdResource;
	}


	/**
	 * Set the GD resource handle
	 *
	 * @param resource $gdResource The GD resource handle
	 *
	 * @return static
	 *
	 * @throws \InvalidArgumentException If $gdResource is not a GD resource
	 */
	public function setResource($gdResource)
	{
		if (!is_resource($gdResource) || get_resource_type($gdResource) !== 'gd')
		{
			throw new \InvalidArgumentException('$gdResource is not a valid GD resource');
		}

		$this->gdResource = $gdResource;

		return $this;
	}


	/**
	 * Save the GD image to a file
	 *
	 * @param string $path The image path
	 *
	 * @return static
	 *
	 * @throws \InvalidArgumentException If the image type cannot be generated
	 */
	public function saveToFile($path)
	{
		$arrGdInfo = gd_info();
		$extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

		// Fallback to PNG if GIF ist not supported
		if ($extension == 'gif' && !$arrGdInfo['GIF Create Support'])
		{
			$extension = 'png';
		}

		// Create the new image
		switch ($extension)
		{
			case 'gif':
				$this->convertToPaletteImage();
				imagegif($this->gdResource, $path);
				break;

			case 'jpg':
			case 'jpeg':
				imageinterlace($this->gdResource, 1); // see #6529
				imagejpeg($this->gdResource, $path, (\Config::get('jpgQuality') ?: 80));
				break;

			case 'png':
				if ($this->countColors(256) <= 256 && !$this->isSemitransparent())
				{
					$this->convertToPaletteImage();
				}
				imagepng($this->gdResource, $path);
				break;

			default:
				throw new \InvalidArgumentException('Image type "' . $extension . '" cannot be generated');
				break;
		}

		return $this;
	}


	/**
	 * Save the GD image to a file
	 *
	 * @param self    $gdImage The target GD image
	 * @param integer $x       The target X coordinate
	 * @param integer $y       The target Y coordinate
	 * @param integer $width   The target width
	 * @param integer $height  The target height
	 *
	 * @return static
	 */
	public function copyTo(self $gdImage, $x, $y, $width, $height)
	{
		imagecopyresampled($gdImage->getResource(), $this->gdResource, $x, $y, 0, 0, $width, $height, imagesx($this->gdResource), imagesy($this->gdResource));

		return $this;
	}


	/**
	 * Convert a true color image to a palette image with 256 colors and preserve transparency
	 *
	 * @return static
	 */
	public function convertToPaletteImage()
	{
		if (!imageistruecolor($this->gdResource))
		{
			return $this;
		}

		$width = imagesx($this->gdResource);
		$height = imagesy($this->gdResource);

		$transparentColor = null;

		if ($this->countColors(256) <= 256)
		{
			$paletteImage = imagecreate($width, $height);
			$colors = array();
			$isTransparent = false;

			for ($x = 0; $x < $width; $x++)
			{
				for ($y = 0; $y < $height; $y++)
				{
					$color = imagecolorat($this->gdResource, $x, $y);

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

			imagecopy($paletteImage, $this->gdResource, 0, 0, 0, 0, $width, $height);
		}
		else
		{
			$paletteImage = imagecreatetruecolor($width, $height);
			imagealphablending($paletteImage, false);
			imagesavealpha($paletteImage, true);
			imagecopy($paletteImage, $this->gdResource, 0, 0, 0, 0, $width, $height);

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
					if (((imagecolorat($this->gdResource, $x, $y) >> 24) & 0x7F) === 127)
					{
						imagefilledrectangle($paletteImage, $x, $y, $x, $y, $transparentColor);
					}
				}
			}
		}

		imagedestroy($this->gdResource);
		$this->gdResource = $paletteImage;

		return $this;
	}


	/**
	 * Count the number of colors in the image
	 *
	 * @param integer $max Stop parsing the image if more colors than $max were found
	 *
	 * @return integer The number of image colors
	 */
	public function countColors($max = null)
	{
		if (!imageistruecolor($this->gdResource))
		{
			return imagecolorstotal($this->gdResource);
		}

		$colors = array();
		$width = imagesx($this->gdResource);
		$height = imagesy($this->gdResource);

		for ($x = 0; $x < $width; $x++)
		{
			for ($y = 0; $y < $height; $y++)
			{
				$colors[imagecolorat($this->gdResource, $x, $y)] = true;

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
	 * @return boolean True if the image contains semitransparent pixels
	 */
	public function isSemitransparent()
	{
		if (!imageistruecolor($this->gdResource))
		{
			return false;
		}

		$width = imagesx($this->gdResource);
		$height = imagesy($this->gdResource);

		for ($x = 0; $x < $width; $x++)
		{
			for ($y = 0; $y < $height; $y++)
			{
				// Check if the pixel is semitransparent
				$alpha = (imagecolorat($this->gdResource, $x, $y) >> 24) & 0x7F;

				if ($alpha > 0 && $alpha < 127)
				{
					return true;
				}
			}
		}

		return false;
	}


	/**
	 * Destroy the GD resource to free memory
	 */
	public function __destruct()
	{
		if (is_resource($this->gdResource))
		{
			imagedestroy($this->gdResource);
		}
	}
}
