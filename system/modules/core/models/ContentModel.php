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
 * @method static $this findById($id, $opt=array())
 * @method static $this findByPk($id, $opt=array())
 * @method static $this findByIdOrAlias($val, $opt=array())
 * @method static $this findOneBy($col, $val, $opt=array())
 * @method static $this findOneByPid($val, $opt=array())
 * @method static $this findOneByPtable($val, $opt=array())
 * @method static $this findOneBySorting($val, $opt=array())
 * @method static $this findOneByTstamp($val, $opt=array())
 * @method static $this findOneByType($val, $opt=array())
 * @method static $this findOneByHeadline($val, $opt=array())
 * @method static $this findOneByText($val, $opt=array())
 * @method static $this findOneByAddImage($val, $opt=array())
 * @method static $this findOneBySingleSRC($val, $opt=array())
 * @method static $this findOneByAlt($val, $opt=array())
 * @method static $this findOneByTitle($val, $opt=array())
 * @method static $this findOneBySize($val, $opt=array())
 * @method static $this findOneByImagemargin($val, $opt=array())
 * @method static $this findOneByImageUrl($val, $opt=array())
 * @method static $this findOneByFullsize($val, $opt=array())
 * @method static $this findOneByCaption($val, $opt=array())
 * @method static $this findOneByFloating($val, $opt=array())
 * @method static $this findOneByHtml($val, $opt=array())
 * @method static $this findOneByListtype($val, $opt=array())
 * @method static $this findOneByListitems($val, $opt=array())
 * @method static $this findOneByTableitems($val, $opt=array())
 * @method static $this findOneBySummary($val, $opt=array())
 * @method static $this findOneByThead($val, $opt=array())
 * @method static $this findOneByTfoot($val, $opt=array())
 * @method static $this findOneByTleft($val, $opt=array())
 * @method static $this findOneBySortable($val, $opt=array())
 * @method static $this findOneBySortIndex($val, $opt=array())
 * @method static $this findOneBySortOrder($val, $opt=array())
 * @method static $this findOneByMooHeadline($val, $opt=array())
 * @method static $this findOneByMooStyle($val, $opt=array())
 * @method static $this findOneByMooClasses($val, $opt=array())
 * @method static $this findOneByHighlight($val, $opt=array())
 * @method static $this findOneByShClass($val, $opt=array())
 * @method static $this findOneByCode($val, $opt=array())
 * @method static $this findOneByUrl($val, $opt=array())
 * @method static $this findOneByTarget($val, $opt=array())
 * @method static $this findOneByTitleText($val, $opt=array())
 * @method static $this findOneByLinkTitle($val, $opt=array())
 * @method static $this findOneByEmbed($val, $opt=array())
 * @method static $this findOneByRel($val, $opt=array())
 * @method static $this findOneByUseImage($val, $opt=array())
 * @method static $this findOneByMultiSRC($val, $opt=array())
 * @method static $this findOneByOrderSRC($val, $opt=array())
 * @method static $this findOneByUseHomeDir($val, $opt=array())
 * @method static $this findOneByPerRow($val, $opt=array())
 * @method static $this findOneByPerPage($val, $opt=array())
 * @method static $this findOneByNumberOfItems($val, $opt=array())
 * @method static $this findOneBySortBy($val, $opt=array())
 * @method static $this findOneByMetaIgnore($val, $opt=array())
 * @method static $this findOneByGalleryTpl($val, $opt=array())
 * @method static $this findOneByCustomTpl($val, $opt=array())
 * @method static $this findOneByPlayerSRC($val, $opt=array())
 * @method static $this findOneByYoutube($val, $opt=array())
 * @method static $this findOneByPosterSRC($val, $opt=array())
 * @method static $this findOneByPlayerSize($val, $opt=array())
 * @method static $this findOneByAutoplay($val, $opt=array())
 * @method static $this findOneBySliderDelay($val, $opt=array())
 * @method static $this findOneBySliderSpeed($val, $opt=array())
 * @method static $this findOneBySliderStartSlide($val, $opt=array())
 * @method static $this findOneBySliderContinuous($val, $opt=array())
 * @method static $this findOneByCteAlias($val, $opt=array())
 * @method static $this findOneByArticleAlias($val, $opt=array())
 * @method static $this findOneByArticle($val, $opt=array())
 * @method static $this findOneByForm($val, $opt=array())
 * @method static $this findOneByModule($val, $opt=array())
 * @method static $this findOneByProtected($val, $opt=array())
 * @method static $this findOneByGroups($val, $opt=array())
 * @method static $this findOneByGuests($val, $opt=array())
 * @method static $this findOneByCssID($val, $opt=array())
 * @method static $this findOneBySpace($val, $opt=array())
 * @method static $this findOneByInvisible($val, $opt=array())
 * @method static $this findOneByStart($val, $opt=array())
 * @method static $this findOneByStop($val, $opt=array())
 * @method static $this findOneByCom_order($val, $opt=array())
 * @method static $this findOneByCom_perPage($val, $opt=array())
 * @method static $this findOneByCom_moderate($val, $opt=array())
 * @method static $this findOneByCom_bbcode($val, $opt=array())
 * @method static $this findOneByCom_disableCaptcha($val, $opt=array())
 * @method static $this findOneByCom_requireLogin($val, $opt=array())
 * @method static $this findOneByCom_template($val, $opt=array())
 *
 * @method static \Model\Collection|\ContentModel findByPid($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByPtable($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findBySorting($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByType($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByHeadline($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByText($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByAddImage($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findBySingleSRC($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByAlt($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByTitle($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findBySize($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByImagemargin($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByImageUrl($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByFullsize($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByCaption($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByFloating($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByHtml($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByListtype($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByListitems($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByTableitems($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findBySummary($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByThead($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByTfoot($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByTleft($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findBySortable($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findBySortIndex($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findBySortOrder($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByMooHeadline($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByMooStyle($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByMooClasses($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByHighlight($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByShClass($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByCode($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByUrl($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByTarget($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByTitleText($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByLinkTitle($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByEmbed($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByRel($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByUseImage($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByMultiSRC($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByOrderSRC($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByUseHomeDir($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByPerRow($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByPerPage($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByNumberOfItems($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findBySortBy($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByMetaIgnore($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByGalleryTpl($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByCustomTpl($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByPlayerSRC($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByYoutube($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByPosterSRC($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByPlayerSize($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByAutoplay($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findBySliderDelay($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findBySliderSpeed($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findBySliderStartSlide($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findBySliderContinuous($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByCteAlias($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByArticleAlias($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByArticle($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByForm($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByModule($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByProtected($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByGroups($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByGuests($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByCssID($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findBySpace($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByInvisible($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByStart($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByStop($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByCom_order($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByCom_perPage($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByCom_moderate($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByCom_bbcode($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByCom_disableCaptcha($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByCom_requireLogin($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findByCom_template($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\ContentModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\ContentModel findAll($opt=array())
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
	 * @return \Model\Collection|\ContentModel|null A collection of models or null if there are no content elements
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
}
