<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao\Database;


/**
 * Adjust the database if the system is updated
 * 
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
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
					   ->execute(time(), $GLOBALS['TL_CONFIG']['websiteTitle']);

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
			$this->Database->query("UPDATE tl_module SET news_featured='featured' WHERE news_featured=1");
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
			  `pid` int(10) unsigned NOT NULL default '0',
			  `tstamp` int(10) unsigned NOT NULL default '0',
			  `type` varchar(16) NOT NULL default '',
			  `path` varchar(255) NOT NULL default '',
			  `extension` varchar(16) NOT NULL default '',
			  `hash` varchar(32) NOT NULL default '',
			  `found` char(1) NOT NULL default '1',
			  `name` varchar(64) NOT NULL default '',
			  `meta` blob NULL,
			  PRIMARY KEY  (`id`),
			  KEY `pid` (`pid`),
			  UNIQUE KEY `path` (`path`),
			  KEY `extension` (`extension`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;"
		);

		// Create the DCA extracts (will also create the DCA cache)
		\DcaExtractor::createAllExtracts();

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
					$objFile = new \File('system/modules/' . $strFolder . '/html/.htaccess');
					$objFile->write("order deny,allow\nallow from all");
					$objFile->close();
				}
			}
		}

		// Convert the gradient angle syntax (see #4569)
		if ($this->Database->fieldExists('gradientAngle', 'tl_style'))
		{
			$objStyle = $this->Database->execute("SELECT id, gradientAngle FROM tl_style WHERE gradientAngle!=''");

			while ($objStyle->next())
			{
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
	 * Scan the upload folder and create the database entries
	 * 
	 * @param string  $strPath The target folder
	 * @param integer $pid     The parent ID
	 */
	public function scanUploadFolder($strPath=null, $pid=0)
	{
		if ($strPath === null)
		{
			$strPath = $GLOBALS['TL_CONFIG']['uploadPath'];
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

			$id = $this->Database->prepare("INSERT INTO tl_files (pid, tstamp, name, type, path, hash) VALUES (?, ?, ?, 'folder', ?, ?)")
								 ->execute($pid, time(), basename($strFolder), $strFolder, $objFolder->hash)
								 ->insertId;

			$this->scanUploadFolder($strFolder, $id);
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

			$objFile = new \File($strFile);

			$id = $this->Database->prepare("INSERT INTO tl_files (pid, tstamp, name, type, path, extension, hash) VALUES (?, ?, ?, 'file', ?, ?, ?)")
								 ->execute($pid, time(), basename($strFile), $strFile, $objFile->extension, $objFile->hash)
								 ->insertId;

			$arrMapper[basename($strFile)] = $id;
		}

		// Insert the meta data AFTER the file entries have been created
		if (!empty($arrMeta))
		{
			foreach ($arrMeta as $file=>$meta)
			{
				if (isset($arrMapper[$file]))
				{
					$this->Database->prepare("UPDATE tl_files SET meta=? WHERE id=?")
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
		$arrFields = array();

		// Find all fileTree fields
		foreach (scan(TL_ROOT . '/system/cache/dca') as $strFile)
		{
			if ($strFile != '.htaccess')
			{
				$strTable = str_replace('.php', '', $strFile);
				$this->loadDataContainer($strTable);
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

				foreach ($GLOBALS['TL_DCA'][$strTable]['fields'] as $strField=>$arrField)
				{
					// FIXME: support other field types
					if ($arrField['inputType'] == 'fileTree')
					{
						if ($this->Database->fieldExists($strField, $strTable))
						{
							$key = $arrField['eval']['multiple'] ? 'multiple' : 'single';
							$arrFields[$key][] = $strTable . '.' . $strField;
						}
					}
				}
			}
		}

		// Update the existing singleSRC entries
		foreach ($arrFields['single'] as $val)
		{
			list($table, $field) = explode('.', $val);
			$objRow = $this->Database->query("SELECT id, $field FROM $table WHERE $field!=''");

			while ($objRow->next())
			{
				if (!is_numeric($objRow->$field))
				{
					$objFile = \FilesModel::findByPath($objRow->$field);

					$this->Database->prepare("UPDATE $table SET $field=? WHERE id=?")
								   ->execute($objFile->id, $objRow->id);
				}
			}
		}

		// Update the existing multiSRC entries
		foreach ($arrFields['multiple'] as $val)
		{
			list($table, $field) = explode('.', $val);
			$objRow = $this->Database->query("SELECT id, $field FROM $table WHERE $field!=''");

			while ($objRow->next())
			{
				$arrPaths = deserialize($objRow->$field, true);

				if (empty($arrPaths))
				{
					continue;
				}

				foreach ($arrPaths as $k=>$v)
				{
					if (!is_numeric($v))
					{
						$objFile = \FilesModel::findByPath($v);
						$arrPaths[$k] = $objFile->id;
					}
				}

				$this->Database->prepare("UPDATE $table SET $field=? WHERE id=?")
							   ->execute(serialize($arrPaths), $objRow->id);
			}
		}
	}


	/**
	 * Create a content element
	 * 
	 * @param \Database\Result $objElement A database result object
	 * @param string           $strPtable  The name of the parent table
	 * @param string           $strField   The name of the text column
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
