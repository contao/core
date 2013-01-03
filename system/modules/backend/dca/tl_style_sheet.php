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
 * Table tl_style_sheet
 */
$GLOBALS['TL_DCA']['tl_style_sheet'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_theme',
		'ctable'                      => array('tl_style'),
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_style_sheet', 'checkPermission'),
			array('tl_style_sheet', 'updateStyleSheet')
		),
		'oncopy_callback' => array
		(
			array('tl_style_sheet', 'scheduleUpdate')
		),
		'onsubmit_callback' => array
		(
			array('tl_style_sheet', 'scheduleUpdate')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('name'),
			'panelLayout'             => 'filter,search,limit',
			'headerFields'            => array('name', 'author', 'tstamp'),
			'child_record_callback'   => array('tl_style_sheet', 'listStyleSheet'),
			'child_record_class'      => 'no_padding'
		),
		'global_operations' => array
		(
			'import' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_style_sheet']['import'],
				'href'                => 'key=import',
				'class'               => 'header_css_import',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),
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
				'label'               => &$GLOBALS['TL_LANG']['tl_style_sheet']['edit'],
				'href'                => 'table=tl_style',
				'icon'                => 'edit.gif',
				'attributes'          => 'class="contextmenu"'
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_style_sheet']['editheader'],
				'href'                => 'table=tl_style_sheet&amp;act=edit',
				'icon'                => 'header.gif',
				'button_callback'     => array('tl_style_sheet', 'editHeader'),
				'attributes'          => 'class="edit-header"'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_style_sheet']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_style_sheet']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_style_sheet']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_style_sheet']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{title_legend},name,cc;{media_legend},media,mediaQuery;{vars_legend},vars'
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style_sheet']['name'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'search'                  => true,
			'flag'                    => 1,
			'eval'                    => array('mandatory'=>true, 'unique'=>true, 'rgxp'=>'alnum', 'maxlength'=>64, 'spaceToUnderscore'=>true, 'tl_class'=>'w50')
		),
		'cc' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style_sheet']['cc'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'search'                  => true,
			'eval'                    => array('decodeEntities'=>true, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_style_sheet', 'sanitizeCc')
			)
		),
		'media' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style_sheet']['media'],
			'inputType'               => 'checkbox',
			'exclude'                 => true,
			'filter'                  => true,
			'options'                 => array('all', 'aural', 'braille', 'embossed', 'handheld', 'print', 'projection', 'screen', 'tty', 'tv'),
			'eval'                    => array('multiple'=>true, 'mandatory'=>true, 'tl_class'=>'clr')
		),
		'mediaQuery' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style_sheet']['mediaQuery'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'search'                  => true,
			'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'clr long')
		),
		'vars' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style_sheet']['vars'],
			'inputType'               => 'keyValueWizard',
			'exclude'                 => true
		),
		'source' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style_sheet']['source'],
			'eval'                    => array('fieldType'=>'checkbox', 'files'=>true, 'filesOnly'=>true, 'extensions'=>'css', 'class'=>'mandatory')
		)
	)
);


/**
 * Class tl_style_sheet
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class tl_style_sheet extends Backend
{

	/**
	 * Import the back end user object
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
			$this->log('Not enough permissions to access the style sheets module', 'tl_style_sheets checkPermission', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
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
	 * This method is triggered when a single style sheet or multiple style
	 * sheets are modified (edit/editAll) or duplicated (copy/copyAll).
	 * @param mixed
	 */
	public function scheduleUpdate($id)
	{
		// The onsubmit_callback passes a DataContainer object
		if (is_object($id))
		{
			$id = $id->id;
		}

		// Return if there is no ID 
		if (!$id || $this->Input->get('act') == 'copy')
		{
			return;
		}

		// Store the ID in the session
		$session = $this->Session->get('style_sheet_updater');
		$session[] = $id;
		$this->Session->set('style_sheet_updater', array_unique($session));
	}


	/**
	 * List a style sheet
	 * @param array
	 * @return string
	 */
	public function listStyleSheet($row)
	{
		$media = deserialize($row['media']);

		if (!is_array($media) || empty($media))
		{
			return '<div style="float:left">'. $row['name'] ."</div>\n";
		}

		return '<div style="float:left">'. $row['name'] .' <span style="color:#b3b3b3;padding-left:3px">['. implode(', ', $media) .']</span>' . "</div>\n";
	}


	/**
	 * Sanitize the conditional comments field
	 * @param mixed
	 * @return string
	 */
	public function sanitizeCc($varValue)
	{
		if ($varValue != '')
		{
			$varValue = str_replace(array('<!--[', ']>'), '', $varValue);
		}

		return $varValue;
	}


	/**
	 * Return the edit header button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editHeader($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || count(preg_grep('/^tl_style_sheet::/', $this->User->alexf)) > 0) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : '';
	}
}

?>