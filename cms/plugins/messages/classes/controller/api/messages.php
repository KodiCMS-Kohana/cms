<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_API_Messages extends Controller_System_Api {

	public function get_get()
	{		
		$user_id = $this->param('uid', NULL, TRUE);
		$parent_id = (int) $this->param('pid');
		$messages = Model_API::factory('api_message')
			->get_all($user_id, $parent_id, $this->fields);

		$this->response($messages);
	}
	
	public function get_by_id()
	{
		$id = $this->param('id', NULL, TRUE);
	}
}