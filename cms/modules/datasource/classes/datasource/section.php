<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Datasource
 */
class Datasource_Section {
	
	/**
	 * Загруженные разделы из БД
	 * @var array 
	 */
	protected static $_cached_sections = array();

	/**
	 * Фабрика создания раздела данных
	 * 
	 * @param string $type Тип раздела
	 * 
	 * @return \Datasource_Section
	 */
	public static function factory($type)
	{
		if( ! self::exists($type) )
		{
			throw new DataSource_Exception('Class :class_name not exists', 
					array(':class_name' => $class));
		}
		
		$class = 'Datasource_Section_' . ucfirst($type);
		return new $class($type);
	}
	
	/**
	 * Проверка класса на существование по типу раздела
	 * 
	 * @param string $type
	 * @return boolean
	 */
	public static function exists($type)
	{
		$class = 'Datasource_Section_' . ucfirst($type);
		
		return class_exists($class);
	}
	
	/**
	 * 
	 * @param string $action
	 * @param integer|string $ds_id
	 * @return string
	 */
	public static function uri($action = 'view', $ds_id = NULL)
	{
		if($action == 'view')
		{
			$uri = Route::get('datasources')->uri(array(
				'controller' => 'data',
				'directory' => 'datasources',
			));
			
			return $ds_id !== NULL 
				? $uri. URL::query(array('ds_id' => (int) $ds_id))
				: $uri;
		}

		return Route::get('datasources')->uri(array(
			'controller' => 'section',
			'directory' => 'datasources',
			'action' => $action,
			'id' => $ds_id
		));
	}

	/**
	 * 
	 * @return string
	 */
	public static function icon()
	{
		return 'folder-open-o';
	}

	/**
	 * Загрузка разедла по ID
	 * 
	 * @param integer $id
	 * @return null|Datasource_Section
	 */
	public static function load( $id ) 
	{
		if ($id === NULL)
		{
			return NULL;
		}
		
		if (isset(self::$_cached_sections[$id]))
		{
			return self::$_cached_sections[$id];
		}
		
		$query = DB::select()
			->from('datasources')
			->where('id', '=', (int) $id)
			->execute()
			->current();
		
		if(($section = self::load_from_array($query)) === NULL)
		{
			return NULL;
		}

		self::$_cached_sections[$id] = $section;
		return $section;
	}
	
	/**
	 * Загрузка разедла из массива данных
	 * 
	 * @param array $data
	 * @return null|Datasource_Section
	 */
	public static function load_from_array(array $data)
	{
		if (empty($data))
		{
			return NULL;
		}
		
		$section = unserialize($data['code']);
		
		$section->_id = $data['id'];
		$section->name = $data['name'];
		$section->description = Arr::get($data, 'description');
		$section->_docs = (int) Arr::get($data, 'docs');
		$section->_is_indexable = (bool) Arr::get($data, 'indexed');
		
		return $section;
	}

	/**
	 * Идентификатор раздела
	 * 
	 * @var integer
	 */
	protected $_id;
	
	/**
	 * Тип раздела
	 * 
	 * @var string
	 */
	protected $_type;
	
	/**
	 * Название раздела
	 * 
	 * @var string
	 */
	public $name;
	
	/**
	 * Описание раздела
	 * 
	 * @var string
	 */
	public $description;
	
	/**
	 * Кол-во документов в разделе
	 * 
	 * @var integer
	 */
	protected $_docs = 0;
	
	/**
	 * Таблица раздела в БД
	 * 
	 * @var string
	 */
	protected $_ds_table;
	
	/**
	 * Индексировать раздел
	 * 
	 * @var boolean
	 */
	protected $_is_indexable = FALSE;
	
	/**
	 * Объект загрузки списка документов 
	 * 
	 * @var Datasource_Section_Headline
	 */
	protected $_headline = NULL;

	/**
	 * Название класса документа
	 * 
	 * @var string 
	 */
	protected $_document_class_name = NULL;
	
	/**
	 * Типы виджетов для которых очищать кеш при обновлении данных в документах
	 * 
	 * @var array
	 */
	protected $_widget_types = array();
	
