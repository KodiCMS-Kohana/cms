<?php defined('SYSPATH') or die('No direct access allowed.');

Observer::observe('installer_step_other', function($data) {
	$plugins = Plugins::find_all();

	echo View::factory('plugins/install', array(
		'plugins' => $plugins,
		'data' => $data
	));
});
