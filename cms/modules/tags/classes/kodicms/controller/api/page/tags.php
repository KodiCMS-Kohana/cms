<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	API
 * @author		ButscHSter
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