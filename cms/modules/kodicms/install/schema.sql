CREATE TABLE IF NOT EXISTS `__TABLE_PREFIX__config` (
  `group_name` varchar(128) NOT NULL,
  `config_key` varchar(128) NOT NULL,
  `config_value` text NOT NULL,
  PRIMARY KEY (`group_name`,`config_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `__TABLE_PREFIX__media` (
  `id` int(18) NOT NULL AUTO_INCREMENT,
  `module` varchar(50) DEFAULT NULL,
  `size` int(18) NOT NULL,
  `content_type` varchar(255) DEFAULT 'image',
  `filename` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;