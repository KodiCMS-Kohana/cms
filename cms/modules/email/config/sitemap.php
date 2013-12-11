<?php defined('SYSPATH') or die('No direct access allowed.');

return array(

	'System' => array(
		array(
			'name' => __('Email templates'),
			'url' => Route::url('email_controllers', array('controller' => 'templates')),
			'permissions' => 'email_templates.index',
			'priority' => 400,
			'icon' => 'envelope',
			'divider' => TRUE,
		),
		array(
			'name' => __('Email types'),
			'url' => Route::url('email_controllers', array('controller' => 'types')),
			'permissions' => 'email_types.index',
			'priority' => 410,
			'icon' => 'cog',
		)
	),

);
