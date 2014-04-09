<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Plugins
 * @author		ButscHSter
 */
class Plugin_Decorator extends Plugin {
	
	const TABLE_NAME = 'plugins';
	const CACHE_KEY = 'plugins';
	
	/**
	 * Параметры плагина
	 * 
	 * @var array
	 */
	protected $_settings = array();
	
	/**
	 * Информация о плагине
	 * @var array
	 */
	protected $_info = array();

	/**
	 * Параметры по умолчанию
	 * @return array
	 */
	public function default_settings()
	{
		return array();
	}
	
	/**
	 * Правила валидации параметров плагина
	 * 
	 * @return array
	 */
	public function rules()
	{
		return array();
	}
	
	/**
	 * Заголовки параметров
	 * 
	 * @return array
	 */
	public function labels()
	{
		return array();
	}
	
	/**
	 * 
	 * @param string $id
	 * @param array $info
	 * 
	 *		array(
	 *			'title' => 'Plugin name'
	 *		)
	 * 
	 * @throws Plugin_Exception
	 */
	public function __construct( $id, array $info )
	{
		$this->_info['id'] = strtolower($id);
		$this->_info['path'] = PLUGPATH . $this->id() . DIRECTORY_SEPARATOR;
		
		if( ! isset($info['title']) )
		{
			throw new Plugin_Exception('Plugin title for plugin :id not set', array(
				':id' => $this->id()
			));
		}
		
		foreach ($info as $key => $value)
		{
			$this->_info[$key] = $value;
		}
		
		$this->_info['icon'] = file_exists( $this->path() . 'icon.png') 
			? PLUGINS_URL . $this->id() . '/' . 'icon.png'
			: NULL;
		
		if( $this->is_activated() )
		{
			$this->_init();
		}
	}
	
	/**
	 * Идентификатор плагина
	 * @return string
	 */
	public function id()
	{
		return Arr::get($this->_info, 'id');
	}
	
	/**
	 * Название плагина
	 * @return string
	 */
	public function title()
	{
		return Arr::get($this->_info, 'title');
	}
	
	/**
	 * Описание плагина
	 * @return string
	 */
	public function description()
	{
		return Arr::get($this->_info, 'description');
	}
	
	/**
	 * Версия плагина
	 * @return string
	 */
	public function version()
	{
		return Arr::get($this->_info, 'version', '0.0.0');
	}
	
	/**
	 * Автор плагина
	 * @return string
	 */
	public function author()
	{
		return Arr::get($this->_info, 'author');
	}
	
	/**
	 * Путь до папки плагина
	 * @return string
	 */
	public function path()
	{
		return Arr::get($this->_info, 'path');
	}

	/**
	 * Иконка плагина
	 * @return string
	 */
	public function icon()
	{
		return Arr::get($this->_info, 'icon');
	}
	
	/**
	 * Получение значения параметра
	 * 
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get( $key, $default = NULL )
	{
		return Arr::get($this->settings(), $key, $default);
	}	

	/**
	 * Установка параметра
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @return \Plugin_Decorator
	 */
	public function set( $key, $value = NULL )
	{
		$this->_settings[$key] = $value;
		
		return $this;
	}
	
	/**
	 * 
	 * @param array $data
	 * @return \Plugin_Decorator
	 */
	public function set_settings( array $data )
	{
		foreach ($data as $k => $v)
		{
			$this->set($k, $v);
		}
		
		return $this;
	}
	
	/**
	 * Получение списка параметров
	 * @return array
	 */
	public function settings()
	{
		return Arr::merge($this->default_settings(), $this->_settings);
	}
	
	/**
	 * Проверка 
	 * @return boolean
	 */
	public function is_activated()
	{
		return Plugins::is_activated( $this->id() );
	}

	/**
	 * Активация плагина
	 * 
	 * При инсталляции плагина происходит добавление плагина в `Kohana::modules()`,
	 * запуск SQL из файла `plugin_path/install/schema.sql` и запуск файла
	 * `plugin_path/install.php`
	 * 
	 * @observer plugin_install
	 * @return \Plugin_Decorator
	 */
	public function activate()
	{
		$data = array(
			'id' => $this->id(),
			'title' => $this->title(),
			'settings' => serialize( $this->settings() )
		);

		$result = DB::insert( self::TABLE_NAME )
			->columns(array_keys($data))
			->values($data)
			->execute();

		Kohana::modules( Kohana::modules() + array('plugin_' . $this->id() => PLUGPATH . $this->id()) );

		$this->_clear_cache();
		
		$schema_file = $this->path() . 'install' . DIRECTORY_SEPARATOR . 'schema.sql';
		if( file_exists( $schema_file ))
		{
			Database_Helper::insert_sql(file_get_contents($schema_file));
		}
		
		$install_file = $this->path() . 'install' . EXT;

		if( file_exists( $install_file ))
		{
			Kohana::load($install_file);
		}
		
		Observer::notify('plugin_install', $this->id());
		
		Plugins::activate( $this );
	}
	
