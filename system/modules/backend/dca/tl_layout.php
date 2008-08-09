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
 * Table tl_layout
 */
$GLOBALS['TL_DCA']['tl_layout'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('name'),
			'flag'                    => 1,
			'panelLayout'             => 'filter;search,limit'
		),
		'label' => array
		(
			'fields'                  => array('name', 'fallback'),
			'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>'
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
				'label'               => &$GLOBALS['TL_LANG']['tl_layout']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_layout']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_layout']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_layout']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('cols', 'header', 'footer', 'static'),
		'default'                     => 'name,fallback;template,mootools,doctype;stylesheet,newsfeeds,calendarfeeds;onload,urchinId,head;cols',
		'1cl'                         => 'name,fallback;template,mootools,doctype;stylesheet,newsfeeds,calendarfeeds;onload,urchinId,head;cols;header;footer;sections,sPosition;modules;static',
		'2cll'                        => 'name,fallback;template,mootools,doctype;stylesheet,newsfeeds,calendarfeeds;onload,urchinId,head;cols;widthLeft;header;footer;sections,sPosition;modules;static',
		'2clr'                        => 'name,fallback;template,mootools,doctype;stylesheet,newsfeeds,calendarfeeds;onload,urchinId,head;cols;widthRight;header;footer;sections,sPosition;modules;static',
		'3cl'                         => 'name,fallback;template,mootools,doctype;stylesheet,newsfeeds,calendarfeeds;onload,urchinId,head;cols;widthLeft,widthRight;header;footer;sections,sPosition;modules;static'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'header'                      => 'headerHeight',
		'footer'                      => 'footerHeight',
		'static'                      => 'width,align',
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['name'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'search'                  => true,
			'eval'                    => array('mandatory'=>true, 'unique'=>true, 'maxlength'=>255)
		),
		'fallback' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['fallback'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('fallback'=>true)
		),
		'template' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['template'],
			'exclude'                 => true,
			'filter'                  => true,
			'search'                  => true,
			'inputType'               => 'select',
			'options'                 => $this->getTemplateGroup('fe_')
		),
		'mootools' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['mootools'],
			'exclude'                 => true,
			'filter'                  => true,
			'search'                  => true,
			'inputType'               => 'select',
			'options'                 => $this->getTemplateGroup('moo_'),
			'eval'                    => array('includeBlankOption'=>true)
		),
		'doctype' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['doctype'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options'                 => array('xhtml_strict', 'xhtml_trans'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_layout']
		),
		'urchinId' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['urchinId'],
			'exclude'                 => true,
			'filter'                  => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=>true)
		),
		'stylesheet' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['stylesheet'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkboxWizard',
			'foreignKey'              => 'tl_style_sheet.name',
			'eval'                    => array('multiple'=>true)
		),
		'newsfeeds' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['newsfeeds'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'options_callback'        => array('tl_layout', 'getNewsfeeds'),
			'eval'                    => array('multiple'=>true)
		),
		'calendarfeeds' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['calendarfeeds'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'options_callback'        => array('tl_layout', 'getCalendarfeeds'),
			'eval'                    => array('multiple'=>true)
		),
		'onload' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['onload'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255)
		),
		'head' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['head'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('style'=>'height:80px;', 'preserveTags'=>true)
		),
		'cols' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['cols'],
			'default'                 => '2cll',
			'exclude'                 => true,
			'inputType'               => 'radioTable',
			'options'                 => array('1cl', '2cll', '2clr', '3cl'),
			'eval'                    => array('helpwizard'=>true, 'cols'=>4, 'submitOnChange'=>true),
			'reference'               => &$GLOBALS['TL_LANG']['tl_layout']
		),
		'widthLeft' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['widthLeft'],
			'exclude'                 => true,
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit')
		),
		'widthRight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['widthRight'],
			'exclude'                 => true,
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit')
		),
		'header' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['header'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'headerHeight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['headerHeight'],
			'exclude'                 => true,
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit')
		),
		'footer' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['footer'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'footerHeight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['footerHeight'],
			'exclude'                 => true,
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit')
		),
		'static' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['static'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'width' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['width'],
			'exclude'                 => true,
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit')
		),
		'align' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['align'],
			'default'                 => 'center',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('left', 'center', 'right'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC']
		),
		'sections' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['sections'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'checkbox',
			'options'                 => trimsplit(',', $GLOBALS['TL_CONFIG']['customSections']),
			'eval'                    => array('multiple'=>true)
		),
		'sPosition' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['sPosition'],
			'default'                 => 'main',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('before', 'main', 'after'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_layout']
		),
		'modules' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['modules'],
			'exclude'                 => true,
			'inputType'               => 'moduleWizard'
		)
	)
);


/**
 * Class tl_layout
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_layout extends Backend
{

	/**
	 * Return all news archives with XML feeds
	 * @return array
	 */
	public function getNewsfeeds()
	{
		$objFeed = $this->Database->execute("SELECT id, title FROM tl_news_archive WHERE makeFeed=1");

		if ($objFeed->numRows < 1)
		{
			return array();
		}

		$return = array();

		while ($objFeed->next())
		{
			$return[$objFeed->id] = $objFeed->title;
		}

		return $return;
	}


	/**
	 * Return all calendars with XML feeds
	 * @return array
	 */
	public function getCalendarfeeds()
	{
		$objFeed = $this->Database->execute("SELECT id, title FROM tl_calendar WHERE makeFeed=1");

		if ($objFeed->numRows < 1)
		{
			return array();
		}

		$return = array();

		while ($objFeed->next())
		{
			$return[$objFeed->id] = $objFeed->title;
		}

		return $return;
	}
}

?>