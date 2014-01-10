<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Datasource
 */

class DataSource_Hybrid_Field_Document extends DataSource_Hybrid_Field {

	protected $_props = array(
		'isreq' => TRUE,
		'one_to_one' => FALSE
	);
	
	public function __construct( array $data )
	{
		parent::__construct( $data );
		
		$this->family = self::TYPE_DOCUMENT;
	}
	
	public function set( array $data )
	{
		if(!isset($data['isreq']))
		{
			$data['isreq'] = FALSE;
		}
		
		if(!isset($data['one_to_one']))
		{
			$data['one_to_one'] = FALSE;
		}

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
		$ds->increase_lock();
		
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

		$new->fields[$this->name] = $old->fields[$this->name];
	}
	
	public function onRemoveDocument( $doc )
	{
		if($this->one_to_one) 
		{
			$ds = DataSource_Hybrid_Field_Utils::load_ds($this->ds_id);
			$ds->delete($doc->fields[$this->name]);
		}
	}

	public function fetch_value($doc) 
	{
		$header = DataSource_Hybrid_Field_Utils::get_document_header($this->type, $this->from_ds, $doc->fields[$this->name]);
		$doc->fields[$this->name] = array(
			'id' => $header ? $doc->fields[$this->name] : NULL,
			'header' => $header
		);
	}
	
	public function convert_to_plain($doc) 
	{
		$doc->fields[$this->name] = Arr::path($doc->fields, $this->name . '.header');
	}
	
	public function get_type()
	{
		switch($this->type) 
		{
			case 'hybrid':
				return 'INT(11) UNSIGNED';
		}

		return NULL;
	}
	
	/**
	 * 
	 * @param array $row
	 * @param integr $fid
	 * @param integer $recurse
	 * @return array
	 */
	protected static function _fetch_related_widget( $widget, $row, $fid, $recurse)
	{
		$widget_id = $widget->doc_fetched_widgets[$fid];

		$widget = Context::instance()->get_widget($widget_id);
		if(!$widget)
		{
			$widget = Widget_Manager::load($widget_id);
		}
		
		if($widget === NULL) return array();

		$doc_ids = explode(',', $row[$fid]);

		$widget->ids = $doc_ids;
		$docs = $widget->get_documents( $recurse - 1);
		
		return $docs;
	}
	
	/**
	 * @param Model_Widget_Hybrid
	 * @param array $field
	 * @param array $row
	 * @param string $fid
	 * @return mixed
	 */
	public static function set_doc_field( $widget, $field, $row, $fid, $recurse )
	{
		$related_widget = NULL;

		if($recurse > 0 AND isset($widget->doc_fetched_widgets[$fid]))
		{
			$related_widget = self::_fetch_related_widget($widget, $row, $fid, $recurse);
		}

		return ($related_widget !== NULL) 
			? $related_widget 
			: array(
				'id' => $row[$fid], 
				'header' => $row[$fid . 'header']
			);
	}
}