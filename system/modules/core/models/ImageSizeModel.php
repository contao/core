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
 * @method static $this findOneByPid()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByName()
 * @method static $this findOneBySizes()
 * @method static $this findOneByDensities()
 * @method static $this findOneByWidth()
 * @method static $this findOneByHeight()
 * @method static $this findOneByResizeMode()
 * @method static $this findOneByZoom()
 * @method static \ImageSizeModel[]|\Model\Collection findByPid()
 * @method static \ImageSizeModel[]|\Model\Collection findByTstamp()
 * @method static \ImageSizeModel[]|\Model\Collection findByName()
 * @method static \ImageSizeModel[]|\Model\Collection findBySizes()
 * @method static \ImageSizeModel[]|\Model\Collection findByDensities()
 * @method static \ImageSizeModel[]|\Model\Collection findByWidth()
 * @method static \ImageSizeModel[]|\Model\Collection findByHeight()
 * @method static \ImageSizeModel[]|\Model\Collection findByResizeMode()
 * @method static \ImageSizeModel[]|\Model\Collection findByZoom()
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
