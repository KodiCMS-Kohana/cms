CREATE TABLE IF NOT EXISTS `__TABLE_PREFIX__jobs` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `job` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `date_last_run` datetime NOT NULL,
  `date_next_run` datetime NOT NULL,
  `interval` int(11) NOT NULL,
  `crontime` varchar(100) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `attempts` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `date_next_run` (`date_next_run`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `__TABLE_PREFIX__job_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` int(11) unsigned NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `status` varchar(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `job_id` (`job_id`),
  CONSTRAINT `__TABLE_PREFIX__job_logs_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `__TABLE_PREFIX__jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `__TABLE_PREFIX__calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `className` varchar(50) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime DEFAULT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `allDay` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;