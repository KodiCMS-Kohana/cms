<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Less extends Controller_System_Plugin {

	public function action_settings()
	{
		parent::action_settings();

		$less_folder_path = trim($this->plugin->get('less_folder_path', 'public/less'), '/');
		$css_folder_path = trim($this->plugin->get('css_folder_path', 'public/css'), '/');
		
		$less_path = DOCROOT.$less_folder_path.DIRECTORY_SEPARATOR;
		$css_path = DOCROOT.$css_folder_path.DIRECTORY_SEPARATOR;
		
		$this->template->content->content->set(array(
			'less_folder_path' => $less_folder_path, 
			'css_folder_path' => $css_folder_path,
			'is_dir_less' => is_dir($less_path), 
			'is_dir_css' => is_dir($css_path)
		));
	}
	
	protected function _settings_save( $plugin )
	{
		if(!isset($_POST['setting']['enabled']))
		{
			$_POST['setting']['enabled'] = 'no';
		}

		if(!isset($_POST['setting']['format_css']))
		{
			$_POST['setting']['format_css'] = 'no';
		}

		parent::_settings_save($plugin);
	}
}