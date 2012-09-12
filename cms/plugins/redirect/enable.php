<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );


$settings = array(
	'domain'       => $_SERVER['HTTP_HOST'],
	'check_url_suffix'  => 'yes',
);

// Save plugin settings
Plugins::setAllSettings($settings, 'redirect');