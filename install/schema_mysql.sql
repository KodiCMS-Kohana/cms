CREATE TABLE TABLE_PREFIX_page (
  id int(11) unsigned NOT NULL auto_increment,
  title varchar(255) default NULL,
  slug varchar(100) default NULL,
  breadcrumb varchar(160) default NULL,
  keywords varchar(255) default NULL,
  description text,
  parent_id int(11) unsigned default NULL,
  layout_file varchar(250) NOT NULL,
  behavior_id varchar(25) NOT NULL,
  status_id int(11) unsigned NOT NULL default '100',
  created_on datetime default NULL,
  published_on datetime default NULL,
  updated_on datetime default NULL,
  created_by_id int(11) default NULL,
  updated_by_id int(11) default NULL,
  position mediumint(6) unsigned default NULL,
  needs_login tinyint(1) NOT NULL default '2',
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE TABLE_PREFIX_page_permission (
  page_id int(11) NOT NULL,
  permission_id int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE TABLE_PREFIX_page_part (
  id int(11) unsigned NOT NULL auto_increment,
  name varchar(100) default NULL,
  filter_id varchar(25) default NULL,
  content longtext,
  content_html longtext,
  page_id int(11) unsigned default NULL,
  is_protected tinyint(4) default '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE TABLE_PREFIX_page_tag (
  page_id int(11) unsigned NOT NULL,
  tag_id int(11) unsigned NOT NULL,
  UNIQUE KEY page_id (page_id,tag_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE TABLE_PREFIX_permission (
  id int(11) NOT NULL auto_increment,
  name varchar(25) NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY name (name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE TABLE_PREFIX_setting (
  name varchar(40) NOT NULL,
  value text NOT NULL,
  UNIQUE KEY id (name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE TABLE_PREFIX_plugin_settings (
  plugin_id varchar(40) NOT NULL,
  name varchar(40) NOT NULL,
  value varchar(255) NOT NULL,
  UNIQUE KEY plugin_setting_id (plugin_id,name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE TABLE_PREFIX_user (
  id int(11) unsigned NOT NULL auto_increment,
  name varchar(100) default NULL,
  email varchar(255) default NULL,
  username varchar(40) NOT NULL,
  password varchar(40) default NULL,
  language varchar(5) default NULL,
  created_on datetime default NULL,
  updated_on datetime default NULL,
  created_by_id int(11) default NULL,
  updated_by_id int(11) default NULL,
  last_login datetime NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY username (username)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE TABLE_PREFIX_user_permission (
  user_id int(11) NOT NULL,
  permission_id int(11) NOT NULL,
  UNIQUE KEY user_id (user_id,permission_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE TABLE_PREFIX_tag (
  id int(11) unsigned NOT NULL auto_increment,
  name varchar(40) NOT NULL,
  count int(11) unsigned NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY name (name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;