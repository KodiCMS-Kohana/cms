<?php defined('SYSPATH') OR die('No direct script access.');

abstract class KodiCMS_Image extends Kohana_Image {
	
	/**
	 * Конвертация изображений с разным расширением в jpeg
	 * 
	 * @param string $source
	 * @param string $target_directory
	 * @param integer $quality
	 * @param boolean $remove_source_image
	 * @return boolean
	 * @throws Kohana_Exception
	 */
	public static function convert_to_jpeg($source, $target_directory = NULL, $quality = 100, $remove_source_image = FALSE)
	{
		if (!file_exists($source))
		{
			return FALSE;
		}

		$ext = pathinfo($source, PATHINFO_EXTENSION);
		$filename = pathinfo($source, PATHINFO_FILENAME);

		if ($target_directory !== NULL)
		{
			if (!is_dir($target_directory) AND is_writable($target_directory))
			{
				mkdir($target_directory, 0777);
			}
		}
		else
		{
			$target_directory = pathinfo($source, PATHINFO_DIRNAME);
		}

		if (!is_writable($target_directory))
		{
			throw new Kohana_Exception('Unable to write to the target directory :resource', array(
				':resource' => $target_directory));
		}

		if (Valid::regex($ext, '/jpg|jpeg/i'))
		{
			$image_tmp = imagecreatefromjpeg($source);
		}
		else if (Valid::regex($ext, '/png/i'))
		{
			$image_tmp = imagecreatefrompng($source);
		}
		else if (Valid::regex($ext, '/gif/i'))
		{
			$image_tmp = imagecreatefromgif($source);
		}
		else if (Valid::regex($ext, '/bmp/i'))
		{
			$image_tmp = imagecreatefrombmp($source);
		}
		else
		{
			return FALSE;
		}

		// quality is a value from 0 (worst) to 100 (best)
		imagejpeg($image_tmp, $dirname . $filename . '.jpg', $quality);
		imagedestroy($image_tmp);

		if ($remove_source_image === TRUE)
		{
			unlink($source);
		}

		return TRUE;
	}

	/**
	 * Создание уменьшенной копии изображения. 
	 * Копия помещается в папку  PUBLICPATH . cache
	 * 
	 * @param string $filepath Путь до файла относительно PUBLICPATH
	 * @param integer $width
	 * @param integer $height
	 * @param integer $master Мастер изменеия размера
	 * @param bool $crop Обрезать?
	 * 
	 * return NULL | string Путь до кеша изображения
	 */
	public static function cache($filepath, $width, $height, $master = Image::INVERSE, $crop = FALSE)
	{
		if ($master === NULL)
		{
			$master = Image::INVERSE;
		}

		$original_image = PUBLICPATH . $filepath;

		if (!is_file($original_image))
		{
			return NULL;
		}

		$filename = pathinfo($filepath, PATHINFO_FILENAME);
		$directory = pathinfo($filepath, PATHINFO_DIRNAME);
		$extension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));

		if (!in_array($extension, array('jpg', 'gif', 'png', 'bmp', 'jpeg')))
		{
			return NULL;
		}

		$cached_filename = $filename . '_' . $width . 'x' . $height . 'x' . $master;

		if ($crop === TRUE)
		{
			$cached_filename .= 'xCR';
		}

		$cached_image = 'cache/' . $directory . '/' . $cached_filename . '.' . $extension;

		$directory = FileSystem::normalize_path($directory);

		if (!is_file(PUBLICPATH . $cached_image) OR ( filectime($original_image) > filectime(PUBLICPATH . $cached_image)))
		{
			$path = PUBLICPATH . 'cache';

			if (!is_dir($path) AND is_writable(PUBLICPATH))
			{
				mkdir($path, 0777);
			}
			else if (!is_writable(PUBLICPATH))
			{
				throw new Kohana_Exception('Unable to write to the cache directory :resource', array(':resource' => $path));
			}

			$directories = explode(DIRECTORY_SEPARATOR, $directory);

			foreach ($directories as $directory)
			{
				if (!is_writable($path))
				{
					throw new Kohana_Exception('Unable to write to the cache directory :resource', array(':resource' => $path));
				}

				$path = $path . DIRECTORY_SEPARATOR . $directory;

				if (!is_dir($path))
				{
					mkdir($path, 0777);
				}
			}

			list($width_orig, $height_orig) = getimagesize($original_image);

			if ($width_orig > $width OR $height_orig > $height)
			{
				$iamge = Image::factory($original_image)
						->resize($width, $height, $master);

				if ($crop === TRUE)
				{
					$iamge->crop($width, $height);
				}

				$iamge->save(PUBLICPATH . $cached_image);
			}
			else
			{
				copy($original_image, PUBLICPATH . $cached_image);
			}
		}

		return PUBLIC_URL . $cached_image;
	}

}
