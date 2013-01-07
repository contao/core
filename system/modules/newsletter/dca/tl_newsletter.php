<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Newsletter
 * @license    LGPL
 * @filesource
 */


/**
 * Table tl_newsletter
 */
$GLOBALS['TL_DCA']['tl_newsletter'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_newsletter_channel',
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_newsletter', 'checkPermission')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('sent', 'date DESC', 'tstamp DESC'),
			'headerFields'            => array('title', 'jumpTo', 'tstamp', 'useSMTP'),
			'panelLayout'             => 'filter;sort,search,limit',
			'child_record_callback'   => array('tl_newsletter', 'listNewsletters')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'send' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter']['send'],
				'href'                => 'key=send',
				'icon'                => 'system/modules/newsletter/html/icon.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('addFile'),
		'default'                     => '{title_legend},subject,alias;{html_legend},content;{text_legend:hide},text;{attachment_legend},addFile;{template_legend:hide},template;{expert_legend:hide},sendText,externalImages,senderName,sender'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'addFile'                     => 'files'
	),

	// Fields
	'fields' => array
	(
		'subject' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter']['subject'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'decodeEntities'=>true, 'maxlength'=>128, 'tl_class'=>'w50')
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'unique'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_newsletter', 'generateAlias')
			)
		),
		'content' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter']['content'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyNews', 'helpwizard'=>true),
			'explanation'             => 'insertTags',
			'save_callback' => array
			(
				array('tl_newsletter', 'convertRelativeLinks')
			)
		),
		'text' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter']['text'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('decodeEntities'=>true)
		),
		'addFile' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter']['addFile'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'files' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter']['files'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'checkbox', 'files'=>true, 'filesOnly'=>true, 'mandatory'=>true)
		),
		'template' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter']['template'],
			'default'                 => 'mail_default',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => $this->getTemplateGroup('mail_')
		),		
		'sendText' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter']['sendText'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'externalImages' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'sender' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter']['sender'],
			'exclude'                 => true,
			'search'                  => true,
			'filter'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'email', 'maxlength'=>128, 'decodeEntities'=>true, 'tl_class'=>'w50')
		),
		'senderName' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter']['senderName'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 11,
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=>true, 'maxlength'=>128, 'tl_class'=>'w50')
		),
		'sent' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter']['sent'],
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 11,
			'eval'                    => array('doNotCopy'=>true, 'isBoolean'=>true)
		),
		'date' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'],
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 8,
			'eval'                    => array('rgxp'=>'datim')
		)
	)
);


/**
 * Class tl_newsletter
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class tl_newsletter extends Backend
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
	 * Check permissions to edit table tl_newsletter
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->newsletters) || empty($this->User->newsletters))
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->newsletters;
		}

		$id = strlen($this->Input->get('id')) ? $this->Input->get('id') : CURRENT_ID;

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'paste':
			case 'select':
				// Allow
				break;

			case 'create':
				if (!strlen($this->Input->get('pid')) || !in_array($this->Input->get('pid'), $root))
				{
					$this->log('Not enough permissions to create newsletters in channel ID "'.$this->Input->get('pid').'"', 'tl_newsletter checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'cut':
			case 'copy':
				if (!in_array($this->Input->get('pid'), $root))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' newsletter ID "'.$id.'" to channel ID "'.$this->Input->get('pid').'"', 'tl_newsletter checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				// NO BREAK STATEMENT HERE

			case 'edit':
			case 'show':
			case 'delete':
				$objChannel = $this->Database->prepare("SELECT pid FROM tl_newsletter WHERE id=?")
											 ->limit(1)
											 ->execute($id);

				if ($objChannel->numRows < 1)
				{
					$this->log('Invalid newsletter ID "'.$id.'"', 'tl_newsletter checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				if (!in_array($objChannel->pid, $root))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' newsletter ID "'.$id.'" of newsletter channel ID "'.$objChannel->pid.'"', 'tl_newsletter checkPermission', TL_ERROR);
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
					$this->log('Not enough permissions to access newsletter channel ID "'.$id.'"', 'tl_news checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$objChannel = $this->Database->prepare("SELECT id FROM tl_newsletter WHERE pid=?")
											 ->execute($id);

				if ($objChannel->numRows < 1)
				{
					$this->log('Invalid newsletter channel ID "'.$id.'"', 'tl_newsletter checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objChannel->fetchEach('id'));
				$this->Session->setData($session);
				break;

			default:
				if (strlen($this->Input->get('act')))
				{
					$this->log('Invalid command "'.$this->Input->get('act').'"', 'tl_newsletter checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				if ($this->Input->get('key') == 'send')
				{
					$objChannel = $this->Database->prepare("SELECT pid FROM tl_newsletter WHERE id=?")
												 ->limit(1)
												 ->execute($id);

					if ($objChannel->numRows < 1)
					{
						$this->log('Invalid newsletter ID "'.$id.'"', 'tl_newsletter checkPermission', TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}

					if (!in_array($objChannel->pid, $root))
					{
						$this->log('Not enough permissions to send newsletter ID "'.$id.'" of newsletter channel ID "'.$objChannel->pid.'"', 'tl_newsletter checkPermission', TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}
				}
				elseif (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access newsletter channel ID "'.$id.'"', 'tl_newsletter checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * List records
	 * @param array
	 * @return string
	 */
	public function listNewsletters($arrRow)
	{
		return '
<div class="cte_type ' . (($arrRow['sent'] && $arrRow['date']) ? 'published' : 'unpublished') . '"><strong>' . $arrRow['subject'] . '</strong> - ' . (($arrRow['sent'] && $arrRow['date']) ? sprintf($GLOBALS['TL_LANG']['tl_newsletter']['sentOn'], $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['date'])) : $GLOBALS['TL_LANG']['tl_newsletter']['notSent']) . '</div>
<div class="limit_height' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h128' : '') . '">' . (!$arrRow['sendText'] ? '
' . $arrRow['content'] . '<hr>' : '' ) . '
' . nl2br_html5($arrRow['text']) . '
</div>' . "\n";
	}


	/**
	 * Convert relative URLs from TinyMCE to absolute URLs
	 * @param string
	 * @return string
	 */
	public function convertRelativeLinks($strContent)
	{
		return $this->convertRelativeUrls($strContent);
	}


	/**
	 * Auto-generate the newsletter alias if it has not been set yet
	 * @param mixed
	 * @param DataContainer
	 * @return string
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if ($varValue == '')
		{
			$autoAlias = true;
			$varValue = standardize($this->restoreBasicEntities($dc->activeRecord->subject));
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_newsletter WHERE alias=?")
								   ->execute($varValue);

		// Check whether the news alias exists
		if ($objAlias->numRows > 1 && !$autoAlias)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// Add ID to alias
		if ($objAlias->numRows && $autoAlias)
		{
			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}
}

?>