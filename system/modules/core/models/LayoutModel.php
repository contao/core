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
 * Reads and writes page layouts
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $tstamp
 * @property string  $name
 * @property string  $rows
 * @property string  $headerHeight
 * @property string  $footerHeight
 * @property string  $cols
 * @property string  $widthLeft
 * @property string  $widthRight
 * @property string  $sections
 * @property string  $sPosition
 * @property string  $framework
 * @property string  $stylesheet
 * @property string  $external
 * @property string  $orderExt
 * @property string  $loadingOrder
 * @property string  $newsfeeds
 * @property string  $calendarfeeds
 * @property string  $modules
 * @property string  $template
 * @property string  $doctype
 * @property string  $webfonts
 * @property boolean $picturefill
 * @property string  $viewport
 * @property string  $titleTag
 * @property string  $cssClass
 * @property string  $onload
 * @property string  $head
 * @property boolean $addJQuery
 * @property string  $jSource
 * @property string  $jquery
 * @property boolean $addMooTools
 * @property string  $mooSource
 * @property string  $mootools
 * @property string  $analytics
 * @property string  $script
 * @property boolean $static
 * @property string  $width
 * @property string  $align
 *
 * @method static $this findById()
 * @method static $this findByPk()
 * @method static $this findByIdOrAlias()
 * @method static $this findOneBy()
 * @method static $this findOneByPid()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByName()
 * @method static $this findOneByRows()
 * @method static $this findOneByHeaderHeight()
 * @method static $this findOneByFooterHeight()
 * @method static $this findOneByCols()
 * @method static $this findOneByWidthLeft()
 * @method static $this findOneByWidthRight()
 * @method static $this findOneBySections()
 * @method static $this findOneBySPosition()
 * @method static $this findOneByFramework()
 * @method static $this findOneByStylesheet()
 * @method static $this findOneByExternal()
 * @method static $this findOneByOrderExt()
 * @method static $this findOneByLoadingOrder()
 * @method static $this findOneByNewsfeeds()
 * @method static $this findOneByCalendarfeeds()
 * @method static $this findOneByModules()
 * @method static $this findOneByTemplate()
 * @method static $this findOneByDoctype()
 * @method static $this findOneByWebfonts()
 * @method static $this findOneByPicturefill()
 * @method static $this findOneByViewport()
 * @method static $this findOneByTitleTag()
 * @method static $this findOneByCssClass()
 * @method static $this findOneByOnload()
 * @method static $this findOneByHead()
 * @method static $this findOneByAddJQuery()
 * @method static $this findOneByJSource()
 * @method static $this findOneByJquery()
 * @method static $this findOneByAddMooTools()
 * @method static $this findOneByMooSource()
 * @method static $this findOneByMootools()
 * @method static $this findOneByAnalytics()
 * @method static $this findOneByScript()
 * @method static $this findOneByStatic()
 * @method static $this findOneByWidth()
 * @method static $this findOneByAlign()
 *
 * @method static \Model\Collection|\LayoutModel findByPid()
 * @method static \Model\Collection|\LayoutModel findByTstamp()
 * @method static \Model\Collection|\LayoutModel findByName()
 * @method static \Model\Collection|\LayoutModel findByRows()
 * @method static \Model\Collection|\LayoutModel findByHeaderHeight()
 * @method static \Model\Collection|\LayoutModel findByFooterHeight()
 * @method static \Model\Collection|\LayoutModel findByCols()
 * @method static \Model\Collection|\LayoutModel findByWidthLeft()
 * @method static \Model\Collection|\LayoutModel findByWidthRight()
 * @method static \Model\Collection|\LayoutModel findBySections()
 * @method static \Model\Collection|\LayoutModel findBySPosition()
 * @method static \Model\Collection|\LayoutModel findByFramework()
 * @method static \Model\Collection|\LayoutModel findByStylesheet()
 * @method static \Model\Collection|\LayoutModel findByExternal()
 * @method static \Model\Collection|\LayoutModel findByOrderExt()
 * @method static \Model\Collection|\LayoutModel findByLoadingOrder()
 * @method static \Model\Collection|\LayoutModel findByNewsfeeds()
 * @method static \Model\Collection|\LayoutModel findByCalendarfeeds()
 * @method static \Model\Collection|\LayoutModel findByModules()
 * @method static \Model\Collection|\LayoutModel findByTemplate()
 * @method static \Model\Collection|\LayoutModel findByDoctype()
 * @method static \Model\Collection|\LayoutModel findByWebfonts()
 * @method static \Model\Collection|\LayoutModel findByPicturefill()
 * @method static \Model\Collection|\LayoutModel findByViewport()
 * @method static \Model\Collection|\LayoutModel findByTitleTag()
 * @method static \Model\Collection|\LayoutModel findByCssClass()
 * @method static \Model\Collection|\LayoutModel findByOnload()
 * @method static \Model\Collection|\LayoutModel findByHead()
 * @method static \Model\Collection|\LayoutModel findByAddJQuery()
 * @method static \Model\Collection|\LayoutModel findByJSource()
 * @method static \Model\Collection|\LayoutModel findByJquery()
 * @method static \Model\Collection|\LayoutModel findByAddMooTools()
 * @method static \Model\Collection|\LayoutModel findByMooSource()
 * @method static \Model\Collection|\LayoutModel findByMootools()
 * @method static \Model\Collection|\LayoutModel findByAnalytics()
 * @method static \Model\Collection|\LayoutModel findByScript()
 * @method static \Model\Collection|\LayoutModel findByStatic()
 * @method static \Model\Collection|\LayoutModel findByWidth()
 * @method static \Model\Collection|\LayoutModel findByAlign()
 * @method static \Model\Collection|\LayoutModel findMultipleByIds()
 * @method static \Model\Collection|\LayoutModel findBy()
 * @method static \Model\Collection|\LayoutModel findAll()
 *
 * @method static integer countById()
 * @method static integer countByPid()
 * @method static integer countByTstamp()
 * @method static integer countByName()
 * @method static integer countByRows()
 * @method static integer countByHeaderHeight()
 * @method static integer countByFooterHeight()
 * @method static integer countByCols()
 * @method static integer countByWidthLeft()
 * @method static integer countByWidthRight()
 * @method static integer countBySections()
 * @method static integer countBySPosition()
 * @method static integer countByFramework()
 * @method static integer countByStylesheet()
 * @method static integer countByExternal()
 * @method static integer countByOrderExt()
 * @method static integer countByLoadingOrder()
 * @method static integer countByNewsfeeds()
 * @method static integer countByCalendarfeeds()
 * @method static integer countByModules()
 * @method static integer countByTemplate()
 * @method static integer countByDoctype()
 * @method static integer countByWebfonts()
 * @method static integer countByPicturefill()
 * @method static integer countByViewport()
 * @method static integer countByTitleTag()
 * @method static integer countByCssClass()
 * @method static integer countByOnload()
 * @method static integer countByHead()
 * @method static integer countByAddJQuery()
 * @method static integer countByJSource()
 * @method static integer countByJquery()
 * @method static integer countByAddMooTools()
 * @method static integer countByMooSource()
 * @method static integer countByMootools()
 * @method static integer countByAnalytics()
 * @method static integer countByScript()
 * @method static integer countByStatic()
 * @method static integer countByWidth()
 * @method static integer countByAlign()
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class LayoutModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_layout';

}
