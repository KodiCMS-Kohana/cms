<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Controller_API_Session extends Controller_System_Api {
	
	public function get_clear()
	{
		if (!ACL::check('system.session.clear'))
		{
			throw HTTP_API_Exception::factory(API::ERROR_PERMISSIONS, 'You don\'t have permission to :permission', array(
				':permission' => __('Сlear user sessions')
			));
		}

		if (Session::$default == 'database')
		{
			DB::delete('sessions')->execute();
			Kohana::$log->add(Log::INFO, ':user clear  user sessions')->write();
			$this->message('User sessions has been cleared!');
		}
	}
}