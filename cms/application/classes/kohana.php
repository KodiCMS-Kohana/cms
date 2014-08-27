<?php defined('SYSPATH') OR die('No direct script access.');

class Kohana extends Kohana_Core {
	
	public static function modules(array $modules = NULL)
	{
		$modules = parent::modules($modules);
		
		array_unshift(Kohana::$_paths, CMSPATH);
		
		return $modules;
	}
}
