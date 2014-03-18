<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Primitive_HTML extends DataSource_Hybrid_Field_Primitive {
	
	protected $_props = array(
		'default' => NULL,
		'filter_html' => FALSE,
		'allowed_tags' => '<b><i><u><p><ul><li><ol>'
	);
	
	public function set( array $data )
	{
		$data['filter_html'] = !empty($data['filter_html']) ? TRUE : FALSE;				
		return parent::set( $data );
	}
	
	public function onUpdateDocument($old, $new) 
	{
		if( $this->filter_html === TRUE )
		{
			$new->fields[$this->name] = Kses::filter( $new->fields[$this->name], $this->allowed_tags );
		}
	}
	
	public function get_type() 
	{
		return 'TEXT NOT NULL';
	}
	
	public function fetch_headline_value( $value )
	{
		return substr(strip_tags($value), 0, 500) . ' ...';
	}
}