<?php defined('SYSPATH') OR die('No direct script access.');

class Kohana extends Kohana_Core {
	
	public static function modules(array $modules = NULL)
	{
		$modules = parent::modules($modules);

		foreach (array(CMSPATH, DOCROOT) as $path)
		{
			if (!in_array($path, Kohana::$_paths))
			{
				array_unshift(Kohana::$_paths, $path);
			}
		}

		return $modules;
	}

	/**
	 * 
	 * @param mixed $data
	 * @return string
	 */
	public static function serialize($data)
	{
//		return str_replace("\0", "~~NULL_BYTE~~", serialize($data));
		return serialize($data);
	}

	/**
	 * 
	 * @param string $data
	 * @return mixed
	 */
	public static function unserialize($data)
	{
//		return unserialize(str_replace("~~NULL_BYTE~~", "\0", $data));
		return unserialize($data);
	}

}