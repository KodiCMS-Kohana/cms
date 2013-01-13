<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_API_Parts extends Controller_System_Api {
	
	public function get_get()
	{		
		$page_id = $this->param('page_id', NULL, TRUE);
		$fields = $this->param('fields', '');
		
		$parts = Model_API::factory('api_page_part')
			->get($page_id, $fields);

		$this->response($parts);
	}
}