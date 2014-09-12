<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package Datasource
 * @category Hybrid
 */
abstract class DataSource_Hybrid_Field {
	
	const FAMILY_PRIMITIVE = 'primitive';
	const FAMILY_FILE = 'file';
	const FAMILY_SOURCE = 'source';

	const PREFFIX = 'f_';

	/**
	 * Список всех полех, которые есть в системе.
	 * Используется для выбора поля при создании.
	 * 
	 * @return array
	 */
	public static function types()
	{
		return Config::get('fields')->as_array();
	}
	
	/**
	 * Получение списка объектов доступных в системе полей.
	 * Используется в момент создания поля.
	 * 
	 * @return array array([TYPE] => [DataSource_Hybrid_Field], ... )
	 */
	public static function get_empty_fields()
	{
		$filed_types = self::types();
		
		$fields = array();
		foreach ($filed_types as $type => $title)
		{
			if(is_array($title))
			{
				foreach ($title as $type => $title)
				{
					$fields[$type] = DataSource_Hybrid_Field::factory($type);
				}
			}
			else
			{
				$fields[$type] = DataSource_Hybrid_Field::factory($type);
			}
		}
		
		return $fields;
	}

	/**
	 * Фабрика создания поля. 
	 * Используется при создании нового поля и загрузке созданного поля из БД
	 * 
	 * @param string $type
	 * @param array $data
	 * @return \DataSource_Hybrid_Field
	 * @throws Kohana_Exception
	 */
	public static function factory($type, array $data = NULL)
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
	 * Название таблицы раздела, в котором находится поле
	 *
	 * @var string
	 */
	public $ds_table = NULL;
	
	/**
	 * Таблица в которую сохраняются поля
	 * 
	 * @var string
	 */
	public $table = 'dshfields';
	
	/**
	 * Идентификатор поля
	 *
	 * @var integer
	 */
	public $id = NULL;
	
	/**
	 * Идентификатор раздела
	 *
	 * @var integer
	 */
	public $ds_id = NULL;
	
	/**
	 * Идентификатор раздела на который ссылается поле SOURCE
	 *
	 * @var integer
	 */
	public $from_ds = NULL;
	
	/**
	 * @var string
	 */
	public $family;
	
	/**
	 * Тип поля
	 *
	 * @var string
	 */
	public $type;
	
	/**
	 * Ключ поля с преффиксом
	 *
	 * @var string
	 */
	public $name = NULL;
	
	/**
	 * Ключ поля без преффикса
	 *
	 * @var string 
	 */
	public $key = NULL;

	/**
	 * Название поля
	 *
	 * @var string
	 */
	public $header;
	
	/**
	 * Позиция поля
	 *
	 * @var integer
	 */
	public $position;

	/**
	 * Дополнительные параметры поля (Сериализуются)
	 *
	 * @var array
	 */
	protected $_props = array();
	
	/**
	 * @see use_as_document_id()
	 * @var boolean 
	 */
	protected $_use_as_document_id = FALSE;
	
	/**
	 * @see is_sortable()
	 * @var boolean 
	 */
	protected $_is_sortable = FALSE;
	
	/**
	 * @see widget_types()
	 * @var array 
	 */
	protected $_widget_types = NULL;
	
	/**
	 * @see is_required()
	 * @var boolean 
	 */
	protected $_is_required = TRUE;
	
	/**
	 * @see is_indexable()
	 * @var boolean 
	 */
	protected $_is_indexable = TRUE;

	/**
	 * 
	 * @param array $data
	 */
	public function __construct( array $data = NULL) 
	{
		if( ! empty($data) )
		{
			$this->set($data);
		}
		
		$this->type = strtolower(substr(get_called_class(), 24));
		$this->from_ds = (int) $this->from_ds;
		$this->key = str_replace( DataSource_Hybrid_Field::PREFFIX, '', $this->name);
	}
	
	/**
	 * Поулучение списка параметров
	 * 
	 * @return array
	 */
	public function properties()
	{
		return $this->_props;
	}

	/**
	 * Булевые параметры поля
	 * 
	 * @return array
	 */
	public function booleans()
	{
		return array();
	}

