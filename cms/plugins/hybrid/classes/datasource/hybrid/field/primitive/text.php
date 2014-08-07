<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Primitive_Text extends DataSource_Hybrid_Field_Primitive {
	
	protected $_is_indexable = FALSE;

	protected $_props = array(
		'default' => NULL,
		'allow_html' => TRUE,
		'filter_html' => FALSE,
		'allowed_tags' => '<b><i><u><p><ul><li><ol>',
		'rows' => 3
	);
	
	public function booleans()
	{
		return array('allow_html', 'filter_html');
	}
	
	public function set( array $data )
	{
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
	
	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new) 
	{
		if( $this->allow_html === FALSE )
		{
			$new->set($this->name, strip_tags( $new->get($this->name)));
		}
		else if( $this->filter_html === TRUE )
		{
			$new->set($this->name, Kses::filter( $new->get($this->name), $this->allowed_tags ));
		}
	}
	
	public function get_type() 
	{
		return 'TEXT NOT NULL';
	}
	
	public function fetch_headline_value( $value, $document_id )
	{
		if(empty($value)) return '';

		return substr(strip_tags($value), 0, 500) . ' ...';
	}
}