	/**
	 * Деактивация плагина
	 * 
	 * При деактивации плагина происходит  запуск SQL из 
	 * файла `plugin_path/install/drop.sql` и запуск файла
	 * `plugin_path/uninstall.php`
	 * 
	 * @observer plugin_uninstall
	 * @return \Plugin_Decorator
	 */
	public function deactivate( $run_script = FALSE )
	{
		$this->_status = (bool) DB::delete( self::TABLE_NAME )
			->where('id', '=', $this->id())
			->execute();
		
		Plugins::deactivate( $this );
		
		$drop_file = $this->path() . 'install' . DIRECTORY_SEPARATOR . 'drop.sql';
		if( file_exists( $drop_file ))
		{
			Database_Helper::insert_sql(file_get_contents($drop_file));
		}

		$uninstall_file = $this->path() . 'uninstall' . EXT;
		if($run_script === TRUE AND file_exists( $uninstall_file ))
		{
			Kohana::load($uninstall_file);
		}

		Observer::notify('plugin_uninstall', $this->id());

		return $this->_clear_cache();
	}
	
	/**
	 * Регистрация плагина
	 * 
	 * @return \Plugin_Decorator
	 */
	public function register()
	{
		Plugins::register( $this );
		return $this;
	}
	
	/**
	 * Проверка на наличие у плагина страницы настроек, проверка на 
	 * существование VIEW файла `plugin_path/views/[plugin_id]/settings.php`
	 * 
	 * @return boolean
	 */
	public function has_settings_page()
	{
		return file_exists($this->path() . 'views' . DIRECTORY_SEPARATOR . $this->id() . DIRECTORY_SEPARATOR . 'settings' . EXT);
	}
	
	/**
	 * Сохранение параметров плагина в БД в сериализованном виде
	 * 
	 * @return \Plugin_Decorator
	 */
	public function save_settings()
	{
		$status = (bool) DB::update( self::TABLE_NAME )
			->set(array(
				'settings' => serialize($this->settings())
			))
			->where('id', '=', $this->id())
			->execute();

		return $this->_clear_cache();
	}
	
	/**
	 * Валидация параметров плагина согласно правилам валидации
	 * 
	 * @param array $array
	 * @return boolean|Validation
	 */
	public function validate()
	{
		$validation = Validation::factory( $this->settings() );
		
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
		
		return $this;
	}

	/**
	 * Загрузка параметров плагина из БД
	 * 
	 * @cache_key plugins::plugin::[plugin_id]
	 * @cache Date::DAY
	 * @return \Plugin_Decorator
	 */
	protected function _load_settings()
	{
		$settings = DB::select('settings')
			->from( self::TABLE_NAME )
			->where('id', '=', $this->id())
			->cache_key(Plugin_Decorator::CACHE_KEY . '::plugin::' . $this->id())
			->cached(Date::DAY)
			->limit(1)
			->execute()
			->get('settings');
		
		$this->_settings = ! empty($settings) 
			? unserialize($settings) 
			: array();
		
		return $this;
	}
	
	/**
	 * Инициализация плагина в системе.
	 * Аналогично `init.php` в модулях у плагина вызываются 
	 * `plugin_path/fronend.php` для fronend части и `plugin_path/backend.php`
	 * для backend части.
	 * 
	 * @return \Plugin_Decorator
	 */
	protected function _init()
	{
		$this->_load_settings();
			
		extract( array('plugin' => $this), EXTR_SKIP );

		$frontend_file = $this->path() . 'frontend' . EXT;
		if(  file_exists( $frontend_file ) AND ! IS_BACKEND )
		{
			if(Kohana::$profiling === TRUE)
			{
				$benchmark = Profiler::start('Frontend plugins', $this->title());
			}
		
			include $frontend_file;
			
			if(isset($benchmark))
			{
				Profiler::stop($benchmark);
			}
		}
		
		$backend_file = $this->path() . 'backend' . EXT;
		if(  file_exists( $backend_file ) AND IS_BACKEND )
		{
			if(Kohana::$profiling === TRUE)
			{
				$benchmark = Profiler::start('Backend plugins', $this->title());
			}
			
			include $backend_file;
			
			if(isset($benchmark))
			{
				Profiler::stop($benchmark);
			}
		}
		
		return $this;
	}

	/**
	 * Очистка кеша плагина
	 * 
	 * @return \Plugin_Decorator
	 */
	protected function _clear_cache()
	{
		if(Kohana::$caching === TRUE)
		{
			$cache = Cache::instance();

			$cache->delete('Database::cache('.self::CACHE_KEY . '::list)');
			$cache->delete('Database::cache('.self::CACHE_KEY . '::plugin::' . $this->id() . ')');

			Kohana::cache('Route::cache()', NULL, -1);
			Kohana::cache('Kohana::find_file()', NULL, -1);
		}

		return $this;
	}
	
	/******************************************
	 * 
	 *			 Magic methods
	 * 
	 ******************************************/
	public function __isset($key)
	{
		return isset($this->_settings[$key]);
	}

	public function __set( $key, $value )
	{
		return $this->set( $key, $value );
	}
	
	public function __get( $key )
	{
		return $this->get( $key );
	}
}