<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Email extends Controller_System {

	public function action_settings()
	{
		// check if trying to save
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_save();
		}
		
		$this->template->title = __('Email settings');
		$this->breadcrumbs
			->add($this->template->title, Route::url( 'backend', array('controller' => 'email', 'action' => 'settings')));

		$this->template->content = View::factory( 'email/index', array(
			'settings' => Config::get('email'),
			'drivers' => Config::get('email', 'drivers', array()),
		) );
	}
	
	private function _save()
	{
		$data = $this->request->post('setting');
		
		$validation = Validation::factory($data['email'])
			->rule('default', 'email')
			->rule('default', 'not_empty')
			->rule('driver', 'in_array', array(':value', array_keys(Config::get('email', 'drivers', array()))))
			->label('default', 'Default email address')
			->label('driver', 'SMTP Driver');
		
		if(!$validation->check())
		{
			Messages::errors($validation->errors('validation'));
			$this->go_back();
		}
		
		Config::set_from_array($data);

		Kohana::$log->add(Log::INFO, ':user change email settings')->write();
		
		Messages::success( __( 'Settings has been saved!' ) );

		$this->go_back();
	}
}