	/**
	 * Правила валидации поля при его создании и редактировании.
	 * Используется класс Validation
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
			)
		);
	}
	
	/**
	 * Валидация создаваемого поля.
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
		
		if( ! $array->check() )
		{
			throw new Validation_Exception($array);
		}
		
		return TRUE;
	}
	
	/**
	 * Сеттер. Используется при создании и обновлении параметров поля.
	 * 
	 * Принцип работы:
	 *  
	 *   * Передается массив с данными
	 *   * Данные прогоняются по циклу, если для передаваемого поля есть метод
	 *     set_{$field}, то происходит его вызов и передача в него значения,
	 *     если нет, то $this->{$field} = $value
	 *   * Валидация данных
	 * 
	 * @param array $data
	 * @return \DataSource_Hybrid_Field
	 */
	public function set( array $data )
	{
		$boleans = $this->booleans();
		$boleans[] = 'isreq';

		foreach ($boleans as $key)
		{
			$data[$key] = ! empty($data[$key]) ? TRUE : FALSE;
		}

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

		return $this;
	}

	/**
	 * 
	 * Сеттер. Сохраняет все значения в параметр $this->_props
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
	 * Геттер. Получает значения из параметра $this->_props
	 * 
	 * @param type $key
	 * @return string|NULL
	 * @throws Kohana_Exception
	 */
	public function __get($key)
	{
		return Arr::get($this->_props, $key);
	}
	
	/**
	 * Проверка ключа на существование в параметре $this->_props
	 * 
	 * @param string $key
	 * @return mixed
	 */
	public function __isset( $key )
	{
		return isset($this->_props[$key]);
	}
	
	/**
	 * Удаление значения из параметра $this->_props
	 * 
	 * @param string $key
	 */
	public function __unset( $key )
	{
		unset($this->_props[$key]);
	}
	
	/**
	 * Указывается массив типов Виджетов, ккоторые могут загружаться с данным
	 * полем.
	 * Используется в видеже "Гибридный документ" и "Список Гибридных документов"
	 * в списке полей для текущего поля.
	 * 
	 * @return array|NULL
	 */
	public function widget_types()
	{
		return $this->_widget_types;
	}
	
	/**
	 * Показывать значение поля в спсике документов раздела
	 * 
	 * @param boolean $status
	 * @return \DataSource_Hybrid_Field
	 */
	public function set_in_headline($status = FALSE)
	{
		$this->in_headline = (bool) $status;
		return $this;
	}
	
	/**
	 * Установка индекса для поля
	 * 
	 * @param string $type
	 * @return \DataSource_Hybrid_Field
	 */
	public function set_index($type = DataSource_Hybrid_Field_Factory::INDEX_INDEX)
	{
		$this->index_type = $type;
		return $this;
	}
	
	public function is_indexed()
	{
		return DataSource_Hybrid_Field_Factory::is_index($this);
	}

		/**
	 * Метод используется для присвоения старого значения для поля документа
	 * 
	 * @param DataSource_Hybrid_Document $document
	 */
	public function set_old_value( $document )
	{
		$document->set($this->name, $document->get_old_value($this->name));
		return $this;
	}

	/**
	 * Указание ID раздела в котором находится поле.
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
	 * Присвоение Идентификатора полю
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
	 * Указание позиции поля
	 * 
	 * @param integer $position
	 * @return \DataSource_Hybrid_Field
	 */
	public function set_position( $position ) 
	{
		if (!Valid::numeric($position))
		{
			$position = DataSource_Hybrid_Field_Factory::get_last_position($this->ds_id) + 10;
		}
	
		$this->position = (int) $position;
		
		if($this->position < 0)
		{
			$this->position = 0;
		}

		return $this;
	}
	
	/**
	 * Установка подсказки для поля
	 * 
	 * Происходит очистка текста функцией strip_tags
	 * 
	 * @param string $text
	 * @return \DataSource_Hybrid_Field
	 */
	public function set_hint( $text )
	{
		$this->hint = Text::limit_chars(strip_tags( $text ), 150, '');
		
		return $this;
	}

	/**
	 * Создание поля в БД.
	 * 
	 * @see DataSource_Hybrid_Field_Factory::create_field()
	 * 
	 * @return integer Идентификатор поля
	 */
	public function create() 
	{
		$this->validate();

		$data = array(
			'ds_id' => (int) $this->ds_id, 
			'name' => $this->name, 
			'family' => $this->family, 
			'type' => $this->type, 
			'header' => $this->header,
			'from_ds' => (int) $this->from_ds,
			'props' => serialize($this->_props),
			'position' => (int) $this->position
		);

		$query = DB::insert($this->table)
			->columns(array_keys($data))
			->values($data)
			->execute();

		$this->id = $query[0];

		return $this->id;
	}
	
