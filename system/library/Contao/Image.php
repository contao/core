<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Library
 * @link    http://www.contao.org
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
 * * left_top:      the top left corner will be preserved
 * * center_top:    the top of the image will be preserved
 * * right_top:     the top right corner will be preserved
 * * left_center:   the left side of the image will be preserved
 * * center_center: the center of the image will be preserved
 * * right_center:  the right side of the image will be preserved
 * * left_bottom:   the bottom left corner will be preserved
 * * center_bottom: the bottom of the image will be preserved
 * * right_bottm:   the bottom right corner will be preserved
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
 * @copyright Leo Feyer 2011-2012
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
		return static::get($image, $width, $height, $mode, $image) ? true : false;
	}


	/**
	 * Resize an image and store the resized version in the assets/images folder
	 * 
	 * @param string  $image  The image path
	 * @param integer $width  The target width
	 * @param integer $height The target height
	 * @param string  $mode   The resize mode
	 * @param string  $target An optional target path
	 * 
	 * @return string|null The path of the resized image or null
	 */
	public static function get($image, $width, $height, $mode='', $target=null)
	{
		if ($image == '')
		{
			return null;
		}

		$image = rawurldecode($image);

		// Check whether the file exists
		if (!is_file(TL_ROOT . '/' . $image))
		{
			\System::log('Image "' . $image . '" could not be found', 'Controller getImage()', TL_ERROR);
			return null;
		}

		$objFile = new \File($image);
		$arrAllowedTypes = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['validImageTypes']));

		// Check the file type
		if (!in_array($objFile->extension, $arrAllowedTypes))
		{
			\System::log('Image type "' . $objFile->extension . '" was not allowed to be processed', 'Controller getImage()', TL_ERROR);
			return null;
		}

		// No resizing required
		if ($objFile->width == $width && $objFile->height == $height)
		{
			return \System::urlEncode($image);
		}

		// No mode given
		if ($mode == '')
		{
			$mode = 'proportional';
		}

		// Backwards compatibility
		if ($mode == 'crop')
		{
			$mode = 'center_center';
		}

		$strCacheKey = substr(md5('-w' . $width . '-h' . $height . '-' . $image . '-' . $mode . '-' . $objFile->mtime), 0, 8);
		$strCacheName = 'assets/images/' . substr($strCacheKey, -1) . '/' . $objFile->filename . '-' . $strCacheKey . '.' . $objFile->extension;

		// Return the path of the new image if it exists already
		if (!$GLOBALS['TL_CONFIG']['bypassCache'] && file_exists(TL_ROOT . '/' . $strCacheName))
		{
			return \System::urlEncode($strCacheName);
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
		if (!extension_loaded('gd') || !$objFile->isGdImage || $objFile->width > $GLOBALS['TL_CONFIG']['gdMaxImgWidth'] || $objFile->height > $GLOBALS['TL_CONFIG']['gdMaxImgHeight'] || (!$width && !$height) || $width > 1200 || $height > 1200)
		{
			return \System::urlEncode($image);
		}

		$intPositionX = 0;
		$intPositionY = 0;
		$intWidth = $width;
		$intHeight = $height;

		// Mode-specific changes
		if ($intWidth && $intHeight)
		{
			switch ($mode)
			{
				case 'proportional':
					if ($objFile->width >= $objFile->height)
					{
						unset($height, $intHeight);
					}
					else
					{
						unset($width, $intWidth);
					}
					break;

				case 'box':
					if (round($objFile->height * $width / $objFile->width) <= $intHeight)
					{
						unset($height, $intHeight);
					}
					else
					{
						unset($width, $intWidth);
					}
					break;
			}
		}

		// Resize width and height and crop the image if necessary
		if ($intWidth && $intHeight)
		{
			if (($intWidth * $objFile->height) != ($intHeight * $objFile->width))
			{
				$intWidth = max(round($objFile->width * $height / $objFile->height), 1);
				$intPositionX = -intval(($intWidth - $width) / 2);

				if ($intWidth < $width)
				{
					$intWidth = $width;
					$intHeight = max(round($objFile->height * $width / $objFile->width), 1);
					$intPositionX = 0;
					$intPositionY = -intval(($intHeight - $height) / 2);
				}
			}

			// Advanced crop modes
			switch ($mode)
			{
				case 'left_top':
					$intPositionX = 0;
					$intPositionY = 0;
					break;

				case 'center_top':
					$intPositionX = -intval(($intWidth - $width) / 2);
					$intPositionY = 0;
					break;

				case 'right_top':
					$intPositionX = -intval($intWidth - $width);
					$intPositionY = 0;
					break;

				case 'left_center':
					$intPositionX = 0;
					$intPositionY = -intval(($intHeight - $height) / 2);
					break;

				case 'center_center':
					$intPositionX = -intval(($intWidth - $width) / 2);
					$intPositionY = -intval(($intHeight - $height) / 2);
					break;

				case 'right_center':
					$intPositionX = -intval($intWidth - $width);
					$intPositionY = -intval(($intHeight - $height) / 2);
					break;

				case 'left_bottom':
					$intPositionX = 0;
					$intPositionY = -intval($intHeight - $height);
					break;

				case 'center_bottom':
					$intPositionX = -intval(($intWidth - $width) / 2);
					$intPositionY = -intval($intHeight - $height);
					break;

				case 'right_bottom':
					$intPositionX = -intval($intWidth - $width);
					$intPositionY = -intval($intHeight - $height);
					break;
			}

			$strNewImage = imagecreatetruecolor($width, $height);
		}

		// Calculate the height if only the width is given
		elseif ($intWidth)
		{
			$intHeight = max(round($objFile->height * $width / $objFile->width), 1);
			$strNewImage = imagecreatetruecolor($intWidth, $intHeight);
		}

		// Calculate the width if only the height is given
		elseif ($intHeight)
		{
			$intWidth = max(round($objFile->width * $height / $objFile->height), 1);
			$strNewImage = imagecreatetruecolor($intWidth, $intHeight);
		}

		$arrGdinfo = gd_info();
		$strGdVersion = preg_replace('/[^0-9\.]+/', '', $arrGdinfo['GD Version']);

		switch ($objFile->extension)
		{
			case 'gif':
				if ($arrGdinfo['GIF Read Support'])
				{
					$strSourceImage = imagecreatefromgif(TL_ROOT . '/' . $image);
					$intTranspIndex = imagecolortransparent($strSourceImage);

					// Handle transparency
					if ($intTranspIndex >= 0 && $intTranspIndex < imagecolorstotal($strSourceImage))
					{
						$arrColor = imagecolorsforindex($strSourceImage, $intTranspIndex);
						$intTranspIndex = imagecolorallocate($strNewImage, $arrColor['red'], $arrColor['green'], $arrColor['blue']);
						imagefill($strNewImage, 0, 0, $intTranspIndex);
						imagecolortransparent($strNewImage, $intTranspIndex);
					}
				}
				break;

			case 'jpg':
			case 'jpeg':
				if ($arrGdinfo['JPG Support'] || $arrGdinfo['JPEG Support'])
				{
					$strSourceImage = imagecreatefromjpeg(TL_ROOT . '/' . $image);
				}
				break;

			case 'png':
				if ($arrGdinfo['PNG Support'])
				{
					$strSourceImage = imagecreatefrompng(TL_ROOT . '/' . $image);

					// Handle transparency (GDlib >= 2.0 required)
					if (version_compare($strGdVersion, '2.0', '>='))
					{
						imagealphablending($strNewImage, false);
						$intTranspIndex = imagecolorallocatealpha($strNewImage, 0, 0, 0, 127);
						imagefill($strNewImage, 0, 0, $intTranspIndex);
						imagesavealpha($strNewImage, true);
					}
				}
				break;
		}

		// The new image could not be created
		if (!$strSourceImage)
		{
			imagedestroy($strNewImage);
			\System::log('Image "' . $image . '" could not be processed', 'Controller getImage()', TL_ERROR);
			return null;
		}

		imagecopyresampled($strNewImage, $strSourceImage, $intPositionX, $intPositionY, 0, 0, $intWidth, $intHeight, $objFile->width, $objFile->height);

		// Fallback to PNG if GIF ist not supported
		if ($objFile->extension == 'gif' && !$arrGdinfo['GIF Create Support'])
		{
			$objFile->extension = 'png';
		}

		// Create the new image
		switch ($objFile->extension)
		{
			case 'gif':
				imagegif($strNewImage, TL_ROOT . '/' . $strCacheName);
				break;

			case 'jpg':
			case 'jpeg':
				imagejpeg($strNewImage, TL_ROOT . '/' . $strCacheName, (!$GLOBALS['TL_CONFIG']['jpgQuality'] ? 80 : $GLOBALS['TL_CONFIG']['jpgQuality']));
				break;

			case 'png':
				// Optimize non-truecolor images (see #2426)
				if (version_compare($strGdVersion, '2.0', '>=') && function_exists('imagecolormatch') && !imageistruecolor($strSourceImage))
				{
					// TODO: make it work with transparent images, too
					if (imagecolortransparent($strSourceImage) == -1)
					{
						$intColors = imagecolorstotal($strSourceImage);

						// Convert to a palette image
						// @see http://www.php.net/manual/de/function.imagetruecolortopalette.php#44803
						if ($intColors > 0 && $intColors < 256)
						{
							$wi = imagesx($strNewImage);
							$he = imagesy($strNewImage);
							$ch = imagecreatetruecolor($wi, $he);
							imagecopymerge($ch, $strNewImage, 0, 0, 0, 0, $wi, $he, 100);
							imagetruecolortopalette($strNewImage, false, $intColors);
							imagecolormatch($ch, $strNewImage);
							imagedestroy($ch);
						}
					}
				}

				imagepng($strNewImage, TL_ROOT . '/' . $strCacheName);
				break;
		}

		// Destroy the temporary images
		imagedestroy($strSourceImage);
		imagedestroy($strNewImage);

		// Resize the original image
		if ($target)
		{
			\Files::getInstance()->rename($strCacheName, $target);
			return \System::urlEncode($target);
		}

		// Set the file permissions when the Safe Mode Hack is used
		if ($GLOBALS['TL_CONFIG']['useFTP'])
		{
			\Files::getInstance()->chmod($strCacheName, 0644);
		}

		// Return the path to new image
		return \System::urlEncode($strCacheName);
	}
}
