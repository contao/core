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
 * Table tl_form_field
 */
$GLOBALS['TL_DCA']['tl_form_field'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'ptable'                      => 'tl_form',
		'onload_callback' => array
		(
			array('tl_form_field', 'checkPermission')
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
			'headerFields'            => array('title', 'tstamp', 'formID', 'storeValues', 'sendViaEmail', 'recipient', 'subject', 'tableless'),
			'child_record_callback'   => array('tl_form_field', 'listFormFields')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_form_field']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form_field']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form_field']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form_field']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form_field']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'     => array('tl_form_field', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form_field']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('type', 'fsType', 'multiple', 'storeFile', 'addSubmit', 'imageSubmit'),
		'default'                     => '{type_legend},type',
		'headline'                    => '{type_legend},type;{text_legend},text',
		'explanation'                 => '{type_legend},type;{text_legend},text',
		'fieldset'                    => '{type_legend},type;{fconfig_legend},fsType;{expert_legend:hide},class',
		'html'                        => '{type_legend},type;{text_legend},html',
		'text'                        => '{type_legend},type,name,label;{fconfig_legend},mandatory,rgxp,maxlength;{expert_legend:hide},value,class,accesskey;{submit_legend},addSubmit',
		'password'                    => '{type_legend},type,name,label;{fconfig_legend},mandatory,rgxp,maxlength;{expert_legend:hide},class,accesskey;{submit_legend},addSubmit',
		'textarea'                    => '{type_legend},type,name,label;{fconfig_legend},mandatory,rgxp,size;{expert_legend:hide},value,class,accesskey;{submit_legend},addSubmit',
		'select'                      => '{type_legend},type,name,label;{options_legend},options;{fconfig_legend},mandatory,multiple;{expert_legend:hide},class,accesskey;{submit_legend},addSubmit',
		'radio'                       => '{type_legend},type,name,label;{options_legend},options;{fconfig_legend},mandatory;{expert_legend:hide},class;{submit_legend},addSubmit',
		'checkbox'                    => '{type_legend},type,name,label;{options_legend},options;{fconfig_legend},mandatory;{expert_legend:hide},class;{submit_legend},addSubmit',
		'upload'                      => '{type_legend},type,name,label;{fconfig_legend},mandatory,extensions,maxlength;{store_legend:hide},storeFile;{expert_legend:hide},class,accesskey;{submit_legend},addSubmit',
		'hidden'                      => '{type_legend},type,name,value',
		'captcha'                     => '{type_legend},type,label;{expert_legend:hide},class,accesskey;{submit_legend},addSubmit',
		'submit'                      => '{type_legend},type,slabel;{image_legend:hide},imageSubmit;{expert_legend:hide},class,accesskey'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'fsType_fsStart'              => 'label',
		'multiple'                    => 'mSize',
		'storeFile'                   => 'uploadFolder,useHomeDir,doNotOverwrite',
		'addSubmit'                   => 'slabel',
		'imageSubmit'                 => 'singleSRC'
	),

	// Fields
	'fields' => array
	(
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['type'],
			'default'                 => 'text',
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_form_field', 'getFields'),
			'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true),
			'reference'               => &$GLOBALS['TL_LANG']['FFL']
		),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'extnd', 'spaceToUnderscore'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
		),
		'label' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['label'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50')
		),
		'text' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['text'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE', 'helpwizard'=>true),
			'explanation'             => 'insertTags'
		),
		'html' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['html'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'allowHtml'=>true)
		),
		'options' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['options'],
			'exclude'                 => true,
			'inputType'               => 'optionWizard',
			'eval'                    => array('mandatory'=>true)
		),
		'mandatory' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['mandatory'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'rgxp' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['rgxp'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('digit', 'alpha', 'alnum', 'extnd', 'date', 'time', 'datim', 'phone', 'email', 'url'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_form_field'],
			'eval'                    => array('helpwizard'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'maxlength' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['maxlength'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'size' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['size'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'multiple' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['multiple'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'mSize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['mSize'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit')
		),
		'extensions' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['extensions'],
			'exclude'                 => true,
			'default'                 => 'jpg,jpeg,gif,png,pdf,doc,xls,ppt',
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'extnd', 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'storeFile' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['storeFile'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'uploadFolder' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['uploadFolder'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'tl_class'=>'clr')
		),
		'useHomeDir' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['useHomeDir'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'doNotOverwrite' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['doNotOverwrite'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'fsType' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['fsType'],
			'default'                 => 'fsStart',
			'exclude'                 => true,
			'inputType'               => 'radio',
			'options'                 => array('fsStart', 'fsStop'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_form_field'],
			'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true)
		),
		'value' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['value'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255)
		),
		'class' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['class'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50')
		),
		'accesskey' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['accesskey'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'maxlength'=>1, 'tl_class'=>'w50')
		),
		'addSubmit' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['addSubmit'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'slabel' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['slabel'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
		),
		'imageSubmit' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['imageSubmit'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'singleSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['singleSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true, 'mandatory'=>true, 'tl_class'=>'clr')
		)
	)
);


/**
 * Class tl_form_field
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class tl_form_field extends Backend
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
	 * Check permissions to edit table tl_form_field
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->forms) || count($this->User->forms) < 1)
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->forms;
		}

		$id = strlen($this->Input->get('id')) ? $this->Input->get('id') : CURRENT_ID;

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'paste':
				// Allow
				break;

			case 'create':
			case 'select':
				if (!strlen($this->Input->get('id')) || !in_array($this->Input->get('id'), $root))
				{
					$this->log('Not enough permissions to access form ID "'.$this->Input->get('id').'"', 'tl_form_field checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'cut':
			case 'copy':
				$pid = $this->Input->get('pid');

				// Get form ID
				if ($this->Input->get('mode') == 1)
				{
					$objField = $this->Database->prepare("SELECT pid FROM tl_form_field WHERE id=?")
											   ->limit(1)
											   ->execute($this->Input->get('pid'));

					if ($objField->numRows < 1)
					{
						$this->log('Invalid form field ID "'.$this->Input->get('pid').'"', 'tl_form_field checkPermission', TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}

					$pid = $objField->pid;
				}

				if (!in_array($pid, $root))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' form field ID "'.$id.'" to form ID "'.$pid.'"', 'tl_form_field checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				// NO BREAK STATEMENT HERE

			case 'edit':
			case 'show':
			case 'delete':
			case 'toggle':
				$objField = $this->Database->prepare("SELECT pid FROM tl_form_field WHERE id=?")
										   ->limit(1)
										   ->execute($id);

				if ($objField->numRows < 1)
				{
					$this->log('Invalid form field ID "'.$id.'"', 'tl_form_field checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				if (!in_array($objField->pid, $root))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' form field ID "'.$id.'" of form ID "'.$objField->pid.'"', 'tl_form_field checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
			case 'cutAll':
			case 'copyAll':
				if (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access form ID "'.$id.'"', 'tl_form_field checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$objForm = $this->Database->prepare("SELECT id FROM tl_form_field WHERE pid=?")
										  ->execute($id);

				if ($objForm->numRows < 1)
				{
					$this->log('Invalid form ID "'.$id.'"', 'tl_form_field checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objForm->fetchEach('id'));
				$this->Session->setData($session);
				break;

			default:
				if (strlen($this->Input->get('act')))
				{
					$this->log('Invalid command "'.$this->Input->get('act').'"', 'tl_form_field checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				elseif (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access form ID "'.$id.'"', 'tl_form_field checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Add the type of input field
	 * @param array
	 * @return string
	 */
	public function listFormFields($arrRow)
	{
		$arrRow['required'] = $arrRow['mandatory'];
		$key = $arrRow['invisible'] ? 'unpublished' : 'published';

		$strType = '
<div class="cte_type ' . $key . '">' . $GLOBALS['TL_LANG']['FFL'][$arrRow['type']][0] . ($arrRow['name'] ? ' (' . $arrRow['name'] . ')' : '') . '</div>
<div class="limit_height' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h32' : '') . ' block">';

		$strClass = $GLOBALS['TL_FFL'][$arrRow['type']];

		if (!$this->classFileExists($strClass))
		{
			return '';
		}

		$objWidget = new $strClass($arrRow);

		$strWidget = $objWidget->parse();
		$strWidget = preg_replace('/ name="[^"]+"/i', '', $strWidget);
		$strWidget = str_replace('type="submit"', 'type="button"', $strWidget);

		if ($objWidget instanceof FormHidden)
		{
			return $strType . "\n" . $objWidget->value . "\n</div>\n";
		}
		
		return $strType . '
<table class="tl_form_field_preview">
'.$strWidget.'</table>
</div>' . "\n";
	}


	/**
	 * Return a list of form fields
	 * @param object
	 * @return array
	 */
	public function getFields(DataContainer $dc)
	{
		$arrFields = $GLOBALS['TL_FFL'];
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		$objForm = $this->Database->prepare("SELECT tableless FROM tl_form WHERE id=?")
								  ->limit(1)
								  ->execute($intPid);

		// Fieldsets are only supported in tableless forms
		if (!$objForm->tableless)
		{
			unset($arrFields['fieldset']);
		}

		// Add the translation
		foreach (array_keys($arrFields) as $key)
		{
			$arrFields[$key] = $GLOBALS['TL_LANG']['FFL'][$key][0];
		}

		return $arrFields;
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
	 * Toggle the visibility of a form field
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'toggle');
		$this->checkPermission();

		$this->createInitialVersion('tl_form_field', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_form_field']['fields']['invisible']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_form_field']['fields']['invisible']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_form_field SET tstamp=". time() .", invisible='" . ($blnVisible ? '' : 1) . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_form_field', $intId);
	}
}

?>