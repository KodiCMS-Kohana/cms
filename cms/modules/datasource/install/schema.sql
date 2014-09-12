CREATE TABLE IF NOT EXISTS `__TABLE_PREFIX__datasources` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(64) NOT NULL,
  `docs` int(6) unsigned NOT NULL DEFAULT '0',
  `indexed` int(1) NOT NULL DEFAULT '0',
  `name` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by_id` int(11) unsigned NOT NULL DEFAULT '0',
  `locks` int(3) unsigned NOT NULL DEFAULT '0',
  `code` text,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `docs` (`docs`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;