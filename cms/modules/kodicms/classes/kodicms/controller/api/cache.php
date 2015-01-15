<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Controller_API_Cache extends Controller_System_Api {
	
	public function rest_delete()
	{
		if (!ACL::check('system.cache.clear'))
		{
			throw HTTP_API_Exception::factory(API::ERROR_PERMISSIONS, 'You dont hanve permissions to clear cache');
		}

		if (Kohana::$caching === TRUE)
		{
			Cache::register_shutdown_function();
		}

		Kohana::$log->add(Log::INFO, ':user clear cache')->write();

		$this->message('Cache has been cleared!');
	}
}