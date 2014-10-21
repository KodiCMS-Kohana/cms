<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	API
 * @author		ButscHSter
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
			// Enable the Kohana shutdown handler, which clear cache
			register_shutdown_function(array('Cache', 'clear_all'));
		}

		Kohana::$log->add(Log::INFO, ':user clear cache')->write();

		$this->message('Cache has been cleared!');
	}
}