<?php defined('SYSPATH') or die('No direct access allowed.');

if (IS_BACKEND)
{
	Route::set('datasources', ADMIN_DIR_NAME . '/<directory>(/<controller>(/<action>(/<id>)))', array(
		'directory' => '(datasources|' . implode('|', array_keys(Datasource_Data_Manager::types())) . ')'
	))
	->defaults(array(
		'directory' => 'datasources',
		'controller' => 'data',
		'action' => 'index',
	));
}

Observer::observe('modules::after_load', function() {

	if (!IS_BACKEND OR ! Auth::is_logged_in())
	{
		return;
	}

	$types = Datasource_Data_Manager::types();
	
	if (empty($types))
	{
		return;
	}

	try
	{
		$ds_section = Model_Navigation::get_section('Datasources');
		$ds_section->icon = 'tasks';
		$sections_list = Datasource_Data_Manager::get_tree(array_keys($types));
		
		$datasource_is_empty = empty($sections_list);
		$folders = Datasource_Folder::get_all();
		$root_sections = array();

		foreach ($sections_list as $type => $sections)
		{
			foreach ($sections as $id => $section)
			{
				if ($section->show_in_root_menu())
				{
					$root_sections[] = $section;
					unset($sections_list[$type][$id]);
					continue;
				}

				if (array_key_exists($section->folder_id(), $folders))
				{
					$folders[$section->folder_id()]['sections'][] = $section;
					unset($sections_list[$type][$id]);
				}
			}
		}
		
		if (!empty($root_sections))
		{
			foreach ($root_sections as $id => $section)
			{
				$section->add_to_menu();
			}
		}

		foreach ($folders as $folder_id => $folder)
		{
			if (empty($folder['sections']))
			{
				continue;
			}
	
			$folder_section = Model_Navigation::get_section($folder['name'], $ds_section);

			foreach ($folder['sections'] as $id => $section)
			{
				$section->add_to_menu($folder_section);
			}
		}

		foreach ($sections_list as $type => $sections)
		{
			foreach ($sections as $id => $section)
			{
				$section->add_to_menu($ds_section);
			}
		}

		$_create_section = Model_Navigation::get_section(__('Create section'), $ds_section, 999);

		foreach ($types as $id => $type)
		{
			$_create_section
				->add_page(new Model_Navigation_Page(array(
				'name' => $type,
				'url' => Route::get('datasources')->uri(array(
					'controller' => 'section',
					'directory' => 'datasources',
					'action' => 'create',
					'id' => $id
				)),
				'permissions' => $id.'.section.create'
			)));
		}
		
		unset($sections_list, $folders, $root_section);
	}
	catch (Exception $ex)
	{
		
	}
});

Observer::observe('update_search_index', function() {

	$ds_ids = Datasource_Data_Manager::get_all();

	foreach ($ds_ids as $ds_id => $data)
	{
		$ds = Datasource_Data_Manager::load($ds_id);

		if (!$ds->loaded())
		{
			continue;
		}

		$ds->update_index();
	}
});
