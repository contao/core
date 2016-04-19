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
 * @method static \FaqModel|null findById($id, $opt=array())
 * @method static \FaqModel|null findByPk($id, $opt=array())
 * @method static \FaqModel|null findByIdOrAlias($val, $opt=array())
 * @method static \FaqModel|null findOneBy($col, $val, $opt=array())
 * @method static \FaqModel|null findOneByPid($val, $opt=array())
 * @method static \FaqModel|null findOneBySorting($val, $opt=array())
 * @method static \FaqModel|null findOneByTstamp($val, $opt=array())
 * @method static \FaqModel|null findOneByQuestion($val, $opt=array())
 * @method static \FaqModel|null findOneByAlias($val, $opt=array())
 * @method static \FaqModel|null findOneByAuthor($val, $opt=array())
 * @method static \FaqModel|null findOneByAnswer($val, $opt=array())
 * @method static \FaqModel|null findOneByAddImage($val, $opt=array())
 * @method static \FaqModel|null findOneBySingleSRC($val, $opt=array())
 * @method static \FaqModel|null findOneByAlt($val, $opt=array())
 * @method static \FaqModel|null findOneBySize($val, $opt=array())
 * @method static \FaqModel|null findOneByImagemargin($val, $opt=array())
 * @method static \FaqModel|null findOneByImageUrl($val, $opt=array())
 * @method static \FaqModel|null findOneByFullsize($val, $opt=array())
 * @method static \FaqModel|null findOneByCaption($val, $opt=array())
 * @method static \FaqModel|null findOneByFloating($val, $opt=array())
 * @method static \FaqModel|null findOneByAddEnclosure($val, $opt=array())
 * @method static \FaqModel|null findOneByEnclosure($val, $opt=array())
 * @method static \FaqModel|null findOneByNoComments($val, $opt=array())
 * @method static \FaqModel|null findOneByPublished($val, $opt=array())
 *
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findByPid($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findBySorting($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findByQuestion($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findByAlias($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findByAuthor($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findByAnswer($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findByAddImage($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findBySingleSRC($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findByAlt($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findBySize($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findByImagemargin($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findByImageUrl($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findByFullsize($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findByCaption($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findByFloating($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findByAddEnclosure($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findByEnclosure($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findByNoComments($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findByPublished($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\FaqModel[]|\FaqModel|null findAll($opt=array())
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
	 * @return \FaqModel|null The model or null if there is no FAQ
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
	 * @return \Model\Collection|\FaqModel[]|\FaqModel|null A collection of models or null if there are no FAQs
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
	 * @return \Model\Collection|\FaqModel[]|\FaqModel|null A collection of models or null if there are no FAQs
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