	/**
	 * 
	 * @param string $type
	 */
	public function __construct( $type ) 
	{
		$this->_type = $type;

		$this->_initialize();
		$this->_init_headline();
		
		if ( ! class_exists( $this->_document_class_name ))
		{
			throw new DataSource_Exception('Document class :class_name not exists', 
					array(':class_name' => $this->_document_class_name));
		}
	}
	
	/**
	 * Возвращает тип раздела
	 * 
	 * @return string
	 */
	public function type()
	{
		return $this->_type;
	}
	
	/**
	 * Возвращает идентификатор раздела
	 * 
	 * @return integer
	 */
	public function id()
	{
		return $this->_id;
	}
	
	/**
	 * Проверка раздела на существование
	 *  
	 * @return boolean
	 */
	public function loaded()
	{
		return $this->_id !== NULL;
	}
	
	/**
	 * Возвращает отбъект списка документов
	 * 
	 * @return Datasource_Section_Headline
	 */
	public function headline()
	{
		return $this->_headline;
	}
	
	/**
	 * Возвращает название таблицы раздела
	 * 
	 * @return Datasource_Section_Headline
	 */
	public function table()
	{
		return $this->_ds_table;
	}

	/**
	 * Создание раздела 
	 * 
	 * @param array $values Массив полей раздела
	 *
	 * @return integer Идентификатор раздела
	 * @throws DataSource_Exception
	 */
	public function create( array $values ) 
	{
		$this->validate($values);

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
		
		if (empty($this->_id))
		{
			throw new DataSource_Exception('Datasource section :name not created', 
					array(':name' => $this->name));
		}
		
		unset($query, $values);
		
		Observer::notify('datasource_after_create', $this->_id);
		
		return $this->_id;
	}
	
