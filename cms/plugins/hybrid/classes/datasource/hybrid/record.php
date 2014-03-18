<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Datasource
 */

class DataSource_Hybrid_Record {
	
	/**
	 *
	 * @var Datasource_Section
	 */
	public $ds;
	
	/**
	 *
	 * @var integer
	 */
	public $ds_id;
	
	/**
	 *
	 * @var array
	 */
	protected $_fields = NULL;
	
	/**
	 * 
	 * @param Datasource_Document $ds
	 */
	public function __construct( Datasource_Section $ds)
	{
		$this->ds = $ds;
		$this->ds_id = (int) $ds->id();

		$this->load();
	}

	/**
	 * 
	 * @return \DataSource_Hybrid_Record
	 */
	public function load() 
	{
		$this->_fields = array();
		
		$ids = DB::select('id')
			->from('dshfields')
			->where('ds_id', '=', $this->ds_id)
			->order_by('position', 'asc')
			->execute()
			->as_array(NULL, 'id');
		
		if( count( $ids ) > 0)
		{
			$fields = DataSource_Hybrid_Field_Factory::get_fields($ids);
			
			foreach ($fields as $field)
			{
				$this->_fields[$field->name] = $field;
			}
		}
		
		return $this;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function fields()
	{
		if($this->_fields === NULL)
		{
			$this->load();
		}

		return $this->_fields;
	}

	/**
	 * 
	 * @return \DataSource_Hybrid_Record
	 */
	public function destroy() 
	{
		DataSource_Hybrid_Field_Factory::remove_fields($this, array_keys($this->fields()));
		
		return $this;
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Document $doc
	 * @return \DataSource_Hybrid_Record
	 */
	public function initialize_document($doc) 
	{
		foreach($this->fields() as $field)
		{
			$field->onCreateDocument($doc);
		}
		
		return $this;
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Document $old
	 * @param DataSource_Hybrid_Document $new
	 * 
	 * @return \DataSource_Hybrid_Record
	 */
	public function document_changed($old, $new) 
	{
		foreach($this->fields() as $field)
		{
			$field->onUpdateDocument($old, $new);
		}
		
		return $this;
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Document $doc
	 * @return boolean
	 */
	public function destroy_document($doc) 
	{
		if($doc->ds_id != $this->ds_id)
		{
			return FALSE;
		}
		
		foreach($this->fields() as $field)
		{
			$field->onRemoveDocument($doc);
		}

		return TRUE;
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Document $doc
	 * @return array
	 */
	public function get_sql($doc, $update = FALSE) 
	{
		$queries = array();

		foreach($this->fields() as $field)
		{
			if($part = $field->get_sql($doc))
			{
				$queries[$field->ds_table][$part[0]] = $part[1];
			}
		}
		
		$date_field = $update !== FALSE ? 'updated_on' : 'created_on';
		
		$updates = array(
			(string) DB::update('dshybrid')
				->set(array(
					'published' => $doc->published ? 1 : 0,
					'header' => $doc->header,
					$date_field => date('Y-m-d H:i:s')
				))
				->where('id', '=', $doc->id));

		foreach($queries as $table => $update)
		{
			$updates[] = (string) DB::update ( $table )
				->set($update)
				->where('id', '=', $doc->id);
		}

		return $updates;
	}
}