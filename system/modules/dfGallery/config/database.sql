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
-- Table `tl_content`
-- 

CREATE TABLE `tl_content` (
  `dfTitle` varchar(64) NOT NULL default '',
  `dfSize` varchar(255) NOT NULL default '',
  `dfInterval` smallint(5) unsigned NOT NULL default '7',
  `dfTemplate` varchar(32) NOT NULL default '',
  `dfPause` char(1) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
