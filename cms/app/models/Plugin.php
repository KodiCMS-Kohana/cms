<?php if(!defined('CMS_ROOT')) die;

/**
 * Flexo CMS - Content Management System. <http://flexo.up.dn.ua>
 * Copyright (C) 2008 Maslakov Alexander <jmas.ukraine@gmail.com>
 * Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>
 * Copyright (C) 2008 Martijn van der Kleijn <martijn.niji@gmail.com>
 *
 * This file is part of Flexo CMS.
 *
 * Flexo CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Flexo CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Flexo CMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Flexo CMS has made an exception to the GNU General Public License for plugins.
 * See exception.txt for details and the full text.
 */

/**
 * @package Flexo
 * @subpackage models
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

/**
 * class Plugin 
 *
 * Provide a Plugin API to make frog more flexible
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since Flexo version 0.9
 */
class Plugin
{
	static $plugins = array();
	static $plugins_infos = array();
    static $updatefile_cache = array();
	
	static $controllers = array();
    static $javascripts = array();
	static $stylesheets = array();
	
	static $nav = array();

	/**
	 * Initialize all activated plugin by including is index.php file
	 */
	static function init()
	{
		self::$plugins = unserialize(Setting::get('plugins'));
		
		foreach (self::$plugins as $plugin_id => $tmp)
		{
			$manifest_file = PLUGINS_ROOT.DIRECTORY_SEPARATOR.$plugin_id.DIRECTORY_SEPARATOR. 'manifest.ini';
			
			if (file_exists($manifest_file))
			{
				$mainfest = parse_ini_file($manifest_file, false);
				self::setInfos( $mainfest );
				
				// Lang file
				$lang_file = PLUGINS_ROOT.DIRECTORY_SEPARATOR.$plugin_id.DIRECTORY_SEPARATOR.'i18n'.DIRECTORY_SEPARATOR . I18n::getLocale().'-message.php';
				
				if (file_exists($lang_file))
				{
					$array = include($lang_file);
					I18n::add($array);
				}
				
				// Index file
				if (strpos(CURRENT_URI, ADMIN_DIR_NAME) === 1)
					$file = PLUGINS_ROOT.DIRECTORY_SEPARATOR.$plugin_id.DIRECTORY_SEPARATOR . 'backend.php';
				else
					$file = PLUGINS_ROOT.DIRECTORY_SEPARATOR.$plugin_id.DIRECTORY_SEPARATOR . 'frontend.php';
				
				if (file_exists($file))
					include($file);
			}
		}
	}

	/**
	 * Set plugin informations (id, title, description, version and website)
	 *
	 * @param infos array Assoc array with plugin informations
	 */
	static function setInfos($infos)
	{
		self::$plugins_infos[$infos['id']] = (object) $infos;
	}

	/**
	* Activate a plugin. This will execute the enable.php file of the plugin
	* when found.
	*
	* @param plugin_id string	The plugin name to activate
	*/
	static function activate($plugin_id)
	{
		self::$plugins[$plugin_id] = 1;
		self::save();
		
		$enable_file = PLUGINS_ROOT.DIRECTORY_SEPARATOR.$plugin_id.DIRECTORY_SEPARATOR . 'enable.php';
		
		if (file_exists($enable_file))
			include($enable_file);
	}
	
	/**
	 * Deactivate a plugin
	 *
	 * @param plugin_id string	The plugin name to deactivate
	 */
	static function deactivate($plugin_id)
	{
		if (isset(self::$plugins[$plugin_id]))
		{
			unset(self::$plugins[$plugin_id]);
			self::save();
			
			$disable_file = PLUGINS_ROOT.DIRECTORY_SEPARATOR.$plugin_id.DIRECTORY_SEPARATOR . 'disable.php';
			
			if (file_exists($disable_file))
				include($disable_file);
		}
	}

	/**
	 * Save activated plugins to the setting 'plugins'
	 */
	static function save()
	{
		Setting::saveFromData(array('plugins' => serialize(self::$plugins)));
	}

	/**
	 * Find all plugins installed in the plugin folder
	 *
	 * @return array
	 */
	static function findAll()
	{
		$dir = PLUGINS_ROOT.DIRECTORY_SEPARATOR;
		
		if( $handle = opendir($dir) )
		{
			while( false !== ($plugin_id = readdir($handle)) )
			{
				if( !isset(self::$plugins[$plugin_id]) && is_dir($dir.$plugin_id) && strpos($plugin_id, '.') !== 0 )
				{
					$manifest_file = PLUGINS_ROOT.DIRECTORY_SEPARATOR.$plugin_id.DIRECTORY_SEPARATOR . '/manifest.ini';
					
					if( file_exists($manifest_file) )
					{
						$manifest = parse_ini_file($manifest_file, false);
						
						self::setInfos( $manifest );
					}
				}
			}
			closedir($handle);
		}
		
		ksort(self::$plugins_infos);
		return self::$plugins_infos;
	}

