<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_API_Messages extends Controller_System_Api {

	public function get_get()
	{		
		$user_id = $this->param('user_id');		
		$messages = Model_API::factory('api_message')
			->get($user_id, $this->fields);

		$this->json['response'] = $messages;
	}
	
	public function get_by_id()
	{
		$id = $this->param('id');
	}
}