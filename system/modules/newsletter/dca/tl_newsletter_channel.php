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
 * @package    Newsletter
 * @license    LGPL
 * @filesource
 */


/**
 * Table tl_newsletter_channel
 */
$GLOBALS['TL_DCA']['tl_newsletter_channel'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_newsletter', 'tl_newsletter_recipients'),
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_newsletter_channel', 'checkPermission')
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
			'panelLayout'             => 'search,limit'
		),
		'label' => array
		(
			'fields'                  => array('title'),
			'format'                  => '%s'
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
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['edit'],
				'href'                => 'table=tl_newsletter',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif',
				'button_callback'     => array('tl_newsletter_channel', 'copyChannel')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_newsletter_channel', 'deleteChannel')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'recipients' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['recipients'],
				'href'                => 'table=tl_newsletter_recipients',
				'icon'                => 'mgroup.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'title;jumpTo'
	),

	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['title'],
			'search'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
		),
		'jumpTo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['jumpTo'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'eval'                    => array('fieldType'=>'radio', 'helpwizard'=>true),
			'explanation'             => 'jumpTo'
		),
	)
);


/**
 * Class tl_newsletter_channel
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_newsletter_channel extends Backend
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
	 * Check permissions to edit table tl_newsletter_channel
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->newsletters) || count($this->User->newsletters) < 1)
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->newsletters;
		}

		$GLOBALS['TL_DCA']['tl_newsletter_channel']['config']['closed'] = true;
		$GLOBALS['TL_DCA']['tl_newsletter_channel']['list']['sorting']['root'] = $root;

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'select':
				// Allow
				break;

			case 'edit':
			case 'show':
				if (!in_array($this->Input->get('id'), $root))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' newsletter channel ID "'.$this->Input->get('id').'"', 'tl_newsletter_channel checkPermission', 5);
					$this->redirect('typolight/main.php?act=error');
				}
				break;

			case 'editAll':
				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $root);
				$this->Session->setData($session);
				break;

			default:
				if (strlen($this->Input->get('act')))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' newsletter channels', 'tl_newsletter_channel checkPermission', 5);
					$this->redirect('typolight/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Return the copy archive button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyChannel($row, $href, $label, $title, $icon, $attributes)
	{
		if (!$this->User->isAdmin)
		{
			return '';
		}

		return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Return the delete archive button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteChannel($row, $href, $label, $title, $icon, $attributes)
	{
		if (!$this->User->isAdmin)
		{
			return '';
		}

		return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}
}

?>