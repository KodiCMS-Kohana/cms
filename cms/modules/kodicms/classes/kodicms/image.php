<?php defined('SYSPATH') OR die('No direct script access.');

abstract class KodiCMS_Image extends Kohana_Image {
	
	/**
	 * Создание уменьшенной копии изображения. 
	 * Копия помещается в папку  PUBLICPATH . cache
	 * 
	 * @param string $filename Путь до файла относительно PUBLICPATH
	 * @param integer $width
	 * @param integer $height
	 * @param integer $master Мастер изменеия размера
	 * 
	 * return NULL | string Путь до кеша изображения отностительно PUBLICPATH
	 */
	public static function cache($filepath, $width, $height, $master = Image::INVERSE)
	{
		if ( ! is_file(PUBLICPATH . $filepath) ) 
		{
			return NULL;
		}
		
		$filename = pathinfo($filepath, PATHINFO_FILENAME);
		$directory = FileSystem::normalize_path(pathinfo($filepath, PATHINFO_DIRNAME));
		$extension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
		
		if( ! in_array($extension, array('jpg', 'gif', 'png', 'bmp', 'jpeg')) )
		{
			return NULL;
		}
		
		$old_image = PUBLICPATH . $filepath;
		$new_image = 'cache/'  . $directory . '/' . $filename . '_' . $width . 'x' . $height . '.' . $extension;
		
		if( ! is_file( PUBLICPATH . $new_image ) OR (filectime( $old_image ) > filectime( PUBLICPATH . $new_image ))) 
		{
			$path = PUBLICPATH . 'cache';
			
			if ( ! is_dir( $path ) AND is_writable( PUBLICPATH )) 
			{
				mkdir( $path, 0777 );
			}
			else
			{
				throw new Kohana_Exception('Unable to write to the cache directory :resource', 
						array(':resource' => $path) );
			}
			
			$directories = explode(DIRECTORY_SEPARATOR, $directory);
			
			foreach ($directories as $directory)
			{
				if( ! is_writable( $path ) )
				{
					throw new Kohana_Exception('Unable to write to the cache directory :resource', 
						array(':resource' => $path));
				}

				$path = $path . DIRECTORY_SEPARATOR . $directory;

				if ( ! is_dir( $path) ) 
				{
					mkdir( $path, 0777 );
				}
			}
			
			list($width_orig, $height_orig) = getimagesize( $old_image );

			if ($width_orig > $width OR $height_orig > $height) 
			{			
				Image::factory( $old_image )
					->resize($width, $height, $master)
					->save( PUBLICPATH . $new_image );
			} 
			else 
			{
				copy( $old_image, PUBLICPATH . $new_image );
			}
		}
		
		return PUBLIC_URL . $new_image;
	}
}
