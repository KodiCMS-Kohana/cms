<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Datasource
 */
class Datasource_Document {

	/**
	 * Список системных полей
	 * @var array 
	 */
	protected $_system_fields = array(
		'id' => NULL,
		'ds_id' => NULL,
		'published' => NULL,
		'header' => NULL
	);
	
	/**
	 * Значения, которые могут понадобится в документе,
	 * но которые не попадут в БД.
	 * 
	 * @var array 
	 */
	protected $_temp_fields = array();

	/**
	 * 
	 * @var array 
	 */
	protected $_changed_fields = array();
	
	/**
	 * Список значений полей по умолчанию
	 * @var array 
	 */
	protected $_default_values = array(
		'published' => 1
	);
	
	/**
	 * Объект раздела
	 * @var DataSource_Hybrid_Section 
	 */
	protected $_section = NULL;
	
	/**
	 * Статус загрузки документа
	 * @var boolean 
	 */
	protected $_loaded = FALSE;
	
	/**
	 * Статус обновления документа
	 * @var boolean 
	 */
	protected $_updated = FALSE;
	
	/**
	 * Статус создания документа
	 * @var boolean 
	 */
	protected $_created = FALSE;

	/**
	 * 
	 * @param DataSource_Hybrid_Section $section
	 */
	public function __construct( DataSource_Section $section )
	{
		$this->_section = $section;
		
		$this->ds_id = $section->id();
		
		$this->reset();
	}

	/**
	 * Правила фильтрации полей документа
	 * @return array
	 */
	public function filters()
	{
		return array(
			'id' => array(
				array('intval')
			),
			'ds_id' => array(
				array('intval')
			),
			'published' => array(
				array(array($this, 'set_published'))
			)
		);
	}
	
	/**
	 * Правила валидации полей документа
	 * @return type
	 */
	public function rules()
	{
		return array(
			'header' => array(
				array('not_empty')
			)
		);
	}
	
	/**
	 * Заголовки полей
	 * @return type
	 */
	public function labels()
	{
		return array(
			'id' => __('ID'),
			'header' =>  __('Header'),
			'published' => __('Published')
		);
	}
	
	/**
	 * Сеттер. Присваивает значение полю документа
	 * 
	 * @param string $field
	 * @param string $value
	 */
	public function __set($field, $value)
	{
		$this->set($field, $value);
	}
	
	/**
	 * Геттер значений полей документов
	 * 
	 * @param string $field
	 * @return mixed
	 */
	public function __get($field)
	{
		return $this->get($field);
	}

	/**
	 * Проаверка существаования поля в документе
	 * 
	 * @param type $field
	 * @return type
	 */
	public function __isset($field)
	{
		return isset($this->_system_fields[$field]);
	}

	/**
	 * Проверка существования документа
	 * 
	 * @return boolean
	 */
	public function loaded()
	{
		return (bool) $this->_loaded;
	}
	
	/**
	 * Проверка создания документа
	 * 
	 * @return boolean
	 */
	public function created()
	{
		return (bool) $this->_created;
	}
	
	/**
	 * Проверка обновления документа
	 * 
	 * @return boolean
	 */
	public function updated()
	{
		return (bool) $this->_updated;
	}

		/**
	 * Получение объекта раздела
	 * 
	 * @return DataSource_Section
	 */
	public function section()
	{
		return $this->_section;
	}

	/**
	 * Геттер значений полей документов
	 * 
	 * @param string $field
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($field, $default = NULL)
	{
		if(array_key_exists($field, $this->_system_fields))
		{
			if( ! $this->loaded() AND empty($this->_system_fields[$field]))
			{
				return Arr::get($this->_default_values, $field, $default);
			}

			return $this->_system_fields[$field];
		}

		return Arr::get($this->_temp_fields, $field, $default);
	}
	
	/**
	 * Сеттер. Присваивает значение полю документа
	 * Если поле не существует, значение попадает в массив _temp_fields
	 * 
	 * @param string $field
	 * @param string $value
	 */
	public function set($field, $value)
	{
		if(array_key_exists($field, $this->_system_fields))
		{
			$this->_changed_fields[$field] = $this->_system_fields[$field];
			$this->_system_fields[$field] = $this->_run_filter($field, $value);
		}
		else
		{
			$this->_temp_fields[$field] = $value;
		}
		
		return $this;
	}
	
	public function set_published($value)
	{
		return (bool) $value ? 1 : 0;
	}

	/**
	 * Получение старого значения поля, до присвоения нового
	 * 
	 * @param string $field
	 * @param mixed $default
	 * @return mixed
	 */
	public function get_old_value($field, $default = NULL)
	{
		return Arr::get($this->_changed_fields, $field);
	}

	/**
	 * Проверка поля на изменение значения
	 * 
	 * @param string $field
	 * @return boolean
	 */
	public function is_changed( $field )
	{
		return (
			$this->get_old_value($field) !== NULL 
		AND 
			$this->{$field} != $this->get_old_value($field)
		);
	}
	
	/**
	 * Получение всех значений полей
	 * 
	 * @return array array([Field name] => [value])
	 */
	public function values()
	{
		return $this->_system_fields;
	}

	/**
	 * Загрузка данных из массива
	 * 
	 * @param array $array Массив значений полей документа
	 * @return \DataSource_Document
	 */
	public function read_values(array $array = NULL) 
	{
		foreach($this->_system_fields as $key => $value)
		{
			$this->{$key} = Arr::get($array, $key);
			unset($array[$key]);
		}
		
		$this->_temp_fields = $array;
		
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
		foreach($this->_system_fields as $key => $value)
		{
			$this->{$key} = Arr::get($array, $key);
			unset($array[$key]);
		}
		
		$this->_temp_fields = $array;
		return $this;
	}
	
