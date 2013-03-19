<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

$plugin = Plugins_Item::factory( array(
	'id' => 'archive',
	'title' => 'Archive',
	'description' => 'Provides an Archive pagetype behaving similar to a blog or news archive.',
	'version' => '1.0.0',
) )->register();

if($plugin->enabled())
{
	if(IS_BACKEND)
	{
		$behaviors = array();
		foreach (Kohana::$config->load('behaviors') as $key => $behavior)
		{
			if(isset($behavior['link']))
			{
				$behaviors[] = $key;
			}
		}
		
		if(empty($behaviors))
		{
			return;
		}

		$pages = DB::select()
			->from(Model_Page::tableName())
			->where('behavior_id', 'in', $behaviors)
			->cache_key( 'archive_section' )
			->cached()
			->as_object()
			->execute();

		foreach ($pages as $page) 
		{
			Model_Navigation::get_section('Archive')
				->add_page(new Model_Navigation_Page(array(
					'name' => $page->title, 
					'url' => URL::backend('archive/'.$page->id),
					'permissions' => array(),
				)), 999);
		}

		Observer::observe(array('page_delete', 'page_edit_after_save'), 'clear_archive_section_cahe');
		
		function clear_archive_section_cahe() 
		{
			Cache::instance()->delete('Database::cache(archive_section)');
		}
	}
}

if ( ! Route::cache())
{
	Route::set( 'archive', ADMIN_DIR_NAME . '/archive/<id>' , array(
		'id' => '[0-9]+',
		'controller' => 'archive',
		'action' => 'index',
	) )
		->defaults( array(
			'controller' => 'archive',
			'action' => 'index',
		) );
}