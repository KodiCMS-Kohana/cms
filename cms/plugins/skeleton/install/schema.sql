/*  
SQL код из этого файла выполняется в момент активации плагина
 
__TABLE_PREFIX__ - обязателен для указания в названии таблицы, чтобы
избежать проблем в том случае, если у вас в настройках указан префикс

CREATE TABLE IF NOT EXISTS `__TABLE_PREFIX__skeleton` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

Наличие данного файла не обязательно
*/