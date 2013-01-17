<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'Content' => array(
		array(
			'name' => __('Messages'), 
			'url' => URL::site('messages'),
			'permissions' => array('login'),
			'icon' => UI::icon('envelope'),
			'divider' => TRUE,
			'counter' => ORM::factory( 'message' )->count_new(AuthUser::getId()),
			'priority' => 105
		)
	)

);
