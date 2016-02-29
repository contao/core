<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
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
 * @method static \ImageSizeModel|null findById($id, $opt=array())
 * @method static \ImageSizeModel|null findByPk($id, $opt=array())
 * @method static \ImageSizeModel|null findByIdOrAlias($val, $opt=array())
 * @method static \ImageSizeModel|null findOneBy($col, $val, $opt=array())
 * @method static \ImageSizeModel|null findOneByPid($val, $opt=array())
 * @method static \ImageSizeModel|null findOneByTstamp($val, $opt=array())
 * @method static \ImageSizeModel|null findOneByName($val, $opt=array())
 * @method static \ImageSizeModel|null findOneBySizes($val, $opt=array())
 * @method static \ImageSizeModel|null findOneByDensities($val, $opt=array())
 * @method static \ImageSizeModel|null findOneByWidth($val, $opt=array())
 * @method static \ImageSizeModel|null findOneByHeight($val, $opt=array())
 * @method static \ImageSizeModel|null findOneByResizeMode($val, $opt=array())
 * @method static \ImageSizeModel|null findOneByZoom($val, $opt=array())
 *
 * @method static \Model\Collection|\ImageSizeModel[]|\ImageSizeModel|null findByPid($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel[]|\ImageSizeModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel[]|\ImageSizeModel|null findByName($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel[]|\ImageSizeModel|null findBySizes($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel[]|\ImageSizeModel|null findByDensities($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel[]|\ImageSizeModel|null findByWidth($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel[]|\ImageSizeModel|null findByHeight($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel[]|\ImageSizeModel|null findByResizeMode($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel[]|\ImageSizeModel|null findByZoom($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel[]|\ImageSizeModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel[]|\ImageSizeModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\ImageSizeModel[]|\ImageSizeModel|null findAll($opt=array())
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
