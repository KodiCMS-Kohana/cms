<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	array(
		'name' => 'Pages',
		'url' => Route::get('backend')->uri(array('controller' => 'page')),
		'permissions' => 'page.index',
		'priority' => 100,
		'icon' => 'sitemap'
	),
);
