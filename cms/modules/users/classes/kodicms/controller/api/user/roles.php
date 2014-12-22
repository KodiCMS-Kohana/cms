<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Users
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
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