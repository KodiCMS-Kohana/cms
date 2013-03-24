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