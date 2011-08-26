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
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['edit'],
				'href'                => 'table=tl_newsletter',
				'icon'                => 'edit.gif',
				'attributes'          => 'class="contextmenu"'
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
				'button_callback'     => array('tl_newsletter_channel', 'editHeader'),
				'attributes'          => 'class="edit-header"'
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
		'__selector__'                => array('useSMTP'),
		'default'                     => '{title_legend},title,jumpTo;{smtp_legend:hide},useSMTP'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'useSMTP'                     => 'smtpHost,smtpUser,smtpPass,smtpEnc,smtpPort'
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
			'eval'                    => array('mandatory'=>true, 'decodeEntities'=>true, 'maxlength'=>255)
		),
		'jumpTo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['jumpTo'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'eval'                    => array('fieldType'=>'radio')
		),
		'useSMTP' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['useSMTP'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'smtpHost' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['smtpHost'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'nospace'=>true, 'doNotShow'=>true, 'tl_class'=>'long')
		),
		'smtpUser' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['smtpUser'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=>true, 'maxlength'=>128, 'doNotShow'=>true, 'tl_class'=>'w50')
		),
		'smtpPass' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['smtpPass'],
			'exclude'                 => true,
			'inputType'               => 'textStore',
			'eval'                    => array('decodeEntities'=>true, 'maxlength'=>32, 'doNotShow'=>true, 'tl_class'=>'w50')
		),
		'smtpEnc' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['smtpEnc'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array(''=>'-', 'ssl'=>'SSL', 'tls'=>'TLS'),
			'eval'                    => array('doNotShow'=>true, 'tl_class'=>'w50')
		),
		'smtpPort' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['smtpPort'],
			'default'                 => 25,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true, 'doNotShow'=>true, 'tl_class'=>'w50')
		)
	)
);


/**
 * Class tl_newsletter_channel
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
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

		$GLOBALS['TL_DCA']['tl_newsletter_channel']['list']['sorting']['root'] = $root;

		// Check permissions to add channels
		if (!$this->User->hasAccess('create', 'newsletterp'))
		{
			$GLOBALS['TL_DCA']['tl_newsletter_channel']['config']['closed'] = true;
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

					if (is_array($arrNew['tl_newsletter_channel']) && in_array($this->Input->get('id'), $arrNew['tl_newsletter_channel']))
					{
						// Add permissions on user level
						if ($this->User->inherit == 'custom' || !$this->User->groups[0])
						{
							$objUser = $this->Database->prepare("SELECT newsletters, newsletterp FROM tl_user WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->id);

							$arrNewsletterp = deserialize($objUser->newsletterp);

							if (is_array($arrNewsletterp) && in_array('create', $arrNewsletterp))
							{
								$arrNewsletters = deserialize($objUser->newsletters);
								$arrNewsletters[] = $this->Input->get('id');

								$this->Database->prepare("UPDATE tl_user SET newsletters=? WHERE id=?")
											   ->execute(serialize($arrNewsletters), $this->User->id);
							}
						}

						// Add permissions on group level
						elseif ($this->User->groups[0] > 0)
						{
							$objGroup = $this->Database->prepare("SELECT newsletters, newsletterp FROM tl_user_group WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->groups[0]);

							$arrNewsletterp = deserialize($objGroup->newsletterp);

							if (is_array($arrNewsletterp) && in_array('create', $arrNewsletterp))
							{
								$arrNewsletters = deserialize($objGroup->newsletters);
								$arrNewsletters[] = $this->Input->get('id');

								$this->Database->prepare("UPDATE tl_user_group SET newsletters=? WHERE id=?")
											   ->execute(serialize($arrNewsletters), $this->User->groups[0]);
							}
						}

						// Add new element to the user object
						$root[] = $this->Input->get('id');
						$this->User->newsletter = $root;
					}
				}
				// No break;

			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array($this->Input->get('id'), $root) || ($this->Input->get('act') == 'delete' && !$this->User->hasAccess('delete', 'newsletterp')))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' newsletter channel ID "'.$this->Input->get('id').'"', 'tl_newsletter_channel checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if ($this->Input->get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'newsletterp'))
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
					$this->log('Not enough permissions to '.$this->Input->get('act').' newsletter channels', 'tl_newsletter_channel checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
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
		return ($this->User->isAdmin || count(preg_grep('/^tl_newsletter_channel::/', $this->User->alexf)) > 0) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : '';
	}


	/**
	 * Return the copy channel button
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
		return ($this->User->isAdmin || $this->User->hasAccess('create', 'newsletterp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the delete channel button
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
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'newsletterp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}
}

?>