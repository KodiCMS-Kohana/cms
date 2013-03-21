<?php defined('SYSPATH') or die('No direct access allowed.');

return array(

	'Documentation' => array(
		array(
			'name' => __('User Guide'), 
			'url' => URL::backend('guide/doc'),
			'priority' => 101,
			'icon' => 'book',
			'permissions' => array()
		),
		array(
			'name' => __('API Browser'), 
			'url' => URL::backend('guide/api'),
			'priority' => 102,
			'icon' => 'wrench',
			'permissions' => array()
		)
	)

);
