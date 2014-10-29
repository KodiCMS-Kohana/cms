<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * @package		KodiCMS/Installer
 * @category	Installer
 * @author		ButscHSter
 */
class Installer {

	/**
	 *
	 * @var Validation 
	 */
	protected $_validation = NULL;
	
	/**
	 *
	 * @var Database 
	 */
	protected $_db_instance = NULL;
	
	/**
	 *
	 * @var Config 
	 */
	protected $_config;
	
	/**
	 *
	 * @var Session 
	 */
	protected $_session;
	
	/**
	 * 
	 * @return Config
	 */
	public function config()
	{
		return $this->_config;
	}

	/**
	 * 
	 * @return array
	 */
	public function default_params()
	{
		return array(
			'db_server' => 'localhost',
			'db_port' => 3306,
			'db_user' => 'root',
			'db_name' => 'kodicms',
			'site_name' => CMS_NAME,
			'username' => 'admin',
			'email' => 'admin@yoursite.com',
			'admin_dir_name' => 'backend',
			'url_suffix' => '.html',
			'password_generate' => FALSE,
			'timezone' => date_default_timezone_get(),
			'date_format' => 'd F Y',
			'locale' => I18n::lang()
		);
	}
	
	/**
	 * 
	 * @return array
	 */
	public function cache_types()
	{
		return $this->_config->get('cache_types', array());
	}
	
	/**
	 * 
	 * @return array
	 */
	public function session_types()
	{
		return $this->_config->get('session_types', array());
	}
	
	/**
	 * 
	 * @return array
	 */
	public function database_drivers()
	{
		$database_drivers =  $this->_config->get('database_drivers', array());

		if (version_compare(PHP_VERSION, '5.5.0', '>='))
		{
			unset($database_drivers['mysql']);
		}
		
		return $database_drivers;
	}

	public function __construct()
	{
		$this->_config = Kohana::$config->load('installer');
		$this->_session = Session::instance();
		
		$this->_include_module_observers();
	}
	
	/**
	 * 
	 * @param array $post
	 * @return boolean
	 * @throws Installer_Exception
	 */
	public function install(array $post)
	{
		if (empty($post))
		{
			throw new Installer_Exception('No install data!');
		}
		
		if (isset($post['password_generate']))
		{
			$post['password_field'] = Text::random();
		}

		if (isset($post['admin_dir_name']))
		{
			$post['admin_dir_name'] = URL::title($post['admin_dir_name']);
		}

		if (isset($post['db_port']))
		{
			$post['db_port'] = (int) $post['db_port'];
		}

		date_default_timezone_set($post['timezone']);

		$this->_session->set('install_data', $post);

		$this->_validation = $this->_valid($post);
		
		try
		{
			$this->_db_instance = $this->connect_to_db($post);
		}
		catch (Database_Exception $exc)
		{
			$validation = FALSE;
			switch ($exc->getCode())
			{
				case 1049:
					$this->_validation->error('db_name', 'incorrect');
					$validation = TRUE;
					break;
				case 2:
					$this->_validation
							->error('db_server', 'incorrect')
							->error('db_user', 'incorrect')
							->error('db_password', 'incorrect');

					$validation = TRUE;
					break;
			}

			if ($validation === TRUE)
			{
				throw new Validation_Exception($this->_validation, $exc->getMessage(), NULL, $exc->getCode());
			}
			else
			{
				throw new Database_Exception($exc->getMessage(), NULL, $exc->getCode());
			}
		}
		

		Database::$default = 'install';
		
		Observer::notify('before_install', $post, $this->_validation);

		if (isset($post['empty_database']))
		{
			$this->_reset();
		}

		define('TABLE_PREFIX_TMP', Arr::get($post, 'db_table_prefix', ''));

		$this->_import_shema($post);
		$this->_import_dump($post);
		$this->_install_modules($post);

		Observer::notify('install', $post);

		$this->_create_site_config($post);
		$this->_create_config_file($post);

		return TRUE;
	}

	protected function _include_module_observers()
	{
		if (!is_dir(MODPATH))
		{
			return;
		}

		// Create a new directory iterator
		$path = new DirectoryIterator(MODPATH);

		foreach ($path as $dir)
		{
			if ($dir->isDot())
			{
				continue;
			}

			$file_name = MODPATH . $dir->getBasename() . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'observers' . EXT;
			if (file_exists($file_name))
			{
				include $file_name;
			}
		}
	}

