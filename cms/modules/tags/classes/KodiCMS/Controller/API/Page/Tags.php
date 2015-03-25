<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Tags
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Controller_API_Page_Tags extends Controller_System_Api {
	
	public function get_get()
	{
		$uids = $this->param('uids');

		$tags = Model_API::factory('api_page_tag')
			->get_all($uids, $this->fields);

		$this->response($tags);
	}

}