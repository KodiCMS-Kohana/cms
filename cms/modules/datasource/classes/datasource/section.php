<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Datasource
 * @category	Section
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
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
			throw new DataSource_Exception_Section('Class :class_name not exists', 
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
	public static function default_icon()
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
		
		if($query == NULL OR ($section = self::load_from_array($query)) === NULL)
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
	 * @return Datasource_Section
	 */
	public static function load_from_array(array $data)
	{
		$section = Kohana::unserialize($data['code']);
		
		$section->_id = $data['id'];
		$section->name = $data['name'];
		$section->description = Arr::get($data, 'description');
		$section->_docs = (int) Arr::get($data, 'docs');
		$section->_is_indexable = (bool) Arr::get($data, 'indexed');
		$section->_created_by_id = (int) Arr::get($data, 'created_by_id');
		$section->_folder_id = (int) Arr::get($data, 'folder_id');
		
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
	 * Иконка раздела
	 * 
	 * @var string
	 */
	public $icon;
	
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
	 * Идентификатор папки раздела
	 * 
	 * @var integer 
	 */
	protected $_folder_id = 0;

	/**
	 * Создатель раздела
	 * 
	 * @var integer
	 */
	protected $_created_by_id = NULL;
	
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
	 * Показывать в корне меню
	 * 
	 * @var boolean
	 */
	protected $_show_in_root_menu = FALSE;
	
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
			throw new DataSource_Exception_Section('Document class :class_name not exists', 
					array(':class_name' => $this->_document_class_name));
		}
	}
	
	/**
	 * 
	 * @param Model_Navigation_Section $parent_section
	 * @return Model_Navigation_Section
	 */
	public function add_to_menu(Model_Navigation_Section $parent_section = NULL)
	{	
		return Datasource_Data_Manager::add_section_to_menu($this, $parent_section);
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
	 * @return integer
	 */
	public function created_by_id()
	{
		return (int) $this->_created_by_id;
	}
	
	/**
	 * @return integer
	 */
	public function folder_id()
	{
		return (int) $this->_folder_id;
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
	 * @return string
	 */
	public function table()
	{
		return $this->_ds_table;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function show_in_root_menu()
	{
		return (bool) $this->_show_in_root_menu;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function icon()
	{
		if (!empty($this->icon))
		{
			return $this->icon;
		}

		$class = get_called_class();
		return $class::default_icon();
	}

	/**
	 * Создание раздела 
	 * 
	 * @param array $values Массив полей раздела
	 *
	 * @return integer Идентификатор раздела
	 * @throws DataSource_Exception_Section
	 */
	public function create(array $values)
	{
		if (!$this->has_access_create())
		{
			throw new DataSource_Exception_Section('You do not have permission to create section');
		}

		$this->name = Arr::get($values, 'name');
		$this->description = Arr::get($values, 'description');
		$this->icon = Arr::get($values, 'icon');
		$this->_is_indexable = (bool) Arr::get($values, 'is_indexable');
		$this->_show_in_root_menu = (bool) Arr::get($values, 'show_in_root_menu');
		$this->_created_by_id = (int) Arr::get($values, 'created_by_id', Auth::get_id());
		$this->_folder_id = (int) Arr::get($values, 'folder_id');
		
		$data = array(
			'type' => $this->_type,
			'indexed' => (bool) $this->_is_indexable,
			'description' => $this->description,
			'name' => $this->name,
			'created_on' => date('Y-m-d H:i:s'),
			'created_by_id' => $this->_created_by_id,
			'folder_id' => $this->_folder_id,
			'code' => Kohana::serialize($this),
		);
		
		$query = DB::insert('datasources')
			->columns(array_keys($data))
			->values(array_values($data))
			->execute();

		$this->_id = $query[0];
		
		if (empty($this->_id))
		{
			throw new DataSource_Exception_Section('Datasource section :name not created', 
					array(':name' => $this->name));
		}
		
		unset($query, $values);
		
		Observer::notify('datasource_after_create', $this->_id);
		
		return $this->_id;
	}
	
	/**
	 * 
	 * @param array $values
	 * @throws Validation_Exception
	 */
	public function values(array $values = array())
	{
		$this->validate($values);

		$this->name = Arr::get($values, 'name');
		$this->description = Arr::get($values, 'description');
		$this->icon = Arr::get($values, 'icon');
		$this->_show_in_root_menu = (bool) Arr::get($values, 'show_in_root_menu');

		if (!empty($values['folder_id']))
		{
			$this->_folder_id = (int) Arr::get($values, 'folder_id');
		}

		if (!empty($values['created_by_id']))
		{
			$this->_created_by_id = (int) $values['created_by_id'];
		}

		$this->set_indexable(Arr::get($values, 'is_indexable', FALSE));
		$this->_headline->set_sorting(Arr::get($values, 'doc_order', array()));
		
		return $this;
	}

	/**
	 * Обновление раздела.
	 * 
	 * При сохранении раздела в БД происходит его сериализация и сохарение данных
	 * в поле "code". Список полей, которые не должын попадать в БД указывается в 
	 * методе {@see _serialize()}
	 * 
	 * @param array $values
	 * @throws DataSource_Exception_Section
	 * @return boolean
	 */
	public function update()
	{
		if (!$this->has_access_edit())
		{
			throw new DataSource_Exception_Section('You do not have permission to update section');
		}

		if (!$this->loaded())
		{
			return FALSE;
		}

		DB::update('datasources')
			->set(array(
				'indexed' => $this->_is_indexable,
				'name' => $this->name,
				'description' => $this->description,
				'updated_on' => date('Y-m-d H:i:s'),
				'created_by_id' => $this->_created_by_id,
				'folder_id' => $this->_folder_id,
				'code' => Kohana::serialize($this)
				))
			->where( 'id', '=', $this->_id )
			->execute();

		$this->update_size();
		
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
		if (!$this->has_access_remove())
		{
			throw new DataSource_Exception_Section('You do not have permission to remove section');
		}

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
	 * 
	 * @param integer $folder_id
	 * @return \Datasource_Section
	 */
	public function move_to_folder($folder_id)
	{
		DB::update('datasources')
			->set(array('folder_id' => (int) $folder_id))
			->where( 'id', '=', $this->_id )
			->execute();
		
		$this->_folder_id = (int) $folder_id;
		
		return $this;
	}

	/**
	 * Создание нового документа
	 * 
	 * @param DataSource_Document $doc
	 * @return NULL|DataSource_Document
	 */
	public function create_document( DataSource_Document $doc ) 
	{
		try
		{
			$doc->create();
		} 
		catch (DataSource_Exception_Document $ex) 
		{
			$doc->onCreateException($ex);
		}
		catch (Kohana_Exception $ex) 
		{
			$doc->onCreateException($ex);
		}

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
		$old = $this->get_document($doc->id);
	
		if (empty($old) OR ! $doc->loaded())
		{
			return FALSE;
		}
			
		try
		{
			$doc->update();
		}
		catch (DataSource_Exception_Document $ex) 
		{
			$doc->onUpdateException($ex);
		}
		catch (Kohana_Exception $ex) 
		{
			$doc->onUpdateException($ex);
		}

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
	public function remove_documents(array $ids = NULL)
	{
		if (empty($ids))
		{
			return $this;
		}
		
		$deleted_documents = array();

		foreach ($ids as $id)
		{
			$document = $this->get_document($id);

			try
			{
				if ($document->loaded())
				{
					$document->remove();
					$deleted_documents[] = $id;
				}
			} 
			catch (DataSource_Exception_Document $ex)
			{
				$document->onRemoveException($ex);
				continue;
			}
		}

		$this->update_size();
		$this->remove_from_index($deleted_documents);
		$this->clear_cache();

		return $this;
	}

	/**
	 * Загрузка документа по ID
	 * 
	 * @param integer $id
	 * @return \DataSource_Document
	 */
	public function get_document($id = NULL)
	{
		$document = $this->get_empty_document();
		if (empty($id))
		{
			return $document;
		}

		return $document->load($id);
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
	 * @param boolean $status
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

		if($status === TRUE)
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
	public function validate(array $array = NULL)
	{
		$validation = Validation::factory($array)
			->rules('name', array(
				array('not_empty')
			))
			->rules('created_by_id', array(
				array('not_empty'),
				array('numeric')
			))
			->label('name', __('Header'))
			->label('created_by_id', __('Author'));

		if (!$validation->check())
		{
			throw new Validation_Exception($validation);
		}
		
		return $this;
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
			$vars['_created_by_id'],
			$vars['_type'], 
			$vars['name'],
			$vars['description'], 
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
	/**
	 * 
	 * @return array
	 */
	public function acl_actions()
	{
		return array(
			array(
				'action' => 'section.view',
				'description' => 'View section'
			),
			array(
				'action' => 'section.edit',
				'description' => 'Edit section'
			),
			array(
				'action' => 'section.remove',
				'description' => 'Remove section'
			),
			array(
				'action' => 'document.view',
				'description' => 'View documents'
			),
			array(
				'action' => 'document.create',
				'description' => 'Create documents'
			),
			array(
				'action' => 'document.edit',
				'description' => 'Edit documents'
			),
			array(
				'action' => 'document.remove',
				'description' => 'Remove documents'
			)
		);
	}

    /**
	 * Пользователь - создатель раздела
	 * 
	 * @param integer $user_id
	 * @return boolean
	 */
	public function is_creator($user_id = NULL)
	{
		if($user_id === NULL)
		{
			$user_id = Auth::get_id();
		}

		return ACL::is_admin($user_id) OR ($this->_created_by_id == (int) $user_id);
	}
	/**
	 * Проверка прав доступа
	 * @param string $acl_type
	 * @return boolean
	 */
	public function has_access($acl_type = 'section.edit', $check_own = TRUE, $user_id = NULL)
	{
		return (
			ACL::check('ds_id.' . $this->id() . '.' . $acl_type)
			OR
			(
				$check_own === TRUE
				AND
				$this->is_creator($user_id)
			)
		);
	}
	
	/**
	 * Проверка прав на редактирование
	 * @return boolean
	 */
	public function has_access_edit($user_id = NULL)
	{
		return $this->has_access('section.edit', TRUE, $user_id);
	}
	
	/**
	 * Проверка прав на редактирование
	 * @return boolean
	 */
	public function has_access_create()
	{
		return ACL::check($this->type() . '.' . 'section.create');
	}
	
	/**
	 * Проверка прав на просмотр
	 * @return boolean
	 */
	public function has_access_view($user_id = NULL)
	{
		return $this->has_access('section.view', TRUE, $user_id);
	}
	
	/**
	 * Проверка прав на удаление
	 * @return boolean
	 */
	public function has_access_remove($user_id = NULL)
	{
		return $this->has_access('section.remove', TRUE, $user_id);
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
	 * @param boolean $state
	 * @return \Datasource_Section
	 */
	public function set_indexable($state)
	{
		$state = (bool) $state;

		if (!$this->loaded())
		{
			$this->_is_indexable = $state;

			return $this;
		}

		if ($state == $this->is_indexable())
		{
			return $this;
		}

		if ($state)
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