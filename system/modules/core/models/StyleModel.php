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
 * Reads and writes format definitions
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $sorting
 * @property integer $tstamp
 * @property string  $selector
 * @property string  $category
 * @property string  $comment
 * @property boolean $size
 * @property string  $width
 * @property string  $height
 * @property string  $minwidth
 * @property string  $minheight
 * @property string  $maxwidth
 * @property string  $maxheight
 * @property boolean $positioning
 * @property string  $trbl
 * @property string  $position
 * @property string  $floating
 * @property string  $clear
 * @property string  $overflow
 * @property string  $display
 * @property boolean $alignment
 * @property string  $margin
 * @property string  $padding
 * @property string  $align
 * @property string  $verticalalign
 * @property string  $textalign
 * @property string  $whitespace
 * @property boolean $background
 * @property string  $bgcolor
 * @property string  $bgimage
 * @property string  $bgposition
 * @property string  $bgrepeat
 * @property string  $shadowsize
 * @property string  $shadowcolor
 * @property string  $gradientAngle
 * @property string  $gradientColors
 * @property boolean $border
 * @property string  $borderwidth
 * @property string  $borderstyle
 * @property string  $bordercolor
 * @property string  $borderradius
 * @property string  $bordercollapse
 * @property string  $borderspacing
 * @property boolean $font
 * @property string  $fontfamily
 * @property string  $fontsize
 * @property string  $fontcolor
 * @property string  $lineheight
 * @property string  $fontstyle
 * @property string  $texttransform
 * @property string  $textindent
 * @property string  $letterspacing
 * @property string  $wordspacing
 * @property boolean $list
 * @property string  $liststyletype
 * @property string  $liststyleimage
 * @property string  $own
 * @property boolean $invisible
 *
 * @method static $this findById()
 * @method static $this findByPk()
 * @method static $this findByIdOrAlias()
 * @method static $this findOneBy()
 * @method static $this findOneByPid()
 * @method static $this findOneBySorting()
 * @method static $this findOneByTstamp()
 * @method static $this findOneBySelector()
 * @method static $this findOneByCategory()
 * @method static $this findOneByComment()
 * @method static $this findOneBySize()
 * @method static $this findOneByWidth()
 * @method static $this findOneByHeight()
 * @method static $this findOneByMinwidth()
 * @method static $this findOneByMinheight()
 * @method static $this findOneByMaxwidth()
 * @method static $this findOneByMaxheight()
 * @method static $this findOneByPositioning()
 * @method static $this findOneByTrbl()
 * @method static $this findOneByPosition()
 * @method static $this findOneByFloating()
 * @method static $this findOneByClear()
 * @method static $this findOneByOverflow()
 * @method static $this findOneByDisplay()
 * @method static $this findOneByAlignment()
 * @method static $this findOneByMargin()
 * @method static $this findOneByPadding()
 * @method static $this findOneByAlign()
 * @method static $this findOneByVerticalalign()
 * @method static $this findOneByTextalign()
 * @method static $this findOneByWhitespace()
 * @method static $this findOneByBackground()
 * @method static $this findOneByBgcolor()
 * @method static $this findOneByBgimage()
 * @method static $this findOneByBgposition()
 * @method static $this findOneByBgrepeat()
 * @method static $this findOneByShadowsize()
 * @method static $this findOneByShadowcolor()
 * @method static $this findOneByGradientAngle()
 * @method static $this findOneByGradientColors()
 * @method static $this findOneByBorder()
 * @method static $this findOneByBorderwidth()
 * @method static $this findOneByBorderstyle()
 * @method static $this findOneByBordercolor()
 * @method static $this findOneByBorderradius()
 * @method static $this findOneByBordercollapse()
 * @method static $this findOneByBorderspacing()
 * @method static $this findOneByFont()
 * @method static $this findOneByFontfamily()
 * @method static $this findOneByFontsize()
 * @method static $this findOneByFontcolor()
 * @method static $this findOneByLineheight()
 * @method static $this findOneByFontstyle()
 * @method static $this findOneByTexttransform()
 * @method static $this findOneByTextindent()
 * @method static $this findOneByLetterspacing()
 * @method static $this findOneByWordspacing()
 * @method static $this findOneByList()
 * @method static $this findOneByListstyletype()
 * @method static $this findOneByListstyleimage()
 * @method static $this findOneByOwn()
 * @method static $this findOneByInvisible()
 *
 * @method static \Model\Collection|\StyleModel findByPid()
 * @method static \Model\Collection|\StyleModel findBySorting()
 * @method static \Model\Collection|\StyleModel findByTstamp()
 * @method static \Model\Collection|\StyleModel findBySelector()
 * @method static \Model\Collection|\StyleModel findByCategory()
 * @method static \Model\Collection|\StyleModel findByComment()
 * @method static \Model\Collection|\StyleModel findBySize()
 * @method static \Model\Collection|\StyleModel findByWidth()
 * @method static \Model\Collection|\StyleModel findByHeight()
 * @method static \Model\Collection|\StyleModel findByMinwidth()
 * @method static \Model\Collection|\StyleModel findByMinheight()
 * @method static \Model\Collection|\StyleModel findByMaxwidth()
 * @method static \Model\Collection|\StyleModel findByMaxheight()
 * @method static \Model\Collection|\StyleModel findByPositioning()
 * @method static \Model\Collection|\StyleModel findByTrbl()
 * @method static \Model\Collection|\StyleModel findByPosition()
 * @method static \Model\Collection|\StyleModel findByFloating()
 * @method static \Model\Collection|\StyleModel findByClear()
 * @method static \Model\Collection|\StyleModel findByOverflow()
 * @method static \Model\Collection|\StyleModel findByDisplay()
 * @method static \Model\Collection|\StyleModel findByAlignment()
 * @method static \Model\Collection|\StyleModel findByMargin()
 * @method static \Model\Collection|\StyleModel findByPadding()
 * @method static \Model\Collection|\StyleModel findByAlign()
 * @method static \Model\Collection|\StyleModel findByVerticalalign()
 * @method static \Model\Collection|\StyleModel findByTextalign()
 * @method static \Model\Collection|\StyleModel findByWhitespace()
 * @method static \Model\Collection|\StyleModel findByBackground()
 * @method static \Model\Collection|\StyleModel findByBgcolor()
 * @method static \Model\Collection|\StyleModel findByBgimage()
 * @method static \Model\Collection|\StyleModel findByBgposition()
 * @method static \Model\Collection|\StyleModel findByBgrepeat()
 * @method static \Model\Collection|\StyleModel findByShadowsize()
 * @method static \Model\Collection|\StyleModel findByShadowcolor()
 * @method static \Model\Collection|\StyleModel findByGradientAngle()
 * @method static \Model\Collection|\StyleModel findByGradientColors()
 * @method static \Model\Collection|\StyleModel findByBorder()
 * @method static \Model\Collection|\StyleModel findByBorderwidth()
 * @method static \Model\Collection|\StyleModel findByBorderstyle()
 * @method static \Model\Collection|\StyleModel findByBordercolor()
 * @method static \Model\Collection|\StyleModel findByBorderradius()
 * @method static \Model\Collection|\StyleModel findByBordercollapse()
 * @method static \Model\Collection|\StyleModel findByBorderspacing()
 * @method static \Model\Collection|\StyleModel findByFont()
 * @method static \Model\Collection|\StyleModel findByFontfamily()
 * @method static \Model\Collection|\StyleModel findByFontsize()
 * @method static \Model\Collection|\StyleModel findByFontcolor()
 * @method static \Model\Collection|\StyleModel findByLineheight()
 * @method static \Model\Collection|\StyleModel findByFontstyle()
 * @method static \Model\Collection|\StyleModel findByTexttransform()
 * @method static \Model\Collection|\StyleModel findByTextindent()
 * @method static \Model\Collection|\StyleModel findByLetterspacing()
 * @method static \Model\Collection|\StyleModel findByWordspacing()
 * @method static \Model\Collection|\StyleModel findByList()
 * @method static \Model\Collection|\StyleModel findByListstyletype()
 * @method static \Model\Collection|\StyleModel findByListstyleimage()
 * @method static \Model\Collection|\StyleModel findByOwn()
 * @method static \Model\Collection|\StyleModel findByInvisible()
 * @method static \Model\Collection|\StyleModel findMultipleByIds()
 * @method static \Model\Collection|\StyleModel findBy()
 * @method static \Model\Collection|\StyleModel findAll()
 *
 * @method static integer countById()
 * @method static integer countByPid()
 * @method static integer countBySorting()
 * @method static integer countByTstamp()
 * @method static integer countBySelector()
 * @method static integer countByCategory()
 * @method static integer countByComment()
 * @method static integer countBySize()
 * @method static integer countByWidth()
 * @method static integer countByHeight()
 * @method static integer countByMinwidth()
 * @method static integer countByMinheight()
 * @method static integer countByMaxwidth()
 * @method static integer countByMaxheight()
 * @method static integer countByPositioning()
 * @method static integer countByTrbl()
 * @method static integer countByPosition()
 * @method static integer countByFloating()
 * @method static integer countByClear()
 * @method static integer countByOverflow()
 * @method static integer countByDisplay()
 * @method static integer countByAlignment()
 * @method static integer countByMargin()
 * @method static integer countByPadding()
 * @method static integer countByAlign()
 * @method static integer countByVerticalalign()
 * @method static integer countByTextalign()
 * @method static integer countByWhitespace()
 * @method static integer countByBackground()
 * @method static integer countByBgcolor()
 * @method static integer countByBgimage()
 * @method static integer countByBgposition()
 * @method static integer countByBgrepeat()
 * @method static integer countByShadowsize()
 * @method static integer countByShadowcolor()
 * @method static integer countByGradientAngle()
 * @method static integer countByGradientColors()
 * @method static integer countByBorder()
 * @method static integer countByBorderwidth()
 * @method static integer countByBorderstyle()
 * @method static integer countByBordercolor()
 * @method static integer countByBorderradius()
 * @method static integer countByBordercollapse()
 * @method static integer countByBorderspacing()
 * @method static integer countByFont()
 * @method static integer countByFontfamily()
 * @method static integer countByFontsize()
 * @method static integer countByFontcolor()
 * @method static integer countByLineheight()
 * @method static integer countByFontstyle()
 * @method static integer countByTexttransform()
 * @method static integer countByTextindent()
 * @method static integer countByLetterspacing()
 * @method static integer countByWordspacing()
 * @method static integer countByList()
 * @method static integer countByListstyletype()
 * @method static integer countByListstyleimage()
 * @method static integer countByOwn()
 * @method static integer countByInvisible()
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class StyleModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_style';

}
