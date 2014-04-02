<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Yandex_Map extends DataSource_Hybrid_Field_Primitive {
	
	protected $_is_required = FALSE;
	
	protected $_props = array(
		'default' => '55.753994, 37.622093'
	);

	public function convert_value( $value )
	{
		Assets::js('Yandex.map', 'http://api-maps.yandex.ru/2.0/?load=package.full&lang=' . I18n::lang());
		
		return explode(',', $value);
	}
	
	public function onCreateDocument( DataSource_Hybrid_Document $doc) 
	{
		$this->onUpdateDocument($doc, $doc);
	}
	
	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new)
	{
		$value = $new->get($this->name);
		if(is_array($value))
		{
			if(!empty($value[0]) AND !empty($value[1]))
				$value = implode(',', $value);
			else
				$value = '';
		}
		
		$new->set($this->name, $value);
	}

	public function get_type() 
	{
		return 'VARCHAR (50)';
	}
	
	public function coord_x( $value )
	{
		if( ! is_array($value) AND strpos($value, ',') !== FALSE)
		{
			$value = explode(',', $value, 2);
		}
		
		$coord = NULL;
		
		if(is_array($value))
		{
			$coord = Arr::get($value, 0);
		}
	
		return $coord;
	}
	
	public function coord_y( $value )
	{
		if( ! is_array($value) AND strpos($value, ',') !== FALSE)
		{
			$value = explode(',', $value, 2);
		}
		
		$coord = NULL;
		
		if(is_array($value))
		{
			$coord = Arr::get($value, 1);
		}

		return $coord;
	}
}