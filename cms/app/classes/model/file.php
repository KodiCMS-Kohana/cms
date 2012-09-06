<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Model_File {
	
	public $name;
	
	protected $_content;
	protected $_file;
	protected $_path;


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
	
	public function get_file()
	{
		return $this->_file;
	}
	
	public function get_path()
	{
		return $this->_path;
	}
	
	public function set_name($name)
	{
		$this->name = $name;
	}
	
	public function set_content($content)
	{
		$this->_content = $content;
	}
	
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
				$files[] = new self(substr($file, 0, strrpos($file, EXT)));
			}
		}
		
		return $files;
    }
	
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
	
	public function is_exists()
	{
		return file_exists($this->_file);
	}
	
	public function save()
	{
		$new_file = $this->_path . $this->name . EXT;
		
		if ( $new_file != $this->_file )
		{			
			rename($this->_file, $new_file);
			$this->_file = $new_file;
		}
		
		$f = fopen($this->_file, 'w+');
		$result = fwrite($f, $this->_content);
		fclose($f);
		
		return $result === FALSE ? FALSE : TRUE;
	}
}