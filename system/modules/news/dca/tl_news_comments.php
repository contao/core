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
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Table tl_news_comments
 */
$GLOBALS['TL_DCA']['tl_news_comments'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_news',
		'doNotCopyRecords'            => true,
		'enableVersioning'            => true,
		'closed'                      => true,
		'onload_callback' => array
		(
			array('tl_news_comments', 'checkPermission')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('date DESC'),
			'flag'                    => 8,
			'panelLayout'             => 'filter;sort,search,limit'
		),
		'label' => array
		(
			'fields'                  => array('name'),
			'format'                  => '%s',
			'label_callback'          => array('tl_news_comments', 'listComments')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_news_comments']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news_comments']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news_comments']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'name,email,website;comment;published'
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news_comments']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>64)
		),
		'email' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news_comments']['email'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>128, 'rgxp'=>'email')
		),
		'website' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news_comments']['website'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>128, 'rgxp'=>'url', 'decodeEntities'=>true)
		),
		'comment' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news_comments']['comment'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE')
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news_comments']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true)
		),
		'date' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news_comments']['date'],
			'sorting'                 => true,
			'filter'                  => true,
			'flag'                    => 8
		),
		'pid' => array
		(
			'label'                   => array('PID', 'Parent ID'),
			'filter'                  => true,
			'sorting'                 => true
		)
	)
);


/**
 * Class tl_news_comments
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_news_comments extends Backend
{

	/**
	 * Database result
	 * @var array
	 */
	protected $arrData = null;


	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Check permissions to edit table tl_news_comments
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->news) || count($this->User->news) < 1)
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->news;
		}

		$id = strlen($this->Input->get('id')) ? $this->Input->get('id') : CURRENT_ID;

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'select':
				// Allow
				break;

			case 'edit':
			case 'show':
			case 'delete':
				$objArchive = $this->Database->prepare("SELECT pid FROM tl_news WHERE id=?")
											 ->limit(1)
											 ->execute($id);

				if ($objArchive->numRows < 1)
				{
					$this->log('Invalid news item ID "'.$id.'"', 'tl_news_comments checkPermission', 5);
					$this->redirect('typolight/main.php?act=error');
				}

				if (!in_array($objArchive->pid, $root))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' news comment ID "'.$this->Input->get('id').'"', 'tl_news_comments checkPermission', 5);
					$this->redirect('typolight/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
				if (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access news archive ID "'.$id.'"', 'tl_news_comments checkPermission', 5);
					$this->redirect('typolight/main.php?act=error');
				}

				$objArchive = $this->Database->prepare("SELECT id FROM tl_news WHERE pid=?")
											 ->execute($id);

				if ($objArchive->numRows < 1)
				{
					$this->log('Invalid news archive ID "'.$id.'"', 'tl_news_comments checkPermission', 5);
					$this->redirect('typolight/main.php?act=error');
				}

				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objArchive->fetchEach('id'));
				$this->Session->setData($session);
				break;

			default:
				if (strlen($this->Input->get('act')))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' news comments', 'tl_news_comments checkPermission', 5);
					$this->redirect('typolight/main.php?act=error');
				}
				elseif (!in_array($this->Input->get('id'), $root))
				{
					$this->log('Not enough permissions to access news archive ID "'.$this->Input->get('id').'"', 'tl_news_comments checkPermission', 5);
					$this->redirect('typolight/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * List a particular record
	 * @param array
	 * @return string
	 */
	public function listComments($arrRow)
	{
		if (is_null($this->arrData))
		{
			$this->import('String');

			$objData = $this->Database->prepare("SELECT n.id, n.headline FROM tl_news n, tl_news_archive a WHERE a.id=n.pid AND a.id=?")
									  ->execute(CURRENT_ID);

			while ($objData->next())
			{
				$this->arrData[$objData->id] = $objData->headline;
			}
		}

		$key = $arrRow['published'] ? 'published' : 'unpublished';
		return '
<div class="comment_wrap">
<div class="cte_type ' . $key . '"><strong><a href="mailto:' . $arrRow['email'] . '" title="' . specialchars($arrRow['email']) . '">' . $arrRow['name'] . '</a></strong>' . (strlen($arrRow['website']) ? ' (<a href="' . $arrRow['website'] . '" title="' . specialchars($arrRow['website']) . '" onclick="window.open(this.href); return false;">' . $GLOBALS['TL_LANG']['MSC']['com_website'] . '</a>)' : '') . ' - ' . date($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['date']) . ' - IP ' . $arrRow['ip'] . '<br />' . $this->arrData[$arrRow['pid']] . ' (PID: ' . $arrRow['pid'] . ')</div>
<div class="limit_height mark_links' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h52' : '') . ' block">
' . $arrRow['comment'] . '
</div>
</div>' . "\n    ";
	}
}

?>