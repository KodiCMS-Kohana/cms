<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Source_Document extends DataSource_Hybrid_Field_Source_OneToOne {

	protected $_props = array(
		'isreq' => TRUE,
		'source' => NULL,
		'one_to_one' => FALSE
	);
	
	public function __construct( array $data )
	{
		parent::__construct( $data );
		$this->family = DataSource_Hybrid_Field::FAMILY_SOURCE;
	}
	
	public function set( array $data )
	{
		$data['one_to_one'] = !empty($data['one_to_one']) ? TRUE : FALSE;
		return parent::set( $data );
	}
	
	public function create() 
	{
		parent::create();
		
		if( ! $this->id)
		{
			return FALSE;
		}

		$ds = DataSource_Hybrid_Field_Utils::load_ds($this->from_ds);		
		$this->update();
		
		return $this->id;
	}
	
	public function update() 
	{
		return DB::update($this->table)
			->set(array(
				'header' => $this->header,
				'props' => serialize($this->_props),
				'from_ds' => $this->from_ds
			))
			->where('id', '=', $this->id)
			->execute();
	}
	
	public function remove() 
	{
		$ds = DataSource_Hybrid_Field_Utils::load_ds($this->from_ds);
		$ds->decrease_lock();

		parent::remove();
	}
	
	public function onUpdateDocument($old, $new) 
	{
		if( $new->fields[$this->name] == -1 ) 
		{
			if($this->one_to_one) 
			{
				$ds = DataSource_Hybrid_Field_Utils::load_ds($this->ds_id);
				$ds->delete($old->fields[$this->name]);
			}

			$new->fields[$this->name] = NULL;
			return;
		}

//		$new->fields[$this->name] = $old->fields[$this->name];
	}
	
	public function onRemoveDocument( $doc )
	{
		if($this->one_to_one) 
		{
			$ds = DataSource_Hybrid_Field_Utils::load_ds($this->ds_id);
			$ds->delete($doc->fields[$this->name]);
		}
	}

	public function fetch_value( $doc ) 
	{
		$header = DataSource_Hybrid_Field_Utils::get_document_header($this->source, $this->from_ds, $doc->fields[$this->name]);
		$doc->fields[$this->name] = array(
			'id' => $header ? $doc->fields[$this->name] : NULL,
			'header' => $header
		);
	}
	
	public function get_type()
	{
		return 'INT(11) UNSIGNED';
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
			$related_widget = self::_fetch_related_widget($widget, $row, $fid, $recurse);
		}

		return ($related_widget !== NULL) 
			? $related_widget 
			: $row[$fid];
	}
	
	public function fetch_headline_value( $value )
	{
		if(empty($value)) return parent::fetch_headline_value($value);

		$header = DataSource_Hybrid_Field_Utils::get_document_header($this->source, $this->from_ds, $value);
		
		if(!empty($header))
		{
			return HTML::anchor(Route::url('datasources', array(
					'directory' => 'hybrid',
					'controller' => 'document',
					'action' => 'view'
				)) . URL::query(array('ds_id' => $this->from_ds, 'id' => $value), FALSE),
				$header,
				array(
					'class' => ' popup fancybox.iframe'
				)
			);
		}
		
		return parent::fetch_headline_value($value);
	}
	
	public function get_query_props(\Database_Query $query)
	{
		return $query->join(array('ds' . $this->source, 'dss' . $this->id), 'left')
			->on(DataSource_Hybrid_Field::PREFFIX . $this->key, '=', 'dss' . $this->id . '.id')
			->on('dss' . $this->id . '.published', '=', DB::expr( 1 ))
			->select(array('dss'.$this->id.'.header', $this->id . 'header'));
	}
}