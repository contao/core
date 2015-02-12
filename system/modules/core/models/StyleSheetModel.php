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
 * @method static $this findById()
 * @method static $this findByPk()
 * @method static $this findByIdOrAlias()
 * @method static $this findOneBy()
 * @method static $this findByName()
 * @method static $this findOneByPid()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByDisablePie()
 * @method static $this findOneByEmbedImages()
 * @method static $this findOneByCc()
 * @method static $this findOneByMedia()
 * @method static $this findOneByMediaQuery()
 * @method static $this findOneByVars()
 *
 * @method static \Model\Collection|\StyleSheetModel findByPid()
 * @method static \Model\Collection|\StyleSheetModel findByTstamp()
 * @method static \Model\Collection|\StyleSheetModel findByDisablePie()
 * @method static \Model\Collection|\StyleSheetModel findByEmbedImages()
 * @method static \Model\Collection|\StyleSheetModel findByCc()
 * @method static \Model\Collection|\StyleSheetModel findByMedia()
 * @method static \Model\Collection|\StyleSheetModel findByMediaQuery()
 * @method static \Model\Collection|\StyleSheetModel findByVars()
 * @method static \Model\Collection|\StyleSheetModel findMultipleByIds()
 * @method static \Model\Collection|\StyleSheetModel findBy()
 * @method static \Model\Collection|\StyleSheetModel findAll()
 *
 * @method static integer countById()
 * @method static integer countByPid()
 * @method static integer countByTstamp()
 * @method static integer countByName()
 * @method static integer countByDisablePie()
 * @method static integer countByEmbedImages()
 * @method static integer countByCc()
 * @method static integer countByMedia()
 * @method static integer countByMediaQuery()
 * @method static integer countByVars()
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
