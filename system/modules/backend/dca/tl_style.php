<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
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
			array('tl_style', 'adjustCategory'),
			array('tl_style', 'updateStyleSheet')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'filter'                  => true,
			'fields'                  => array('sorting'),
			'panelLayout'             => 'search,filter,limit',
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
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
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
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_style']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_style']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
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
		'__selector__'                => array('size', 'alignment', 'background', 'border', 'font', 'list'),
		'default'                     => 'comment,selector,category;size;alignment;background;border;font;list;own',
	),

	// Subpalettes
	'subpalettes' => array
	(
		'size'                        => 'width,height,trbl,position,overflow,floating,clear,display',
		'alignment'                   => 'margin,padding,align,textalign,verticalalign',
		'background'                  => 'bgcolor,bgimage,bgposition,bgrepeat',
		'border'                      => 'borderwidth,borderstyle,bordercolor,bordercollapse',
		'font'                        => 'fontfamily,fontstyle,fontsize,fontcolor,lineheight,whitespace',
		'list'                        => 'liststyletype,liststyleimage'
	),

	// Fields
	'fields' => array
	(
		'comment' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['comment'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'decodeEntities'=>true)
		),
		'selector' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['selector'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'decodeEntities'=>true)
		),
		'category' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['category'],
			'search'                  => true,
			'filter'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>32, 'decodeEntities'=>true, 'doNotCopy'=>true),
			'load_callback' => array
			(
				array('tl_style', 'checkCategory')
			)
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
			'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'alnum')
		),
		'height' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['height'],
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'alnum')
		),
		'trbl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['trbl'],
			'inputType'               => 'trbl',
			'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'alnum')
		),
		'position' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['position'],
			'inputType'               => 'select',
			'options'                 => array('absolute', 'relative', 'static', 'fixed'),
			'eval'                    => array('includeBlankOption'=>true)
		),
		'overflow' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['overflow'],
			'inputType'               => 'select',
			'options'                 => array('auto', 'hidden', 'scroll', 'visible'),
			'eval'                    => array('includeBlankOption'=>true)
		),
		'floating' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['floating'],
			'inputType'               => 'select',
			'options'                 => array('left', 'right', 'none'),
			'eval'                    => array('includeBlankOption'=>true)
		),
		'clear' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['clear'],
			'inputType'               => 'select',
			'options'                 => array('both', 'left', 'right', 'none'),
			'eval'                    => array('includeBlankOption'=>true)
		),
		'display' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['display'],
			'inputType'               => 'select',
			'options'                 => array('block', 'inline', 'list-item', 'run-in', 'compact', 'none', 'table', 'inline-table', 'table-row', 'table-row-group', 'table-header-group', 'table-footer-group', 'table-column', 'table-column-group', 'table-caption'),
			'eval'                    => array('includeBlankOption'=>true)
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
			'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'alnum')
		),
		'padding' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['padding'],
			'inputType'               => 'trbl',
			'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit')
		),
		'align' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['align'],
			'inputType'               => 'select',
			'options'                 => array('left', 'center', 'right'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('includeBlankOption'=>true)
		),
		'textalign' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['textalign'],
			'inputType'               => 'select',
			'options'                 => array('left', 'center', 'right', 'justify'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('includeBlankOption'=>true)
		),
		'verticalalign' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['verticalalign'],
			'inputType'               => 'select',
			'options'                 => array('top', 'text-top', 'middle', 'text-bottom', 'baseline', 'bottom'),
			'eval'                    => array('includeBlankOption'=>true)
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
			'eval'                    => array('maxlength'=>6, 'rgxp'=>'alnum'),
			'wizard' => array
			(
				array('tl_style', 'colorPicker')
			)
		),
		'bgimage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['bgimage'],
			'inputType'               => 'text'
		),
		'bgposition' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['bgposition'],
			'inputType'               => 'select',
			'options'                 => array('left top', 'left center', 'left bottom', 'center top', 'center center', 'center bottom', 'right top', 'right center', 'right bottom'),
			'eval'                    => array('includeBlankOption'=>true)
		),
		'bgrepeat' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['bgrepeat'],
			'inputType'               => 'select',
			'options'                 => array('repeat-x', 'repeat-y', 'no-repeat'),
			'eval'                    => array('includeBlankOption'=>true)
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
			'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit')
		),
		'borderstyle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['borderstyle'],
			'inputType'               => 'select',
			'options'                 => array('solid', 'dotted', 'dashed', 'double', 'groove', 'ridge', 'inset', 'outset', 'hidden'),
			'eval'                    => array('includeBlankOption'=>true)
		),
		'bordercolor' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['bordercolor'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>6, 'rgxp'=>'alnum'),
			'wizard' => array
			(
				array('tl_style', 'colorPicker')
			)
		),
		'bordercollapse' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['bordercollapse'],
			'inputType'               => 'select',
			'options'                 => array('collapse', 'separate'),
			'eval'                    => array('includeBlankOption'=>true)
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
			'eval'                    => array('maxlength'=>255)
		),
		'fontstyle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['fontstyle'],
			'inputType'               => 'checkbox',
			'options'                 => array('bold', 'italic', 'normal', 'underline', 'notUnderlined', 'line-through', 'overline', 'small-caps'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_style'],
			'eval'                    => array('multiple'=>true)
		),
		'fontsize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['fontsize'],
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit')
		),
		'fontcolor' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['fontcolor'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>6, 'rgxp'=>'alnum'),
			'wizard' => array
			(
				array('tl_style', 'colorPicker')
			)
		),
		'lineheight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['lineheight'],
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit')
		),
		'whitespace' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['whitespace'],
			'inputType'               => 'checkbox'
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
			'eval'                    => array('includeBlankOption'=>true)
		),
		'liststyleimage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['liststyleimage'],
			'inputType'               => 'text'
		),
		'own' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['own'],
			'inputType'               => 'textarea',
			'eval'                    => array('decodeEntities'=>true)
		)
	)
);


/**
 * Class tl_style
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_style extends Backend
{

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
	 * Automatically adjust the category if a record is being cut
	 */
	public function adjustCategory()
	{
		if ($this->Input->get('act') != 'cut')
		{
			return;
		}

		$filter = $this->Session->get('filter');
		$category = $filter['tl_style_' . CURRENT_ID]['category'];

		// Return if no category is set
		if (!strlen($category))
		{
			return;
		}

		$this->import('Database');

		// Update the new record
		$this->Database->prepare("UPDATE tl_style SET category=? WHERE id=?")
					   ->execute($category, $this->Input->get('id'));
	}


	/**
	 * Return the color picker wizard
	 * @param object
	 * @return string
	 */
	public function colorPicker(DataContainer $dc)
	{
		return ' ' . $this->generateImage('pickcolor.gif', $GLOBALS['TL_LANG']['MSC']['colorpicker'], 'style="vertical-align:top; cursor:pointer;" onclick="Backend.pickColor(\'ctrl_'.$dc->field.'\')"');
	}


	/**
	 * Update style sheet
	 */
	public function updateStyleSheet()
	{
		$this->import('StyleSheets');
		$this->StyleSheets->updateStyleSheet(CURRENT_ID);
	}
}

?>