<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Core
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class StyleSheetModel
 *
 * Provide methods to find and save style sheets.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Core
 */
class StyleSheetModel extends \Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_style_sheet';


	/**
	 * Find multiple style sheets by their IDs
	 * @param array
	 * @return \Model_Collection|null
	 */
	public static function findByIds($arrIds)
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$objDatabase = \Database::getInstance();
		$arrIds = array_map('intval', $arrIds);

		$objResult = $objDatabase->execute("SELECT *, (SELECT MAX(tstamp) FROM tl_style WHERE tl_style.pid=tl_style_sheet.id) AS tstamp2, (SELECT COUNT(*) FROM tl_style WHERE tl_style.selector='@font-face' AND tl_style.pid=tl_style_sheet.id) AS hasFontFace FROM tl_style_sheet WHERE id IN (" . implode(',', $arrIds) . ") ORDER BY " . $objDatabase->findInSet('id', $arrIds));
		return new \Model_Collection($objResult, 'tl_style_sheet');
	}
}
