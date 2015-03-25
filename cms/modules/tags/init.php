<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

// При сохранении страницы обновление тегов
Observer::observe(array('page_add_after_save', 'page_edit_after_save'), function($page) {
	$tags = Request::current()->post('page_tags');
	
	if ($tags !== NULL)
	{
		Model_Page_Tag::save_by_page($page->id, $tags);
	}
});

// Загрузка шаблона с тегами в блок с метатегами в редактор страницы
Observer::observe('view_page_edit_meta', function($page) {
	echo View::factory('page/tags', array(
		'tags' => Model_Page_Tag::find_by_page($page->id)
	));
});

Observer::observe('layout_backend_head_before', function() {
	echo '<script type="text/javascript">var TAG_SEPARATOR = "' . Model_Tag::SEPARATOR . '";</script>';
});

// При выводе списка стран запускается метод custom_filter и передача в него 
// Database_query_builder, в этом обсервере можно дополнять этот запрос
Observer::observe( 'frontpage_custom_filter', function($sql, $page) {
	$tags = Context::instance()->get('tag');
		
	if (empty($tags))
	{
		return;
	}

	$sql->join(array(Model_Page_Tag::TABLE_NAME, 'pts'), 'inner')
		->distinct(TRUE)
		->on('pts.page_id', '=', 'page.id')
		->join(array(Model_Tag::TABLE_NAME, 'ts'))
		->on('pts.tag_id', '=', 'ts.id')
		->where('ts.name', 'in', explode(',', $tags));
});