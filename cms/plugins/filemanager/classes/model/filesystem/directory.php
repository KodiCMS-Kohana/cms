<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Model_FileSystem_Directory extends DirectoryIterator {
	
	public function createFolder($name)
	{
		$folder_path = $this->getPath() . DIRECTORY_SEPARATOR . $name;
		if ( ! is_dir($folder_path))
		{
			return mkdir($folder_path);
		}
		else
		{
			return FALSE;
		}
	}

	public function delete()
	{
		$dirHandle = opendir($this->getRealPath());
			
		while (FALSE !== ($file = readdir($dirHandle))) 
		{
			if ($file != '.' AND $file != '..')
			{
				$tmpPath = $this->getRealPath() . DIRECTORY_SEPARATOR . $file;
				chmod($tmpPath, 0777);

				Model_FileSystem::factory($tmpPath)->delete();
			}
		}

		closedir($dirHandle);

		if (file_exists($this->getRealPath()))
		{
			return rmdir($this->getRealPath());
		}
		
		return FALSE;
	}
	
	public function getParent()
	{
		$path = $this->getRealPath();
		$parent_path = (!empty($path) ? substr($path, 0, strrpos($path, DIRECTORY_SEPARATOR)): '');
		return Model_FileSystem::factory($parent_path);
	}
}