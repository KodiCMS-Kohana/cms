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
		parent::before();
	}
	
	public function post_save()
	{
		$settings = $this->param('setting', array(), TRUE);
		
		$filter = Filter::factory($settings)
			->rule('site.allow_html_title', FALSE, Config::NO);		
		
		$validation = Validation::factory(array());
		Observer::notify( 'validation_settings', $validation, $filter );

		$filter->run();
		$validation = $validation->copy($filter->data());

		if( ! $validation->check() ) 
			throw new API_Validation_Exception($validation->errors('validation'));
		
		$settings = $validation->data();

		Config::set_from_array($settings);

		Observer::notify('save_settings', $settings );
		Kohana::$log->add(Log::INFO, ':user change Settings')->write();
		$this->message(__( 'Settings has been saved!'));
	}
}