-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- 
-- Table `tl_member`
-- 

CREATE TABLE `tl_member` (
  `activation` varchar(32) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `reg_groups` blob NULL,
  `reg_assignDir` char(1) NOT NULL default '',
  `reg_homeDir` varchar(255) NOT NULL default '',
  `reg_allowLogin` char(1) NOT NULL default '',
  `reg_activate` char(1) NOT NULL default '',
  `reg_jumpTo` smallint(5) unsigned NOT NULL default '0',
  `reg_skipName` char(1) NOT NULL default '',
  `reg_text` text NULL,
  `reg_password` text NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
