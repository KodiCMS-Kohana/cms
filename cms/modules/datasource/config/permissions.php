<?php defined('SYSPATH') or die('No direct access allowed.');

$perms = array();

foreach (Datasource_Data_Manager::get_all() as $id => $section)
{
	$actions = $section->acl_actions();
	$actions['title'] = __('Datasource :name', array(':name' => $section->name));
	$perms['ds_id.' . $id] = $actions;
}

foreach (Datasource_Data_Manager::types() as $type => $title)
{
	$perms[$type.'.section'] = array(
		'title' => 'Datasource',
		array(
			'action' => 'create',
			'description' => 'Create '.$type.' section'
		)
	);
}

return $perms;