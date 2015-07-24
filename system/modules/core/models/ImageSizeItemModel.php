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
 * @method static $this findById($id, $opt=array())
 * @method static $this findByPk($id, $opt=array())
 * @method static $this findByIdOrAlias($val, $opt=array())
 * @method static $this findOneBy($col, $val, $opt=array())
 * @method static $this findOneByPid($val, $opt=array())
 * @method static $this findOneBySorting($val, $opt=array())
 * @method static $this findOneByTstamp($val, $opt=array())
 * @method static $this findOneByMedia($val, $opt=array())
 * @method static $this findOneBySizes($val, $opt=array())
 * @method static $this findOneByDensities($val, $opt=array())
 * @method static $this findOneByWidth($val, $opt=array())
 * @method static $this findOneByHeight($val, $opt=array())
 * @method static $this findOneByResizeMode($val, $opt=array())
 * @method static $this findOneByZoom($val, $opt=array())
 * @method static $this findOneByInvisible($val, $opt=array())
 *
 * @method static \Model\Collection|\ImageSizeItemModel findByPid($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel findBySorting($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel findByMedia($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel findBySizes($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel findByDensities($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel findByWidth($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel findByHeight($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel findByResizeMode($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel findByZoom($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel findByInvisible($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\ImageSizeItemModel findAll($opt=array())
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
	 * @return \Model\Collection|\ImageSizeItemModel|null A collection of models or null if there are no items
	 */
	public static function findVisibleByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;

		return static::findBy(array("$t.pid=? AND $t.invisible=''"), intval($intPid), $arrOptions);
	}
}
