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
 * @method static \LayoutModel|null findById($id, $opt=array())
 * @method static \LayoutModel|null findByPk($id, $opt=array())
 * @method static \LayoutModel|null findByIdOrAlias($val, $opt=array())
 * @method static \LayoutModel|null findOneBy($col, $val, $opt=array())
 * @method static \LayoutModel|null findOneByPid($val, $opt=array())
 * @method static \LayoutModel|null findOneByTstamp($val, $opt=array())
 * @method static \LayoutModel|null findOneByName($val, $opt=array())
 * @method static \LayoutModel|null findOneByRows($val, $opt=array())
 * @method static \LayoutModel|null findOneByHeaderHeight($val, $opt=array())
 * @method static \LayoutModel|null findOneByFooterHeight($val, $opt=array())
 * @method static \LayoutModel|null findOneByCols($val, $opt=array())
 * @method static \LayoutModel|null findOneByWidthLeft($val, $opt=array())
 * @method static \LayoutModel|null findOneByWidthRight($val, $opt=array())
 * @method static \LayoutModel|null findOneBySections($val, $opt=array())
 * @method static \LayoutModel|null findOneBySPosition($val, $opt=array())
 * @method static \LayoutModel|null findOneByFramework($val, $opt=array())
 * @method static \LayoutModel|null findOneByStylesheet($val, $opt=array())
 * @method static \LayoutModel|null findOneByExternal($val, $opt=array())
 * @method static \LayoutModel|null findOneByOrderExt($val, $opt=array())
 * @method static \LayoutModel|null findOneByLoadingOrder($val, $opt=array())
 * @method static \LayoutModel|null findOneByNewsfeeds($val, $opt=array())
 * @method static \LayoutModel|null findOneByCalendarfeeds($val, $opt=array())
 * @method static \LayoutModel|null findOneByModules($val, $opt=array())
 * @method static \LayoutModel|null findOneByTemplate($val, $opt=array())
 * @method static \LayoutModel|null findOneByDoctype($val, $opt=array())
 * @method static \LayoutModel|null findOneByWebfonts($val, $opt=array())
 * @method static \LayoutModel|null findOneByPicturefill($val, $opt=array())
 * @method static \LayoutModel|null findOneByViewport($val, $opt=array())
 * @method static \LayoutModel|null findOneByTitleTag($val, $opt=array())
 * @method static \LayoutModel|null findOneByCssClass($val, $opt=array())
 * @method static \LayoutModel|null findOneByOnload($val, $opt=array())
 * @method static \LayoutModel|null findOneByHead($val, $opt=array())
 * @method static \LayoutModel|null findOneByAddJQuery($val, $opt=array())
 * @method static \LayoutModel|null findOneByJSource($val, $opt=array())
 * @method static \LayoutModel|null findOneByJquery($val, $opt=array())
 * @method static \LayoutModel|null findOneByAddMooTools($val, $opt=array())
 * @method static \LayoutModel|null findOneByMooSource($val, $opt=array())
 * @method static \LayoutModel|null findOneByMootools($val, $opt=array())
 * @method static \LayoutModel|null findOneByAnalytics($val, $opt=array())
 * @method static \LayoutModel|null findOneByScript($val, $opt=array())
 * @method static \LayoutModel|null findOneByStatic($val, $opt=array())
 * @method static \LayoutModel|null findOneByWidth($val, $opt=array())
 * @method static \LayoutModel|null findOneByAlign($val, $opt=array())
 *
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByPid($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByName($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByRows($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByHeaderHeight($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByFooterHeight($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByCols($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByWidthLeft($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByWidthRight($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findBySections($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findBySPosition($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByFramework($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByStylesheet($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByExternal($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByOrderExt($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByLoadingOrder($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByNewsfeeds($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByCalendarfeeds($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByModules($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByTemplate($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByDoctype($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByWebfonts($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByPicturefill($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByViewport($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByTitleTag($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByCssClass($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByOnload($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByHead($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByAddJQuery($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByJSource($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByJquery($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByAddMooTools($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByMooSource($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByMootools($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByAnalytics($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByScript($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByStatic($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByWidth($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findByAlign($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\LayoutModel[]|\LayoutModel|null findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByPid($val, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByName($val, $opt=array())
 * @method static integer countByRows($val, $opt=array())
 * @method static integer countByHeaderHeight($val, $opt=array())
 * @method static integer countByFooterHeight($val, $opt=array())
 * @method static integer countByCols($val, $opt=array())
 * @method static integer countByWidthLeft($val, $opt=array())
 * @method static integer countByWidthRight($val, $opt=array())
 * @method static integer countBySections($val, $opt=array())
 * @method static integer countBySPosition($val, $opt=array())
 * @method static integer countByFramework($val, $opt=array())
 * @method static integer countByStylesheet($val, $opt=array())
 * @method static integer countByExternal($val, $opt=array())
 * @method static integer countByOrderExt($val, $opt=array())
 * @method static integer countByLoadingOrder($val, $opt=array())
 * @method static integer countByNewsfeeds($val, $opt=array())
 * @method static integer countByCalendarfeeds($val, $opt=array())
 * @method static integer countByModules($val, $opt=array())
 * @method static integer countByTemplate($val, $opt=array())
 * @method static integer countByDoctype($val, $opt=array())
 * @method static integer countByWebfonts($val, $opt=array())
 * @method static integer countByPicturefill($val, $opt=array())
 * @method static integer countByViewport($val, $opt=array())
 * @method static integer countByTitleTag($val, $opt=array())
 * @method static integer countByCssClass($val, $opt=array())
 * @method static integer countByOnload($val, $opt=array())
 * @method static integer countByHead($val, $opt=array())
 * @method static integer countByAddJQuery($val, $opt=array())
 * @method static integer countByJSource($val, $opt=array())
 * @method static integer countByJquery($val, $opt=array())
 * @method static integer countByAddMooTools($val, $opt=array())
 * @method static integer countByMooSource($val, $opt=array())
 * @method static integer countByMootools($val, $opt=array())
 * @method static integer countByAnalytics($val, $opt=array())
 * @method static integer countByScript($val, $opt=array())
 * @method static integer countByStatic($val, $opt=array())
 * @method static integer countByWidth($val, $opt=array())
 * @method static integer countByAlign($val, $opt=array())
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
