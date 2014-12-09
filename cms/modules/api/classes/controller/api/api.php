<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/API
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright  (c) 2012-2014 butschster
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_API_Api extends Controller_System_API
{
	public function get_keys()
	{
		if (!ACL::check('system.api.view_keys'))
		{
			throw HTTP_API_Exception::factory(API::ERROR_PERMISSIONS, 'You dont hanve permissions to view api keys');
		}

		$keys = ORM::factory('api_key')->find_all()->as_array('id', 'description');
		$curret_key = Config::get('api', 'key');
	
		unset($keys[$curret_key]);

		$this->response($keys);
	}
	
	public function put_key()
	{
		if (!ACL::check('system.api.new_key'))
		{
			throw HTTP_API_Exception::factory(API::ERROR_PERMISSIONS, 'You dont hanve permissions to generate api key');
		}
		
		$description = $this->param('description', NULL, TRUE);	

		$key = ORM::factory('api_key')->generate($description);

		$this->response($key);
	}
	
	public function delete_key()
	{
		if (!ACL::check('system.api.delete_key'))
		{
			throw HTTP_API_Exception::factory(API::ERROR_PERMISSIONS, 'You dont hanve permissions to refresh api key');
		}

		$curret_key = Config::get('api', 'key');
		$key = $this->param('key', NULL, TRUE);
		
		if($key == $curret_key)
		{
			throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN, 'You dont hanve permissions to delete KodiCMS api key');
		}

		$this->response((bool) ORM::factory('api_key', $key)->delete());
	}

	public function post_refresh()
	{
		if (!ACL::check('system.api.refresh_key'))
		{
			throw HTTP_API_Exception::factory(API::ERROR_PERMISSIONS, 'You dont hanve permissions to refresh api key');
		}

		$key = $this->param('key', NULL, TRUE);

		$key = ORM::factory('api_key')->refresh($key);
		Config::set('api', 'key', $key);
		$this->response($key);
	}
}