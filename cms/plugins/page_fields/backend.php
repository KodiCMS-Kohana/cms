<?php defined('SYSPATH') or die('No direct access allowed.');

Observer::observe('view_page_edit_plugins', function($page) {
	echo View::factory('page/fields/edit', array(
		'page' => $page,
		'fields' => ORM::factory('page_field')->get_by_page_id($page->id),
		'pages' => Model_Page_Sitemap::get(TRUE)->exclude(array($page->id))->flatten(),
	));
});

Observer::observe('page_add_after_save', function($page) {
	$post_data = Request::current()->post('fields');
	if (!empty($post_data['from_page_id']))
	{
		ORM::factory('page_field')->copy($post_data['from_page_id'], $page->id);
	}
});

Observer::observe(array('controller_before_page_edit', 'controller_before_page_add'), function() {
	Assets::js('controller.page_fields', ADMIN_RESOURCES . 'js/controller/page_fields.js', 'global');
});