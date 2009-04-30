<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Table tl_user
 */
$GLOBALS['TL_DCA']['tl_user'] = array
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
			'fields'                  => array('name', 'username'),
			'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>',
			'label_callback'          => array('tl_user', 'addIcon')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_user']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_user']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_user']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_user']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'su' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_user']['su'],
				'href'                => 'key=su',
				'icon'                => 'su.gif',
				'button_callback'     => array('tl_user', 'switchUser')
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('inherit', 'admin'),
		'login'                       => '{name_legend},name,email;{backend_legend},language,showHelp,thumbnails,useRTE,oldBeTheme;{session_legend},session;{password_legend},password',
		'admin'                       => '{name_legend},username,name,email;{backend_legend:hide},language,showHelp,thumbnails,useRTE,oldBeTheme;{password_legend:hide},password;{admin_legend},admin;{account_legend},disable,start,stop',
		'default'                     => '{name_legend},username,name,email;{backend_legend:hide},language,showHelp,thumbnails,useRTE,oldBeTheme;{password_legend:hide},password;{admin_legend},admin;{groups_legend},groups,inherit;{account_legend},disable,start,stop',
		'group'                       => '{name_legend},username,name,email;{backend_legend:hide},language,showHelp,thumbnails,useRTE,oldBeTheme;{password_legend:hide},password;{admin_legend},admin;{groups_legend},groups,inherit;{account_legend},disable,start,stop',
		'extend'                      => '{name_legend},username,name,email;{backend_legend:hide},language,showHelp,thumbnails,useRTE,oldBeTheme;{password_legend:hide},password;{admin_legend},admin;{groups_legend},groups,inherit;{modules_legend},modules;{pagemounts_legend},pagemounts,alpty;{filemounts_legend},filemounts,fop;{forms_legend},forms;{account_legend},disable,start,stop',
		'custom'                      => '{name_legend},username,name,email;{backend_legend:hide},language,showHelp,thumbnails,useRTE,oldBeTheme;{password_legend:hide},password;{admin_legend},admin;{groups_legend},groups,inherit;{modules_legend},modules;{pagemounts_legend},pagemounts,alpty;{filemounts_legend},filemounts,fop;{forms_legend},forms;{account_legend},disable,start,stop'
	),

	// Fields
	'fields' => array
	(
		'username' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['username'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'extnd', 'nospace'=>true, 'unique'=>true, 'maxlength'=>64)
		),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'email' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['email'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'email', 'maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'w50')
		),
		'language' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['language'],
			'default'                 => $GLOBALS['TL_LANGUAGE'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options'                 => $this->getBackendLanguages()
		),
		'showHelp' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['showHelp'],
			'default'                 => 1,
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'thumbnails' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['thumbnails'],
			'default'                 => 1,
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'useRTE' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['useRTE'],
			'default'                 => 1,
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'oldBeTheme' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['oldBeTheme'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'password' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['password'],
			'exclude'                 => true,
			'inputType'               => 'password',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'extnd', 'minlength'=>8)
		),
		'admin' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['admin'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'filter'                  => true,
			'eval'                    => array('submitOnChange'=>true),
			'save_callback' => array
			(
				array('tl_user', 'checkAdmin')
			)
		),
		'groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['groups'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_user_group.name',
			'eval'                    => array('multiple'=>true)
		),
		'inherit' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['inherit'],
			'exclude'                 => true,
			'default'                 => 'group',
			'inputType'               => 'radio',
			'options'                 => array('group', 'extend', 'custom'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_user'],
			'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true)
		),
		'modules' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['modules'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'options_callback'        => array('tl_user', 'getModules'),
			'reference'               => &$GLOBALS['TL_LANG']['MOD'],
			'eval'                    => array('multiple'=>true, 'helpwizard'=>true)
		),
		'pagemounts' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['pagemounts'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'eval'                    => array('fieldType'=>'checkbox')
		),
		'alpty' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['alpty'],
			'default'                 => array('regular', 'redirect', 'forward'),
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'options'                 => array('regular', 'redirect', 'forward', 'root', 'error_403', 'error_404'),
			'reference'               => &$GLOBALS['TL_LANG']['PTY'],
			'eval'                    => array('multiple'=>true, 'helpwizard'=>true)
		),
		'filemounts' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['filemounts'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'checkbox')
		),
		'fop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['FOP']['fop'],
			'default'                 => array('f1', 'f2', 'f3'),
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'options'                 => array('f1', 'f2', 'f3', 'f4', 'f5'),
			'reference'               => &$GLOBALS['TL_LANG']['FOP'],
			'eval'                    => array('multiple'=>true)
		),
		'forms' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['forms'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_form.title',
			'eval'                    => array('multiple'=>true)
		),
		'disable' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['disable'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 2,
			'inputType'               => 'checkbox'
		),
		'start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['start'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'datepicker'=>$this->getDatePickerString(), 'tl_class'=>'w50 wizard')

		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'datepicker'=>$this->getDatePickerString(), 'tl_class'=>'w50 wizard')
		),
		'session' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['session'],
			'exclude'                 => true,
			'input_field_callback'    => array('tl_user', 'sessionField'),
			'eval'                    => array('doNotShow'=>true)
		)
	)
);


