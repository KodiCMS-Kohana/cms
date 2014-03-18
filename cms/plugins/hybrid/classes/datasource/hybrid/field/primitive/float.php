<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Primitive_Float extends DataSource_Hybrid_Field_Primitive {
	
	protected $_props = array(
		'default' => 0,
		'min' => 0, 
		'max' => 0,
		'length' => 10,
	);

	public function set_default($value)
	{
		$this->default = (float) $value;
		
		if($this->default < 0)
		{
			$this->default = 0;
		}
		
		if( ! empty($this->min) AND $this->default < $this->min )
		{
			$this->default = $this->min;
		}
		else if( ! empty($this->max) AND $this->default > $this->max )
		{
			$this->default = $this->max;
		}
	}
	
	public function set_min($value)
	{
		$this->min = (float) $value;
	}
	
	public function set_max($value)
	{
		$this->max = (float) $value;
	}
	
	public function onUpdateDocument($old, $new) 
	{
		$new->fields[$this->name] = (float) $new->fields[$this->name];
	}
	
	public function document_validation_rules( Validation $validation, DataSource_Hybrid_Document $doc )
	{
		$validation->rule($this->name, 'numeric');

		return parent::document_validation_rules($validation, $doc);
	}
	
	public function get_type() 
	{
		if($this->length < 1 OR $this->length > 11)
		{
			$this->length = 10;
		}

		return 'FLOAT NOT NULL';
	}
}