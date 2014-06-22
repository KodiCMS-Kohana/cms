<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Plugin_Less extends Plugin_Decorator {
	
	public function rules()
	{
		return array(
			'less_folder_path' => array(
				array('not_empty'),
				array(array($this, 'is_dir'), array(':value')),
			),
			'css_folder_path' => array(
				array('not_empty'),
				array(array($this, 'is_dir'), array(':value')),
			),
		);
	}

	public function default_settings()
	{
		$settings = parent::default_settings();
		
		$settings['less_folder_path'] = 'cms/media/less';
		$settings['css_folder_path'] = 'cms/media/css';
		$settings['enabled'] = Config::NO;
		
		return $settings;
	}
	
	public function set_settings( array $data )
	{
		if ( ! isset($data['enabled']))
		{
			$data['enabled'] = Config::NO;
		}

		return parent::set_settings($data);
	}
	
	public function is_dir( $path )
	{
		$path = DOCROOT . trim($path, '/') . DIRECTORY_SEPARATOR;
		return is_dir( $path );
	}
}