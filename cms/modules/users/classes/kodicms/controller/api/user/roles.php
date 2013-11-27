<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Users
 * @category	API
 * @author		ButscHSter
 */
class KodiCMS_Controller_API_User_Roles extends Controller_System_Api {
	
	public function get_get()
	{		
		$uids = $this->param('uids');
		
		$roles = Model_API::factory('api_user_role')
			->get_all($uids, $this->fields);

		$this->response($roles);
	}
}