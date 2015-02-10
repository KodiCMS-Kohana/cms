<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Datasource
 * @category	Document
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Datasource_Document implements ArrayAccess {

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
	 * Документ имеет автора
	 * @var boolean 
	 */
	protected $_is_authored = FALSE;
	
	/**
	 * Документ в режиме для чтения
	 * @var boolean 
	 */
	protected $_read_only = FALSE;

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
	 * Список значений полей по умолчанию
	 * @return array
	 */
	public function defaults()
	{
		return array(
			'published' => 1
		);
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
	 * @param string $field
	 * @return boolean
	 */
	public function __isset($field)
	{
		return isset($this->_system_fields[$field]) OR isset($this->_temp_fields[$field]);
	}
	
	/**
	 * 
	 * @param string $field
	 */
	public function __unset($field)
	{
		$this->_system_fields[$field] = NULL;
		$this->_temp_fields[$field] = NULL;
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
		if (array_key_exists($field, $this->system_fields()))
		{
			if (!$this->loaded() AND (Arr::get($this->_system_fields, $field) === NULL))
			{
				return Arr::get($this->defaults(), $field, $default);
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
	 * @return \Datasource_Document
	 */
	public function set($field, $value)
	{
		if ($this->is_read_only())
		{
			return $this;
		}

		if (array_key_exists($field, $this->system_fields()))
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
	 * Установка документа в режим для чтения
	 * @return \Datasource_Document
	 */
	public function set_read_only()
	{
		$this->_read_only = TRUE;
		
		return $this;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function is_published()
	{
		return (bool) $this->published;
	}
	
	/**
	 * Установка документа в режим для чтения
	 * @return boolean
	 */
	public function is_read_only()
	{
		return $this->_read_only;
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
		return $this->system_fields();
	}
	
	/**
	 * Получение списка системных полей
	 * 
	 * @return array array([Field name])
	 */
	public function system_fields()
	{
		if ($this->_is_authored === TRUE AND ! isset($this->_system_fields['created_by_id']))
		{
			$this->_system_fields['created_by_id'] = NULL;
		}

		return $this->_system_fields;
	}

	/**
	 * Загрузка данных из массива
	 * 
	 * @param array $array Массив значений полей документа
	 * @param array $expected
	 * @return \DataSource_Document
	 */
	public function read_values(array $array = NULL, array $expected = NULL) 
	{
		// Default to expecting everything except the primary key
		if ($expected === NULL)
		{
			$expected = $this->system_fields();
		}
		else
		{
			$fields = $this->system_fields();
			foreach ($fields as $key => $value)
			{
				if (!in_array($key, $expected))
				{
					unset($fields[$key]);
				}
			}

			$expected = $fields;
		}

		foreach ($expected as $key => $value)
		{
			if ($key == 'id')
			{
				continue;
			}

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
		foreach ($array as $key => $value)
		{
			$this->{$key} = $value;
		}

		return $this;
	}
	
	/**
	 * Сброс значений полей документа
	 * 
	 * @return \DataSource_Document
	 */
	public function reset() 
	{
		foreach ($this->system_fields() as $key => $value)
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
	public function load($id)
	{
		return $this->load_by('id', (int) $id);
	}

	/**
	 * Загрузка документа по названию поля значению
	 * 
	 * @param string $field
	 * @param string $value
	 * @return \DataSource_Document
	 */
	public function load_by($field, $value)
	{
		$result = DB::select()
			->select_array(array_keys($this->system_fields()))
			->from($this->section()->table())
			->where('ds_id', '=', (int) $this->section()->id())
			->where($field, '=', $value)
			->limit(1)
			->execute()
			->current();

		if (empty($result))
		{
			return $this;
		}

		$this->_loaded = TRUE;

		foreach ($result as $field => $value)
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
		if ($this->is_read_only())
		{
			throw new DataSource_Exception_Document('Document is read only');
		}

		if (!$this->has_access_create())
		{
			throw new DataSource_Exception_Document('You do not have permission to create document');
		}

		$values = $this->system_fields();
		
		$values['ds_id'] = $this->section()->id();
		$values['created_on'] = date('Y-m-d H:i:s');
		$values['updated_on'] = $values['created_on'];

		if ($this->_is_authored === TRUE)
		{
			$values['created_by_id'] = (int) Auth::get_id();
		}

		unset($values['id']);
		
		$query = DB::insert($this->section()->table())
			->columns(array_keys($values))
			->values(array_values($values))
			->execute();

		$id = $query[0];

		if (empty($id))
		{
			return $this;
		}

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
		if ($this->is_read_only())
		{
			throw new DataSource_Exception_Document('Document is read only');
		}

		if (!$this->has_access_edit())
		{
			throw new DataSource_Exception_Document('You do not have permission to update document');
		}
		
		if (!$this->loaded())
		{
			return $this;
		}

		$values = $this->system_fields();
		unset($values['id'], $values['ds_id'], $values['created_on']);
		
		$values['updated_on'] = date('Y-m-d H:i:s');

		DB::update($this->section()->table())
			->set($values)
			->where('ds_id', '=', (int) $this->section()->id())
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
		if ($this->is_read_only())
		{
			throw new DataSource_Exception_Document('Document is read only');
		}

		if (!$this->has_access_remove())
		{
			throw new DataSource_Exception_Document('You do not have permission to remove document');
		}
		
		if (!$this->loaded())
		{
			return FALSE;
		}

		DB::delete($this->section()->table())
			->where('ds_id', '=', (int) $this->section()->id())
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
	 * @param Validation $extra_validation
	 * @param array $expected
	 * @return boolean|Validation
	 */
	public function validate(Validation $extra_validation = NULL, array $expected = NULL)
	{
		// Determine if any external validation failed
		$extra_errors = ($extra_validation AND ! $extra_validation->check());
		
		$values = Arr::merge($this->values(), $this->_temp_fields);

		$validation = Validation::factory( $values );
		
		$validation->rules('csrf', array(
			array('not_empty'), array('Security::check')
		));
		
		// Default to expecting everything except the primary key
		if ($expected === NULL)
		{
			$expected = $this->rules();
		}
		else
		{
			$rules = $this->rules();
			foreach ($rules as $field => $_rules)
			{
				if (!in_array($field, $expected))
				{
					unset($rules[$field]);
				}
			}
			
			$expected = $rules;
		}
		
		foreach ($expected as $field => $rules)
		{
			$validation->rules($field, $rules);
		}
		
		foreach ($this->labels() as $field => $label)
		{
			$validation->label($field, $label);
		}

		if (!$validation->check() OR $extra_errors)
		{
			$exception = new Validation_Exception($validation);

			if ($extra_errors)
			{
				// Merge any possible errors from the external object
				$exception->add_object($extra_validation);
			}

			throw $exception;
		}

		return TRUE;
	}

	/**
	 * Событие вызываемое в момент загрузки контроллера
	 */
	public function onControllerLoad() {}
	
	/**
	 * Событие вызываемое в момент ошибки создания документа
	 */
	public function onCreateException(Kohana_Exception $exception)
	{
		Messages::errors($exception->getMessage());
	}
	
	/**
	 * Событие вызываемое в момент ошибки обновления документа
	 */
	public function onUpdateException(Kohana_Exception $exception)
	{
		Messages::errors($exception->getMessage());
	}
	
	/**
	 * Событие вызываемое в момент ошибки удаления документа
	 */
	public function onRemoveException(Kohana_Exception $exception) {}
	
	/**************************************************************************
	 * ACL
	 **************************************************************************/
	/**
	 * Пользователь - создатель документа
	 * 
	 * @param integer $user_id
	 * @return boolean
	 */
	public function is_creator($user_id = NULL)
	{
		if ($this->_is_authored === TRUE)
		{
			if ($user_id === NULL)
			{
				$user_id = Auth::get_id();
			}

			$created_by_id = (int) Arr::get($this->system_fields(), 'created_by_id');
			return ACL::is_admin($user_id) OR ( $created_by_id == (int) $user_id);
		}

		return TRUE;
	}
	
	/**
	 * Пользователь имеет права на создание документа
	 * @param integer $user_id
	 * @return boolean
	 */
	public function has_access_view($user_id = NULL)
	{
		return (
			$this->section()->has_access('document.view')
			OR
			$this->has_access_create($user_id)
			OR
			$this->has_access_edit($user_id)
		);
	}
	
	/**
	 * Пользователь имеет права на создание документа
	 * @param integer $user_id
	 * @return boolean
	 */
	public function has_access_create()
	{
		return (
			$this->section()->has_access('document.create')
			OR
			$this->section()->is_creator()
		);
	}

	/**
	 * Пользователь имеет права на редактирование документа
	 * @param integer $user_id
	 * @return boolean
	 */
	public function has_access_edit($user_id = NULL, $check_own = TRUE)
	{
		return (
			($check_own === TRUE AND $this->is_creator($user_id))
			OR
			$this->section()->has_access('document.edit')
		);
	}
	
	/**
	 * @param integer $user_id
	 * @return boolean
	 */
	public function has_access_change($user_id = NULL, $check_own = TRUE)
	{
		return (
			($this->loaded() AND $this->has_access_edit($user_id, $check_own))
			OR
			(!$this->loaded() AND $this->has_access_create())
		);
	}
	
	/**
	 * Пользователь имеет права на редактирование документа
	 * @param integer $user_id
	 * @return boolean
	 */
	public function has_access_remove($user_id = NULL, $check_own = TRUE)
	{
		return (
			($check_own === TRUE AND $this->is_creator($user_id))
			OR 
			$this->section()->has_access('document.remove')
		);
	}
	
	/**************************************************************************
	 * Links
	 **************************************************************************/
	/**
	 * 
	 * @return string
	 */
	public function edit_link()
	{
		return $this->view_link();
	}
	
	/**
	 * 
	 * @return string
	 */
	public function view_link()
	{
		return Route::get('datasources')->uri(array(
			'controller' => 'document',
			'directory' => $this->section()->type(),
			'action' => 'view'
		)) . URL::query(array(
			'ds_id' => $this->section()->id(), 
			'id' => $this->id
		));
	}
	
	/**************************************************************************
	 * ArrayAccess
	 **************************************************************************/
	public function offsetSet($offset, $value)
	{
		$this->set($offset, $value);
	}

	public function offsetExists($offset)
	{
		return $this->__isset($offset);
	}

	public function offsetUnset($offset)
	{
		return $this->__unset($offset);
	}

	public function offsetGet($offset)
	{
		return $this->get($offset);
	}
	
	/**
	 * 
	 * @return string ID
	 */
	public function __toString()
	{
		return (string) $this->id;
	}
}