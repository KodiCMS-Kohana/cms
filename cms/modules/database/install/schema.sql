CREATE TABLE IF NOT EXISTS `__TABLE_PREFIX__sessions` (
  `session_id` varchar(24) NOT NULL,
  `last_active` int(11) UNSIGNED NOT NULL,
  `contents` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_active` (`last_active`)
) ENGINE = MYISAM DEFAULT CHARSET=utf8;