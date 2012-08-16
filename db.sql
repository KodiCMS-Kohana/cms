-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 18, 2012 at 07:34 AM
-- Server version: 5.1.40
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `frog`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(11) unsigned NOT NULL DEFAULT '0',
  `body` text,
  `author_name` varchar(50) DEFAULT NULL,
  `author_email` varchar(100) DEFAULT NULL,
  `author_link` varchar(100) DEFAULT NULL,
  `ip` char(100) NOT NULL DEFAULT '0',
  `is_approved` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`),
  KEY `created_on` (`created_on`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `comment`
--


-- --------------------------------------------------------

--
-- Table structure for table `layout`
--

CREATE TABLE IF NOT EXISTS `layout` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `content_type` varchar(80) DEFAULT NULL,
  `content` text,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `position` mediumint(6) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `layout`
--

INSERT INTO `layout` (`id`, `name`, `content_type`, `content`, `created_on`, `updated_on`, `created_by_id`, `updated_by_id`, `position`) VALUES
(1, 'none', 'text/html', '<?php echo $this->content(); ?>', '2010-10-20 14:37:10', '2010-11-24 16:46:51', 1, 1, 2),
(2, 'Normal', 'text/html', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"\n"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\n<html xmlns="http://www.w3.org/1999/xhtml">\n<head>\n	<title><?php echo $page->title; ?></title>\n\n	<meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />\n	<meta name="robots" content="index, follow" />\n	<meta name="description" content="<?php echo ($page->description != '''') ? $page->description : ''Default description goes here''; ?>" />\n	<meta name="keywords" content="<?php echo ($page->keywords != '''') ? $page->keywords : ''default, keywords, here''; ?>" />\n	<meta name="author" content="Author Name" />\n\n	<link rel="favourites icon" href="<?php echo URL::site(''favicon.ico''); ?>" />\n	<link rel="stylesheet" href="<?php echo URL::site(''public/themes/normal/screen.css''); ?>" media="screen" type="text/css" />\n	<link rel="stylesheet" href="<?php echo URL::site(''public/themes/normal/print.css''); ?>" media="print" type="text/css" />\n	<link rel="alternate" type="application/rss+xml" title="Frog Default RSS Feed" href="<?php echo URL::site(''rss.xml'') ?>" />\n</head>\n<body>\n<div id="page">\n<?php echo $page->snippet(''header''); ?>\n<div id="content">\n  <h2><?php echo $page->title; ?></h2>\n  <?php echo $page->content(); ?> \n  <?php if ($page->has_content(''extended'')) echo $page->content(''extended''); ?> \n\n</div> <!-- end #content -->\n<div id="sidebar">\n  <?php echo $page->content(''sidebar'', true); ?> \n</div> <!-- end #sidebar -->\n<?php echo $page->snippet(''footer''); ?>\n</div> <!-- end #page -->\n</body>\n</html>', '2010-10-20 14:37:12', '2010-11-26 12:20:48', 1, 1, 1),
(3, 'RSS XML', 'application/rss+xml', '<?php echo $this->content(); ?>', '2010-10-20 14:37:14', '2010-11-24 16:46:51', 1, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `breadcrumb` varchar(160) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` text,
  `parent_id` int(11) unsigned DEFAULT NULL,
  `layout_id` int(11) unsigned DEFAULT NULL,
  `behavior_id` varchar(25) NOT NULL,
  `status_id` int(11) unsigned NOT NULL DEFAULT '100',
  `comment_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `published_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `position` mediumint(6) unsigned DEFAULT NULL,
  `is_protected` tinyint(1) NOT NULL DEFAULT '0',
  `needs_login` tinyint(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=92 ;

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`id`, `title`, `slug`, `breadcrumb`, `keywords`, `description`, `parent_id`, `layout_id`, `behavior_id`, `status_id`, `comment_status`, `created_on`, `published_on`, `updated_on`, `created_by_id`, `updated_by_id`, `position`, `is_protected`, `needs_login`) VALUES
(1, 'Home Page', 'index', 'Home Page', '', '', 0, 2, '', 100, 0, '2010-10-20 00:00:00', '2010-10-20 00:00:00', '2010-10-20 00:00:00', 1, 1, 0, 1, 0),
(56, 'hjkhj', NULL, 'khjkhjk', 'hjk', 'hjk', 0, 0, '', 1, 0, '2010-12-02 09:58:00', NULL, '2010-12-02 09:58:00', NULL, NULL, NULL, 0, 2),
(53, 'Articles', 'articles', 'Articles', '', '', 1, 0, '', 100, 0, '2010-11-26 00:00:00', '2010-12-02 23:41:44', '2010-11-26 00:00:00', 1, 1, 0, 1, 2),
(48, 'test', 'test', 'test', '', '', 1, 0, '', 100, 0, '2010-11-24 11:15:28', '2010-11-24 11:15:48', '2010-11-24 11:15:28', 1, 1, 0, 0, 2),
(89, 'first article', 'first-article', 'first article', '', '', 53, 0, '', 100, 0, '2010-12-02 00:00:00', '2010-12-03 01:04:02', '2010-12-02 00:00:00', 1, 1, NULL, 0, 2),
(90, 'Articles (copy)', 'articles-copy', 'Articles', '', '', 1, 0, '', 100, 0, '2010-11-26 00:00:00', '2011-08-22 22:12:15', '2010-11-26 00:00:00', 1, 1, 1, 1, 2),
(91, 'first article (copy)', 'first-article-copy', 'first article', '', '', 90, 0, '', 100, 0, '2010-12-02 00:00:00', '2011-08-22 22:12:15', '2010-12-02 00:00:00', 1, 1, NULL, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `page_part`
--

CREATE TABLE IF NOT EXISTS `page_part` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `filter_id` varchar(25) DEFAULT NULL,
  `content` longtext,
  `content_html` longtext,
  `page_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=60 ;

--
-- Dumping data for table `page_part`
--

INSERT INTO `page_part` (`id`, `name`, `filter_id`, `content`, `content_html`, `page_id`) VALUES
(1, 'body', '', '<?php $page_article = Site_Page::find(''/articles/''); ?>\n<?php $last_article = $page_article->_children()->order_by(''page.created_on'', ''DESC'')->find();?>\n\n<div class="first entry">\n  <h3><?php echo $last_article->link(); ?></h3>\n  <?php echo $last_article->content(); ?>\n  <?php if ($last_article->has_content(''extended'')) echo $last_article->link(''Continue Reading&#8230;''); ?>\n  <p class="info">Posted by <?php echo $last_article->author; ?> on <?php echo $last_article->date(); ?></p>\n</div>\n\n<?php foreach ($page_article->_children()->limit(4)->offset(1)->order_by(''page.created_on'', ''DESC'')->find_all() as $article): ?>\n<div class="entry">\n  <h3><?php echo $article->link(); ?></h3>\n  <?php echo $article->content; ?>\n  <?php if ($article->has_content(''extended'')) echo $article->link(''Continue Reading&#8230;''); ?>\n  <p class="info">Posted by <?php echo $article->author; ?> on <?php echo $article->date(); ?></p>\n</div>\n<?php endforeach; ?>', '<?php $page_article = Site_Page::find(''/articles/''); ?>\n<?php $last_article = $page_article->_children()->order_by(''page.created_on'', ''DESC'')->find();?>\n\n<div class="first entry">\n  <h3><?php echo $last_article->link(); ?></h3>\n  <?php echo $last_article->content(); ?>\n  <?php if ($last_article->has_content(''extended'')) echo $last_article->link(''Continue Reading&#8230;''); ?>\n  <p class="info">Posted by <?php echo $last_article->author; ?> on <?php echo $last_article->date(); ?></p>\n</div>\n\n<?php foreach ($page_article->_children()->limit(4)->offset(1)->order_by(''page.created_on'', ''DESC'')->find_all() as $article): ?>\n<div class="entry">\n  <h3><?php echo $article->link(); ?></h3>\n  <?php echo $article->content; ?>\n  <?php if ($article->has_content(''extended'')) echo $article->link(''Continue Reading&#8230;''); ?>\n  <p class="info">Posted by <?php echo $article->author; ?> on <?php echo $article->date(); ?></p>\n</div>\n<?php endforeach; ?>', 1),
(56, 'body', '', 'My first content', 'My first content', 89),
(57, 'extended', '', 'content', 'content', 89),
(54, 'sidebar', '', '<h3>About Me</h3>\n\n<p>I''m just a demonstration of how easy it is to use Frog CMS to power a blog. <a href="<?php echo URL::base(); ?>about_us">more ...</a></p>\n\n<h3>Favorite Sites</h3>\n<ul>\n  <li><a href="http://www.madebyfrog.com">Frog CMS</a></li>\n</ul>\n\n<h3>Recent Entries</h3>\n<?php $page_article = Site_Page::find(''/articles/''); ?>\n<ul>\n<?php foreach ($page_article->_children()->limit(10)->order_by(''page.created_on'', ''DESC'')->find_all() as $article): ?>\n  <li><?php echo $article->link(); ?></li> \n<?php endforeach; ?>\n</ul>\n\n<a href="<?php echo URL::base(); ?>articles">Archives</a>\n\n<h3>Syndicate</h3>\n\n<a href="<?php echo URL::base(); ?>rss.xml">Articles RSS Feed</a>', '<h3>About Me</h3>\n\n<p>I''m just a demonstration of how easy it is to use Frog CMS to power a blog. <a href="<?php echo URL::base(); ?>about_us">more ...</a></p>\n\n<h3>Favorite Sites</h3>\n<ul>\n  <li><a href="http://www.madebyfrog.com">Frog CMS</a></li>\n</ul>\n\n<h3>Recent Entries</h3>\n<?php $page_article = Site_Page::find(''/articles/''); ?>\n<ul>\n<?php foreach ($page_article->_children()->limit(10)->order_by(''page.created_on'', ''DESC'')->find_all() as $article): ?>\n  <li><?php echo $article->link(); ?></li> \n<?php endforeach; ?>\n</ul>\n\n<a href="<?php echo URL::base(); ?>articles">Archives</a>\n\n<h3>Syndicate</h3>\n\n<a href="<?php echo URL::base(); ?>rss.xml">Articles RSS Feed</a>', 1),
(58, 'body', '', 'My first content', 'My first content', 91),
(59, 'extended', '', 'content', 'content', 91);

-- --------------------------------------------------------

--
-- Table structure for table `page_tag`
--

CREATE TABLE IF NOT EXISTS `page_tag` (
  `page_id` int(11) unsigned NOT NULL,
  `tag_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `page_id` (`page_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `page_tag`
--

INSERT INTO `page_tag` (`page_id`, `tag_id`) VALUES
(52, 1);

-- --------------------------------------------------------

--
-- Table structure for table `plugin_settings`
--

CREATE TABLE IF NOT EXISTS `plugin_settings` (
  `plugin_id` varchar(40) NOT NULL,
  `name` varchar(40) NOT NULL,
  `value` varchar(255) NOT NULL,
  UNIQUE KEY `plugin_setting_id` (`plugin_id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `plugin_settings`
--

INSERT INTO `plugin_settings` (`plugin_id`, `name`, `value`) VALUES
('comment', 'auto_approve_comment', '0'),
('comment', 'use_captcha', '1'),
('comment', 'rowspage', '15'),
('comment', 'numlabel', '1');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'login', 'Login privileges, granted after account confirmation'),
(2, 'administrator', 'Administrative user, has access to everything.'),
(3, 'developer', 'Developers role');

-- --------------------------------------------------------

--
-- Table structure for table `roles_users`
--

CREATE TABLE IF NOT EXISTS `roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles_users`
--

INSERT INTO `roles_users` (`user_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(24) NOT NULL,
  `last_active` int(10) unsigned NOT NULL,
  `contents` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_active` (`last_active`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`session_id`, `last_active`, `contents`) VALUES
('4cf65716d7e4c2-49082543', 1291212863, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjEyODYzO3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjEzIjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjEyNTY2Ijt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf6539a7fde45-22811953', 1291212556, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjEyNTU2O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjEyIjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjExNjc0Ijt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf64fde3c2b23-31333243', 1291210877, 'YToxOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjEwODc3O30='),
('4cf64b5d772418-09645978', 1291210674, 'YToxOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjEwNjc0O30='),
('4cf658526c61f2-92443537', 1291212882, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjEyODgyO3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjE0IjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjEyODgyIjt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf65856c9a357-10099770', 1291212887, 'YToxOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjEyODg3O30='),
('4cf6585dd54274-20288043', 1291213267, 'YToyOntzOjk6ImF1dGhfdXNlciI7TzoxMDoiTW9kZWxfVXNlciI6Njp7czoxNToiACoAX29iamVjdF9uYW1lIjtzOjQ6InVzZXIiO3M6MTA6IgAqAF9vYmplY3QiO2E6Njp7czoyOiJpZCI7czoxOiIxIjtzOjU6ImVtYWlsIjtzOjEyOiJwYXZlbEBicHkubWUiO3M6ODoidXNlcm5hbWUiO3M6NToiYWRtaW4iO3M6ODoicGFzc3dvcmQiO3M6NTA6IjRhMDk1ODQ0Y2M0MGU0NjI1YWM0MmMxNDRkNDc4YzAzZGNmMjNkYzhkNTYzNjVlMzAzIjtzOjY6ImxvZ2lucyI7czoyOiIxNSI7czoxMDoibGFzdF9sb2dpbiI7czoxMDoiMTI5MTIxMjg5MyI7fXM6MTE6IgAqAF9jaGFuZ2VkIjthOjA6e31zOjEwOiIAKgBfbG9hZGVkIjtiOjE7czo5OiIAKgBfc2F2ZWQiO2I6MTtzOjExOiIAKgBfc29ydGluZyI7YToxOntzOjI6ImlkIjtzOjM6IkFTQyI7fX1zOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjEzMjY3O30='),
('4cf659db5bb8a2-30048549', 1291213275, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjEzMjc1O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjE2IjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjEzMjc1Ijt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf7416cf14932-42429026', 1291272974, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjcyOTc0O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjE4IjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjcyNTU2Ijt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf74329c32a89-02625399', 1291273096, 'YTo4OntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjczMDk2O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjE5IjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjczMDAxIjt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fXM6NDoicGFnZSI7Tzo4OiJzdGRDbGFzcyI6MTE6e3M6OToicGFyZW50X2lkIjtzOjE6IjEiO3M6NToidGl0bGUiO3M6MDoiIjtzOjQ6InNsdWciO3M6MDoiIjtzOjEwOiJicmVhZGNydW1iIjtzOjA6IiI7czo4OiJrZXl3b3JkcyI7czowOiIiO3M6MTE6ImRlc2NyaXB0aW9uIjtzOjA6IiI7czo5OiJsYXlvdXRfaWQiO3M6MDoiIjtzOjExOiJiZWhhdmlvcl9pZCI7czowOiIiO3M6OToic3RhdHVzX2lkIjtzOjE6IjEiO3M6MTE6Im5lZWRzX2xvZ2luIjtzOjE6IjIiO3M6MTI6ImlzX3Byb3RlY3RlZCI7aTowO31zOjEwOiJwYWdlX3BhcnRzIjtPOjg6InN0ZENsYXNzIjoyOntpOjA7Tzo4OiJzdGRDbGFzcyI6Mzp7czo0OiJuYW1lIjtzOjQ6ImJvZHkiO3M6OToiZmlsdGVyX2lkIjtzOjA6IiI7czo3OiJjb250ZW50IjtzOjM6ImdoaiI7fWk6MjtPOjg6InN0ZENsYXNzIjozOntzOjQ6Im5hbWUiO3M6MzoiNjc4IjtzOjk6ImZpbHRlcl9pZCI7czowOiIiO3M6NzoiY29udGVudCI7czowOiIiO319czo4OiJwYWdlX3RhZyI7YToxOntzOjQ6InRhZ3MiO3M6MDoiIjt9czo1OiJlcnJvciI7czoyODoiWW91IGhhdmUgdG8gc3BlY2lmeSBhIHRpdGxlISI7czo5OiJwb3N0X2RhdGEiO086ODoic3RkQ2xhc3MiOjk6e3M6OToicGFyZW50X2lkIjtzOjQ6Imh0bWwiO3M6NToidGl0bGUiO3M6NToiaGpraGoiO3M6MTA6ImJyZWFkY3J1bWIiO3M6Nzoia2hqa2hqayI7czo4OiJrZXl3b3JkcyI7czozOiJoamsiO3M6MTE6ImRlc2NyaXB0aW9uIjtzOjM6ImhqayI7czo5OiJsYXlvdXRfaWQiO3M6MDoiIjtzOjExOiJiZWhhdmlvcl9pZCI7czowOiIiO3M6OToic3RhdHVzX2lkIjtzOjE6IjEiO3M6MTE6Im5lZWRzX2xvZ2luIjtzOjE6IjIiO31zOjE1OiJwb3N0X3BhcnRzX2RhdGEiO086ODoic3RkQ2xhc3MiOjI6e2k6MDthOjM6e3M6NDoibmFtZSI7czo0OiJib2R5IjtzOjk6ImZpbHRlcl9pZCI7czowOiIiO3M6NzoiY29udGVudCI7czo5OiJoamtoamtoamsiO31pOjE7YTozOntzOjQ6Im5hbWUiO3M6NjoiaGpraGprIjtzOjk6ImZpbHRlcl9pZCI7czowOiIiO3M6NzoiY29udGVudCI7czoxMToiIGZqZmdqZm5namgiO319fQ=='),
('4cf743ffca5c43-49358388', 1291275562, 'YTo0OntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjc1NTYyO3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjIwIjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjczMjE1Ijt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fXM6OToicG9zdF9kYXRhIjtPOjg6InN0ZENsYXNzIjoxMDp7czo5OiJwYXJlbnRfaWQiO3M6MToiMSI7czo1OiJ0aXRsZSI7czozOiJyZXciO3M6NDoic2x1ZyI7czozOiJyZXciO3M6MTA6ImJyZWFkY3J1bWIiO3M6MzoicmV3IjtzOjg6ImtleXdvcmRzIjtzOjM6ImVydyI7czoxMToiZGVzY3JpcHRpb24iO3M6MToidyI7czo5OiJsYXlvdXRfaWQiO3M6MDoiIjtzOjExOiJiZWhhdmlvcl9pZCI7czowOiIiO3M6OToic3RhdHVzX2lkIjtzOjE6IjEiO3M6MTE6Im5lZWRzX2xvZ2luIjtzOjE6IjIiO31zOjE1OiJwb3N0X3BhcnRzX2RhdGEiO086ODoic3RkQ2xhc3MiOjI6e2k6MDthOjM6e3M6NDoibmFtZSI7czo0OiJib2R5IjtzOjk6ImZpbHRlcl9pZCI7czowOiIiO3M6NzoiY29udGVudCI7czo3OiJyd2Vyd2VyIjt9aToxO2E6Mzp7czo0OiJuYW1lIjtzOjQ6Ijc1NjciO3M6OToiZmlsdGVyX2lkIjtzOjA6IiI7czo3OiJjb250ZW50IjtzOjY6Imhqa2hqayI7fX19'),
('4cf74d3f054c50-00867664', 1291276198, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjc2MTk4O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjIxIjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjc1NTgzIjt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf750f42bf572-49963577', 1291281039, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjgxMDM5O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjIyIjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjc2NTMyIjt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf762f9300593-02703593', 1291281427, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjgxNDI3O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjIzIjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjgxMTQ1Ijt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf76421208a55-01325377', 1291281467, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjgxNDY3O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjI0IjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjgxNDQxIjt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf7645a20c016-07011752', 1291281757, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjgxNzU3O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjI1IjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjgxNDk4Ijt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf7659d63d548-79037312', 1291282049, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjgyMDQ5O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjI2IjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjgxODIxIjt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf766a56b0124-04668331', 1291282134, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjgyMTM0O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjI3IjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjgyMDg1Ijt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf766dd9991d3-60140536', 1291282470, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjgyNDcwO3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjI4IjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjgyMTQxIjt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf768316a9ca1-53766927', 1291282482, 'YToxOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjgyNDgyO30='),
('4cf7683a4c63f8-45675848', 1291282735, 'YToyOntzOjk6ImF1dGhfdXNlciI7TzoxMDoiTW9kZWxfVXNlciI6Njp7czoxNToiACoAX29iamVjdF9uYW1lIjtzOjQ6InVzZXIiO3M6MTA6IgAqAF9vYmplY3QiO2E6Njp7czoyOiJpZCI7czoxOiIxIjtzOjU6ImVtYWlsIjtzOjEyOiJwYXZlbEBicHkubWUiO3M6ODoidXNlcm5hbWUiO3M6NToiYWRtaW4iO3M6ODoicGFzc3dvcmQiO3M6NTA6IjRhMDk1ODQ0Y2M0MGU0NjI1YWM0MmMxNDRkNDc4YzAzZGNmMjNkYzhkNTYzNjVlMzAzIjtzOjY6ImxvZ2lucyI7czoyOiIyOSI7czoxMDoibGFzdF9sb2dpbiI7czoxMDoiMTI5MTI4MjQ5MCI7fXM6MTE6IgAqAF9jaGFuZ2VkIjthOjA6e31zOjEwOiIAKgBfbG9hZGVkIjtiOjE7czo5OiIAKgBfc2F2ZWQiO2I6MTtzOjExOiIAKgBfc29ydGluZyI7YToxOntzOjI6ImlkIjtzOjM6IkFTQyI7fX1zOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjgyNzM1O30='),
('4cf7693db5e677-06479821', 1291282831, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjgyODMxO3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjMwIjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjgyNzQ5Ijt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf76997506a25-04616010', 1291283213, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjgzMjEzO3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjMxIjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjgyODM5Ijt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf76b29b0e341-19172508', 1291284782, 'YToyOntzOjk6ImF1dGhfdXNlciI7TzoxMDoiTW9kZWxfVXNlciI6Njp7czoxNToiACoAX29iamVjdF9uYW1lIjtzOjQ6InVzZXIiO3M6MTA6IgAqAF9vYmplY3QiO2E6Njp7czoyOiJpZCI7czoxOiIxIjtzOjU6ImVtYWlsIjtzOjEyOiJwYXZlbEBicHkubWUiO3M6ODoidXNlcm5hbWUiO3M6NToiYWRtaW4iO3M6ODoicGFzc3dvcmQiO3M6NTA6IjRhMDk1ODQ0Y2M0MGU0NjI1YWM0MmMxNDRkNDc4YzAzZGNmMjNkYzhkNTYzNjVlMzAzIjtzOjY6ImxvZ2lucyI7czoyOiIzMiI7czoxMDoibGFzdF9sb2dpbiI7czoxMDoiMTI5MTI4MzI0MSI7fXM6MTE6IgAqAF9jaGFuZ2VkIjthOjA6e31zOjEwOiIAKgBfbG9hZGVkIjtiOjE7czo5OiIAKgBfc2F2ZWQiO2I6MTtzOjExOiIAKgBfc29ydGluZyI7YToxOntzOjI6ImlkIjtzOjM6IkFTQyI7fX1zOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjg0NzgyO30='),
('4cf7718f1da353-29670310', 1291284939, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjg0OTM5O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjMzIjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjg0ODc5Ijt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf771dc98f836-88890470', 1291285843, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjg1ODQzO3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjM0IjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjg0OTU2Ijt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf7756630a159-52752615', 1291286494, 'YToyOntzOjk6ImF1dGhfdXNlciI7TzoxMDoiTW9kZWxfVXNlciI6Njp7czoxNToiACoAX29iamVjdF9uYW1lIjtzOjQ6InVzZXIiO3M6MTA6IgAqAF9vYmplY3QiO2E6Njp7czoyOiJpZCI7czoxOiIxIjtzOjU6ImVtYWlsIjtzOjEyOiJwYXZlbEBicHkubWUiO3M6ODoidXNlcm5hbWUiO3M6NToiYWRtaW4iO3M6ODoicGFzc3dvcmQiO3M6NTA6IjRhMDk1ODQ0Y2M0MGU0NjI1YWM0MmMxNDRkNDc4YzAzZGNmMjNkYzhkNTYzNjVlMzAzIjtzOjY6ImxvZ2lucyI7czoyOiIzNSI7czoxMDoibGFzdF9sb2dpbiI7czoxMDoiMTI5MTI4NTg2MiI7fXM6MTE6IgAqAF9jaGFuZ2VkIjthOjA6e31zOjEwOiIAKgBfbG9hZGVkIjtiOjE7czo5OiIAKgBfc2F2ZWQiO2I6MTtzOjExOiIAKgBfc29ydGluZyI7YToxOntzOjI6ImlkIjtzOjM6IkFTQyI7fX1zOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjg2NDk0O30='),
('4cf7755941ec22-65617359', 1291285855, 'YToxOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjg1ODU1O30='),
('4cf778329e1f23-49197938', 1291286594, 'YToxOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjg2NTk0O30='),
('4cf77b37df3c25-06819282', 1291287359, 'YToyOntzOjk6ImF1dGhfdXNlciI7TzoxMDoiTW9kZWxfVXNlciI6Njp7czoxNToiACoAX29iamVjdF9uYW1lIjtzOjQ6InVzZXIiO3M6MTA6IgAqAF9vYmplY3QiO2E6Njp7czoyOiJpZCI7czoxOiIxIjtzOjU6ImVtYWlsIjtzOjEyOiJwYXZlbEBicHkubWUiO3M6ODoidXNlcm5hbWUiO3M6NToiYWRtaW4iO3M6ODoicGFzc3dvcmQiO3M6NTA6IjRhMDk1ODQ0Y2M0MGU0NjI1YWM0MmMxNDRkNDc4YzAzZGNmMjNkYzhkNTYzNjVlMzAzIjtzOjY6ImxvZ2lucyI7czoyOiIzNyI7czoxMDoibGFzdF9sb2dpbiI7czoxMDoiMTI5MTI4NzM1MSI7fXM6MTE6IgAqAF9jaGFuZ2VkIjthOjA6e31zOjEwOiIAKgBfbG9hZGVkIjtiOjE7czo5OiIAKgBfc2F2ZWQiO2I6MTtzOjExOiIAKgBfc29ydGluZyI7YToxOntzOjI6ImlkIjtzOjM6IkFTQyI7fX1zOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjg3MzU5O30='),
('4cf77b44ee89d8-54192900', 1291287365, 'YToxOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjg3MzY1O30='),
('4cf77b4bd403c7-08023191', 1291287712, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjg3NzEyO3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjM4IjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjg3MzcxIjt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf77ca8588420-67848322', 1291287726, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjg3NzI2O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjM5IjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjg3NzIwIjt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf77cd980a2b0-17703382', 1291288795, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjg4Nzk1O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjQwIjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjg3NzY5Ijt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf78118a083d9-76646884', 1291288861, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjg4ODYxO3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjQxIjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjg4ODU2Ijt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf78121bd4120-22295711', 1291288868, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjg4ODY4O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjQyIjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjg4ODY1Ijt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf78133d5ffb5-03373143', 1291288919, 'YToxOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjg4OTE5O30='),
('4cf781b951bb76-77233759', 1291289027, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjg5MDI3O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjQ1IjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjg5MDE3Ijt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf781c8897786-55645523', 1291289038, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjg5MDM4O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjQ2IjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjg5MDMyIjt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf781db7c66d8-70678612', 1291289330, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjg5MzMwO3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjQ3IjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjg5MDUxIjt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf7830aeb9b82-78205567', 1291289510, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjg5NTEwO3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjQ4IjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjg5MzU0Ijt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf78484953306-58257185', 1291293224, 'YTozOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjkzMjI0O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjQ5IjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjg5NzMyIjt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fXM6OToicG9zdF9kYXRhIjtPOjg6InN0ZENsYXNzIjozOntzOjQ6Im5hbWUiO3M6OToic25pcHBldDEwIjtzOjk6ImZpbHRlcl9pZCI7czowOiIiO3M6NzoiY29udGVudCI7czo3OiJmc2Rmc2RmIjt9fQ=='),
('4cf792424917b0-25935479', 1291293251, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjkzMjUxO3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo2OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo4OiJwYXNzd29yZCI7czo1MDoiNGEwOTU4NDRjYzQwZTQ2MjVhYzQyYzE0NGQ0NzhjMDNkY2YyM2RjOGQ1NjM2NWUzMDMiO3M6NjoibG9naW5zIjtzOjI6IjUwIjtzOjEwOiJsYXN0X2xvZ2luIjtzOjEwOiIxMjkxMjkzMjUwIjt9czoxMToiACoAX2NoYW5nZWQiO2E6MDp7fXM6MTA6IgAqAF9sb2FkZWQiO2I6MTtzOjk6IgAqAF9zYXZlZCI7YjoxO3M6MTE6IgAqAF9zb3J0aW5nIjthOjE6e3M6MjoiaWQiO3M6MzoiQVNDIjt9fX0='),
('4cf7926e132344-02690592', 1291298048, 'YTozOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjk4MDQ4O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo3OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo0OiJuYW1lIjtzOjEzOiJBZG1pbmlzdHJhdG9yIjtzOjg6InBhc3N3b3JkIjtzOjUwOiI0YTA5NTg0NGNjNDBlNDYyNWFjNDJjMTQ0ZDQ3OGMwM2RjZjIzZGM4ZDU2MzY1ZTMwMyI7czo2OiJsb2dpbnMiO3M6MjoiNTEiO3M6MTA6Imxhc3RfbG9naW4iO3M6MTA6IjEyOTEyOTMyOTQiO31zOjExOiIAKgBfY2hhbmdlZCI7YTowOnt9czoxMDoiACoAX2xvYWRlZCI7YjoxO3M6OToiACoAX3NhdmVkIjtiOjE7czoxMToiACoAX3NvcnRpbmciO2E6MTp7czoyOiJpZCI7czozOiJBU0MiO319czo5OiJwb3N0X2RhdGEiO086ODoic3RkQ2xhc3MiOjU6e3M6NDoibmFtZSI7czoxMDoi0J/QsNCy0LXQuyI7czo1OiJlbWFpbCI7czoxMzoicGF2ZUBhZG1pbi5ydSI7czo4OiJ1c2VybmFtZSI7czo1OiJwYXZlbCI7czo4OiJwYXNzd29yZCI7czo2OiJienp6enoiO3M6NzoiY29uZmlybSI7czo2OiJienp6enoiO319'),
('4cf7a51a383e31-06219505', 1291298327, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjk4MzI3O3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo3OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo0OiJuYW1lIjtzOjEzOiJBZG1pbmlzdHJhdG9yIjtzOjg6InBhc3N3b3JkIjtzOjUwOiI0YTA5NTg0NGNjNDBlNDYyNWFjNDJjMTQ0ZDQ3OGMwM2RjZjIzZGM4ZDU2MzY1ZTMwMyI7czo2OiJsb2dpbnMiO3M6MjoiNTIiO3M6MTA6Imxhc3RfbG9naW4iO3M6MTA6IjEyOTEyOTgwNzQiO31zOjExOiIAKgBfY2hhbmdlZCI7YTowOnt9czoxMDoiACoAX2xvYWRlZCI7YjoxO3M6OToiACoAX3NhdmVkIjtiOjE7czoxMToiACoAX3NvcnRpbmciO2E6MTp7czoyOiJpZCI7czozOiJBU0MiO319fQ=='),
('4cf7a61c713553-61366389', 1291298332, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMjk4MzMyO3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo3OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo0OiJuYW1lIjtzOjEzOiJBZG1pbmlzdHJhdG9yIjtzOjg6InBhc3N3b3JkIjtzOjUwOiI0YTA5NTg0NGNjNDBlNDYyNWFjNDJjMTQ0ZDQ3OGMwM2RjZjIzZGM4ZDU2MzY1ZTMwMyI7czo2OiJsb2dpbnMiO3M6MjoiNTMiO3M6MTA6Imxhc3RfbG9naW4iO3M6MTA6IjEyOTEyOTgzMzIiO31zOjExOiIAKgBfY2hhbmdlZCI7YTowOnt9czoxMDoiACoAX2xvYWRlZCI7YjoxO3M6OToiACoAX3NhdmVkIjtiOjE7czoxMToiACoAX3NvcnRpbmciO2E6MTp7czoyOiJpZCI7czozOiJBU0MiO319fQ=='),
('4cf7f83eb1ff34-18931282', 1291330680, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMjkxMzMwNjgwO3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo3OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo0OiJuYW1lIjtzOjEzOiJBZG1pbmlzdHJhdG9yIjtzOjg6InBhc3N3b3JkIjtzOjUwOiI0YTA5NTg0NGNjNDBlNDYyNWFjNDJjMTQ0ZDQ3OGMwM2RjZjIzZGM4ZDU2MzY1ZTMwMyI7czo2OiJsb2dpbnMiO3M6MjoiNTQiO3M6MTA6Imxhc3RfbG9naW4iO3M6MTA6IjEyOTEzMTkzNTgiO31zOjExOiIAKgBfY2hhbmdlZCI7YTowOnt9czoxMDoiACoAX2xvYWRlZCI7YjoxO3M6OToiACoAX3NhdmVkIjtiOjE7czoxMToiACoAX3NvcnRpbmciO2E6MTp7czoyOiJpZCI7czozOiJBU0MiO319fQ=='),
('4e529bfb801c26-76505784', 1314036841, 'YToyOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxMzE0MDM2ODQxO3M6OToiYXV0aF91c2VyIjtPOjEwOiJNb2RlbF9Vc2VyIjo2OntzOjE1OiIAKgBfb2JqZWN0X25hbWUiO3M6NDoidXNlciI7czoxMDoiACoAX29iamVjdCI7YTo3OntzOjI6ImlkIjtzOjE6IjEiO3M6NToiZW1haWwiO3M6MTI6InBhdmVsQGJweS5tZSI7czo4OiJ1c2VybmFtZSI7czo1OiJhZG1pbiI7czo0OiJuYW1lIjtzOjEzOiJBZG1pbmlzdHJhdG9yIjtzOjg6InBhc3N3b3JkIjtzOjUwOiI0YTA5NTg0NGNjNDBlNDYyNWFjNDJjMTQ0ZDQ3OGMwM2RjZjIzZGM4ZDU2MzY1ZTMwMyI7czo2OiJsb2dpbnMiO3M6MjoiNTUiO3M6MTA6Imxhc3RfbG9naW4iO3M6MTA6IjEzMTQwMzY3MzEiO31zOjExOiIAKgBfY2hhbmdlZCI7YTowOnt9czoxMDoiACoAX2xvYWRlZCI7YjoxO3M6OToiACoAX3NhdmVkIjtiOjE7czoxMToiACoAX3NvcnRpbmciO2E6MTp7czoyOiJpZCI7czozOiJBU0MiO319fQ==');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE IF NOT EXISTS `setting` (
  `name` varchar(40) NOT NULL,
  `value` text NOT NULL,
  UNIQUE KEY `id` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`name`, `value`) VALUES
('admin_title', 'Kohana Frog Fork'),
('language', 'ru'),
('theme', 'default'),
('default_status_id', '1'),
('default_filter_id', ''),
('default_tab', 'page'),
('allow_html_title', 'off'),
('plugins', 'a:6:{s:5:"field";i:1;s:11:"hybrid_data";i:1;s:8:"markdown";i:1;s:6:"hybrid";i:1;s:7:"textile";i:1;s:7:"archive";i:1;}');

-- --------------------------------------------------------

--
-- Table structure for table `snippet`
--

CREATE TABLE IF NOT EXISTS `snippet` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `filter_id` varchar(25) DEFAULT NULL,
  `content` text,
  `content_html` text,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `position` mediumint(6) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `snippet`
--

INSERT INTO `snippet` (`id`, `name`, `filter_id`, `content`, `content_html`, `created_on`, `updated_on`, `created_by_id`, `updated_by_id`, `position`) VALUES
(1, 'header', '', '<div id="header">\n  <h1><a href="<?php echo URL::site(); ?>">Frog</a> <span>content management simplified</span></h1>\n  <div id="nav">\n    <ul>\n      <li><a href="<?php echo URL::site(); ?>">Home</a></li>\n<?php foreach(Site_Page::find(''/'')->children() as $menu): ?>\n      <li><?php echo $menu->link($menu->title, (in_array($menu->slug, explode(''/'', $page->url())) ? '' class="current"'': null)); ?></li>\n<?php endforeach; ?> \n    </ul>\n  </div> <!-- end #navigation -->\n</div> <!-- end #header -->', '<div id="header">\n  <h1><a href="<?php echo URL::site(); ?>">Frog</a> <span>content management simplified</span></h1>\n  <div id="nav">\n    <ul>\n      <li><a href="<?php echo URL::site(); ?>">Home</a></li>\n<?php foreach(Site_Page::find(''/'')->children() as $menu): ?>\n      <li><?php echo $menu->link($menu->title, (in_array($menu->slug, explode(''/'', $page->url())) ? '' class="current"'': null)); ?></li>\n<?php endforeach; ?> \n    </ul>\n  </div> <!-- end #navigation -->\n</div> <!-- end #header -->', '2010-10-20 14:37:37', '2010-11-26 16:23:03', 1, 1, 1),
(2, 'footer', '', '<div id="footer"><div id="footer-inner">\r\n  <p>&copy; Copyright <?php echo date(''Y''); ?> <a href="http://www.madebyfrog.com/" title="Frog">Madebyfrog.com</a><br />\r\n  Powered by <a href="http://www.madebyfrog.com/" title="Frog CMS">Frog CMS</a>.\r\n  </p>\r\n</div></div><!-- end #footer -->', '<div id="footer"><div id="footer-inner">\r\n  <p>&copy; Copyright <?php echo date(''Y''); ?> <a href="http://www.madebyfrog.com/" title="Frog">Madebyfrog.com</a><br />\r\n  Powered by <a href="http://www.madebyfrog.com/" title="Frog CMS">Frog CMS</a>.\r\n  </p>\r\n</div></div><!-- end #footer -->', '2010-10-20 14:37:39', '2010-10-20 14:37:40', 1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `count` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`id`, `name`, `count`) VALUES
(1, 'tag1', 1),
(2, 'tag2', 0),
(3, 'tag3', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(127) NOT NULL,
  `username` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL,
  `password` char(50) NOT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_username` (`username`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `name`, `password`, `logins`, `last_login`) VALUES
(1, 'pavel@bpy.me', 'admin', 'Administrator', '4a095844cc40e4625ac42c144d478c03dcf23dc8d56365e303', 55, 1314036731),
(2, 'pave@admin.ru', 'pavel', 'Павелghj', '0a8ba7e28580956143222cf6453d6f91334eb79025b9e8c76d', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

CREATE TABLE IF NOT EXISTS `user_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `user_agent` varchar(40) NOT NULL,
  `token` varchar(32) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_token` (`token`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `user_tokens`
--

INSERT INTO `user_tokens` (`id`, `user_id`, `user_agent`, `token`, `created`, `expires`) VALUES
(2, 1, '90cea0dbdc4587c60903626b2ea54be2e0722950', 'ATYXJZBfRlMOtJQgmUQSuHYpZ1bOzrnV', 1291275582, 1292485182),
(3, 1, '3c00e7ba7b8d494e6abda8e10223e322ae51a578', 'woO3P2wNnfgkwO4yf8zbohC26zbBcwbY', 1291276532, 1292486132),
(4, 1, '90cea0dbdc4587c60903626b2ea54be2e0722950', 'sZeaVhtboD0bsWeKvmn2fS5Dqs9CVz07', 1291281145, 1292490745),
(5, 1, '90cea0dbdc4587c60903626b2ea54be2e0722950', 'vX7SYiX9hTSEnZsYGExrtEb5NJ60xajl', 1291282749, 1292492349),
(6, 1, '3c00e7ba7b8d494e6abda8e10223e322ae51a578', 'vvqLDvSKEiY1SwfSBD8cZQRScwlx2qU7', 1291282839, 1292492439),
(7, 1, '3c00e7ba7b8d494e6abda8e10223e322ae51a578', '4XturhKFSnrXRF9wXUQPu8JPn8GoV1z2', 1291284956, 1292494556),
(8, 1, '3c00e7ba7b8d494e6abda8e10223e322ae51a578', 'KE3rvLwyPDJk7dIGU17SCaogBFveq0KW', 1291285862, 1292495462),
(11, 1, '3c00e7ba7b8d494e6abda8e10223e322ae51a578', '5JSlUFEfdi3MYycbLi7mDbok7YpJLaEq', 1291289017, 1292498617),
(12, 1, '165db7776f4c4ed4ef2ee5c935e6d46c331a042b', '429ESWndcJupHMPvcW3k0SrlNhOLpAmG', 1291319358, 1292528958);
