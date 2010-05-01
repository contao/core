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
-- Table `tl_member`
-- 

CREATE TABLE `tl_member` (
  `allowEmail` varchar(32) NOT NULL default '',
  `publicFields` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `ml_groups` blob NULL,
  `ml_fields` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
