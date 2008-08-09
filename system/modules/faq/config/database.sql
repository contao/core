-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************


-- --------------------------------------------------------

-- 
-- Table `tl_faq`
-- 

CREATE TABLE `tl_faq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `alias` varchar(128) NOT NULL default '',
  `question` varchar(255) NOT NULL default '',
  `answer` text NULL,
  `addImage` char(1) NOT NULL default '',
  `singleSRC` varchar(255) NOT NULL default '',
  `size` varchar(255) NOT NULL default '',
  `alt` varchar(255) NOT NULL default '',
  `caption` varchar(255) NOT NULL default '',
  `floating` varchar(32) NOT NULL default '',
  `imagemargin` varchar(255) NOT NULL default '',
  `fullsize` char(1) NOT NULL default '',
  `author` varchar(64) NOT NULL default '',
  `published` char(1) NOT NULL default '',
  `addEnclosure` char(1) NOT NULL default '',
  `enclosure` blob NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_faq_category`
-- 

CREATE TABLE `tl_faq_category` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `headline` varchar(255) NOT NULL default '',
  `jumpTo` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `faq_categories` varchar(255) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;