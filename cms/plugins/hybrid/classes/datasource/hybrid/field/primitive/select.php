<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Primitive_Select extends DataSource_Hybrid_Field_Primitive {
	
	protected $_props = array(
		'default' => NULL,
		'options' => array()
	);
	
	public function set_options($value)
	{
		if( !is_array($value) )
		{
			$value = preg_split('/\\r\\n|\\r|\\n/', $value);
			$value = array_unique(array_filter($value));
			$value = array_combine($value, $value);
		}
		
		$this->options = $value;
	}

	public function onUpdateDocument($old, $new) 
	{
		if( in_array($new->fields[$this->name], (array) $this->options ))
			$new->fields[$this->name] = $this->options[$new->fields[$this->name]];
		else if($new->fields[$this->name] == 0)
			$new->fields[$this->name] = '';
		else
			$new->fields[$this->name] = $old->fields[$this->name];
	}
	
	public function document_validation_rules( Validation $validation, DataSource_Hybrid_Document $doc )
	{
		$validation->rule($this->name, 'in_array', array(':value', array(0) + $this->options));
			
		return parent::document_validation_rules($validation, $doc);
	}
	
	public function get_type() 
	{
		return 'TEXT NOT NULL';
	}
}