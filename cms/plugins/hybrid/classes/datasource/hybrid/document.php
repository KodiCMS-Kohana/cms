<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package Datasource
 * @category Hybrid
 */
class DataSource_Hybrid_Document extends Datasource_Document {
	
	/**
	 * Список полей документа
	 * @var array array([ID] => [Document value])
	 */
	protected $_fields = array();

	/**
	 * Проаверка существаования поля в документе
	 * 
	 * @param type $field
	 * @return type
	 */
	public function __isset($field)
	{
		return isset($this->_fields[$field]);
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
		if(array_key_exists($field, $this->_system_fields))
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
		return Arr::merge($this->_fields, $this->_system_fields);
	}

	/**
	 * Загрузка данных из массива
	 * 
	 * @param array $array Массив значений полей документа
	 * @return \DataSource_Hybrid_Document
	 */
	public function read_values(array $array = NULL) 
	{
		foreach($this->section()->record()->fields() as $field)
		{
			if($field->family == DataSource_Hybrid_Field::FAMILY_FILE )	continue;
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
	 * @return \DataSource_Hybrid_Document
	 */
	public function load( $id )
	{
		$ds_id = $this->section()->id();

		$result = DB::select(array('dshybrid.id', 'id'))
			->select('ds_id', 'published', 'header')
			->select_array( array_keys( $this->_fields ))
			->from('dshybrid')
			->join("dshybrid_{$ds_id}", 'left')
				->on("dshybrid_{$ds_id}.id", '=', 'dshybrid.id')
			->where('dshybrid.id', '=', (int) $id)
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
		parent::create();
		
		if( ! $this->created() ) return NULL;
		
		$query = DB::insert("dshybrid_" . $this->ds_id)
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
		parent::remove();

		if( ! $this->loaded() ) return NULL;
		
		DB::delete("dshybrid_" . $this->section()->id())
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
		$validation = Validation::factory($this->values());
		
		foreach ($this->rules() as $field => $rules)
		{
			$validation->rules($field, $rules);
		}
		
		foreach ($this->labels() as $field => $label)
		{
			$validation->label($field, $label);
		}

		foreach ($this->section()->record()->fields() as $name => $field)
		{
			$field->onValidateDocument($validation, $this);
		}

		if( ! $validation->check() )
		{
			throw new Validation_Exception( $validation );
		}
		
		return TRUE;
	}
}