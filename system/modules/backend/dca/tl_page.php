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
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Table tl_page
 */
$GLOBALS['TL_DCA']['tl_page'] = array
(

	// Config
	'config' => array
	(
		'label'                       => &$GLOBALS['TL_CONFIG']['websiteTitle'],
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_article'),
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_page', 'checkPermission'),
		),
		'onsubmit_callback' => array
		(
			array('tl_page', 'updateSitemap')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 5,
			'icon'                    => 'pagemounts.gif',
			'paste_button_callback'   => array('tl_page', 'pastePage')
		),
		'label' => array
		(
			'fields'                  => array('title'),
			'format'                  => '%s',
			'label_callback'          => array('tl_page', 'addImage')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'toggleNodes' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['toggleNodes'],
				'href'                => 'ptg=all',
				'class'               => 'header_toggle'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_page']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif',
				'button_callback'     => array('tl_page', 'editPage')
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_page']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
				'button_callback'     => array('tl_page', 'copyPage')
			),
			'copyChilds' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_page']['copyChilds'],
				'href'                => 'act=paste&amp;mode=copy&amp;childs=1',
				'icon'                => 'copychilds.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
				'button_callback'     => array('tl_page', 'copyPageWithSubpages')
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_page']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
				'button_callback'     => array('tl_page', 'cutPage')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_page']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_page', 'deletePage')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_page']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('type', 'autoforward', 'protected', 'includeLayout', 'includeCache', 'includeChmod', 'createSitemap'),
		'default'                     => 'title,alias,type;followup,start,stop',
		'regular'                     => 'title,alias,type;language,pageTitle,description;protected;includeLayout;includeCache;includeChmod;cssClass,tabindex,accesskey;hide,guests,noSearch;published,start,stop',
		'redirect'                    => 'title,alias,type;url,redirect,target;protected;includeLayout;includeCache;includeChmod;cssClass,tabindex,accesskey;hide,guests;published,start,stop',
		'forward'                     => 'title,alias,type;jumpTo,redirect;protected;includeLayout;includeCache;includeChmod;cssClass,tabindex,accesskey;hide,guests;published,start,stop',
		'root'                        => 'title,alias,type;dns,language,fallback;pageTitle,adminEmail;includeLayout;includeCache;createSitemap;includeChmod;published,start,stop',
		'error_403'                   => 'title,alias,type;language,pageTitle,description;autoforward;includeLayout;includeCache;includeChmod;published,start,stop',
		'error_404'                   => 'title,alias,type;language,pageTitle,description;autoforward;includeLayout;includeCache;includeChmod;published,start,stop'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'autoforward'                 => 'jumpTo,redirect',
		'protected'                   => 'groups',
		'includeLayout'               => 'layout',
		'includeCache'                => 'cache',
		'includeChmod'                => 'cuser,cgroup,chmod',
		'createSitemap'               => 'sitemapName'
	),

	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['alias'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'doNotCopy'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128),
			'save_callback' => array
			(
				array('tl_page', 'generateAlias')
			)

		),
		'adminEmail' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['adminEmail'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'email', 'maxlength'=>255)
		),
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['type'],
			'default'                 => 'regular',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_page', 'pageTypes'),
			'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true),
			'reference'               => &$GLOBALS['TL_LANG']['PTY']
		),
		'language' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['language'],
			'default'                 => $GLOBALS['TL_LANGUAGE'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'alpha', 'maxlength'=>2, 'nospace'=>true)
		),
		'pageTitle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['pageTitle'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255)
		),
		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['description'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('style'=>'height:80px;')
		),
		'url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['url'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255)
		),
		'target' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['target'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'redirect' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['redirect'],
			'default'                 => 'permanent',
			'exclude'                 => true,
			'inputType'               => 'radio',
			'options'                 => array('temporary', 'permanent'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_page']
		),
		'autoforward' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['autoforward'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'dns' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['dns'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255)
		),
		'fallback' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['fallback'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'jumpTo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['jumpTo'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'eval'                    => array('fieldType'=>'radio', 'helpwizard'=>true),
			'explanation'             => 'jumpTo'
		),
		'protected' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['protected'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('multiple'=>true)
		),
		'includeLayout' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['includeLayout'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'layout' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['layout'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_layout.name'
		),
		'includeCache' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['includeCache'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'cache' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['cache'],
			'default'                 => 0,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array(0, 15, 30, 60, 300, 900, 1800, 3600, 21600, 43200, 86400, 259200, 604800),
			'reference'               => &$GLOBALS['TL_LANG']['CACHE']
		),
		'includeChmod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['includeChmod'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'chmod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['chmod'],
			'default'                 => $GLOBALS['TL_CONFIG']['defaultChmod'],
			'exclude'                 => true,
			'inputType'               => 'chmod',
		),
		'cuser' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['cuser'],
			'default'                 => $GLOBALS['TL_CONFIG']['defaultUser'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user.username',
		),
		'cgroup' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['cgroup'],
			'default'                 => $GLOBALS['TL_CONFIG']['defaultGroup'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user_group.name',
		),
		'createSitemap' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['createSitemap'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'sitemapName' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['sitemapName'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'unique'=>true, 'rgxp'=>'alnum', 'decodeEntities'=>true, 'maxlength'=>32)
		),
		'hide' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['hide'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'noSearch' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['noSearch'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'guests' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['guests'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'cssClass' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['cssClass'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>64)
		),
		'tabindex' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['tabindex'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit', 'nospace'=>true)
		),
		'accesskey' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['accesskey'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'maxlength'=>1)
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['published'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true)
		),
		'start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['start'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>10, 'rgxp'=>'date', 'datepicker'=>$this->getDatePickerString())
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>10, 'rgxp'=>'date', 'datepicker'=>$this->getDatePickerString())
		)
	)
);