	/**
	 * Обновление раздела.
	 * 
	 * При сохранении раздела в БД происходит его сериализация и сохарение данных
	 * в поле "code". Список полей, которые не должын попадать в БД указывается в 
	 * методе {@see _serialize()}
	 * 
	 * @param array $values
	 * @return boolean
	 */
	public function save( array $values = NULL) 
	{
		if ( ! $this->loaded())
		{
			return FALSE;
		}
		
		if (is_array($values))
		{
			$this->validate($values);

			$this->name = Arr::get($values, 'name');
			$this->description = Arr::get($values, 'description');
		
			$this->set_indexable(Arr::get($values, 'is_indexable', FALSE));
			
			$this->_headline->set_sorting(Arr::get($values, 'doc_order', array()));
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
		
		unset($data, $values);
		
		Observer::notify('datasource_after_save', $this->_id);
		
		return TRUE;
	}
	
	/**
	 * Удаление раздела
	 * 
	 * При удалении раздела происходит удаление документов.
	 * 
	 * @return \Datasource_Section
	 */
	public function remove()
	{
		$ids = DB::select('id')
			->from($this->table())
			->where('ds_id', '=', $this->id())
			->execute()
			->as_array(NULL, 'id');

		$this->remove_documents($ids);
		
		DB::delete('datasources')
			->where('id', '=', $this->id())
			->execute();

		$id = $this->_id;
		$this->_id = NULL;
		
		Observer::notify('datasource_after_remove', $id);
		
		return $this;
	}
	
	/**
	 * Создание нового документа
	 * 
	 * @param DataSource_Document $doc
	 * @return DataSource_Document
	 */
	public function create_document( DataSource_Document $doc ) 
	{
		$doc->create();

		if ($doc->loaded())
		{
			$this->update_size();
			$this->add_to_index(array($doc->id));

			$this->clear_cache();
		}
		
		return $doc;
	}
	
	/**
	 * Обновление документа
	 * 
	 * @param DataSource_Document $doc
	 * @return DataSource_Document
	 */	
	public function update_document( DataSource_Document $doc ) 
	{
		$old = $this
			->get_document($doc->id);
	
		if (empty($old) OR ! $doc->loaded())
		{
			return FALSE;
		}
		
		$doc->update();

		if ($old->published != $doc->published) 
		{
			if( $doc->published === TRUE )
			{
				$this->add_to_index(array($old->id));
			}
			else
			{
				$this->remove_from_index(array($old->id));
			}
		} 
		else if ($old->published === TRUE)
		{
			$this->update_index(array($old->id));
		}
		
		$this->clear_cache();

		return $doc;
	}
	
	/**
	 * Удаление документов по ID
	 * 
	 * @see DataSource_Document::remove()
	 * 
	 * @param array $ids
	 * @return \DataSource_Section
	 */
	public function remove_documents( array $ids = NULL  ) 
	{
		if (empty($ids))
		{
			return $this;
		}
		
		foreach ($ids as $id)
		{
			$document = $this->get_empty_document()->load($id);
			if($document->loaded())
			{
				$document->remove();
			}
		}

		$this->update_size();
		$this->remove_from_index($ids);
		$this->clear_cache();

		return $this;
	}
	
	/**
	 * Загрузка документа по ID
	 * 
	 * @param integer $id
	 * @return \DataSource_Document
	 */
	public function get_document($id)
	{
		if( empty($id) )
		{
			return NULL;
		}
		
		return $this->get_empty_document()->load($id);
	}
	
	/**
	 * Получение пустого объекта документа
	 * 
	 * @return \DataSource_Document
	 */
	public function get_empty_document() 
	{
		return new $this->_document_class_name($this);
	}
	
	/**
	 * Публикация документов по ID
	 * 
	 * @param array $ids
	 * @return \Datasource_Section
	 */
	public function publish(array $ids) 
	{
		return $this->_publish($ids, TRUE);
	}

	/**
	 * Снятие документов с публикации по ID
	 * 
	 * @param array $ids
	 * @return \Datasource_Section
	 */
	public function unpublish(array $ids) 
	{
		return $this->_publish($ids, FALSE);
	}
	
	/**
	 * Смена статуса документов по ID.
	 * 
	 * @param array $ids
	 * @param boolean $value
	 * @return \Datasource_Section
	 */
	protected function _publish(array $ids, $status) 
	{
		DB::update($this->_ds_table)
			->set(array(
				'published' => (bool) $status,
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
	 * Обновление поля кол-ва документов в разделе
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
	 * Валидация данных полей раздела
	 * 
	 * @param array $array
	 * @throws Validation_Exception
	 */
	public function validate( array $array )
	{
		$validation = Validation::factory($array)
			->rules('name', array(
				array('not_empty')
			))
			->label('name', __('Header') );
		
		if( ! $validation->check() )
		{
			throw new Validation_Exception( $validation );
		}
	}
	
	/**
	 * Очистка кеша виджетов раздела
	 * 
	 * @return \Datasource_Section
	 */
	public function clear_cache( )
	{
		Datasource_Data_Manager::clear_cache( $this->id(), $this->_widget_types);
		
		return $this;
	}
	
	/**
	 * Вызывается при сохранении раздела в БД
	 * 
	 * @return array
	 */
	public function __sleep()
	{
		return array_keys($this->_serialize());
	}
	
	/**
	 * Список параметров объекта, которые должны сохраняться в БД.
	 * 
	 * @return array
	 */
	protected function _serialize()
	{
		$vars = get_object_vars($this);
		unset(
			$vars['_id'],
			$vars['_docs'],
			$vars['_is_indexable'],
			$vars['_type'], 
			$vars['name'],
			$vars['description'],
			$vars['_headline'], 
			$vars['_document_class_name'],
			$vars['_ds_table'], 
			$vars['_widget_types']
		);
		
		return $vars;
	}

	/**
	 * При заггрузке данных раздела из БД происходит десериализация объекта из поля
	 * "Code", что по сути является загрузкой раздела и в этот момент вызывается этот метод.
	 * 
	 * Если после загрузки раздела необходимо восстановить связи с другими объектами, их 
	 * необходимо описывать в методе {@see _initialize()}
	 */
	public function __wakeup()
	{
		$this->_initialize();
		
		if($this->_headline === NULL)
		{
			$this->_init_headline();
		}
		$this->_headline->set_section($this);
	}
	
	/**
	 * Инициализация данных раздела при создании или загрузке
	 * @throws Kohana_Exception
	 */
	protected function _initialize()
	{
		$this->_docs = 0;
		$this->_is_indexable = FALSE;
		
		$this->_document_class_name = 'Datasource_' . ucfirst($this->type()) . '_Document';
	}
	
	protected function _init_headline()
	{
		$headline_class = 'Datasource_Section_' . ucfirst($this->type()) . '_Headline';
		if(!class_exists($headline_class))
		{
			throw new Kohana_Exception('Headline class :class not found', array(
				':class' => $headline_class
			));
		}
		
		$this->_headline = new $headline_class();
		$this->_headline->set_section($this);
	}
	
	/**************************************************************************
	 * ACL
	 **************************************************************************/
	public function has_access($acl_type = 'section.edit')
	{
		return ACL::check($this->type() . $this->id() . '.' . $acl_type);
	}

	/**************************************************************************
	 * Search indexation
	 **************************************************************************/

	/**
	 * Состояние поисковой индексации раздела
	 * 
	 * @return boolean
	 */
	public function is_indexable()
	{
		return (bool) $this->_is_indexable;
	}

	/**
	 * Смена статуса поисковой индексации раздела
	 * 
	 * @param boolean $newState
	 * @return \Datasource_Section
	 */
	public function set_indexable( $state ) 
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
	 * Загрузка списка документов по ID в формате для индексации
	 * 
	 * @param integer|array $id
	 * @return array array([ID] => array('id', 'header', 'content', 'intro'), ...)
	 */
	public function get_indexable_documents( array $id = NULL ) 
	{
		$result = DB::select('id', 'header', 'content', 'intro')
			->from($this->_ds_table)
			->where('published', '=', 1)
			->where('ds_id', '=', $this->_id);
		
		if( ! empty($id) )
		{
			$result->where('id', 'in', $id);
		}

		return $result
			->execute()
			->as_array('id');
	}
	
	/**
	 * Добавление документов раздела в поисковый индекс
	 * 
	 * При передаче массива ID другие параметры указывать не нужно, т.к. они 
	 * загрузятся автоматически 
	 * 
	 * @see Datasource_Section::get_indexable_documents()
	 * 
	 * @param array $ids Индентификаторы документов
	 * @param string $header Заголовок документа
	 * @param string $content Индексируемый текст
	 * @param string $intro Описание документа
	 * @return \Datasource_Section
	 */
	public function add_to_index(array $ids = array(), $header = NULL, $content = NULL, $intro = NULL, array $params = NULL) 
	{
		if( ! $this->is_indexable())
		{
			return $this;
		}

		if(count($ids) == 1 AND $header !== NULL)
		{
			Search::instance()->add_to_index('ds_' . $this->id(), $ids[0], $header, $content, $intro, $params);
		}
		else
		{
			$docs = $this->get_indexable_documents($ids);
			
			foreach($docs as $doc)
			{
				Search::instance()->add_to_index('ds_' . $this->id(), $doc['id'], $doc['header'], $doc['content'], $doc['intro'], Arr::get($doc, 'params'));
			}
		}
	}
	
	/**
	 * Обновление поискового индекса документов раздела
	 * 
	 * При передаче массива ID другие параметры указывать не нужно, т.к. они 
	 * загрузятся автоматически 
	 * 
	 * @see Datasource_Section::get_indexable_documents()
	 * 
	 * @param array $ids
	 * @param string $header
	 * @param string $content
	 * @param string $intro
	 * @return \Datasource_Section
	 */
	public function update_index(array $ids = array(), $header = NULL, $content = NULL, $intro = NULL, array $params = NULL) 
	{
		if( ! $this->is_indexable())
		{
			return $this;
		}

		return $this->add_to_index($ids, $header, $content, $intro, $params);
	}
	
	/**
	 * Удаление документов из поискового индекса
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
}