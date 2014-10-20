<?php defined('SYSPATH') OR die('No direct script access.');

class Kohana extends Kohana_Core {
	
	public static function modules(array $modules = NULL)
	{
		if ($modules === NULL)
		{
			// Not changing modules, just return the current set
			return Kohana::$_modules;
		}

		// Start a new list of include paths, APPPATH first
		$paths = array(CMSPATH, DOCROOT, APPPATH);

		foreach ($modules as $name => $path)
		{
			if (is_dir($path))
			{
				// Add the module to include paths
				$paths[] = $modules[$name] = realpath($path).DIRECTORY_SEPARATOR;
			}
			else
			{
				// This module is invalid, remove it
				throw new Kohana_Exception('Attempted to load an invalid or missing module \':module\' at \':path\'', array(
					':module' => $name,
					':path'   => Debug::path($path),
				));
			}
		}

		// Finish the include paths by adding SYSPATH
		$paths[] = SYSPATH;

		// Set the new include paths
		Kohana::$_paths = $paths;

		// Set the current module list
		Kohana::$_modules = $modules;
		
		if (!IS_INSTALLED)
		{
			$modules_dirs = new DirectoryIterator(MODPATH);
			$paths = array(APPPATH);
			
			$paths = $paths + $modules;
			
			foreach ($modules_dirs as $dir)
			{
				if ($dir->isDot() OR array_key_exists($dir->getBasename(), Kohana::$_modules))
				{
					continue;
				}

				$paths[] = MODPATH . $dir->getBasename() . DIRECTORY_SEPARATOR;
			}
			
			// Finish the include paths by adding SYSPATH
			$paths[] = SYSPATH;

			// Set the new include paths
			Kohana::$_paths = $paths;
		}
		
		foreach (Kohana::$_modules as $path)
		{
			$init = $path.'init'.EXT;

			if (is_file($init))
			{
				// Include the module initialization file once
				require_once $init;
			}
		}

		return Kohana::$_modules;
	}
}