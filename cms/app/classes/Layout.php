<?php defined('SYSPATH') or die('No direct access allowed.');


class Layout
{
	public $name;
	
	private $_content;
	private $file;

	public function __construct( $layout_name = '' )
	{
		$this->name = $layout_name;
		
		$this->file = LAYOUTS_SYSPATH.$layout_name.EXT;
	}
	
	public function __get($key)
	{
		if (method_exists($this, $key))
		{
			return $this->{$key}();
		}
	}
	
	public function __set($key, $value)
	{
		if ($key == 'content')
		{
			$this->_content = $value;
		}
	}
	
    public static function findAll()
    {
		$layouts = array();
		
		$layouts_dir = opendir(LAYOUTS_SYSPATH);
		
		while ($layout_file = readdir($layouts_dir))
		{
			if (is_file(LAYOUTS_SYSPATH.$layout_file) && substr($layout_file, -strlen(EXT)) == EXT)
			{
				$layouts[] = new Layout(substr($layout_file, 0, strrpos($layout_file, EXT)));
			}
		}
		
		return $layouts;
    }
    
    public function isUsed()
    {
        return Record::countFrom('Page', 'layout_file = :name', array(
			':name' => $this->name
		));
    }
	
	public function content()
	{
		if ($this->_content === NULL)
		{
			if (file_exists($this->file))
			{
				$this->_content = file_get_contents($this->file);
			}
			else
			{
				$this->_content = '';
			}
		}
		
		return $this->_content;
	}

	public function save()
	{
		$new_file = LAYOUTS_SYSPATH.$this->name.EXT;
		
		if ( $new_file != $this->file )
		{			
			rename($this->file, $new_file);
			$this->file = $new_file;
		}
		
		$f = fopen($this->file, 'w+');
		$result = fwrite($f, $this->_content);
		fclose($f);
		
		return ($result === FALSE ? FALSE : TRUE);
	}
	
	public function delete()
	{		
		return unlink($this->file);
	}
	
	public function isExists()
	{
		return file_exists($this->file);
	}
    
} // end Layout class