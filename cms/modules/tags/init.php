<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

Observer::observe( array('page_add_after_save', 'page_edit_after_save'), function($page) {
	$tags = Request::current()->post('page_tags');
	Model_Page_Tag::save_by_page( $page->id, $tags );
});

Observer::observe( 'view_page_edit_meta', function($page) {
	echo View::factory('page/tags', array(
		'tags' => Model_Page_Tag::find_by_page($page->id)
	));
});

Observer::observe( 'frontpage_custom_filter', function($sql, $page) {
	$tags = Context::instance()->get('tag');
		
	if(empty($tags)) return;

	$sql->join(array(Model_Page_Tag::TABLE_NAME, 'pts'), 'inner')
		->distinct(TRUE)
		->on('pts.page_id', '=', 'page.id')
		->join(array(Model_Tag::TABLE_NAME, 'ts'))
		->on('pts.tag_id', '=', 'ts.id')
		->where('ts.name', 'in', explode(',', $tags));
});