-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

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
  `disableCaptcha` char(1) NOT NULL default '',
  `reg_groups` blob NULL,
  `reg_allowLogin` char(1) NOT NULL default '',
  `reg_skipName` char(1) NOT NULL default '',
  `reg_assignDir` char(1) NOT NULL default '',
  `reg_close` varchar(32) NOT NULL default '',
  `reg_homeDir` varchar(255) NOT NULL default '',
  `reg_activate` char(1) NOT NULL default '',
  `reg_jumpTo` int(10) unsigned NOT NULL default '0',
  `reg_text` text NULL,
  `reg_password` text NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
