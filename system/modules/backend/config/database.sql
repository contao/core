-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

-- 
-- Table `tl_article`
-- 

CREATE TABLE `tl_article` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  `author` int(10) unsigned NOT NULL default '0',
  `inColumn` varchar(32) NOT NULL default '',
  `keywords` text NULL,
  `showTeaser` char(1) NOT NULL default '',
  `teaserCssID` varchar(255) NOT NULL default '',
  `teaser` text NULL,
  `printable` varchar(255) NOT NULL default '',
  `cssID` varchar(255) NOT NULL default '',
  `space` varchar(64) NOT NULL default '',
  `published` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_content`
-- 

CREATE TABLE `tl_content` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `invisible` char(1) NOT NULL default '',
  `type` varchar(32) NOT NULL default '',
  `headline` varchar(255) NOT NULL default '',
  `text` mediumtext NULL,
  `addImage` char(1) NOT NULL default '',
  `singleSRC` varchar(255) NOT NULL default '',
  `alt` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `size` varchar(64) NOT NULL default '',
  `imagemargin` varchar(128) NOT NULL default '',
  `imageUrl` varchar(255) NOT NULL default '',
  `fullsize` char(1) NOT NULL default '',
  `caption` varchar(255) NOT NULL default '',
  `floating` varchar(32) NOT NULL default '',
  `html` mediumtext NULL,
  `listtype` varchar(32) NOT NULL default '',
  `listitems` blob NULL,
  `tableitems` mediumblob NULL,
  `summary` varchar(255) NOT NULL default '',
  `thead` char(1) NOT NULL default '',
  `tfoot` char(1) NOT NULL default '',
  `tleft` char(1) NOT NULL default '',
  `sortable` char(1) NOT NULL default '',
  `sortIndex` smallint(5) unsigned NOT NULL default '0',
  `sortOrder` varchar(32) NOT NULL default '',
  `mooType` varchar(32) NOT NULL default '',
  `mooHeadline` varchar(255) NOT NULL default '',
  `mooStyle` varchar(255) NOT NULL default '',
  `mooClasses` varchar(255) NOT NULL default '',
  `shClass` varchar(255) NOT NULL default '',
  `highlight` varchar(32) NOT NULL default '',
  `code` text NULL,
  `url` varchar(255) NOT NULL default '',
  `target` char(1) NOT NULL default '',
  `linkTitle` varchar(255) NOT NULL default '',
  `embed` varchar(255) NOT NULL default '',
  `rel` varchar(64) NOT NULL default '',
  `useImage` char(1) NOT NULL default '',
  `multiSRC` blob NULL,
  `useHomeDir` char(1) NOT NULL default '',
  `perRow` smallint(5) unsigned NOT NULL default '0',
  `perPage` smallint(5) unsigned NOT NULL default '0',
  `numberOfItems` smallint(5) unsigned NOT NULL default '0',
  `sortBy` varchar(32) NOT NULL default '',
  `galleryTpl` varchar(64) NOT NULL default '',
  `cteAlias` int(10) unsigned NOT NULL default '0',
  `articleAlias` int(10) unsigned NOT NULL default '0',
  `article` int(10) unsigned NOT NULL default '0',
  `form` int(10) unsigned NOT NULL default '0',
  `module` int(10) unsigned NOT NULL default '0',
  `protected` char(1) NOT NULL default '',
  `groups` blob NULL,
  `guests` char(1) NOT NULL default '',
  `cssID` varchar(255) NOT NULL default '',
  `space` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_form`
-- 

