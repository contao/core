<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Table tl_news_feed
 */
$GLOBALS['TL_DCA']['tl_news_feed'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_news_feed', 'checkPermission'),
			array('tl_news_feed', 'generateFeed')
		),
		'onsubmit_callback' => array
		(
			array('tl_news_feed', 'scheduleUpdate')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'alias' => 'index'
			)
		),
		'backlink'                    => 'do=news'
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
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			),
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news_feed']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news_feed']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news_feed']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news_feed']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{title_legend},title,alias,language;{archives_legend},archives;{config_legend},format,source,maxItems,feedBase,description'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news_feed']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news_feed']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'alias', 'unique'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_news_feed', 'checkFeedAlias')
			),
			'sql'                     => "varchar(128) COLLATE utf8_bin NOT NULL default ''"
		),
		'language' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news_feed']['language'],
			'exclude'                 => true,
			'search'                  => true,
			'filter'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>32, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'archives' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news_feed']['archives'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'checkbox',
			'options_callback'        => array('tl_news_feed', 'getAllowedArchives'),
			'eval'                    => array('multiple'=>true, 'mandatory'=>true),
			'sql'                     => "blob NULL"
		),
		'format' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news_feed']['format'],
			'default'                 => 'rss',
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options'                 => array('rss'=>'RSS 2.0', 'atom'=>'Atom'),
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'source' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news_feed']['source'],
			'default'                 => 'source_teaser',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('source_teaser', 'source_text'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_news_feed'],
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'maxItems' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news_feed']['maxItems'],
			'default'                 => 25,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
		),
		'feedBase' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news_feed']['feedBase'],
			'default'                 => Environment::get('base'),
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('trailingSlash'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news_feed']['description'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('style'=>'height:60px', 'tl_class'=>'clr'),
			'sql'                     => "text NULL"
		)
	)
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class tl_news_feed extends Backend
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
	 * Check permissions to edit table tl_news_archive
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set the root IDs
		if (!is_array($this->User->newsfeeds) || empty($this->User->newsfeeds))
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->newsfeeds;
		}

		$GLOBALS['TL_DCA']['tl_news_feed']['list']['sorting']['root'] = $root;

		// Check permissions to add feeds
		if (!$this->User->hasAccess('create', 'newsfeedp'))
		{
			$GLOBALS['TL_DCA']['tl_news_feed']['config']['closed'] = true;
		}

		// Check current action
		switch (Input::get('act'))
		{
			case 'create':
			case 'select':
				// Allow
				break;

			case 'edit':
				// Dynamically add the record to the user profile
				if (!in_array(Input::get('id'), $root))
				{
					$arrNew = $this->Session->get('new_records');

					if (is_array($arrNew['tl_news_feed']) && in_array(Input::get('id'), $arrNew['tl_news_feed']))
					{
						// Add permissions on user level
						if ($this->User->inherit == 'custom' || !$this->User->groups[0])
						{
							$objUser = $this->Database->prepare("SELECT newsfeeds, newsfeedp FROM tl_user WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->id);

							$arrNewsfeedp = deserialize($objUser->newsfeedp);

							if (is_array($arrNewsfeedp) && in_array('create', $arrNewsfeedp))
							{
								$arrNewsfeeds = deserialize($objUser->newsfeeds);
								$arrNewsfeeds[] = Input::get('id');

								$this->Database->prepare("UPDATE tl_user SET newsfeeds=? WHERE id=?")
											   ->execute(serialize($arrNewsfeeds), $this->User->id);
							}
						}

						// Add permissions on group level
						elseif ($this->User->groups[0] > 0)
						{
							$objGroup = $this->Database->prepare("SELECT newsfeeds, newsfeedp FROM tl_user_group WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->groups[0]);

							$arrNewsfeedp = deserialize($objGroup->newsfeedp);

							if (is_array($arrNewsfeedp) && in_array('create', $arrNewsfeedp))
							{
								$arrNewsfeeds = deserialize($objGroup->newsfeeds);
								$arrNewsfeeds[] = Input::get('id');

								$this->Database->prepare("UPDATE tl_user_group SET newsfeeds=? WHERE id=?")
											   ->execute(serialize($arrNewsfeeds), $this->User->groups[0]);
							}
						}

						// Add new element to the user object
						$root[] = Input::get('id');
						$this->User->newsfeeds = $root;
					}
				}
				// No break;

			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array(Input::get('id'), $root) || (Input::get('act') == 'delete' && !$this->User->hasAccess('delete', 'newsfeedp')))
				{
					$this->log('Not enough permissions to '.Input::get('act').' news feed ID "'.Input::get('id').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if (Input::get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'newsfeedp'))
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
				if (strlen(Input::get('act')))
				{
					$this->log('Not enough permissions to '.Input::get('act').' news feeds', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Check for modified news feeds and update the XML files if necessary
	 */
	public function generateFeed()
	{
		$session = $this->Session->get('news_feed_updater');

		if (!is_array($session) || empty($session))
		{
			return;
		}

		$this->import('News');

		foreach ($session as $id)
		{
			$this->News->generateFeedsByArchive($id);
		}

		$this->import('Automator');
		$this->Automator->generateSitemap();

		$this->Session->set('news_feed_updater', null);
	}


	/**
	 * Schedule a news feed update
	 *
	 * This method is triggered when a single news archive or multiple news
	 * archives are modified (edit/editAll).
	 *
	 * @param DataContainer $dc
	 */
	public function scheduleUpdate(DataContainer $dc)
	{
		// Return if there is no ID
		if (!$dc->id)
		{
			return;
		}

		// Store the ID in the session
		$session = $this->Session->get('news_feed_updater');
		$session[] = $dc->id;
		$this->Session->set('news_feed_updater', array_unique($session));
	}


	/**
	 * Return the IDs of the allowed news archives as array
	 *
	 * @return array
	 */
	public function getAllowedArchives()
	{
		if ($this->User->isAdmin)
		{
			$objArchive = NewsArchiveModel::findAll();
		}
		else
		{
			$objArchive = NewsArchiveModel::findMultipleByIds($this->User->news);
		}

		$return = array();

		if ($objArchive !== null)
		{
			while ($objArchive->next())
			{
				$return[$objArchive->id] = $objArchive->title;
			}
		}

		return $return;
	}


	/**
	 * Check the RSS-feed alias
	 *
	 * @param mixed         $varValue
	 * @param DataContainer $dc
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 */
	public function checkFeedAlias($varValue, DataContainer $dc)
	{
		// No change or empty value
		if ($varValue == $dc->value || $varValue == '')
		{
			return $varValue;
		}

		$varValue = standardize($varValue); // see #5096

		$this->import('Automator');
		$arrFeeds = $this->Automator->purgeXmlFiles(true);

		// Alias exists
		if (array_search($varValue, $arrFeeds) !== false)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		return $varValue;
	}
}
