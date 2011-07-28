<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
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
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_user', 'checkPermission')
		),
		'onsubmit_callback' => array
		(
			array('tl_user', 'storeDateAdded')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('name'),
			'flag'                    => 1,
			'panelLayout'             => 'filter;sort,search,limit'
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
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_user']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif',
				'button_callback'     => array('tl_user', 'editUser')
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_user']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif',
				'button_callback'     => array('tl_user', 'copyUser')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_user']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_user', 'deleteUser')
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_user']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'     => array('tl_user', 'toggleIcon')
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
		'login'                       => '{name_legend},name,email;{backend_legend},language,backendTheme,showHelp,thumbnails,useRTE,useCE,fancyUpload,oldBeTheme;{session_legend},session;{password_legend},password',
		'admin'                       => '{name_legend},username,name,email;{backend_legend:hide},language,backendTheme,showHelp,thumbnails,useRTE,useCE,fancyUpload,oldBeTheme;{password_legend:hide},password;{admin_legend},admin;{account_legend},disable,start,stop',
		'default'                     => '{name_legend},username,name,email;{backend_legend:hide},language,backendTheme,showHelp,thumbnails,useRTE,useCE,fancyUpload,oldBeTheme;{password_legend:hide},password;{admin_legend},admin;{groups_legend},groups,inherit;{account_legend},disable,start,stop',
		'group'                       => '{name_legend},username,name,email;{backend_legend:hide},language,backendTheme,showHelp,thumbnails,useRTE,useCE,fancyUpload,oldBeTheme;{password_legend:hide},password;{admin_legend},admin;{groups_legend},groups,inherit;{account_legend},disable,start,stop',
		'extend'                      => '{name_legend},username,name,email;{backend_legend:hide},language,backendTheme,showHelp,thumbnails,useRTE,useCE,fancyUpload,oldBeTheme;{password_legend:hide},password;{admin_legend},admin;{groups_legend},groups,inherit;{modules_legend},modules,themes;{pagemounts_legend},pagemounts,alpty;{filemounts_legend},filemounts,fop;{forms_legend},forms,formp;{account_legend},disable,start,stop',
		'custom'                      => '{name_legend},username,name,email;{backend_legend:hide},language,backendTheme,showHelp,thumbnails,useRTE,useCE,fancyUpload,oldBeTheme;{password_legend:hide},password;{admin_legend},admin;{groups_legend},groups,inherit;{modules_legend},modules,themes;{pagemounts_legend},pagemounts,alpty;{filemounts_legend},filemounts,fop;{forms_legend},forms,formp;{account_legend},disable,start,stop'
	),

	// Fields
	'fields' => array
	(
		'username' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['username'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'extnd', 'nospace'=>true, 'unique'=>true, 'maxlength'=>64)
		),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'email' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['email'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'email', 'maxlength'=>255, 'unique'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50')
		),
		'language' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['language'],
			'default'                 => $GLOBALS['TL_LANGUAGE'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options'                 => $this->getBackendLanguages(),
			'eval'                    => array('tl_class'=>'w50')
		),
		'backendTheme' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['backendTheme'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => $this->getBackendThemes(),
			'eval'                    => array('tl_class'=>'w50')
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
		'useCE' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['useCE'],
			'default'                 => 1,
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'fancyUpload' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['fancyUpload'],
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
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'extnd', 'minlength'=>$GLOBALS['TL_CONFIG']['minPasswordLength'])
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
			'inputType'               => 'checkboxWizard',
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
		'themes' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['themes'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'options'                 => array('css', 'modules', 'layout'),
			'reference'               => &$GLOBALS['TL_LANG']['MOD'],
			'eval'                    => array('multiple'=>true)
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
		'formp' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['formp'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'options'                 => array('create', 'delete'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
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
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard')

		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		),
		'session' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_user']['session'],
			'exclude'                 => true,
			'input_field_callback'    => array('tl_user', 'sessionField'),
			'eval'                    => array('doNotShow'=>true)
		),
		'dateAdded' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['dateAdded'],
			'sorting'                 => true,
			'flag'                    => 6,
			'eval'                    => array('rgxp'=>'datim')
		),
		'lastLogin' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['lastLogin'],
			'sorting'                 => true,
			'flag'                    => 6,
			'eval'                    => array('rgxp'=>'datim')
		)
	)
);


