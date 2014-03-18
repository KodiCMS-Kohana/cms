<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Source_Array extends DataSource_Hybrid_Field_Source_OneToMany {

	protected $_props = array(
		'isreq' => TRUE,
		'source' => NULL
	);
	
	public function __construct( array $data )
	{
		parent::__construct( $data );
		$this->family = DataSource_Hybrid_Field::FAMILY_SOURCE;
	}
	
	public function create() 
	{
		parent::create();
		
		if( ! $this->id)
		{
			return FALSE;
		}
		
		$ds = DataSource_Hybrid_Field_Utils::load_ds($this->from_ds);
		$ds->increase_lock();
		
		$this->update();
		
		return $this->id;
	}
	
	public function remove() 
	{
		$ds = DataSource_Hybrid_Field_Utils::load_ds($this->from_ds);
		$ds->decrease_lock();

		parent::remove();
	}
	
	public function onUpdateDocument($old, $new) 
	{
		$o = empty($old->fields[$this->name]) ? array() : explode(',', $old->fields[$this->name]);
		$n = empty($new->fields[$this->name]) ? array() : explode(',', $new->fields[$this->name]);
		$diff = array_diff($o, $n);
		
		if($this->one_to_many AND !empty($diff)) 
		{
			$ds = DataSource_Hybrid_Field_Utils::load_ds($this->ds_id);
			$ds->delete($diff);
		}
	}
	
	public function onRemoveDocument( $doc )
	{
		$ids = explode(',', $doc->fields[$this->name]);
		if($this->one_to_many AND !empty($ids)) 
		{
			$ds = DataSource_Hybrid_Field_Utils::load_ds($this->ds_id);
			$ds->delete($ids);
		}
	}
	
	public function fetch_value($doc) 
	{
		$ids = $doc->fields[$this->name] 
			? explode(',', $doc->fields[$this->name]) 
			: array();

		$doc->fields[$this->name] = DataSource_Hybrid_Field_Utils::get_document_headers($this->source, $this->from_ds, $ids);
	}
	
	public function convert_to_plain($doc) 
	{
		if(is_array($doc->fields[$this->name]))
		{
			$doc->fields[$this->name] = implode(', ', $doc->fields[$this->name]);
		}
	}
	
	public function is_valid($value) 
	{
		return strlen($value) == strspn($value, '0123456789,');
	}
	
	public function document_validation_rules( Validation $validation, DataSource_Hybrid_Document $doc )
	{
		return $validation
				->rule($this->name, array($this, 'is_valid'));
	}
	
	public function get_type()
	{
		return 'VARCHAR(255)';
	}
	
	/**
	 * @param Model_Widget_Hybrid
	 * @param array $field
	 * @param array $row
	 * @param string $fid
	 * @return mixed
	 */
	public static function fetch_widget_field( $widget, $field, $row, $fid, $recurse )
	{
		$related_widget = NULL;

		if($recurse > 0 AND isset($widget->doc_fetched_widgets[$fid]))
		{
			if(!empty($row[$fid]))
			{
				$related_widget = self::_fetch_related_widget($widget, $row, $fid, $recurse);
			}
		}

		return !empty($related_widget) 
			? $related_widget 
			: (!empty($row[$fid]) ? explode(',', $row[$fid]) : array());
	}
	
	public function fetch_headline_value( $value )
	{
		if( ! empty($value) )
		{
			$docs = explode(',', $value);
			foreach($docs as $i => $id)
			{
				$header = DataSource_Hybrid_Field_Utils::get_document_header($this->source, $this->from_ds, $id);

				$docs[$i] = HTML::anchor(Route::url('datasources', array(
					'controller' => 'document',
					'directory' => 'hybrid',
					'action' => 'view'
				)) . URL::query(array(
					'ds_id' => $this->from_ds, 'id' => $id
				)), $header, array('target' => 'blank'));
			}
			return implode(', ', $docs);
		}
		
		return parent::fetch_headline_value($value);
	}
}