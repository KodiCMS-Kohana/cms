<?php defined('SYSPATH') or die('No direct access allowed.');

$types = Datasource_Data_Manager::types();

$menu = array();

foreach ($types as $id => $type)
{
	$menu[] = array(
		'name' => $type,
		'url' => Route::url('datasources', array(
			'controller' => 'section',
			'directory' => 'datasources',
			'action' => 'create',
			'id' => $id
		)),
		'permissions' => $id.'.section.create'
	);
}

return array(
	array(
		'name' => 'Datasources',
		'children' => array(
			array(
				'name' => 'Create',
				'icon' => 'plus',
				'children' => $menu
			)
		)
	)
);
