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
 * @method static \StyleModel|null findById($id, $opt=array())
 * @method static \StyleModel|null findByPk($id, $opt=array())
 * @method static \StyleModel|null findByIdOrAlias($val, $opt=array())
 * @method static \StyleModel|null findOneBy($col, $val, $opt=array())
 * @method static \StyleModel|null findOneByPid($val, $opt=array())
 * @method static \StyleModel|null findOneBySorting($val, $opt=array())
 * @method static \StyleModel|null findOneByTstamp($val, $opt=array())
 * @method static \StyleModel|null findOneBySelector($val, $opt=array())
 * @method static \StyleModel|null findOneByCategory($val, $opt=array())
 * @method static \StyleModel|null findOneByComment($val, $opt=array())
 * @method static \StyleModel|null findOneBySize($val, $opt=array())
 * @method static \StyleModel|null findOneByWidth($val, $opt=array())
 * @method static \StyleModel|null findOneByHeight($val, $opt=array())
 * @method static \StyleModel|null findOneByMinwidth($val, $opt=array())
 * @method static \StyleModel|null findOneByMinheight($val, $opt=array())
 * @method static \StyleModel|null findOneByMaxwidth($val, $opt=array())
 * @method static \StyleModel|null findOneByMaxheight($val, $opt=array())
 * @method static \StyleModel|null findOneByPositioning($val, $opt=array())
 * @method static \StyleModel|null findOneByTrbl($val, $opt=array())
 * @method static \StyleModel|null findOneByPosition($val, $opt=array())
 * @method static \StyleModel|null findOneByFloating($val, $opt=array())
 * @method static \StyleModel|null findOneByClear($val, $opt=array())
 * @method static \StyleModel|null findOneByOverflow($val, $opt=array())
 * @method static \StyleModel|null findOneByDisplay($val, $opt=array())
 * @method static \StyleModel|null findOneByAlignment($val, $opt=array())
 * @method static \StyleModel|null findOneByMargin($val, $opt=array())
 * @method static \StyleModel|null findOneByPadding($val, $opt=array())
 * @method static \StyleModel|null findOneByAlign($val, $opt=array())
 * @method static \StyleModel|null findOneByVerticalalign($val, $opt=array())
 * @method static \StyleModel|null findOneByTextalign($val, $opt=array())
 * @method static \StyleModel|null findOneByWhitespace($val, $opt=array())
 * @method static \StyleModel|null findOneByBackground($val, $opt=array())
 * @method static \StyleModel|null findOneByBgcolor($val, $opt=array())
 * @method static \StyleModel|null findOneByBgimage($val, $opt=array())
 * @method static \StyleModel|null findOneByBgposition($val, $opt=array())
 * @method static \StyleModel|null findOneByBgrepeat($val, $opt=array())
 * @method static \StyleModel|null findOneByShadowsize($val, $opt=array())
 * @method static \StyleModel|null findOneByShadowcolor($val, $opt=array())
 * @method static \StyleModel|null findOneByGradientAngle($val, $opt=array())
 * @method static \StyleModel|null findOneByGradientColors($val, $opt=array())
 * @method static \StyleModel|null findOneByBorder($val, $opt=array())
 * @method static \StyleModel|null findOneByBorderwidth($val, $opt=array())
 * @method static \StyleModel|null findOneByBorderstyle($val, $opt=array())
 * @method static \StyleModel|null findOneByBordercolor($val, $opt=array())
 * @method static \StyleModel|null findOneByBorderradius($val, $opt=array())
 * @method static \StyleModel|null findOneByBordercollapse($val, $opt=array())
 * @method static \StyleModel|null findOneByBorderspacing($val, $opt=array())
 * @method static \StyleModel|null findOneByFont($val, $opt=array())
 * @method static \StyleModel|null findOneByFontfamily($val, $opt=array())
 * @method static \StyleModel|null findOneByFontsize($val, $opt=array())
 * @method static \StyleModel|null findOneByFontcolor($val, $opt=array())
 * @method static \StyleModel|null findOneByLineheight($val, $opt=array())
 * @method static \StyleModel|null findOneByFontstyle($val, $opt=array())
 * @method static \StyleModel|null findOneByTexttransform($val, $opt=array())
 * @method static \StyleModel|null findOneByTextindent($val, $opt=array())
 * @method static \StyleModel|null findOneByLetterspacing($val, $opt=array())
 * @method static \StyleModel|null findOneByWordspacing($val, $opt=array())
 * @method static \StyleModel|null findOneByList($val, $opt=array())
 * @method static \StyleModel|null findOneByListstyletype($val, $opt=array())
 * @method static \StyleModel|null findOneByListstyleimage($val, $opt=array())
 * @method static \StyleModel|null findOneByOwn($val, $opt=array())
 * @method static \StyleModel|null findOneByInvisible($val, $opt=array())
 *
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByPid($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findBySorting($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findBySelector($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByCategory($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByComment($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findBySize($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByWidth($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByHeight($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByMinwidth($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByMinheight($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByMaxwidth($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByMaxheight($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByPositioning($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByTrbl($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByPosition($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByFloating($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByClear($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByOverflow($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByDisplay($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByAlignment($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByMargin($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByPadding($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByAlign($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByVerticalalign($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByTextalign($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByWhitespace($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByBackground($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByBgcolor($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByBgimage($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByBgposition($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByBgrepeat($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByShadowsize($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByShadowcolor($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByGradientAngle($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByGradientColors($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByBorder($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByBorderwidth($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByBorderstyle($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByBordercolor($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByBorderradius($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByBordercollapse($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByBorderspacing($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByFont($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByFontfamily($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByFontsize($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByFontcolor($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByLineheight($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByFontstyle($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByTexttransform($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByTextindent($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByLetterspacing($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByWordspacing($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByList($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByListstyletype($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByListstyleimage($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByOwn($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findByInvisible($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\StyleModel[]|\StyleModel|null findAll($opt=array())
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
