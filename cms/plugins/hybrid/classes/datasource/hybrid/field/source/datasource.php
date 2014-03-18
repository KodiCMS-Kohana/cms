<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Datasource
 */
class DataSource_Hybrid_Field_Source_Datasource extends DataSource_Hybrid_Field {

	public $from_ds = NULL;
	
	protected $_props = array(
		'isreq' => TRUE
	);
	
	public function __construct( array $data )
	{
		parent::__construct( $data );
		
		$this->family = DataSource_Hybrid_Field::FAMILY_SOURCE;
		$this->from_ds = (int) $this->from_ds;
	}
	
	public function create() 
	{
		if(parent::create())
		{
			$this->update();
		}

		$ds = DataSource_Hybrid_Field_Utils::load_ds($this->from_ds);
		$ds->increase_lock();
		
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
	
	public function convert_to_plain($doc) 
	{
		$doc->fields[$this->name] = NULL;
	}
	
	public function get_type()
	{
		return 'INT(11) UNSIGNED';
	}
	
	public function get_query_props(Database_Query $query )
	{
		return $query->join(array('datasources', 'dss' . $fid), 'left')
			->on(DataSource_Hybrid_Field::PREFFIX . $field['name'], '=', 'dss' . $fid . '.ds_id')
			->select(array('dss'.$fid.'.docs', $fid . 'docs'));
	}
}