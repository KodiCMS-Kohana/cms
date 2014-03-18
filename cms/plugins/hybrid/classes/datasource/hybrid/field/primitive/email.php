<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Primitive_Email extends DataSource_Hybrid_Field_Primitive {
	
	protected $_props = array(
		'default' => NULL
	);
	
//	public function fetch_value($doc) 
//	{
//		$doc->fields[$this->name] = HTML::mailto($doc->fields[$this->name]);
//	}
	
	public function document_validation_rules( Validation $validation, DataSource_Hybrid_Document $doc )
	{
		$validation->rule( $this->name, 'email' );
		return parent::document_validation_rules($validation, $doc);
	}
	
	public function get_type() 
	{
		return 'VARCHAR (50) NOT NULL';
	}
}