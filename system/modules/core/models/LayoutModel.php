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
 * @method static \LayoutModel findById($id, $opt=array())
 * @method static \LayoutModel findByPk($id, $opt=array())
 * @method static \LayoutModel findByIdOrAlias($val, $opt=array())
 * @method static \LayoutModel findOneBy($col, $val, $opt=array())
 * @method static \LayoutModel findOneByPid($val, $opt=array())
 * @method static \LayoutModel findOneByTstamp($val, $opt=array())
 * @method static \LayoutModel findOneByName($val, $opt=array())
 * @method static \LayoutModel findOneByRows($val, $opt=array())
 * @method static \LayoutModel findOneByHeaderHeight($val, $opt=array())
 * @method static \LayoutModel findOneByFooterHeight($val, $opt=array())
 * @method static \LayoutModel findOneByCols($val, $opt=array())
 * @method static \LayoutModel findOneByWidthLeft($val, $opt=array())
 * @method static \LayoutModel findOneByWidthRight($val, $opt=array())
 * @method static \LayoutModel findOneBySections($val, $opt=array())
 * @method static \LayoutModel findOneBySPosition($val, $opt=array())
 * @method static \LayoutModel findOneByFramework($val, $opt=array())
 * @method static \LayoutModel findOneByStylesheet($val, $opt=array())
 * @method static \LayoutModel findOneByExternal($val, $opt=array())
 * @method static \LayoutModel findOneByOrderExt($val, $opt=array())
 * @method static \LayoutModel findOneByLoadingOrder($val, $opt=array())
 * @method static \LayoutModel findOneByNewsfeeds($val, $opt=array())
 * @method static \LayoutModel findOneByCalendarfeeds($val, $opt=array())
 * @method static \LayoutModel findOneByModules($val, $opt=array())
 * @method static \LayoutModel findOneByTemplate($val, $opt=array())
 * @method static \LayoutModel findOneByDoctype($val, $opt=array())
 * @method static \LayoutModel findOneByWebfonts($val, $opt=array())
 * @method static \LayoutModel findOneByPicturefill($val, $opt=array())
 * @method static \LayoutModel findOneByViewport($val, $opt=array())
 * @method static \LayoutModel findOneByTitleTag($val, $opt=array())
 * @method static \LayoutModel findOneByCssClass($val, $opt=array())
 * @method static \LayoutModel findOneByOnload($val, $opt=array())
 * @method static \LayoutModel findOneByHead($val, $opt=array())
 * @method static \LayoutModel findOneByAddJQuery($val, $opt=array())
 * @method static \LayoutModel findOneByJSource($val, $opt=array())
 * @method static \LayoutModel findOneByJquery($val, $opt=array())
 * @method static \LayoutModel findOneByAddMooTools($val, $opt=array())
 * @method static \LayoutModel findOneByMooSource($val, $opt=array())
 * @method static \LayoutModel findOneByMootools($val, $opt=array())
 * @method static \LayoutModel findOneByAnalytics($val, $opt=array())
 * @method static \LayoutModel findOneByScript($val, $opt=array())
 * @method static \LayoutModel findOneByStatic($val, $opt=array())
 * @method static \LayoutModel findOneByWidth($val, $opt=array())
 * @method static \LayoutModel findOneByAlign($val, $opt=array())
 *
 * @method static \Model\Collection|\LayoutModel findByPid($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByName($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByRows($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByHeaderHeight($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByFooterHeight($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByCols($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByWidthLeft($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByWidthRight($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findBySections($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findBySPosition($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByFramework($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByStylesheet($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByExternal($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByOrderExt($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByLoadingOrder($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByNewsfeeds($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByCalendarfeeds($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByModules($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByTemplate($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByDoctype($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByWebfonts($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByPicturefill($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByViewport($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByTitleTag($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByCssClass($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByOnload($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByHead($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByAddJQuery($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByJSource($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByJquery($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByAddMooTools($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByMooSource($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByMootools($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByAnalytics($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByScript($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByStatic($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByWidth($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findByAlign($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\LayoutModel findAll($opt=array())
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
