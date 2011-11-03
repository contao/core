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
 * Load class tl_page
 */
$this->loadDataContainer('tl_page');


/**
 * Table tl_article
 */
$GLOBALS['TL_DCA']['tl_article'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_page',
		'ctable'                      => array('tl_content'),
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_article', 'checkPermission'),
			array('tl_page', 'addBreadcrumb')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 6,
			'fields'                  => array('published DESC', 'title', 'author'),
			'paste_button_callback'   => array('tl_article', 'pasteArticle')
		),
		'label' => array
		(
			'fields'                  => array('title', 'inColumn'),
			'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>',
			'label_callback'          => array('tl_article', 'addIcon')
		),
		'global_operations' => array
		(
			'toggleNodes' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['toggleNodes'],
				'href'                => '&amp;ptg=all',
				'class'               => 'header_toggle'
			),
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
				'label'               => &$GLOBALS['TL_LANG']['tl_article']['edit'],
				'href'                => 'table=tl_content',
				'icon'                => 'edit.gif',
				'button_callback'     => array('tl_article', 'editArticle'),
				'attributes'          => 'class="contextmenu"'
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_article']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
				'button_callback'     => array('tl_article', 'editHeader'),
				'attributes'          => 'class="edit-header"'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_article']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
				'button_callback'     => array('tl_article', 'copyArticle')
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_article']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
				'button_callback'     => array('tl_article', 'cutArticle')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_article']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_article', 'deleteArticle')
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_article']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'     => array('tl_article', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_article']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{title_legend},title,alias,author;{layout_legend},inColumn,keywords;{teaser_legend:hide},teaserCssID,showTeaser,teaser;{expert_legend:hide},printable,cssID,space;{publish_legend},published,start,stop'
	),

	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['alias'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'doNotCopy'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_article', 'generateAlias')
			)

		),
		'author' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['author'],
			'default'                 => $this->User->id,
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user.name',
			'eval'                    => array('doNotCopy'=>true, 'mandatory'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'inColumn' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['inColumn'],
			'exclude'                 => true,
			'default'                 => 'main',
			'inputType'               => 'select',
			'options_callback'        => array('tl_article', 'getActivePageSections'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_article']
		),
		'keywords' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['keywords'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('style'=>'height:60px;')
		),
		'teaserCssID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['teaserCssID'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50')
		),
		'showTeaser' => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['showTeaser'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12')
		),
		'teaser' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['teaser'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE', 'tl_class'=>'clr')
		),
		'printable' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['printable'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'options'                 => array('print', 'pdf', 'facebook', 'twitter'),
			'eval'                    => array('multiple'=>true),
			'reference'               => &$GLOBALS['TL_LANG']['tl_article']
		),
		'cssID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['cssID'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50')
		),
		'space' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['space'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'published' => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['published'],
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true)
		),
		'start' => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['start'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		),
		'stop' => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['stop'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		)
	)
);


