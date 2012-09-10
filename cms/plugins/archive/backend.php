<?php defined('SYSPATH') or die('No direct access allowed.');

Route::set( 'archive', ADMIN_DIR_NAME . '/plugin/archive/<id>' , array(
	'id' => '[0-9]+',
	'controller' => 'archive',
	'action' => 'index',
) )
	->defaults( array(
		'controller' => 'archive',
		'action' => 'index',
	) );

// Add behaviors
Behavior::add('archive', 'archive/archive.php');
Behavior::add('archive_day_index', 'archive/archive.php');
Behavior::add('archive_month_index', 'archive/archive.php');
Behavior::add('archive_year_index', 'archive/archive.php');

$behaviors = array('archive', 'archive_day_index', 'archive_month_index', 'archive_year_index');
$pages = DB::select()
	->from(Page::tableName())
	->where('behavior_id', 'in', $behaviors)
	->where('status_id', '=', Page::STATUS_PUBLISHED)
	->cache_key( 'archive_section' )
	->cached()
	->as_object()
	->execute();

foreach ($pages as $page) 
{
	Model_Navigation::add_section('Archive', $page->title, 'plugin/archive/'.$page->id, array());
}

Observer::observe(array(
	'page_delete', 'page_edit_after_save'
), function() {
	
	Core::cache('Database::cache(archive_section)', NULL, -1);
});