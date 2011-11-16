-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

--
-- Table `tl_user_acl`
--

CREATE TABLE `tl_user_acl` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `user` int(10) unsigned NOT NULL default '0',
  `acl` varchar(1024) NOT NULL default '',
  `right` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_user_group_acl`
--

CREATE TABLE `tl_user_group_acl` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `group` int(10) unsigned NOT NULL default '0',
  `acl` varchar(1024) NOT NULL default '',
  `right` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_member_acl`
--

CREATE TABLE `tl_member_acl` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `user` int(10) unsigned NOT NULL default '0',
  `acl` varchar(1024) NOT NULL default '',
  `right` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_member_group_acl`
--

CREATE TABLE `tl_member_group_acl` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `group` int(10) unsigned NOT NULL default '0',
  `acl` varchar(1024) NOT NULL default '',
  `right` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
