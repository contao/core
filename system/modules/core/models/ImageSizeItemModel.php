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
