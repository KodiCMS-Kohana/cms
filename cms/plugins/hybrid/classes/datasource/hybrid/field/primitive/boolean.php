<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Primitive_Boolean extends DataSource_Hybrid_Field_Primitive {
	
	protected $_is_required = FALSE;
	
	public function onUpdateDocument( DataSource_Hybrid_Document $document ) 
	{
		$document->set($this->name, $document->get($this->name) ? 1 : 0);
	}
	
	public function get_type() 
	{
		return 'TINYINT(1) UNSIGNED NOT NULL';
	}

	public static function fetch_widget_field( $widget, $field, $row, $fid, $recurse )
	{
		return (bool) $row[$fid];
	}
	
	public function fetch_headline_value( $value )
	{
		return $value == 1 ? __('TRUE') : __('FALSE');
	}
}