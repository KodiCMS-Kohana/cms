<?php defined('SYSPATH') or die('No direct access allowed.');

$new = Api::get('messages.count_new', array(
	'uid' => AuthUser::getId()
))->as_object();

return array(
	'Content' => array(
		array(
			'name' => __('Messages'), 
			'url' => URL::site('messages'),
			'permissions' => array('login'),
			'icon' => UI::icon('envelope'),
			'divider' => TRUE,
			'counter' => $new->response,
			'priority' => 105
		)
	)

);
