<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	array(
		'name' => 'Calendar', 
		'url' => Route::get('backend')->uri(array('controller' => 'calendar')),
		'priority' => 900,
		'icon' => 'calendar',
		'permissions' => 'scheduler.index'
	),
	array(
		'name' => 'System',
		'children' => array(
			array(
				'name' => 'Jobs',
				'icon' => 'bolt',
				'url' => Route::get('backend')->uri(array('controller' => 'jobs')),
				'permissions' => 'jobs.index',
				'priority' => 150,
			)
		)
	)
);
