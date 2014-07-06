<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Primitive_Date extends DataSource_Hybrid_Field_Primitive {
	
	protected $_props = array(
		'default' => NULL
	);
	
	protected $_format = 'Y-m-d';
	
	public function booleans()
	{
		return array('set_current');
	}
	
	public function set( array $data )
	{
		return parent::set( $data );
		
		if($this->set_current === TRUE)
		{
			$this->default = date($this->_format);
		}
	}
	
	public function onSetValue( $value, DataSource_Hybrid_Document $doc)
	{
		if( ! $doc->loaded() AND $this->set_current === TRUE )
		{
			return date($this->_format);
		}
		
		return parent::onSetValue($value, $doc);
	}

	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new)
	{
		$new->set($this->name, $this->format_date($new->get($this->name))); 
	}
	
	public function convert_value( $value ) 
	{
		return $this->format_date($value);
	}
	
	public function format_date( $value ) 
	{
		if ( ! empty($value))
		{
			$time = (int) strtotime($value);
		}
		else
		{
			$time = 0;
		}
		
		return $time > 0 
			? date($this->_format, $time) 
			: NULL;
	}
	
	public function onValidateDocument( Validation $validation, DataSource_Hybrid_Document $doc )
	{
		$validation->rule($this->name, 'date');
			
		return parent::onValidateDocument($validation, $doc);
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