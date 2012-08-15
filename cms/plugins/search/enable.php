<?php if (!defined('CMS_ROOT')) die;


$settings = array(
	'search_query'       => 'q',
	'search_only_title'  => 'yes',
);

// Save plugin settings
Plugin::setAllSettings($settings, 'search');