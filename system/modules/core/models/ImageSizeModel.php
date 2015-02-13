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
 * Reads and writes image sizes
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $tstamp
 * @property string  $name
 * @property string  $sizes
 * @property string  $densities
 * @property integer $width
 * @property integer $height
 * @property string  $resizeMode
 * @property integer $zoom
 *
 * @method static $this findById()
 * @method static $this findByPk()
 * @method static $this findByIdOrAlias()
 * @method static $this findOneBy()
 * @method static $this findOneByPid()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByName()
 * @method static $this findOneBySizes()
 * @method static $this findOneByDensities()
 * @method static $this findOneByWidth()
 * @method static $this findOneByHeight()
 * @method static $this findOneByResizeMode()
 * @method static $this findOneByZoom()
 *
 * @method static \Model\Collection|\ImageSizeModel findByPid()
 * @method static \Model\Collection|\ImageSizeModel findByTstamp()
 * @method static \Model\Collection|\ImageSizeModel findByName()
 * @method static \Model\Collection|\ImageSizeModel findBySizes()
 * @method static \Model\Collection|\ImageSizeModel findByDensities()
 * @method static \Model\Collection|\ImageSizeModel findByWidth()
 * @method static \Model\Collection|\ImageSizeModel findByHeight()
 * @method static \Model\Collection|\ImageSizeModel findByResizeMode()
 * @method static \Model\Collection|\ImageSizeModel findByZoom()
 * @method static \Model\Collection|\ImageSizeModel findMultipleByIds()
 * @method static \Model\Collection|\ImageSizeModel findBy()
 * @method static \Model\Collection|\ImageSizeModel findAll()
 *
 * @method static integer countById()
 * @method static integer countByPid()
 * @method static integer countByTstamp()
 * @method static integer countByName()
 * @method static integer countBySizes()
 * @method static integer countByDensities()
 * @method static integer countByWidth()
 * @method static integer countByHeight()
 * @method static integer countByResizeMode()
 * @method static integer countByZoom()
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ImageSizeModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_image_size';

}