	/**
	 * Обновление данных поля в БД
	 * 
	 * Данные которые можно обновить:
	 * 
	 * * Название {@see header}
	 * * Ключ {@see name}
	 * * Параметры {@see _props}
	 * * Позиция {@see position}
	 * 
	 * @see DataSource_Hybrid_Field_Factory::update_field()
	 * 
	 * @return array DB::update
	 */
	public function update() 
	{
		$this->validate();

		return DB::update($this->table)
			->set(array(
				'header' => $this->header,
				'props' => serialize( $this->_props ),
				'position' => (int) $this->position,
				'name' => $this->name,
				'from_ds' => (int) $this->from_ds,
			))
			->where('id', '=', $this->id)
			->execute();
	}
	
	/**
	 * Удаление поля из БД
	 * 
	 * @see DataSource_Hybrid_Field_Factory::remove_fields_by_key()
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
	 * Преобразование данных поля в массив
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
	 * Используется для получения значения поля из документа и сохранения в БД
	 * @see DataSource_Hybrid_Record::get_sql()
	 * 
	 * @param DataSource_Hybrid_Document $document
	 * @return array array([field name] => [document value])
	 */
	final public function get_sql( DataSource_Hybrid_Document $document )
	{
		return array($this->name, $document->get($this->name));
	}
	
	/**
	 * Поле может использоваться в качестве Идентификатора документа
	 * Используется в виджете "Гибридный документ"
	 * 
	 * @return boolean
	 */
	public function use_as_document_id()
	{
		return (bool) $this->_use_as_document_id;
	}
	
	/**
	 * Поле может использоваться в сортировке списка документов
	 * 
	 * @return boolean
	 */
	public function is_sortable()
	{
		return (bool) $this->_is_sortable;
	}
	
	/**
	 * Поле может быть обязательным
	 * Используется в шаблоне создания и редактирования поля.
	 * 
	 * @return boolean
	 */
	public function is_required()
	{
		return (bool) $this->_is_required;
	}
	
	/**
	 * Поле может иметь MySQL индекс
	 * 
	 * @return boolean
	 */
	public function is_indexable()
	{
		return (bool) $this->_is_indexable;
	}

	/**
	 * Метод позволяет дополнить запрос к БД в момент генерации данных спсика документов 
	 * или отдельного документа в виджете "Список ГД документов" и "Гибридный документ"
	 * 
	 * @see DataSource_Hybrid_Agent::get_query_props()
	 * 
	 * @param Database_Query $query
	 * @return \Database_Query
	 */
	public function get_query_props(Database_Query $query, DataSource_Hybrid_Agent $agent)
	{
		$query->select(array($this->table_column_key(), $this->id));
	}
	
	/**
	 * 
	 * @return string
	 */
	public function table_column_key()
	{
		return DataSource_Hybrid_Field::PREFFIX . $this->key;
	}

	/**
	 * Сортировка списка по текущему полю.
	 * 
	 * @see DataSource_Hybrid_Agent::_fetch_orders()
	 * 
	 * @param Database_Query $query
	 * @param string $dir (ASC|DESC)
	 */
	public function sorting_condition(Database_Query $query, $dir)
	{
		$query->order_by($this->name, $dir);
	}
	
	/**
	 * Условие фильтрации текущего поля, если оно используется в фильтре виджета
	 * "Список ГД документов"
	 * 
	 * @see DataSource_Hybrid_Agent::_fetch_filters()
	 * 
	 * @param Database_Query $query
	 * @param string $condition
	 * @param string $value
	 * @return type
	 */
	public function filter_condition(Database_Query $query, $condition, $value)
	{
		$query->where($this->name, $condition, $value);
	}
	
	/**
	 * Метод используется в момент вывода данных документа в форме редактирования.
	 * Применяется в том случае, если в момент вывода данных они должны быть приведены
	 * к определенному формату
	 * 
	 * @see DataSource_Hybrid_Document::convert_values()
	 * 
	 * @param string $value
	 * @return mixed $value
	 */
	public function convert_value( $value )
	{
		return $value;
	}
	
	/**
	 * Преобразование значения поля списка документов выводимых в Админ панели.
	 * 
	 * Например, если у нас поле содержит ID связанного документа и мы ходим вывести его заголовок,
	 * то в этом методе мы по ID получаем заголовок и возвращаем его.
	 * 
	 * @see Datasource_Section_Hybrid_Headline::get()
	 * 
	 * @param string $value
	 * @return string
	 */
	public function fetch_headline_value( $value, $document_id )
	{
		return $value;
	}
	
	/**
	 * Шаблон поля, используемый при редактрировании документа.
	 * 
	 * @param string $template
	 * @param DataSource_Hybrid_Document $document
	 * @return View
	 */
	public function backend_template( DataSource_Hybrid_Document $document, $template = NULL )
	{
		if($template === NULL)
		{
			$template = 'datasource/hybrid/document/fields/' . $this->type;
		}
		
		return View::factory($template, array(
			'value' => $document->get($this->name), 
			'field' => $this,
			'doc' => $document
		));
	}
	
