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
 * Reads and writes FAQs
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $sorting
 * @property integer $tstamp
 * @property string  $question
 * @property string  $alias
 * @property integer $author
 * @property string  $answer
 * @property boolean $addImage
 * @property string  $singleSRC
 * @property string  $alt
 * @property string  $size
 * @property string  $imagemargin
 * @property string  $imageUrl
 * @property boolean $fullsize
 * @property string  $caption
 * @property string  $floating
 * @property boolean $addEnclosure
 * @property string  $enclosure
 * @property boolean $noComments
 * @property boolean $published
 *
 * @method static $this findById($id, $opt=array())
 * @method static $this findByPk($id, $opt=array())
 * @method static $this findByIdOrAlias($val, $opt=array())
 * @method static $this findOneBy($col, $val, $opt=array())
 * @method static $this findOneByPid($val, $opt=array())
 * @method static $this findOneBySorting($val, $opt=array())
 * @method static $this findOneByTstamp($val, $opt=array())
 * @method static $this findOneByQuestion($val, $opt=array())
 * @method static $this findOneByAlias($val, $opt=array())
 * @method static $this findOneByAuthor($val, $opt=array())
 * @method static $this findOneByAnswer($val, $opt=array())
 * @method static $this findOneByAddImage($val, $opt=array())
 * @method static $this findOneBySingleSRC($val, $opt=array())
 * @method static $this findOneByAlt($val, $opt=array())
 * @method static $this findOneBySize($val, $opt=array())
 * @method static $this findOneByImagemargin($val, $opt=array())
 * @method static $this findOneByImageUrl($val, $opt=array())
 * @method static $this findOneByFullsize($val, $opt=array())
 * @method static $this findOneByCaption($val, $opt=array())
 * @method static $this findOneByFloating($val, $opt=array())
 * @method static $this findOneByAddEnclosure($val, $opt=array())
 * @method static $this findOneByEnclosure($val, $opt=array())
 * @method static $this findOneByNoComments($val, $opt=array())
 * @method static $this findOneByPublished($val, $opt=array())
 *
 * @method static \Model\Collection|\FaqModel findByPid($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findBySorting($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findByQuestion($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findByAlias($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findByAuthor($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findByAnswer($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findByAddImage($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findBySingleSRC($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findByAlt($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findBySize($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findByImagemargin($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findByImageUrl($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findByFullsize($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findByCaption($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findByFloating($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findByAddEnclosure($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findByEnclosure($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findByNoComments($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findByPublished($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\FaqModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\FaqModel findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByPid($val, $opt=array())
 * @method static integer countBySorting($val, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByQuestion($val, $opt=array())
 * @method static integer countByAlias($val, $opt=array())
 * @method static integer countByAuthor($val, $opt=array())
 * @method static integer countByAnswer($val, $opt=array())
 * @method static integer countByAddImage($val, $opt=array())
 * @method static integer countBySingleSRC($val, $opt=array())
 * @method static integer countByAlt($val, $opt=array())
 * @method static integer countBySize($val, $opt=array())
 * @method static integer countByImagemargin($val, $opt=array())
 * @method static integer countByImageUrl($val, $opt=array())
 * @method static integer countByFullsize($val, $opt=array())
 * @method static integer countByCaption($val, $opt=array())
 * @method static integer countByFloating($val, $opt=array())
 * @method static integer countByAddEnclosure($val, $opt=array())
 * @method static integer countByEnclosure($val, $opt=array())
 * @method static integer countByNoComments($val, $opt=array())
 * @method static integer countByPublished($val, $opt=array())
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FaqModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_faq';


	/**
	 * Find a published FAQ from one or more categories by its ID or alias
	 *
	 * @param mixed $varId      The numeric ID or alias name
	 * @param array $arrPids    An array of parent IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return static The FaqModel or null if there is no FAQ
	 */
	public static function findPublishedByParentAndIdOrAlias($varId, $arrPids, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("($t.id=? OR $t.alias=?) AND pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.published='1'";
		}

		return static::findOneBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId), $arrOptions);
	}


	/**
	 * Find all published FAQs by their parent ID
	 *
	 * @param int   $intPid     The parent ID
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\FaqModel|null A collection of models or null if there are no FAQs
	 */
	public static function findPublishedByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=?");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.published='1'";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting";
		}

		return static::findBy($arrColumns, $intPid, $arrOptions);
	}


	/**
	 * Find all published FAQs by their parent IDs
	 *
	 * @param array $arrPids    An array of FAQ category IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\FaqModel|null A collection of models or null if there are no FAQs
	 */
	public static function findPublishedByPids($arrPids, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.published='1'";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.pid, $t.sorting";
		}

		return static::findBy($arrColumns, null, $arrOptions);
	}
}
