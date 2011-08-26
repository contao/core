-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

-- 
-- Table `tl_newsletter`
-- 

CREATE TABLE `tl_newsletter` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  `content` text NULL,
  `text` text NULL,
  `addFile` char(1) NOT NULL default '',
  `files` blob NULL,
  `template` varchar(32) NOT NULL default '',
  `sendText` char(1) NOT NULL default '',
  `externalImages` char(1) NOT NULL default '',
  `sender` varchar(128) NOT NULL default '',
  `senderName` varchar(128) NOT NULL default '',
  `sent` char(1) NOT NULL default '',
  `date` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_newsletter_channel`
-- 

CREATE TABLE `tl_newsletter_channel` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `jumpTo` int(10) unsigned NOT NULL default '0',
  `useSMTP` char(1) NOT NULL default '',
  `smtpHost` varchar(64) NOT NULL default '',
  `smtpUser` varchar(128) NOT NULL default '',
  `smtpPass` varchar(32) NOT NULL default '',
  `smtpPort` smallint(5) unsigned NOT NULL default '0',
  `smtpEnc` varchar(3) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_newsletter_recipients`
-- 

CREATE TABLE `tl_newsletter_recipients` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `email` varchar(128) NOT NULL default '',
  `active` char(1) NOT NULL default '',
  `addedOn` varchar(10) NOT NULL default '',
  `ip` varchar(64) NOT NULL default '',
  `token` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_member`
-- 

CREATE TABLE `tl_member` (
  `newsletter` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `newsletters` blob NULL,
  `nl_channels` blob NULL,
  `nl_hideChannels` char(1) NOT NULL default '',
  `nl_subscribe` text NULL,
  `nl_unsubscribe` text NULL,
  `nl_template` varchar(32) NOT NULL default '',
  `nl_includeCss` char(1) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_user`
-- 

CREATE TABLE `tl_user` (
  `newsletters` blob NULL,
  `newsletterp` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_user_group`
-- 

CREATE TABLE `tl_user_group` (
  `newsletters` blob NULL,
  `newsletterp` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
