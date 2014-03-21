<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package Datasource
 */
abstract class Datasource_Decorator {
	
	/**
	 * @var string
	 */
	protected $_table_name;
	
	/**
	 * @var integer
	 */
	protected $_id = NULL;
	
	/**
	 * @var boolean 
	 */
	protected $_loaded = FALSE;
	
	/**
	 * @var boolean 
	 */
	protected $_updated = FALSE;
	
	/**
	 * @var boolean 
	 */
	protected $_created = FALSE;
	
	/**
	 *
	 * @var boolean 
	 */
	protected $_saved = FALSE;
	
	/**
	 * @var array 
	 */
	protected $_object = array();

	/**
	 * @var array 
	 */
	protected $_original_values = array();
	
	/**
	 *
	 * @var array 
	 */
	protected $_temp_values = array();
	
	public function __construct()
	{
		$this->_load_values($this->defaults());
		$this->_initialize();
	}
	
	protected function _initialize() {}

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
	 * @return string
	 */
	public function table_name()
	{
		return $this->_table_name;
	}

	/**
	 * 
	 * @return array
	 */
	public function values()
	{
		return $this->_object;
	}

	/***************************************************************************
	 * Rules
	 **************************************************************************/
	/**
	 * return array
	 */
	public function fields()
	{
		return array();
	}

    /**
	 * 
	 * @return array
	 */
	public function rules()
	{
		return array();
	}
	
	/**
	 * @return array
	 */
	public function defaults()
	{
		return array();
	}
	
	/**
	 * @return array
	 */
	public function filters()
	{
		return array();
	}
	
	/**
	 * @return array
	 */
	public function labels()
	{
		return array();
	}
	
	/***************************************************************************
	 * Statuses
	 **************************************************************************/
	/**
	 * @return boolean
	 */
	public function loaded()
	{
		return (bool) $this->_loaded;
	}
	
	/**
	 * @return boolean
	 */
	public function created()
	{
		return (bool) $this->_created;
	}
	
	/**
	 * @return boolean
	 */
	public function updated()
	{
		return (bool) $this->_updated;
	}

	/***************************************************************************
	 * Getter & Setter
	 **************************************************************************/
	
	public function __set($key, $value)
	{
		$this->set($key, $value);
	}

	public function __get($key)
	{
		return $this->get($key);
	}

	public function set($key, $value)
	{
		if (in_array($key, $this->fields()))
		{
			$value = $this->_run_filter($key, $value);

			// See if the data really changed
			if ($value !== $this->_object[$key])
			{
				$this->_object[$key] = $value;

				$this->_saved = FALSE;
			}
		}
		else
		{
			$this->_temp_values[$key] = $value;
		}
		
		return $this;
	}
	
	public function get($key, $default = NULL)
	{
		if(array_key_exists($key, $this->_object))
		{
			if( ! $this->loaded() AND empty($this->_object[$key]))
			{
				return Arr::get($this->defaults(), $key, $default);
			}

			return $this->_object[$key];
		}

		return Arr::get($this->_temp_values, $key, $default);
	}

	public function __isset($key)
	{
		return isset($this->_object[$key]) OR isset($this->_temp_values[$key]);
	}
	
	public function __unset($key)
	{
		unset($this->_object[$key], $this->_temp_values[$key]);
	}
	
	public function __toString()
	{
		return (string) $this->_id;
	}
	
	public function reset() 
	{
		foreach ($this->fields() as $key)
		{
			$this->_object[$key] = NULL;
		}
		
		$this->_original_values = $this->_temp_values = $this->_changed_values = array();
		
		$this->_load_values( $this->_object );
		
		$this->_loaded = $this->_created = $this->_updated = FALSE;
		$this->_id = NULL;

		return $this;
	}
	
	protected function _load_values(array $values)
	{
		if (array_key_exists('id', $values))
		{
			if ($values['id'] !== NULL)
			{
				$this->_loaded = TRUE;
				$this->_id = $values['id'];
			}
			else
			{
				$this->_loaded = FALSE;
			}
		}
		
		foreach ($this->fields() as $column)
		{
			$this->_object[$column] = Arr::get($values, $column);
		}

		if ( $this->_loaded )
		{
			$this->_original_values = $this->_object;
		}

		return $this;
	}
	
	

	/**
	 * Загрузка данных из массива
	 * 
	 * @param array $array Массив значений полей документа
	 * @return \DataSource_Document
	 */
	public function read_values(array $array = NULL) 
	{
		foreach($this->fields() as $key)
		{
			$this->{$key} = Arr::get($array, $key);
			unset($array[$key]);
		}
		
		$this->_temp_values = Arr::merge($array, $this->_temp_values);
		
		return $this;
	}
	
