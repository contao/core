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
 * Reads and writes content elements
 *
 * @property integer $id
 * @property integer $pid
 * @property string  $ptable
 * @property integer $sorting
 * @property integer $tstamp
 * @property string  $type
 * @property string  $headline
 * @property string  $text
 * @property boolean $addImage
 * @property string  $singleSRC
 * @property string  $alt
 * @property string  $title
 * @property string  $size
 * @property string  $imagemargin
 * @property string  $imageUrl
 * @property boolean $fullsize
 * @property string  $caption
 * @property string  $floating
 * @property string  $html
 * @property string  $listtype
 * @property string  $listitems
 * @property string  $tableitems
 * @property string  $summary
 * @property boolean $thead
 * @property boolean $tfoot
 * @property boolean $tleft
 * @property boolean $sortable
 * @property integer $sortIndex
 * @property string  $sortOrder
 * @property string  $mooHeadline
 * @property string  $mooStyle
 * @property string  $mooClasses
 * @property string  $highlight
 * @property string  $shClass
 * @property string  $code
 * @property string  $url
 * @property boolean $target
 * @property string  $titleText
 * @property string  $linkTitle
 * @property string  $embed
 * @property string  $rel
 * @property boolean $useImage
 * @property string  $multiSRC
 * @property string  $orderSRC
 * @property boolean $useHomeDir
 * @property integer $perRow
 * @property integer $perPage
 * @property integer $numberOfItems
 * @property string  $sortBy
 * @property boolean $metaIgnore
 * @property string  $galleryTpl
 * @property string  $customTpl
 * @property string  $playerSRC
 * @property string  $youtube
 * @property string  $posterSRC
 * @property string  $playerSize
 * @property boolean $autoplay
 * @property integer $sliderDelay
 * @property integer $sliderSpeed
 * @property integer $sliderStartSlide
 * @property boolean $sliderContinuous
 * @property integer $cteAlias
 * @property integer $articleAlias
 * @property integer $article
 * @property integer $form
 * @property integer $module
 * @property boolean $protected
 * @property string  $groups
 * @property boolean $guests
 * @property string  $cssID
 * @property string  $space
 * @property boolean $invisible
 * @property string  $start
 * @property string  $stop
 * @property string  $com_order
 * @property integer $com_perPage
 * @property boolean $com_moderate
 * @property boolean $com_bbcode
 * @property boolean $com_disableCaptcha
 * @property boolean $com_requireLogin
 * @property string  $com_template
 * @property string  $typePrefix
 * @property string  $classes
 * @property integer $origId
 *
 * @method static \ContentModel|null findById($id, $opt=array())
 * @method static \ContentModel|null findByPk($id, $opt=array())
 * @method static \ContentModel|null findByIdOrAlias($val, $opt=array())
 * @method static \ContentModel|null findOneBy($col, $val, $opt=array())
 * @method static \ContentModel|null findOneByPid($val, $opt=array())
 * @method static \ContentModel|null findOneByPtable($val, $opt=array())
 * @method static \ContentModel|null findOneBySorting($val, $opt=array())
 * @method static \ContentModel|null findOneByTstamp($val, $opt=array())
 * @method static \ContentModel|null findOneByType($val, $opt=array())
 * @method static \ContentModel|null findOneByHeadline($val, $opt=array())
 * @method static \ContentModel|null findOneByText($val, $opt=array())
 * @method static \ContentModel|null findOneByAddImage($val, $opt=array())
 * @method static \ContentModel|null findOneBySingleSRC($val, $opt=array())
 * @method static \ContentModel|null findOneByAlt($val, $opt=array())
 * @method static \ContentModel|null findOneByTitle($val, $opt=array())
 * @method static \ContentModel|null findOneBySize($val, $opt=array())
 * @method static \ContentModel|null findOneByImagemargin($val, $opt=array())
 * @method static \ContentModel|null findOneByImageUrl($val, $opt=array())
 * @method static \ContentModel|null findOneByFullsize($val, $opt=array())
 * @method static \ContentModel|null findOneByCaption($val, $opt=array())
 * @method static \ContentModel|null findOneByFloating($val, $opt=array())
 * @method static \ContentModel|null findOneByHtml($val, $opt=array())
 * @method static \ContentModel|null findOneByListtype($val, $opt=array())
 * @method static \ContentModel|null findOneByListitems($val, $opt=array())
 * @method static \ContentModel|null findOneByTableitems($val, $opt=array())
 * @method static \ContentModel|null findOneBySummary($val, $opt=array())
 * @method static \ContentModel|null findOneByThead($val, $opt=array())
 * @method static \ContentModel|null findOneByTfoot($val, $opt=array())
 * @method static \ContentModel|null findOneByTleft($val, $opt=array())
 * @method static \ContentModel|null findOneBySortable($val, $opt=array())
 * @method static \ContentModel|null findOneBySortIndex($val, $opt=array())
 * @method static \ContentModel|null findOneBySortOrder($val, $opt=array())
 * @method static \ContentModel|null findOneByMooHeadline($val, $opt=array())
 * @method static \ContentModel|null findOneByMooStyle($val, $opt=array())
 * @method static \ContentModel|null findOneByMooClasses($val, $opt=array())
 * @method static \ContentModel|null findOneByHighlight($val, $opt=array())
 * @method static \ContentModel|null findOneByShClass($val, $opt=array())
 * @method static \ContentModel|null findOneByCode($val, $opt=array())
 * @method static \ContentModel|null findOneByUrl($val, $opt=array())
 * @method static \ContentModel|null findOneByTarget($val, $opt=array())
 * @method static \ContentModel|null findOneByTitleText($val, $opt=array())
 * @method static \ContentModel|null findOneByLinkTitle($val, $opt=array())
 * @method static \ContentModel|null findOneByEmbed($val, $opt=array())
 * @method static \ContentModel|null findOneByRel($val, $opt=array())
 * @method static \ContentModel|null findOneByUseImage($val, $opt=array())
 * @method static \ContentModel|null findOneByMultiSRC($val, $opt=array())
 * @method static \ContentModel|null findOneByOrderSRC($val, $opt=array())
 * @method static \ContentModel|null findOneByUseHomeDir($val, $opt=array())
 * @method static \ContentModel|null findOneByPerRow($val, $opt=array())
 * @method static \ContentModel|null findOneByPerPage($val, $opt=array())
 * @method static \ContentModel|null findOneByNumberOfItems($val, $opt=array())
 * @method static \ContentModel|null findOneBySortBy($val, $opt=array())
 * @method static \ContentModel|null findOneByMetaIgnore($val, $opt=array())
 * @method static \ContentModel|null findOneByGalleryTpl($val, $opt=array())
 * @method static \ContentModel|null findOneByCustomTpl($val, $opt=array())
 * @method static \ContentModel|null findOneByPlayerSRC($val, $opt=array())
 * @method static \ContentModel|null findOneByYoutube($val, $opt=array())
 * @method static \ContentModel|null findOneByPosterSRC($val, $opt=array())
 * @method static \ContentModel|null findOneByPlayerSize($val, $opt=array())
 * @method static \ContentModel|null findOneByAutoplay($val, $opt=array())
 * @method static \ContentModel|null findOneBySliderDelay($val, $opt=array())
 * @method static \ContentModel|null findOneBySliderSpeed($val, $opt=array())
 * @method static \ContentModel|null findOneBySliderStartSlide($val, $opt=array())
 * @method static \ContentModel|null findOneBySliderContinuous($val, $opt=array())
 * @method static \ContentModel|null findOneByCteAlias($val, $opt=array())
 * @method static \ContentModel|null findOneByArticleAlias($val, $opt=array())
 * @method static \ContentModel|null findOneByArticle($val, $opt=array())
 * @method static \ContentModel|null findOneByForm($val, $opt=array())
 * @method static \ContentModel|null findOneByModule($val, $opt=array())
 * @method static \ContentModel|null findOneByProtected($val, $opt=array())
 * @method static \ContentModel|null findOneByGroups($val, $opt=array())
 * @method static \ContentModel|null findOneByGuests($val, $opt=array())
 * @method static \ContentModel|null findOneByCssID($val, $opt=array())
 * @method static \ContentModel|null findOneBySpace($val, $opt=array())
 * @method static \ContentModel|null findOneByInvisible($val, $opt=array())
 * @method static \ContentModel|null findOneByStart($val, $opt=array())
 * @method static \ContentModel|null findOneByStop($val, $opt=array())
 * @method static \ContentModel|null findOneByCom_order($val, $opt=array())
 * @method static \ContentModel|null findOneByCom_perPage($val, $opt=array())
 * @method static \ContentModel|null findOneByCom_moderate($val, $opt=array())
 * @method static \ContentModel|null findOneByCom_bbcode($val, $opt=array())
 * @method static \ContentModel|null findOneByCom_disableCaptcha($val, $opt=array())
 * @method static \ContentModel|null findOneByCom_requireLogin($val, $opt=array())
 * @method static \ContentModel|null findOneByCom_template($val, $opt=array())
 *
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByPid($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByPtable($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findBySorting($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByType($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByHeadline($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByText($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByAddImage($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findBySingleSRC($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByAlt($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByTitle($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findBySize($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByImagemargin($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByImageUrl($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByFullsize($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByCaption($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByFloating($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByHtml($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByListtype($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByListitems($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByTableitems($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findBySummary($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByThead($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByTfoot($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByTleft($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findBySortable($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findBySortIndex($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findBySortOrder($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByMooHeadline($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByMooStyle($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByMooClasses($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByHighlight($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByShClass($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByCode($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByUrl($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByTarget($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByTitleText($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByLinkTitle($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByEmbed($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByRel($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByUseImage($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByMultiSRC($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByOrderSRC($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByUseHomeDir($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByPerRow($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByPerPage($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByNumberOfItems($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findBySortBy($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByMetaIgnore($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByGalleryTpl($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByCustomTpl($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByPlayerSRC($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByYoutube($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByPosterSRC($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByPlayerSize($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByAutoplay($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findBySliderDelay($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findBySliderSpeed($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findBySliderStartSlide($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findBySliderContinuous($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByCteAlias($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByArticleAlias($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByArticle($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByForm($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByModule($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByProtected($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByGroups($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByGuests($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByCssID($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findBySpace($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByInvisible($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByStart($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByStop($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByCom_order($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByCom_perPage($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByCom_moderate($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByCom_bbcode($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByCom_disableCaptcha($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByCom_requireLogin($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findByCom_template($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\ContentModel[]|\ContentModel|null findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByPid($val, $opt=array())
 * @method static integer countByPtable($val, $opt=array())
 * @method static integer countBySorting($val, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByType($val, $opt=array())
 * @method static integer countByHeadline($val, $opt=array())
 * @method static integer countByText($val, $opt=array())
 * @method static integer countByAddImage($val, $opt=array())
 * @method static integer countBySingleSRC($val, $opt=array())
 * @method static integer countByAlt($val, $opt=array())
 * @method static integer countByTitle($val, $opt=array())
 * @method static integer countBySize($val, $opt=array())
 * @method static integer countByImagemargin($val, $opt=array())
 * @method static integer countByImageUrl($val, $opt=array())
 * @method static integer countByFullsize($val, $opt=array())
 * @method static integer countByCaption($val, $opt=array())
 * @method static integer countByFloating($val, $opt=array())
 * @method static integer countByHtml($val, $opt=array())
 * @method static integer countByListtype($val, $opt=array())
 * @method static integer countByListitems($val, $opt=array())
 * @method static integer countByTableitems($val, $opt=array())
 * @method static integer countBySummary($val, $opt=array())
 * @method static integer countByThead($val, $opt=array())
 * @method static integer countByTfoot($val, $opt=array())
 * @method static integer countByTleft($val, $opt=array())
 * @method static integer countBySortable($val, $opt=array())
 * @method static integer countBySortIndex($val, $opt=array())
 * @method static integer countBySortOrder($val, $opt=array())
 * @method static integer countByMooHeadline($val, $opt=array())
 * @method static integer countByMooStyle($val, $opt=array())
 * @method static integer countByMooClasses($val, $opt=array())
 * @method static integer countByHighlight($val, $opt=array())
 * @method static integer countByShClass($val, $opt=array())
 * @method static integer countByCode($val, $opt=array())
 * @method static integer countByUrl($val, $opt=array())
 * @method static integer countByTarget($val, $opt=array())
 * @method static integer countByTitleText($val, $opt=array())
 * @method static integer countByLinkTitle($val, $opt=array())
 * @method static integer countByEmbed($val, $opt=array())
 * @method static integer countByRel($val, $opt=array())
 * @method static integer countByUseImage($val, $opt=array())
 * @method static integer countByMultiSRC($val, $opt=array())
 * @method static integer countByOrderSRC($val, $opt=array())
 * @method static integer countByUseHomeDir($val, $opt=array())
 * @method static integer countByPerRow($val, $opt=array())
 * @method static integer countByPerPage($val, $opt=array())
 * @method static integer countByNumberOfItems($val, $opt=array())
 * @method static integer countBySortBy($val, $opt=array())
 * @method static integer countByMetaIgnore($val, $opt=array())
 * @method static integer countByGalleryTpl($val, $opt=array())
 * @method static integer countByCustomTpl($val, $opt=array())
 * @method static integer countByPlayerSRC($val, $opt=array())
 * @method static integer countByYoutube($val, $opt=array())
 * @method static integer countByPosterSRC($val, $opt=array())
 * @method static integer countByPlayerSize($val, $opt=array())
 * @method static integer countByAutoplay($val, $opt=array())
 * @method static integer countBySliderDelay($val, $opt=array())
 * @method static integer countBySliderSpeed($val, $opt=array())
 * @method static integer countBySliderStartSlide($val, $opt=array())
 * @method static integer countBySliderContinuous($val, $opt=array())
 * @method static integer countByCteAlias($val, $opt=array())
 * @method static integer countByArticleAlias($val, $opt=array())
 * @method static integer countByArticle($val, $opt=array())
 * @method static integer countByForm($val, $opt=array())
 * @method static integer countByModule($val, $opt=array())
 * @method static integer countByProtected($val, $opt=array())
 * @method static integer countByGroups($val, $opt=array())
 * @method static integer countByGuests($val, $opt=array())
 * @method static integer countByCssID($val, $opt=array())
 * @method static integer countBySpace($val, $opt=array())
 * @method static integer countByInvisible($val, $opt=array())
 * @method static integer countByStart($val, $opt=array())
 * @method static integer countByStop($val, $opt=array())
 * @method static integer countByCom_order($val, $opt=array())
 * @method static integer countByCom_perPage($val, $opt=array())
 * @method static integer countByCom_moderate($val, $opt=array())
 * @method static integer countByCom_bbcode($val, $opt=array())
 * @method static integer countByCom_disableCaptcha($val, $opt=array())
 * @method static integer countByCom_requireLogin($val, $opt=array())
 * @method static integer countByCom_template($val, $opt=array())
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ContentModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_content';


	/**
	 * Find all published content elements by their parent ID and parent table
	 *
	 * @param integer $intPid         The article ID
	 * @param string  $strParentTable The parent table name
	 * @param array   $arrOptions     An optional options array
	 *
	 * @return \Model\Collection|\ContentModel[]|\ContentModel|null A collection of models or null if there are no content elements
	 */
	public static function findPublishedByPidAndTable($intPid, $strParentTable, array $arrOptions=array())
	{
		$t = static::$strTable;

		// Also handle empty ptable fields (backwards compatibility)
		if ($strParentTable == 'tl_article')
		{
			$arrColumns = array("$t.pid=? AND ($t.ptable=? OR $t.ptable='')");
		}
		else
		{
			$arrColumns = array("$t.pid=? AND $t.ptable=?");
		}

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.invisible=''";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting";
		}

		return static::findBy($arrColumns, array($intPid, $strParentTable), $arrOptions);
	}


	/**
	 * Find all published content elements by their parent ID and parent table
	 *
	 * @param integer $intPid         The article ID
	 * @param string  $strParentTable The parent table name
	 * @param array   $arrOptions     An optional options array
	 *
	 * @return integer The number of matching rows
	 */
	public static function countPublishedByPidAndTable($intPid, $strParentTable, array $arrOptions=array())
	{
		$t = static::$strTable;

		// Also handle empty ptable fields (backwards compatibility)
		if ($strParentTable == 'tl_article')
		{
			$arrColumns = array("$t.pid=? AND ($t.ptable=? OR $t.ptable='')");
		}
		else
		{
			$arrColumns = array("$t.pid=? AND $t.ptable=?");
		}

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.invisible=''";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting";
		}

		return static::countBy($arrColumns, array($intPid, $strParentTable), $arrOptions);
	}
}
