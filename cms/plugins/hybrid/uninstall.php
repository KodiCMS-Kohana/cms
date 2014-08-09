<?php defined('SYSPATH') or die('No direct access allowed.');

$datasources = Datasource_Data_Manager::get_all('hybrid');

foreach ($datasources as $id => $ds)
{
	$datasource = Datasource_Data_Manager::load($id);
	
	if($datasource->loaded())
	{
		DB::query(NULL, 'DROP TABLE IF EXISTS :table_:id')
			->parameters(array(
				':table' => DB::expr($datasource->table()),
				':id' => DB::expr($id)
			))
			->execute();
	}
}