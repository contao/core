<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
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
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index'
			)
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
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
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
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form_field']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form_field']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form_field']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
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
		'headline'                    => '{type_legend},type;{text_legend},text;{template_legend:hide},customTpl',
		'explanation'                 => '{type_legend},type;{text_legend},text;{template_legend:hide},customTpl',
		'fieldsetfsStart'             => '{type_legend},type;{fconfig_legend},fsType,label;{expert_legend:hide},class;{template_legend:hide},customTpl',
		'fieldsetfsStop'              => '{type_legend},type;{fconfig_legend},fsType;{template_legend:hide},customTpl',
		'html'                        => '{type_legend},type;{text_legend},html;{template_legend:hide},customTpl',
		'text'                        => '{type_legend},type,name,label;{fconfig_legend},mandatory,rgxp,placeholder;{expert_legend:hide},class,value,minlength,maxlength,accesskey,tabindex;{template_legend:hide},customTpl;{submit_legend},addSubmit',
		'password'                    => '{type_legend},type,name,label;{fconfig_legend},mandatory,rgxp,placeholder;{expert_legend:hide},class,value,minlength,maxlength,accesskey,tabindex;{template_legend:hide},customTpl;{submit_legend},addSubmit',
		'textarea'                    => '{type_legend},type,name,label;{fconfig_legend},mandatory,rgxp,placeholder;{size_legend},size;{expert_legend:hide},class,value,minlength,maxlength,accesskey,tabindex;{template_legend:hide},customTpl;{submit_legend},addSubmit',
		'select'                      => '{type_legend},type,name,label;{fconfig_legend},mandatory,multiple;{options_legend},options;{expert_legend:hide},class,accesskey,tabindex;{template_legend:hide},customTpl;{submit_legend},addSubmit',
		'radio'                       => '{type_legend},type,name,label;{fconfig_legend},mandatory;{options_legend},options;{expert_legend:hide},class;{template_legend:hide},customTpl;{submit_legend},addSubmit',
		'checkbox'                    => '{type_legend},type,name,label;{fconfig_legend},mandatory;{options_legend},options;{expert_legend:hide},class;{template_legend:hide},customTpl;{submit_legend},addSubmit',
		'upload'                      => '{type_legend},type,name,label;{fconfig_legend},mandatory,extensions,maxlength;{store_legend:hide},storeFile;{expert_legend:hide},class,accesskey,tabindex,fSize;{template_legend:hide},customTpl;{submit_legend},addSubmit',
		'hidden'                      => '{type_legend},type,name,value;{fconfig_legend},mandatory,rgxp;{template_legend:hide},customTpl',
		'captcha'                     => '{type_legend},type,label;{fconfig_legend},placeholder;{expert_legend:hide},class,accesskey,tabindex;{template_legend:hide},customTpl;{submit_legend},addSubmit',
		'submit'                      => '{type_legend},type,slabel;{image_legend:hide},imageSubmit;{expert_legend:hide},class,accesskey,tabindex;{template_legend:hide},customTpl'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'multiple'                    => 'mSize',
		'storeFile'                   => 'uploadFolder,useHomeDir,doNotOverwrite',
		'addSubmit'                   => 'slabel',
		'imageSubmit'                 => 'singleSRC'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'              => 'tl_form.title',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
		),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'invisible' => array
		(
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['type'],
			'default'                 => 'text',
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_form_field', 'getFields'),
			'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true),
			'reference'               => &$GLOBALS['TL_LANG']['FFL'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'extnd', 'spaceToUnderscore'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'label' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['label'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'text' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['text'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE', 'helpwizard'=>true),
			'explanation'             => 'insertTags',
			'sql'                     => "text NULL"
		),
		'html' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['html'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'allowHtml'=>true, 'class'=>'monospace', 'rte'=>'ace|html'),
			'sql'                     => "text NULL"
		),
		'options' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['options'],
			'exclude'                 => true,
			'inputType'               => 'optionWizard',
			'eval'                    => array('mandatory'=>true, 'allowHtml'=>true),
			'sql'                     => "blob NULL"
		),
		'mandatory' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['mandatory'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'rgxp' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['rgxp'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('digit', 'alpha', 'alnum', 'extnd', 'date', 'time', 'datim', 'phone', 'email', 'url'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_form_field'],
			'eval'                    => array('helpwizard'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'placeholder' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['placeholder'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'minlength' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['minlength'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'maxlength' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['maxlength'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'size' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['size'],
			'default'                 => array(4, 40),
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true, 'size'=>2, 'rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'multiple' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['multiple'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'clr'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'mSize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['mSize'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural'),
			'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
		),
		'extensions' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['extensions'],
			'exclude'                 => true,
			'default'                 => 'jpg,jpeg,gif,png,pdf,doc,xls,ppt',
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'extnd', 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'storeFile' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['storeFile'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'uploadFolder' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['uploadFolder'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'tl_class'=>'clr'),
			'sql'                     => "binary(16) NULL"
		),
		'useHomeDir' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['useHomeDir'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'doNotOverwrite' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['doNotOverwrite'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'fsType' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['fsType'],
			'default'                 => 'fsStart',
			'exclude'                 => true,
			'inputType'               => 'radio',
			'options'                 => array('fsStart', 'fsStop'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_form_field'],
			'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'class' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['class'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'value' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['value'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'accesskey' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['accesskey'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'maxlength'=>1, 'tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'tabindex' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['tabindex'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
		),
		'fSize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['fSize'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
		),
		'customTpl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['customTpl'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_form_field', 'getFormFieldTemplates'),
			'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'addSubmit' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['addSubmit'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'slabel' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['slabel'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'imageSubmit' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['imageSubmit'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'singleSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['singleSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'filesOnly'=>true, 'mandatory'=>true, 'tl_class'=>'clr'),
			'sql'                     => "binary(16) NULL"
		)
	)
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
		if (!is_array($this->User->forms) || empty($this->User->forms))
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->forms;
		}

		$id = strlen(Input::get('id')) ? Input::get('id') : CURRENT_ID;

		// Check current action
		switch (Input::get('act'))
		{
			case 'paste':
				// Allow
				break;

			case 'create':
			case 'select':
				if (!strlen(Input::get('id')) || !in_array(Input::get('id'), $root))
				{
					$this->log('Not enough permissions to access form ID "'.Input::get('id').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'cut':
			case 'copy':
				$pid = Input::get('pid');

				// Get form ID
				if (Input::get('mode') == 1)
				{
					$objField = $this->Database->prepare("SELECT pid FROM tl_form_field WHERE id=?")
											   ->limit(1)
											   ->execute(Input::get('pid'));

					if ($objField->numRows < 1)
					{
						$this->log('Invalid form field ID "'.Input::get('pid').'"', __METHOD__, TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}

					$pid = $objField->pid;
				}

				if (!in_array($pid, $root))
				{
					$this->log('Not enough permissions to '.Input::get('act').' form field ID "'.$id.'" to form ID "'.$pid.'"', __METHOD__, TL_ERROR);
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
					$this->log('Invalid form field ID "'.$id.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				if (!in_array($objField->pid, $root))
				{
					$this->log('Not enough permissions to '.Input::get('act').' form field ID "'.$id.'" of form ID "'.$objField->pid.'"', __METHOD__, TL_ERROR);
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
					$this->log('Not enough permissions to access form ID "'.$id.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$objForm = $this->Database->prepare("SELECT id FROM tl_form_field WHERE pid=?")
										  ->execute($id);

				if ($objForm->numRows < 1)
				{
					$this->log('Invalid form ID "'.$id.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objForm->fetchEach('id'));
				$this->Session->setData($session);
				break;

			default:
				if (strlen(Input::get('act')))
				{
					$this->log('Invalid command "'.Input::get('act').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				elseif (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access form ID "'.$id.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Add the type of input field
	 *
	 * @param array $arrRow
	 *
	 * @return string
	 */
	public function listFormFields($arrRow)
	{
		$arrRow['required'] = $arrRow['mandatory'];
		$key = $arrRow['invisible'] ? 'unpublished' : 'published';

		$strType = '
<div class="cte_type ' . $key . '">' . $GLOBALS['TL_LANG']['FFL'][$arrRow['type']][0] . ($arrRow['name'] ? ' (' . $arrRow['name'] . ')' : '') . '</div>
<div class="limit_height' . (!Config::get('doNotCollapse') ? ' h32' : '') . '">';

		$strClass = $GLOBALS['TL_FFL'][$arrRow['type']];

		if (!class_exists($strClass))
		{
			return '';
		}

		/** @var Widget $objWidget */
		$objWidget = new $strClass($arrRow);

		$strWidget = $objWidget->parse();
		$strWidget = preg_replace('/ name="[^"]+"/i', '', $strWidget);
		$strWidget = str_replace(array(' type="submit"', ' autofocus', ' required'), array(' type="button"', '', ''), $strWidget);

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
	 *
	 * @param DataContainer $dc
	 *
	 * @return array
	 */
	public function getFields(DataContainer $dc)
	{
		$arrFields = $GLOBALS['TL_FFL'];
		$intPid = $dc->activeRecord->pid;

		if (Input::get('act') == 'overrideAll')
		{
			$intPid = Input::get('id');
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
	 * Return all form field templates as array
	 *
	 * @return array
	 */
	public function getFormFieldTemplates()
	{
		return $this->getTemplateGroup('form_');
	}


	/**
	 * Return the "toggle visibility" button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.$row['invisible'];

		if ($row['invisible'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
	}


	/**
	 * Toggle the visibility of a form field
	 *
	 * @param integer       $intId
	 * @param boolean       $blnVisible
	 * @param DataContainer $dc
	 */
	public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
	{
		// Check permissions
		Input::setGet('id', $intId);
		Input::setGet('act', 'toggle');
		$this->checkPermission();

		$objVersions = new Versions('tl_form_field', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_form_field']['fields']['invisible']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_form_field']['fields']['invisible']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, ($dc ?: $this));
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, ($dc ?: $this));
				}
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_form_field SET tstamp=". time() .", invisible='" . ($blnVisible ? '' : 1) . "' WHERE id=?")
					   ->execute($intId);

		$objVersions->create();
		$this->log('A new version of record "tl_form_field.id='.$intId.'" has been created'.$this->getParentEntries('tl_form_field', $intId), __METHOD__, TL_GENERAL);
	}
}
