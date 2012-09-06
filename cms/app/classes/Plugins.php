<?php defined('SYSPATH') or die('No direct access allowed.');

class Plugins {

	static $plugins = array( );
	static $plugins_infos = array( );
	static $updatefile_cache = array( );

	static $javascripts = array( );
	static $stylesheets = array( );

	protected static $_settings = array( );
	protected static $table_name = 'plugin_settings';

	/**
	 * Initialize all activated plugin by including is index.php file
	 */
	static function init()
	{
		self::$plugins = unserialize( Setting::get( 'plugins', 'a:0:{}' ) );

		self::get_settings();

		$modules = array( );
		$files = array( );
		foreach ( self::$plugins as $plugin_id => $tmp )
		{
			$manifest_file = PLGPATH . $plugin_id . DIRECTORY_SEPARATOR . 'manifest.ini';

			if ( file_exists( $manifest_file ) )
			{
				$mainfest = parse_ini_file( $manifest_file, FALSE );
				self::setInfos( $mainfest );

				// Index file
				if ( IS_BACKEND )
					$files[] = PLGPATH . $plugin_id . DIRECTORY_SEPARATOR . 'backend.php';
				else
					$files[] = PLGPATH . $plugin_id . DIRECTORY_SEPARATOR . 'frontend.php';

				$modules[] = PLGPATH . $plugin_id;

				self::addJavascript( $plugin_id, $plugin_id . '.css' );
				self::addStylesheet( $plugin_id, $plugin_id . '.css' );
			}
		}

		Core::modules( Core::modules() + $modules );

		foreach ( $files as $file )
		{
			if ( file_exists( $file ) )
				include($file);
		}
	}

	/**
	 * Set plugin informations (id, title, description, version and website)
	 *
	 * @param infos array Assoc array with plugin informations
	 */
	static function setInfos( $infos )
	{
		self::$plugins_infos[$infos['id']] = (object) $infos;
	}

	/**
	 * Activate a plugin. This will execute the enable.php file of the plugin
	 * when found.
	 *
	 * @param plugin_id string	The plugin name to activate
	 */
	static function activate( $plugin_id )
	{
		self::$plugins[$plugin_id] = 1;
		self::save();

		$enable_file = PLGPATH . $plugin_id . DIRECTORY_SEPARATOR . 'enable.php';

		if ( file_exists( $enable_file ) )
			include($enable_file);
	}

	/**
	 * Deactivate a plugin
	 *
	 * @param plugin_id string	The plugin name to deactivate
	 */
	static function deactivate( $plugin_id )
	{
		if ( isset( self::$plugins[$plugin_id] ) )
		{
			unset( self::$plugins[$plugin_id] );
			self::save();

			$disable_file = PLGPATH . $plugin_id . DIRECTORY_SEPARATOR . 'disable.php';

			if ( file_exists( $disable_file ) )
				include($disable_file);
		}
	}

	/**
	 * Save activated plugins to the setting 'plugins'
	 */
	static function save()
	{
		Setting::saveFromData( array( 'plugins' => serialize( self::$plugins ) ) );
	}

	/**
	 * Find all plugins installed in the plugin folder
	 *
	 * @return array
	 */
	static function findAll()
	{
		$dir = PLGPATH;

		if ( $handle = opendir( $dir ) )
		{
			while ( FALSE !== ($plugin_id = readdir( $handle )) )
			{
				if ( !isset( self::$plugins[$plugin_id] ) && is_dir( $dir . $plugin_id ) && strpos( $plugin_id, '.' ) !== 0 )
				{
					$manifest_file = PLGPATH . $plugin_id . DIRECTORY_SEPARATOR . '/manifest.ini';

					if ( file_exists( $manifest_file ) )
					{
						$manifest = parse_ini_file( $manifest_file, FALSE );

						self::setInfos( $manifest );
					}
				}
			}
			closedir( $handle );
		}

		ksort( self::$plugins_infos );
		return self::$plugins_infos;
	}

	/**
	 * Add a javascript file to be added to the html page for a plugin.
	 * Backend only right now.
	 *
	 * @param $plugin_id    string  The folder name of the plugin
	 * @param $file         string  The path to the javascript file relative to plugin SYSPATH
	 */
	static function addJavascript( $plugin_id, $file )
	{
		if ( file_exists( PLGPATH . $plugin_id . DIRECTORY_SEPARATOR . $file ) )
		{
			self::$javascripts[] = PLUGINS_URL . $plugin_id . '/' . $file;
		}
	}

