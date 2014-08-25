<?php defined('SYSPATH') or die('No direct access allowed.');

if(Auth::is_logged_in())
{
	$new = Api::get('user-messages.count_new', array(
		'uid' => Auth::get_id()
	))->as_object();

	return array(
		array(
			'name' => 'Content',
			'children' => array(
				array(
					'name' => __('Messages'), 
					'url' => Route::get('backend')->uri(array('controller' => 'messages')),
					'permissions' => 'messages.index',
					'icon' => 'envelope-o',
					'divider' => TRUE,
					'counter' => (int) $new->response,
					'priority' => 105
				)
			)
		)
	);
}
else
{
	return array();
}