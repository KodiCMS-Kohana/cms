<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'Design' => array(
		array(
			'name' => __('Snippets'), 
			'url' => Route::url('backend', array('controller' => 'snippet')),
			'permissions' => 'snippet.index',
			'priority' => 200,
			'icon' => 'food'
		)
	)
);
