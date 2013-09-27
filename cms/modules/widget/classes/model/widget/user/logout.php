<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Widget_User_Logout extends Model_Widget_Decorator {
	
	public $use_template = FALSE;

	public function fetch_data()
	{
		
	}
	
	public function render( $params = array( ) )
	{
		return;
	}

	public function on_page_load()
	{
		AuthUser::logout();
		Observer::notify('admin_after_logout', array(AuthUser::getUserName()));
		
		HTTP::redirect($this->get('next_url', Request::current()->referrer()));
	}
}