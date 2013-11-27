<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	API
 * @author		ButscHSter
 */
class KodiCMS_Controller_API_Cache extends Controller_System_Api {
	
	public function before() 
	{
		define('REST_BACKEND', TRUE);
		parent::before();
	}
	
	public function get_clear()
	{
		Cache::instance()->delete_all();
		Kohana::cache('Kohana::find_file()', NULL, -1);
		Kohana::cache('Route::cache()', NULL, -1);
		Kohana::cache('profiler_application_stats', NULL, -1);
		
		Kohana::$log->add(Log::INFO, ':user clear cache')->write();
		
		$this->json['message'] = __( 'Cache has been cleared!' );
	}
}