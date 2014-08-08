<?php defined('SYSPATH') or die('No direct access allowed.');

$datasources = Datasource_Data_Manager::get_all('hybrid');

foreach ($datasources as $id => $ds)
{
	$datasource = Datasource_Section::load($id);
	
	if($datasource->loaded())
	{
		DB::query(NULL, 'DROP TABLE :table_:id')
			->parameters(array(
				':table' => DB::expr($datasource->table()),
				':id' => DB::expr($id)
			))
			->execute();
	}
}