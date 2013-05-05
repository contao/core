<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
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
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index',
				'alias' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 6,
			'fields'                  => array('published DESC', 'title', 'author'),
			'paste_button_callback'   => array('tl_article', 'pasteArticle'),
			'panelLayout'             => 'search'
		),
		'label' => array
		(
			'fields'                  => array('title', 'inColumn'),
			'format'                  => '%s <span style="color:#b3b3b3;padding-left:3px">[%s]</span>',
			'label_callback'          => array('tl_article', 'addIcon')
		),
		'global_operations' => array
		(
			'toggleNodes' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['toggleAll'],
				'href'                => '&amp;ptg=all',
				'class'               => 'header_toggle'
			),
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
				'label'               => &$GLOBALS['TL_LANG']['tl_article']['edit'],
				'href'                => 'table=tl_content',
				'icon'                => 'edit.gif',
				'button_callback'     => array('tl_article', 'editArticle')
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_article']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
				'button_callback'     => array('tl_article', 'editHeader')
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_article']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"',
				'button_callback'     => array('tl_article', 'copyArticle')
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_article']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"',
				'button_callback'     => array('tl_article', 'cutArticle')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_article']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
				'button_callback'     => array('tl_article', 'deleteArticle')
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_article']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
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
		'__selector__'                => array('protected'),
		'default'                     => '{title_legend},title,alias,author;{layout_legend},inColumn,keywords;{teaser_legend:hide},teaserCssID,showTeaser,teaser;{syndication_legend},printable;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{publish_legend},published,start,stop'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'protected'                   => 'groups'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'label'                   => array('ID'),
			'search'                  => true,
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'              => 'tl_page.title',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
		),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'search'                  => true,
			'eval'                    => array('mandatory'=>true, 'decodeEntities'=>true, 'maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['alias'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'search'                  => true,
			'eval'                    => array('rgxp'=>'alias', 'doNotCopy'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_article', 'generateAlias')
			),
			'sql'                     => "varbinary(128) NOT NULL default ''"

		),
		'author' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['author'],
			'default'                 => BackendUser::getInstance()->id,
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user.name',
			'eval'                    => array('doNotCopy'=>true, 'mandatory'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'hasOne', 'load'=>'eager')
		),
		'inColumn' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['inColumn'],
			'exclude'                 => true,
			'default'                 => 'main',
			'inputType'               => 'select',
			'options_callback'        => array('tl_article', 'getActivePageSections'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_article'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'keywords' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['keywords'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'search'                  => true,
			'eval'                    => array('style'=>'height:60px', 'decodeEntities'=>true),
			'sql'                     => "text NULL"
		),
		'showTeaser' => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['showTeaser'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'teaserCssID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['teaserCssID'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'teaser' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['teaser'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'search'                  => true,
			'eval'                    => array('rte'=>'tinyMCE', 'tl_class'=>'clr'),
			'sql'                     => "text NULL"
		),
		'printable' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['printable'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'options'                 => array('print', 'pdf', 'facebook', 'twitter', 'gplus'),
			'eval'                    => array('multiple'=>true),
			'reference'               => &$GLOBALS['TL_LANG']['tl_article'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'protected' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['protected'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true),
			'sql'                     => "blob NULL",
			'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
		),
		'guests' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['guests'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'cssID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['cssID'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'space' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['space'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'published' => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['published'],
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'start' => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['start'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'stop' => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_article']['stop'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		)
	)
);


/**
 * Class tl_article
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
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
		$GLOBALS['TL_DCA']['tl_page']['fields']['cuser']['default'] = $GLOBALS['TL_CONFIG']['defaultUser'] ?: $this->User->id;
		$GLOBALS['TL_DCA']['tl_page']['fields']['cgroup']['default'] = $GLOBALS['TL_CONFIG']['defaultGroup'] ?: $this->User->groups[0];

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

			$session['CURRENT']['IDS'] = (Input::get('act') == 'deleteAll') ? $delete_all : $edit_all;
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

		$error = false;
		$permission = 0;

		// Overwrite the session
		$this->Session->setData($session);

		// Check current action
		if (Input::get('act') && Input::get('act') != 'paste')
		{
			// Set ID of the article's page
			$objPage = $this->Database->prepare("SELECT pid FROM tl_article WHERE id=?")
									  ->limit(1)
									  ->execute(Input::get('id'));

			$ids = $objPage->numRows ? array($objPage->pid) : array();

			// Set permission
			switch (Input::get('act'))
			{
				case 'edit':
				case 'toggle':
					$permission = 4;
					break;

				case 'move':
					$permission = 5;
					$ids[] = Input::get('sid');
					break;

				// Do not insert articles into a website root page
				case 'create':
				case 'copy':
				case 'copyAll':
				case 'cut':
				case 'cutAll':
					$permission = 5;

					// Insert into a page
					if (Input::get('mode') == 2)
					{
						$objParent = $this->Database->prepare("SELECT id, type FROM tl_page WHERE id=?")
													->limit(1)
													->execute(Input::get('pid'));

						$ids[] = Input::get('pid');
					}

					// Insert after an article
					else
					{
						$objParent = $this->Database->prepare("SELECT id, type FROM tl_page WHERE id=(SELECT pid FROM tl_article WHERE id=?)")
													->limit(1)
													->execute(Input::get('pid'));

						$ids[] = $objParent->id;
					}

					if ($objParent->numRows && $objParent->type == 'root')
					{
						$this->log('Attempt to insert an article into website root page '.Input::get('pid'), 'tl_article checkPermission()', TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}
					break;

				case 'delete':
					$permission = 6;
					break;
			}

			// Check user permissions
			if (Input::get('act') != 'show')
			{
				$pagemounts = array();

				// Get all allowed pages for the current user
				foreach ($this->User->pagemounts as $root)
				{
					$pagemounts[] = $root;
					$pagemounts = array_merge($pagemounts, $this->Database->getChildRecords($root, 'tl_page'));
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
							$this->log('Not enough permissions to '. Input::get('act') .' '. (strlen(Input::get('id')) ? 'article ID '. Input::get('id') : ' articles') .' on page ID '. $id .' or paste it/them into page ID '. $id, 'tl_article checkPermission()', TL_ERROR);
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

		return '<a href="contao/main.php?do=feRedirect&amp;page='.$row['pid'].'&amp;article='.(($row['alias'] != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $row['alias'] : $row['id']).'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['view']).'" target="_blank">'.Image::getHtml('articles'.($published ? '' : '_').'.gif').'</a> '.$label;
	}


	/**
	 * Auto-generate an article alias if it has not been set yet
	 * @param mixed
	 * @param \DataContainer
	 * @return string
	 * @throws \Exception
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
	 * @param \DataContainer
	 * @return array
	 */
	public function getActivePageSections(DataContainer $dc)
	{
		$arrCustom = array();
		$arrSections = array('header', 'left', 'right', 'main', 'footer');

		// Show only active sections
		if ($dc->activeRecord->pid)
		{
			// Load the page
			$objPage = PageModel::findWithDetails($dc->activeRecord->pid);

			// Get the layout settings
			$objLayout = LayoutModel::findByPk($objPage->layout);

			// No layout specified
			if ($objLayout === null)
			{
				return array('main');
			}

			$arrSections = array();

			// Header
			if ($objLayout->rows == '2rwh' || $objLayout->rows == '3rw')
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
			if ($objLayout->rows == '2rwf' || $objLayout->rows == '3rw')
			{
				$arrSections[] = 'footer';
			}

			$arrCustom = deserialize($objLayout->sections);
		}

		// Always add the custom layout sections in "override all" mode
		if (Input::get('act') == 'overrideAll')
		{
			$arrCustom = trimsplit(',', $GLOBALS['TL_CONFIG']['customSections']);
		}

		// Add the custom layout sections
		if (!empty($arrCustom) && is_array($arrCustom))
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

		return ($this->User->isAdmin || $this->User->isAllowed(4, $objPage->row())) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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
			return Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
		}

		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
								  ->limit(1)
								  ->execute($row['pid']);

		return ($this->User->isAdmin || $this->User->isAllowed(4, $objPage->row())) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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

		return ($this->User->isAdmin || $this->User->isAllowed(5, $objPage->row())) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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

		return ($this->User->isAdmin || $this->User->isAllowed(5, $objPage->row())) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the paste article button
	 * @param \DataContainer
	 * @param array
	 * @param string
	 * @param boolean
	 * @param array
	 * @return string
	 */
	public function pasteArticle(DataContainer $dc, $row, $table, $cr, $arrClipboard=null)
	{
		$imagePasteAfter = Image::getHtml('pasteafter.gif', sprintf($GLOBALS['TL_LANG'][$dc->table]['pasteafter'][1], $row['id']));
		$imagePasteInto = Image::getHtml('pasteinto.gif', sprintf($GLOBALS['TL_LANG'][$dc->table]['pasteinto'][1], $row['id']));

		if ($table == $GLOBALS['TL_DCA'][$dc->table]['config']['ptable'])
		{
			return ($row['type'] == 'root' || (!$this->User->isAdmin && !$this->User->isAllowed(5, $row)) || $cr) ? Image::getHtml('pasteinto_.gif').' ' : '<a href="'.$this->addToUrl('act='.$arrClipboard['mode'].'&amp;mode=2&amp;pid='.$row['id'].(!is_array($arrClipboard['id']) ? '&amp;id='.$arrClipboard['id'] : '')).'" title="'.specialchars(sprintf($GLOBALS['TL_LANG'][$dc->table]['pasteinto'][1], $row['id'])).'" onclick="Backend.getScrollOffset()">'.$imagePasteInto.'</a> ';
		}

		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
								  ->limit(1)
								  ->execute($row['pid']);

		return (($arrClipboard['mode'] == 'cut' && $arrClipboard['id'] == $row['id']) || ($arrClipboard['mode'] == 'cutAll' && in_array($row['id'], $arrClipboard['id'])) || (!$this->User->isAdmin && !$this->User->isAllowed(5, $objPage->row())) || $cr) ? Image::getHtml('pasteafter_.gif').' ' : '<a href="'.$this->addToUrl('act='.$arrClipboard['mode'].'&amp;mode=1&amp;pid='.$row['id'].(!is_array($arrClipboard['id']) ? '&amp;id='.$arrClipboard['id'] : '')).'" title="'.specialchars(sprintf($GLOBALS['TL_LANG'][$dc->table]['pasteafter'][1], $row['id'])).'" onclick="Backend.getScrollOffset()">'.$imagePasteAfter.'</a> ';
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

		return ($this->User->isAdmin || $this->User->isAllowed(6, $objPage->row())) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1));
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
			return Image::getHtml($icon) . ' ';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
	}


	/**
	 * Disable/enable a user group
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions to edit
		Input::setGet('id', $intId);
		Input::setGet('act', 'toggle');
		$this->checkPermission();

		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_article::published', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish article ID "'.$intId.'"', 'tl_article toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$objVersions = new Versions('tl_article', $intId);
		$objVersions->initialize();

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

		$objVersions->create();
		$this->log('A new version of record "tl_article.id='.$intId.'" has been created'.$this->getParentEntries('tl_article', $intId), 'tl_article toggleVisibility()', TL_GENERAL);
	}
}
