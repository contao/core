-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- 
-- Table `tl_calendar`
-- 

CREATE TABLE `tl_calendar` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `language` varchar(32) NOT NULL default '',
  `jumpTo` int(10) unsigned NOT NULL default '0',
  `protected` char(1) NOT NULL default '',
  `groups` blob NULL,
  `makeFeed` char(1) NOT NULL default '',
  `alias` varchar(128) NOT NULL default '',
  `feedBase` varchar(255) NOT NULL default '',
  `description` text NULL,
  `format` varchar(32) NOT NULL default '',
  `maxItems` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_calendar_events`
-- 

CREATE TABLE `tl_calendar_events` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `alias` varchar(128) NOT NULL default '',
  `author` int(10) unsigned NOT NULL default '0',
  `startDate` varchar(10) NOT NULL default '',
  `endDate` varchar(10) NOT NULL default '',
  `addTime` char(1) NOT NULL default '',
  `startTime` int(10) unsigned NOT NULL default '0',
  `endTime` int(10) unsigned NOT NULL default '0',
  `recurring` char(1) NOT NULL default '',
  `repeatEach` varchar(64) NOT NULL default '',
  `repeatEnd` int(10) unsigned NOT NULL default '0',
  `recurrences` smallint(5) unsigned NOT NULL default '0',
  `teaser` text NULL,
  `details` mediumtext NULL,
  `addImage` char(1) NOT NULL default '',
  `singleSRC` varchar(255) NOT NULL default '',
  `size` varchar(255) NOT NULL default '',
  `alt` varchar(255) NOT NULL default '',
  `caption` varchar(255) NOT NULL default '',
  `floating` varchar(32) NOT NULL default '',
  `imagemargin` varchar(255) NOT NULL default '',
  `fullsize` char(1) NOT NULL default '',
  `addEnclosure` char(1) NOT NULL default '',
  `enclosure` blob NULL,
  `source` varchar(32) NOT NULL default '',
  `jumpTo` int(10) unsigned NOT NULL default '0',
  `url` varchar(255) NOT NULL default '',
  `target` char(1) NOT NULL default '',
  `cssClass` varchar(255) NOT NULL default '',
  `published` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `cal_calendar` blob NULL,
  `cal_template` varchar(32) NOT NULL default '',
  `cal_startDay` smallint(5) unsigned NOT NULL default '0',
  `cal_previous` varchar(255) NOT NULL default '',
  `cal_next` varchar(255) NOT NULL default '',
  `cal_format` varchar(32) NOT NULL default '',
  `cal_limit` varchar(32) NOT NULL default '',
  `cal_noSpan` char(1) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_user`
-- 

CREATE TABLE `tl_user` (
  `calendars` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_user_group`
-- 

CREATE TABLE `tl_user_group` (
  `calendars` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
