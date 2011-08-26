-- Contao Repository :: Database setup file
--
-- Copyright (C) 2008-2010 by Peter Koch, IBK Software AG.
-- For license see accompaning file LICENSE.txt
--
-- NOTE: this file was edited with tabs set to 4.
-- 
-- **********************************************************
-- *      ! ! !   I M P O R T A N T  N O T E   ! ! !        *
-- *                                                        *
-- * Do not import this file manually! Use the Contao       *
-- * install tool to create and maintain database tables:   *
-- * - Point your browser to                                *
-- *   http://www.yourdomain.com/contao/install.php         *
-- * - Enter the installation password and click "Login"    *
-- * - Scroll down and click button "Update Database"       *
-- **********************************************************
  
-- --------------------------------------------------------
-- 
-- Table `tl_repository_installs`
-- 
CREATE TABLE `tl_repository_installs` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `extension` varchar(32) NOT NULL default '',
  `version` int(9) NOT NULL default '0',
  `build` int(9) NOT NULL default '0',
  `alpha` char(1) NOT NULL default '',
  `beta` char(1) NOT NULL default '',
  `rc` char(1) NOT NULL default '',
  `stable` char(1) NOT NULL default '1',
  `lickey` varchar(255) NOT NULL default '',
  `delprot` char(1) NOT NULL default '',
  `updprot` char(1) NOT NULL default '',
  `error` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- 
-- Table `tl_repository_instfiles`
-- 
CREATE TABLE `tl_repository_instfiles` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `filename` varchar(255) NOT NULL default '',
  `filetype` char(1) NOT NULL default 'F',
  `flag` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

