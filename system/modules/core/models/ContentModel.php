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
 * @method static $this findById()
 * @method static $this findByPk()
 * @method static $this findByIdOrAlias()
 * @method static $this findOneBy()
 * @method static $this findOneByPid()
 * @method static $this findOneByPtable()
 * @method static $this findOneBySorting()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByType()
 * @method static $this findOneByHeadline()
 * @method static $this findOneByText()
 * @method static $this findOneByAddImage()
 * @method static $this findOneBySingleSRC()
 * @method static $this findOneByAlt()
 * @method static $this findOneByTitle()
 * @method static $this findOneBySize()
 * @method static $this findOneByImagemargin()
 * @method static $this findOneByImageUrl()
 * @method static $this findOneByFullsize()
 * @method static $this findOneByCaption()
 * @method static $this findOneByFloating()
 * @method static $this findOneByHtml()
 * @method static $this findOneByListtype()
 * @method static $this findOneByListitems()
 * @method static $this findOneByTableitems()
 * @method static $this findOneBySummary()
 * @method static $this findOneByThead()
 * @method static $this findOneByTfoot()
 * @method static $this findOneByTleft()
 * @method static $this findOneBySortable()
 * @method static $this findOneBySortIndex()
 * @method static $this findOneBySortOrder()
 * @method static $this findOneByMooHeadline()
 * @method static $this findOneByMooStyle()
 * @method static $this findOneByMooClasses()
 * @method static $this findOneByHighlight()
 * @method static $this findOneByShClass()
 * @method static $this findOneByCode()
 * @method static $this findOneByUrl()
 * @method static $this findOneByTarget()
 * @method static $this findOneByTitleText()
 * @method static $this findOneByLinkTitle()
 * @method static $this findOneByEmbed()
 * @method static $this findOneByRel()
 * @method static $this findOneByUseImage()
 * @method static $this findOneByMultiSRC()
 * @method static $this findOneByOrderSRC()
 * @method static $this findOneByUseHomeDir()
 * @method static $this findOneByPerRow()
 * @method static $this findOneByPerPage()
 * @method static $this findOneByNumberOfItems()
 * @method static $this findOneBySortBy()
 * @method static $this findOneByMetaIgnore()
 * @method static $this findOneByGalleryTpl()
 * @method static $this findOneByCustomTpl()
 * @method static $this findOneByPlayerSRC()
 * @method static $this findOneByYoutube()
 * @method static $this findOneByPosterSRC()
 * @method static $this findOneByPlayerSize()
 * @method static $this findOneByAutoplay()
 * @method static $this findOneBySliderDelay()
 * @method static $this findOneBySliderSpeed()
 * @method static $this findOneBySliderStartSlide()
 * @method static $this findOneBySliderContinuous()
 * @method static $this findOneByCteAlias()
 * @method static $this findOneByArticleAlias()
 * @method static $this findOneByArticle()
 * @method static $this findOneByForm()
 * @method static $this findOneByModule()
 * @method static $this findOneByProtected()
 * @method static $this findOneByGroups()
 * @method static $this findOneByGuests()
 * @method static $this findOneByCssID()
 * @method static $this findOneBySpace()
 * @method static $this findOneByInvisible()
 * @method static $this findOneByStart()
 * @method static $this findOneByStop()
 * @method static $this findOneByCom_order()
 * @method static $this findOneByCom_perPage()
 * @method static $this findOneByCom_moderate()
 * @method static $this findOneByCom_bbcode()
 * @method static $this findOneByCom_disableCaptcha()
 * @method static $this findOneByCom_requireLogin()
 * @method static $this findOneByCom_template()
 *
 * @method static \Model\Collection|\ContentModel findByPid()
 * @method static \Model\Collection|\ContentModel findByPtable()
 * @method static \Model\Collection|\ContentModel findBySorting()
 * @method static \Model\Collection|\ContentModel findByTstamp()
 * @method static \Model\Collection|\ContentModel findByType()
 * @method static \Model\Collection|\ContentModel findByHeadline()
 * @method static \Model\Collection|\ContentModel findByText()
 * @method static \Model\Collection|\ContentModel findByAddImage()
 * @method static \Model\Collection|\ContentModel findBySingleSRC()
 * @method static \Model\Collection|\ContentModel findByAlt()
 * @method static \Model\Collection|\ContentModel findByTitle()
 * @method static \Model\Collection|\ContentModel findBySize()
 * @method static \Model\Collection|\ContentModel findByImagemargin()
 * @method static \Model\Collection|\ContentModel findByImageUrl()
 * @method static \Model\Collection|\ContentModel findByFullsize()
 * @method static \Model\Collection|\ContentModel findByCaption()
 * @method static \Model\Collection|\ContentModel findByFloating()
 * @method static \Model\Collection|\ContentModel findByHtml()
 * @method static \Model\Collection|\ContentModel findByListtype()
 * @method static \Model\Collection|\ContentModel findByListitems()
 * @method static \Model\Collection|\ContentModel findByTableitems()
 * @method static \Model\Collection|\ContentModel findBySummary()
 * @method static \Model\Collection|\ContentModel findByThead()
 * @method static \Model\Collection|\ContentModel findByTfoot()
 * @method static \Model\Collection|\ContentModel findByTleft()
 * @method static \Model\Collection|\ContentModel findBySortable()
 * @method static \Model\Collection|\ContentModel findBySortIndex()
 * @method static \Model\Collection|\ContentModel findBySortOrder()
 * @method static \Model\Collection|\ContentModel findByMooHeadline()
 * @method static \Model\Collection|\ContentModel findByMooStyle()
 * @method static \Model\Collection|\ContentModel findByMooClasses()
 * @method static \Model\Collection|\ContentModel findByHighlight()
 * @method static \Model\Collection|\ContentModel findByShClass()
 * @method static \Model\Collection|\ContentModel findByCode()
 * @method static \Model\Collection|\ContentModel findByUrl()
 * @method static \Model\Collection|\ContentModel findByTarget()
 * @method static \Model\Collection|\ContentModel findByTitleText()
 * @method static \Model\Collection|\ContentModel findByLinkTitle()
 * @method static \Model\Collection|\ContentModel findByEmbed()
 * @method static \Model\Collection|\ContentModel findByRel()
 * @method static \Model\Collection|\ContentModel findByUseImage()
 * @method static \Model\Collection|\ContentModel findByMultiSRC()
 * @method static \Model\Collection|\ContentModel findByOrderSRC()
 * @method static \Model\Collection|\ContentModel findByUseHomeDir()
 * @method static \Model\Collection|\ContentModel findByPerRow()
 * @method static \Model\Collection|\ContentModel findByPerPage()
 * @method static \Model\Collection|\ContentModel findByNumberOfItems()
 * @method static \Model\Collection|\ContentModel findBySortBy()
 * @method static \Model\Collection|\ContentModel findByMetaIgnore()
 * @method static \Model\Collection|\ContentModel findByGalleryTpl()
 * @method static \Model\Collection|\ContentModel findByCustomTpl()
 * @method static \Model\Collection|\ContentModel findByPlayerSRC()
 * @method static \Model\Collection|\ContentModel findByYoutube()
 * @method static \Model\Collection|\ContentModel findByPosterSRC()
 * @method static \Model\Collection|\ContentModel findByPlayerSize()
 * @method static \Model\Collection|\ContentModel findByAutoplay()
 * @method static \Model\Collection|\ContentModel findBySliderDelay()
 * @method static \Model\Collection|\ContentModel findBySliderSpeed()
 * @method static \Model\Collection|\ContentModel findBySliderStartSlide()
 * @method static \Model\Collection|\ContentModel findBySliderContinuous()
 * @method static \Model\Collection|\ContentModel findByCteAlias()
 * @method static \Model\Collection|\ContentModel findByArticleAlias()
 * @method static \Model\Collection|\ContentModel findByArticle()
 * @method static \Model\Collection|\ContentModel findByForm()
 * @method static \Model\Collection|\ContentModel findByModule()
 * @method static \Model\Collection|\ContentModel findByProtected()
 * @method static \Model\Collection|\ContentModel findByGroups()
 * @method static \Model\Collection|\ContentModel findByGuests()
 * @method static \Model\Collection|\ContentModel findByCssID()
 * @method static \Model\Collection|\ContentModel findBySpace()
 * @method static \Model\Collection|\ContentModel findByInvisible()
 * @method static \Model\Collection|\ContentModel findByStart()
 * @method static \Model\Collection|\ContentModel findByStop()
 * @method static \Model\Collection|\ContentModel findByCom_order()
 * @method static \Model\Collection|\ContentModel findByCom_perPage()
 * @method static \Model\Collection|\ContentModel findByCom_moderate()
 * @method static \Model\Collection|\ContentModel findByCom_bbcode()
 * @method static \Model\Collection|\ContentModel findByCom_disableCaptcha()
 * @method static \Model\Collection|\ContentModel findByCom_requireLogin()
 * @method static \Model\Collection|\ContentModel findByCom_template()
 * @method static \Model\Collection|\ContentModel findMultipleByIds()
 * @method static \Model\Collection|\ContentModel findBy()
 * @method static \Model\Collection|\ContentModel findAll()
 *
 * @method static integer countById()
 * @method static integer countByPid()
 * @method static integer countByPtable()
 * @method static integer countBySorting()
 * @method static integer countByTstamp()
 * @method static integer countByType()
 * @method static integer countByHeadline()
 * @method static integer countByText()
 * @method static integer countByAddImage()
 * @method static integer countBySingleSRC()
 * @method static integer countByAlt()
 * @method static integer countByTitle()
 * @method static integer countBySize()
 * @method static integer countByImagemargin()
 * @method static integer countByImageUrl()
 * @method static integer countByFullsize()
 * @method static integer countByCaption()
 * @method static integer countByFloating()
 * @method static integer countByHtml()
 * @method static integer countByListtype()
 * @method static integer countByListitems()
 * @method static integer countByTableitems()
 * @method static integer countBySummary()
 * @method static integer countByThead()
 * @method static integer countByTfoot()
 * @method static integer countByTleft()
 * @method static integer countBySortable()
 * @method static integer countBySortIndex()
 * @method static integer countBySortOrder()
 * @method static integer countByMooHeadline()
 * @method static integer countByMooStyle()
 * @method static integer countByMooClasses()
 * @method static integer countByHighlight()
 * @method static integer countByShClass()
 * @method static integer countByCode()
 * @method static integer countByUrl()
 * @method static integer countByTarget()
 * @method static integer countByTitleText()
 * @method static integer countByLinkTitle()
 * @method static integer countByEmbed()
 * @method static integer countByRel()
 * @method static integer countByUseImage()
 * @method static integer countByMultiSRC()
 * @method static integer countByOrderSRC()
 * @method static integer countByUseHomeDir()
 * @method static integer countByPerRow()
 * @method static integer countByPerPage()
 * @method static integer countByNumberOfItems()
 * @method static integer countBySortBy()
 * @method static integer countByMetaIgnore()
 * @method static integer countByGalleryTpl()
 * @method static integer countByCustomTpl()
 * @method static integer countByPlayerSRC()
 * @method static integer countByYoutube()
 * @method static integer countByPosterSRC()
 * @method static integer countByPlayerSize()
 * @method static integer countByAutoplay()
 * @method static integer countBySliderDelay()
 * @method static integer countBySliderSpeed()
 * @method static integer countBySliderStartSlide()
 * @method static integer countBySliderContinuous()
 * @method static integer countByCteAlias()
 * @method static integer countByArticleAlias()
 * @method static integer countByArticle()
 * @method static integer countByForm()
 * @method static integer countByModule()
 * @method static integer countByProtected()
 * @method static integer countByGroups()
 * @method static integer countByGuests()
 * @method static integer countByCssID()
 * @method static integer countBySpace()
 * @method static integer countByInvisible()
 * @method static integer countByStart()
 * @method static integer countByStop()
 * @method static integer countByCom_order()
 * @method static integer countByCom_perPage()
 * @method static integer countByCom_moderate()
 * @method static integer countByCom_bbcode()
 * @method static integer countByCom_disableCaptcha()
 * @method static integer countByCom_requireLogin()
 * @method static integer countByCom_template()
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
