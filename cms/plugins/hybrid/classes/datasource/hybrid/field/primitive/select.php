<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Primitive_Select extends DataSource_Hybrid_Field_Primitive {
	
	protected $_props = array(
		'options' => array(),
		'custom_option' => FALSE,
		'empty_value' => TRUE,
	);
	
	public function set( array $data )
	{
		$data['custom_option'] = !empty($data['custom_option']) ? TRUE : FALSE;
		$data['empty_value'] = !empty($data['empty_value']) ? TRUE : FALSE;	
		
		return parent::set( $data );
	}
	
	public function set_options( $options )
	{
		if( !is_array( $options ) )
		{
			$options = preg_split('/\\r\\n|\\r|\\n/', $options);
		}
		
		if( ! empty($options) )
		{
			$options = array_unique(array_filter($options));
			$options = array_map('trim', $options);
			$options = array_combine($options, $options);
		}
		
		$this->options = (array) $options;
	}
	
	public function get_options()
	{
		if($this->empty_value === TRUE)
		{
			$this->options = array('--- Not set ---') + $this->options;
		}
		
		return $this->options;
	}
	
	/**
	 * 
	 * @param array $data
	 * @return DataSource_Hybrid_Field
	 */
	public function onReadDocumentValue(array $data, DataSource_Hybrid_Document $document)
	{
		if($this->custom_option === TRUE AND isset($data[$this->name . '_custom']) AND !empty($data[$this->name . '_custom']))
		{
			$option = $data[$this->name . '_custom'];
			$options = $this->options;
			$options[] = $option;
			$this->set_options($options);
			$this->update();
			
			$document->set($this->name, $option);
			
			return $this;
		}
		
		return parent::onReadDocumentValue($data, $document);
	}

	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new) 
	{
		$value = $new->get($this->name);

		if(array_key_exists($value, $this->options ) OR ($this->custom_option === TRUE AND !empty($value)))
		{
			$new->set($this->name, $this->options[$value]);
		}
		else if($value == 0 AND $this->empty_value === TRUE)
		{
			$new->set($this->name, '');
		}
		else
		{
			$new->set($this->name, $old->get($this->name));
		}
	}
	
	public function onValidateDocument( Validation $validation, DataSource_Hybrid_Document $doc )
	{
		if($this->custom_option === FALSE)
		{
			if($this->empty_value === TRUE)
			{
				$this->options = array(0) + $this->options;
			}

			$validation->rule($this->name, 'in_array', array(':value', $this->options));
		}
			
		return parent::onValidateDocument($validation, $doc);
	}
	
	public function get_type() 
	{
		return 'TEXT NOT NULL';
	}
}