	/**
	* Check the file mentioned as update_url for the latest plugin version available.
	* Messages that can be returned:
	* unknown - returned if the plugin doesn't provide an update url
	* latest  - returned if the plugin version matches the version number registerd at the url
	* error   - returned if the update url could not be reached or for any other reason
	*
	* @param plugin     object A plugin object.
	*
	* @return           string The latest version number or a localized message.
	*/
    static function checkLatest($plugin)
    {
        $data = null;
		
        if (!defined('CHECK_UPDATES') || !CHECK_UPDATES)
            return 'unknown';
		
        // Check if plugin has update file url set
        if ( ! isset($plugin->update_url) )
            return 'unknown';
		
        // Check if update file was already cached and is no older than 30 minutes
        if (array_key_exists($plugin->update_url, self::$updatefile_cache) && (self::$updatefile_cache[$plugin->update_url]['time'] + 30 * 60) < time())
		{
            unset(self::$updatefile_cache[$plugin->update_url]);
        }
		
        if (!array_key_exists($plugin->update_url, self::$updatefile_cache))
		{
            // Read and cache the update file
            if (!defined('CHECK_TIMEOUT'))
				define('CHECK_TIMEOUT', 5);
			
            $ctx = stream_context_create(array('http' => array('timeout' => CHECK_TIMEOUT)));
			
            if ( ! ($data = file_get_contents($plugin->update_url, 0, $ctx)) )
                return 'error';
			
            self::$updatefile_cache[$plugin->update_url] = array('time' => time(), 'data' => $data);
        }
		
        $xml = simplexml_load_string(self::$updatefile_cache[$plugin->update_url]['data']);
		
        foreach ($xml as $node)
		{
            if ($plugin->id == $node->id)
			{
                if ($plugin->version == $node->version)
                    return 'latest';
                else
                    return (string) $node->version;
			}
        }
		
        return 'error';
    }


	/**
	 * Add a controller to the administration layout
	 *
	 * @param plugin_id     string  The folder name of the plugin
	 * @param permissions   string  List of roles that will have the tab displayed
	 *                              separate by coma ie: 'administrator,developer'
	 *
	 * @return void
	 */
	static function addController($plugin_id, $class_name, $permissions = null)
	{
		if ($permissions !== null && AuthUser::hasPermission($permissions))
		{
			$class_name = Inflector::camelize($class_name).'Controller';
			
			$class_file = PLUGINS_ROOT.DIRECTORY_SEPARATOR.$plugin_id.DIRECTORY_SEPARATOR . $class_name.'.php';
			
			if (file_exists($class_file))
			{
				self::$controllers[$plugin_id] = (object) array(
					'class_name' => $class_name,
					'file'       => $class_file
				);
		
				AutoLoader::addFile($class_name, $class_file);
			}
			else
				throw new Exception('Plugin controller file '. $class_file .' was not found!');
		}
	}
    

	/**
	* Add a javascript file to be added to the html page for a plugin.
	* Backend only right now.
	*
	* @param $plugin_id    string  The folder name of the plugin
	* @param $file         string  The path to the javascript file relative to plugin root
	*/
    static function addJavascript($plugin_id, $file)
    {
        if (file_exists(PLUGINS_ROOT.DIRECTORY_SEPARATOR.$plugin_id.DIRECTORY_SEPARATOR . $file))
        {
            self::$javascripts[] = PLUGINS_URL.$plugin_id.'/'.$file;
        }
    }
	
	static function addStylesheet($plugin_id, $file)
    {
        if (file_exists(PLUGINS_ROOT.DIRECTORY_SEPARATOR.$plugin_id.DIRECTORY_SEPARATOR . $file))
        {
            self::$stylesheets[] = PLUGINS_URL.$plugin_id.'/'.$file;
        }
    }
    
    
    static function hasSettingsPage($plugin_id)
    {
        $class_name = Inflector::camelize($plugin_id).'Controller';
        
        return (array_key_exists($plugin_id, self::$controllers) && method_exists($class_name, 'settings'));
    }
    
    
    static function hasDocumentationPage($plugin_id)
    {
        $class_name = Inflector::camelize($plugin_id).'Controller';
        
        return (array_key_exists($plugin_id, self::$controllers) && method_exists($class_name, 'documentation'));
    }

	/**
	* Returns true if a plugin is enabled for use.
	*
	* @param string $plugin_id
	*/
    static function isEnabled($plugin_id)
    {
        if (array_key_exists($plugin_id, self::$plugins) && self::$plugins[$plugin_id] == 1)
            return true;
        else
            return false;
    }

	/**
	* Stores all settings from a name<->value pair array in the database.
	*
	* @param array $settings Array of name-value pairs
	* @param string $plugin_id     The folder name of the plugin
	*/
    static function setAllSettings($array=null, $plugin_id=null)
    {
        if ($array == null || $plugin_id == null) return false;

        $conn = Record::getConnection();
        $tablename = TABLE_PREFIX.'plugin_settings';
        $plugin_id = $conn->quote($plugin_id);

        $existingSettings = array();

        $sql = "SELECT name FROM $tablename WHERE plugin_id=$plugin_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        while ($settingname = $stmt->fetchColumn())
            $existingSettings[$settingname] = $settingname;

        $ret = false;

        foreach ($array as $name => $value)
        {
            if (array_key_exists($name, $existingSettings))
            {
                $name = $conn->quote($name);
                $value = $conn->quote($value);
                $sql = "UPDATE $tablename SET value=$value WHERE name=$name AND plugin_id=$plugin_id";
            }
            else
            {
                $name = $conn->quote($name);
                $value = $conn->quote($value);
                $sql = "INSERT INTO $tablename (value, name, plugin_id) VALUES ($value, $name, $plugin_id)";
            }

            $stmt = $conn->prepare($sql);
            $ret = $stmt->execute();
        }

        return $ret;
    }

