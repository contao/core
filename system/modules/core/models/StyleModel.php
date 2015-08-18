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
 * @method static \StyleModel findById($id, $opt=array())
 * @method static \StyleModel findByPk($id, $opt=array())
 * @method static \StyleModel findByIdOrAlias($val, $opt=array())
 * @method static \StyleModel findOneBy($col, $val, $opt=array())
 * @method static \StyleModel findOneByPid($val, $opt=array())
 * @method static \StyleModel findOneBySorting($val, $opt=array())
 * @method static \StyleModel findOneByTstamp($val, $opt=array())
 * @method static \StyleModel findOneBySelector($val, $opt=array())
 * @method static \StyleModel findOneByCategory($val, $opt=array())
 * @method static \StyleModel findOneByComment($val, $opt=array())
 * @method static \StyleModel findOneBySize($val, $opt=array())
 * @method static \StyleModel findOneByWidth($val, $opt=array())
 * @method static \StyleModel findOneByHeight($val, $opt=array())
 * @method static \StyleModel findOneByMinwidth($val, $opt=array())
 * @method static \StyleModel findOneByMinheight($val, $opt=array())
 * @method static \StyleModel findOneByMaxwidth($val, $opt=array())
 * @method static \StyleModel findOneByMaxheight($val, $opt=array())
 * @method static \StyleModel findOneByPositioning($val, $opt=array())
 * @method static \StyleModel findOneByTrbl($val, $opt=array())
 * @method static \StyleModel findOneByPosition($val, $opt=array())
 * @method static \StyleModel findOneByFloating($val, $opt=array())
 * @method static \StyleModel findOneByClear($val, $opt=array())
 * @method static \StyleModel findOneByOverflow($val, $opt=array())
 * @method static \StyleModel findOneByDisplay($val, $opt=array())
 * @method static \StyleModel findOneByAlignment($val, $opt=array())
 * @method static \StyleModel findOneByMargin($val, $opt=array())
 * @method static \StyleModel findOneByPadding($val, $opt=array())
 * @method static \StyleModel findOneByAlign($val, $opt=array())
 * @method static \StyleModel findOneByVerticalalign($val, $opt=array())
 * @method static \StyleModel findOneByTextalign($val, $opt=array())
 * @method static \StyleModel findOneByWhitespace($val, $opt=array())
 * @method static \StyleModel findOneByBackground($val, $opt=array())
 * @method static \StyleModel findOneByBgcolor($val, $opt=array())
 * @method static \StyleModel findOneByBgimage($val, $opt=array())
 * @method static \StyleModel findOneByBgposition($val, $opt=array())
 * @method static \StyleModel findOneByBgrepeat($val, $opt=array())
 * @method static \StyleModel findOneByShadowsize($val, $opt=array())
 * @method static \StyleModel findOneByShadowcolor($val, $opt=array())
 * @method static \StyleModel findOneByGradientAngle($val, $opt=array())
 * @method static \StyleModel findOneByGradientColors($val, $opt=array())
 * @method static \StyleModel findOneByBorder($val, $opt=array())
 * @method static \StyleModel findOneByBorderwidth($val, $opt=array())
 * @method static \StyleModel findOneByBorderstyle($val, $opt=array())
 * @method static \StyleModel findOneByBordercolor($val, $opt=array())
 * @method static \StyleModel findOneByBorderradius($val, $opt=array())
 * @method static \StyleModel findOneByBordercollapse($val, $opt=array())
 * @method static \StyleModel findOneByBorderspacing($val, $opt=array())
 * @method static \StyleModel findOneByFont($val, $opt=array())
 * @method static \StyleModel findOneByFontfamily($val, $opt=array())
 * @method static \StyleModel findOneByFontsize($val, $opt=array())
 * @method static \StyleModel findOneByFontcolor($val, $opt=array())
 * @method static \StyleModel findOneByLineheight($val, $opt=array())
 * @method static \StyleModel findOneByFontstyle($val, $opt=array())
 * @method static \StyleModel findOneByTexttransform($val, $opt=array())
 * @method static \StyleModel findOneByTextindent($val, $opt=array())
 * @method static \StyleModel findOneByLetterspacing($val, $opt=array())
 * @method static \StyleModel findOneByWordspacing($val, $opt=array())
 * @method static \StyleModel findOneByList($val, $opt=array())
 * @method static \StyleModel findOneByListstyletype($val, $opt=array())
 * @method static \StyleModel findOneByListstyleimage($val, $opt=array())
 * @method static \StyleModel findOneByOwn($val, $opt=array())
 * @method static \StyleModel findOneByInvisible($val, $opt=array())
 *
 * @method static \Model\Collection|\StyleModel findByPid($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findBySorting($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findBySelector($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByCategory($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByComment($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findBySize($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByWidth($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByHeight($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByMinwidth($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByMinheight($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByMaxwidth($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByMaxheight($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByPositioning($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByTrbl($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByPosition($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByFloating($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByClear($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByOverflow($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByDisplay($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByAlignment($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByMargin($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByPadding($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByAlign($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByVerticalalign($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByTextalign($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByWhitespace($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByBackground($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByBgcolor($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByBgimage($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByBgposition($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByBgrepeat($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByShadowsize($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByShadowcolor($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByGradientAngle($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByGradientColors($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByBorder($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByBorderwidth($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByBorderstyle($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByBordercolor($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByBorderradius($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByBordercollapse($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByBorderspacing($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByFont($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByFontfamily($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByFontsize($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByFontcolor($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByLineheight($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByFontstyle($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByTexttransform($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByTextindent($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByLetterspacing($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByWordspacing($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByList($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByListstyletype($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByListstyleimage($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByOwn($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findByInvisible($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\StyleModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\StyleModel findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByPid($val, $opt=array())
 * @method static integer countBySorting($val, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countBySelector($val, $opt=array())
 * @method static integer countByCategory($val, $opt=array())
 * @method static integer countByComment($val, $opt=array())
 * @method static integer countBySize($val, $opt=array())
 * @method static integer countByWidth($val, $opt=array())
 * @method static integer countByHeight($val, $opt=array())
 * @method static integer countByMinwidth($val, $opt=array())
 * @method static integer countByMinheight($val, $opt=array())
 * @method static integer countByMaxwidth($val, $opt=array())
 * @method static integer countByMaxheight($val, $opt=array())
 * @method static integer countByPositioning($val, $opt=array())
 * @method static integer countByTrbl($val, $opt=array())
 * @method static integer countByPosition($val, $opt=array())
 * @method static integer countByFloating($val, $opt=array())
 * @method static integer countByClear($val, $opt=array())
 * @method static integer countByOverflow($val, $opt=array())
 * @method static integer countByDisplay($val, $opt=array())
 * @method static integer countByAlignment($val, $opt=array())
 * @method static integer countByMargin($val, $opt=array())
 * @method static integer countByPadding($val, $opt=array())
 * @method static integer countByAlign($val, $opt=array())
 * @method static integer countByVerticalalign($val, $opt=array())
 * @method static integer countByTextalign($val, $opt=array())
 * @method static integer countByWhitespace($val, $opt=array())
 * @method static integer countByBackground($val, $opt=array())
 * @method static integer countByBgcolor($val, $opt=array())
 * @method static integer countByBgimage($val, $opt=array())
 * @method static integer countByBgposition($val, $opt=array())
 * @method static integer countByBgrepeat($val, $opt=array())
 * @method static integer countByShadowsize($val, $opt=array())
 * @method static integer countByShadowcolor($val, $opt=array())
 * @method static integer countByGradientAngle($val, $opt=array())
 * @method static integer countByGradientColors($val, $opt=array())
 * @method static integer countByBorder($val, $opt=array())
 * @method static integer countByBorderwidth($val, $opt=array())
 * @method static integer countByBorderstyle($val, $opt=array())
 * @method static integer countByBordercolor($val, $opt=array())
 * @method static integer countByBorderradius($val, $opt=array())
 * @method static integer countByBordercollapse($val, $opt=array())
 * @method static integer countByBorderspacing($val, $opt=array())
 * @method static integer countByFont($val, $opt=array())
 * @method static integer countByFontfamily($val, $opt=array())
 * @method static integer countByFontsize($val, $opt=array())
 * @method static integer countByFontcolor($val, $opt=array())
 * @method static integer countByLineheight($val, $opt=array())
 * @method static integer countByFontstyle($val, $opt=array())
 * @method static integer countByTexttransform($val, $opt=array())
 * @method static integer countByTextindent($val, $opt=array())
 * @method static integer countByLetterspacing($val, $opt=array())
 * @method static integer countByWordspacing($val, $opt=array())
 * @method static integer countByList($val, $opt=array())
 * @method static integer countByListstyletype($val, $opt=array())
 * @method static integer countByListstyleimage($val, $opt=array())
 * @method static integer countByOwn($val, $opt=array())
 * @method static integer countByInvisible($val, $opt=array())
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
