<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Primitive_Text extends DataSource_Hybrid_Field_Primitive {
	
	protected $_props = array(
		'default' => NULL,
		'allow_html' => TRUE,
		'filter_html' => FALSE,
		'allowed_tags' => '<b><i><u><p><ul><li><ol>',
		'rows' => 3
	);
	
	public function set( array $data )
	{				
		$data['allow_html'] = !empty($data['allow_html']) ? TRUE : FALSE;
		$data['filter_html'] = !empty($data['filter_html']) ? TRUE : FALSE;
		
		return parent::set( $data );
	}
	
	public function set_rows( $rows )
	{				
		$this->rows = (int) $rows;
		
		if( $this->rows < 1 )
		{
			$this->rows = 1;
		}
	}
	
	public function onUpdateDocument($old, $new) 
	{
		if( $this->allow_html === FALSE )
		{
			$new->fields[$this->name] = strip_tags( $new->fields[$this->name] );
		}
		else if( $this->filter_html === TRUE )
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
		if(empty($value)) return '';

		return substr(strip_tags($value), 0, 500) . ' ...';
	}
}