CREATE TABLE `tl_form` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  `jumpTo` int(10) unsigned NOT NULL default '0',
  `sendViaEmail` char(1) NOT NULL default '',
  `recipient` text NULL,
  `subject` varchar(255) NOT NULL default '',
  `format` varchar(32) NOT NULL default '',
  `skipEmpty` char(1) NOT NULL default '',
  `storeValues` char(1) NOT NULL default '',
  `targetTable` varchar(64) NOT NULL default '',
  `method` varchar(12) NOT NULL default '',
  `attributes` varchar(255) NOT NULL default '',
  `formID` varchar(64) NOT NULL default '',
  `tableless` char(1) NOT NULL default '',
  `allowTags` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_form_field`
-- 

CREATE TABLE `tl_form_field` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `invisible` char(1) NOT NULL default '',
  `type` varchar(32) NOT NULL default '',
  `name` varchar(64) NOT NULL default '',
  `label` varchar(255) NOT NULL default '',
  `text` text NULL,
  `html` text NULL,
  `options` blob NULL,
  `mandatory` char(1) NOT NULL default '',
  `rgxp` varchar(32) NOT NULL default '',
  `maxlength` int(10) unsigned NOT NULL default '0',
  `size` varchar(255) NOT NULL default '',
  `fSize` smallint(5) unsigned NOT NULL default '0',
  `multiple` char(1) NOT NULL default '',
  `mSize` smallint(5) unsigned NOT NULL default '0',
  `extensions` varchar(255) NOT NULL default '',
  `storeFile` char(1) NOT NULL default '',
  `uploadFolder` varchar(255) NOT NULL default '',
  `useHomeDir` char(1) NOT NULL default '',
  `doNotOverwrite` char(1) NOT NULL default '',
  `fsType` varchar(32) NOT NULL default '',
  `value` varchar(255) NOT NULL default '',
  `placeholder` varchar(255) NOT NULL default '',
  `class` varchar(255) NOT NULL default '',
  `accesskey` char(1) NOT NULL default '',
  `tabindex` smallint(5) unsigned NOT NULL default '0',
  `addSubmit` char(1) NOT NULL default '',
  `slabel` varchar(255) NOT NULL default '',
  `imageSubmit` char(1) NOT NULL default '',
  `singleSRC` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_layout`
-- 

CREATE TABLE `tl_layout` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `fallback` char(1) NOT NULL default '',
  `header` char(1) NOT NULL default '',
  `headerHeight` varchar(255) NOT NULL default '',
  `footer` char(1) NOT NULL default '',
  `footerHeight` varchar(255) NOT NULL default '',
  `cols` varchar(32) NOT NULL default '',
  `widthLeft` varchar(255) NOT NULL default '',
  `widthRight` varchar(255) NOT NULL default '',
  `sections` blob NULL,
  `sPosition` varchar(32) NOT NULL default '',
  `stylesheet` blob NULL,
  `skipTinymce` char(1) NOT NULL default '',
  `webfonts` varchar(255) NOT NULL default '',
  `newsfeeds` blob NULL,
  `calendarfeeds` blob NULL,
  `modules` blob NULL,
  `template` varchar(64) NOT NULL default '',
  `skipFramework` char(1) NOT NULL default '',
  `doctype` varchar(32) NOT NULL default '',
  `mooSource` varchar(16) NOT NULL default '',
  `cssClass` varchar(255) NOT NULL default '',
  `onload` varchar(255) NOT NULL default '',
  `head` text NULL,
  `mootools` text NULL,
  `script` text NULL,
  `static` char(1) NOT NULL default '',
  `width` varchar(255) NOT NULL default '',
  `align` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_lock`
-- 

CREATE TABLE `tl_lock` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `tstamp` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_log`
-- 

