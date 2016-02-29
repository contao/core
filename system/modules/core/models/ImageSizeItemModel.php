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
 * Reads and writes image size items
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $sorting
 * @property integer $tstamp
 * @property string  $media
 * @property string  $sizes
 * @property string  $densities
 * @property integer $width
 * @property integer $height
 * @property string  $resizeMode
 * @property integer $zoom
 * @property boolean $invisible
 *
 * @method static \ImageSizeItemModel|null findById($id, $opt=array())
 * @method static \ImageSizeItemModel|null findByPk($id, $opt=array())
 * @method static \ImageSizeItemModel|null findByIdOrAlias($val, $opt=array())
 * @method static \ImageSizeItemModel|null findOneBy($col, $val, $opt=array())
 * @method static \ImageSizeItemModel|null findOneByPid($val, $opt=array())
 * @method static \ImageSizeItemModel|null findOneBySorting($val, $opt=array())
 * @method static \ImageSizeItemModel|null findOneByTstamp($val, $opt=array())
 * @method static \ImageSizeItemModel|null findOneByMedia($val, $opt=array())
 * @method static \ImageSizeItemModel|null findOneBySizes($val, $opt=array())
 * @method static \ImageSizeItemModel|null findOneByDensities($val, $opt=array())
 * @method static \ImageSizeItemModel|null findOneByWidth($val, $opt=array())
 * @method static \ImageSizeItemModel|null findOneByHeight($val, $opt=array())
 * @method static \ImageSizeItemModel|null findOneByResizeMode($val, $opt=array())
 * @method static \ImageSizeItemModel|null findOneByZoom($val, $opt=array())
 * @method static \ImageSizeItemModel|null findOneByInvisible($val, $opt=array())
 *
 * @method static \Model\Collection|\ImageSizeItemModel[]|\ImageSizeItemModel|null findByPid($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel[]|\ImageSizeItemModel|null findBySorting($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel[]|\ImageSizeItemModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel[]|\ImageSizeItemModel|null findByMedia($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel[]|\ImageSizeItemModel|null findBySizes($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel[]|\ImageSizeItemModel|null findByDensities($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel[]|\ImageSizeItemModel|null findByWidth($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel[]|\ImageSizeItemModel|null findByHeight($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel[]|\ImageSizeItemModel|null findByResizeMode($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel[]|\ImageSizeItemModel|null findByZoom($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel[]|\ImageSizeItemModel|null findByInvisible($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel[]|\ImageSizeItemModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel[]|\ImageSizeItemModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel[]|\ImageSizeItemModel|null findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByPid($val, $opt=array())
 * @method static integer countBySorting($val, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByMedia($val, $opt=array())
 * @method static integer countBySizes($val, $opt=array())
 * @method static integer countByDensities($val, $opt=array())
 * @method static integer countByWidth($val, $opt=array())
 * @method static integer countByHeight($val, $opt=array())
 * @method static integer countByResizeMode($val, $opt=array())
 * @method static integer countByZoom($val, $opt=array())
 * @method static integer countByInvisible($val, $opt=array())
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ImageSizeItemModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_image_size_item';


	/**
	 * Find visible image size items by their parent ID
	 *
	 * @param integer $intPid     Parent ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\ImageSizeItemModel[]|\ImageSizeItemModel|null A collection of models or null if there are no items
	 */
	public static function findVisibleByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;

		return static::findBy(array("$t.pid=? AND $t.invisible=''"), intval($intPid), $arrOptions);
	}
}
