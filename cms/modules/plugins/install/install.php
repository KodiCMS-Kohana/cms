<?php defined('SYSPATH') or die('No direct access allowed.');

$plugins = Arr::get($post, 'plugins', array());

if (!empty($post['insert_test_data']))
{
	$plugins['test'] = 'test';
}

Plugins::find_all();
foreach ($plugins as $name)
{
	$plugin = Plugins::get_registered($name);
	if ($plugin instanceof Plugin_Decorator AND $plugin->is_installable())
	{
		$plugin->activate();
	}
}