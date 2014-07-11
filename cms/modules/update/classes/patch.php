<?php defined('SYSPATH') OR die('No direct script access.');

class Patch {
	
	/**
	 * 
	 * @return array
	 */
	public static function find_remote()
	{
		$respoonse = Update::request('https://api.github.com/repos/KodiCMS/patches/git/trees/master?recursive=true');
		$respoonse = json_decode($respoonse, TRUE);
		
		$patches = array();
		
		$cache = Cache::instance();
		$cached_patches = $cache->get('patches_cache');
		
		if($cached_patches !== NULL)
		{
			return $cached_patches;
		}
		
		if(isset($respoonse['tree']))
		{
			$installed_patches = self::installed();
			
			foreach($respoonse['tree'] as $row)
			{
				if ( ! in_array($row['path'], $installed_patches) AND $row['path'] != 'README.md')
				{
					$patches[$row['path']] = 'https://raw.githubusercontent.com/KodiCMS/patches/master/' . $row['path'];
				}
			}
			
			$cache->set('patches_cache', $patches);
		}
		
		return $patches;
	}
	
	/**
	 * @return array
	 */
	public static function find_local()
	{
		$patches_list = Kohana::list_files('patches', array(DOCROOT));
		$installed_patches = self::installed();

		$patches = array();
		foreach ($patches_list as $path)
		{
			$filename = pathinfo($path, PATHINFO_BASENAME);
			
			if ( ! in_array($filename, $installed_patches))
			{
				$patches[$filename] = $path;
			}
		}
		
		return $patches;
	}
	
	/**
	 * 
	 * @return array
	 */
	public static function find_all()
	{
		$remote_patches = self::find_remote();
		$local_patches = self::find_local();
		
		return Arr::merge($local_patches, $remote_patches);
	}
	
	/**
	 * 
	 * @param string $patch
	 */
	public static function apply($patch)
	{
		$installed_patches = self::installed();
		
		$filename = pathinfo($patch, PATHINFO_BASENAME);
		
		if (Valid::url($patch))
		{
			$filename = Upload::from_url($patch, PATCHES_FOLDER, $filename, array('php'), TRUE);
			
			$patch = PATCHES_FOLDER . $filename;
		}
		
		if (file_exists($patch) AND ! in_array($patch, $installed_patches))
		{
			include $patch;
			@unlink($patch);
			
			$installed_patches[] = $filename;
			Config::set('update', 'installed_patches', $installed_patches);
		}
	}
	
	/**
	 * 
	 * @return array
	 */
	public static function installed()
	{
		return Config::get('update', 'installed_patches', array());
	}
}