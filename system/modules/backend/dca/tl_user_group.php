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
 * Table tl_user_group
 */
$GLOBALS['TL_DCA']['tl_user_group'] = array
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
			'panelLayout'             => 'search,filter,limit',
		),
		'label' => array
		(
			'fields'                  => array('name'),
			'format'                  => '%s',
			'label_callback'          => array('tl_user_group', 'addIcon')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_user_group']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_user_group']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_user_group']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_user_group']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'name;modules;pagemounts,alpty;filemounts,fop;forms;alexf;disable,start,stop',
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user_group']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'unique'=>true, 'maxlength'=>255)
		),
		'modules' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user_group']['modules'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'options_callback'        => array('tl_user_group', 'getModules'),
			'reference'               => &$GLOBALS['TL_LANG']['MOD'],
			'eval'                    => array('multiple'=>true, 'helpwizard'=>true)
		),
		'pagemounts' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user_group']['pagemounts'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'eval'                    => array('fieldType'=>'checkbox')
		),
		'alpty' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user_group']['alpty'],
			'default'                 => array('regular', 'redirect', 'forward'),
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'options'                 => array_keys($GLOBALS['TL_PTY']),
			'reference'               => &$GLOBALS['TL_LANG']['PTY'],
			'eval'                    => array('multiple'=>true, 'helpwizard'=>true)
		),
		'filemounts' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user_group']['filemounts'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'checkbox')
		),
		'fop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['FOP']['fop'],
			'exclude'                 => true,
			'default'                 => array('f1', 'f2', 'f3'),
			'inputType'               => 'checkbox',
			'options'                 => array('f1', 'f2', 'f3', 'f4', 'f5'),
			'reference'               => &$GLOBALS['TL_LANG']['FOP'],
			'eval'                    => array('multiple'=>true)
		),
		'forms' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user_group']['forms'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_form.title',
			'eval'                    => array('multiple'=>true)
		),
		'alexf' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user_group']['alexf'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'options_callback'        => array('tl_user_group', 'getExcludedFields'),
			'eval'                    => array('multiple'=>true, 'size'=>36)
		),
		'disable' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user_group']['disable'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox'
		),
		'start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user_group']['start'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>10, 'rgxp'=>'date', 'datepicker'=>$this->getDatePickerString())
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user_group']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>10, 'rgxp'=>'date', 'datepicker'=>$this->getDatePickerString())
		)
	)
);


/**
 * Class tl_user_group
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_user_group extends Backend
{

	/**
	 * Add an image to each record
	 * @param array
	 * @param string
	 * @return string
	 */
	public function addIcon($row, $label)
	{
		$image = 'group';

		if ($row['disable'] || strlen($row['start']) && $row['start'] > time() || strlen($row['stop']) && $row['stop'] < time())
		{
			$image .= '_';
		}

		return sprintf('<div class="list_icon" style="background-image:url(\'system/themes/%s/images/%s.gif\');">%s</div>', $this->getTheme(), $image, $label);
	}


	/**
	 * Return all modules except profile modules
	 * @return array
	 */
	public function getModules()
	{
		$arrModules = array();

		foreach ($GLOBALS['BE_MOD'] as $k=>$v)
		{
			if (count($v) && $k != 'profile')
			{
				$arrModules = array_merge($arrModules, array_keys($v));
			}
		}

		return $arrModules;
	}


	/**
	 * Return all excluded fields as HTML drop down menu
	 * @return array
	 */
	public function getExcludedFields()
	{
		$included = array();

		foreach ($this->Config->getActiveModules() as $strModule)
		{
			$strDir = sprintf('%s/system/modules/%s/dca/', TL_ROOT, $strModule);

			if (!is_dir($strDir))
			{
				continue;
			}

			foreach (scan($strDir) as $strFile)
			{
				if (in_array($strFile, $included))
				{
					continue;
				}

				$strFile = str_replace('.php', '', $strFile);
				$included[] = $strFile;

				$this->loadLanguageFile($strFile);
				$this->loadDataContainer($strFile);
			}
		}

		$arrReturn = array();

		// Get all excluded fields
		foreach ($GLOBALS['TL_DCA'] as $k=>$v)
		{
			if (is_array($v['fields']))
			{
				foreach ($v['fields'] as $kk=>$vv)
				{
					if (isset($vv['exclude']))
					{
						$arrReturn[$k][specialchars($k.'::'.$kk)] = (strlen($vv['label'][0]) ? $vv['label'][0] : $kk);
					}
				}
			}
		}

		ksort($arrReturn);
		return $arrReturn;
	}
}

?>