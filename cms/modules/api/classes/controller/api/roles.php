<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_API_Roles extends Controller_System_Api {
	
	public function get_get()
	{		
		$uids = $this->param('uids');
		
		$roles = Model_API::factory('api_user_role')
			->get($uids, $this->fields);

		$this->response($roles);
	}
}