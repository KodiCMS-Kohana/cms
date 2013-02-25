<?php defined('SYSPATH') or die('No direct script access.');

class Upload extends Kohana_Upload {
	
	/**
	 * 
	 * @param array $file
	 * @param array $types
	 * @return type
	 * @throws Validation_Exception
	 */
	public static function file( $file, array $types = array('jpg', 'jpeg', 'gif', 'png') )
	{
		if( ! is_array($file) )
		{
			return Upload::from_url($file, $types);
		}

		$validation = Validation::factory( array('file' => $file ) )
			->rules( 'file', array(
				array('Upload::valid'),
				array('Upload::type', array(':value', $types)),
				array('Upload::size', array(':value', 100000000))
			) );

		if ( ! $validation->check() )
		{
			return array(FALSE, $validation);
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
	public static function from_url($url, array $types = array('jpg', 'jpeg', 'gif', 'png') )
	{
		$url = trim($url);
		$ext = pathinfo($url, PATHINFO_EXTENSION);

		$validation = Validation::factory( array('url' => $url, 'ext' => $ext ) )
			->rules( 'url', array(
				array('url'),
				array('not_empty'),
			) )
			->rules( 'ext', array(
				array('in_array', array(':value', $types))
			) );
			
		if ( ! $validation->check() )
		{
			return array(FALSE, $validation);
		}

		$filename = uniqid() . '.' . $ext;
		
		if( ! is_dir( TMPPATH ))
		{
			mkdir(TMPPATH, 0777);
			chmod(TMPPATH, 0777);
		}

		// Make the filename into a complete path
		$path = TMPPATH.$filename;
		
		$file = fopen($url, 'rb');

		if( ! $file)
		{
			return array(TRUE, NULL);
		}
		
		$new_file = fopen($path, 'wb');
		
		if($new_file)
		{
			while(!feof($file))
			{
				// Write the url file to the directory.
				fwrite($new_file, fread($file, 1024 * 8), 1024 * 8);
			}

			// Set permissions on filename
			chmod($path, 0777);
			
			return array(TRUE, $filename);
		}
		
		return array(TRUE, NULL);
	}
}