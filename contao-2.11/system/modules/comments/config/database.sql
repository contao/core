-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

-- 
-- Table `tl_comments`
-- 

CREATE TABLE `tl_comments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `source` varchar(32) NOT NULL default '',
  `parent` int(10) unsigned NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `email` varchar(128) NOT NULL default '',
  `website` varchar(128) NOT NULL default '',
  `comment` text NULL,
  `addReply` char(1) NOT NULL default '',
  `reply` text NULL,
  `author` int(10) unsigned NOT NULL default '0',
  `published` char(1) NOT NULL default '',
  `date` int(10) unsigned NOT NULL default '0',
  `ip` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `source` (`source`),
  KEY `parent` (`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_content`
-- 

CREATE TABLE `tl_content` (
  `com_order` varchar(32) NOT NULL default '',
  `com_perPage` smallint(5) unsigned NOT NULL default '0',
  `com_moderate` char(1) NOT NULL default '',
  `com_bbcode` char(1) NOT NULL default '',
  `com_requireLogin` char(1) NOT NULL default '',
  `com_disableCaptcha` char(1) NOT NULL default '',
  `com_template` varchar(32) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `com_order` varchar(32) NOT NULL default '',
  `com_moderate` char(1) NOT NULL default '',
  `com_bbcode` char(1) NOT NULL default '',
  `com_requireLogin` char(1) NOT NULL default '',
  `com_disableCaptcha` char(1) NOT NULL default '',
  `com_template` varchar(32) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
