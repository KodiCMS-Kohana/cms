<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class KodiCMS_Controller_API_Pages extends Controller_System_Api {
	
	public function get_get()
	{		
		$uids = $this->param('uids');
		$parent = $this->param('pid');
		
		$pages = Model_API::factory('api_page')
			->get_all($uids, $parent, $this->fields);

		$this->response($pages);
	}
	
	public function get_tags()
	{
		$uid = $this->param('uid', NULL, TRUE);
		
		$tags = Model_API::factory('api_page_tag')
			->get_all(NULL, $this->fields, $uid);
		
		$this->response($tags);
	}
	
	public function get_by_uri()
	{
		$uri = $this->param('uri', NULL, TRUE);

		$page = Model::factory('api_page')
			->find_by_uri($uri, $this->fields);
		
		$this->response($page);
	}
}