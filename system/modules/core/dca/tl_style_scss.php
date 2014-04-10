<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Table tl_style_scss
 */
$GLOBALS['TL_DCA']['tl_style_scss'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_style_sheet',
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_style_scss', 'checkPermission'),
			array('tl_style_scss', 'updateStyleSheet')
		),
		'onsubmit_callback' => array
		(
			array('tl_style_scss', 'scheduleUpdate')
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

	// Palettes
	'palettes' => array
	(
		'default'                     => 'code',
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
			'foreignKey'              => 'tl_style_sheet.name',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'code' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style_scss']['code'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'preserveTags'=>true, 'decodeEntities'=>true, 'class'=>'monospace', 'rte'=>'ace'),
			'sql'                     => "mediumtext NULL"
		)
	)
);


/**
 * Class tl_style_scss
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class tl_style_scss extends Backend
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
			$this->log('Not enough permissions to access the style sheets module', __METHOD__, TL_ERROR);
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
	 * This method is triggered when a single style or multiple styles are
	 * modified (edit/editAll), duplicated (copy/copyAll), moved (cut/cutAll)
	 * or deleted (delete/deleteAll).
	 */
	public function scheduleUpdate()
	{
		// Return if there is no ID
		if (!CURRENT_ID || Input::get('act') == 'copy' || Environment::get('isAjaxRequest'))
		{
			return;
		}

		// Store the ID in the session
		$session = $this->Session->get('style_sheet_updater');
		$session[] = CURRENT_ID;
		$this->Session->set('style_sheet_updater', array_unique($session));
	}
}
