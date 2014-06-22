<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

Observer::observe('view_setting_plugins', function($plugin) {
	echo View::factory('maintenance/settings_page', array(
		'plugin' => $plugin
	));
}, $plugin);