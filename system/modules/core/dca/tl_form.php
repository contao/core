<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Table tl_form
 */
$GLOBALS['TL_DCA']['tl_form'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'ctable'                      => array('tl_form_field'),
		'onload_callback' => array
		(
			array('tl_form', 'checkPermission')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'alias' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('title'),
			'flag'                    => 1,
			'panelLayout'             => 'filter;search,limit',
		),
		'label' => array
		(
			'fields'                  => array('title', 'formID'),
			'format'                  => '%s <span style="color:#b3b3b3;padding-left:3px">[%s]</span>'
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
				'label'               => &$GLOBALS['TL_LANG']['tl_form']['edit'],
				'href'                => 'table=tl_form_field',
				'icon'                => 'edit.gif'
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
				'button_callback'     => array('tl_form', 'editHeader')
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif',
				'button_callback'     => array('tl_form', 'copyForm')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
				'button_callback'     => array('tl_form', 'deleteForm')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('sendViaEmail', 'storeValues'),
		'default'                     => '{title_legend},title,alias,jumpTo;{config_legend},tableless,allowTags;{email_legend},sendViaEmail;{store_legend:hide},storeValues;{expert_legend:hide},method,novalidate,attributes,formID'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'sendViaEmail'                => 'recipient,subject,format,skipEmpty',
		'storeValues'                 => 'targetTable'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['alias'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alias', 'doNotCopy'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_form', 'generateAlias')
			),
			'sql'                     => "varchar(128) COLLATE utf8_bin NOT NULL default ''"
		),
		'jumpTo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['jumpTo'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'foreignKey'              => 'tl_page.title',
			'eval'                    => array('fieldType'=>'radio', 'tl_class'=>'clr'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'hasOne', 'load'=>'eager')
		),
		'sendViaEmail' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['sendViaEmail'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'recipient' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['recipient'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>1022, 'rgxp'=>'emails', 'tl_class'=>'w50'),
			'sql'                     => "varchar(1022) NOT NULL default ''"
		),
		'subject' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['subject'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'format' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['format'],
			'default'                 => 'raw',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('raw', 'xml', 'csv', 'email'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_form'],
			'eval'                    => array('helpwizard'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(12) NOT NULL default ''"
		),
		'skipEmpty' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['skipEmtpy'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'storeValues' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['storeValues'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'targetTable' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['targetTable'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_form', 'getAllTables'),
			'eval'                    => array('chosen'=>true),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'method' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['method'],
			'default'                 => 'POST',
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options'                 => array('POST', 'GET'),
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(12) NOT NULL default ''"
		),
		'novalidate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['novalidate'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'attributes' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['attributes'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'formID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['formID'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('nospace'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'tableless' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['tableless'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'allowTags' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['allowTags'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		)
	)
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class tl_form extends Backend
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
	 * Check permissions to edit table tl_form
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

		$GLOBALS['TL_DCA']['tl_form']['list']['sorting']['root'] = $root;

		// Check permissions to add forms
		if (!$this->User->hasAccess('create', 'formp'))
		{
			$GLOBALS['TL_DCA']['tl_form']['config']['closed'] = true;
		}

		// Check current action
		switch (Input::get('act'))
		{
			case 'create':
			case 'select':
				// Allow
				break;

			case 'edit':
				// Dynamically add the record to the user profile
				if (!in_array(Input::get('id'), $root))
				{
					$arrNew = $this->Session->get('new_records');

					if (is_array($arrNew['tl_form']) && in_array(Input::get('id'), $arrNew['tl_form']))
					{
						// Add permissions on user level
						if ($this->User->inherit == 'custom' || !$this->User->groups[0])
						{
							$objUser = $this->Database->prepare("SELECT forms, formp FROM tl_user WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->id);

							$arrFormp = deserialize($objUser->formp);

							if (is_array($arrFormp) && in_array('create', $arrFormp))
							{
								$arrForms = deserialize($objUser->forms);
								$arrForms[] = Input::get('id');

								$this->Database->prepare("UPDATE tl_user SET forms=? WHERE id=?")
											   ->execute(serialize($arrForms), $this->User->id);
							}
						}

						// Add permissions on group level
						elseif ($this->User->groups[0] > 0)
						{
							$objGroup = $this->Database->prepare("SELECT forms, formp FROM tl_user_group WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->groups[0]);

							$arrFormp = deserialize($objGroup->formp);

							if (is_array($arrFormp) && in_array('create', $arrFormp))
							{
								$arrForms = deserialize($objGroup->forms);
								$arrForms[] = Input::get('id');

								$this->Database->prepare("UPDATE tl_user_group SET forms=? WHERE id=?")
											   ->execute(serialize($arrForms), $this->User->groups[0]);
							}
						}

						// Add new element to the user object
						$root[] = Input::get('id');
						$this->User->forms = $root;
					}
				}
				// No break;

			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array(Input::get('id'), $root) || (Input::get('act') == 'delete' && !$this->User->hasAccess('delete', 'formp')))
				{
					$this->log('Not enough permissions to '.Input::get('act').' form ID "'.Input::get('id').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if (Input::get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'formp'))
				{
					$session['CURRENT']['IDS'] = array();
				}
				else
				{
					$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $root);
				}
				$this->Session->setData($session);
				break;

			default:
				if (strlen(Input::get('act')))
				{
					$this->log('Not enough permissions to '.Input::get('act').' forms', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Auto-generate a form alias if it has not been set yet
	 *
	 * @param mixed         $varValue
	 * @param DataContainer $dc
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate an alias if there is none
		if ($varValue == '')
		{
			$autoAlias = true;
			$varValue = standardize(String::restoreBasicEntities($dc->activeRecord->title));
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_form WHERE id=? OR alias=?")
								   ->execute($dc->id, $varValue);

		// Check whether the page alias exists
		if ($objAlias->numRows > 1)
		{
			if (!$autoAlias)
			{
				throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
			}

			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}


	/**
	 * Get all tables and return them as array
	 *
	 * @return array
	 */
	public function getAllTables()
	{
		return $this->Database->listTables();
	}


	/**
	 * Return the edit header button
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
	public function editHeader($row, $href, $label, $title, $icon, $attributes)
	{
		return $this->User->canEditFieldsOf('tl_form') ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the copy form button
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
	public function copyForm($row, $href, $label, $title, $icon, $attributes)
	{
		return $this->User->hasAccess('create', 'formp') ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the delete form button
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
	public function deleteForm($row, $href, $label, $title, $icon, $attributes)
	{
		return $this->User->hasAccess('delete', 'formp') ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}
}
