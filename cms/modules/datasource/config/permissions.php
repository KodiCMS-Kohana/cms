<?php defined('SYSPATH') or die('No direct access allowed.');

$perms = array();

foreach (Datasource_Data_Manager::get_all() as $id => $section)
{
	$perms[$section['type'].$id] = array(
		'title' => __('Datasource :name', array(':name' => $section['name'])),
		array(
			'action' => 'section.view',
			'description' => 'View section'
		),
		array(
			'action' => 'section.edit',
			'description' => 'Edit section'
		),
		array(
			'action' => 'section.remove',
			'description' => 'Remove section'
		),
		array(
			'action' => 'document.view',
			'description' => 'View documents'
		),
		array(
			'action' => 'document.edit',
			'description' => 'Edit documents'
		),
		array(
			'action' => 'field.edit',
			'description' => 'Edit hybrid fields'
		),
		array(
			'action' => 'field.remove',
			'description' => 'Remove hybrid fields'
		),
	);
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