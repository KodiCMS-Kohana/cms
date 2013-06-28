<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class KodiCMS_Controller_API_User_Roles extends Controller_System_Api {
	
	public function get_get()
	{		
		$uids = $this->param('uids');
		
		$roles = Model_API::factory('api_user_role')
			->get_all($uids, $this->fields);

		$this->response($roles);
	}
}