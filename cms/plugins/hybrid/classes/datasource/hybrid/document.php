<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package Datasource
 * @category Hybrid
 */
class DataSource_Hybrid_Document extends Datasource_Document {
	
	protected $_related_fields = array();
	
	protected $_related_table_name = NULL;

	public function defaults()
	{
		$defaults = parent::defaults();
		
		foreach( $this->section()->custom_fields() as $key => $field )
		{
			$defaults[$key] = $field->default;
		}
		
		return $defaults;
	}

	public function __construct( DataSource_Section $section )
	{
		foreach( $section->custom_fields() as $key => $field )
		{
			$this->_related_fields[$field->name] = NULL;
		}

		parent::__construct($section);

		$this->_related_table_name = $this->table_name() . '_' . $this->_section->id();
	}
	
	public function related_table_name()
	{
		return $this->_related_table_name;
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
		if( array_key_exists($field, $this->_related_fields) )
		{
			return $this->_related_fields[$field];
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
		if(in_array($field, $this->fields()))
		{
			return parent::set($field, $value);
		}
		else if(array_key_exists($field, $this->_related_fields))
		{
			$this->set_field_value($field, $value);
		}
		
		return $this;
	}

	/**
	 * Получение всех значений полей
	 * 
	 * @return array array([Field name] => [value])
	 */
	public function values()
	{
		$values = parent::values();
		return Arr::merge($values, $this->_related_fields);
	}

	/**
	 * Загрузка данных из массива
	 * 
	 * @param array $array Массив значений полей документа
	 * @return \DataSource_Hybrid_Document
	 */
	public function read_values(array $array = NULL) 
	{
		$original_array = $array;
		parent::read_values($array);

		foreach($this->section()->custom_fields() as $field)
		{
			if($field->family == DataSource_Hybrid_Field::FAMILY_FILE )	continue;
		
			$field->onReadDocumentValue($original_array, $this);
			$this->{$field->name} = Arr::get($original_array, $field->name);

			unset($this->_temp_values[$field->name]);
		}

		return $this;
	}
	
	/**
	 * Загрузка файлов из массива
	 * 
	 * @param array $array
	 * @return \DataSource_Hybrid_Document
	 */
	public function read_files( array $array = NULL ) 
	{
		$original_array = $array;
		parent::read_files($array);

		foreach($this->section()->custom_fields() as $key => $field)
		{
			if($field->family != DataSource_Hybrid_Field::FAMILY_FILE) continue;

			if(
				isset($array[$key]) 
			AND 
				Upload::valid( $array[$key] ) 
			AND 
				Upload::not_empty($array[$key]))
			{
				$field->onReadDocumentValue($original_array, $this);

				$this->{$field->name} = Arr::get($array, $field->name);
				
				unset($this->_temp_values[$field->name]);
			}
			else
			{
				$this->{$field->name} = '';
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
		$fields = $this->section()->custom_fields();

		$value = isset($fields[$field]) 
			? $fields[$field]->onSetDocumentValue( $value, $this )
			: $value;

		$this->_original_values[$field] = $this->_related_fields[$field];
		$this->_related_fields[$field] = $value;

		$this->_saved = FALSE;
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
			->select_array( array_keys( $this->_related_fields ))
			->from('dshybrid')
			->join("dshybrid_{$ds_id}", 'left')
				->on("dshybrid_{$ds_id}.id", '=', 'dshybrid.id')
			->where('dshybrid.id', '=', (int) $id)
			->limit(1)
			->execute()
			->current();
				
		if( empty($result) ) return $this;
		
		$this->_loaded = TRUE;
		
		$this->_load_values($result);

		return $this;
	}
	
	protected function _load_values(array $values)
	{
		foreach($this->_related_fields as $key => $value)
		{
			$this->_related_fields[$key] = Arr::get($values, $key);
		}
		
		if ( $this->_loaded )
		{
			$this->_original_values = $this->_related_fields;
		}

		return parent::_load_values($values);
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
		
		foreach($this->section()->custom_fields() as $field)
		{
			$field->onCreateDocument( $this );
		}
		
		$values = $this->_related_fields;
		$values['id'] = $this->_id;

		$query = DB::insert($this->related_table_name())
			->columns(array_keys($values))
			->values(array_values($values))
			->execute();

		return $this->_id;
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

		$old_docuemnt = $this->section()->load($this->_id);
		
		foreach($this->section()->custom_fields() as $field)
		{
			$field->onUpdateDocument( $old_docuemnt, $this );
		}
		
		DB::update($this->related_table_name())
			->set($this->_related_fields)
			->where('id', '=', $this->_id)
			->execute();
		
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
		if( ! $this->loaded() ) return NULL;
		
		foreach($this->section()->custom_fields() as $field)
		{
			$field->onRemoveDocument( $this );
		}
		
		DB::delete($this->related_table_name())
			->where('id', '=', $this->_id)
			->execute();
		
		return parent::remove();
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
	public function validate()
	{
		$values = Arr::merge($this->_temp_values, $this->values());

		$validation = Validation::factory( $values )
			->bind(':temp_values', $this->_temp_values)
			->bind(':original_values', $this->_original_values);
		
		foreach ($this->rules() as $key => $rules)
		{
			$validation->rules($key, $rules);
		}
		
		foreach ($this->labels() as $key => $label)
		{
			$validation->label($key, $label);
		}
	
		$this->_extra_validate($validation);
		
		echo debug::vars($this);
		exit();

		if( ! $validation->check() )
		{
			throw new Validation_Exception( $validation );
		}
		
		return TRUE;
	}
	
	protected function _extra_validate( Validation $validation)
	{
		foreach ($this->section()->custom_fields() as $name => $field)
		{
			$field->onValidateDocument($validation, $this);
		}
	}
}