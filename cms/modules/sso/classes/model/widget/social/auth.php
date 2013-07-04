<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Widget_Social_Auth extends Model_Widget_Social_Registration {
	
	public $backend_template = 'social_registration';
	
	public function get_url($provider)
	{
		return Route::url('accounts-auth', array(
			'directory' => 'oauth', 
			'controller' => $provider, 
			'action' => 'auth')
		);
	}
}