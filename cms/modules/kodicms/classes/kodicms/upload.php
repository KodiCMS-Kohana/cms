<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS
 * @category	Helper
 * @author		ButscHSter
 */
class KodiCMS_Upload extends Kohana_Upload {
	
	/**
	 * Загрузка файла и сохранение в папку TMPPATH
	 *
	 *		try
	 *		{
	 *			$filename = Upload::file($_FILES['file'], NULL, NULL, array('jpg', 'jpeg', 'gif', 'png'));
	 *			$path = TMPPATH . $filename;
	 *		}
	 *		catch (Validation_Exception $e)
	 *		{
	 *			echo debug::vars($e->errors('validation'));
	 *		}
	 * 
	 * При указании строки в качестве параметра $file, будет произведена 
	 * попытка загрузить файл по URL
	 * 
	 * @param string|array $file
	 * @param string $directory Путь к каталогу, куда загружать файл
	 * @param string $filename Название файла (filename.ext)
	 * @param array $types Разрешенные типы файлов (При указании пустой строки, разрешены все файлы) array('jpg', '...')
	 * @param integer $max_size Максимальный размер загружаемого файла
	 * @return string|NULL Название файла.
	 * @throws Validation_Exception
	 */
	public static function file( $file, $directory = NULL, $filename = NULL, array $types = array('jpg', 'jpeg', 'gif', 'png'), $max_size = NULL )
	{
		if ( ! is_array($file) )
		{
			return Upload::from_url($file, $directory, $filename, $types);
		}
		
		if ($directory === NULL)
		{
			$directory = TMPPATH;
		}
		
		if ($filename === NULL)
		{
			$filename = uniqid();
		}
		else if ($filename === TRUE)
		{
			$filename = $file['name'];
		}
		
		$ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
		$filename_ext = pathinfo( $filename, PATHINFO_EXTENSION );
		
		if (empty($filename_ext))
		{
			$filename .= '.' . $ext;
		}
		
		if($max_size === NULL)
		{
			$max_size = Num::bytes('20MiB');
		}

		$validation = Validation::factory( array('file' => $file ) )
			->rules( 'file', array(
				array('Upload::valid'),
				array('Upload::size', array(':value', $max_size))
			) );
		
		if ( ! empty($types) )
		{
			$validation->rule('file', 'Upload::type', array(':value', $types));
		}

		if ( ! $validation->check() )
		{
			throw new Validation_Exception($validation);
		}

		if ( ! is_dir( $directory ))
		{
			mkdir($directory, 0777);
			chmod($directory, 0777);
		}

		Upload::save( $file, $filename, $directory, 0777 );

		return $filename;
	}
	
	/**
	 * 
	 * Загрузка файла по URL и сохранение в папку TMPPATH
	 *
	 *		try
	 *		{
	 *			$filename = Upload::from_url('http://....', NULL, NULL, array('jpg', 'jpeg', 'gif', 'png'));
	 *			$path = TMPPATH . $filename;
	 *		}
	 *		catch (Validation_Exception $e)
	 *		{
	 *			echo debug::vars($e->errors('validation'));
	 *		}
	 * 
	 * 
	 * @param string $url Ссылка на файл (http://....)
	 * @param string $directory Путь к каталогу, куда загружать файл
	 * @param string $filename Название файла (filename.ext)
	 * @param array $types Разрешенные типы файлов (При указании пустой строки, разрешены все файлы) array('jpg', '...')
	 * @return string|NULL Название файла
	 * @throws Validation_Exception
	 */
	public static function from_url($url, $directory = NULL, $filename = NULL, array $types = array('jpg', 'jpeg', 'gif', 'png'), $use_curl = FALSE)
	{
		$url = trim($url);
		$ext = pathinfo($url, PATHINFO_EXTENSION);

		if ($directory === NULL)
		{
			$directory = TMPPATH;
		}
		
		if ($filename === NULL)
		{
			$filename = uniqid();
		}
		else if ($filename === TRUE)
		{
			$filename = pathinfo( $filename, PATHINFO_BASENAME );
		}
		
		$filename_ext = pathinfo( $filename, PATHINFO_EXTENSION );
		
		if (empty($filename_ext))
		{
			$filename .= '.' . $ext;
		}

		$validation = Validation::factory( array('url' => $url, 'ext' => $ext ) )
			->rules( 'url', array(
				array('url'),
				array('not_empty'),
			) );
		
		if ( ! empty($types))
		{
			$validation->rule('ext', 'in_array', array(':value', $types));
		}
			
		if ( ! $validation->check())
		{
			throw new Validation_Exception($validation);
		}
		
		if ( ! is_dir($directory))
		{
			mkdir($directory, 0777);
			chmod($directory, 0777);
		}

		// Make the filename into a complete path
		$path = $directory . $filename;
		
		if($use_curl === TRUE)
		{
			$file = Request::factory($url, array(
				'options' => array(
					CURLOPT_SSL_VERIFYPEER => FALSE,
					CURLOPT_SSL_VERIFYHOST => FALSE,
					CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5'
				)
			))->execute()->body();
		}
		else
		{
			$file = fopen($url, 'rb');
		}
		
		if ( empty($file))
		{
			return FALSE;
		}
		
		$new_file = fopen($path, 'wb');
		
		if ( ! $new_file)
		{
			return FALSE;
		}

		if($use_curl === TRUE)
		{
			 fwrite($new_file, $file);
			
		}
		else
		{
			while ( ! feof($file))
			{
				// Write the url file to the directory.
				fwrite($new_file, fread($file, 1024 * 8), 1024 * 8);
			}
		}
		
		fclose($new_file);
		
		// Set permissions on filename
		chmod($path, 0777);

		return $filename;
	}
}