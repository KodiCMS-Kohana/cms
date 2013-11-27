<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS
 * @category	Model
 * @author		ButscHSter
 */
class KodiCMS_Model_File {
	
	/**
	 *
	 * @var string
	 */
	public $name;
	
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
	 * @param string $name
	 */
	public function __construct( $name = '' )
	{
		$this->set_name($name);
		
		$this->_file = $this->_path . $this->name . EXT;
	}
	
	public function __toString() 
	{
		return (string) $this->_content;
	}
	
	public function __get($key)
	{
		if ($key == 'content')
		{
			return $this->get_content( );
		}
	}
	
	public function __set($key, $value)
	{
		if ($key == 'content')
		{
			$this->set_content( $value );
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
	 * @param string $name
	 * @return \Model_File
	 */
	public function set_name($name)
	{
		$this->name = $name;
		return $this;
	}
	
	/**
	 * 
	 * @param string $content
	 * @return \Model_File
	 */
	public function set_content($content)
	{
		$this->_content = $content;
		return $this;
	}
	
	/**
	 * 
	 * @return array
	 */
	public static function find_all()
    {
		$class = get_called_class();
		$object = new $class;
		$path = $object->get_path();

		$files = array();
		
		$dir = opendir($path);
		
		while ($file = readdir($dir))
		{
			if (is_file($path.$file) AND substr($file, -strlen(EXT)) == EXT)
			{
				$files[] = new $class(substr($file, 0, strrpos($file, EXT)));
			}
		}
		
		return $files;
    }
	
	/**
	 * 
	 * @return string
	 */
	public function get_content()
	{
		if ($this->_content === NULL)
		{
			if (file_exists($this->_file))
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
		
		if(!$validation->check())
		{
			throw new Validation_Exception($validation);
		}

		$new_file = $this->_path . $this->name . EXT;
		
		if ( $new_file != $this->_file )
		{			
			rename($this->_file, $new_file);
			$this->_file = $new_file;
		}
		
		$f = fopen($this->_file, 'w+');
		$result = fwrite($f, $this->_content);
		fclose($f);
		
		return $result !== FALSE;
	}
}