<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_API_Pages extends Controller_System_Api {
	
	public function action_get()
	{		
		$uids = $this->param('uids');
		$parent = $this->param('pid');
		$fields = $this->param('fields');
		
		$pages = Model_API::factory('api_page')
			->get($uids, $parent, $fields);

		$this->json['response'] = $pages;
	}
	
	public function action_tags()
	{
		$uid = $this->param('uid', NULL, TRUE);
		$fields = $this->param('fields');
		
		$tags = Model_API::factory('api_page_tag')
			->get(NULL, $fields, $uid);
		
		$this->json['response'] = $tags;
	}
	
	public function action_uri()
	{
		$uri = $this->param('uri', NULL, TRUE);
		$fields = $this->param('fields');

		$page = Model::factory('api_page')
			->find_by_uri($uri, $fields);
		
		$this->json['response'] = $page;
	}
}