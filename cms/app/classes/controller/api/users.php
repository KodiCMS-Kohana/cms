<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_API_Users extends Controller_System_Api {
	
	public function get_get()
	{		
		$uids = $this->param('uids');
		
		$users = Model_API::factory('api_user')
			->get($uids, $this->fields);

		$this->response($users);
	}
	
	public function get_roles()
	{
		$uid = $this->param('uid', NULL, TRUE);
		
		$roles = Model_API::factory('api_user_role')
			->get(NULL, $this->fields, $uid);
		
		$this->response($roles);
	}
}