/**
 * Class tl_user
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_user extends Backend
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
	 * Add an image to each record
	 * @param array
	 * @param string
	 * @return string
	 */
	public function addIcon($row, $label)
	{
		$image = $row['admin'] ? 'admin' :  'user';

		if ($row['disable'] || strlen($row['start']) && $row['start'] > time() || strlen($row['stop']) && $row['stop'] < time())
		{
			$image .= '_';
		}

		return sprintf('<div class="list_icon" style="background-image:url(\'system/themes/%s/images/%s.gif\');">%s</div>', $this->getTheme(), $image, $label);
	}


	/**
	 * Generate a "switch account" button and return it as string
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function switchUser($row, $href, $label, $title, $icon)
	{
		if (!$this->User->isAdmin)
		{
			return '';
		}

		if ($this->Input->get('key') == 'su' && $this->Input->get('id'))
		{
			$this->Database->prepare("UPDATE tl_session SET pid=? WHERE pid=?")
						   ->execute($this->Input->get('id'), $this->User->id);

			$this->redirect('typolight/main.php');
		}

		return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'">'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Return a checkbox to delete session data
	 * @param object
	 * @return string
	 */
	public function sessionField(DataContainer $dc)
	{
		if ($this->Input->post('FORM_SUBMIT') == 'tl_user')
		{
			$arrPurge = $this->Input->post('purge');

			if (is_array($arrPurge))
			{
				$this->import('Automator');

				if (in_array('purge_session', $arrPurge))
				{
					$this->Session->setData(array());
					$_SESSION['TL_CONFIRM'][] = $GLOBALS['TL_LANG']['tl_user']['sessionPurged'];
				}

				if (in_array('purge_html', $arrPurge))
				{
					$this->Automator->purgeHtmlFolder();
					$_SESSION['TL_CONFIRM'][] = $GLOBALS['TL_LANG']['tl_user']['htmlPurged'];
				}

				if (in_array('purge_temp', $arrPurge))
				{
					$this->Automator->purgeTempFolder();
					$_SESSION['TL_CONFIRM'][] = $GLOBALS['TL_LANG']['tl_user']['tempPurged'];
				}
			}
		}

		return '
  <h3><label for="ctrl_purge">'.$GLOBALS['TL_LANG']['tl_user']['session'][0].'</label></h3>
  <div id="ctrl_purge" class="tl_checkbox_container">
  <input type="checkbox" id="check_all_purge" class="tl_checkbox" onclick="Backend.toggleCheckboxGroup(this, \'ctrl_purge\')" /> <label for="check_all_purge" style="color:#a6a6a6;"><em>'.$GLOBALS['TL_LANG']['MSC']['selectAll'].'</em></label><br />
  <input type="checkbox" name="purge[]" id="opt_purge_0" class="tl_checkbox" value="purge_session" onfocus="Backend.getScrollOffset();" /> <label for="opt_purge_0">'.$GLOBALS['TL_LANG']['tl_user']['sessionLabel'].'</label><br />
  <input type="checkbox" name="purge[]" id="opt_purge_1" class="tl_checkbox" value="purge_html" onfocus="Backend.getScrollOffset();" /> <label for="opt_purge_1">'.$GLOBALS['TL_LANG']['tl_user']['htmlLabel'].'</label><br />
  <input type="checkbox" name="purge[]" id="opt_purge_2" class="tl_checkbox" value="purge_temp" onfocus="Backend.getScrollOffset();" /> <label for="opt_purge_2">'.$GLOBALS['TL_LANG']['tl_user']['tempLabel'].'</label>
  </div>'.$dc->help();
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
	 * Prevent administrators from downgrading their own account
	 * @param mixed
	 * @param object
	 * @return mixed
	 */
	public function checkAdmin($varValue, DataContainer $dc)
	{
		if (!$varValue)
		{
			if ($this->User->id == $dc->id)
			{
				$varValue = 1;
			}
		}

		return $varValue;
	}
}

?>