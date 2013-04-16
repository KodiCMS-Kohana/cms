<?php defined('SYSPATH') or die('No direct access allowed.');

//Вставка JS и Стилей в шаблон
Observer::observe( 'frontpage_found',  function($page) {
	
	$layout = $page->get_layout_object();
	$widgets = Widget_Manager::get_widgets_by_page($page->id);
	
	foreach($layout->blocks() as $block)
	{
		if( ! $page->has_content($block))
		{
			continue;
		}

		$widgets[] = $page->get_part($block);
	}
	
	Context::instance()->register_widgets($widgets);
	
	Observer::notify('load_blocks');
});

Observer::observe('view_page_edit_plugins', function($page) {
	echo View::factory('widgets/page/edit', array(
		'page' => $page,
		'pages' => Model_Page_Sitemap::get()->exclude(array($page->id))->flatten(),
		'widgets' => Widget_Manager::get_widgets_by_page( $page->id ),
		'blocks' => ORM::factory( 'layout_block')->find_by_layout($page->layout())
	));
});

Observer::observe('page_add_after_save', function($page) {
	$post_data = Request::current()->post('widgets');
	
	if(!empty($post_data['from_page_id']))
	{
		Widget_Manager::copy($post_data['from_page_id'], $page->id);
	}
});