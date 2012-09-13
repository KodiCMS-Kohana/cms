SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE `TABLE_PREFIX_pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `breadcrumb` varchar(160) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` text,
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
  KEY `status_id` (`status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE `TABLE_PREFIX_page_parts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `filter_id` varchar(25) DEFAULT NULL,
  `content` longtext,
  `content_html` longtext,
  `page_id` int(11) unsigned DEFAULT NULL,
  `is_protected` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE `TABLE_PREFIX_page_roles` (
  `page_id` int(11) unsigned NOT NULL,
  `role_id` int(11) unsigned NOT NULL,
  KEY `page_id` (`page_id`,`role_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `TABLE_PREFIX_page_tags` (
  `page_id` int(11) unsigned NOT NULL,
  `tag_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `page_id` (`page_id`,`tag_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `TABLE_PREFIX_plugin_settings` (
  `plugin_id` varchar(40) NOT NULL,
  `name` varchar(40) NOT NULL,
  `value` varchar(255) NOT NULL,
  UNIQUE KEY `plugin_setting_id` (`plugin_id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `TABLE_PREFIX_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `TABLE_PREFIX_roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `TABLE_PREFIX_settings` (
  `name` varchar(40) NOT NULL,
  `value` text NOT NULL,
  UNIQUE KEY `id` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `TABLE_PREFIX_tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `count` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `TABLE_PREFIX_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(127) NOT NULL,
  `username` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL,
  `password` char(64) NOT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_username` (`username`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE `TABLE_PREFIX_user_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `user_agent` varchar(40) NOT NULL,
  `token` varchar(32) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_token` (`token`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


ALTER TABLE `TABLE_PREFIX_pages` DROP FOREIGN KEY `pages_ibfk_1` ,
	ADD FOREIGN KEY ( `created_by_id` ) REFERENCES `flexokohana`.`users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
	ADD FOREIGN KEY ( `updated_by_id` ) REFERENCES `flexokohana`.`users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE ;

ALTER TABLE `TABLE_PREFIX_pages`
  ADD CONSTRAINT `TABLE_PREFIX_pages_ibfk_1` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,

ALTER TABLE `TABLE_PREFIX_page_parts`
  ADD CONSTRAINT `TABLE_PREFIX_page_parts_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `TABLE_PREFIX_page_roles`
  ADD CONSTRAINT `TABLE_PREFIX_page_roles_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `TABLE_PREFIX_page_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `TABLE_PREFIX_page_tags`
  ADD CONSTRAINT `TABLE_PREFIX_page_tags_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `TABLE_PREFIX_page_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `TABLE_PREFIX_roles_users`
  ADD CONSTRAINT `TABLE_PREFIX_roles_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `TABLE_PREFIX_roles_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `TABLE_PREFIX_user_tokens`
  ADD CONSTRAINT `TABLE_PREFIX_user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

SET FOREIGN_KEY_CHECKS=1;