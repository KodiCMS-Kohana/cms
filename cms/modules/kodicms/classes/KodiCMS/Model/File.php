<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS
 * @category	Model
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Model_File {
	
	/**
	 *
	 * @var string 
	 */
	protected $_folder;

	/**
	 *
	 * @var string
	 */
	protected $_name;
	
	/**
	 *
	 * @var string
	 */
	protected $_content;
	
	/**
	 *
	 * @var string
	 */
	protected $_file;
	
	/**
	 *
	 * @var string
	 */
	protected $_path;
	
	/**
	 *
	 * @var array 
	 */
	protected $_changed = array();
	
	/**
	 *
	 * @var boolean 
	 */
	protected $_read_only = FALSE;

	/**
	 *
	 * @var array 
	 */
	protected $_settings = array(
		'roles' => array('administrator', 'developer'),
		'editor' => 'ace'
	);

	/**
	 * 
	 * @param string $name
	 */
	public function __construct($name = '')
	{
		$this->_name = $name;

		if ($file = $this->find_file())
		{
			$this->_path = pathinfo($file, PATHINFO_DIRNAME);
			$this->_file = $file;

			$this->name = pathinfo($file, PATHINFO_FILENAME);
		}
		else
		{
			$this->_path = pathinfo($this->name, PATHINFO_DIRNAME);
	
			if (empty($this->_path) OR $this->_path == '.')
			{
				$this->_path = DOCROOT . $this->_folder;
				$this->_file = $this->_path . DIRECTORY_SEPARATOR . $this->name;
			}
			else
			{
				$this->_file = $this->name;
			}
			
			$this->name = pathinfo($this->name, PATHINFO_FILENAME);
		}

		if (strpos($this->_file, EXT) === FALSE)
		{
			$this->_file .= EXT;
		}
		
		$settings = FileSystem::load_merged_array($this->_folder, '.settings');
		if(isset($settings[$this->name]) AND is_array($settings[$this->name]))
		{
			$this->_settings = Arr::merge($this->_settings, $settings[$this->name]);
		}
		
		if ($this->is_exists() AND !Auth::has_permissions($this->get_roles()))
		{
			$this->set_read_only();
		}
	}
	
	/**
	 * 
	 * @param string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		if (method_exists($this, 'get_' . $key))
		{
			return $this->{'get_' . $key}( );
		}
	}
	
	/**
	 * 
	 * @param string $key
	 * @param mixed $value
	 */
	public function __set($key, $value)
	{
		if (method_exists($this, 'set_' . $key))
		{
			$this->{'set_' . $key}( $value );
		}
	}
	
	/**
	 * 
	 * @return string
	 */
	public function get_file()
	{
		return $this->_file;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function get_path()
	{
		return $this->_path;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function get_folder()
	{
		return $this->_folder;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function get_editor()
	{
		return Arr::get($this->_settings, 'editor', 'ace');
	}
	
	/**
	 * 
	 * @param string $editor
	 */
	public function set_editor($editor)
	{
		$this->_settings['editor'] = $editor;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function get_roles()
	{
		$roles = Arr::get($this->_settings, 'roles', array());
		
		if(!is_array($roles))
		{
			$roles =  array($roles);
		}
		
		return $roles;
	}
	
	/**
	 * 
	 * @return bool
	 */
	public function is_read_only()
	{
		return (bool) $this->_read_only OR ($this->is_exists() AND !$this->is_writable());
	}
	

	public function set_read_only()
	{
		$this->_read_only = TRUE;
	}

	/**
	 * 
	 * @param array $roles
	 */
	public function set_roles(array $roles)
	{
		$this->_settings['roles'] = $roles;
	}

	/**
	 * @return string
	 */
	public function get_relative_path()
	{
		return DIRECTORY_SEPARATOR . str_replace(array(CMSPATH, DOCROOT), '', $this->_file);
	}

	/**
	 * 
	 * @param string $name
	 * @return \Model_File
	 */
	public function set_name($name)
	{
		if (!empty($this->_name))
		{
			$this->_changed['name'] = $this->_name;
		}

		$this->_name = $name;
		return $this;
	}
	
	/**
	 * 
	 * @param string $content
	 * @return \Model_File
	 */
	public function set_content($content)
	{
		$old_content = $this->get_content();
		if (!empty($old_content))
		{
			$this->_changed['content'] = $old_content;
		}

		$this->_content = $content;
		return $this;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function find_file()
	{
		return Kohana::find_file($this->_folder, $this->name);
	}

	/**
	 * 
	 * @return array
	 */
	public static function find_all()
    {
		$class = get_called_class();
		$object = new $class;

		$files = array();
		$paths = Kohana::include_paths();
		
		$found_files = Kohana::list_files($object->_folder, $paths);
		foreach ($found_files as $file)
		{
			if (is_array($file) OR strpos($file, EXT) === FALSE)
			{
				continue;
			}

			$files[] = new $class($file);
		}
		
		return $files;
    }
	
	/**
	 * 
	 * @return array
	 */
	public static function html_select()
	{
		$choices = array(
			__('--- none ---')
		);
		
		$snippets = Model_File_Snippet::find_all();
		
		foreach ($snippets as $snippet)
		{
			$choices[$snippet->name] = $snippet->name;
		}
		
		return $choices;
	}

	/**
	 * 
	 * @return string
	 */
	public function get_content()
	{
		if ($this->_content === NULL)
		{
			if ($this->is_exists())
			{
				$this->_content = file_get_contents($this->_file);
			}
			else
			{
				$this->_content = '';
			}
		}

		return $this->_content;
	}

	/**
	 * 
	 * @return string
	 */
	public function get_name()
	{
		return $this->_name;
	}

	/**
	 * 
	 * @return boolean
	 */
	public function delete()
	{		
		return unlink($this->_file);
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function is_exists()
	{
		return file_exists($this->_file);
	}

	/**
	 * 
	 * @return boolean
	 */
	public function is_writable()
	{
		return is_writable($this->_file);
	}

	/**
	 * 
	 * @return string
	 */
	public function size()
	{
		return filesize($this->_file);
	}

	/**
	 * Get the file's last modification time.
	 *
	 * @param  string  $path
	 * @return int
	 */
	public function modified()
	{
		return filemtime($this->_file);
	}

	/**
	 * 
	 * @return boolean
	 */
	public function save()
	{
		$validation = Validation::factory(array(
			'name' => $this->name
		))
			->rule('name', 'not_empty')
			->label('name', __('Name'));

		if (!$validation->check())
		{
			throw new Validation_Exception($validation);
		}
		
		$settings = FileSystem::load_merged_array($this->_folder, '.settings');
		
		$this->name = FileSystem::filter_name($this->name);

		// Если изменено название файла в редакторе, переименовываем файл
		if (!empty($this->_changed['name']) AND $this->name != $this->_changed['name'])
		{
			unset($settings[$this->_changed['name']]);
			$new_file = $this->_path . DIRECTORY_SEPARATOR . $this->name . EXT;
			@rename($this->_file, $new_file);
			$this->_file = $new_file;
		}

		if (Config::get('site', 'templates_revision') == Config::YES)
		{
			$this->_add_revision_of_file();
		}
		
		$this->_changed = array();
		
		$settings[$this->name] = $this->_settings;
		$this->_save_settings($settings);
		
		return file_put_contents($this->_file, $this->_content) !== FALSE;
	}
	
	/**
	 * 
	 * @param array $settings
	 */
	protected function _save_settings(array $settings)
	{
		$data = "<?php defined('SYSPATH') or die('No direct access allowed.');\n\n";
		
		$data .= "return ";
		$data .= var_export($settings, TRUE);
		$data .= ";";

		file_put_contents(DOCROOT . $this->_folder . DIRECTORY_SEPARATOR . '.settings.php', $data);
	}

	protected function _add_revision_of_file()
	{
		$name = Arr::get($this->_changed, 'name');
		$content = Arr::get($this->_changed, 'content');
		
		if (empty($name) OR strcmp($content, $this->_content) == 0)
		{
			return;
		}

		$directory = CMSPATH . 'logs' . DIRECTORY_SEPARATOR . $this->_folder;

		if (!is_dir($directory))
		{
			mkdir($directory, 02777);

			// Set permissions (must be manually set to fix umask issues)
			chmod($directory, 02777);
		}

		// Set the name of the log file
		$filename = $directory.DIRECTORY_SEPARATOR.$name.date('Y-m-d-H-i-s').EXT;

		if (!file_exists($filename))
		{
			// Create the log file
			file_put_contents($filename, Kohana::FILE_SECURITY . ' ?>' . PHP_EOL . $content);

			// Allow anyone to write to log files
			chmod($filename, 0666);
		}
	}

	/**
	 * 
	 * @return string
	 */
	public function __toString() 
	{
		return (string) $this->_content;
	}
}