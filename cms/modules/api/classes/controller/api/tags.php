<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_API_Tags extends Controller_System_Api {
	
	public function get_get()
	{		
		$uids = $this->param('uids', '');
		$fields = $this->param('fields', '');
		
		$tags = Model_API::factory('api_page_tag')
			->get($uids, $fields);

		$this->json['response'] = $tags;
	}
}