/**
 * Class tl_page
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_page extends Backend
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

		$groups = $this->User->groups;

		// Set default user and group
		$GLOBALS['TL_DCA']['tl_page']['fields']['cuser']['default'] = strlen($GLOBALS['TL_CONFIG']['cuser']) ? $GLOBALS['TL_CONFIG']['cuser'] : $this->User->id;
		$GLOBALS['TL_DCA']['tl_page']['fields']['cgroup']['default'] = strlen($GLOBALS['TL_CONFIG']['cgroup']) ? $GLOBALS['TL_CONFIG']['cgroup'] : $groups[0];

		// Restrict user permissions
		$GLOBALS['TL_DCA']['tl_page']['list']['sorting']['root'] = $this->User->pagemounts;

		// Set allowed page IDs (edit/delete all)
		if ($this->Input->get('act') == 'editAll' || $this->Input->get('act') == 'deleteAll')
		{
			$session = $this->Session->getData();

			if (is_array($session['CURRENT']['IDS']))
			{
				$edit_all = array();
				$delete_all = array();

				foreach ($session['CURRENT']['IDS'] as $id)
				{
					$objPage = $this->Database->prepare("SELECT id, pid, includeChmod, chmod, cuser, cgroup FROM tl_page WHERE id=?")
											  ->limit(1)
											  ->execute($id);

					if ($objPage->numRows < 1)
					{
						continue;
					}

					$row = $objPage->fetchAssoc();

					if ($this->User->isAllowed(1, $row))
					{
						$edit_all[] = $id;
					}

					if ($this->User->isAllowed(3, $row) && !in_array($id, $this->User->pagemounts))
					{
						$delete_all[] = $id;
					}
				}
			}

			// Overwrite session
			$session['CURRENT']['IDS'] = ($this->Input->get('act') == 'deleteAll') ? $delete_all : $edit_all;
			$this->Session->setData($session);
		}

		// Add access rights to new pages
		if ($this->Input->get('act') == 'create')
		{
			$GLOBALS['TL_DCA']['tl_page']['fields']['includeChmod']['default'] = 1;
		}

		// Check permissions to save and create new
		if ($this->Input->get('act') == 'edit')
		{
			$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=(SELECT pid FROM tl_page WHERE id=?)")
									  ->limit(1)
									  ->execute($this->Input->get('id'));

			if ($objPage->numRows && !$this->User->isAllowed(2, $objPage->fetchAssoc()))
			{
				$GLOBALS['TL_DCA']['tl_page']['config']['closed'] = true;
			}
		}

		// Check current action
		if ($this->Input->get('act') && $this->Input->get('act') != 'paste')
		{
			$error = false;
			$ids = strlen(CURRENT_ID) ? array(CURRENT_ID) : array();

			// Set permission
			switch ($this->Input->get('act'))
			{
				case 'edit':
					$permission = 1;
					break;

				case 'move':
					$permission = 2;
					$ids[] = $this->Input->get('sid');
					break;

				case 'new':
				case 'copy':
				case 'cut':
					$permission = 2;
					$ids[] = $this->Input->get('pid');
					break;

				case 'delete':
					$permission = 3;
					break;
			}

			// Check user permissions
			if ($this->Input->get('act') != 'show')
			{
				$pagemounts = array();

				// Get all allowed pages for the current user
				foreach ($this->User->pagemounts as $root)
				{
					if ($this->Input->get('act') != 'delete')
					{
						$pagemounts[] = $root;
					}

					$pagemounts = array_merge($pagemounts, $this->getChildRecords($root, 'tl_page'));
				}

				$pagemounts = array_unique($pagemounts);

				// Check each page
				foreach ($ids as $id)
				{
					if (!in_array($id, $pagemounts))
					{
						$error = true;
						break;
					}

					// Get page object
					$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
											  ->limit(1)
											  ->execute($id);

					if ($objPage->numRows < 1)
					{
						continue;
					}

					// Check whether the current user is allowed to access the current page
					if (!$this->User->isAllowed($permission, $objPage->fetchAssoc()))
					{
						$error = true;
						break;
					}
				}
			}
		}

		// Redirect if there is an error
		if ($error)
		{
			$this->log('Not enough permissions to modify page ID "'.$id.'"', 'tl_page checkPermission', 5);
			$this->redirect('typolight/main.php?act=error');
		}
	}


	/**
	 * Autogenerate a page alias if it has not been set yet
	 * @param mixed
	 * @param object
	 * @return string
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if (!strlen($varValue))
		{
			$objTitle = $this->Database->prepare("SELECT title FROM tl_page WHERE id=?")
									   ->limit(1)
									   ->execute($dc->id);

			$autoAlias = true;
			$varValue = standardize($objTitle->title);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_page WHERE id=? OR alias=?")
								   ->execute($dc->id, $varValue);

		// Check whether the page alias exists
		if ($objAlias->numRows > 1)
		{
			$arrDomains = array();

			while ($objAlias->next())
			{
				$_pid = $objAlias->id;
				$_type = '';

				do
				{
					$objParentPage = $this->Database->prepare("SELECT id, pid, alias, type, dns FROM tl_page WHERE id=?")
													->limit(1)
													->execute($_pid);

					if ($objParentPage->numRows < 1)
					{
						break;
					}

					$_pid = $objParentPage->pid;
					$_type = $objParentPage->type;
				}
				while ($_pid > 0 && $_type != 'root');

				$arrDomains[] = ($objParentPage->numRows && ($objParentPage->type == 'root' || $objParentPage->pid > 0)) ? $objParentPage->dns : '';
			}

			$arrUnique = array_unique($arrDomains);

			if (count($arrDomains) != count($arrUnique))
			{
				if (!$autoAlias)
				{
					throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
				}

				$varValue .= '.' . $dc->id;
			}
		}

		return $varValue;
	}


	/**
	 * Add an image to each page in the tree
	 * @param array
	 * @param string
	 * @param string
	 * @param object
	 * @return string
	 */
	public function addImage($row, $label, $imageAttribute, DataContainer $dc)
	{
		$sub = 0;
		$image = ''.$row['type'].'.gif';

		// Page not published or not active
		if ((!$row['published'] || $row['start'] && $row['start'] > time() || $row['stop'] && $row['stop'] < time()))
		{
			$sub += 1;
		}

		// Page hidden from menu
		if ($row['hide'] && !in_array($row['type'], array('redirect', 'forward', 'root', 'error_403', 'error_404')))
		{
			$sub += 2;
		}

		// Page protected
		if ($row['protected'] && !in_array($row['type'], array('root', 'error_403', 'error_404')))
		{
			$sub += 4;
		}

		// Get image name
		if ($sub > 0)
		{
			$image = ''.$row['type'].'_'.$sub.'.gif';
		}

		if ($row['type'] == 'root' || $this->Input->get('do') == 'article')
		{
			$label = '<strong>' . $label . '</strong>';
		}

		// Return image
		return '<a href="'.$this->generateFrontendUrl($row).'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['view']).'"' . (($dc->table != 'tl_page') ? ' class="tl_gray"' : ''). LINK_NEW_WINDOW_BLUR . '>'.$this->generateImage($image, '', $imageAttribute).'</a> '.$label;
	}


	/**
	 * Return the edit page button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editPage($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->isAllowed(1, $row)) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the copy page button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyPage($row, $href, $label, $title, $icon, $attributes, $table)
	{
		if ($GLOBALS['TL_DCA'][$table]['config']['closed'])
		{
			return '';
		}

		return ($this->User->isAdmin || $this->User->isAllowed(2, $row)) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the copy page with subpages button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyPageWithSubpages($row, $href, $label, $title, $icon, $attributes, $table)
	{
		if ($GLOBALS['TL_DCA'][$table]['config']['closed'])
		{
			return '';
		}

		$objSubpages = $this->Database->prepare("SELECT * FROM tl_page WHERE pid=?")
									  ->limit(1)
									  ->execute($row['id']);

		return ($objSubpages->numRows && ($this->User->isAdmin || $this->User->isAllowed(2, $row))) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the cut page button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function cutPage($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->isAllowed(2, $row)) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the paste page button
	 * @param object
	 * @param array
	 * @param string
	 * @param boolean
	 * @param array
	 * @return string
	 */
	public function pastePage(DataContainer $dc, $row, $table, $cr, $arrClipboard=false)
	{
		$disablePA = false;
		$disablePI = false;

		// Disable all buttons if there is a circular reference
		if ($arrClipboard !== false && $arrClipboard['mode'] == 'cut' && ($cr == 1 || $arrClipboard['id'] == $row['id']))
		{
			$disablePA = true;
			$disablePI = true;
		}

		// Check permissions if the user is not an administrator
		if (!$this->User->isAdmin)
		{
			// Disable "paste into" button if there is no permission 2 for the current page
			if (!$disablePI && !$this->User->isAllowed(2, $row))
			{
				$disablePI = true;
			}

			$objPage = $this->Database->prepare("SELECT * FROM " . $table . " WHERE id=?")
									  ->limit(1)
									  ->execute($row['pid']);

			// Disable "paste after" button if there is no permission 2 for the parent page
			if (!$disablePA && $objPage->numRows)
			{
				if (!$this->User->isAllowed(2, $objPage->fetchAssoc()))
				{
					$disablePA = true;
				}
			}

			// Disable "paste after" button if the parent page is a root page and the user is not an administrator
			if (!$disablePA && ($row['pid'] < 1 || in_array($row['id'], $dc->rootIds)))
			{
				$disablePA = true;
			}
		}

		// Return the buttons
		$imagePasteAfter = $this->generateImage('pasteafter.gif', sprintf($GLOBALS['TL_LANG'][$table]['pasteafter'][1], $row['id']), 'class="blink"');
		$imagePasteInto = $this->generateImage('pasteinto.gif', sprintf($GLOBALS['TL_LANG'][$table]['pasteinto'][1], $row['id']), 'class="blink"');

		if ($row['id'] > 0)
		{
			$return = $disablePA ? $this->generateImage('pasteafter_.gif', '', 'class="blink"').' ' : '<a href="'.$this->addToUrl('act='.$arrClipboard['mode'].'&amp;mode=1&amp;pid='.$row['id'].'&amp;id='.$arrClipboard['id']).'" title="'.specialchars(sprintf($GLOBALS['TL_LANG'][$table]['pasteafter'][1], $row['id'])).'" onclick="Backend.getScrollOffset();">'.$imagePasteAfter.'</a> ';
		}

		return $return.($disablePI ? $this->generateImage('pasteinto_.gif', '', 'class="blink"').' ' : '<a href="'.$this->addToUrl('act='.$arrClipboard['mode'].'&amp;mode=2&amp;pid='.$row['id'].'&amp;id='.$arrClipboard['id']).'" title="'.specialchars(sprintf($GLOBALS['TL_LANG'][$table]['pasteinto'][1], $row['id'])).'" onclick="Backend.getScrollOffset();">'.$imagePasteInto.'</a> ');
	}


	/**
	 * Return the delete page button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deletePage($row, $href, $label, $title, $icon, $attributes)
	{
		$root = func_get_arg(7);
		return ($this->User->isAdmin || $this->User->isAllowed(3, $row) && !in_array($row['id'], $root)) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Returns all allowed page types as array
	 * @param object
	 * @return string
	 */
	public function pageTypes(DataContainer $dc)
	{
		$arrOptions = array();

		foreach (array_keys($GLOBALS['TL_PTY']) as $pty)
		{
			if ($pty == $dc->value || $this->User->hasAccess($pty, 'alpty'))
			{
				$arrOptions[] = $pty;
			}
		}

		return $arrOptions;
	}


	/**
	 * Recursively add pages to a sitemap
	 * @param object
	 */
	public function updateSitemap(DataContainer $dc)
	{
		$this->import('Automator');
		$this->Automator->generateSitemap($dc->id);
	}
}

?>