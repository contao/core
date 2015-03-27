<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao\Database;


/**
 * Adjust the database if the system is updated.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Updater extends \Controller
{

	/**
	 * Import the Database object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}


	/**
	 * Version 2.8.0 update
	 */
	public function run28Update()
	{
		// Database changes
		$this->Database->query("ALTER TABLE `tl_layout` ADD `script` text NULL");
		$this->Database->query("ALTER TABLE `tl_member` ADD `dateAdded` int(10) unsigned NOT NULL default '0'");
		$this->Database->query("ALTER TABLE `tl_member` ADD `currentLogin` int(10) unsigned NOT NULL default '0'");
		$this->Database->query("ALTER TABLE `tl_member` ADD `lastLogin` int(10) unsigned NOT NULL default '0'");
		$this->Database->query("ALTER TABLE `tl_user` ADD `dateAdded` int(10) unsigned NOT NULL default '0'");
		$this->Database->query("ALTER TABLE `tl_user` ADD `currentLogin` int(10) unsigned NOT NULL default '0'");
		$this->Database->query("ALTER TABLE `tl_user` ADD `lastLogin` int(10) unsigned NOT NULL default '0'");
		$this->Database->query("ALTER TABLE `tl_comments` ADD `source` varchar(32) NOT NULL default ''");
		$this->Database->query("ALTER TABLE `tl_comments` ADD KEY `source` (`source`)");
		$this->Database->query("ALTER TABLE `tl_layout` CHANGE `mootools` `mootools` text NULL");
		$this->Database->query("ALTER TABLE `tl_comments` CHANGE `pid` `parent` int(10) unsigned NOT NULL default '0'");
		$this->Database->query("UPDATE tl_member SET dateAdded=tstamp, currentLogin=tstamp");
		$this->Database->query("UPDATE tl_user SET dateAdded=tstamp, currentLogin=tstamp");
		$this->Database->query("UPDATE tl_layout SET mootools='moo_accordion' WHERE mootools='moo_default'");
		$this->Database->query("UPDATE tl_comments SET source='tl_content'");
		$this->Database->query("UPDATE tl_module SET cal_format='next_365', type='eventlist' WHERE type='upcoming_events'");

		// Get all front end groups
		$objGroups = $this->Database->execute("SELECT id FROM tl_member_group");
		$strGroups = serialize($objGroups->fetchEach('id'));

		// Update protected elements
		$this->Database->prepare("UPDATE tl_page SET groups=? WHERE protected=1 AND groups=''")->execute($strGroups);
		$this->Database->prepare("UPDATE tl_content SET groups=? WHERE protected=1 AND groups=''")->execute($strGroups);
		$this->Database->prepare("UPDATE tl_module SET groups=? WHERE protected=1 AND groups=''")->execute($strGroups);

		// Update layouts
		$objLayout = $this->Database->execute("SELECT id, mootools FROM tl_layout");

		while ($objLayout->next())
		{
			$mootools = array('moo_mediabox');

			if ($objLayout->mootools != '')
			{
				$mootools[] = $objLayout->mootools;
			}

			$this->Database->prepare("UPDATE tl_layout SET mootools=? WHERE id=?")
						   ->execute(serialize($mootools), $objLayout->id);
		}

		// Update event reader
		if (!file_exists(TL_ROOT . '/templates/event_default.tpl'))
		{
			$this->Database->execute("UPDATE tl_module SET cal_template='event_full' WHERE cal_template='event_default'");
		}

		// News comments
		$objComment = $this->Database->execute("SELECT * FROM tl_news_comments");

		while ($objComment->next())
		{
			$arrSet = $objComment->row();

			$arrSet['source'] = 'tl_news';
			$arrSet['parent'] = $arrSet['pid'];
			unset($arrSet['id']);
			unset($arrSet['pid']);

			$this->Database->prepare("INSERT INTO tl_comments %s")->set($arrSet)->execute();
		}

		// Delete system/modules/news/Comments.php
		$this->import('Files');
		$this->Files->delete('system/modules/news/Comments.php');
	}


	/**
	 * Version 2.9.0 update
	 */
	public function run29Update()
	{
		// Create the themes table
		$this->Database->query(
			"CREATE TABLE `tl_theme` (
			  `id` int(10) unsigned NOT NULL auto_increment,
			  `tstamp` int(10) unsigned NOT NULL default '0',
			  `name` varchar(128) NOT NULL default '',
			  `author` varchar(128) NOT NULL default '',
			  `screenshot` varchar(255) NOT NULL default '',
			  `folders` blob NULL,
			  `templates` varchar(255) NOT NULL default '',
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;"
		);

		// Add a PID column to the child tables
		$this->Database->query("ALTER TABLE `tl_module` ADD `pid` int(10) unsigned NOT NULL default '0'");
		$this->Database->query("ALTER TABLE `tl_style_sheet` ADD `pid` int(10) unsigned NOT NULL default '0'");
		$this->Database->query("ALTER TABLE `tl_layout` ADD `pid` int(10) unsigned NOT NULL default '0'");
		$this->Database->query("UPDATE tl_module SET pid=1");
		$this->Database->query("UPDATE tl_style_sheet SET pid=1");
		$this->Database->query("UPDATE tl_layout SET pid=1");

		// Create a theme from the present resources
		$this->Database->prepare("INSERT INTO tl_theme SET tstamp=?, name=?")
					   ->execute(time(), \Config::get('websiteTitle'));

		// Adjust the back end user permissions
		$this->Database->query("ALTER TABLE `tl_user` ADD `themes` blob NULL");
		$this->Database->query("ALTER TABLE `tl_user_group` ADD `themes` blob NULL");

		// Adjust the user and group rights
		$objUser = $this->Database->execute("SELECT id, modules, 'tl_user' AS tbl FROM tl_user WHERE modules!='' UNION SELECT id, modules, 'tl_user_group' AS tbl FROM tl_user_group WHERE modules!=''");

		while ($objUser->next())
		{
			$modules = deserialize($objUser->modules);

			if (!is_array($modules) || empty($modules))
			{
				continue;
			}

			$themes = array();

			foreach ($modules as $k=>$v)
			{
				if ($v == 'css' || $v == 'modules ' || $v == 'layout')
				{
					$themes[] = $v;
					unset($modules[$k]);
				}
			}

			if (!empty($themes))
			{
				$modules[] = 'themes';
			}

			$modules = array_values($modules);

			$set = array
			(
				'modules' => (!empty($modules) ? serialize($modules) : null),
				'themes'  => (!empty($themes) ? serialize($themes) : null)
			);

			$this->Database->prepare("UPDATE " . $objUser->tbl . " %s WHERE id=?")
						   ->set($set)
						   ->execute($objUser->id);
		}

		// Featured news
		if ($this->Database->fieldExists('news_featured', 'tl_module'))
		{
			$this->Database->query("ALTER TABLE `tl_module` CHANGE `news_featured` `news_featured` varchar(16) NOT NULL default ''");
			$this->Database->query("UPDATE tl_module SET news_featured='featured' WHERE news_featured='1'");
		}

		// Other version 2.9 updates
		$this->Database->query("UPDATE tl_member SET country='gb' WHERE country='uk'");
		$this->Database->query("ALTER TABLE `tl_module` CHANGE `news_jumpToCurrent` `news_jumpToCurrent` varchar(16) NOT NULL default ''");
		$this->Database->query("UPDATE tl_module SET news_jumpToCurrent='show_current' WHERE news_jumpToCurrent=1");
		$this->Database->query("ALTER TABLE `tl_user` ADD `useCE` char(1) NOT NULL default ''");
		$this->Database->query("UPDATE tl_user SET useCE=1");
	}


	/**
	 * Version 2.9.2 update
	 */
	public function run292Update()
	{
		$this->Database->query("ALTER TABLE `tl_calendar_events` CHANGE `startTime` `startTime` int(10) unsigned NULL");
		$this->Database->query("ALTER TABLE `tl_calendar_events` CHANGE `endTime` `endTime` int(10) unsigned NULL");
		$this->Database->query("ALTER TABLE `tl_calendar_events` CHANGE `startDate` `startDate` int(10) unsigned NULL");
		$this->Database->query("ALTER TABLE `tl_calendar_events` CHANGE `endDate` `endDate` int(10) unsigned NULL");
		$this->Database->query("UPDATE tl_calendar_events SET endDate=null WHERE endDate=0");
	}


	/**
	 * Version 2.10.0 update
	 */
	public function run210Update()
	{
		$this->Database->query("ALTER TABLE `tl_style` ADD `positioning` char(1) NOT NULL default ''");
		$this->Database->query("UPDATE `tl_style` SET `positioning`=`size`");
		$this->Database->query("UPDATE `tl_module` SET `guests`=1 WHERE `type`='lostPassword' OR `type`='registration'");
		$this->Database->query("UPDATE `tl_news` SET `teaser`=CONCAT('<p>', teaser, '</p>') WHERE `teaser`!='' AND `teaser` NOT LIKE '<p>%'");
	}


	/**
	 * Version 3.0.0 update
	 */
	public function run300Update()
	{
		// Create the files table
		$this->Database->query(
			"CREATE TABLE `tl_files` (
			  `id` int(10) unsigned NOT NULL auto_increment,
			  `pid` binary(16) NULL,
			  `tstamp` int(10) unsigned NOT NULL default '0',
			  `uuid` binary(16) NULL,
			  `type` varchar(16) NOT NULL default '',
			  `path` varchar(1022) NOT NULL default '',
			  `extension` varchar(16) NOT NULL default '',
			  `hash` varchar(32) NOT NULL default '',
			  `found` char(1) NOT NULL default '1',
			  `name` varchar(255) NOT NULL default '',
			  `meta` blob NULL,
			  PRIMARY KEY  (`id`),
			  KEY `pid` (`pid`),
			  UNIQUE KEY `uuid` (`uuid`),
			  KEY `extension` (`extension`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;"
		);

		// Add the "numberOfItems" field
		$this->Database->query("ALTER TABLE `tl_module` ADD `numberOfItems` smallint(5) unsigned NOT NULL default '0'");
		$this->Database->query("UPDATE `tl_module` SET `numberOfItems`=`rss_numberOfItems` WHERE `rss_numberOfItems`>0");
		$this->Database->query("UPDATE `tl_module` SET `numberOfItems`=`news_numberOfItems` WHERE `news_numberOfItems`>0");

		// Add the "addMooTools" field
		$this->Database->query("ALTER TABLE `tl_layout` ADD `addMooTools` char(1) NOT NULL default ''");
		$this->Database->query("UPDATE `tl_layout` SET `addMooTools`=1 WHERE `mootools`!=''");

		// Add the "notified" field
		$this->Database->query("ALTER TABLE `tl_comments` ADD `notified` char(1) NOT NULL default ''");
		$this->Database->query("UPDATE `tl_comments` SET `notified`=1");

		// Add the "rows" field
		$this->Database->query("ALTER TABLE `tl_layout` ADD `rows` varchar(8) NOT NULL default ''");
		$this->Database->query("UPDATE `tl_layout` SET `rows`='1rw' WHERE `header`='' AND `footer`=''");
		$this->Database->query("UPDATE `tl_layout` SET `rows`='2rwh' WHERE `header`!='' AND `footer`=''");
		$this->Database->query("UPDATE `tl_layout` SET `rows`='2rwf' WHERE `header`='' AND `footer`!=''");
		$this->Database->query("UPDATE `tl_layout` SET `rows`='3rw' WHERE `header`!='' AND `footer`!=''");

		// Update the "mooType" field
		$this->Database->query("UPDATE `tl_content` SET `mooType`='mooStart' WHERE `mooType`='start'");
		$this->Database->query("UPDATE `tl_content` SET `mooType`='mooStop' WHERE `mooType`='stop'");
		$this->Database->query("UPDATE `tl_content` SET `mooType`='mooSingle' WHERE `mooType`='single'");

		// Add the "framework" field
		$this->Database->query("ALTER TABLE `tl_layout` ADD `framework` varchar(255) NOT NULL default ''");
		$this->Database->query("UPDATE `tl_layout` SET `framework`='a:2:{i:0;s:10:\"layout.css\";i:1;s:11:\"tinymce.css\";}'");
		$this->Database->query("UPDATE `tl_layout` SET `framework`='a:1:{i:0;s:10:\"layout.css\";}' WHERE skipTinymce=1");

		// Make sure the "skipFramework" field exists (see #4624)
		if ($this->Database->fieldExists('skipFramework', 'tl_layout'))
		{
			$this->Database->query("UPDATE `tl_layout` SET `framework`='' WHERE skipFramework=1");
		}

		// Add the "ptable" field
		$this->Database->query("ALTER TABLE `tl_content` ADD ptable varchar(64) NOT NULL default ''");

		// Create a content element for each news article
		$objNews = $this->Database->execute("SELECT * FROM tl_news WHERE text!='' AND source='default'");

		while ($objNews->next())
		{
			$this->createContentElement($objNews, 'tl_news', 'text');
		}

		// Create a content element for each event
		$objEvents = $this->Database->execute("SELECT * FROM tl_calendar_events WHERE details!='' AND source='default'");

		while ($objEvents->next())
		{
			$this->createContentElement($objEvents, 'tl_calendar_events', 'details');
		}

		// Add an .htaccess file to the modules' html folders so they can be accessed via HTTP
		foreach (scan(TL_ROOT . '/system/modules') as $strFolder)
		{
			if (is_dir(TL_ROOT . '/system/modules/' . $strFolder) && is_dir(TL_ROOT . '/system/modules/' . $strFolder . '/html'))
			{
				if (!file_exists(TL_ROOT . '/system/modules/' . $strFolder . '/html/.htaccess'))
				{
					\File::putContent('system/modules/' . $strFolder . '/html/.htaccess', "<IfModule !mod_authz_core.c>\n  Order allow,deny\n  Allow from all\n</IfModule>\n<IfModule mod_authz_core.c>\n  Require all granted\n</IfModule>");
				}
			}
		}

		// Convert the gradient angle syntax (see #4569)
		if ($this->Database->fieldExists('gradientAngle', 'tl_style'))
		{
			$objStyle = $this->Database->execute("SELECT id, gradientAngle FROM tl_style WHERE gradientAngle!=''");

			while ($objStyle->next())
			{
				$angle = '';

				if (strpos($objStyle->gradientAngle, 'deg') !== false)
				{
					$angle = (abs(450 - intval($objStyle->gradientAngle)) % 360) . 'deg';
				}
				else
				{
					switch ($objStyle->gradientAngle)
					{
						case 'top':          $angle = 'to bottom';       break;
						case 'right':        $angle = 'to left';         break;
						case 'bottom':       $angle = 'to top';          break;
						case 'left':         $angle = 'to right';        break;
						case 'top left':     $angle = 'to bottom right'; break;
						case 'top right':    $angle = 'to bottom left';  break;
						case 'bottom left':  $angle = 'to top right';    break;
						case 'bottom right': $angle = 'to top left';     break;
					}
				}

				$this->Database->prepare("UPDATE tl_style SET gradientAngle=? WHERE id=?")
							   ->execute($angle, $objStyle->id);
			}
		}

		// Make unlimited recurrences end on 2038-01-01 00:00:00 (see #4862)
		$this->Database->query("UPDATE `tl_calendar_events` SET `repeatEnd`=2145913200 WHERE `recurring`=1 AND `recurrences`=0");
	}


	/**
	 * Version 3.1.0 update
	 */
	public function run31Update()
	{
		// Get all page layouts that use the CSS framework
		$objLayout = $this->Database->query("SELECT `id`, `framework` FROM `tl_layout` WHERE `framework`!=''");

		// Rename "responsive.css" to "grid.css"
		while ($objLayout->next())
		{
			$arrCss = deserialize($objLayout->framework);

			if (($key = array_search('responsive.css', $arrCss)) !== false)
			{
				$arrCss[$key] = 'grid.css';
			}

			$this->Database->prepare("UPDATE `tl_layout` SET `framework`=? WHERE `id`=?")
						   ->execute(serialize($arrCss), $objLayout->id);
		}

		// Add the jQuery fields if they do not yet exist (see #5689)
		if (!$this->Database->fieldExists('addJQuery', 'tl_layout'))
		{
			$this->Database->query("ALTER TABLE `tl_layout` ADD `addJQuery` char(1) NOT NULL default ''");
			$this->Database->query("ALTER TABLE `tl_layout` ADD `jSource` varchar(16) NOT NULL default ''");
			$this->Database->query("ALTER TABLE `tl_layout` ADD `jquery` text NULL");
		}

		// Get all page layouts that use the moo_mediaelement template
		$objLayout = $this->Database->query("SELECT `id`, `addJQuery`, `jquery`, `mootools` FROM `tl_layout` WHERE `addMooTools`=1 AND `mootools` LIKE '%\"moo_mediaelement\"%'");

		// Activate the "j_mediaelement" template instead
		while ($objLayout->next())
		{
			$arrSet = array();

			// jQuery already activated
			if ($objLayout->addjQuery)
			{
				$arrJQuery = deserialize($objLayout->jquery);

				// Add j_mediaelement
				if (!is_array($arrJQuery))
				{
					$arrSet['jquery'] = serialize(array('j_mediaelement'));
				}
				elseif (!in_array('j_mediaelement', $arrJQuery))
				{
					$arrJQuery[] = 'j_mediaelement';
					$arrSet['jquery'] = serialize($arrJQuery);
				}
			}
			else
			{
				$arrSet['addJQuery'] = 1;
				$arrSet['jSource'] = 'j_local';
				$arrSet['jquery'] = serialize(array('j_mediaelement'));
			}

			$arrMooTools = deserialize($objLayout->mootools);

			// Unset the moo_mediaelement template
			if (($key = array_search('moo_mediaelement', $arrMooTools)) !== false)
			{
				unset($arrMooTools[$key]);
			}

			// Update the MooTools templates
			if (empty($arrMooTools))
			{
				$arrSet['mootools'] = '';
			}
			else
			{
				$arrSet['mootools'] = serialize(array_values($arrMooTools));
			}

			$this->Database->prepare("UPDATE `tl_layout` %s WHERE `id`=?")
						   ->set($arrSet)
						   ->execute($objLayout->id);
		}

		// Get all page layouts
		$objLayout = $this->Database->query("SELECT `id`, `modules` FROM `tl_layout`");

		// Add the "enable" flag to all modules
		while ($objLayout->next())
		{
			$arrModules = deserialize($objLayout->modules);

			foreach (array_keys($arrModules) as $key)
			{
				$arrModules[$key]['enable'] = true;
			}

			$this->Database->prepare("UPDATE `tl_layout` SET `modules`=? WHERE `id`=?")
						   ->execute(serialize($arrModules), $objLayout->id);
		}

		// Adjust the accordion elements
		$this->Database->query("UPDATE `tl_content` SET `type`='accordionStart' WHERE `type`='accordion' AND `mooType`='mooStart'");
		$this->Database->query("UPDATE `tl_content` SET `type`='accordionStop' WHERE `type`='accordion' AND `mooType`='mooStop'");
		$this->Database->query("UPDATE `tl_content` SET `type`='accordionSingle' WHERE `type`='accordion' AND `mooType`='mooSingle'");

		// White-space is now in the "alignment" section (see #4519)
		$this->Database->query("UPDATE `tl_style` SET `alignment`=1 WHERE `whitespace`!=''");
		$this->Database->query("ALTER TABLE `tl_style` CHANGE `whitespace` `whitespace` varchar(8) NOT NULL default ''");
		$this->Database->query("UPDATE `tl_style` SET `whitespace`='nowrap' WHERE `whitespace`!=''");

		// Drop the tl_files.path index (see #5598)
		if ($this->Database->indexExists('path', 'tl_files'))
		{
			$this->Database->query("ALTER TABLE `tl_files` DROP INDEX `path`");
		}

		// Remove the "mooType" field (triggers the version 3.1 update)
		$this->Database->query("ALTER TABLE `tl_content` DROP `mooType`");
	}


	/**
	 * Version 3.2.0 update
	 */
	public function run32Update()
	{
		// Adjust the custom layout sections (see #2885)
		$this->Database->query("ALTER TABLE `tl_layout` CHANGE `sections` `sections` varchar(1022) NOT NULL default ''");
		$objLayout = $this->Database->query("SELECT id, sections FROM tl_layout WHERE sections!=''");

		while ($objLayout->next())
		{
			$strSections = '';
			$tmp = deserialize($objLayout->sections);

			if (!empty($tmp) && is_array($tmp))
			{
				$strSections = implode(', ', $tmp);
			}

			$this->Database->prepare("UPDATE tl_layout SET sections=? WHERE id=?")
						   ->execute($strSections, $objLayout->id);
		}

		// Check whether there are UUIDs
		if (!$this->Database->fieldExists('uuid', 'tl_files'))
		{
			// Adjust the DB structure
			$this->Database->query("ALTER TABLE `tl_files` ADD `uuid` binary(16) NULL");
			$this->Database->query("ALTER TABLE `tl_files` ADD UNIQUE KEY `uuid` (`uuid`)");

			// Backup the pid column and change the column type
			$this->Database->query("ALTER TABLE `tl_files` ADD `pid_backup` int(10) unsigned NOT NULL default '0'");
			$this->Database->query("UPDATE `tl_files` SET `pid_backup`=`pid`");
			$this->Database->query("ALTER TABLE `tl_files` CHANGE `pid` `pid` binary(16) NULL");
			$this->Database->query("UPDATE `tl_files` SET `pid`=NULL");
			$this->Database->query("UPDATE `tl_files` SET `pid`=NULL WHERE `pid_backup`=0");

			$objFiles = $this->Database->query("SELECT id FROM tl_files");

			// Generate the UUIDs
			while ($objFiles->next())
			{
				$this->Database->prepare("UPDATE tl_files SET uuid=? WHERE id=?")
							   ->execute($this->Database->getUuid(), $objFiles->id);
			}

			$objFiles = $this->Database->query("SELECT pid_backup FROM tl_files WHERE pid_backup>0 GROUP BY pid_backup");

			// Adjust the parent IDs
			while ($objFiles->next())
			{
				$objParent = $this->Database->prepare("SELECT uuid FROM tl_files WHERE id=?")
											->execute($objFiles->pid_backup);

				if ($objParent->numRows < 1)
				{
					throw new \Exception('Invalid parent ID ' . $objFiles->pid_backup);
				}

				$this->Database->prepare("UPDATE tl_files SET pid=? WHERE pid_backup=?")
							   ->execute($objParent->uuid, $objFiles->pid_backup);
			}

			// Drop the pid_backup column
			$this->Database->query("ALTER TABLE `tl_files` DROP `pid_backup`");
		}

		// Update the fields
		$this->updateFileTreeFields();
	}


	/**
	 * Version 3.3.0 update
	 */
	public function run33Update()
	{
		$objLayout = $this->Database->query("SELECT id, framework FROM tl_layout WHERE framework!=''");

		while ($objLayout->next())
		{
			$strFramework = '';
			$tmp = deserialize($objLayout->framework);

			if (!empty($tmp) && is_array($tmp))
			{
				if (($key = array_search('layout.css', $tmp)) !== false)
				{
					array_insert($tmp, $key + 1, 'responsive.css');
				}

				$strFramework = serialize(array_values(array_unique($tmp)));
			}

			$this->Database->prepare("UPDATE tl_layout SET framework=? WHERE id=?")
						   ->execute($strFramework, $objLayout->id);
		}

		// Add the "viewport" field (triggers the version 3.3 update)
		$this->Database->query("ALTER TABLE `tl_layout` ADD `viewport` varchar(64) NOT NULL default ''");
	}


	/**
	 * Version 3.5.0 update
	 */
	public function run35Update()
	{
		$this->Database->query("ALTER TABLE `tl_member` CHANGE `username` `username` varchar(64) COLLATE utf8_bin NULL");
		$this->Database->query("UPDATE `tl_member` SET username=NULL WHERE username=''");
		$this->Database->query("ALTER TABLE `tl_member` DROP INDEX `username`, ADD UNIQUE KEY `username` (`username`)");
	}


	/**
	 * Scan the upload folder and create the database entries
	 *
	 * @param string  $strPath The target folder
	 * @param integer $pid     The parent ID
	 */
	public function scanUploadFolder($strPath=null, $pid=null)
	{
		if ($strPath === null)
		{
			$strPath = \Config::get('uploadPath');
		}

		$arrMeta = array();
		$arrMapper = array();
		$arrFolders = array();
		$arrFiles = array();
		$arrScan = scan(TL_ROOT . '/' . $strPath);

		foreach ($arrScan as $strFile)
		{
			if (strncmp($strFile, '.', 1) === 0)
			{
				continue;
			}

			if (is_dir(TL_ROOT . '/' . $strPath . '/' . $strFile))
			{
				$arrFolders[] = $strPath . '/' . $strFile;
			}
			else
			{
				$arrFiles[] = $strPath . '/' . $strFile;
			}
		}

		// Folders
		foreach ($arrFolders as $strFolder)
		{
			$objFolder = new \Folder($strFolder);
			$strUuid = $this->Database->getUuid();

			$this->Database->prepare("INSERT INTO tl_files (pid, tstamp, uuid, name, type, path, hash) VALUES (?, ?, ?, ?, 'folder', ?, ?)")
						   ->execute($pid, time(), $strUuid, basename($strFolder), $strFolder, $objFolder->hash);

			$this->scanUploadFolder($strFolder, $strUuid);
		}

		// Files
		foreach ($arrFiles as $strFile)
		{
			$matches = array();

			// Handle meta.txt files
			if (preg_match('/^meta(_([a-z]{2}))?\.txt$/', basename($strFile), $matches))
			{
				$key = $matches[2] ?: 'en';
				$arrData = file(TL_ROOT . '/' . $strFile, FILE_IGNORE_NEW_LINES);

				foreach ($arrData as $line)
				{
					list($name, $info) = explode('=', $line, 2);
					list($title, $link, $caption) = explode('|', $info);
					$arrMeta[trim($name)][$key] = array('title'=>trim($title), 'link'=>trim($link), 'caption'=>trim($caption));
				}
			}

			$objFile = new \File($strFile, true);
			$strUuid = $this->Database->getUuid();

			$this->Database->prepare("INSERT INTO tl_files (pid, tstamp, uuid, name, type, path, extension, hash) VALUES (?, ?, ?, ?, 'file', ?, ?, ?)")
						   ->execute($pid, time(), $strUuid, basename($strFile), $strFile, $objFile->extension, $objFile->hash);

			$arrMapper[basename($strFile)] = $strUuid;
		}

		// Insert the meta data AFTER the file entries have been created
		if (!empty($arrMeta))
		{
			foreach ($arrMeta as $file=>$meta)
			{
				if (isset($arrMapper[$file]))
				{
					$this->Database->prepare("UPDATE tl_files SET meta=? WHERE uuid=?")
								   ->execute(serialize($meta), $arrMapper[$file]);
				}
			}
		}
	}


	/**
	 * Update all FileTree fields
	 */
	public function updateFileTreeFields()
	{
		$arrFiles = array();

		// Parse all modules (see #6058)
		foreach (scan(TL_ROOT . '/system/modules') as $strModule)
		{
			$strDir = 'system/modules/' . $strModule . '/dca';

			if (!is_dir(TL_ROOT . '/' . $strDir))
			{
				continue;
			}

			foreach (scan(TL_ROOT . '/' . $strDir) as $strFile)
			{
				// Ignore non PHP files and files which have been included before
				if (substr($strFile, -4) != '.php' || in_array($strFile, $arrFiles))
				{
					continue;
				}

				$arrFiles[] = substr($strFile, 0, -4);
			}
		}

		$arrFields = array();

		// Find all fileTree fields
		foreach ($arrFiles as $strTable)
		{
			try
			{
				$this->loadDataContainer($strTable);
			}
			catch (\Exception $e)
			{
				continue;
			}

			$arrConfig = &$GLOBALS['TL_DCA'][$strTable]['config'];

			// Skip non-database DCAs
			if ($arrConfig['dataContainer'] == 'File')
			{
				continue;
			}
			if ($arrConfig['dataContainer'] == 'Folder' && !$arrConfig['databaseAssisted'])
			{
				continue;
			}

			// Make sure there are fields (see #6437)
			if (is_array($GLOBALS['TL_DCA'][$strTable]['fields']))
			{
				foreach ($GLOBALS['TL_DCA'][$strTable]['fields'] as $strField=>$arrField)
				{
					// FIXME: support other field types
					if ($arrField['inputType'] == 'fileTree')
					{
						if ($this->Database->fieldExists($strField, $strTable, true))
						{
							$key = $arrField['eval']['multiple'] ? 'multiple' : 'single';
							$arrFields[$key][] = $strTable . '.' . $strField;
						}

						// Convert the order fields as well
						if (isset($arrField['eval']['orderField']) && isset($GLOBALS['TL_DCA'][$strTable]['fields'][$arrField['eval']['orderField']]))
						{
							if ($this->Database->fieldExists($arrField['eval']['orderField'], $strTable, true))
							{
								$arrFields['order'][] = $strTable . '.' . $arrField['eval']['orderField'];
							}
						}
					}
				}
			}
		}

		// Update the existing singleSRC entries
		if (isset($arrFields['single']))
		{
			foreach ($arrFields['single'] as $val)
			{
				list($table, $field) = explode('.', $val);
				static::convertSingleField($table, $field);
			}
		}

		// Update the existing multiSRC entries
		if (isset($arrFields['multiple']))
		{
			foreach ($arrFields['multiple'] as $val)
			{
				list($table, $field) = explode('.', $val);
				static::convertMultiField($table, $field);
			}
		}

		// Update the existing orderField entries
		if (isset($arrFields['order']))
		{
			foreach ($arrFields['order'] as $val)
			{
				list($table, $field) = explode('.', $val);
				static::convertOrderField($table, $field);
			}
		}
	}


	/**
	 * Convert a single source field to UUIDs
	 *
	 * @param string $table The table name
	 * @param string $field The field name
	 */
	public static function convertSingleField($table, $field)
	{
		$objDatabase = \Database::getInstance();

		// Get the non-empty rows
		$objRow = $objDatabase->query("SELECT id, $field FROM $table WHERE $field!=''");

		// Check the column type
		$objDesc = $objDatabase->query("DESC $table $field");

		// Change the column type
		if ($objDesc->Type != 'binary(16)')
		{
			$objDatabase->query("ALTER TABLE `$table` CHANGE `$field` `$field` binary(16) NULL");
			$objDatabase->query("UPDATE `$table` SET `$field`=NULL WHERE `$field`='' OR `$field`=0");
		}

		while ($objRow->next())
		{
			$objHelper = static::generateHelperObject($objRow->$field);

			// UUID already
			if ($objHelper->isUuid)
			{
				continue;
			}

			// Numeric ID to UUID
			if ($objHelper->isNumeric)
			{
				$objFile = \FilesModel::findByPk($objHelper->value);

				$objDatabase->prepare("UPDATE $table SET $field=? WHERE id=?")
							->execute($objFile->uuid, $objRow->id);
			}

			// Path to UUID
			else
			{
				$objFile = \FilesModel::findByPath($objHelper->value);

				$objDatabase->prepare("UPDATE $table SET $field=? WHERE id=?")
							->execute($objFile->uuid, $objRow->id);
			}
		}
	}


	/**
	 * Convert a multi source field to UUIDs
	 *
	 * @param string $table The table name
	 * @param string $field The field name
	 */
	public static function convertMultiField($table, $field)
	{
		$objDatabase = \Database::getInstance();

		// Get the non-empty rows
		$objRow = $objDatabase->query("SELECT id, $field FROM $table WHERE $field!=''");

		// Check the column type
		$objDesc = $objDatabase->query("DESC $table $field");

		// Change the column type
		if ($objDesc->Type != 'blob')
		{
			$objDatabase->query("ALTER TABLE `$table` CHANGE `$field` `$field` blob NULL");
			$objDatabase->query("UPDATE `$table` SET `$field`=NULL WHERE `$field`=''");
		}

		while ($objRow->next())
		{
			$arrValues = deserialize($objRow->$field, true);

			if (empty($arrValues))
			{
				continue;
			}

			$objHelper = static::generateHelperObject($arrValues);

			// UUID already
			if ($objHelper->isUuid)
			{
				continue;
			}

			foreach ($arrValues as $k=>$v)
			{
				// Numeric ID to UUID
				if ($objHelper->isNumeric)
				{
					$objFile = \FilesModel::findByPk($objHelper->value[$k]);
					$arrValues[$k] = $objFile->uuid;
				}

				// Path to UUID
				else
				{
					$objFile = \FilesModel::findByPath($objHelper->value[$k]);
					$arrValues[$k] = $objFile->uuid;
				}
			}

			$objDatabase->prepare("UPDATE $table SET $field=? WHERE id=?")
						->execute(serialize($arrValues), $objRow->id);
		}
	}


	/**
	 * Convert an order source field to UUIDs
	 *
	 * @param string $table The table name
	 * @param string $field The field name
	 */
	public static function convertOrderField($table, $field)
	{
		$objDatabase = \Database::getInstance();

		// Get the non-empty rows
		$objRow = $objDatabase->query("SELECT id, $field FROM $table WHERE $field LIKE '%,%'");

		// Convert the comma separated lists into serialized arrays
		while ($objRow->next())
		{
			$objDatabase->prepare("UPDATE $table SET $field=? WHERE id=?")
						->execute(serialize(explode(',', $objRow->$field)), $objRow->id);
		}

		static::convertMultiField($table, $field);
	}


	/**
	 * Generate a helper object based on a field value
	 *
	 * @param mixed $value The field value
	 *
	 * @return \stdClass The helper object
	 */
	protected static function generateHelperObject($value)
	{
		$return = new \stdClass();

		if (!is_array($value))
		{
			$return->value = rtrim($value, "\x00");
			$return->isUuid = (strlen($value) == 16 && !is_numeric($return->value) && strncmp($return->value, \Config::get('uploadPath') . '/', strlen(\Config::get('uploadPath')) + 1) !== 0);
			$return->isNumeric = (is_numeric($return->value) && $return->value > 0);
		}
		else
		{
			$return->value = array_map(function($var) { return rtrim($var, "\x00"); }, $value);
			$return->isUuid = (strlen($value[0]) == 16 && !is_numeric($return->value[0]) && strncmp($return->value[0], \Config::get('uploadPath') . '/', strlen(\Config::get('uploadPath')) + 1) !== 0);
			$return->isNumeric = (is_numeric($return->value[0]) && $return->value[0] > 0);
		}

		return $return;
	}


	/**
	 * Create a content element
	 *
	 * @param \Database\Result|object $objElement A database result object
	 * @param string                  $strPtable  The name of the parent table
	 * @param string                  $strField   The name of the text column
	 */
	protected function createContentElement(\Database\Result $objElement, $strPtable, $strField)
	{
		$set = array
		(
			'pid'         => $objElement->id,
			'ptable'      => $strPtable,
			'sorting'     => 128,
			'tstamp'      => $objElement->tstamp,
			'type'        => 'text',
			'text'        => $objElement->$strField,
			'addImage'    => $objElement->addImage,
			'singleSRC'   => $objElement->singleSRC,
			'alt'         => $objElement->alt,
			'size'        => $objElement->size,
			'imagemargin' => $objElement->imagemargin,
			'imageUrl'    => $objElement->imageUrl,
			'fullsize'    => $objElement->fullsize,
			'caption'     => $objElement->caption,
			'floating'    => $objElement->floating
		);

		$this->Database->prepare("INSERT INTO tl_content %s")->set($set)->execute();
	}
}
