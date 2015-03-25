<?php defined('SYSPATH') or die('No direct access allowed.');

 Observer::observe('system::init', function() {
	if (Config::get('job', 'agent') == Model_Job::AGENT_CRON)
	{
		return;
	}

	try
	{
		ORM::factory('job')->run_all();
	}
	catch (Exception $ex)
	{
		
	}
});

Observer::observe('view_setting_plugins', function() {
	echo View::factory('jobs/settings');
});
