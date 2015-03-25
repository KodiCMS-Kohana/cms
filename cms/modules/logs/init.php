<?php defined('SYSPATH') or die('No direct access allowed.');

Observer::observe('view_user_profile_information', function($user_id) {
	$logs = Api::get('log.get', array('uids' => $user_id, 'level' => Log::INFO))->as_object()->get('response');
	echo View::factory('logs/profile/activity', array(
		'user_id' => (int) $user_id,
		'logs' => $logs
	));
});

Observer::observe('view_setting_plugins', function() {
	echo View::factory('logs/settings');
});

Observer::observe('view_navbar_menu', function() {
	echo View::factory('logs/navbar');
});