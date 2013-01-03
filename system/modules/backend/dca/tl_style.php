<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Table tl_style
 */
$GLOBALS['TL_DCA']['tl_style'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_style_sheet',
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_style', 'checkPermission'),
			array('tl_style', 'updateStyleSheet')
		),
		'oncopy_callback' => array
		(
			array('tl_style', 'scheduleUpdate')
		),
		'oncut_callback' => array
		(
			array('tl_style', 'scheduleUpdate')
		),
		'ondelete_callback' => array
		(
			array('tl_style', 'scheduleUpdate')
		),
		'onsubmit_callback' => array
		(
			array('tl_style', 'scheduleUpdate')
		),
		'onrestore_callback' => array
		(
			array('tl_style', 'updateAfterRestore')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('sorting'),
			'panelLayout'             => 'filter,search,limit',
			'headerFields'            => array('name', 'tstamp', 'media'),
			'child_record_callback'   => array('StyleSheets', 'compileDefinition')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_style']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_style']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_style']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_style']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_style']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s,\'tl_style\')"',
				'button_callback'     => array('tl_style', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_style']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('size', 'positioning', 'alignment', 'background', 'border', 'font', 'list'),
		'default'                     => '{selector_legend},selector,category,comment;{size_legend},size;{position_legend},positioning;{align_legend},alignment;{background_legend},background;{border_legend},border;{font_legend},font;{list_legend},list;{custom_legend:hide},own',
	),

	// Subpalettes
	'subpalettes' => array
	(
		'size'                        => 'width,height,minwidth,minheight,maxwidth,maxheight',
		'positioning'                 => 'trbl,position,floating,clear,overflow,display',
		'alignment'                   => 'margin,padding,align,verticalalign,textalign',
		'background'                  => 'bgcolor,bgimage,bgposition,bgrepeat,shadowsize,shadowcolor,gradientAngle,gradientColors',
		'border'                      => 'borderwidth,borderstyle,bordercolor,borderradius,bordercollapse,borderspacing',
		'font'                        => 'fontfamily,fontsize,fontcolor,lineheight,fontstyle,whitespace,texttransform,textindent,letterspacing,wordspacing',
		'list'                        => 'liststyletype,liststyleimage'
	),

	// Fields
	'fields' => array
	(
		'invisible' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['invisible']
		),
		'selector' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['selector'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'long')
		),
		'category' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['category'],
			'search'                  => true,
			'filter'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>32, 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'load_callback' => array
			(
				array('tl_style', 'checkCategory')
			)
		),
		'comment' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['comment'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'w50')
		),
		'size' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['size'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'width' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['width'],
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit_auto_inherit', 'maxlength' => 20, 'tl_class'=>'w50')
		),
		'height' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['height'],
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit_auto_inherit', 'maxlength' => 20, 'tl_class'=>'w50')
		),
		'minwidth' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['minwidth'],
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit_inherit', 'maxlength' => 20, 'tl_class'=>'w50')
		),
		'minheight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['minheight'],
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit_inherit', 'maxlength' => 20, 'tl_class'=>'w50')
		),
		'maxwidth' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['maxwidth'],
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit_inherit_none', 'maxlength' => 20, 'tl_class'=>'w50')
		),
		'maxheight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['maxheight'],
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit_inherit_none', 'maxlength' => 20, 'tl_class'=>'w50')
		),
		'positioning' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['positioning'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'trbl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['trbl'],
			'inputType'               => 'trbl',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit_auto_inherit', 'tl_class'=>'w50')
		),
		'position' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['position'],
			'inputType'               => 'select',
			'options'                 => array('absolute', 'relative', 'static', 'fixed'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'floating' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['floating'],
			'inputType'               => 'select',
			'options'                 => array('left', 'right', 'none'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'clear' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['clear'],
			'inputType'               => 'select',
			'options'                 => array('both', 'left', 'right', 'none'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'overflow' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['overflow'],
			'inputType'               => 'select',
			'options'                 => array('auto', 'hidden', 'scroll', 'visible'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'display' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['display'],
			'inputType'               => 'select',
			'options'                 => array('block', 'inline', 'inline-block', 'list-item', 'run-in', 'compact', 'none', 'table', 'inline-table', 'table-row', 'table-cell', 'table-row-group', 'table-header-group', 'table-footer-group', 'table-column', 'table-column-group', 'table-caption'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'alignment' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['alignment'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'margin' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['margin'],
			'inputType'               => 'trbl',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit_auto_inherit', 'tl_class'=>'w50')
		),
		'padding' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['padding'],
			'inputType'               => 'trbl',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit_inherit', 'tl_class'=>'w50')
		),
		'align' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['align'],
			'inputType'               => 'select',
			'options'                 => array('left', 'center', 'right'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'verticalalign' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['verticalalign'],
			'inputType'               => 'select',
			'options'                 => array('top', 'text-top', 'middle', 'text-bottom', 'baseline', 'bottom'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'textalign' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['textalign'],
			'inputType'               => 'select',
			'options'                 => array('left', 'center', 'right', 'justify'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'background' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['background'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'bgcolor' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['bgcolor'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>6, 'multiple'=>true, 'size'=>2, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'),
			'wizard' => array
			(
				array('tl_style', 'colorPicker')
			)
		),
		'bgimage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['bgimage'],
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50 wizard'),
			'wizard' => array
			(
				array('tl_style', 'filePicker')
			)
		),
		'bgposition' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['bgposition'],
			'inputType'               => 'select',
			'options'                 => array('left top', 'left center', 'left bottom', 'center top', 'center center', 'center bottom', 'right top', 'right center', 'right bottom'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'bgrepeat' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['bgrepeat'],
			'inputType'               => 'select',
			'options'                 => array('repeat', 'repeat-x', 'repeat-y', 'no-repeat'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'shadowsize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['shadowsize'],
			'inputType'               => 'trbl',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit_', 'tl_class'=>'w50')
		),
		'shadowcolor' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['shadowcolor'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>6, 'multiple'=>true, 'size'=>2, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'),
			'wizard' => array
			(
				array('tl_style', 'colorPicker')
			)
		),
		'gradientAngle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['gradientAngle'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>32, 'tl_class'=>'w50')
		),
		'gradientColors' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['gradientColors'],
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>4, 'decodeEntities'=>true, 'tl_class'=>'w50')
		),
		'border' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['border'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'borderwidth' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['borderwidth'],
			'inputType'               => 'trbl',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit_inherit', 'tl_class'=>'w50')
		),
		'borderstyle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['borderstyle'],
			'inputType'               => 'select',
			'options'                 => array('solid', 'dotted', 'dashed', 'double', 'groove', 'ridge', 'inset', 'outset', 'hidden'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'bordercolor' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['bordercolor'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>6, 'multiple'=>true, 'size'=>2, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'),
			'wizard' => array
			(
				array('tl_style', 'colorPicker')
			)
		),
		'borderradius' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['borderradius'],
			'inputType'               => 'trbl',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit_', 'tl_class'=>'w50')
		),
		'bordercollapse' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['bordercollapse'],
			'inputType'               => 'select',
			'options'                 => array('collapse', 'separate'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'borderspacing' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['borderspacing'],
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit_inherit', 'maxlength' => 20, 'tl_class'=>'w50')
		),
		'font' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['font'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'fontfamily' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['fontfamily'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50')
		),
		'fontsize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['fontsize'],
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit_inherit', 'maxlength' => 20, 'tl_class'=>'w50')
		),
		'fontcolor' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['fontcolor'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>6, 'multiple'=>true, 'size'=>2, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'),
			'wizard' => array
			(
				array('tl_style', 'colorPicker')
			)
		),
		'lineheight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['lineheight'],
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit_normal_inherit', 'maxlength' => 20, 'tl_class'=>'w50')
		),
		'fontstyle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['fontstyle'],
			'inputType'               => 'checkbox',
			'options'                 => array('bold', 'italic', 'normal', 'underline', 'notUnderlined', 'line-through', 'overline', 'small-caps'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_style'],
			'eval'                    => array('multiple'=>true, 'tl_class'=>'clr')
		),
		'whitespace' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['whitespace'],
			'inputType'               => 'checkbox'
		),
		'texttransform' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['texttransform'],
			'inputType'               => 'select',
			'options'                 => array('uppercase', 'lowercase', 'capitalize', 'none'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_style'],
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'textindent' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['textindent'],
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit_inherit', 'maxlength' => 20, 'tl_class'=>'w50')
		),
		'letterspacing' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['letterspacing'],
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit_normal_inherit', 'maxlength' => 20, 'tl_class'=>'w50')
		),
		'wordspacing' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['wordspacing'],
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit_normal_inherit', 'maxlength' => 20, 'tl_class'=>'w50')
		),
		'list' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['list'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'liststyletype' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['liststyletype'],
			'inputType'               => 'select',
			'options'                 => array('disc', 'circle', 'square', 'decimal', 'upper-roman', 'lower-roman', 'upper-alpha', 'lower-alpha', 'none'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_style'],
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'liststyleimage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['liststyleimage'],
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50 wizard'),
			'wizard' => array
			(
				array('tl_style', 'filePicker')
			)
		),
		'own' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['own'],
			'inputType'               => 'textarea',
			'eval'                    => array('decodeEntities'=>true, 'style'=>'height:120px;')
		)
	)
);


