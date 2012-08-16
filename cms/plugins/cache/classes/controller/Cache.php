<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Cache extends Controller_System_Plugin
{
	public function action_settings()
	{
		if ( Request::current()->method() == Request::POST )
		{
			$settings = array(
				'cache_dynamic'       => 'no',
				'cache_static'        => 'no',
				'cache_remove_static' => 'no',
				'cache_lifetime'            => 86400
			);
			
			if (isset($_POST['setting']) && is_array($_POST['setting']))
			{
				foreach ($_POST['setting'] as $key => $val)
					$settings[$key] = $val;
			}
			
			Plugins::setAllSettings($settings, 'cache');
			
			Flash::set('success', __('Settings has been saved!'));
			$this->go(URL::site('plugin/cache/settings'));
		}
		
		$this->template->content = View::factory('cache/settings', array(
			'setting' => Plugins::getAllSettings('cache')
		));
	}
	
	public function remove_cache()
	{
		$dir = new DirectoryIterator(CACHE_DYNAMIC_SYSPATH);
			
		foreach ($dir as $file)
		{
			if (!$file->isDot() && $file->isFile())
				unlink($file->getPathname());
		}
		
		$dir = new DirectoryIterator(CACHE_STATIC_SYSPATH);
			
		foreach ($dir as $file)
		{
			if (!$file->isDot() && $file->isFile())
				unlink($file->getPathname());
		}
		
		Flash::set('success', __('All cache removed!'));
		$this->go(URL::site('plugin/cache/settings'));
	}
} // end class CacheController