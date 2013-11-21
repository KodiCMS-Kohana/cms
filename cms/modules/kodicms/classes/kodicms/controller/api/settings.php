<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class KodiCMS_Controller_API_Settings extends Controller_System_Api {
	
	public function before() 
	{
		define('REST_BACKEND', TRUE);
		parent::before();
	}
	
	public function post_save()
	{
		$settings = $this->param('setting', array(), TRUE);

		if( !isset( $settings['site']['allow_html_title'] ) )
		{
			$settings['site']['allow_html_title'] = 'off';
		}
		
		Config::set_from_array($settings);

		Observer::notify('save_settings', $settings );

		Kohana::$log->add(Log::INFO, 'Change settings')->write();
		
		$this->json['message'] = __( 'Settings has been saved!' );
	}
}