<?php defined('SYSPATH') or die('No direct access allowed.');

 Observer::observe('modules::afer_load', function() {
	if( Config::get('job', 'agent', Model_Job::AGENT_SYSTEM) === Model_Job::AGENT_CRON) return;
	
	ORM::factory('job')->run_all();
 });
 
 Observer::observe('view_setting_plugins', function() {
	echo View::factory('scheduler/settings_page');
});
