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
	 * Resize an image and store the resized version in the assets/images folder
	 *
	 * @param string  $image  The image path
	 * @param integer $width  The target width
	 * @param integer $height The target height
	 * @param string  $mode   The resize mode
	 * @param string  $target An optional target path
	 * @param boolean $force  Override existing target images
	 *
	 * @return string|null The path of the resized image or null
	 */
	public static function get($image, $width, $height, $mode='', $target=null, $force=false)
	{
		if ($image == '')
		{
			return null;
		}

		$image = rawurldecode($image);

		// Check whether the file exists
		if (!is_file(TL_ROOT . '/' . $image))
		{
			\System::log('Image "' . $image . '" could not be found', __METHOD__, TL_ERROR);
			return null;
		}

		$objFile = new \File($image, true);
		$arrAllowedTypes = trimsplit(',', strtolower(\Config::get('validImageTypes')));

		// Check the file type
		if (!in_array($objFile->extension, $arrAllowedTypes))
		{
			\System::log('Image type "' . $objFile->extension . '" was not allowed to be processed', __METHOD__, TL_ERROR);
			return null;
		}

		// No resizing required
		if (($objFile->width == $width || !$width) && ($objFile->height == $height || !$height))
		{
			// Return the target image (thanks to Tristan Lins) (see #4166)
			if ($target)
			{
				// Copy the source image if the target image does not exist or is older than the source image
				if (!file_exists(TL_ROOT . '/' . $target) || $objFile->mtime > filemtime(TL_ROOT . '/' . $target))
				{
					\Files::getInstance()->copy($image, $target);
				}

				return \System::urlEncode($target);
			}

			return \System::urlEncode($image);
		}

		// No mode given
		if ($mode == '')
		{
			// Backwards compatibility
			if ($width && $height)
			{
				$mode = 'center_top';
			}
			else
			{
				$mode = 'proportional';
			}
		}

		// Backwards compatibility
		if ($mode == 'crop')
		{
			$mode = 'center_center';
		}

		$strCacheKey = substr(md5('-w' . $width . '-h' . $height . '-' . $image . '-' . $mode . '-' . $objFile->mtime), 0, 8);
		$strCacheName = 'assets/images/' . substr($strCacheKey, -1) . '/' . $objFile->filename . '-' . $strCacheKey . '.' . $objFile->extension;

		// Check whether the image exists already
		if (!\Config::get('debugMode'))
		{
			// Custom target (thanks to Tristan Lins) (see #4166)
			if ($target && !$force)
			{
				if (file_exists(TL_ROOT . '/' . $target) && $objFile->mtime <= filemtime(TL_ROOT . '/' . $target))
				{
					return \System::urlEncode($target);
				}
			}

			// Regular cache file
			if (file_exists(TL_ROOT . '/' . $strCacheName))
			{
				// Copy the cached file if it exists
				if ($target)
				{
					\Files::getInstance()->copy($strCacheName, $target);
					return \System::urlEncode($target);
				}

				return \System::urlEncode($strCacheName);
			}
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getImage']) && is_array($GLOBALS['TL_HOOKS']['getImage']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getImage'] as $callback)
			{
				$return = \System::importStatic($callback[0])->$callback[1]($image, $width, $height, $mode, $strCacheName, $objFile, $target);

				if (is_string($return))
				{
					return \System::urlEncode($return);
				}
			}
		}

		// Return the path to the original image if the GDlib cannot handle it
		if (!extension_loaded('gd') || !$objFile->isGdImage || $objFile->width > \Config::get('gdMaxImgWidth') || $objFile->height > \Config::get('gdMaxImgHeight') || (!$width && !$height) || $width > \Config::get('gdMaxImgWidth') || $height > \Config::get('gdMaxImgHeight'))
		{
			return \System::urlEncode($image);
		}

		$coordinates = static::computeResize($width, $height, $objFile->width, $objFile->height, $mode);

		$strNewImage = static::createGdImage($coordinates['width'], $coordinates['height']);

		$strSourceImage = static::getGdImageFromFile($objFile);

		// The new image could not be created
		if (!$strSourceImage)
		{
			imagedestroy($strNewImage);
			\System::log('Image "' . $image . '" could not be processed', __METHOD__, TL_ERROR);
			return null;
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
			$objFile->width,
			$objFile->height
		);

		static::saveGdImageToFile($strNewImage, TL_ROOT . '/' . $strCacheName, $objFile->extension);

		// Destroy the temporary images
		imagedestroy($strSourceImage);
		imagedestroy($strNewImage);

		// Resize the original image
		if ($target)
		{
			\Files::getInstance()->copy($strCacheName, $target);
			return \System::urlEncode($target);
		}

		// Set the file permissions when the Safe Mode Hack is used
		if (\Config::get('useFTP'))
		{
			\Files::getInstance()->chmod($strCacheName, \Config::get('defaultFileChmod'));
		}

		// Return the path to new image
		return \System::urlEncode($strCacheName);
	}

	protected static function computeResize($width, $height, $originalWidth, $originalHeight, $mode)
	{
		if (!$width && !$height)
		{
			$width = $originalWidth;
		}

		$targetX = 0;
		$targetY = 0;
		$targetWidth = $width;
		$targetHeight = $height;

		if ($mode === 'proportional' && $width && $height)
		{
			if ($originalWidth >= $originalHeight)
			{
				$targetHeight = null;
			}
			else
			{
				$targetWidth = null;
			}
		}

		elseif ($mode === 'box' && $width && $height)
		{
			if ($originalHeight * $width / $originalWidth <= $targetHeight)
			{
				$targetHeight = null;
			}
			else
			{
				$targetWidth = null;
			}
		}

		// Resize width and height and crop the image if necessary
		if ($targetWidth && $targetHeight)
		{
			$targetWidth = max($originalWidth * $height / $originalHeight, 1);
			$targetX = ($targetWidth - $width) / -2;

			if ($targetWidth < $width)
			{
				$targetWidth = $width;
				$targetHeight = max($originalHeight * $width / $originalWidth, 1);
				$targetX = 0;
				$targetY = ($targetHeight - $height) / -2;
			}

			// Advanced crop modes
			switch ($mode)
			{
				case 'left_top':
					$targetX = 0;
					$targetY = 0;
					break;

				case 'center_top':
					$targetY = 0;
					break;

				case 'right_top':
					$targetX = $width - $targetWidth;
					$targetY = 0;
					break;

				case 'left_center':
					$targetX = 0;
					break;

				case 'right_center':
					$targetX = $width - $targetWidth;
					break;

				case 'left_bottom':
					$targetX = 0;
					$targetY = $height - $targetHeight;
					break;

				case 'center_bottom':
					$targetY = $height - $targetHeight;
					break;

				case 'right_bottom':
					$targetX = $width - $targetWidth;
					$targetY = $height - $targetHeight;
					break;
			}

			$coordinates = array(
				'width' => (int)round($width),
				'height' => (int)round($height),
			);
		}

		else
		{
			// Calculate the height if only the width is given
			if ($targetWidth)
			{
				$targetHeight = max($originalHeight * $width / $originalWidth, 1);
			}
			// Calculate the width if only the height is given
			elseif ($targetHeight)
			{
				$targetWidth = max($originalWidth * $height / $originalHeight, 1);
			}

			$coordinates = array(
				'width' => (int)round($targetWidth),
				'height' => (int)round($targetHeight),
			);
		}

		$coordinates['target_x'] = (int)round($targetX);
		$coordinates['target_y'] = (int)round($targetY);
		$coordinates['target_width'] = (int)round($targetWidth);
		$coordinates['target_height'] = (int)round($targetHeight);

		return $coordinates;
	}

	protected static function createGdImage($width, $height)
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

	protected static function getGdImageFromFile($objFile)
	{
		$arrGdinfo = gd_info();
		$strGdImage = null;

		switch ($objFile->extension)
		{
			case 'gif':
				if ($arrGdinfo['GIF Read Support'])
				{
					$strGdImage = imagecreatefromgif(TL_ROOT . '/' . $objFile->path);
					$intTranspIndex = imagecolortransparent($strGdImage);
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

	protected static function saveGdImageToFile($strGdImage, $path, $extension)
	{
		$arrGdinfo = gd_info();
		$strGdVersion = preg_replace('/[^0-9\.]+/', '', $arrGdinfo['GD Version']);

		// Fallback to PNG if GIF ist not supported
		if ($extension == 'gif' && !$arrGdinfo['GIF Create Support'])
		{
			$extension = 'png';
		}

		// Create the new image
		switch ($extension)
		{
			case 'gif':
				// TODO: fix transparent GIFs
				imagegif($strGdImage, $path);
				break;

			case 'jpg':
			case 'jpeg':
				imageinterlace($strNewImage, 1); // see #6529
				imagejpeg($strGdImage, $path, (\Config::get('jpgQuality') ?: 80));
				break;

			case 'png':
				// TODO: fix issue #2426
				imagepng($strGdImage, $path);
				break;
		}
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
}
