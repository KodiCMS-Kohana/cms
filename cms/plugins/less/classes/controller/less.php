<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Less extends Controller_System_Plugin {

	public function action_settings()
	{
		$less_folder_path = trim(Plugins::getSetting('less_folder_path', 'less', 'public/less'), '/');
		$css_folder_path = trim(Plugins::getSetting('css_folder_path', 'less', 'public/css'), '/');
		
		$less_path = SYSPATH.$less_folder_path.DIRECTORY_SEPARATOR;
		$css_path = SYSPATH.$css_folder_path.DIRECTORY_SEPARATOR;

		if ( Request::current()->method() == Request::POST )
		{
			$settings = Arr::get($_POST, 'setting', array());
			
			if(!isset($settings['enabled']))
			{
				$settings['enabled'] = 'no';
			}
			
			if(!isset($settings['format_css']))
			{
				$settings['format_css'] = 'no';
			}

			Plugins::setAllSettings($settings, 'less');
			
			Messages::success( __('Settings has been saved!'));
			$this->go(URL::site('plugin/less/settings'));
		}
		
		$this->template->content = View::factory('less/settings', array(
			'settings' => Plugins::getAllSettings('less'),
			'less_folder_path' => $less_folder_path, 
			'css_folder_path' => $css_folder_path,
			'is_dir_less' => is_dir($less_path), 
			'is_dir_css' => is_dir($css_path)
		));
	}
}