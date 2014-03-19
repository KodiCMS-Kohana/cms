<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Datasource
 */

abstract class DataSource_Hybrid_Field {
	
	const FAMILY_PRIMITIVE = 'primitive';
	const FAMILY_FILE = 'file';
	const FAMILY_SOURCE = 'source';

	const PREFFIX = 'f_';
	
	/**
	 *
	 * @var string
	 */
	public $ds_table = NULL;
	
	/**
	 *
	 * @var string
	 */
	public $table = 'dshfields';
	
	/**
	 *
	 * @var integer
	 */
	public $id = NULL;
	
	/**
	 *
	 * @var integer
	 */
	public $ds_id = NULL;
	
	/**
	 *
	 * @var integer
	 */
	public $from_ds = NULL;
	
	/**
	 *
	 * @var string
	 */
	public $family;
	
	/**
	 *
	 * @var string
	 */
	public $type;
	
	/**
	 *
	 * @var string
	 */
	public $name;

	/**
	 *
	 * @var string
	 */
	public $header;
	
	/**
	 *
	 * @var integer
	 */
	public $position;
	
	/**
	 *
	 * @var string 
	 */
	public $key = NULL;


	/**
	 *
	 * @var array
	 */
	protected $_props = array();
	
	/**
	 *
	 * @var boolean 
	 */
	protected $_use_as_document_id = FALSE;
	
	/**
	 *
	 * @var boolean 
	 */
	protected $_is_sortable = FALSE;
	
	/**
	 *
	 * @var array 
	 */
	protected $_widget_types = NULL;


	/**
	 * 
	 * @return array
	 */
	public static function types()
	{
		return Config::get('fields')->as_array();
	}

	/**
	 * 
	 * @param type $type
	 * @param array $data
	 * @return \DataSource_Hybrid_Field
	 * @throws Kohana_Exception
	 */
	public static function factory($type, array $data)
	{
		$class_name = 'DataSource_Hybrid_Field_' . $type;
		
		if(!class_exists( $class_name ))
		{
			throw new Kohana_Exception('Class for field - :type not found', array(
				':type' => $type));
		}
		
		return new $class_name($data);
	}

	/**
	 * 
	 * @param array $data
	 */
	public function __construct( array $data) 
	{
		$this->set($data);
		
		$this->type = strtolower(substr(get_called_class(), 24));
		$this->from_ds = (int) $this->from_ds;
		$this->key = str_replace( DataSource_Hybrid_Field::PREFFIX, '', $this->name);
	}
	
	/**
	 * 
	 * @return array
	 */
	public function rules()
	{
		return array(
			'name' => array(
				array('not_empty')
			),
			'header' => array(
				array('not_empty')
			),
//			'type' => array(
//				array('in_array', array(
//					':value', array_keys(DataSource_Hybrid_Field::types())
//				))
//			)
		);
	}
	
	/**
	 * 
	 * @param array $data
	 * @return boolean
	 * @throws Validation_Exception
	 */
	public function validate(array $data = NULL)
	{
		if($data === NULL)
		{
			$data = $this->as_array();
		}

		$array = Validation::factory($data);
		
		$rules = $this->rules();
		
		foreach ( $rules as $field => $r )
		{
			$array->rules($field, $r);
		}
		
		if($this->id === NULL)
		{
			$array->rule('name', 'DataSource_Hybrid_Field_Factory::field_not_exists', array(DataSource_Hybrid_Field_Factory::get_full_key($this->name), $this->ds_id));
		}
		
		if(!$array->check())
		{
			throw new Validation_Exception($array);
		}
		
		return TRUE;
	}
	
	/**
	 * 
	 * @param array $data
	 * @return \DataSource_Hybrid_Field
	 */
	public function set( array $data )
	{
		$data['isreq'] = ! empty($data['isreq']) ? TRUE : FALSE;
		$data['in_headline'] = ! empty($data['in_headline']) ? TRUE : FALSE;		

		foreach ( $data as $key => $value )
		{
			$method = "set_{$key}";
			if(method_exists($this, $method))
			{
				$this->$method($value);
			}
			else
			{
				$this->{$key} = $value;
			}
		}

		$this->validate();

		return $this;
	}

	/**
	 * 
	 * @param type $key
	 * @param type $value
	 */
	public function __set($key, $value)
	{
		switch ($key)
		{
			case 'isreq':
				$value = (bool) $value;
				break;
		}
		
		$this->_props[$key] = $value;
	}

	/**
	 * 
	 * @param type $key
	 * @return string|NULL
	 * @throws Kohana_Exception
	 */
	public function __get($key)
	{
		return Arr::get($this->_props, $key);
	}
	
	public function __isset( $key )
	{
		return isset($this->_props[$key]);
	}
	
	public function __unset( $key )
	{
		unset($this->_props[$key]);
	}
	
	/**
	 * 
	 * @return array
	 */
	public function widget_types()
	{
		return $this->_widget_types;
	}

