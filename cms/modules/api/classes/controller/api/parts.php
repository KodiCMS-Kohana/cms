<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_API_Parts extends Controller_System_Api {
	
	public function action_get()
	{		
		$uids = $this->param('uids', '');
		$fields = $this->param('fields', '');
		
		$parts = Model_API::factory('api_page_part')
			->get($uids, $fields);

		$this->json['response'] = $parts;
	}
}