	/**
	 * Создание коннекта к БД
	 * 
	 * @param array $post
	 * @return Database
	 * @throws Validation_Exception
	 */
	public function connect_to_db(array $post)
	{
		$config = Kohana::$config->load('database');

		switch ($post['db_driver'])
		{
			case 'mysql':
				$connection = array(
					'hostname'	 => $post['db_server'] . ':' . $post['db_port'],
					'database'	 => $post['db_name'],
					'username'	 => $post['db_user'],
					'password'	 => $post['db_password'],
					'persistent' => FALSE,
				);
				break;
			case 'mysqli':
				$connection = array(
					'hostname'	 => $post['db_server'],
					'port'		 => $post['db_port'],
					'database'	 => $post['db_name'],
					'username'	 => $post['db_user'],
					'password'	 => $post['db_password'],
					'persistent' => FALSE,
				);
				break;
			case 'pdo':
				$connection = array(
					'dsn'        => 'mysql:host='.$post['db_server'].';port='.$post['db_port'].';dbname='.$post['db_name'],
					'username'   => $post['db_user'],
					'password'   => $post['db_password'],
					'persistent' => FALSE,
				);
				break;
		}
				
		$config->set('install', array(
			'type' => $post['db_driver'],
			'connection' => $connection,
			'table_prefix' => $post['db_table_prefix'],
			'charset' => 'utf8',
			'caching' => FALSE,
			'profiling' => TRUE
		));

		$db = Database::instance('install');
		$db->connect();

		return $db;
	}

	/**
	 * Проверка данных формы
	 * 
	 * @param array $data
	 * @return Validation
	 * @throws Validation_Exception
	 */
	protected function _valid(array $data)
	{
		$cache_types = $this->_config->get('cache_types', array());
		$session_types = $this->_config->get('session_types', array());

		$validation = Validation::factory($data)
			->rule('db_server', 'not_empty')
			->rule('db_user', 'not_empty')
			->rule('db_name', 'not_empty')
			->rule('admin_dir_name', 'not_empty')
			->rule('username', 'not_empty')
			->rule('email', 'not_empty')
			->rule('email', 'email')
			->rule('cache_type', 'not_empty')
			->rule('cache_type', 'in_array', array(':value', array_keys($cache_types)))
			->rule('session_type', 'not_empty')
			->rule('session_type', 'in_array', array(':value', array_keys($session_types)))
			->label('db_server', __('Database server'))
			->label('db_user', __('Database user'))
			->label('db_password', __('Database password'))
			->label('db_name', __('Database name'))
			->label('admin_dir_name', __('Admin dir name'))
			->label('username', __('Administrator username'))
			->label('email', __('Administrator email'))
			->label('password_field', __('Password'))
			->label('cache_type', __('Cache type'))
			->label('session_type', __('Session type'));

		if (!isset($data['password_generate']))
		{
			$validation
				->rule('password_field', 'min_length', array(':value', Kohana::$config->load('auth')->get('password_length')))
				->rule('password_field', 'not_empty')
				->rule('password_confirm', 'matches', array(':validation', ':field', 'password_field'))
				->label('password_confirm', __('Confirm Password'));
		}

		if (!$validation->check())
		{
			throw new Validation_Exception($validation);
		}

		return $validation;
	}

	/**
	 * Импорт схемы БД из файла `schema.sql`
	 * 
	 * @param array $post
	 * @throws Installer_Exception
	 */
	protected function _import_shema(array $post)
	{
		// Merge modules schema.sql
		$schema_content = $this->_merge_module_files('schema.sql');

		if (!empty($schema_content))
		{
			$this->_insert_data($schema_content);
		}
	}

	/**
	 * Импорт данных из файла `dump.sql`
	 * 
	 * @param array $post
	 * @throws Installer_Exception
	 */
	protected function _import_dump(array $post)
	{
		// Merge modules dump.sql
		$dump_content = $this->_merge_module_files('dump.sql');
		
		$replace = array(
			'__EMAIL__'				=> Arr::get($post, 'email'),
			'__USERNAME__'			=> Arr::get($post, 'username'),
			'__ADMIN_PASSWORD__'	=> Auth::instance()->hash($post['password_field']),
			'__DATE__'				=> date('Y-m-d H:i:s'),
			'__LANG__'				=> Model_User::DEFAULT_LOCALE
		);
		
		$dump_content = str_replace(
			array_keys($replace), array_values($replace), $dump_content
		);

		if (!empty($dump_content))
		{
			$this->_insert_data($dump_content);
		}
	}
	
	/**
	 * Запись в БД данных в таблицу config
	 * Значения по умолчанию устанавливаются в конфиг файле `installer`
	 * 
	 * @param array $post
	 */
	protected function _create_site_config(array $post)
	{
		$config_values = $this->_config->get('default_config', array());

		$config_values['site']['title'] = Arr::get($post, 'site_name');
		$config_values['site']['date_format'] = Arr::get($post, 'date_format');
		$config_values['site']['default_locale'] = Arr::get($post, 'locale');
		$config_values['email']['default'] = Arr::get($post, 'email');

		$insert = DB::insert('config', array('group_name', 'config_key', 'config_value'));
		foreach ($config_values as $group => $data)
		{
			foreach ($data as $key => $config)
			{
				$insert->values(array($group, $key, serialize($config)));
			}
		}

		$insert->execute($this->_db_instance);
	}

