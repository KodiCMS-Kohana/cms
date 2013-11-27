<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	API
 * @author		ButscHSter
 */
class KodiCMS_Controller_API_Settings extends Controller_System_Api {
	
	protected $_check_token = TRUE;
	
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

		Kohana::$log->add(Log::INFO, ':user change Settings')->write();
		
		$this->message(__( 'Settings has been saved!'));
	}
}