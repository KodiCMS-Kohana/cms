<?php defined('SYSPATH') or die('No direct access allowed.');

if(AuthUser::isLoggedIn())
{
	$new = Api::get('user-messages.count_new', array(
		'uid' => AuthUser::getId()
	))->as_object();

	return array(
		'Content' => array(
			array(
				'name' => __('Messages'), 
				'url' => Route::url('backend', array('controller' => 'messages')),
				'permissions' => 'messages.index',
				'icon' => 'envelope',
				'divider' => TRUE,
				'counter' => (int) $new->response,
				'priority' => 105
			)
		)

	);
}
else
{
	return array();
}