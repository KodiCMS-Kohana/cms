<?php defined('SYSPATH') or die('No direct access allowed.');

$plugin = Plugin::factory('maintenance', array(
	'title' => 'Maintenance mode',
))->register();

Observer::observe('save_settings', function($post, $plugin) {
	$post = Request::current()->post();

	if(!isset($post['plugin']['maintenance_mode']))
	{
		$plugin->set('maintenance_mode', Config::NO);
	}
	else
	{
		$plugin->set('maintenance_mode', $post['plugin']['maintenance_mode']);
	}
	
	$plugin->save_settings();
}, $plugin);