<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * @package		KodiCMS/Plugins
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Plugin  {
	
	const TABLE_NAME = 'plugins';
	const CACHE_KEY = 'plugins';
	
	/**
	 * 
	 * @param string $id
	 * @param array $info
	 * @return \Plugin_Decorator
	 * @throws Plugin_Exception
	 */
	public static function factory($id, array $info)
	{
		$class = 'Plugin_' . $id;

		if (class_exists($class))
		{
			return new $class($id, $info);
		}

		return new Plugin_Decorator($id, $info);
	}

	/**
	 * Информация о плагине
	 * @var array
	 */
	protected $_info = array(
		'author' => CMS_NAME,
		'version' => '1.0.0'
	);
	
	/**
	 *
	 * @var boolean 
	 */
	protected $_is_installable = TRUE;


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
	public function __construct($id, array $info)
	{
		$this->_info['id'] = strtolower($id);
		$this->_info['path'] = PLUGPATH . $this->id() . DIRECTORY_SEPARATOR;

		if (!isset($info['title']))
		{
			throw new Plugin_Exception('Plugin title for plugin :id not set', array(
				':id' => $this->id()
			));
		}

		$this->_is_installable = isset($info['required_cms_version']) 
			? version_compare(CMS_VERSION, $info['required_cms_version'], '>=') 
			: TRUE;

		foreach ($info as $key => $value)
		{
			$this->_info[$key] = $value;
		}

		$this->_info['icon'] = file_exists($this->path() . 'icon.png') 
			? PLUGINS_URL . $this->id() . '/' . 'icon.png' 
			: NULL;

		if ($this->is_activated())
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
	 * Требуемая версия CMS
	 * @return string
	 */
	public function required_cms_version()
	{
		return Arr::get($this->_info, 'required_cms_version', CMS_VERSION);
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
		return PLUGPATH . $this->id() . DIRECTORY_SEPARATOR;
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
	 * Проверка активирован ли плагин
	 * @return boolean
	 */
	public function is_activated()
	{
		return Plugins::is_activated( $this->id() );
	}
	
	/**
	 * Проверка на возможность активации плагина
	 * @return boolean
	 */
	public function is_installable()
	{
		return $this->_is_installable;
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
		if (!$this->is_installable())
		{
			throw new Plugin_Exception('Plugin can not be installed. The required version of the CMS: :required_version. Version of your CMS is: :current_version.', array(
				':required_version' => $this->required_cms_version(),
				':current_version' => CMS_VERSION
			));
		}

		$data = array(
			'id' => $this->id(),
			'title' => $this->title(),
			'settings' => Kohana::serialize( $this->settings() )
		);

		$result = DB::insert( self::TABLE_NAME )
			->columns(array_keys($data))
			->values($data)
			->execute();

		Kohana::modules( Kohana::modules() + array('plugin_' . $this->id() => PLUGPATH . $this->id()) );

		$this->_clear_cache();
		
		$schema_file = $this->path() . 'install' . DIRECTORY_SEPARATOR . 'schema.sql';
		if (file_exists($schema_file))
		{
			Database_Helper::insert_sql(file_get_contents($schema_file));
		}

		$install_file = $this->path() . 'install' . EXT;

		if (file_exists($install_file))
		{
			Kohana::load($install_file);
		}

		Observer::notify('plugin_install', $this->id());
		$this->_on_activate();
	
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
	public function deactivate($run_script = FALSE)
	{
		$this->_status = (bool) DB::delete( self::TABLE_NAME )
			->where('id', '=', $this->id())
			->execute();
		
		Plugins::deactivate( $this );

		$uninstall_file = $this->path() . 'uninstall' . EXT;
		if ($run_script === TRUE AND file_exists($uninstall_file))
		{
			Kohana::load($uninstall_file);
		}

		$drop_file = $this->path() . 'install' . DIRECTORY_SEPARATOR . 'drop.sql';
		if (file_exists($drop_file))
		{
			Database_Helper::insert_sql(file_get_contents($drop_file));
		}

		Observer::notify('plugin_uninstall', $this->id());

		$this->_on_deactivate();

		return $this->_clear_cache();
	}
	
	/**
	 * Регистрация плагина
	 * 
	 * @return \Plugin_Decorator
	 */
	public function register()
	{
		Plugins::register($this);
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

		extract(array('plugin' => $this), EXTR_SKIP);

		$frontend_file = $this->path() . 'frontend' . EXT;
		if (file_exists($frontend_file) AND ! IS_BACKEND)
		{
			if (Kohana::$profiling === TRUE)
			{
				$benchmark = Profiler::start('Frontend plugins', $this->title());
			}

			include $frontend_file;

			if (isset($benchmark))
			{
				Profiler::stop($benchmark);
			}
		}

		$backend_file = $this->path() . 'backend' . EXT;

		if (file_exists($backend_file) AND IS_BACKEND)
		{
			if (Kohana::$profiling === TRUE)
			{
				$benchmark = Profiler::start('Backend plugins', $this->title());
			}

			include $backend_file;

			if (isset($benchmark))
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
		if (Kohana::$caching === TRUE)
		{
			$cache = Cache::instance();
			$cache->delete('Database::cache(' . self::CACHE_KEY . '::list)');

			register_shutdown_function(array('Cache', 'clear_file'));
		}

		return $this;
	}
	
	protected function _on_activate() {}
	protected function _on_deactivate() {}
}