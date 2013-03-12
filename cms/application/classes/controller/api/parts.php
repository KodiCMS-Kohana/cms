<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_API_Parts extends Controller_System_Api {
	
	public function get_get()
	{		
		$page_id = $this->param('pid', NULL, TRUE);
		
		$parts = Model_API::factory('api_page_part')
			->get_all($page_id, $this->fields);

		$this->response($parts);
	}
	
	public function rest_get()
	{
		return $this->get_get();
	}
	
	public function rest_put()
	{
		$id = $this->param('id', NULL, TRUE);
		$part = Record::findByIdFrom('Model_Page_Part', (int)$id);
		
		$part
			->setFromData($this->params(), array('id'))
			->save();
		
		Cache::instance()->delete_tag('page_parts');
		$this->response($part->prepare_data());
	}
	
	public function rest_post()
	{
		$part = new Model_Page_Part;
		
		$part
			->setFromData($this->params())
			->save();
		
		Cache::instance()->delete_tag('page_parts');
		$this->response($part->prepare_data());
	}
	
	public function rest_delete()
	{
		$id = $this->param('id', NULL, TRUE);
		
		$part = Model_Page_Part::findByIdFrom( 'Model_Page_Part', (int) $id );
		$part->delete();
	}
}