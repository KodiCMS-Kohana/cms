<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	array(
		'name' => 'Content',
		'children' => array(
			array(
				'name' => 'Scheduler', 
				'url' => Route::url('backend', array('controller' => 'scheduler')),
				'priority' => 900,
				'icon' => 'calendar',
				'permissions' => 'scheduler.index'
			)
		)
	),
	array(
		'name' => 'System',
		'children' => array(
			array(
				'name' => 'Jobs',
				'icon' => 'bolt',
				'url' => Route::url('backend', array('controller' => 'scheduler', 'action' => 'jobs')),
				'permissions' => 'scheduler.jobs',
				'priority' => 150,
			)
		)
	)
);