/**
 * Class tl_article
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class tl_article extends Backend
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
	 * Check permissions to edit table tl_page
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		$session = $this->Session->getData();

		// Set the default page user and group
		$GLOBALS['TL_DCA']['tl_page']['fields']['cuser']['default'] = ($GLOBALS['TL_CONFIG']['defaultUser'] != '') ? $GLOBALS['TL_CONFIG']['defaultUser'] : $this->User->id;
		$GLOBALS['TL_DCA']['tl_page']['fields']['cgroup']['default'] = ($GLOBALS['TL_CONFIG']['defaultGroup'] != '') ? $GLOBALS['TL_CONFIG']['defaultGroup'] : $this->User->groups[0];

		// Restrict the page tree
		$GLOBALS['TL_DCA']['tl_page']['list']['sorting']['root'] = $this->User->pagemounts;

		// Set allowed page IDs (edit multiple)
		if (is_array($session['CURRENT']['IDS']))
		{
			$edit_all = array();
			$delete_all = array();

			foreach ($session['CURRENT']['IDS'] as $id)
			{
				$objArticle = $this->Database->prepare("SELECT p.pid, p.includeChmod, p.chmod, p.cuser, p.cgroup FROM tl_article a, tl_page p WHERE a.id=? AND a.pid=p.id")
											 ->limit(1)
											 ->execute($id);

				if ($objArticle->numRows < 1)
				{
					continue;
				}

				$row = $objArticle->row();

				if ($this->User->isAllowed(4, $row))
				{
					$edit_all[] = $id;
				}

				if ($this->User->isAllowed(6, $row))
				{
					$delete_all[] = $id;
				}
			}

			$session['CURRENT']['IDS'] = ($this->Input->get('act') == 'deleteAll') ? $delete_all : $edit_all;
		}

		// Set allowed clipboard IDs
		if (isset($session['CLIPBOARD']['tl_article']) && is_array($session['CLIPBOARD']['tl_article']['id']))
		{
			$clipboard = array();

			foreach ($session['CLIPBOARD']['tl_article']['id'] as $id)
			{
				$objArticle = $this->Database->prepare("SELECT p.pid, p.includeChmod, p.chmod, p.cuser, p.cgroup FROM tl_article a, tl_page p WHERE a.id=? AND a.pid=p.id")
											 ->limit(1)
											 ->execute($id);

				if ($objArticle->numRows < 1)
				{
					continue;
				}

				if ($this->User->isAllowed(5, $objArticle->row()))
				{
					$clipboard[] = $id;
				}
			}

			$session['CLIPBOARD']['tl_article']['id'] = $clipboard;
		}

		// Overwrite session
		$this->Session->setData($session);

		// Check current action
		if ($this->Input->get('act') && $this->Input->get('act') != 'paste')
		{
			$error = false;

			// Set ID of the article's page
			$objPage = $this->Database->prepare("SELECT pid FROM tl_article WHERE id=?")
									  ->limit(1)
									  ->execute($this->Input->get('id'));

			$ids = $objPage->numRows ? array($objPage->pid) : array();

			// Set permission
			switch ($this->Input->get('act'))
			{
				case 'edit':
				case 'toggle':
					$permission = 4;
					break;

				case 'move':
					$permission = 5;
					$ids[] = $this->Input->get('sid');
					break;

				// Do not insert articles into a website root page
				case 'create':
				case 'copy':
				case 'copyAll':
				case 'cut':
				case 'cutAll':
					$permission = 5;

					// Insert into a page
					if ($this->Input->get('mode') == 2)
					{
						$objParent = $this->Database->prepare("SELECT id, type FROM tl_page WHERE id=?")
													->limit(1)
													->execute($this->Input->get('pid'));

						$ids[] = $this->Input->get('pid');
					}

					// Insert after an article
					else
					{
						$objParent = $this->Database->prepare("SELECT id, type FROM tl_page WHERE id=(SELECT pid FROM tl_article WHERE id=?)")
													->limit(1)
													->execute($this->Input->get('pid'));

						$ids[] = $objParent->id;
					}

					if ($objParent->numRows && $objParent->type == 'root')
					{
						$this->log('Attempt to insert an article into website root page '.$this->Input->get('pid'), 'tl_article checkPermission()', TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}
					break;

				case 'delete':
					$permission = 6;
					break;
			}

			// Check user permissions
			if ($this->Input->get('act') != 'show')
			{
				$pagemounts = array();

				// Get all allowed pages for the current user
				foreach ($this->User->pagemounts as $root)
				{
					$pagemounts[] = $root;
					$pagemounts = array_merge($pagemounts, $this->getChildRecords($root, 'tl_page'));
				}

				$pagemounts = array_unique($pagemounts);

				// Check each page
				foreach ($ids as $id)
				{
					if (!$error && !in_array($id, $pagemounts))
					{
						$this->log('Page ID ' . $id . ' was not mounted', 'tl_article checkPermission()', TL_ERROR);
						$error = true;
					}

					$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
											  ->limit(1)
											  ->execute($id);

					// Check whether the current user has permission for the current page
					if (!$error && $objPage->numRows)
					{
						if (!$this->User->isAllowed($permission, $objPage->row()))
						{
							$this->log('Not enough permissions to '. $this->Input->get('act') .' '. (strlen($this->Input->get('id')) ? 'article ID '. $this->Input->get('id') : ' articles') .' on page ID '. $id .' or paste it/them into page ID '. $id, 'tl_article checkPermission()', TL_ERROR);
							$error = true;
						}
					}
				}
			}
		}

		// Redirect if there is an error
		if ($error)
		{
			$this->redirect('contao/main.php?act=error');
		}
	}


	/**
	 * Add an image to each page in the tree
	 * @param array
	 * @param string
	 * @return string
	 */
	public function addIcon($row, $label)
	{
		$time = time();
		$published = ($row['published'] && ($row['start'] == '' || $row['start'] < $time) && ($row['stop'] == '' || $row['stop'] > $time));

		return $this->generateImage('articles'.($published ? '' : '_').'.gif') .' '. $label;
	}


	/**
	 * Auto-generate an article alias if it has not been set yet
	 * @param mixed
	 * @param object
	 * @return string
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate an alias if there is none
		if ($varValue == '')
		{
			$autoAlias = true;
			$varValue = standardize($dc->activeRecord->title);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_article WHERE id=? OR alias=?")
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
	 * Return all active page sections as array
	 * @param object
	 * @return array
	 */
	public function getActivePageSections(DataContainer $dc)
	{
		$arrSections = array('header', 'left', 'right', 'main', 'footer');

		// Show only active sections
		if ($dc->activeRecord->pid)
		{
			// Inherit the page settings
			$objPage = $this->getPageDetails($dc->activeRecord->pid);

			// Get the layout settings
			$objLayout = $this->Database->prepare("SELECT * FROM tl_layout WHERE id=? OR fallback=1 ORDER BY fallback")
										->limit(1)
										->execute($objPage->layout);

			// No layout specified
			if ($objLayout->numRows < 1)
			{
				return array('main');
			}

			$arrSections = array();

			// Header
			if ($objLayout->header)
			{
				$arrSections[] = 'header';
			}

			// Left column
			if ($objLayout->cols == '2cll' || $objLayout->cols == '3cl')
			{
				$arrSections[] = 'left';
			}

			// Right column
			if ($objLayout->cols == '2clr' || $objLayout->cols == '3cl')
			{
				$arrSections[] = 'right';
			}

			// Main column
			$arrSections[] = 'main';

			// Footer
			if ($objLayout->footer)
			{
				$arrSections[] = 'footer';
			}
		}

		$arrCustom = deserialize($objLayout->sections);

		// Always add the custom layout sections in "override all" mode
		if ($this->Input->get('act') == 'overrideAll')
		{
			$arrCustom = trimsplit(',', $GLOBALS['TL_CONFIG']['customSections']);
		}

		// Add the custom layout sections
		if (is_array($arrCustom) && count($arrCustom) > 0)
		{
			$arrSections = array_merge($arrSections, $arrCustom);
		}

		return $arrSections;
	}


	/**
	 * Return the edit article button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editArticle($row, $href, $label, $title, $icon, $attributes)
	{
		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
								  ->limit(1)
								  ->execute($row['pid']);

		return ($this->User->isAdmin || $this->User->isAllowed(4, $objPage->row())) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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
		if (!$this->User->isAdmin && count(preg_grep('/^tl_article::/', $this->User->alexf)) < 1)
		{
			return '';
		}

		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
								  ->limit(1)
								  ->execute($row['pid']);

		return ($this->User->isAdmin || $this->User->isAllowed(4, $objPage->row())) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : '';
	}


	/**
	 * Return the copy article button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyArticle($row, $href, $label, $title, $icon, $attributes, $table)
	{
		if ($GLOBALS['TL_DCA'][$table]['config']['closed'])
		{
			return '';
		}

		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
								  ->limit(1)
								  ->execute($row['pid']);

		return ($this->User->isAdmin || $this->User->isAllowed(5, $objPage->row())) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the cut article button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function cutArticle($row, $href, $label, $title, $icon, $attributes)
	{
		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
								  ->limit(1)
								  ->execute($row['pid']);

		return ($this->User->isAdmin || $this->User->isAllowed(5, $objPage->row())) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the paste article button
	 * @param object
	 * @param array
	 * @param string
	 * @param boolean
	 * @param array
	 * @return string
	 */
	public function pasteArticle(DataContainer $dc, $row, $table, $cr, $arrClipboard=false)
	{
		$imagePasteAfter = $this->generateImage('pasteafter.gif', sprintf($GLOBALS['TL_LANG'][$dc->table]['pasteafter'][1], $row['id']), 'class="blink"');
		$imagePasteInto = $this->generateImage('pasteinto.gif', sprintf($GLOBALS['TL_LANG'][$dc->table]['pasteinto'][1], $row['id']), 'class="blink"');

		if ($table == $GLOBALS['TL_DCA'][$dc->table]['config']['ptable'])
		{
			return ($row['type'] == 'root' || (!$this->User->isAdmin && !$this->User->isAllowed(5, $row)) || $cr) ? $this->generateImage('pasteinto_.gif', '', 'class="blink"').' ' : '<a href="'.$this->addToUrl('act='.$arrClipboard['mode'].'&amp;mode=2&amp;pid='.$row['id'].(!is_array($arrClipboard['id']) ? '&amp;id='.$arrClipboard['id'] : '')).'" title="'.specialchars(sprintf($GLOBALS['TL_LANG'][$dc->table]['pasteinto'][1], $row['id'])).'" onclick="Backend.getScrollOffset();">'.$imagePasteInto.'</a> ';
		}

		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
								  ->limit(1)
								  ->execute($row['pid']);

		return (($arrClipboard['mode'] == 'cut' && $arrClipboard['id'] == $row['id']) || ($arrClipboard['mode'] == 'cutAll' && in_array($row['id'], $arrClipboard['id'])) || (!$this->User->isAdmin && !$this->User->isAllowed(5, $objPage->row())) || $cr) ? $this->generateImage('pasteafter_.gif', '', 'class="blink"').' ' : '<a href="'.$this->addToUrl('act='.$arrClipboard['mode'].'&amp;mode=1&amp;pid='.$row['id'].(!is_array($arrClipboard['id']) ? '&amp;id='.$arrClipboard['id'] : '')).'" title="'.specialchars(sprintf($GLOBALS['TL_LANG'][$dc->table]['pasteafter'][1], $row['id'])).'" onclick="Backend.getScrollOffset();">'.$imagePasteAfter.'</a> ';
	}


	/**
	 * Return the delete article button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteArticle($row, $href, $label, $title, $icon, $attributes)
	{
		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
								  ->limit(1)
								  ->execute($row['pid']);

		return ($this->User->isAdmin || $this->User->isAllowed(6, $objPage->row())) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_article::published', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}		

		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
								  ->limit(1)
								  ->execute($row['pid']);

		if (!$this->User->isAdmin && !$this->User->isAllowed(4, $objPage->row()))
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_article::published', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish article ID "'.$intId.'"', 'tl_article toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('tl_article', $intId);
	
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_article']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_article']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_article SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_article', $intId);
	}
}

?>