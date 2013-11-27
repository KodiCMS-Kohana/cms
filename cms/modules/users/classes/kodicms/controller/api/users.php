<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Users
 * @category	API
 * @author		ButscHSter
 */
class KodiCMS_Controller_API_Users extends Controller_System_Api {

	public function get_get()
	{		
		$uids = $this->param('uids');
		
		if(!empty($uids))
			$uids = explode(',', $uids);

		$users = Model_API::factory('api_user')
			->get_all($uids, $this->fields);

		$this->response($users);
	}
	
	/**
	 * @link /api-users.like
	 */
	public function get_like()
	{
		$query = $this->param('key', NULL, TRUE);
		$search_in = $this->param('sin', NULL);

		$users = Model_API::factory('api_user')
			->get_like($query, $search_in, $this->fields);
		
		$this->response($users);
	}

	public function get_roles()
	{
		$uid = $this->param('uid', NULL, TRUE);
		
		$roles = Model_API::factory('api_user_role')
			->get_all(NULL, $this->fields, $uid);
		
		$this->response($roles);
	}
}