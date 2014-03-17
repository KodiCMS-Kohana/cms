<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Datasource
 */
abstract class Datasource_Section {
	
	/**
	 * 
	 * @param string $type
	 * @return \Datasource_Section
	 */
	public static function factory($type)
	{
		$class = 'Datasource_Section_' . ucfirst($type);
		
		if( ! class_exists($class))
		{
			throw new DataSource_Exception('Class :class_name not exists', 
					array(':class_name' => $class));
		}
		
		return new $class($type);
	}

	/**
	 *
	 * @var integer
	 */
	protected $_id;
	
	/**
	 *
	 * @var string
	 */
	protected $_type;
	
	/**
	 *
	 * @var string
	 */
	public $name;
	
	/**
	 *
	 * @var string
	 */
	public $description;
	
	/**
	 *
	 * @var integer
	 */
	protected $_docs = 0;
	
	/**
	 *
	 * @var integer
	 */
	protected $_size = 0;
	
	/**
	 *
	 * @var string
	 */
	protected $_ds_table;
	
	/**
	 *
	 * @var boolean
	 */
	protected $_is_indexable = FALSE;
	
	/**
	 *
	 * @var integer
	 */
	protected $_lock;
	
	/**
	 * 
	 * @param string $type
	 */
	public function __construct( $type ) 
	{
		$this->_type = $type;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function type()
	{
		return $this->_type;
	}
	
	/**
	 * 
	 * @return integer
	 */
	public function id()
	{
		return $this->_id;
	}
	
	/**
	 * 
	 * @return integer
	 */
	public function lock()
	{
		return $this->_lock;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function loaded()
	{
		return $this->_id !== NULL;
	}

	/**
	 * @return array Fields
	 */
	public function fields()
	{
		return array(
			'id' => array(
				'name' => 'ID',
				'width' => 50
			),
			'header' => array(
				'name' => 'Header',
				'width' => NULL,
				'type' => 'link'
			)
		);
	}

	/**
	 * 
	 * @param string $name
	 * @param string $description
	 *
	 * @return integer DataSource ID
	 */
	public function create( array $values ) 
	{
		$this->valid($values);

		$this->name = Arr::get($values, 'name');
		$this->description = Arr::get($values, 'description');
		$this->_is_indexable = (bool) Arr::get($values, 'is_indexable');
		
		$data = array(
			'type' => $this->_type,
			'indexed' => (bool) $this->_is_indexable,
			'description' => $this->description,
			'name' => $this->name,
			'created_on' => date('Y-m-d H:i:s'),
			'code' => serialize($this)
		);
		
		$query = DB::insert('datasources')
			->columns(array_keys($data))
			->values(array_values($data))
			->execute();

		$this->_id = $query[0];
		
		if(empty($this->_id))
		{
			throw new DataSource_Exception('Datasource section :name not created', 
					array(':name' => $this->name));
		}
		
		return $this->_id;
	}
	
	/**
	 * 
	 * @param integer $id
	 * @return null|Datasource_Section
	 */
	public static function load( $id ) 
	{
		if($id === NULL)
		{
			return NULL;
		}
		
		$query = DB::select('docs', 'indexed', 'locks', 'code')
			->from('datasources')
			->where('id', '=', (int) $id)
			->execute()
			->current();
	
		if( $query === NULL )
		{
			return NULL;
		}

		$result = unserialize($query['code']);

		$result->_id = $id;
		$result->_lock = (int) $query['locks'];
		$result->_docs = (int) $query['docs'];
		$result->_is_indexable = (bool) $query['indexed'];

		return $result;
	}
	
	/**
	 * 
	 * @param array $values
	 * @return boolean
	 */
	public function save( array $values = NULL) 
	{
		if( ! $this->loaded())
		{
			return FALSE;
		}
		
		if(is_array($values))
		{
			$this->valid($values);

			$this->name = Arr::get($values, 'name');
			$this->description = Arr::get($values, 'description');
		
			$this->set_indexable(Arr::get($values, 'is_indexable', FALSE));
		}
		
		$data = array(
			'indexed' => $this->_is_indexable,
			'name' => $this->name,
			'description' => $this->description,
			'updated_on' => date('Y-m-d H:i:s'),
			'code' => serialize( $this )
		);
		
		DB::update('datasources')
			->set($data)
			->where( 'id', '=', $this->_id )
			->execute();

		$this->update_size();
		
		return TRUE;
	}
	
	/**
	 * 
	 * @return \Datasource_Section
	 */
	public function remove() 
	{
		$this->remove_documents();
		
		DB::delete('datasources')
			->where('id', '=', $this->_id)
			->execute();

		$this->_id = NULL;
		
		return $this;
	}
	
	/**
	 * @return \Datasource_Section
	 */
	public function remove_documents()
	{
		DB::delete($this->_ds_table)
			->where('ds_id', '=', $this->_id)
			->execute();
		
		return $this;
	}
	
	/**
	 * 
	 * @param integer $doc_id
	 * @return DataSource_Document
	 */
	abstract public function get_document($doc_id);
	
	/**
	 * 
	 * @return \DataSource_Document
	 */
	abstract public function get_empty_document();
	
	/**
	 * 
	 * @return \Datasource_Section
	 */
	public function increase_lock() 
	{
		DB::update('datasources')
			->set(array(
				'locks' => DB::expr('locks + 1')
			))
			->where('id', '=', $this->_id)
			->execute();

		$this->_lock++;
		
		return $this;
	}

	/**
	 * 
	 * @return \Datasource_Section
	 */
	public function decrease_lock()	
	{
		DB::update('datasources')
			->set(array(
				'locks' => DB::expr('locks - 1')
			))
			->where('id', '=', $this->_id)
			->execute();

		$this->_lock--;
		
		return $this;
	}
	
	/**
	 * 
	 * @param array $ids
	 * @return \Datasource_Section
	 */
	public function publish(array $ids) 
	{
		return $this->_publish($ids, TRUE);
	}

	/**
	 * 
	 * @param array $ids
	 * @return \Datasource_Section
	 */
	public function unpublish(array $ids) 
	{
		return $this->_publish($ids, FALSE);
	}
	
	/**
	 * 
	 * @param array $ids
	 * @param boolean $value
	 * @return \Datasource_Section
	 */
	protected function _publish(array $ids, $value) 
	{
		DB::update($this->_ds_table)
			->set(array(
				'published' => (bool) $value,
				'updated_on' => date('Y-m-d H:i:s'),
			))
			->where('id', 'in', $ids)
			->where('ds_id', '=', $this->_id)
			->execute();

		if($value === TRUE)
		{
			$this->add_to_index($ids);
		}
		else
		{
			$this->remove_from_index($ids);
		}

		return $this;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function is_full() 
	{
		if( ! $this->_size) return FALSE;

		return $this->_size <= $this->_docs;
	}

	/**
	 * 
	 * @param integer $size
	 * @return \Datasource_Section
	 */
	public function set_size($size) 
	{
		$size = (int) $size;

		$this->_size = $size == 0 OR $this->_docs <= $size ? $size : $this->_size;
		
		return $this;
	}
	
	/**
	 * 
	 * @return \Datasource_Section
	 */
	public function update_size() 
	{
		if($this->_ds_table) 
		{
			DB::update('datasources')
				->set(array(
					'docs' => DB::select(DB::expr('COUNT("*")'))
						->from($this->_ds_table)
						->where('ds_id', '=', $this->_id)
				))
				->where('id', '=', $this->_id)
				->execute();
		}
		
		return $this;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function is_indexable()
	{
		return (bool) $this->_is_indexable;
	}

		/**
	 * 
	 * @param boolean $newState
	 * @return \Datasource_Section
	 */
	public function set_indexable($state) 
	{
		$state = (bool) $state;

		if( ! $this->loaded() )
		{
			$this->_is_indexable = $state;
			
			return $this;
		}

		if($state == $this->is_indexable())
		{
			return $this;
		}

		if($state) 
		{
			$this->_is_indexable = $state;
			$this->add_to_index();
		} 
		else 
		{
			$this->remove_from_index();
			$this->_is_indexable = $state;
		}
		
		return $this;
	}
	
	/**
	 * 
	 * @param array $ids
	 * @param string $header
	 * @param string $content
	 * @param string $intro
	 * @return \Datasource_Section
	 */
	public function add_to_index(array $ids = array(), $header = NULL, $content = NULL, $intro = NULL) 
	{
		if( ! $this->is_indexable())
		{
			return $this;
		}

		if(count($ids) == 1 AND $header !== NULL)
		{
			Search::instance()->add_to_index('ds_' . $this->id(), $ids[0], $header, $content, $intro);
		}
		else
		{
			$docs = $this->get_indexable_docs($ids);
			
			foreach($docs as $doc)
			{
				Search::instance()->add_to_index('ds_' . $this->id(), $doc['id'], $doc['header'], $doc['content'], $doc['intro']);
			}
		}
	}
	
	/**
	 * 
	 * @param array $id
	 * @param string $header
	 * @param string $content
	 * @param string $intro
	 * @return \Datasource_Section
	 */
	public function update_index(array $ids = array(), $header = NULL, $content = NULL, $intro = NULL) 
	{
		if( ! $this->is_indexable())
		{
			return $this;
		}

		return $this->add_to_index($ids, $header, $content, $intro);
	}
	
	/**
	 * 
	 * @param array $ids
	 * @return \Datasource_Section
	 */
	public function remove_from_index( array $ids = NULL) 
	{
		if( ! $this->is_indexable())
		{
			return $this;
		}
		
		Search::instance()->remove_from_index('ds_' . $this->id(), $ids);
	}
	
	public function get_indexable_docs($id = NULL) 
	{
		$result = DB::select('id', 'header', 'content', 'intro')
			->from($this->_ds_table)
			->where('published', '=', 1)
			->where('ds_id', '=', $this->_id);
		
		if($id !== NULL)
		{
			if(is_array($id))
			{
				$result->where('id', 'in', $id);
			}
			else
			{
				$result->where('id', '=', $id);
			}
		}

		return $result
			->execute()
			->as_array('id');
	}
	
	/**
	 * 
	 * @param array $ids
	 * @return array
	 */
	public function filter_docs( array $ids) 
	{
		return DB::select('id')
			->from($this->_ds_table)
			->where('id', 'in', $ids)
			->where('ds_id', '=', $this->_id)
			->execute()
			->as_array(NULL, 'id');
	}
	
	/**
	 * 
	 * @param integer $ds_id
	 * @return array
	 */
	abstract public function get_headline( array $ids = NULL, $search_word = NULL );
	
	public function __sleep()
	{
		$vars = get_object_vars($this);
		unset($vars['_docs'], $vars['_is_indexable']);

		return array_keys($vars);
	}
	
	public function __wakeup()
	{
		$this->_docs = 0;
		$this->_is_indexable = FALSE;
	}
	
	public function valid(array $array)
	{
		$array = Validation::factory($array)
			->rules('name', array(
				array('not_empty')
			))
			->label('name', __('Header') );
		
		if( ! $array->check())
		{
			throw new Validation_Exception($array);
		}
	}
}