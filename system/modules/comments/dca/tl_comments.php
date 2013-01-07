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
 * @package    Comments
 * @license    LGPL
 * @filesource
 */


/**
 * Table tl_comments
 */
$GLOBALS['TL_DCA']['tl_comments'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'closed'                      => true,
		'onload_callback' => array
		(
			array('tl_comments', 'checkPermission')
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
			'label_callback'          => array('tl_comments', 'listComments')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_comments']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif',
				'button_callback'     => array('tl_comments', 'editComment')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_comments']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
				'button_callback'     => array('tl_comments', 'deleteComment')
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_comments']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_comments', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_comments']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('addReply'),
		'default'                     => '{author_legend},name,email,website;{comment_legend},comment;{reply_legend},addReply;{publish_legend},published'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'addReply'                    => 'author,reply'
	),

	// Fields
	'fields' => array
	(
		'source' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_comments']['source'],
			'filter'                  => true,
			'sorting'                 => true,
			'reference'               => &$GLOBALS['TL_LANG']['tl_comments'],
		),
		'parent' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_comments']['parent'],
			'filter'                  => true,
			'sorting'                 => true
		),
		'date' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_comments']['date'],
			'sorting'                 => true,
			'filter'                  => true,
			'flag'                    => 8
		),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_comments']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>64)
		),
		'email' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_comments']['email'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>128, 'rgxp'=>'email', 'decodeEntities'=>true, 'tl_class'=>'w50')
		),
		'website' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_comments']['website'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>128, 'rgxp'=>'url', 'decodeEntities'=>true, 'tl_class'=>'w50')
		),
		'comment' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_comments']['comment'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'rte'=>'tinyMCE')
		),
		'addReply' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_comments']['addReply'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'author' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_comments']['author'],
			'default'                 => $this->User->id,
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user.name',
			'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'doNotCopy'=>true, 'includeBlankOption'=>true)
		),
		'reply' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_comments']['reply'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE')
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_comments']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true)
		)
	)
);


