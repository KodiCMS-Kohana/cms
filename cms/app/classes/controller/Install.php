<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Install extends Controller {

	public $template = 'layouts/install';

	public function action_index()
	{
		$this->template->data = Session::instance()->get( 'install_data', array( ) );
		$this->template->errors = Session::instance()->get( 'install_errors', array( ) );
		$this->template->exception = Session::instance()->get( 'exception' );
	}

	public function action_go()
	{

		$this->auto_render = false;
		Session::instance()->restart();

		if ( !isset( $_POST['install'] ) )
		{
			throw new Core_Exception( 'Install data not found!' );
		}

		$post = $_POST['install'];
		Session::instance()
				->set( 'install_data', $post );

		$validation = Validation::factory( $post )
				->rule( 'db_server', 'not_empty' )
				->rule( 'db_user', 'not_empty' )
				->rule( 'db_name', 'not_empty' );

		if ( !$validation->check() )
		{
			Session::instance()->set( 'install_errors', $validation->errors() );
			$this->go_back();
		}

		$db = Database::instance( 'install', array(
					'type' => DB_TYPE,
					'connection' => array(
						'hostname' => $post['db_server'],
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
		} catch (Exception $exc)
		{
			Session::instance()->set( 'exception', $exc->getMessage() );
			$this->go_back();
		}

		Session::instance()
				->destroy();

		$driver = 'mysql';
		$schema_file = APPPATH . 'install' . DIRECTORY_SEPARATOR . 'schema.sql';
		$dump_file = APPPATH . 'install' . DIRECTORY_SEPARATOR . 'dump.sql';

		if ( !file_exists( $schema_file ) )
		{
			throw new Core_Exception( 'Database schema file not found!' );
		}

		// Create tables
		$schema_content = file_get_contents( $schema_file );
		$schema_content = str_replace( 'TABLE_PREFIX_', $post['table_prefix'], $schema_content );
		$schema_content = preg_split( '/;(\s*)$/m', $schema_content );

		foreach ( $schema_content as $create_table_sql )
		{
			$create_table_sql = trim( $create_table_sql );

			if ( !empty( $create_table_sql ) )
			{
				DB::query( Database::INSERT, $create_table_sql )->execute( $db );
			}
		}

		if ( !file_exists( $dump_file ) )
		{
			throw new Core_Exception( 'Database dump file not found!' );
		}

		// Insert SQL dump
		$password = '12' . dechex( rand( 100000000, 4294967295 ) ) . 'K';

		function date_incremenator()
		{
			static $cpt = 1;
			$cpt++;
			return date( 'Y-m-d H:i:s', time() + $cpt );
		}

		$dump_content = file_get_contents( $dump_file );
		$dump_content = str_replace( 'TABLE_PREFIX_', $post['table_prefix'], $dump_content );
		$dump_content = str_replace( '__ADMIN_PASSWORD__', sha1( $password ), $dump_content );
		$dump_content = preg_replace_callback( '/__DATE__/m', 'date_incremenator', $dump_content );
		$dump_content = str_replace( '__LANG__', $i18n_lang, $dump_content );
		$dump_content = preg_split( '/;(\s*)$/m', $dump_content );

		foreach ( $dump_content as $insert_sql )
		{
			$insert_sql = trim( $insert_sql );

			if ( !empty( $insert_sql ) )
			{
				DB::query( Database::INSERT, $insert_sql )->execute( $db );
			}
		}

		$tpl_file = APPPATH . 'install' . DIRECTORY_SEPARATOR . 'config.tpl';

		// Insert settings to config.php		
		$tpl_content = file_get_contents( $tpl_file );

		$repl = array(
			'__DB_TYPE__' => $data['db_driver'],
			'__DB_SERVER__' => $data['db_server'],
			'__DB_NAME__' => $data['db_name'],
			'__DB_USER__' => $data['db_user'],
			'__DB_PASS__' => $data['db_password'],
			'__TABLE_PREFIX__' => $data['table_prefix'],
			'__URL_SUFFIX__' => $data['url_suffix'],
		);

		$tpl_content = str_replace(
				array_keys( $repl ), array_values( $repl ), $tpl_content
		);

		if ( !file_put_contents( APPPATH . 'config' . EXT, $tpl_content ) !== false )
		{
			throw new Core_Exception( 'Can\'t write config.php file!' );
		}
	}

}