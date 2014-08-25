<?php defined('SYSPATH') or die('No direct access allowed.');

$datasources = Datasource_Data_Manager::get_all('hybrid');

foreach ($datasources as $id => $ds)
{
	$datasource = Datasource_Data_Manager::load($id);
	if($datasource->loaded())
	{
		$datasource->remove();
	}
}