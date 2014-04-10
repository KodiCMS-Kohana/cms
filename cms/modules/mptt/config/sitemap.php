<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	array(
		'name' => 'Content',
		'children' => array(
			array(
				'name' => 'Categories',
				'url' => URL::backend('categories'),
				'permissions' => 'categories.index',
				'priority' => 100,
				'icon' => 'sort-by-attributes'
			)
		)
	),
);
