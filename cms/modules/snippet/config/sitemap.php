<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'Design' => array(
		array(
			'name' => __('Snippets'), 
			'url' => URL::backend('snippet'),
			'permissions' => array('administrator', 'developer'),
			'priority' => 200
		)
	)
);
