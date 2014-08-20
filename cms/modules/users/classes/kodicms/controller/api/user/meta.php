<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Users
 * @category	API
 * @author		ButscHSter
 */
class KodiCMS_Controller_API_User_Meta extends Controller_System_Api {
	
	/**
	 * Api.get('user-meta', {key: 'test'}, function(r){console.log(r)});
	 * Api.get('user-meta', {key: 'test', uid: 1}, function(r){console.log(r)});
	 */
	public function rest_get()
	{		
		$key = $this->param('key', NULL, TRUE);
		$user_id = $this->param('uid', NULL);

		$this->response(Model_User_Meta::get($key, NULL, $user_id));
	}
	
	/**
	 * Api.post('user-meta', {key: 'test', value:'hello world2'}, function(r){console.log(r)});
	 */
	public function rest_post()
	{
		$key = $this->param('key', NULL, TRUE);
		$value = $this->param('value', NULL, TRUE);
		$user_id = $this->param('uid', NULL);
		
		$this->response(Model_User_Meta::set($key, $value, $user_id));
	}
	
	/**
	 * Api.delete('user-meta', {key: 'test'}, function(r){console.log(r)});
	 */
	public function rest_delete()
	{		
		$key = $this->param('key', NULL, TRUE);
		$user_id = $this->param('uid', NULL);

		$this->response(Model_User_Meta::delete($key, $user_id));
	}
}