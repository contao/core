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
 * @method static \Model\Collection findByPid()
 * @method static \Model\Collection findByTstamp()
 * @method static \Model\Collection findByName()
 * @method static \Model\Collection findByRows()
 * @method static \Model\Collection findByHeaderHeight()
 * @method static \Model\Collection findByFooterHeight()
 * @method static \Model\Collection findByCols()
 * @method static \Model\Collection findByWidthLeft()
 * @method static \Model\Collection findByWidthRight()
 * @method static \Model\Collection findBySections()
 * @method static \Model\Collection findBySPosition()
 * @method static \Model\Collection findByFramework()
 * @method static \Model\Collection findByStylesheet()
 * @method static \Model\Collection findByExternal()
 * @method static \Model\Collection findByOrderExt()
 * @method static \Model\Collection findByLoadingOrder()
 * @method static \Model\Collection findByNewsfeeds()
 * @method static \Model\Collection findByCalendarfeeds()
 * @method static \Model\Collection findByModules()
 * @method static \Model\Collection findByTemplate()
 * @method static \Model\Collection findByDoctype()
 * @method static \Model\Collection findByWebfonts()
 * @method static \Model\Collection findByPicturefill()
 * @method static \Model\Collection findByViewport()
 * @method static \Model\Collection findByTitleTag()
 * @method static \Model\Collection findByCssClass()
 * @method static \Model\Collection findByOnload()
 * @method static \Model\Collection findByHead()
 * @method static \Model\Collection findByAddJQuery()
 * @method static \Model\Collection findByJSource()
 * @method static \Model\Collection findByJquery()
 * @method static \Model\Collection findByAddMooTools()
 * @method static \Model\Collection findByMooSource()
 * @method static \Model\Collection findByMootools()
 * @method static \Model\Collection findByAnalytics()
 * @method static \Model\Collection findByScript()
 * @method static \Model\Collection findByStatic()
 * @method static \Model\Collection findByWidth()
 * @method static \Model\Collection findByAlign()
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
