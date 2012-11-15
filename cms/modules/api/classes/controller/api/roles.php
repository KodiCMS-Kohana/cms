<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_API_Roles extends Controller_System_Api {
	
	public function action_get()
	{		
		$uids = $this->param('uids');
		$fields = $this->param('fields');
		
		$roles = Model_API::factory('api_user_role')
			->get($uids, $fields);

		$this->json['response'] = $roles;
	}
}