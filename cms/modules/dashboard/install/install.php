<?php defined('SYSPATH') or die('No direct script access.');

$config = Kohana::$config->load('installer');
$data = $config->get('dashboard', array());

foreach ($data as $column => $widgets)
{
	foreach ($widgets as $type => $data)
	{
		try
		{
			$widget = Dashboard::add_widget($type, $data, 0);
			Dashboard::place_widget($widget->id, $column, 0);
		} 
		catch (Kohana_Exception $ex) 
		{
			continue;
		}
		
	}
}