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
 * @method static $this findById($id, $opt=array())
 * @method static $this findByPk($id, $opt=array())
 * @method static $this findByIdOrAlias($val, $opt=array())
 * @method static $this findOneBy($col, $val, $opt=array())
 * @method static $this findOneByPid($val, $opt=array())
 * @method static $this findOneBySorting($val, $opt=array())
 * @method static $this findOneByTstamp($val, $opt=array())
 * @method static $this findOneBySelector($val, $opt=array())
 * @method static $this findOneByCategory($val, $opt=array())
 * @method static $this findOneByComment($val, $opt=array())
 * @method static $this findOneBySize($val, $opt=array())
 * @method static $this findOneByWidth($val, $opt=array())
 * @method static $this findOneByHeight($val, $opt=array())
 * @method static $this findOneByMinwidth($val, $opt=array())
 * @method static $this findOneByMinheight($val, $opt=array())
 * @method static $this findOneByMaxwidth($val, $opt=array())
 * @method static $this findOneByMaxheight($val, $opt=array())
 * @method static $this findOneByPositioning($val, $opt=array())
 * @method static $this findOneByTrbl($val, $opt=array())
 * @method static $this findOneByPosition($val, $opt=array())
 * @method static $this findOneByFloating($val, $opt=array())
 * @method static $this findOneByClear($val, $opt=array())
 * @method static $this findOneByOverflow($val, $opt=array())
 * @method static $this findOneByDisplay($val, $opt=array())
 * @method static $this findOneByAlignment($val, $opt=array())
 * @method static $this findOneByMargin($val, $opt=array())
 * @method static $this findOneByPadding($val, $opt=array())
 * @method static $this findOneByAlign($val, $opt=array())
 * @method static $this findOneByVerticalalign($val, $opt=array())
 * @method static $this findOneByTextalign($val, $opt=array())
 * @method static $this findOneByWhitespace($val, $opt=array())
 * @method static $this findOneByBackground($val, $opt=array())
 * @method static $this findOneByBgcolor($val, $opt=array())
 * @method static $this findOneByBgimage($val, $opt=array())
 * @method static $this findOneByBgposition($val, $opt=array())
 * @method static $this findOneByBgrepeat($val, $opt=array())
 * @method static $this findOneByShadowsize($val, $opt=array())
 * @method static $this findOneByShadowcolor($val, $opt=array())
 * @method static $this findOneByGradientAngle($val, $opt=array())
 * @method static $this findOneByGradientColors($val, $opt=array())
 * @method static $this findOneByBorder($val, $opt=array())
 * @method static $this findOneByBorderwidth($val, $opt=array())
 * @method static $this findOneByBorderstyle($val, $opt=array())
 * @method static $this findOneByBordercolor($val, $opt=array())
 * @method static $this findOneByBorderradius($val, $opt=array())
 * @method static $this findOneByBordercollapse($val, $opt=array())
 * @method static $this findOneByBorderspacing($val, $opt=array())
 * @method static $this findOneByFont($val, $opt=array())
 * @method static $this findOneByFontfamily($val, $opt=array())
 * @method static $this findOneByFontsize($val, $opt=array())
 * @method static $this findOneByFontcolor($val, $opt=array())
 * @method static $this findOneByLineheight($val, $opt=array())
 * @method static $this findOneByFontstyle($val, $opt=array())
 * @method static $this findOneByTexttransform($val, $opt=array())
 * @method static $this findOneByTextindent($val, $opt=array())
 * @method static $this findOneByLetterspacing($val, $opt=array())
 * @method static $this findOneByWordspacing($val, $opt=array())
 * @method static $this findOneByList($val, $opt=array())
 * @method static $this findOneByListstyletype($val, $opt=array())
 * @method static $this findOneByListstyleimage($val, $opt=array())
 * @method static $this findOneByOwn($val, $opt=array())
 * @method static $this findOneByInvisible($val, $opt=array())
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
