CREATE TABLE IF NOT EXISTS `__TABLE_PREFIX__config` (
  `group_name` varchar(128) NOT NULL,
  `config_key` varchar(128) NOT NULL,
  `config_value` text NOT NULL,
  PRIMARY KEY (`group_name`,`config_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `__TABLE_PREFIX__pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `breadcrumb` varchar(160) DEFAULT '',
  `meta_title` varchar(255) DEFAULT '',
  `meta_keywords` varchar(255) DEFAULT '',
  `meta_description` text,
  `robots` varchar(100) DEFAULT 'INDEX, FOLLOW',
  `parent_id` int(11) unsigned DEFAULT NULL,
  `layout_file` varchar(250) NOT NULL,
  `behavior_id` varchar(25) NOT NULL,
  `status_id` int(11) unsigned NOT NULL DEFAULT '100',
  `created_on` datetime DEFAULT NULL,
  `published_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) unsigned DEFAULT NULL,
  `updated_by_id` int(11) unsigned DEFAULT NULL,
  `position` mediumint(6) unsigned DEFAULT NULL,
  `needs_login` tinyint(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `created_by_id` (`created_by_id`),
  KEY `updated_by_id` (`updated_by_id`),
  KEY `slug` (`slug`),
  KEY `status_id` (`status_id`),
  CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`created_by_id`) REFERENCES `__TABLE_PREFIX__users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `pages_ibfk_2` FOREIGN KEY (`updated_by_id`) REFERENCES `__TABLE_PREFIX__users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `__TABLE_PREFIX__tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `count` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `__TABLE_PREFIX__page_parts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `filter_id` varchar(25) DEFAULT NULL,
  `content` longtext,
  `content_html` longtext,
  `page_id` int(11) unsigned DEFAULT NULL,
  `is_protected` tinyint(4) DEFAULT '0',
  `is_expanded` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`),
  KEY `name` (`name`),
  CONSTRAINT `page_parts_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `__TABLE_PREFIX__pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `__TABLE_PREFIX__page_tags` (
  `page_id` int(11) unsigned NOT NULL,
  `tag_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `page_id` (`page_id`,`tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `page_tags_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `__TABLE_PREFIX__pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `page_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `__TABLE_PREFIX__tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `__TABLE_PREFIX__page_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `key` varchar(20) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_id` (`page_id`,`key`),
  CONSTRAINT `page_fields_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `__TABLE_PREFIX__pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;