	/**
	 * 
	 * @param array $data
	 * @param DataSource_Hybrid_Document $doc
	 * @return \DataSource_Hybrid_Field
	 */
	public function set_value(array $data, DataSource_Hybrid_Document $document)
	{
		$document->fields[$this->name] = Arr::get($data, $this->name);
		return $this;
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Document $old
	 * @param DataSource_Hybrid_Document $new
	 */
	public function set_old_value($old, $new)
	{
		$new->fields[$this->name] = is_string($old->fields[$this->name]) ? $old->fields[$this->name] : '';
	}

	/**
	 * 
	 * @param integer $ds_id
	 * @return \DataSource_Hybrid_Field
	 */
	public function set_ds($ds_id) 
	{
		$this->ds_id = (int) $ds_id;
		$this->ds_table = 'dshybrid_' . $this->ds_id;
		
		return $this;
	}
	
	/**
	 * 
	 * @param integer $id
	 * @return \DataSource_Hybrid_Field
	 */
	public function set_id($id) 
	{
		$this->id = (int) $id;
		
		return $this;
	}
	
	/**
	 * 
	 * @param integer $position
	 * @return \DataSource_Hybrid_Field
	 */
	public function set_position( $position ) 
	{
		$this->position = (int) $position;
		
		if($this->position < 0)
		{
			$this->position = 0;
		}

		return $this;
	}
	
	/**
	 * 
	 * @return integer
	 */
	public function create() 
	{
		$this->validate();

		$query = DB::insert($this->table)
			->columns(array(
				'ds_id', 
				'name', 
				'family', 
				'type', 
				'header',
				'from_ds',
				'props',
				'position'
			))
			->values(array(
				$this->ds_id, 
				$this->name, 
				$this->family,
				$this->type, 
				$this->header,
				$this->from_ds,
				serialize($this->_props),
				$this->position,
			))
			->execute();

		$this->id = $query[0];

		return $this->id;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function update() 
	{
		$this->validate();

		return DB::update($this->table)
			->set(array(
				'header' => $this->header,
				'name' => $this->name,
				'props' => serialize( $this->_props ),
				'position' => $this->position
			))
			->where('id', '=', $this->id)
			->execute();
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function remove()
	{
		DB::delete($this->table)
			->where('id', '=', $this->id)
			->execute();
			
		$this->id = NULL;
		
		return TRUE;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function as_array()
	{
		$data = get_object_vars($this);
		$data = Arr::merge($data, $this->_props);

		return $data;
	}

	/**
	 * 
	 * @return null|array
	 */
	public function get_sql($doc)
	{
		return array($this->name, $doc->fields[$this->name]);
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function use_as_document_id()
	{
		return (bool) $this->_use_as_document_id;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function is_sortable()
	{
		return (bool) $this->_is_sortable;
	}

	/**
	 * 
	 * @param Validation $validation
	 * @param DataSource_Hybrid_Document
	 * @return \Validation
	 */
	public function document_validation_rules( Validation $validation, DataSource_Hybrid_Document $doc )
	{
		if($this->isreq === TRUE)
		{
			$validation->rule($this->name, 'not_empty');
		}

		return $validation
				->label($this->name, $this->header);
	}

	/**
	 * 
	 * @param Database_Query $query
	 * @return \Database_Query
	 */
	public function get_query_props(Database_Query $query)
	{
		return $query;
	}
	
	/**
	 * 
	 * @param Database_Query $query
	 * @param string $condition
	 * @param string $value
	 * @return type
	 */
	public function filter_condition(Database_Query $query, $condition, $value)
	{
		return $query->where($this->name, $condition, $value);
	}
	
	/**
	 * 
	 * @param Database_Query $query
	 * @param dtring $dir
	 */
	public function sorting_condition(Database_Query $query, $dir)
	{
		return $query->order_by($this->name, $dir);
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Document $doc
	 */
	public function fetch_value( $doc ) 
	{
		FALSE ? $doc : NULL ;
	}
	
	/**
	 * 
	 * @param string $value
	 * @return string
	 */
	public function fetch_headline_value( $value )
	{
		return $value;
	}
	
	/**
	 * 
	 * @param string $template
	 * @param DataSource_Hybrid_Document $doc
	 * @return type
	 */
	public function backend_template( DataSource_Hybrid_Document $doc, $template = NULL )
	{
		if($template === NULL)
		{
			$template = 'datasource/hybrid/document/fields/' . $this->type;
		}
		
		return View::factory($template, array(
			'value' => $doc->fields[$this->name], 
			'field' => $this,
			'doc' => $doc
		));
	}

	/**************************************************************************
	 * EVENTS
	 **************************************************************************/	
	/**
	 * 
	 * @param DataSource_Hybrid_Document $doc
	 */
	public function onCreateDocument($doc) 
	{
		if(!isset($doc->fields[$this->name]))
		{
			$doc->fields[$this->name] = '';
		}
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Document $old
	 * @param DataSource_Hybrid_Document $new
	 */
	public function onUpdateDocument($old, $new) 
	{
		FALSE ? $old OR $new : NULL ;
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Document $doc
	 */
	public function onRemoveDocument($doc) 
	{
		FALSE ? $doc : NULL ;
	}
	
	/**
	 * return string
	 */
	abstract public function get_type();
}