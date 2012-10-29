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
		Route::set( 'archive', ADMIN_DIR_NAME . '/archive/<id>' , array(
			'id' => '[0-9]+',
			'controller' => 'archive',
			'action' => 'index',
		) )
			->defaults( array(
				'controller' => 'archive',
				'action' => 'index',
			) );
		
		$behaviors = array(
			'archive', 'archive_day_index', 'archive_month_index', 'archive_year_index'
		);

		$pages = DB::select()
			->from(Model_Page::tableName())
			->where('behavior_id', 'in', $behaviors)
			->where('status_id', '=', Model_Page::STATUS_PUBLISHED)
			->cache_key( 'archive_section' )
			->cached()
			->as_object()
			->execute();

		foreach ($pages as $page) 
		{
			Model_Navigation::add_section('Archive', $page->title, 'archive/'.$page->id, array());
		}

		Observer::observe(array('page_delete', 'page_edit_after_save'), 'clear_archive_section_cahe');
		
		function clear_archive_section_cahe() 
		{
			Kohana::cache('Database::cache(archive_section)', NULL, -1);
		}
	}
}