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
 * Reads and writes style sheets
 *
 * The class reads from and writes to the style sheet table. It does not create
 * .css files on the hard disk. This is done by the StyleSheet class.
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $tstamp
 * @property string  $name
 * @property boolean $disablePie
 * @property integer $embedImages
 * @property string  $cc
 * @property string  $media
 * @property string  $mediaQuery
 * @property string  $vars
 * @property string  $type
 * @property boolean $hasFontFace
 * @property string  $singleSRC
 * @property integer $tstamp2
 * @property integer $tstamp3
 *
 * @method static $this findById($id, $opt=array())
 * @method static $this findByPk($id, $opt=array())
 * @method static $this findByIdOrAlias($val, $opt=array())
 * @method static $this findOneBy($col, $val, $opt=array())
 * @method static $this findByName($val, $opt=array())
 * @method static $this findOneByPid($val, $opt=array())
 * @method static $this findOneByTstamp($val, $opt=array())
 * @method static $this findOneByDisablePie($val, $opt=array())
 * @method static $this findOneByEmbedImages($val, $opt=array())
 * @method static $this findOneByCc($val, $opt=array())
 * @method static $this findOneByMedia($val, $opt=array())
 * @method static $this findOneByMediaQuery($val, $opt=array())
 * @method static $this findOneByVars($val, $opt=array())
 *
 * @method static \Model\Collection|\StyleSheetModel findByPid($val, $opt=array())
 * @method static \Model\Collection|\StyleSheetModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\StyleSheetModel findByDisablePie($val, $opt=array())
 * @method static \Model\Collection|\StyleSheetModel findByEmbedImages($val, $opt=array())
 * @method static \Model\Collection|\StyleSheetModel findByCc($val, $opt=array())
 * @method static \Model\Collection|\StyleSheetModel findByMedia($val, $opt=array())
 * @method static \Model\Collection|\StyleSheetModel findByMediaQuery($val, $opt=array())
 * @method static \Model\Collection|\StyleSheetModel findByVars($val, $opt=array())
 * @method static \Model\Collection|\StyleSheetModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\StyleSheetModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\StyleSheetModel findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByPid($val, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByName($val, $opt=array())
 * @method static integer countByDisablePie($val, $opt=array())
 * @method static integer countByEmbedImages($val, $opt=array())
 * @method static integer countByCc($val, $opt=array())
 * @method static integer countByMedia($val, $opt=array())
 * @method static integer countByMediaQuery($val, $opt=array())
 * @method static integer countByVars($val, $opt=array())
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class StyleSheetModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_style_sheet';


	/**
	 * Find multiple style sheets by their IDs
	 *
	 * @param array $arrIds An array of style sheet IDs
	 *
	 * @return \Model\Collection|\StyleSheetModel|null A collection of models or null if there are no style sheets
	 */
	public static function findByIds($arrIds)
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$objDatabase = \Database::getInstance();
		$arrIds = array_map('intval', $arrIds);

		$objResult = $objDatabase->execute("SELECT *, (SELECT tstamp FROM tl_theme WHERE tl_theme.id=tl_style_sheet.pid) AS tstamp3, (SELECT MAX(tstamp) FROM tl_style WHERE tl_style.pid=tl_style_sheet.id) AS tstamp2, (SELECT COUNT(*) FROM tl_style WHERE tl_style.selector='@font-face' AND tl_style.invisible='' AND tl_style.pid=tl_style_sheet.id) AS hasFontFace FROM tl_style_sheet WHERE id IN (" . implode(',', $arrIds) . ") ORDER BY " . $objDatabase->findInSet('id', $arrIds));

		return static::createCollectionFromDbResult($objResult, 'tl_style_sheet');
	}
}