/**
 * Class tl_user
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
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
	 * Check permissions to edit table tl_user
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'create':
			case 'select':
			case 'show':
				// Allow
				break;

			case 'delete':
				if ($this->Input->get('id') == $this->User->id)
				{
					$this->log('Attempt to delete own account ID "'.$this->Input->get('id').'"', 'tl_user checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				// no break;

			case 'edit':
			case 'copy':
			case 'toggle':
			default:
				$objUser = $this->Database->prepare("SELECT admin FROM tl_user WHERE id=?")
										  ->limit(1)
										  ->execute($this->Input->get('id'));

				if ($objUser->admin && $this->Input->get('act') != '')
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' administrator account ID "'.$this->Input->get('id').'"', 'tl_user checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				$objUser = $this->Database->execute("SELECT id FROM tl_user WHERE admin=1");
				$session['CURRENT']['IDS'] = array_diff($session['CURRENT']['IDS'], $objUser->fetchEach('id'));
				$this->Session->setData($session);
				break;
		}
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

		return sprintf('<div class="list_icon" style="background-image:url(\'%ssystem/themes/%s/images/%s.gif\');">%s</div>', TL_SCRIPT_URL, $this->getTheme(), $image, $label);
	}


	/**
	 * Return the edit user button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editUser($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || !$row['admin']) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the copy page button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyUser($row, $href, $label, $title, $icon, $attributes, $table)
	{
		if ($GLOBALS['TL_DCA'][$table]['config']['closed'])
		{
			return '';
		}

		return ($this->User->isAdmin || !$row['admin']) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the delete page button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteUser($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || !$row['admin']) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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

			$this->redirect('contao/main.php');
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
<div>
  <fieldset class="tl_checkbox_container">
    <legend>'.$GLOBALS['TL_LANG']['tl_user']['session'][0].'</legend>
    <input type="checkbox" id="check_all_purge" class="tl_checkbox" onclick="Backend.toggleCheckboxGroup(this, \'ctrl_purge\')"> <label for="check_all_purge" style="color:#a6a6a6;"><em>'.$GLOBALS['TL_LANG']['MSC']['selectAll'].'</em></label><br>
    <input type="checkbox" name="purge[]" id="opt_purge_0" class="tl_checkbox" value="purge_session" onfocus="Backend.getScrollOffset();"> <label for="opt_purge_0">'.$GLOBALS['TL_LANG']['tl_user']['sessionLabel'].'</label><br>
    <input type="checkbox" name="purge[]" id="opt_purge_1" class="tl_checkbox" value="purge_html" onfocus="Backend.getScrollOffset();"> <label for="opt_purge_1">'.$GLOBALS['TL_LANG']['tl_user']['htmlLabel'].'</label><br>
    <input type="checkbox" name="purge[]" id="opt_purge_2" class="tl_checkbox" value="purge_temp" onfocus="Backend.getScrollOffset();"> <label for="opt_purge_2">'.$GLOBALS['TL_LANG']['tl_user']['tempLabel'].'</label>
  </fieldset>'.$dc->help().'
</div>';
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


	/**
	 * Store the date when the account has been added
	 * @param object
	 */
	public function storeDateAdded(DataContainer $dc)
	{
		// Return if there is no active record (override all)
		if (!$dc->activeRecord || $dc->activeRecord->dateAdded > 0)
		{
			return;
		}

		// Fallback solution for existing accounts
		if ($dc->activeRecord->lastLogin > 0)
		{
			$time = $dc->activeRecord->lastLogin;
		}
		else
		{
			$time = time();
		}

		$this->Database->prepare("UPDATE tl_user SET dateAdded=? WHERE id=?")
					   ->execute($time, $dc->id);
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

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_user::disable', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.$row['disable'];

		if ($row['disable'])
		{
			$icon = 'invisible.gif';
		}		

		// Protect admin accounts
		if (!$this->User->isAdmin && $row['admin'])
		{
			return $this->generateImage($icon) . ' ';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Disable/enable a user group
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		// Check admin accounts
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'toggle');
		$this->checkPermission();

		// Protect own account
		if ($this->User->id == $intId)
		{
			return;
		}

		// Check permissions
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_user::disable', 'alexf'))
		{
			$this->log('Not enough permissions to activate/deactivate user ID "'.$intId.'"', 'tl_user toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('tl_user', $intId);
	
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_user']['fields']['disable']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_user']['fields']['disable']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_user SET tstamp=". time() .", disable='" . ($blnVisible ? '' : 1) . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_user', $intId);
	}
}

?>