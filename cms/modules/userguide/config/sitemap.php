<?php defined('SYSPATH') or die('No direct access allowed.');

return array(

	'Documentation' => array(
		array(
			'name' => __('User Guide'), 
			'url' => Route::url('backend', array('controller' => 'guide', 'action' => 'doc')),
			'priority' => 101,
			'icon' => 'book',
		),
		array(
			'name' => __('API Browser'), 
			'url' => Route::url('backend', array('controller' => 'guide', 'action' => 'api')),
			'priority' => 102,
			'icon' => 'beaker',
		)
	)

);
