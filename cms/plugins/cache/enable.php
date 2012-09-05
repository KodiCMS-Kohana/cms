<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

DB::query(Database::INSERT, 'CREATE TABLE IF NOT EXISTS '.TABLE_PREFIX.'cache_page (
	page_id int(11) NOT NULL,
	cache_id varchar(50) collate utf8_bin NOT NULL,
	UNIQUE KEY page_id (page_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8');

$settings = array(
	'cache_dynamic'       => 'no',
	'cache_static'        => 'no',
	'cache_remove_static' => 'no',
	'cache_lifetime'      => 86400
);

// Save plugin settings
Plugins::setAllSettings($settings, 'cache');