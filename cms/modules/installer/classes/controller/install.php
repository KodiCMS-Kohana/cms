<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Install extends Controller_System_Frontend 
{
	public $template = 'layouts/frontend';
	
	public function action_index()
	{
		Assets::js('install', ADMIN_RESOURCES . 'js/install.js', 'global');
		Assets::css('select2', ADMIN_RESOURCES . 'libs/select2/select2.css', 'jquery');
		Assets::js('select2', ADMIN_RESOURCES . 'libs/select2/select2.min.js', 'jquery');

		$this->template->title = __( 'Installation' );

		$data = array(
			'db_driver' => 'mysql',
			'db_server' => 'localhost',
			'db_port' => 3306,
			'db_user' => 'root',
			'site_name' => CMS_NAME,
			'username' => 'admin',
			'email' => 'admin@yoursite.com',
			'admin_dir_name' => 'backend',
			'url_suffix' => '.html',
			'password_generate' => TRUE,
			'timezone' => date_default_timezone_get(),
			'cache_type' => 'sqlite',
			'locale' => I18n::detect_lang()
		);

		$this->template->content = View::factory('install/index', array(
			'data' => Session::instance()->get_once( 'install_data', $data ),
			'env_test' => View::factory('install/env_test'),
			'cache_types' => Kohana::$config->load('installer')->get( 'cache_types', array() )
		));
	}

	public function action_go()
	{
		$this->auto_render = FALSE;
		
		$post = $this->request->post('install');

		if ( empty($post) )
		{
			throw new Installer_Exception( 'No install data!' );
		}

		$post['db_driver'] = DB_TYPE;
		
		if(isset($post['password_generate']))
		{
			$post['password_field'] = Text::random();
		}
		
		date_default_timezone_set( $post['timezone'] );

		Session::instance()
			->set( 'install_data', $post );

		try
		{
			$validation = $this->_valid($post);
			$db = $this->_connect_to_db($post, $validation);
		}
		catch (Validation_Exception $e)
		{
			$this->_show_error($e);
		}
		
		try 
		{
			if(isset($post['empty_database']))
			{
				$this->_reset($db);
			}
			
			$this->_import_shema($post, $db);
			$this->_import_dump($post, $db);
			$this->_create_config($post);
		}
		catch (Exception $e)
		{
			$this->_reset($db);
			$this->_show_error($e);
		}
		
		$this->_complete($post);
	}
	
	protected function _complete($post)
	{
		if(PHP_SAPI == 'cli')
		{
			Minion_CLI::write('==============================================');
			Minion_CLI::write(__('KodiCMS installed succefully'));
			Minion_CLI::write('==============================================');

			$install_data = Session::instance()->get_once('install_data');
			Minion_CLI::write(__('Login: :login', array(':login' => Arr::get($install_data, 'username'))));
			Minion_CLI::write(__('Password: :password', array(':password' => Arr::get($install_data, 'password_field'))));
			exit();
		}
		
		$this->go($post['admin_dir_name'] . '/login');
	}

	protected function _show_error(Exception $e)
	{
		if(PHP_SAPI == 'cli')
		{
			Minion_CLI::write(__(':text | :file [:line]', array(
				':text' => $e->getMessage(),
				':file' => $e->getFile(),
				':line' => $e->getLine()
			)));
			exit();
		}
		
		Messages::errors($e->getMessage());
		$this->go_back();
	}

	protected function _connect_to_db(array $post, $validation)
	{
		$server = $post['db_server'] . ':' . $post['db_port'];
		$db = Database::instance( 'install', array(
			'type' => $post['db_driver'],
			'connection' => array(
				'hostname' => $server,
				'database' => $post['db_name'],
				'username' => $post['db_user'],
				'password' => $post['db_password'],
				'persistent' => FALSE,
			),
			'table_prefix' => $post['table_prefix'],
			'charset' => 'utf8',
			'caching' => FALSE,
			'profiling' => TRUE
		) );

		try
		{
			$db->connect();
			return $db;
		} 
		catch (Database_Exception $exc)
		{
			switch ($exc->getCode())
			{
				case 1049:
					$validation->error( 'db_name' , 'incorrect' );
					break;
				case 2:
					$validation
						->error( 'db_server' , 'incorrect' )
						->error( 'db_user' , 'incorrect' )
						->error( 'db_password' , 'incorrect' );
					break;
			}
			throw new Validation_Exception($validation, $exc->getMessage(), NULL, $exc->getCode());
		}
	}

	protected function _valid(array $data)
	{
		$cache_types = Kohana::$config->load('installer')->get( 'cache_types', array() );

		$validation = Validation::factory( $data )
			->rule( 'db_server', 'not_empty' )
			->rule( 'db_user', 'not_empty' )
			->rule( 'db_name', 'not_empty' )
			->rule( 'admin_dir_name', 'not_empty' )
			->rule( 'username', 'not_empty' )
			->rule( 'email', 'not_empty' )
			->rule( 'email', 'email' )
			->rule( 'cache_type', 'not_empty')
			->rule( 'cache_type', 'in_array', array(':value', array_keys( $cache_types )))
			->label('db_server', __('Database server'))
			->label('db_user', __( 'Database user' ))
			->label('db_password', __( 'Database password' ))
			->label('db_name', __( 'Database name' ))
			->label('admin_dir_name', __( 'Admin dir name' ))
			->label('username', __( 'Administrator username' ))
			->label('email', __( 'Administrator email' ))
			->label('password_field', __( 'Password' ))
			->label('cache_type', __( 'Cache type' ));
		
		if(!isset($data['password_generate']))
		{
			$validation
				->rule('password_field', 'min_length', array(':value', Kohana::$config->load('auth')->get( 'password_length' )))
				->rule('password_field', 'not_empty')
				->rule('password_confirm', 'matches', array(':validation', ':field', 'password_field'))
				->label('password_confirm', __( 'Confirm Password' ));
		}

		if ( !$validation->check() )
		{
			throw new Validation_Exception($validation);
		}
		
		return $validation;
	}

	protected function _import_shema($post, $db)
	{
		$schema_file = INSTALL_DATA . 'schema.sql';
		
		if ( !file_exists( $schema_file ) )
		{
			throw new Installer_Exception( 'Database schema file :file not found!', array(
				':file' => $schema_file
			) );
		}

		// Create tables
		$schema_content = file_get_contents( $schema_file );
		$schema_content = str_replace( 'TABLE_PREFIX_', $post['table_prefix'], $schema_content );

		if ( !empty( $schema_content ) )
		{
			$this->_insert_data($schema_content, $db);
		}
	}
	
	protected function _import_dump($post, $db)
	{
		$dump_file = INSTALL_DATA . 'dump.sql';

		if ( !file_exists( $dump_file ) )
		{
			throw new Installer_Exception( 'Database dump file :file not found!', array(
				':file' => $dump_file
			) );
		}

		// Insert SQL dump
		$dump_content = file_get_contents( $dump_file );
		
		$replace = array(
			'__SITE_NAME__'			=> Arr::get($post, 'site_name'),
			'__EMAIL__'				=> Arr::get($post, 'email'),
			'__USERNAME__'			=> Arr::get($post, 'username'),
			'TABLE_PREFIX_'			=> $post['table_prefix'],
			'__ADMIN_PASSWORD__'	=> Auth::instance()->hash($post['password_field']),
			'__DATE__'				=> date('Y-m-d H:i:s'),
			'__LANG__'				=> Arr::get($post, 'locale'),
		);
		
		$dump_content = str_replace(
			array_keys( $replace ), array_values( $replace ), $dump_content
		);

		if ( !empty( $dump_content ) )
		{
			$this->_insert_data($dump_content, $db);
		}
	}
	
	protected function _create_config($post)
	{
		$tpl_file = INSTALL_DATA . 'config.tpl';
		
		if ( !file_exists( $tpl_file ) )
		{
			throw new Installer_Exception( 'Config template file :file not found!', array(
				':file' => $tpl_file
			) );
		}

		// Insert settings to config.php		
		$tpl_content = file_get_contents( $tpl_file );

		$repl = array(
			'__DB_TYPE__'			=> $post['db_driver'],
			'__DB_SERVER__'			=> $post['db_server'],
			'__DB_NAME__'			=> $post['db_name'],
			'__DB_USER__'			=> $post['db_user'],
			'__DB_PASS__'			=> $post['db_password'],
			'__TABLE_PREFIX__'		=> $post['table_prefix'],
			'__URL_SUFFIX__'		=> $post['url_suffix'],
			'__ADMIN_DIR_NAME__'	=> $post['admin_dir_name'],
			'__TIMEZONE__'			=> $post['timezone'],
			'__COOKIE_SALT__'		=> Text::random('alnum', 16),
			'__CACHE_TYPE__'		=> $post['cache_type']
		);

		$tpl_content = str_replace(
			array_keys( $repl ), array_values( $repl ), $tpl_content
		);

		if ( !file_put_contents( CFGFATH, $tpl_content ) !== FALSE )
		{
			throw new Installer_Exception( 'Can\'t write config.php file!' );
		}
	}
	
	protected function _insert_data($data, $db)
	{
		$data = preg_split( '/;(\s*)$/m', $data );

		foreach($data as $sql)
		{
			if(empty($sql))
			{
				continue;
			}
			
			DB::query(Database::INSERT, $sql)
				->execute($db);
		}
	}
	
	protected function _reset($db)
	{
		DB::query(NULL, 'SET FOREIGN_KEY_CHECKS = 0')
			->execute($db);
		
		$tables = DB::query(Database::SELECT, 'SHOW TABLES')
			->execute($db);
		
		foreach ($tables as $table) 
		{
			$table = array_values($table);
			$table_name = $table[0];

			DB::query(NULL, 'DROP TABLE `:table_name`')
				->param( ':table_name', DB::expr($table_name) )
				->execute($db);
		}
		
		if(  file_exists( CFGFATH ) !== FALSE )
		{
			unlink(CFGFATH);
		}
	}
}