	/**
	 * Используется для установки данных из модулей
	 * 
	 * Метод проходится по модулям, ищет в них файл install.php, если существует
	 * запускает его и передает массив $post
	 *
	 * @param array $post
	 */
	protected function _install_modules(array $post)
	{
		if (!is_dir(MODPATH))
		{
			return;
		}

		// Create a new directory iterator
		$path = new DirectoryIterator(MODPATH);

		foreach ($path as $dir)
		{
			if ($dir->isDot())
			{
				continue;
			}

			$file_name = MODPATH . $dir->getBasename() . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'install' . EXT;
			if (file_exists($file_name))
			{
				include $file_name;
			}
		}
	}

	/**
	 * Создание конфиг файла
	 * 
	 * @param array $post
	 * @throws Installer_Exception
	 */
	protected function _create_config_file(array $post)
	{
		$tpl_file = INSTALL_DATA . 'config.tpl';

		if (!file_exists($tpl_file))
		{
			throw new Installer_Exception('Config template file :file not found!', array(
				':file' => $tpl_file
			));
		}

		// Insert settings to config.php		
		$tpl_content = file_get_contents($tpl_file);

		$repl = array(
			'__DB_TYPE__'			=> $post['db_driver'],
			'__DB_SERVER__'			=> $post['db_server'],
			'__DB_PORT__'			=> $post['db_port'],
			'__DB_NAME__'			=> $post['db_name'],
			'__DB_USER__'			=> $post['db_user'],
			'__DB_PASS__'			=> $post['db_password'],
			'__TABLE_PREFIX__'		=> $post['db_table_prefix'],
			'__URL_SUFFIX__'		=> $post['url_suffix'],
			'__ADMIN_DIR_NAME__'	=> $post['admin_dir_name'],
			'__TIMEZONE__'			=> $post['timezone'],
			'__COOKIE_SALT__'		=> Text::random('alnum', 16),
			'__CACHE_TYPE__'		=> $post['cache_type'],
			'__SESSION_TYPE__'		=> $post['session_type']
		);

		$tpl_content = str_replace(
			array_keys($repl), array_values($repl), $tpl_content
		);

		$this->_write_config_to_file($tpl_content);
	}

	/**
	 * Вставка SQL строк в БД
	 * 
	 * @param string $sql
	 * @throws Validation_Exception
	 */
	protected function _insert_data($sql)
	{
		try
		{
			Database_Helper::insert_sql($sql);
		}
		catch (Database_Exception $exc)
		{
			switch ($exc->getCode())
			{
				case 1005:
					$this->_validation->error('db_name', 'database_not_empty');
					break;
				case 1146:
				case 1064:
					$this->_validation->error('db_server', 'syntax_error', array(':error' => $exc->getMessage()));
					break;
			}
			
			throw new Validation_Exception($this->_validation, $exc->getMessage(), NULL, $exc->getCode());
		}
	}

	/**
	 * Сбор контента из файла модулей в переменную
	 * 
	 * @param string $filename
	 * @param string $content
	 * @return string
	 */
	protected function _merge_module_files($filename, $content = '')
	{
		// Create a new directory iterator
		$path = new DirectoryIterator(MODPATH);

		foreach ($path as $dir)
		{
			if ($dir->isDot())
			{
				continue;
			}

			$file_name = MODPATH . $dir->getBasename() . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . $filename;
			if (file_exists($file_name))
			{
				$content .= "\n";
				$content .= file_get_contents($file_name);
			}
		}

		return $content;
	}

	/**
	 * Очистка указанной БД от записей
	 */
	protected function _reset()
	{
		DB::query(NULL, 'SET FOREIGN_KEY_CHECKS = 0')
			->execute($this->_db_instance);

		$tables = DB::query(Database::SELECT, 'SHOW TABLES')
			->execute($this->_db_instance);

		foreach ($tables as $table)
		{
			$table = array_values($table);
			$table_name = $table[0];

			DB::query(NULL, 'DROP TABLE `:table_name`')
				->param(':table_name', DB::expr($table_name))
				->execute($this->_db_instance);
		}

		if (file_exists(CFGFATH) !== FALSE)
		{
			$this->_write_config_to_file('');
		}

		DB::query(NULL, 'SET FOREIGN_KEY_CHECKS = 1')
			->execute($this->_db_instance);
	}

	/**
	 * 
	 * @param string $content
	 * @return boolean
	 * @throws Installer_Exception
	 */
	protected function _write_config_to_file($content)
	{
		if (!file_put_contents(CFGFATH, $content) !== FALSE)
		{
			throw new Installer_Exception('Can\'t write config.php file!');
		}

		return TRUE;
	}
}