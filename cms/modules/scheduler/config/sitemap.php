<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	array(
		'name' => 'Content',
		'children' => array(
			array(
				'name' => __('Scheduler'), 
				'url' => Route::url('backend', array('controller' => 'scheduler')),
				'priority' => 900,
				'icon' => 'calendar',
				'permissions' => 'scheduler.index'
			)
		)
	)
);
