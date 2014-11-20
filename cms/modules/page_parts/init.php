<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

Observer::observe('view_page_edit_plugins_top', function($page) {
	echo View::factory('part/items');
});

Observer::observe('controller_before_page_edit', function() {
	Assets::package('jquery-ui');
});

// Если страницы загружена, загружаем части страниц в качестве виджетов и помещаем 
// в блоки с названием частей страниц
Observer::observe('frontpage_found', function($page) {
	$layout = $page->get_layout_object();

	$widgets = array();

	foreach ($layout->blocks() as $block)
	{
		if (!Part::exists($page, $block))
		{
			continue;
		}

		$widgets['part_' . $block] = new Model_Widget_Part($block, Part::get($page, $block));
	}

	Context::instance()->register_widgets($widgets);
});

// Загрузка JS кода на страницы редактирования
Observer::observe(array('controller_before_page_edit', 'controller_before_page_add'), function() {
	Assets::js('controller.parts', ADMIN_RESOURCES . 'js/controller/parts.js', 'global');
});

// Сохранение контента частей страниц
Observer::observe('page_edit_after_save', function($page) {
	$parts = Arr::get(Request::initial()->post(), 'part_content', array());

	$indexable_content = '';

	foreach ($parts as $id => $content)
	{
		$part = ORM::factory('page_part', (int) $id);

		if ((bool) $part->is_indexable)
		{
			$indexable_content .= ' ' . $part->content;
		}

		if ($content == $part->content)
		{
			continue;
		}

		$part
			->values(array('content' => $content))
			->save();
	}

	if (in_array($page->status_id, Model_Page_Front::get_statuses()))
	{
		Search::instance()->add_to_index('pages', $page->id, $page->title, $indexable_content, '', array(
			'uri' => $page->get_uri()
		));
	}
	else
	{
		Search::instance()->remove_from_index('pages', $page->id);
	}
});

Observer::observe('update_search_index', function() {

	$pages = ORM::factory('page')->find_all();

	foreach ($pages as $page)
	{
		$indexable_content = '';

		$parts = ORM::factory('page_part')
			->where('page_id', '=', $page->id)
			->where('is_indexable', '=', 1)
			->find_all();

		foreach ($parts as $part)
		{
			$indexable_content .= ' ' . $part->content;
		}

		Search::instance()->add_to_index('pages', $page->id, $page->title, $indexable_content, '', array(
			'uri' => $page->get_uri()
		));
	}
});

// Чистка кеша частей страниц при редактирвании или удалении страницы
Observer::observe(array('page_add_after_save', 'page_edit_after_save', 'page_delete'), function($page) {
	Cache::instance()->delete_tag('page_parts');
});
