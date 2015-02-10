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
 * @method static $this findById()
 * @method static $this findOneByPid()
 * @method static $this findOneBySorting()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByMedia()
 * @method static $this findOneBySizes()
 * @method static $this findOneByDensities()
 * @method static $this findOneByWidth()
 * @method static $this findOneByHeight()
 * @method static $this findOneByResizeMode()
 * @method static $this findOneByZoom()
 * @method static $this findOneByInvisible()
 * @method static \Model\Collection findByPid()
 * @method static \Model\Collection findBySorting()
 * @method static \Model\Collection findByTstamp()
 * @method static \Model\Collection findByMedia()
 * @method static \Model\Collection findBySizes()
 * @method static \Model\Collection findByDensities()
 * @method static \Model\Collection findByWidth()
 * @method static \Model\Collection findByHeight()
 * @method static \Model\Collection findByResizeMode()
 * @method static \Model\Collection findByZoom()
 * @method static \Model\Collection findByInvisible()
 * @method static integer countById()
 * @method static integer countByPid()
 * @method static integer countBySorting()
 * @method static integer countByTstamp()
 * @method static integer countByMedia()
 * @method static integer countBySizes()
 * @method static integer countByDensities()
 * @method static integer countByWidth()
 * @method static integer countByHeight()
 * @method static integer countByResizeMode()
 * @method static integer countByZoom()
 * @method static integer countByInvisible()
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
	 * @return \Model\Collection|null A collection of models or null if there are no items
	 */
	public static function findVisibleByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;

		return static::findBy(array("$t.pid=? AND $t.invisible=''"), intval($intPid), $arrOptions);
	}
}
