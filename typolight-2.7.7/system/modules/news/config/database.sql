-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- 
-- Table `tl_news`
-- 

CREATE TABLE `tl_news` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `headline` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  `author` int(10) unsigned NOT NULL default '0',
  `date` int(10) unsigned NOT NULL default '0',
  `time` int(10) unsigned NOT NULL default '0',
  `subheadline` varchar(255) NOT NULL default '',
  `teaser` text NULL,
  `text` mediumtext NULL,
  `addImage` char(1) NOT NULL default '',
  `singleSRC` varchar(255) NOT NULL default '',
  `alt` varchar(255) NOT NULL default '',
  `size` varchar(64) NOT NULL default '',
  `imagemargin` varchar(128) NOT NULL default '',
  `caption` varchar(255) NOT NULL default '',
  `floating` varchar(32) NOT NULL default '',
  `fullsize` char(1) NOT NULL default '',
  `addEnclosure` char(1) NOT NULL default '',
  `enclosure` blob NULL,
  `source` varchar(32) NOT NULL default '',
  `jumpTo` int(10) unsigned NOT NULL default '0',
  `url` varchar(255) NOT NULL default '',
  `target` char(1) NOT NULL default '',
  `cssClass` varchar(255) NOT NULL default '',
  `noComments` char(1) NOT NULL default '',
  `featured` char(1) NOT NULL default '',
  `published` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_news_archive`
-- 

CREATE TABLE `tl_news_archive` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `jumpTo` int(10) unsigned NOT NULL default '0',
  `allowComments` char(1) NOT NULL default '',
  `notify` varchar(32) NOT NULL default '',
  `template` varchar(32) NOT NULL default '',
  `sortOrder` varchar(32) NOT NULL default '',
  `perPage` smallint(5) unsigned NOT NULL default '0',
  `moderate` char(1) NOT NULL default '',
  `bbcode` char(1) NOT NULL default '',
  `requireLogin` char(1) NOT NULL default '',
  `disableCaptcha` char(1) NOT NULL default '',
  `protected` char(1) NOT NULL default '',
  `groups` blob NULL,
  `makeFeed` char(1) NOT NULL default '',
  `format` varchar(32) NOT NULL default '',
  `language` varchar(32) NOT NULL default '',
  `source` varchar(32) NOT NULL default '',
  `maxItems` smallint(5) unsigned NOT NULL default '0',
  `feedBase` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  `description` text NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_news_comments`
-- 

CREATE TABLE `tl_news_comments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `email` varchar(128) NOT NULL default '',
  `website` varchar(128) NOT NULL default '',
  `comment` text NULL,
  `published` char(1) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  `ip` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `news_archives` blob NULL,
  `news_featured` char(1) NOT NULL default '',
  `news_numberOfItems` smallint(5) unsigned NOT NULL default '0',
  `news_jumpToCurrent` char(1) NOT NULL default '',
  `news_metaFields` varchar(255) NOT NULL default '',
  `news_template` varchar(32) NOT NULL default '',
  `news_format` varchar(32) NOT NULL default '',
  `news_showQuantity` char(1) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_user`
-- 

CREATE TABLE `tl_user` (
  `news` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_user_group`
-- 

CREATE TABLE `tl_user_group` (
  `news` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
