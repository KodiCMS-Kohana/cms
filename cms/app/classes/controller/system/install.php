<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_System_Install extends Controller_System_Template 
{

	public $template = 'layouts/frontend';
	
	public $route = 'install';

	public function action_index()
	{
		$this->template->content = View::factory('system/install', array(
			'data' => Session::instance()->get_once( 'install_data', array( ) )
		));
	}

	public function action_go()
	{
		$this->auto_render = FALSE;

		if ( !isset( $_POST['install'] ) )
		{
			throw new  Kohana_Exception( 'Install data not found!' );
		}

		$post = $this->request->post('install');

		$post['db_driver'] = DB_TYPE;
		$post['password'] = Text::random();
		
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
			Messages::errors($e->getMessage());
			Messages::errors($e->errors('validation'));
			$this->go_back();
		}
		
		try 
		{
			$this->_import_shema($post, $db);
			$this->_import_dump($post, $db);
			$this->_create_config($post);
		}
		catch (Exception $e)
		{
			$this->_reset($post, $db);
			Messages::errors($e->getMessage());
			$this->go_back();
		}
		
		$this->go(Arr::get($post, 'admin_dir_name', '') . '/login');
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
		$validation = Validation::factory( $data )
			->rule( 'db_server', 'not_empty' )
			->rule( 'db_user', 'not_empty' )
			->rule( 'db_name', 'not_empty' )
			->rule( 'admin_dir_name', 'not_empty' )
			->rule( 'username', 'not_empty' )
			->rule( 'email', 'not_empty' )
			->rule( 'email', 'email' )
			->label('db_server', __('Database server'))
			->label('db_user', __( 'Database user' ))
			->label('db_password', __( 'Database password' ))
			->label('db_name', __( 'Database name' ))
			->label('admin_dir_name', __( 'Admin dir name' ))
			->label('username', __( 'Administrator username' ))
			->label('email', __( 'Administrator email' ));

		if ( !$validation->check() )
		{
			throw new Validation_Exception($validation);
		}
		
		return $validation;
	}

	protected function _import_shema($post, $db)
	{
		$schema_file = CMSPATH . 'install' . DIRECTORY_SEPARATOR . 'schema.sql';
		
		if ( !file_exists( $schema_file ) )
		{
			throw new  Kohana_Exception( 'Database schema file :file not found!', array(
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
		$dump_file = CMSPATH . 'install' . DIRECTORY_SEPARATOR . 'dump.sql';

		if ( !file_exists( $dump_file ) )
		{
			throw new  Kohana_Exception( 'Database dump file :file not found!', array(
				':file' => $dump_file
			) );
		}

		// Insert SQL dump
		$dump_content = file_get_contents( $dump_file );
		
		$replace = array(
			'__SITE_NAME__' => Arr::get($post, 'site_name'),
			'__EMAIL__' => Arr::get($post, 'email'),
			'__USERNAME__' => Arr::get($post, 'username'),
			'TABLE_PREFIX_' => $post['table_prefix'],
			'__ADMIN_PASSWORD__' => Auth::instance()->hash($post['password']),
			'__DATE__' => date('Y-m-d H:i:s'),
			'__LANG__' => I18n::lang()
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
		$tpl_file = CMSPATH . 'install' . DIRECTORY_SEPARATOR . 'config.tpl';
		
		if ( !file_exists( $tpl_file ) )
		{
			throw new  Kohana_Exception( 'Config template file :file not found!', array(
				':file' => $tpl_file
			) );
		}

		// Insert settings to config.php		
		$tpl_content = file_get_contents( $tpl_file );

		$repl = array(
			'__DB_TYPE__' => $post['db_driver'],
			'__DB_SERVER__' => $post['db_server'],
			'__DB_NAME__' => $post['db_name'],
			'__DB_USER__' => $post['db_user'],
			'__DB_PASS__' => $post['db_password'],
			'__TABLE_PREFIX__' => $post['table_prefix'] . '_',
			'__URL_SUFFIX__' => $post['url_suffix'],
			'__ADMIN_DIR_NAME__' => $post['admin_dir_name'],
			'__LANG__' => I18n::lang(),
			'__TIMEZONE__' => $post['timezone'],
			'__COOKIE_SALT__' => Text::random('alnum', 16)
		);

		$tpl_content = str_replace(
			array_keys( $repl ), array_values( $repl ), $tpl_content
		);

		if ( !file_put_contents( DOCROOT . 'config' . EXT, $tpl_content ) !== FALSE )
		{
			throw new  Kohana_Exception( 'Can\'t write config.php file!' );
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

			try 
			{
				DB::query(Database::INSERT, $sql)
					->execute($db);
			}
			catch (Exception $e)
			{
				echo($e->getMessage());
				continue;
			}
		}
	}
	
	protected function _reset($post, $db)
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
		
		if(  file_exists( DOCROOT . 'config' . EXT ) !== FALSE )
		{
			unlink(DOCROOT . 'config' . EXT);
		}
	}
}