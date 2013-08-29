<?php defined('SYSPATH') or die('No direct access allowed.');
try {
	DB::query(NULL, "SET FOREIGN_KEY_CHECKS=0")->execute();

	DB::query(NULL, "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."part_revision` (
		`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`part_id` int(10) unsigned NOT NULL,
		`created_on` datetime NOT NULL,
		`content` text NOT NULL,
		PRIMARY KEY (`id`),
		UNIQUE KEY `part_id` (`part_id`,`created_on`)
	  ) ENGINE=InnoDB DEFAULT CHARSET=utf8")->execute();
	
	DB::query(NULL, "ALTER TABLE `".TABLE_PREFIX."part_revision`
		ADD CONSTRAINT `".TABLE_PREFIX."part_revision_ibfk_1` FOREIGN KEY (`part_id`) 
		REFERENCES `".TABLE_PREFIX."page_parts` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;")->execute();

	DB::query(NULL, "SET FOREIGN_KEY_CHECKS=1")->execute();

}  catch (Exception $e) {
	echo debug::vars($e);
}
