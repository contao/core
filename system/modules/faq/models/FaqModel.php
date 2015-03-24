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
 * @method static $this findById()
 * @method static $this findByPk()
 * @method static $this findByIdOrAlias()
 * @method static $this findOneBy()
 * @method static $this findOneByPid()
 * @method static $this findOneBySorting()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByQuestion()
 * @method static $this findOneByAlias()
 * @method static $this findOneByAuthor()
 * @method static $this findOneByAnswer()
 * @method static $this findOneByAddImage()
 * @method static $this findOneBySingleSRC()
 * @method static $this findOneByAlt()
 * @method static $this findOneBySize()
 * @method static $this findOneByImagemargin()
 * @method static $this findOneByImageUrl()
 * @method static $this findOneByFullsize()
 * @method static $this findOneByCaption()
 * @method static $this findOneByFloating()
 * @method static $this findOneByAddEnclosure()
 * @method static $this findOneByEnclosure()
 * @method static $this findOneByNoComments()
 * @method static $this findOneByPublished()
 *
 * @method static \Model\Collection|\FaqModel findByPid()
 * @method static \Model\Collection|\FaqModel findBySorting()
 * @method static \Model\Collection|\FaqModel findByTstamp()
 * @method static \Model\Collection|\FaqModel findByQuestion()
 * @method static \Model\Collection|\FaqModel findByAlias()
 * @method static \Model\Collection|\FaqModel findByAuthor()
 * @method static \Model\Collection|\FaqModel findByAnswer()
 * @method static \Model\Collection|\FaqModel findByAddImage()
 * @method static \Model\Collection|\FaqModel findBySingleSRC()
 * @method static \Model\Collection|\FaqModel findByAlt()
 * @method static \Model\Collection|\FaqModel findBySize()
 * @method static \Model\Collection|\FaqModel findByImagemargin()
 * @method static \Model\Collection|\FaqModel findByImageUrl()
 * @method static \Model\Collection|\FaqModel findByFullsize()
 * @method static \Model\Collection|\FaqModel findByCaption()
 * @method static \Model\Collection|\FaqModel findByFloating()
 * @method static \Model\Collection|\FaqModel findByAddEnclosure()
 * @method static \Model\Collection|\FaqModel findByEnclosure()
 * @method static \Model\Collection|\FaqModel findByNoComments()
 * @method static \Model\Collection|\FaqModel findByPublished()
 * @method static \Model\Collection|\FaqModel findMultipleByIds()
 * @method static \Model\Collection|\FaqModel findBy()
 * @method static \Model\Collection|\FaqModel findAll()
 *
 * @method static integer countById()
 * @method static integer countByPid()
 * @method static integer countBySorting()
 * @method static integer countByTstamp()
 * @method static integer countByQuestion()
 * @method static integer countByAlias()
 * @method static integer countByAuthor()
 * @method static integer countByAnswer()
 * @method static integer countByAddImage()
 * @method static integer countBySingleSRC()
 * @method static integer countByAlt()
 * @method static integer countBySize()
 * @method static integer countByImagemargin()
 * @method static integer countByImageUrl()
 * @method static integer countByFullsize()
 * @method static integer countByCaption()
 * @method static integer countByFloating()
 * @method static integer countByAddEnclosure()
 * @method static integer countByEnclosure()
 * @method static integer countByNoComments()
 * @method static integer countByPublished()
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
