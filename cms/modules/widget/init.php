<?php defined('SYSPATH') or die('No direct access allowed.');

Observer::observe( 'template_before_render',  function($request) {
	
	if(in_array( $request->controller(), array('Page', 'Widgets') ))
	{
		Assets::js('controller.widgets', ADMIN_RESOURCES . 'js/controller/widgets.js', 'global');
	}
});


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

	/**
	 * Запуск метода в виджетах текущей страницы 
	 * Model_Widget_Decorator::on_page_load
	 */
	Observer::notify('on_page_load');

	/**
	 * Блок служит для помещения в него виджета с произволным PHP кодом,
	 * который выполняется до загрузки HTML, вывод данных в этом блоке делать
	 * не надо
	 */
	Block::run('PRE');
});

Observer::observe( 'frontpage_after_render',  function() {

	/**
	 * Запуск метода в виджетах текущей страницы 
	 * Model_Widget_Decorator::after_page_load
	 */
	Observer::notify('after_page_load');

	/**
	 * Блок служит для помещения в него виджета с произволным PHP кодом,
	 * который выполняется после загрузки HTML
	 */
	Block::run('POST');
});

Observer::observe('view_page_edit_plugins', function($page) {

	$blocks = array(0 => __('--Remove from page--'), 'PRE' => __('Before page render'));
	$blocks += ORM::factory( 'layout_block')->find_by_layout($page->layout());
	$blocks += array('POST' => __('After page render'));
	
	echo View::factory('widgets/page/edit', array(
		'page' => $page,
		'pages' => Model_Page_Sitemap::get()->exclude(array($page->id))->flatten(),
		'widgets' => Widget_Manager::get_widgets_by_page( $page->id ),
		'blocks' => $blocks
	));
});

Observer::observe('page_add_after_save', function($page) {
	$post_data = Request::current()->post('widgets');
	
	if(!empty($post_data['from_page_id']))
	{
		Widget_Manager::copy($post_data['from_page_id'], $page->id);
	}
});

Observer::observe('page_edit_after_save', function($page) {
	$post_data = Request::current()->post('widget');
	
	if( is_array($post_data) )
	{
		foreach($post_data as $widget_id => $block)
		{
			if(!empty($block['block']))
			{
				DB::update('page_widgets')
					->where('widget_id', '=',$widget_id)
					->where('page_id', '=', $page->id)
					->set( array('block' => $block) )
					->execute();
			}
			else 
			{
				DB::delete('page_widgets')
					->where('widget_id', '=',$widget_id)
					->where('page_id', '=', $page->id)
					->execute();
			}
		}
	}
});