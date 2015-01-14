<?php defined('SYSPATH') or die('No direct script access.');

 if (IS_INSTALLED AND ACL::check('system.search.settings'))
{
	Observer::observe('view_setting_plugins', function() {
		echo View::factory('search/settings');
	});

	Observer::observe('save_settings', function($settings) {
		$full_text_search = Arr::path($settings, 'search.full_text_search');
		Config::set('search', 'native', array('full_text_search' => (bool) $full_text_search));
	});
}