/**
 * Class tl_comments
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class tl_comments extends Backend
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
	 * Check permissions to edit table tl_comments
	 */
	public function checkPermission()
	{
		switch ($this->Input->get('act'))
		{
			case 'select':
			case 'show':
				// Allow
				break;

			case 'edit':
			case 'delete':
			case 'toggle':
				$objComment = $this->Database->prepare("SELECT id, parent, source FROM tl_comments WHERE id=?")
											 ->limit(1)
											 ->execute($this->Input->get('id'));

				if ($objComment->numRows < 1)
				{
					$this->log('Comment ID ' . $this->Input->get('id') . ' does not exist', 'tl_comments checkPermission()', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				if (!$this->isAllowedToEditComment($objComment->parent, $objComment->source))
				{
					$this->log('Not enough permissions to ' . $this->Input->get('act') . ' comment ID ' . $this->Input->get('id') . ' (parent element: ' . $objComment->source . ' ID ' . $objComment->parent . ')', 'tl_comments checkPermission()', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();

				if (!is_array($session['CURRENT']['IDS']) || empty($session['CURRENT']['IDS']))
				{
					break;
				}

				$objComment = $this->Database->execute("SELECT id, parent, source FROM tl_comments WHERE id IN(" . implode(',', array_map('intval', $session['CURRENT']['IDS'])) . ")");

				while ($objComment->next())
				{
					if (!$this->isAllowedToEditComment($objComment->parent, $objComment->source) && ($key = array_search($objComment->id, $session['CURRENT']['IDS'])) !== false)
					{
						unset($session['CURRENT']['IDS'][$key]);
					}
				}

				$session['CURRENT']['IDS'] = array_values($session['CURRENT']['IDS']);
				$this->Session->setData($session);
				break;

			default:
				if (strlen($this->Input->get('act')))
				{
					$this->log('Invalid command "'.$this->Input->get('act').'"', 'tl_comments checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Check whether the user is allowed to edit a comment 
	 * @param integer
	 * @param string
	 * @return boolean
	 */
	protected function isAllowedToEditComment($intParent, $strSource)
	{
		if ($this->User->isAdmin)
		{
			return true;
		}

		$this->import('Cache');
		$strKey = __METHOD__ . '-' . $strSource . '-' . $intParent;

		// Load cached result
		if (isset($this->Cache->$strKey))
		{
			return $this->Cache->$strKey;
		}

		// Get the pagemounts
		$pagemounts = array();

		foreach ($this->User->pagemounts as $root)
		{
			$pagemounts[] = $root;
			$pagemounts = array_merge($pagemounts, $this->getChildRecords($root, 'tl_page'));
		}

		$pagemounts = array_unique($pagemounts);

		// Order deny,allow
		$this->Cache->$strKey = false;

		switch ($strSource)
		{
			case 'tl_content':
				$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=(SELECT pid FROM tl_article WHERE id=(SELECT pid FROM tl_content WHERE id=?))")
										  ->limit(1)
										  ->execute($intParent);

				// Check whether the page is mounted and the user is allowed to edit its articles
				if ($objPage->numRows > 0 && in_array($objPage->id, $pagemounts) && $this->User->isAllowed(4, $objPage->row()))
				{
					$this->Cache->$strKey = true;
				}
				break;

			case 'tl_page':
				$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
										  ->limit(1)
										  ->execute($intParent);

				// Check whether the page is mounted and the user is allowed to edit it
				if ($objPage->numRows > 0 && in_array($objPage->id, $pagemounts) && $this->User->isAllowed(1, $objPage->row()))
				{
					$this->Cache->$strKey = true;
				}
				break;

			case 'tl_news':
				// Check the access to the news module
				if ($this->User->hasAccess('news', 'modules'))
				{
					$objArchive = $this->Database->prepare("SELECT pid FROM tl_news WHERE id=?")
												 ->limit(1)
												 ->execute($intParent);

					// Check the access to the news archive
					if ($objArchive->numRows > 0 && $this->User->hasAccess($objArchive->pid, 'news'))
					{
						$this->Cache->$strKey = true;
					}
				}
				break;

			case 'tl_calendar_events':
				// Check the access to the calendar module
				if ($this->User->hasAccess('calendar', 'modules'))
				{
					$objCalendar = $this->Database->prepare("SELECT pid FROM tl_calendar_events WHERE id=?")
												  ->limit(1)
												  ->execute($intParent);

					// Check the access to the calendar
					if ($objCalendar->numRows > 0 && $this->User->hasAccess($objCalendar->pid, 'calendars'))
					{
						$this->Cache->$strKey = true;
					}
				}
				break;

			case 'tl_faq':
				// Check the access to the FAQ module
				if ($this->User->hasAccess('faq', 'modules'))
				{
					$this->Cache->$strKey = true;
				}
				break;

			default:
				// HOOK: support custom modules
				if (isset($GLOBALS['TL_HOOKS']['isAllowedToEditComment']) && is_array($GLOBALS['TL_HOOKS']['isAllowedToEditComment']))
				{
					foreach ($GLOBALS['TL_HOOKS']['isAllowedToEditComment'] as $callback)
					{
						$this->import($callback[0]);

						if ($this->$callback[0]->$callback[1]($intParent, $strSource) === true)
						{
							$this->Cache->$strKey = true;
							break;
						}
					}
				}
				break;
		}

		return $this->Cache->$strKey;
	}


	/**
	 * List a particular record
	 * @param array
	 * @return string
	 */
	public function listComments($arrRow)
	{
		$title = $GLOBALS['TL_LANG']['tl_comments'][$arrRow['source']] . ' ' . $arrRow['parent'];

		switch ($arrRow['source'])
		{
			case 'tl_content':
				$objParent = $this->Database->prepare("SELECT id, title FROM tl_article WHERE id=(SELECT pid FROM tl_content WHERE id=?)")
											->execute($arrRow['parent']);

				if ($objParent->numRows)
				{
					$title .= ' (<a href="contao/main.php?do=article&amp;table=tl_content&amp;id=' . $objParent->id . '">' . $objParent->title . '</a>)';
				}
				break;

			case 'tl_page':
				$objParent = $this->Database->prepare("SELECT id, title FROM tl_page WHERE id=?")
											->execute($arrRow['parent']);

				if ($objParent->numRows)
				{
					$title .= ' (<a href="contao/main.php?do=page&amp;act=edit&amp;id=' . $objParent->id . '">' . $objParent->title . '</a>)';
				}
				break;

			case 'tl_news':
				$objParent = $this->Database->prepare("SELECT id, headline FROM tl_news WHERE id=?")
											->execute($arrRow['parent']);

				if ($objParent->numRows)
				{
					$title .= ' (<a href="contao/main.php?do=news&amp;table=tl_news&amp;act=edit&amp;id=' . $objParent->id . '">' . $objParent->headline . '</a>)';
				}
				break;

			case 'tl_faq':
				$objParent = $this->Database->prepare("SELECT id, question FROM tl_faq WHERE id=?")
											->execute($arrRow['parent']);

				if ($objParent->numRows)
				{
					$title .= ' (<a href="contao/main.php?do=faq&amp;table=tl_faq&amp;act=edit&amp;id=' . $objParent->id . '">' . $objParent->question . '</a>)';
				}
				break;

			case 'tl_calendar_events':
				$objParent = $this->Database->prepare("SELECT id, title FROM tl_calendar_events WHERE id=?")
											->execute($arrRow['parent']);

				if ($objParent->numRows)
				{
					$title .= ' (<a href="contao/main.php?do=calendar&amp;table=tl_calendar_events&amp;act=edit&amp;id=' . $objParent->id . '">' . $objParent->title . '</a>)';
				}
				break;

			default:
				// HOOK: support custom modules
				if (isset($GLOBALS['TL_HOOKS']['listComments']) && is_array($GLOBALS['TL_HOOKS']['listComments']))
				{
					foreach ($GLOBALS['TL_HOOKS']['listComments'] as $callback)
					{
						$this->import($callback[0]);

						if (($tmp = $this->$callback[0]->$callback[1]($arrRow)) != '')
						{
							$title .= $tmp;
							break;
						}
					}
				}
				break;
		}

		$key = $arrRow['published'] ? 'published' : 'unpublished';

		return '
<div class="comment_wrap">
<div class="cte_type ' . $key . '"><strong><a href="mailto:' . $arrRow['email'] . '" title="' . specialchars($arrRow['email']) . '">' . $arrRow['name'] . '</a></strong>' . (strlen($arrRow['website']) ? ' (<a href="' . $arrRow['website'] . '" title="' . specialchars($arrRow['website']) . '" target="_blank">' . $GLOBALS['TL_LANG']['MSC']['com_website'] . '</a>)' : '') . ' - ' . $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['date']) . ' - IP ' . specialchars($arrRow['ip']) . '<br>' . $title . '</div>
<div class="limit_height mark_links' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h52' : '') . '">
' . $arrRow['comment'] . '
</div>
</div>' . "\n    ";
	}


	/**
	 * Return the edit comment button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editComment($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->isAllowedToEditComment($row['parent'], $row['source'])) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the delete comment button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteComment($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->isAllowedToEditComment($row['parent'], $row['source'])) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_comments::published', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}		

		if (!$this->User->isAdmin && !$this->isAllowedToEditComment($row['parent'], $row['source']))
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
		// Check permissions to edit
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'toggle');
		$this->checkPermission();

		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_comments::published', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish comment ID "'.$intId.'"', 'tl_calendar_events toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('tl_comments', $intId);
	
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_comments']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_comments']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_comments SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_comments', $intId);
	}
}

?>