CREATE TABLE `tl_log` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `source` varchar(32) NOT NULL default '',
  `action` varchar(32) NOT NULL default '',
  `username` varchar(64) NOT NULL default '',
  `text` text NULL,
  `func` varchar(255) NOT NULL default '',
  `ip` varchar(64) NOT NULL default '',
  `browser` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_member`
-- 

CREATE TABLE `tl_member` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `firstname` varchar(255) NOT NULL default '',
  `lastname` varchar(255) NOT NULL default '',
  `dateOfBirth` varchar(11) NOT NULL default '',
  `gender` varchar(32) NOT NULL default '',
  `company` varchar(255) NOT NULL default '',
  `street` varchar(255) NOT NULL default '',
  `postal` varchar(32) NOT NULL default '',
  `city` varchar(255) NOT NULL default '',
  `state` varchar(64) NOT NULL default '',
  `country` varchar(2) NOT NULL default '',
  `phone` varchar(64) NOT NULL default '',
  `mobile` varchar(64) NOT NULL default '',
  `fax` varchar(64) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `website` varchar(255) NOT NULL default '',
  `language` varchar(2) NOT NULL default '',
  `groups` blob NULL,
  `login` char(1) NOT NULL default '',
  `username` varchar(64) NOT NULL default '',
  `password` varchar(64) NOT NULL default '',
  `assignDir` char(1) NOT NULL default '',
  `homeDir` varchar(255) NOT NULL default '',
  `disable` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  `loginCount` smallint(5) unsigned NOT NULL default '3',
  `locked` int(10) unsigned NOT NULL default '0',
  `session` blob NULL,
  `dateAdded` int(10) unsigned NOT NULL default '0',
  `currentLogin` int(10) unsigned NOT NULL default '0',
  `lastLogin` int(10) unsigned NOT NULL default '0',
  `autologin` varchar(32) NULL default NULL,
  `createdOn` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `username` (`username`),
  KEY `email` (`email`),
  UNIQUE KEY `autologin` (`autologin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_member_group`
-- 

CREATE TABLE `tl_member_group` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `redirect` char(1) NOT NULL default '',
  `jumpTo` int(10) unsigned NOT NULL default '0',
  `disable` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `headline` varchar(255) NOT NULL default '',
  `type` varchar(64) NOT NULL default '',
  `levelOffset` smallint(5) unsigned NOT NULL default '0',
  `showLevel` smallint(5) unsigned NOT NULL default '0',
  `hardLimit` char(1) NOT NULL default '',
  `showProtected` char(1) NOT NULL default '',
  `defineRoot` char(1) NOT NULL default '',
  `rootPage` int(10) unsigned NOT NULL default '0',
  `navigationTpl` varchar(64) NOT NULL default '',
  `pages` blob NULL,
  `showHidden` char(1) NOT NULL default '',
  `customLabel` varchar(64) NOT NULL default '',
  `autologin` char(1) NOT NULL default '',
  `jumpTo` int(10) unsigned NOT NULL default '0',
  `redirectBack` char(1) NOT NULL default '',
  `cols` varchar(32) NOT NULL default '',
  `editable` blob NULL,
  `memberTpl` varchar(64) NOT NULL default '',
  `tableless` char(1) NOT NULL default '',
  `form` int(10) unsigned NOT NULL default '0',
  `searchType` varchar(32) NOT NULL default '',
  `fuzzy` char(1) NOT NULL default '',
  `contextLength` smallint(5) unsigned NOT NULL default '0',
  `totalLength` smallint(5) unsigned NOT NULL default '0',
  `perPage` smallint(5) unsigned NOT NULL default '0',
  `queryType` varchar(32) NOT NULL default '',
  `searchTpl` varchar(64) NOT NULL default '',
  `inColumn` varchar(32) NOT NULL default '',
  `skipFirst` smallint(5) unsigned NOT NULL default '0',
  `loadFirst` char(1) NOT NULL default '',
  `size` varchar(64) NOT NULL default '',
  `transparent` char(1) NOT NULL default '',
  `flashvars` varchar(255) NOT NULL default '',
  `altContent` text NULL,
  `source` varchar(32) NOT NULL default '',
  `singleSRC` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `interactive` char(1) NOT NULL default '',
  `flashID` varchar(64) NOT NULL default '',
  `flashJS` text NULL,
  `imgSize` varchar(64) NOT NULL default '',
  `useCaption` char(1) NOT NULL default '',
  `fullsize` char(1) NOT NULL default '',
  `multiSRC` blob NULL,
  `html` text NULL,
  `protected` char(1) NOT NULL default '',
  `groups` blob NULL,
  `guests` char(1) NOT NULL default '',
  `cssID` varchar(255) NOT NULL default '',
  `space` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_page`
-- 

