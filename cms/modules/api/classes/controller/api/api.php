<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/API
 * @category	API
 * @author		ButscHSter
 */
class Controller_API_Api extends Controller_System_API
{
	public function get_new_key()
	{
		if( ! ACL::check('api.new_key'))
		{
			throw HTTP_API_Exception::factory(API::ERROR_PERMISSIONS, 'You dont hanve permissions to generate api key');
		}

		$key = ORM::factory('api_key')->generate('KodiCMS API key');
		
		$this->response($key);
	}
}