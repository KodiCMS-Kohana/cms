<?php defined('SYSPATH') or die('No direct access allowed.');

Observer::observe( array('page_add_after_save', 'page_edit_after_save'), function($page) {
	if( !empty($page->behavior_id) )
	{
		$data = Request::current()->post('behavior');
		ORM::factory('Page_Behavior_Setting')
			->find_by_page($page)
			->set_page($page)
			->set('data', $data)
			->save();
	}
	else
	{
		$model = ORM::factory('Page_Behavior_Setting')
			->find_by_page($page);
		
		if( $model->loaded() )
		{
			$model->delete();
		}
	}
});

// Init behavior
Observer::observe('modules::afer_load', function() {
	Behavior::init();
});