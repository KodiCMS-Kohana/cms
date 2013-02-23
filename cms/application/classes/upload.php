<?php defined('SYSPATH') or die('No direct script access.');

class Upload extends Kohana_Upload {
	
	/**
	 * 
	 * @param array $file
	 * @param array $types
	 * @return type
	 * @throws Validation_Exception
	 */
	public static function file( $file, array $types = array('jpg', 'gif', 'png') )
	{
		$validation = Validation::factory( array('file' => $file ) )
			->rules( 'file', array(
				array('Upload::valid'),
				array('Upload::type', array(':value', $types)),
				array('Upload::size', array(':value', 100000000))
			) );

		if ( ! $validation->check() )
		{
			return array(FALSE, $validation->errors());
		}

		$ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
		$filename = uniqid() . '.' . $ext;
		
		if( ! is_dir( TMPPATH ))
		{
			mkdir(TMPPATH, 0777);
			chmod(TMPPATH, 0777);
		}

		$uploadedfile = Upload::save( $file, $filename, TMPPATH, 0777 );

		return array(TRUE, $filename);
	}
	
	/**
	 * 
	 * @param string $url
	 * @param string $directory
	 * @param string $chmod
	 * @return string|boolean
	 * @throws Kohana_Exception
	 */
	public static function save_from_url($url, $directory = NULL, $chmod = 0644)
	{
		$url = trim($url);

		if(!Valid::url( $url ))
		{
			return 'URL not vaid';
		}
		
		$filename = pathinfo($url, PATHINFO_BASENAME);
		$filename = uniqid() . $filename;
		
		if (Upload::$remove_spaces === TRUE)
		{
			// Remove spaces from the filename
			$filename = preg_replace('/\s+/u', '_', $filename);
		}
		
		if ($directory === NULL)
		{
			// Use the pre-configured upload directory
			$directory = Upload::$default_directory;
		}
		
		if ( ! is_dir($directory) OR ! is_writable(realpath($directory)))
		{
			throw new Kohana_Exception('Directory :dir must be writable',
				array(':dir' => Debug::path($directory)));
		}

		// Make the filename into a complete path
		$filename = realpath($directory).DIRECTORY_SEPARATOR.$filename;
		
		$file = fopen($url, 'rb');
		if(!$file)
		{
			return FALSE;
		}
		
		$new_file = fopen($filename, 'wb');
		
		if($new_file)
		{
			while(!feof($file))
			{
				// Write the url file to the directory.
				fwrite($new_file, fread($file, 1024 * 8), 1024 * 8);
			}
			
			if ($chmod !== FALSE)
			{
				// Set permissions on filename
				chmod($filename, $chmod);
			}
			
			return $filename;
		}
		
		return FALSE;
	}
}