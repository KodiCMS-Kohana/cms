<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'System' => array(
		array(
			'name' => __('Email'), 
			'url' => Route::url('backend', array('controller' => 'email', 'action' => 'settings')),
			'permissions' => 'email.settings',
			'priority' => 200,
			'icon' => 'envelope',
		)
	),
);