	/**
	 * Загрузка файлов из массива
	 * 
	 * @param array $array
	 * @return \DataSource_Document
	 */
	public function read_files($array) 
	{
		foreach($this->fields() as $key)
		{
			$this->{$key} = Arr::get($array, $key);
			unset($array[$key]);
		}
		
		$this->_temp_values = Arr::merge($array, $this->_temp_values);
		
		return $this;
	}
	
	/***************************************************************************
	 * Getter & Setter
	 **************************************************************************/
	public function validate()
	{
		$values = Arr::merge($this->values(), $this->_temp_values);

		$validation = Validation::factory( $values )
			->bind(':object', $this)
			->bind(':original_values', $this->_original_values);
		
		foreach ($this->rules() as $key => $rules)
		{
			$validation->rules($key, $rules);
		}
		
		foreach ($this->labels() as $key => $label)
		{
			$validation->label($key, $label);
		}

		if( ! $validation->check() )
		{
			throw new Validation_Exception( $validation );
		}
		
		return TRUE;
	}
	
	protected function _run_filter($key, $value)
	{
		$filters = $this->filters();

		// Get the filters for this column
		$wildcards = empty($filters[TRUE]) ? array() : $filters[TRUE];

		// Merge in the wildcards
		$filters = empty($filters[$key]) ? $wildcards : array_merge($wildcards, $filters[$key]);

		// Bind the field name and model so they can be used in the filter method
		$_bound = array
		(
			':field' => $key,
			':document' => $this,
		);

		foreach ($filters as $array)
		{
			// Value needs to be bound inside the loop so we are always using the
			// version that was modified by the filters that already ran
			$_bound[':value'] = $value;

			// Filters are defined as array($filter, $params)
			$filter = $array[0];
			$params = Arr::get($array, 1, array(':value'));

			foreach ($params as $key => $param)
			{
				if (is_string($param) AND array_key_exists($param, $_bound))
				{
					// Replace with bound value
					$params[$key] = $_bound[$param];
				}
			}

			if (is_array($filter) OR ! is_string($filter))
			{
				// This is either a callback as an array or a lambda
				$value = call_user_func_array($filter, $params);
			}
			elseif (strpos($filter, '::') === FALSE)
			{
				// Use a function call
				$function = new ReflectionFunction($filter);

				// Call $function($this[$key], $param, ...) with Reflection
				$value = $function->invokeArgs($params);
			}
			else
			{
				// Split the class and method of the rule
				list($class, $method) = explode('::', $filter, 2);

				// Use a static method call
				$method = new ReflectionMethod($class, $method);

				// Call $Class::$method($this[$key], $param, ...) with Reflection
				$value = $method->invokeArgs(NULL, $params);
			}
		}

		return $value;
	}
	
	public function set_bool($value)
	{
		return (bool) $value ? 1 : 0;
	}
	
	/***************************************************************************
	 * Load & Save & Delete
	 **************************************************************************/
	public function load( $id )
	{
		$values = DB::select()
			->select_array( array_keys( $this->fields() ))
			->from($this->table_name())
			->where('id', '=', (int) $id)
			->limit(1)
			->execute()
			->current();
				
		if( empty($result) ) return $this;
		
		$this->_load_values( $values );
		
		return $this;
	}
	
	public function create()
	{
		if( $this->loaded() ) return $this;
		
		$this->validate();
		
		$values = $this->_object;

		$values['created_on'] = date('Y-m-d H:i:s');
		unset($values['id']);
		
		$result = DB::insert($this->table_name())
			->columns(array_keys($values))
			->values(array_values($values))
			->execute();
		
		$this->_object['id'] = $this->_id = $result[0];

		$this->_loaded = $this->_created = TRUE;
		$this->_original_values = $this->_object;

		return $this;
	}
	
	public function update()
	{
		if( ! $this->loaded() ) return $this;
		
		$this->validate();
		
		$values = $this->_object;
		unset($values['id'], $values['ds_id']);
		
		$values['updated_on'] = date('Y-m-d H:i:s');

		DB::update($this->table_name())
			->set($values)
			->where('id', '=', $this->_id)
			->execute();
		
		$this->_updated = TRUE;
		$this->_original_values = $this->_object;

		return $this;
	}
	
	public function remove()
	{
		if( ! $this->loaded() ) return FALSE;
		
		DB::delete($this->table_name())
			->where('id', '=', $this->_id)
			->execute();
		
		$this->reset();
		
		return TRUE;
	}
}