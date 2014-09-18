<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Reads and writes image size items
 *
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2014
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
	 * @param integer $pid        Parent ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no items
	 */
	public static function findVisibleByPid($pid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array(
			"$t.pid = " . intval($pid),
			"$t.invisible = ''",
		);

		return static::findBy($arrColumns, null, $arrOptions);
	}
}
