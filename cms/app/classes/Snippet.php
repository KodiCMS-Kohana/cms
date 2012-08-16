<?php defined('SYSPATH') or die('No direct access allowed.');

class Snippet
{
	public $name;
	
	private $_content;
	private $file;
	
	public function __construct( $snippet_name = '' )
	{
		$this->name = $snippet_name;
		
		$this->file = SNIPPETS_SYSPATH.$snippet_name.EXT;
	}
	
	public function __toString() {
		return (string) $this->_content;
	}


	public function __get($key)
	{
		if (method_exists($this, $key))
			return $this->{$key}();
	}
	
	public function __set($key, $value)
	{
		if ($key == 'content')
			$this->_content = $value;
	}
	
    public static function findAll()
    {
		$layouts = array();
		
		$SNIPPETS_dir = opendir(SNIPPETS_SYSPATH);
		
		while ($snippet_file = readdir($SNIPPETS_dir))
		{
			if (is_file(SNIPPETS_SYSPATH.$snippet_file) && substr($snippet_file, -strlen(EXT)) == EXT)
			{
				$layouts[] = new Layout(substr($snippet_file, 0, strrpos($snippet_file, EXT)));
			}
		}
		
		return $layouts;
    }
	
	public function content()
	{
		if ($this->_content === null)
		{
			if (file_exists($this->file))
				$this->_content = file_get_contents($this->file);
			else
				$this->_content = '';
		}
		
		return $this->_content;
	}

	public function save()
	{
		$new_file = SNIPPETS_SYSPATH.$this->name.EXT;
		
		if ( $new_file != $this->file )
		{			
			rename($this->file, $new_file);
			$this->file = $new_file;
		}
		
		$f = fopen($this->file, 'w+');
		$result = fwrite($f, $this->_content);
		fclose($f);
		
		return ($result === FALSE ? FALSE : true);
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