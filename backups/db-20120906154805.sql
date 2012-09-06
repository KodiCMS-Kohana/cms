SET FOREIGN_KEY_CHECKS = 0;

--
-- Table structure for table `page_parts`
--

CREATE TABLE `page_parts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `filter_id` varchar(25) DEFAULT NULL,
  `content` longtext,
  `content_html` longtext,
  `page_id` int(11) unsigned DEFAULT NULL,
  `is_protected` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`),
  KEY `name` (`name`),
  CONSTRAINT `page_parts_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `page_parts`
--

INSERT INTO `page_parts` VALUES ('1','body','','','','1','0');
INSERT INTO `page_parts` VALUES ('2','body','','','','10','0');



--
-- Table structure for table `page_roles`
--

CREATE TABLE `page_roles` (
  `page_id` int(11) unsigned NOT NULL,
  `role_id` int(11) unsigned NOT NULL,
  KEY `page_id` (`page_id`,`role_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `page_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `page_roles_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `page_roles`
--

INSERT INTO `page_roles` VALUES ('1','2');
INSERT INTO `page_roles` VALUES ('10','2');



--
-- Table structure for table `page_tags`
--

CREATE TABLE `page_tags` (
  `page_id` int(11) unsigned NOT NULL,
  `tag_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `page_id` (`page_id`,`tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `page_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `page_tags_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `page_tags`
--




--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
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
  KEY `slug` (`slug`),
  KEY `status_id` (`status_id`),
  CONSTRAINT `pages_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `pages` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` VALUES ('1','Home','','Home','','','0','normal','','100','2012-08-15 11:02:20','2012-08-15 11:02:21','2012-09-05 15:07:46','1','1','0','0');
INSERT INTO `pages` VALUES ('2','Articles','articles','Articles','','','1','','archive','100','2012-08-15 11:02:23','2012-08-15 11:02:24','2012-08-16 18:06:57','1','1','0','2');
INSERT INTO `pages` VALUES ('5','Webbing the gap between science and the public','webbing-the-gap-between-science-and-the-public','Webbing the gap between science and the public','','','2','','','100','2012-08-15 11:02:32','2012-08-15 11:02:33','2012-08-15 11:02:34','1','1','5','2');
INSERT INTO `pages` VALUES ('6','GoogleServe 2011: Giving back around the world','googleserve-2011-giving-back-around-the-world','GoogleServe 2011: Giving back around the world','','','2','','','100','2012-08-15 11:02:35','2012-08-15 11:02:36','2012-08-15 11:02:37','1','1','9','2');
INSERT INTO `pages` VALUES ('7','Seeking the Americas’ brightest young minds for a spot at Zeitgeist Americas','seeking-the-americas-brightest-young-minds-for-a-spot-at-zeitgeist-americas','Seeking the Americas’ brightest young minds for a spot at Zeitgeist Americas','','','2','','','100','2012-08-15 11:02:38','2012-08-15 11:02:39','2012-08-15 11:02:40','1','1','6','2');
INSERT INTO `pages` VALUES ('8','%B %Y archive','monthly_archive','%B %Y archive','','','2','','archive_month_index','101','2012-08-15 11:02:47','2012-08-15 11:02:48','2012-08-16 14:41:59','1','1','0','2');
INSERT INTO `pages` VALUES ('9','Sitemap XML','sitemap.xml','Sitemap XML','','','1','sietmap.xml','','101','2012-08-15 11:02:50','2012-08-15 11:02:51','2012-08-15 17:13:35','1','1','1','2');
INSERT INTO `pages` VALUES ('10','Page not found','page-not-found','Page not found','','','1','','','101','2012-08-15 11:02:53','2012-08-15 11:02:54','2012-08-15 17:13:35','1','1','3','2');
INSERT INTO `pages` VALUES ('11','RSS XML Feed','rss.xml','RSS XML Feed','','','1','rss.xml','','101','2012-08-15 11:02:56','2012-08-15 11:02:57','2012-08-15 17:13:35','1','1','2','2');
INSERT INTO `pages` VALUES ('12','Celebrating Pride 2011','celebrating-pride-2011','Celebrating Pride 2011','','','2','','','100','2012-08-15 11:02:44','2012-08-15 11:02:45','2012-08-15 11:02:46','1','1','7','2');
INSERT INTO `pages` VALUES ('13','Examining the impact of clean energy innovation','examining-the-impact-of-clean-energy-innovation','Examining the impact of clean energy innovation','','','2','','','100','2012-08-15 11:02:41','2012-08-15 11:02:42','2012-08-15 11:02:43','1','1','8','2');
INSERT INTO `pages` VALUES ('21','Поиск','search','Поиск','','','1','','search_result','100','2012-08-17 16:24:20','2012-08-17 16:24:20','2012-09-05 17:36:57','1','1','6','2');
INSERT INTO `pages` VALUES ('23','QA','qa','QA','','','1','normal','','100','2012-08-20 10:54:30','2012-08-20 10:54:12','2012-08-22 14:03:25','1','1','7','0');
INSERT INTO `pages` VALUES ('24','view','view','view','','','23','','','101','2012-08-20 11:59:44','2012-08-20 11:59:37','2012-08-20 14:29:19','1','1','1','0');



--
-- Table structure for table `plugin_settings`
--

CREATE TABLE `plugin_settings` (
  `plugin_id` varchar(40) NOT NULL,
  `name` varchar(40) NOT NULL,
  `value` varchar(255) NOT NULL,
  UNIQUE KEY `plugin_setting_id` (`plugin_id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `plugin_settings`
--

INSERT INTO `plugin_settings` VALUES ('cache','cache_dynamic','no');
INSERT INTO `plugin_settings` VALUES ('cache','cache_static','no');
INSERT INTO `plugin_settings` VALUES ('cache','cache_remove_static','no');
INSERT INTO `plugin_settings` VALUES ('cache','cache_lifetime','86400');



--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` VALUES ('1','login','Login privileges, granted after account confirmation');
INSERT INTO `roles` VALUES ('2','administrator','Administrative user, has access to everything.');
INSERT INTO `roles` VALUES ('3','developer','Developers role');
INSERT INTO `roles` VALUES ('4','editor','');



--
-- Table structure for table `roles_users`
--

CREATE TABLE `roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`),
  CONSTRAINT `roles_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `roles_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles_users`
--

INSERT INTO `roles_users` VALUES ('1','1');
INSERT INTO `roles_users` VALUES ('1','2');
INSERT INTO `roles_users` VALUES ('1','3');



--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `name` varchar(40) NOT NULL,
  `value` text NOT NULL,
  UNIQUE KEY `id` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` VALUES ('admin_title','Kohana Frog Fork');
INSERT INTO `settings` VALUES ('language','ru');
INSERT INTO `settings` VALUES ('theme','default');
INSERT INTO `settings` VALUES ('default_status_id','100');
INSERT INTO `settings` VALUES ('default_filter_id','');
INSERT INTO `settings` VALUES ('default_tab','/admin/page');
INSERT INTO `settings` VALUES ('allow_html_title','off');
INSERT INTO `settings` VALUES ('plugins','a:7:{s:5:\"field\";i:1;s:11:\"hybrid_data\";i:1;s:8:\"markdown\";i:1;s:6:\"hybrid\";i:1;s:7:\"textile\";i:1;s:6:\"backup\";i:1;s:7:\"archive\";i:1;}');
INSERT INTO `settings` VALUES ('profiling','no');



--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `count` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` VALUES ('1','tag1','1');
INSERT INTO `tags` VALUES ('2','tag2','0');
INSERT INTO `tags` VALUES ('3','tag3','0');



--
-- Table structure for table `user_tokens`
--

CREATE TABLE `user_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `user_agent` varchar(40) NOT NULL,
  `token` varchar(32) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_token` (`token`),
  KEY `fk_user_id` (`user_id`),
  CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_tokens`
--




--
-- Table structure for table `users`
--

CREATE TABLE `users` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` VALUES ('1','butschster@gmail.com','admin','Administrator','8e7abffa63ff74a22b9b543fc80a4e8f17cc49cacf4d780443763a98009ec645','2','1346921931');



SET FOREIGN_KEY_CHECKS = 1;

--

--
-- THE END
--

