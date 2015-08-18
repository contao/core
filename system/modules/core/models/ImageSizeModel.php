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
 * @method static \ImageSizeModel findById($id, $opt=array())
 * @method static \ImageSizeModel findByPk($id, $opt=array())
 * @method static \ImageSizeModel findByIdOrAlias($val, $opt=array())
 * @method static \ImageSizeModel findOneBy($col, $val, $opt=array())
 * @method static \ImageSizeModel findOneByPid($val, $opt=array())
 * @method static \ImageSizeModel findOneByTstamp($val, $opt=array())
 * @method static \ImageSizeModel findOneByName($val, $opt=array())
 * @method static \ImageSizeModel findOneBySizes($val, $opt=array())
 * @method static \ImageSizeModel findOneByDensities($val, $opt=array())
 * @method static \ImageSizeModel findOneByWidth($val, $opt=array())
 * @method static \ImageSizeModel findOneByHeight($val, $opt=array())
 * @method static \ImageSizeModel findOneByResizeMode($val, $opt=array())
 * @method static \ImageSizeModel findOneByZoom($val, $opt=array())
 *
 * @method static \Model\Collection|\ImageSizeModel findByPid($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel findByName($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel findBySizes($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel findByDensities($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel findByWidth($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel findByHeight($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel findByResizeMode($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel findByZoom($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByPid($val, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByName($val, $opt=array())
 * @method static integer countBySizes($val, $opt=array())
 * @method static integer countByDensities($val, $opt=array())
 * @method static integer countByWidth($val, $opt=array())
 * @method static integer countByHeight($val, $opt=array())
 * @method static integer countByResizeMode($val, $opt=array())
 * @method static integer countByZoom($val, $opt=array())
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
