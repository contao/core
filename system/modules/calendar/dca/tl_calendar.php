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
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Table tl_calendar
 */
$GLOBALS['TL_DCA']['tl_calendar'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_calendar_events'),
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_calendar', 'checkPermission'),
			array('tl_calendar', 'generateFeed')
		),
		'onsubmit_callback' => array
		(
			array('tl_calendar', 'scheduleUpdate')
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
			'panelLayout'             => 'filter;search,limit'
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
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_calendar']['edit'],
				'href'                => 'table=tl_calendar_events',
				'icon'                => 'edit.gif',
				'attributes'          => 'class="contextmenu"'
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_calendar']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
				'button_callback'     => array('tl_calendar', 'editHeader'),
				'attributes'          => 'class="edit-header"'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_calendar']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif',
				'button_callback'     => array('tl_calendar', 'copyCalendar')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_calendar']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_calendar', 'deleteCalendar')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_calendar']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('allowComments', 'protected', 'makeFeed'),
		'default'                     => '{title_legend},title,jumpTo;{comments_legend:hide},allowComments;{protected_legend:hide},protected;{feed_legend:hide},makeFeed'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'allowComments'               => 'notify,sortOrder,perPage,moderate,bbcode,requireLogin,disableCaptcha',
		'protected'                   => 'groups',
		'makeFeed'                    => 'format,language,source,maxItems,feedBase,alias,description'
	),

	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
		),
		'jumpTo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['jumpTo'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'eval'                    => array('fieldType'=>'radio')
		),
		'allowComments' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['allowComments'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'notify' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['notify'],
			'default'                 => 'notify_admin',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('notify_admin', 'notify_author', 'notify_both'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_calendar']
		),
		'sortOrder' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['sortOrder'],
			'default'                 => 'ascending',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('ascending', 'descending'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('tl_class'=>'w50')
		),
		'perPage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['perPage'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'moderate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['moderate'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'bbcode' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['bbcode'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'requireLogin' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['requireLogin'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'disableCaptcha' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['disableCaptcha'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'protected' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['protected'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true)
		),
		'makeFeed' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['makeFeed'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'format' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['format'],
			'default'                 => 'rss',
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options'                 => array('rss'=>'RSS 2.0', 'atom'=>'Atom'),
			'eval'                    => array('tl_class'=>'w50')
		),
		'language' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['language'],
			'exclude'                 => true,
			'filter'                  => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>32, 'tl_class'=>'w50')
		),
		'source' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['source'],
			'default'                 => 'source_teaser',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('source_teaser', 'source_text'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_calendar'],
			'eval'                    => array('tl_class'=>'w50')
		),
		'maxItems' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['maxItems'],
			'default'                 => 25,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'feedBase' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['feedBase'],
			'default'                 => $this->Environment->base,
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('trailingSlash'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'alnum', 'unique'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_calendar', 'checkFeedAlias')
			)
		),
		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['description'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('style'=>'height:60px;', 'tl_class'=>'clr')
		)
	)
);


/**
 * Class tl_calendar
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class tl_calendar extends Backend
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
	 * Check permissions to edit table tl_calendar
	 */
	public function checkPermission()
	{
		// HOOK: comments extension required
		if (!in_array('comments', $this->Config->getActiveModules()))
		{
			unset($GLOBALS['TL_DCA']['tl_calendar']['fields']['allowComments']);
		}

		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->calendars) || count($this->User->calendars) < 1)
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->calendars;
		}

		$GLOBALS['TL_DCA']['tl_calendar']['list']['sorting']['root'] = $root;

		// Check permissions to add calendars
		if (!$this->User->hasAccess('create', 'calendarp'))
		{
			$GLOBALS['TL_DCA']['tl_calendar']['config']['closed'] = true;
		}

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'create':
			case 'select':
				// Allow
				break;

			case 'edit':
				// Dynamically add the record to the user profile
				if (!in_array($this->Input->get('id'), $root))
				{
					$arrNew = $this->Session->get('new_records');

					if (is_array($arrNew['tl_calendar']) && in_array($this->Input->get('id'), $arrNew['tl_calendar']))
					{
						// Add permissions on user level
						if ($this->User->inherit == 'custom' || !$this->User->groups[0])
						{
							$objUser = $this->Database->prepare("SELECT calendars, calendarp FROM tl_user WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->id);

							$arrCalendarp = deserialize($objUser->calendarp);

							if (is_array($arrCalendarp) && in_array('create', $arrCalendarp))
							{
								$arrCalendars = deserialize($objUser->calendars);
								$arrCalendars[] = $this->Input->get('id');

								$this->Database->prepare("UPDATE tl_user SET calendars=? WHERE id=?")
											   ->execute(serialize($arrCalendars), $this->User->id);
							}
						}

						// Add permissions on group level
						elseif ($this->User->groups[0] > 0)
						{
							$objGroup = $this->Database->prepare("SELECT calendars, calendarp FROM tl_user_group WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->groups[0]);

							$arrCalendarp = deserialize($objGroup->calendarp);

							if (is_array($arrCalendarp) && in_array('create', $arrCalendarp))
							{
								$arrCalendars = deserialize($objGroup->calendars);
								$arrCalendars[] = $this->Input->get('id');

								$this->Database->prepare("UPDATE tl_user_group SET calendars=? WHERE id=?")
											   ->execute(serialize($arrCalendars), $this->User->groups[0]);
							}
						}

						// Add new element to the user object
						$root[] = $this->Input->get('id');
						$this->User->calendars = $root;
					}
				}
				// No break;

			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array($this->Input->get('id'), $root) || ($this->Input->get('act') == 'delete' && !$this->User->hasAccess('delete', 'calendarp')))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' calendar ID "'.$this->Input->get('id').'"', 'tl_calendar checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if ($this->Input->get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'calendarp'))
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
				if (strlen($this->Input->get('act')))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' calendars', 'tl_calendar checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Check the RSS-feed alias
	 * @param object
	 * @throws Exception
	 */
	public function checkFeedAlias($varValue, DataContainer $dc)
	{
		// No change or empty value
		if ($varValue == $dc->value || $varValue == '')
		{
			return $varValue;
		}

		$arrFeeds = $this->removeOldFeeds(true);

		// Alias exists
		if (array_search($varValue, $arrFeeds) !== false)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		return $varValue;
	}


	/**
	 * Check for modified calendar feeds and update the XML files if necessary
	 */
	public function generateFeed()
	{
		$session = $this->Session->get('calendar_feed_updater');

		if (!is_array($session) || count($session) < 1)
		{
			return;
		}

		$this->import('Calendar');

		foreach ($session as $id)
		{
			$this->Calendar->generateFeed($id);
		}

		$this->Session->set('calendar_feed_updater', null);
	}


	/**
	 * Schedule a calendar feed update
	 * 
	 * This method is triggered when a single calendar or multiple calendars
	 * are modified (edit/editAll).
	 * @param object
	 */
	public function scheduleUpdate(DataContainer $dc)
	{
		// Return if there is no ID 
		if (!$dc->id)
		{
			return;
		}

		// Store the ID in the session
		$session = $this->Session->get('calendar_feed_updater');
		$session[] = $dc->id;
		$this->Session->set('calendar_feed_updater', array_unique($session));
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
		return ($this->User->isAdmin || count(preg_grep('/^tl_calendar::/', $this->User->alexf)) > 0) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : '';
	}


	/**
	 * Return the copy calendar button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyCalendar($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('create', 'calendarp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the delete calendar button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteCalendar($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'calendarp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}
}

?>