	/**
	* Allows you to store a single setting in the database.
	*
	* @param string $name          Setting name
	* @param string $value         Setting value
	* @param string $plugin_id     Plugin folder name
	*/
    static function setSetting($name=null, $value=null, $plugin_id=null)
    {
        if ($name === null || $value === null || $plugin_id === null)
			return false;
		
        $conn = Record::getConnection();
        $tablename = TABLE_PREFIX.'plugin_settings';
        $plugin_id = $conn->quote($plugin_id);
		
        $existingSettings = array();
		
        $sql = "SELECT name FROM $tablename WHERE plugin_id=$plugin_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($plugin_id));
		
        while ($settingname = $stmt->fetchColumn())
            $existingSettings[$settingname] = $settingname;
		
        if (in_array($name, $existingSettings))
        {
            $name = $conn->quote($name);
            $value = $conn->quote($value);
            $sql = "UPDATE $tablename SET value=$value WHERE name=$name AND plugin_id=$plugin_id";
        }
        else
        {
            $name = $conn->quote($name);
            $value = $conn->quote($value);
            $sql = "INSERT INTO $tablename (value, name, plugin_id) VALUES ($value, $name, $plugin_id)";
        }
		
        $stmt = $conn->prepare($sql);
        return $stmt->execute();
    }

	/**
	* Retrieves all settings for a plugin and returns an array of name-value pairs.
	* Returns empty array when unsuccessful in retrieving the settings.
	*
	* @param <type> $plugin_id
	*/
    static function getAllSettings($plugin_id=null)
    {
        if ($plugin_id == null)
			return false;
		
        $conn = Record::getConnection();
        $tablename = TABLE_PREFIX.'plugin_settings';
        $plugin_id = $conn->quote($plugin_id);

        $settings = array();

        $sql = "SELECT name,value FROM $tablename WHERE plugin_id=$plugin_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        while ($obj = $stmt->fetchObject())
            $settings[$obj->name] = $obj->value;

        return $settings;
    }

	/**
	* Returns the value for a specified setting.
	* Returns false when unsuccessful in retrieving the setting.
	*
	* @param <type> $name
	* @param <type> $plugin_id
	*/
    static function getSetting($name=null, $plugin_id=null)
    {
        if ($name == null || $plugin_id == null)
			return false;
		
        $conn = Record::getConnection();
        $tablename = TABLE_PREFIX.'plugin_settings';
        $plugin_id = $conn->quote($plugin_id);
        $name = $conn->quote($name);
		
        $existingSettings = array();
		
        $sql = "SELECT value FROM $tablename WHERE plugin_id=$plugin_id AND name=$name LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
		
        if ($value = $stmt->fetchColumn())
			return $value;
        else
			return false;
    }
	
	/**
	* Add item to navigation
	*/
	static function addNav($nav = 'Other', $name, $uri, $permissions = array('administrator'), $priority = 0 )
	{
		if (AuthUser::hasPermission( $permissions ))
		{
			if (!isset(self::$nav[$nav]))
			{
				self::$nav[$nav] = (object) array(
					'is_current' => false,
					'items'      => array()
				);
			}
			
			if (isset(self::$nav[$nav]->items[$priority]))
			{
				while( isset(self::$nav[$nav]->items[$priority]))
					$priority++;
			}
			
			self::$nav[$nav]->items[$priority] = (object) array(
				'name' => $name,
				'uri' => $uri,
				'is_current' => false,
				'priority'   => $priority
			);
		}
	}
	
	/**
	* Get navigation items
	*/
	static function getNav()
	{
		$controller = Dispatcher::getController();
		$action = Dispatcher::getAction();
		$params = join('/', Dispatcher::getParams());
		
		$break = false;
		foreach (self::$nav as $nav_key => $nav)
		{
			ksort($nav->items);
			
			foreach ($nav->items as $item_key => $item)
			{
				$item_expl = explode('/', $item->uri);
				$item_controller = $item_expl[0];
				$item_action = isset($item_expl[1]) ? $item_expl[1] : DEFAULT_ACTION;
				
				if (($item_controller != 'plugin' && $item_controller == $controller) || ($item_controller == 'plugin' && $item_action == $action))
				{
					self::$nav[$nav_key]->is_current = true;
					self::$nav[$nav_key]->items[$item_key]->is_current = true;
					$break = true;
					break;
				}
			}
			
			if($break)
				break;
		}
		
		return self::$nav;
	}
	
} // end Plugin class