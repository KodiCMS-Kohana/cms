<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	array(
		'name' => 'Content',
		'children' => array(
			array(
				'name' => 'Pages',
				'url' => Route::url('backend', array('controller' => 'page')),
				'permissions' => 'page.index',
				'priority' => 100,
				'icon' => 'sitemap'
			)
		)
	),
);
