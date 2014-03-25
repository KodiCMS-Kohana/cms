<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Primitive_Date extends DataSource_Hybrid_Field_Primitive {
	
	protected $_props = array(
		'default' => NULL
	);
	
	protected $_format = 'Y-m-d';
	
	public function set( array $data )
	{
		$data['set_current'] = !empty($data['set_current']) ? TRUE : FALSE;
		
		return parent::set( $data );
		
		if($this->set_current === TRUE)
		{
			$this->default = date($this->_format);
		}
	}
	
	public function onSetDocumentValue( $value, DataSource_Hybrid_Document $doc)
	{
		if( ! $doc->loaded() AND $this->set_current === TRUE )
		{
			return date($this->_format);
		}
		
		return parent::onSetDocumentValue($value, $doc);
	}

	public function onUpdateDocument( DataSource_Hybrid_Document $old_document, DataSource_Hybrid_Document $document ) 
	{
		$document->set($this->name, $this->format_date($document->get($this->name))); 
	}
	
	public function format_date( $value ) 
	{
		$time = strtotime( ! empty($value) ? $value : 'now' );
		
		return $time > 0 
			? date($this->_format, $time) 
			: $value;
	}
	
	public function onValidateDocument( Validation $validation, DataSource_Hybrid_Document $doc )
	{
		$validation->rule( $this->name, 'date' );
			
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