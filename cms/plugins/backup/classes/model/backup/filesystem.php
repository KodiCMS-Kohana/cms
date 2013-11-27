<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Backup
 * @category	Model
 * @author		ButscHSter
 */
class Model_Backup_FileSystem extends Model_Backup {
	
	/**
	 * 
	 * @return boolean
	 * @throws Kohana_Exception
	 */
	public function create()
	{
		if (!extension_loaded('zip') === true)
		{
			throw new Kohana_Exception('Extension "zip" not loaded');
		}
			
		$zip = new ZipArchive();

		if ($zip->open(BACKUP_PLUGIN_FOLDER . 'filesystem-'.date('YmdHis').'.zip', ZIPARCHIVE::CREATE) === true)
		{
			$sources = array(
				PUBLICPATH,
				PLUGPATH,
				LAYOUTS_SYSPATH,
				SNIPPETS_SYSPATH
			);
			
			foreach ( $sources as $source )
			{
				if (is_dir($source) === true)
				{
					$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

					foreach ($files as $file)
					{
						$file = realpath($file);

						if (is_dir($file) === true)
						{
							$zip->addEmptyDir(str_replace(DOCROOT, '', $file . DIRECTORY_SEPARATOR));
						}
						else if (is_file($file) === true)
						{
							$zip->addFromString(str_replace(DOCROOT, '', $file), file_get_contents($file));
						}
					}
				}		
			}
			
			$zip->close();
			
			return TRUE;
		}
		
		return FALSE;
    }
	
	/**
	 * 
	 * @param string $file
	 * @return string
	 * @throws Exception
	 */
	public function view($file = NULL)
	{
		if($file === NULL)
		{
			$file = $this->file;
		}
		
		if(!file_exists($file))
		{
			throw new Exception('File '.$file.' not exists');
		}
		
		$za = new ZipArchive();
		
		$files = '';

		$za->open($file);

		for( $i = 0; $i < $za->numFiles; $i++ )
		{
			$stat = $za->statIndex( $i );
			$files .= $stat['name'] . "\n";
		}

		return $files;
	}
	
	/**
	 * 
	 * @param string $file
	 * @return boolean
	 * @throws Exception
	 */
	public function restore($file = NULL)
	{
		if($file === NULL)
		{
			$file = $this->file;
		}
		
		if(!file_exists($file))
		{
			throw new Exception('File '.$file.' not exists');
		}

		$zip = new ZipArchive;

		if ($zip->open($file) === TRUE) 
		{
			$zip->extractTo(DOCROOT);
			$zip->close();
			
			return TRUE;
		}
		
		return FALSE;
	}

}