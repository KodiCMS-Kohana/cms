<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Primitive_Date extends DataSource_Hybrid_Field_Primitive {
	
	protected $_props = array(
		'default' => NULL
	);
	
	protected $_format = 'Y-m-d';


	public function onUpdateDocument($old, $new) 
	{
		$new->fields[$this->name] = $this->format_date($new->fields[$this->name]); 
	}
	
	public function fetch_value($doc) 
	{
		$doc->fields[$this->name] = $this->format_date($doc->fields[$this->name]);
	}
	
	public function format_date( $value ) 
	{
		$time = strtotime( ! empty($value) ? $value : 'now' );
		
		return $time > 0 
			? date($this->_format, $time) 
			: $value;
	}
	
	public function document_validation_rules( Validation $validation, DataSource_Hybrid_Document $doc )
	{
		$validation->rule( $this->name, 'date' );
			
		return parent::document_validation_rules($validation, $doc);
	}
	
	public function get_type() 
	{
		return 'DATE NOT NULL';
	}
	
	public function fetch_headline_value( $value )
	{
		if(!empty($value))
		{
			return Date::format($value);
		}
		return parent::fetch_headline_value($value);
	}
}