	/**
	 * 
	 * @param string $value
	 * @param DataSource_Hybrid_Document $doc
	 * @return bool
	 */
	public function check_unique($value, DataSource_Hybrid_Document $doc) 
	{
		return ! (bool) DB::select($this->name)
			->from($this->ds_table)
			->where($this->name, '=', $value)
			->where('id', '!=', $doc->id)
			->limit(1)
			->execute()
			->count();
	}

	/**************************************************************************
	 * EVENTS
	 **************************************************************************/
	/**
	 * Событие вызываемое в момент присвоения значения полю до валидации
	 * 
	 * 
	 * @param mixed $value
	 * @param DataSource_Hybrid_Document $doc
	 * @return string
	 */
	public function onSetValue( $value, DataSource_Hybrid_Document $doc)
	{
		if( ! $doc->loaded() AND ! empty($this->_props['default']) AND empty($value) )
		{
			return $this->default;
		}
		
		return $value;
	}	

	/**
	 * В момент присвоения полям документа значений происходит обход массива
	 * полей документа и в каждом поле вызов этого метода. Т.е. присвоение значений
	 * происходит в этом методе, присвоение происходит до валидации данных.
	 * 
	 * @see DataSource_Hybrid_Document::read_values()
	 * @see DataSource_Hybrid_Document::read_files()
	 * 
	 * @param array $data
	 * @param DataSource_Hybrid_Document $doc
	 * @return \DataSource_Hybrid_Field
	 */
	public function onReadDocumentValue(array $data, DataSource_Hybrid_Document $document)
	{
		$document->set($this->name, Arr::get($data, $this->name));

		return $this;
	}

	/**
	 * Правила валидации значения поля документа.
	 * При сохранении документа происходит валидация значений его полей. 
	 * Каждое поле прогоняется в цикле и происходит вызов этого метода.
	 * 
	 * @see DataSource_Hybrid_Document::validate()
	 * 
	 * @param Validation $validation
	 * @param DataSource_Hybrid_Document
	 * @return \Validation
	 */
	public function onValidateDocument( Validation $validation, DataSource_Hybrid_Document $doc )
	{
		if($this->isreq === TRUE AND $this->is_required())
		{
			$validation->rule($this->name, 'not_empty');
		}
		
		if( $this->unique === TRUE )
		{
			$validation->rule($this->name, array($this, 'check_unique'), array(':value', $doc));
		}

		return $validation
				->label($this->name, $this->header);
	}
	
	/**
	 * Событие вызываемое в момент создания документа, до сохранения данных в БД
	 * после валидации
	 * 
	 * @see DataSource_Hybrid_Record::initialize_document()
	 * 
	 * @param DataSource_Hybrid_Document $doc
	 */
	public function onCreateDocument(DataSource_Hybrid_Document $doc) {}
	
	/**
	 * Событие вызываемое в момент обновления документа, до сохранения данных в БД
	 * после валидации
	 * 
	 * @see DataSource_Hybrid_Record::document_changed()
	 * 
	 * @param DataSource_Hybrid_Document $old
	 * @param DataSource_Hybrid_Document $new
	 */
	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new) {}
	
	/**
	 * Событие вызываемое в момент удаления документа
	 * 
	 * @param DataSource_Hybrid_Document $doc
	 */
	public function onRemoveDocument( DataSource_Hybrid_Document $doc) {}
	
	/**
	 * Событие вызываемое в момент загрузки контроллера в админ панели
	 */
	public function onControllerLoad() {}

	/**
	 * Тип поля в БД
	 * return string
	 */
	abstract public function get_type();
	
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
		return TRUE;
	}
	
	public function has_access($acl_type = '')
	{
		return ACL::check($this->ds_id . '.field.' . $acl_type);
	}
	
	/**
	 * Пользователь имеет права на создание документа
	 * @param integer $user_id
	 * @return boolean
	 */
	public function has_access_create($user_id = NULL, $check_own = TRUE)
	{
		return (
			$this->has_access('create')
		);
	}
	
	/**
	 * Пользователь имеет права на создание документа
	 * @param integer $user_id
	 * @return boolean
	 */
	public function has_access_view($user_id = NULL, $check_own = TRUE)
	{
		return (
			($check_own === TRUE AND $this->is_creator($user_id))
			AND
			$this->has_access('view')
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
			AND
			$this->has_access('edit')
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
			AND 
			$this->has_access('remove')
		);
	}
}