/**
 * Class tl_style
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class tl_style extends Backend
{

	/**
	 * Add the mooRainbow scripts to the page
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Check permissions to edit the table
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		if (!$this->User->hasAccess('css', 'themes'))
		{
			$this->log('Not enough permissions to access the style sheets module', 'tl_style checkPermission', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
	}


	/**
	 * Automatically set the category if not set
	 * @param mixed
	 * @return string
	 */
	public function checkCategory($varValue)
	{
		// Do not change the value if it has been set already
		if (strlen($varValue) || $this->Input->post('FORM_SUBMIT') == 'tl_style')
		{
			return $varValue;
		}

		$key = 'tl_style_' . CURRENT_ID;
		$filter = $this->Session->get('filter');

		// Return the current category
		if (strlen($filter[$key]['category']))
		{
			return $filter[$key]['category'];
		}

		return '';
	}


	/**
	 * Return the color picker wizard
	 * @param DataContainer
	 * @return string
	 */
	public function colorPicker(DataContainer $dc)
	{
		return ' ' . $this->generateImage('pickcolor.gif', $GLOBALS['TL_LANG']['MSC']['colorpicker'], 'style="vertical-align:top;cursor:pointer" id="moo_'.$dc->field.'"') . '
  <script>
  new MooRainbow("moo_'.$dc->field.'", {
    id:"ctrl_' . $dc->field . '_0",
    startColor:((cl = $("ctrl_' . $dc->field . '_0").value.hexToRgb(true)) ? cl : [255, 0, 0]),
    imgPath:"plugins/colorpicker/images/",
    onComplete: function(color) {
      $("ctrl_' . $dc->field . '_0").value = color.hex.replace("#", "");
    }
  });
  </script>';
	}


	/**
	 * Return the file picker wizard
	 * @param DataContainer
	 * @return string
	 */
	public function filePicker(DataContainer $dc)
	{
		$strField = 'ctrl_' . $dc->field . (($this->Input->get('act') == 'editAll') ? '_' . $dc->id : '');
		return ' ' . $this->generateImage('pickfile.gif', $GLOBALS['TL_LANG']['MSC']['filepicker'], 'style="vertical-align:top;cursor:pointer" onclick="Backend.pickFile(\'' . $strField . '\')"');
	}


	/**
	 * Check for modified style sheets and update them if necessary
	 */
	public function updateStyleSheet()
	{
		$session = $this->Session->get('style_sheet_updater');

		if (!is_array($session) || empty($session))
		{
			return;
		}

		$this->import('StyleSheets');

		foreach ($session as $id)
		{
			$this->StyleSheets->updateStyleSheet($id);
		}

		$this->Session->set('style_sheet_updater', null);
	}


	/**
	 * Schedule a style sheet update
	 * 
	 * This method is triggered when a single style or multiple styles are
	 * modified (edit/editAll), duplicated (copy/copyAll), moved (cut/cutAll)
	 * or deleted (delete/deleteAll).
	 */
	public function scheduleUpdate()
	{
		// Return if there is no ID 
		if (!CURRENT_ID || $this->Input->get('act') == 'copy' || $this->Environment->isAjaxRequest)
		{
			return;
		}

		// Store the ID in the session
		$session = $this->Session->get('style_sheet_updater');
		$session[] = CURRENT_ID;
		$this->Session->set('style_sheet_updater', array_unique($session));
	}


	/**
	 * Update a style sheet after a version has been restored
	 * @param integer
	 * @param string
	 * @param array
	 */
	public function updateAfterRestore($id, $table, $data)
	{
		if ($table != 'tl_style')
		{
			return;
		}

		// Update the timestamp of the style sheet
		$this->Database->prepare("UPDATE tl_style_sheet SET tstamp=? WHERE id=?")
					   ->execute(time(), $data['pid']);

		// Update the CSS file
		$this->import('StyleSheets');
		$this->StyleSheets->updateStyleSheet($data['pid']);
	}


	/**
	 * Return the "toggle visibility" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.$row['invisible'];

		if ($row['invisible'])
		{
			$icon = 'invisible.gif';
		}		

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Toggle the visibility of a format definition
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		$this->createInitialVersion('tl_style', $intId);
	
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_style']['fields']['invisible']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_style']['fields']['invisible']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_style SET tstamp=". time() .", invisible='" . ($blnVisible ? '' : 1) . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_style', $intId);
		$this->log('A new version of record "tl_style.id='.$intId.'" has been created'.$this->getParentRecords('tl_style', $intId), 'tl_style toggleVisibility()', TL_GENERAL);

		// Recreate the style sheet
		$objStylesheet = $this->Database->prepare("SELECT pid FROM tl_style WHERE id=?")
									    ->limit(1)
									    ->execute($intId);

		if ($objStylesheet->numRows)
		{
			$this->import('StyleSheets');
			$this->StyleSheets->updateStyleSheet($objStylesheet->pid);
		}
	}
}

?>