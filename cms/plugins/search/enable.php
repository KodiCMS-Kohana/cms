<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );


$settings = array(
	'search_query'       => 'q',
	'search_only_title'  => 'yes',
);

// Save plugin settings
Plugin::setAllSettings($settings, 'search');