	static function addStylesheet( $plugin_id, $file )
	{
		if ( file_exists( PLGPATH . $plugin_id . DIRECTORY_SEPARATOR . $file ) )
		{
			self::$stylesheets[] = PLUGINS_URL . $plugin_id . '/' . $file;
		}
	}

	static function hasSettingsPage( $plugin_id )
	{
		$class_name = 'Controller_' . Inflector::camelize( $plugin_id );

		return (method_exists( $class_name, 'action_settings' ));
	}

	static function hasDocumentationPage( $plugin_id )
	{
		$class_name = 'Controller_' . Inflector::camelize( $plugin_id );

		return (method_exists( $class_name, 'action_documentation' ));
	}

	/**
	 * Returns TRUE if a plugin is enabled for use.
	 *
	 * @param string $plugin_id
	 */
	static function isEnabled( $plugin_id )
	{
		if ( array_key_exists( $plugin_id, self::$plugins ) && self::$plugins[$plugin_id] == 1 )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Stores all settings from a name<->value pair array in the database.
	 *
	 * @param array $settings Array of name-value pairs
	 * @param string $plugin_id     The folder name of the plugin
	 */
	static function setAllSettings( $array = NULL, $plugin_id = NULL )
	{
		if ( $array == NULL || $plugin_id == NULL )
		{
			return FALSE;
		}

		$existingSettings = DB::select( 'name' )
				->from( self::$table_name )
				->where( 'plugin_id', '=', $plugin_id )
				->execute()
				->as_array( 'name' );

		$ret = FALSE;

		foreach ( $array as $name => $value )
		{
			if ( array_key_exists( $name, $existingSettings ) )
			{
				$query = DB::update( self::$table_name )
						->set( array( 'value' => $value ) )
						->where( 'name', '=', $name )
						->where( 'plugin_id', '=', $plugin_id );
			}
			else
			{
				$query = DB::insert( self::$table_name )
						->columns( array( 'value', 'name', 'plugin_id' ) )
						->values( array( $value, $name, $plugin_id ) );
			}

			$query->execute();
		}

		Core::cache( 'Database::cache(' . self::$table_name . ')', NULL, -1 );

		return TRUE;
	}

	/**
	 * Allows you to store a single setting in the database.
	 *
	 * @param string $name          Setting name
	 * @param string $value         Setting value
	 * @param string $plugin_id     Plugin folder name
	 */
	static function setSetting( $name = NULL, $value = NULL, $plugin_id = NULL )
	{
		if ( $name === NULL || $value === NULL || $plugin_id === NULL )
			return FALSE;

		$existingSettings = DB::select( 'name' )
				->from( self::$table_name )
				->where( 'plugin_id', '=', $plugin_id )
				->execute()
				->as_array( 'name' );

		if ( in_array( $name, $existingSettings ) )
		{
			$query = DB::update( self::$table_name )
					->set( array( 'value' => $value ) )
					->where( 'name', '=', $name )
					->where( 'plugin_id', '=', $plugin_id );
		}
		else
		{
			$query = DB::insert( self::$table_name )
					->columns( array( 'value', 'name', 'plugin_id' ) )
					->values( array( $value, $name, $plugin_id ) );
		}

		Core::cache( 'Database::cache(' . self::$table_name . ')', NULL, -1 );

		return $query->execute();
	}

	/**
	 * Retrieves all settings for a plugin and returns an array of name-value pairs.
	 * Returns empty array when unsuccessful in retrieving the settings.
	 *
	 * @param <type> $plugin_id
	 */
	static function getAllSettings( $plugin_id = NULL )
	{
		return Arr::get( self::$_settings, $plugin_id );
	}

	/**
	 * Returns the value for a specified setting.
	 * Returns FALSE when unsuccessful in retrieving the setting.
	 *
	 * @param <type> $name
	 * @param <type> $plugin_id
	 */
	static function getSetting( $name = NULL, $plugin_id = NULL, $default = NULL )
	{
		return Arr::path( self::$_settings, $plugin_id . '.' . $name, $default );
	}

	static function get_settings()
	{
		$settings = DB::select()
			->from( self::$table_name )
			->cache_key( self::$table_name )
			->cached()
			->as_object()
			->execute();

		foreach ( $settings as $setting )
		{
			self::$_settings[$setting->plugin_id][$setting->name] = $setting->value;
		}
	}
}

// end Plugin class