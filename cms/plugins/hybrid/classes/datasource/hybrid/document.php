<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Datasource
 * @category	Document
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Document extends Datasource_Document {
	
	protected $_system_fields = array(
		'id' => NULL,
		'ds_id' => NULL,
		'published' => NULL,
		'header' => NULL,
		'created_on' => NULL,
		'meta_title' => NULL, 
		'meta_keywords' => NULL, 
		'meta_description' => NULL
	);
	
	/**
	 * Список полей документа
	 * @var array array([ID] => [Document value])
	 */
	protected $_fields = array();
	
	/**
	 * Документ имеет автора
	 * @var boolean 
	 */
	protected $_is_authored = TRUE;
	
	public function defaults()
	{
		$defaults = parent::defaults();
		
		$defaults['meta_title'] = '{.}';

		return $defaults;
	}

	/**
	 * Проаверка существаования поля в документе
	 * 
	 * @param string $field
	 * @return boolean
	 */
	public function __isset($field)
	{
		return isset($this->_fields[$field]) || parent::__isset($field);
	}
	
	/**
	 * 
	 * @param string $field
	 */
	public function __unset($field)
	{
		unset($this->_fields[$field]);
		parent::__unset($field);
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
		if( array_key_exists($field, $this->_fields) )
		{
			return $this->_fields[$field];
		}

		return parent::get($field, $default);
	}
	
	/**
	 * Сеттер. Присваивает значение полю документа
	 * 
	 * @param string $field
	 * @param string $value
	 */
	public function set($field, $value)
	{
		if($this->is_read_only())
		{
			return $this;
		}
		
		if(array_key_exists($field, $this->system_fields()))
		{
			return parent::set($field, $value);
		}
		else if(array_key_exists($field, $this->_fields))
		{
			$this->set_field_value($field, $value);
		}
		
		return $this;
	}

	/**
	 * 
	 * @return DataSource_Hybrid_Record
	 */
	public function record()
	{
		return $this->_record;
	}
	
	/**
	 * Получение всех значений полей
	 * 
	 * @return array array([Field name] => [value])
	 */
	public function values()
	{
		return Arr::merge($this->_fields, $this->system_fields(), $this->_temp_fields);
	}

	/**
	 * Загрузка данных из массива
	 * 
	 * @param array $array Массив значений полей документа
	 * @param array $expected
	 * @return \DataSource_Hybrid_Document
	 */
	public function read_values(array $array = NULL, array $expected = NULL) 
	{
		// Default to expecting everything except the primary key
		if ($expected === NULL)
		{
			$expected = $this->section()->record()->fields();
		}
		else
		{
			$fields = $this->section()->record()->fields();
			foreach ($fields as $name => $field)
			{
				if(!in_array($field->id, $expected))
				{
					unset($fields[$name]);
				}
			}
			
			$expected = $fields;
		}
		
		foreach($expected as $field)
		{
			if($field->family == DataSource_Hybrid_Field::FAMILY_FILE )
			{
				continue;
			}

			$field->onReadDocumentValue($array, $this);
			unset($array[$field->name]);
		}
		
		return parent::read_values($array);
	}
	
	/**
	 * Загрузка файлов из массива
	 * 
	 * @param array $array
	 * @return \DataSource_Hybrid_Document
	 */
	public function read_files($array) 
	{
		foreach($this->section()->record()->fields() as $key => $field)
		{
			if(
				isset($array[$key]) 
			AND
				$field->family == DataSource_Hybrid_Field::FAMILY_FILE 
			AND 
				Upload::valid( $array[$key] ) 
			AND 
				Upload::not_empty($array[$key]))
			{
				$field->onReadDocumentValue($array, $this);
				unset($array[$field->name]);
			}
		}
	
		return $this;
	}

	/**
	 * Установка значения поля документа (не системного)
	 * 
	 * @param string $field
	 * @param mixed $value
	 */
	public function set_field_value($field, $value)
	{
		$this->_changed_fields[$field] = $this->_fields[$field];
		
		$fields = $this->section()->record()->fields();
		
		$this->_fields[$field] = isset($fields[$field]) 
			? $fields[$field]->onSetValue( $value, $this )
			: $value;
	}
	
	/**
	 * Конвертация значений полей документа в момент загрузкти данных в форму
	 * редактора
	 * 
	 * @return \DataSource_Hybrid_Document
	 */
	public function convert_values() 
	{
		foreach( $this->section()->record()->fields() as $key => $field )
		{
			$this->{$key} = $field->convert_value( $this->{$key} );
		}
		
		return $this;
	}
	
	/**
	 * Сброс значений полей документа
	 * 
	 * @return \DataSource_Hybrid_Document
	 */
	public function reset() 
	{
		foreach( $this->section()->record()->fields() as $key => $field )
		{
			$this->_fields[$key] = NULL;
		}
		
		return parent::reset();
	}

	/**
	 * 
	 * @param string $field
	 * @param string $value
	 * @return \DataSource_Hybrid_Document
	 */
	public function load_by( $field, $value )
	{
		$ds_id = $this->section()->id();

		if (array_key_exists($field, $this->_fields))
		{
			$preffix = "dshybrid_{$ds_id}.";
		}
		else
		{
			$preffix = 'dshybrid.';
		}
		
		$columns = $this->system_fields();
		unset($columns['id']);

		$result = DB::select(array('dshybrid.id', 'id'))
			->select_array(array_keys($columns))
			->select_array(array_keys($this->_fields))
			->from('dshybrid')
			->join("dshybrid_{$ds_id}", 'left')
				->on("dshybrid_{$ds_id}.id", '=', 'dshybrid.id')
			->where($preffix . $field, '=', $value)
			->limit(1)
			->execute()
			->current();
				
		if( empty($result) )
		{
			return $this;
		}
		
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
		parent::create();
		
		if( ! $this->created() ) return NULL;
		
		$query = DB::insert("dshybrid_" . $this->section()->id())
			->columns(array('id'))
			->values(array($this->id))
			->execute();

		$record = $this->section()->record();
		$record->initialize_document($this);
		$query = $record->get_sql($this);
	
		foreach($query as $q)
		{
			DB::query(Database::UPDATE, $q)->execute();
		}
		
		return $this->id;
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
		parent::update();
		
		if( ! $this->updated() ) return $this;

		$record = $this->section()->record();
		
		$old_document = $this->section()->get_document($this->id);
		$record->document_changed($old_document, $this);

		$queries = $record->get_sql($this);

		foreach($queries as $query)
		{
			DB::query(Database::UPDATE, $query)->execute();
		}
		
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
		$id = $this->id;

		parent::remove();

		if( ! $this->loaded() ) return FALSE;

		DB::delete("dshybrid_" . $this->section()->id())
			->where('id', '=', $id)
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

		// Default to expecting everything except the primary key
		if ($expected === NULL)
		{
			$fields = $this->section()->record()->fields();
			$expected_rules = $this->rules();
		}
		else
		{
			$fields = $this->section()->record()->fields();
			foreach ($fields as $name => $field)
			{
				if (!in_array($field->id, $expected))
				{
					unset($fields[$name]);
				}
			}

			$rules = $this->rules();
			foreach ($rules as $field => $_rules)
			{
				if (!in_array($field, $expected))
				{
					unset($rules[$field]);
				}
			}

			$expected_rules = $rules;
		}

		$values = $this->values();
		$values['csrf'] = Arr::get($this->_temp_fields, 'csrf');

		$validation = Validation::factory($values);

		$validation->rules('csrf', array(
			array('not_empty'), array('Security::check')
		));

		foreach ($expected_rules as $field => $rules)
		{
			$validation->rules($field, $rules);
		}

		foreach ($this->labels() as $field => $label)
		{
			$validation->label($field, $label);
		}

		foreach ($fields as $name => $field)
		{
			$field->onValidateDocument($validation, $this);
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
	public function onControllerLoad() 
	{
		foreach ($this->section()->record()->fields() as $field)
		{
			$field->onControllerLoad();
		}
	}
}