CREATE TABLE `tl_page` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  `type` varchar(32) NOT NULL default '',
  `pageTitle` varchar(255) NOT NULL default '',
  `language` varchar(2) NOT NULL default '',
  `robots` varchar(32) NOT NULL default '',
  `description` text NULL,
  `redirect` varchar(32) NOT NULL default '',
  `jumpTo` int(10) unsigned NOT NULL default '0',
  `url` varchar(255) NOT NULL default '',
  `target` char(1) NOT NULL default '',
  `fallback` char(1) NOT NULL default '',
  `dns` varchar(255) NOT NULL default '',
  `staticFiles` varchar(255) NOT NULL default '',
  `staticSystem` varchar(255) NOT NULL default '',
  `staticPlugins` varchar(255) NOT NULL default '',
  `adminEmail` varchar(255) NOT NULL default '',
  `dateFormat` varchar(32) NOT NULL default '',
  `timeFormat` varchar(32) NOT NULL default '',
  `datimFormat` varchar(32) NOT NULL default '',
  `createSitemap` char(1) NOT NULL default '',
  `sitemapName` varchar(32) NOT NULL default '',
  `useSSL` char(1) NOT NULL default '',
  `autoforward` char(1) NOT NULL default '',
  `protected` char(1) NOT NULL default '',
  `groups` blob NULL,
  `includeLayout` char(1) NOT NULL default '',
  `layout` int(10) unsigned NOT NULL default '0',
  `includeCache` char(1) NOT NULL default '',
  `cache` int(10) unsigned NOT NULL default '0',
  `includeChmod` char(1) NOT NULL default '',
  `cuser` int(10) unsigned NOT NULL default '0',
  `cgroup` int(10) unsigned NOT NULL default '0',
  `chmod` varchar(255) NOT NULL default '',
  `noSearch` char(1) NOT NULL default '',
  `cssClass` varchar(64) NOT NULL default '',
  `sitemap` varchar(32) NOT NULL default '',
  `hide` char(1) NOT NULL default '',
  `guests` char(1) NOT NULL default '',
  `tabindex` smallint(5) unsigned NOT NULL default '0',
  `accesskey` char(1) NOT NULL default '',
  `published` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `alias` (`alias`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_search`
-- 

CREATE TABLE `tl_search` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `text` mediumtext NULL,
  `filesize` double unsigned NOT NULL default '0',
  `checksum` varchar(32) NOT NULL default '',
  `protected` char(1) NOT NULL default '',
  `groups` blob NULL,
  `language` varchar(2) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `url` (`url`),
  FULLTEXT KEY `text` (`text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_search_index`
-- 

CREATE TABLE `tl_search_index` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `word` varbinary(64) NOT NULL default '',
  `relevance` smallint(5) unsigned NOT NULL default '0',
  `language` varchar(2) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `word` (`word`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_session`
-- 

CREATE TABLE `tl_session` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `sessionID` varchar(128) NOT NULL default '',
  `hash` varchar(40) NOT NULL default '',
  `ip` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_style`
-- 

CREATE TABLE `tl_style` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `invisible` char(1) NOT NULL default '',
  `selector` varchar(255) NOT NULL default '',
  `category` varchar(32) NOT NULL default '',
  `comment` varchar(255) NOT NULL default '',
  `size` char(1) NOT NULL default '',
  `width` varchar(64) NOT NULL default '',
  `height` varchar(64) NOT NULL default '',
  `minwidth` varchar(64) NOT NULL default '',
  `minheight` varchar(64) NOT NULL default '',
  `maxwidth` varchar(64) NOT NULL default '',
  `maxheight` varchar(64) NOT NULL default '',
  `positioning` char(1) NOT NULL default '',
  `trbl` varchar(128) NOT NULL default '',
  `position` varchar(32) NOT NULL default '',
  `floating` varchar(32) NOT NULL default '',
  `clear` varchar(32) NOT NULL default '',
  `overflow` varchar(32) NOT NULL default '',
  `display` varchar(32) NOT NULL default '',
  `alignment` char(1) NOT NULL default '',
  `margin` varchar(128) NOT NULL default '',
  `padding` varchar(128) NOT NULL default '',
  `align` varchar(32) NOT NULL default '',
  `verticalalign` varchar(32) NOT NULL default '',
  `textalign` varchar(32) NOT NULL default '',
  `background` char(1) NOT NULL default '',
  `bgcolor` varchar(64) NOT NULL default '',
  `bgimage` varchar(255) NOT NULL default '',
  `bgposition` varchar(32) NOT NULL default '',
  `bgrepeat` varchar(32) NOT NULL default '',
  `gradientAngle` varchar(32) NOT NULL default '',
  `gradientColors` varchar(128) NOT NULL default '',
  `shadowsize` varchar(128) NOT NULL default '',
  `shadowcolor` varchar(64) NOT NULL default '',
  `border` char(1) NOT NULL default '',
  `borderwidth` varchar(128) NOT NULL default '',
  `borderstyle` varchar(32) NOT NULL default '',
  `bordercolor` varchar(64) NOT NULL default '',
  `borderradius` varchar(128) NOT NULL default '',
  `bordercollapse` varchar(32) NOT NULL default '',
  `borderspacing` varchar(64) NOT NULL default '',
  `font` char(1) NOT NULL default '',
  `fontfamily` varchar(255) NOT NULL default '',
  `fontsize` varchar(64) NOT NULL default '',
  `fontcolor` varchar(64) NOT NULL default '',
  `lineheight` varchar(64) NOT NULL default '',
  `fontstyle` varchar(255) NOT NULL default '',
  `whitespace` char(1) NOT NULL default '',
  `texttransform` varchar(32) NOT NULL default '',
  `textindent` varchar(64) NOT NULL default '',
  `letterspacing` varchar(64) NOT NULL default '',
  `wordspacing` varchar(64) NOT NULL default '',
  `list` char(1) NOT NULL default '',
  `liststyletype` varchar(32) NOT NULL default '',
  `liststyleimage` varchar(255) NOT NULL default '',
  `own` text NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `selector` (`selector`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_style_sheet`
-- 

CREATE TABLE `tl_style_sheet` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `cc` varchar(32) NOT NULL default '',
  `media` varchar(255) NOT NULL default '',
  `mediaQuery` varchar(255) NOT NULL default '',
  `vars` text NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_theme`
-- 

CREATE TABLE `tl_theme` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(128) NOT NULL default '',
  `author` varchar(128) NOT NULL default '',
  `folders` blob NULL,
  `templates` varchar(255) NOT NULL default '',
  `screenshot` varchar(255) NOT NULL default '',
  `vars` text NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_undo`
-- 

CREATE TABLE `tl_undo` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `fromTable` varchar(255) NOT NULL default '',
  `query` text NULL,
  `affectedRows` smallint(5) unsigned NOT NULL default '0',
  `data` mediumblob NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_user`
-- 

CREATE TABLE `tl_user` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `username` varchar(64) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `language` varchar(2) NOT NULL default '',
  `backendTheme` varchar(32) NOT NULL default '',
  `uploader` varchar(32) NOT NULL default '',
  `showHelp` char(1) NOT NULL default '',
  `thumbnails` char(1) NOT NULL default '',
  `useRTE` char(1) NOT NULL default '',
  `useCE` char(1) NOT NULL default '',
  `password` varchar(64) NOT NULL default '',
  `admin` char(1) NOT NULL default '',
  `groups` blob NULL,
  `inherit` varchar(32) NOT NULL default '',
  `modules` blob NULL,
  `themes` blob NULL,
  `pagemounts` blob NULL,
  `alpty` blob NULL,
  `filemounts` blob NULL,
  `fop` blob NULL,
  `forms` blob NULL,
  `formp` blob NULL,
  `disable` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  `loginCount` smallint(5) unsigned NOT NULL default '3',
  `locked` int(10) unsigned NOT NULL default '0',
  `session` blob NULL,
  `dateAdded` int(10) unsigned NOT NULL default '0',
  `currentLogin` int(10) unsigned NOT NULL default '0',
  `lastLogin` int(10) unsigned NOT NULL default '0',
  `pwChange` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_user_group`
-- 

CREATE TABLE `tl_user_group` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `modules` blob NULL,
  `themes` blob NULL,
  `pagemounts` blob NULL,
  `alpty` blob NULL,
  `filemounts` blob NULL,
  `fop` blob NULL,
  `forms` blob NULL,
  `formp` blob NULL,
  `alexf` blob NULL,
  `disable` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_version`
-- 

CREATE TABLE `tl_version` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `version` smallint(5) unsigned NOT NULL default '1',
  `fromTable` varchar(255) NOT NULL default '',
  `username` varchar(64) NOT NULL default '',
  `active` char(1) NOT NULL default '',
  `data` mediumblob NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