	/**
	 * Сброс значений полей документа
	 * 
	 * @return \DataSource_Document
	 */
	public function reset() 
	{
		foreach ($this->_system_fields as $key => $value)
		{
			$this->_system_fields[$key] = NULL;
		}
		
		return $this;
	}
	
	/**
	 * Фильтрация полей документа согласно правилам
	 * 
	 * @see DataSource_Hybrid_Document::filters()
	 * 
	 * @param string $field
	 * @param mixed $value
	 * @return mixed
	 */
	protected function _run_filter($field, $value)
	{
		$filters = $this->filters();

		// Get the filters for this column
		$wildcards = empty($filters[TRUE]) ? array() : $filters[TRUE];

		// Merge in the wildcards
		$filters = empty($filters[$field]) ? $wildcards : array_merge($wildcards, $filters[$field]);

		// Bind the field name and model so they can be used in the filter method
		$_bound = array
		(
			':field' => $field,
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

				// Call $function($this[$field], $param, ...) with Reflection
				$value = $function->invokeArgs($params);
			}
			else
			{
				// Split the class and method of the rule
				list($class, $method) = explode('::', $filter, 2);

				// Use a static method call
				$method = new ReflectionMethod($class, $method);

				// Call $Class::$method($this[$field], $param, ...) with Reflection
				$value = $method->invokeArgs(NULL, $params);
			}
		}

		return $value;
	}

	/**
	 * Загрузка документа по его ID
	 * 
	 *		$ds = Datasource_Data_Manager::load($ds_id);
	 *		$doc = $ds->get_document($id);
	 * 
	 * Проверка загрузки документа
	 * 
	 *		$doc->loaded();
	 * 
	 * @param integer $id
	 * @return \DataSource_Document
	 */
	public function load( $id )
	{
		$ds_id = $this->section()->id();

		$result = DB::select()
			->select_array( array_keys( $this->_system_fields ))
			->from($this->section()->table())
			->where('id', '=', (int) $id)
			->limit(1)
			->execute()
			->current();
				
		if( empty($result) ) return $this;
		
		$this->_loaded = TRUE;
		
		foreach($result as $field => $value)
		{
			$this->{$field} = $value;
		}
		
		return $this;
	}

	/**
	 * Создание документа
	 * 
	 *		$ds = Datasource_Data_Manager::load($ds_id);
	 *		$doc = $ds->get_empty_document();
	 *		$doc
	 *			->read_values($this->request->post())
	 *			->read_files($_FILES)
	 *			->validate();
	 *		$doc = $ds->create_document($doc);
	 *		
	 *	Проверка создания документа
	 * 
	 *		$doc->created()
	 * 
	 * @return DataSource_Document
	 */
	public function create()
	{
		$values = $this->_system_fields;
		
		$values['ds_id'] = $this->section()->id();
		$values['created_on'] = date('Y-m-d H:i:s');
		unset($values['id']);
		
		$query = DB::insert($this->section()->table())
			->columns(array_keys($values))
			->values(array_values($values))
			->execute();

		$id = $query[0];

		if( empty($id) ) return NULL;
		
		$this->id = $id;

		$this->_created = TRUE;

		return $this;
	}
	
	/**
	 * Обновление документа
	 * 
	 *		$ds = Datasource_Data_Manager::load($ds_id);
	 *		$doc = $ds->get_document($id);
	 *		$doc
	 *			->read_values($this->request->post())
	 *			->read_files($_FILES)
	 *			->validate();
	 * 
	 *		$doc = $ds->update_document($doc);
	 *	
	 * Проверка обновленияя документа
	 * 
	 *		$doc->updated()
	 *
	 * @return DataSource_Document
	 */
	public function update()
	{
		if( ! $this->loaded() ) return $this;
		
		$values = $this->_system_fields;
		unset($values['id'], $values['ds_id']);
		
		$values['updated_on'] = date('Y-m-d H:i:s');

		DB::update($this->section()->table())
			->set($values)
			->where('id', '=', $this->id)
			->execute();
		
		$this->_updated = TRUE;
		
		return $this;
	}
	
	/**
	 * Метод удаления документа
	 * 
	 *		$ds = Datasource_Data_Manager::load($ds_id);
	 *		$doc = $ds->get_document($id);
	 * 
	 * @return null|boolean
	 */
	public function remove()
	{
		if( ! $this->loaded() ) return FALSE;
		
		DB::delete($this->section()->table())
			->where('id', '=', $this->id)
			->execute();
		
		$this->reset();
		
		return TRUE;
	}
	
	/**
	 * Валидация полей документа согласно правилам валидации
	 * 
	 * @see DataSource_Document::rules()
	 * @see DataSource_Document::labels()
	 * 
	 *			$doc = $ds->get_document($id);
	 *			$doc
	 *				->read_values($this->request->post())
	 *				->read_files($_FILES)
	 *				->validate($this->request->post() + $_FILES);
	 * 
	 * @param array $array
	 * @param string $errors_file
	 * @return boolean|Validation
	 */
	public function validate($errors_file = 'validation')
	{
		$values = Arr::merge($this->values(), $this->_temp_fields);

		$validation = Validation::factory( $values );
		
		foreach ($this->rules() as $field => $rules)
		{
			$validation->rules($field, $rules);
		}
		
		foreach ($this->labels() as $field => $label)
		{
			$validation->label($field, $label);
		}

		if( ! $validation->check() )
		{
			throw new Validation_Exception( $validation );
		}
		
		return TRUE;
	}	
}