<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Model_FileSystem {
	
	protected $_file;

	public static function factory( $file )
	{
		if(!($file instanceof SplFileInfo))
		{
			if ( !is_dir( $file ) )
			{
				if ( file_exists( $file ) ) 
				{
					$file = new Model_FileSystem_File( $file );
				}
				else
				{
					throw new Kohana_Exception( 'Directory or file :path not found', array(
						':path' => $file
					) );
				}
			}
			else
			{
				$file = new Model_FileSystem_Directory( $file );
			}
		}

		return new Model_FileSystem($file);
	}

	public function __construct( SplFileInfo $file )
	{
		$this->_file = $file;
	}
	
	public function get_object()
	{
		return $this->_file;		
	}
	
	public function __get( $name )
	{
		if(isset($this->_file->{$name}))
		{
			return $this->_file->{$name};
		}
	}

	public function __call( $method, $arguments = array() )
	{
		if( method_exists( $this->_file, $method ))
		{
			return call_user_func_array(array($this->_file, $method), $arguments);
		}
	}

	public function getPerms()
	{
		$perms = $this->_file->getPerms();

		if ( ($perms & 0xC000) == 0xC000 )
		{
			// Socket
			$info = 's';
		}
		elseif ( ($perms & 0xA000) == 0xA000 )
		{
			// Symbolic Link
			$info = 'l';
		}
		elseif ( ($perms & 0x8000) == 0x8000 )
		{
			// Regular
			$info = '-';
		}
		elseif ( ($perms & 0x6000) == 0x6000 )
		{
			// Block special
			$info = 'b';
		}
		elseif ( ($perms & 0x4000) == 0x4000 )
		{
			// Directory
			$info = 'd';
		}
		elseif ( ($perms & 0x2000) == 0x2000 )
		{
			// Character special
			$info = 'c';
		}
		elseif ( ($perms & 0x1000) == 0x1000 )
		{
			// FIFO pipe
			$info = 'p';
		}
		else
		{
			// Unknown
			$info = 'u';
		}

		// Owner
		$info .= (($perms & 0x0100) ? 'r' : '-');
		$info .= (($perms & 0x0080) ? 'w' : '-');
		$info .= (($perms & 0x0040) ?
						(($perms & 0x0800) ? 's' : 'x' ) :
						(($perms & 0x0800) ? 'S' : '-'));

		// Group
		$info .= (($perms & 0x0020) ? 'r' : '-');
		$info .= (($perms & 0x0010) ? 'w' : '-');
		$info .= (($perms & 0x0008) ?
						(($perms & 0x0400) ? 's' : 'x' ) :
						(($perms & 0x0400) ? 'S' : '-'));

		// World
		$info .= (($perms & 0x0004) ? 'r' : '-');
		$info .= (($perms & 0x0002) ? 'w' : '-');
		$info .= (($perms & 0x0001) ?
		(($perms & 0x0200) ? 't' : 'x' ) :
		(($perms & 0x0200) ? 'T' : '-'));

		return substr( sprintf( '%o', $perms ), -4, 4 ); // (perm, chmod)
	}
	
	public function getRelativePath()
	{
		return trim(str_replace(rtrim(PUBLICPATH, DIRECTORY_SEPARATOR), '', $this->getRealPath()), DIRECTORY_SEPARATOR);
	}

	public function getPathArray()
	{
		$dirs_array = explode(DIRECTORY_SEPARATOR, $this->getRelativePath());
		$path = '';
		$dirs_count = count($dirs_array);
		$paths_array = array();

		for($i = 0; $i < $dirs_count; ++$i)
		{
			if(
				empty($dirs_array[$i]) 
			OR 
				$dirs_array[$i] == '.' 
			OR  
				$dirs_array[$i] == '..'
			)
			{
				continue;
			}

			$paths_array[$path . $dirs_array[$i]] = $dirs_array[$i];
			$path .= $dirs_array[$i] . DIRECTORY_SEPARATOR;
		}
		
		return $paths_array;
	}
	
	public function rename($name)
	{
		if (rename($this->getRealPath(), $this->getPath() . DIRECTORY_SEPARATOR . $name))
		{
			return Model_FileSystem::factory($this->getPath() . DIRECTORY_SEPARATOR . $name);
		}
		
		return FALSE;
	}
	
	public function setPerms($chmod)
	{
		return chmod($this->getRealPath(), octdec((int) $chmod));
	}

	public function iteratePaths()
    {
		$array = array();

        foreach ($this->_file as $file)
		{
			$array[] = new Model_FileSystem(clone($file));
		}
		
		return $array;
    }
}