<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	API
 * @author		ButscHSter
 */
class KodiCMS_Controller_API_Session extends Controller_System_Api {
	
	public function get_clear()
	{
		if( ! ACL::check('system.session.clear'))
		{
			throw HTTP_API_Exception::factory(API::ERROR_PERMISSIONS, 'You dont hanve permissions to clear user sessions');
		}
		
		if( Session::$default == 'database' )
		{
			DB::delete('sessions')->execute();
			Kohana::$log->add(Log::INFO, ':user clear  user sessions')->write();
			$this->message('User sessions has been cleared!');
		}
	}
}