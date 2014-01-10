CREATE TABLE IF NOT EXISTS `__TABLE_PREFIX__search_index` (
  `id` int(10) unsigned NOT NULL,
  `module` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `title` text NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`,`module`),
  FULLTEXT KEY `content` (`content`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;