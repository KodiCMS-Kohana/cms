<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_REST_Part extends Controller_Ajax_JSON {
	
	public function action_get()
	{
		$page_id = (int) $this->request->query('page_id');
		
		$parts = Model_Page_Part::findByPageId($page_id);
		
		$this->json['data'] = array();
		
		foreach ($parts as $part)
		{
			$this->json['data'][] = $part->prepare_data();
		}
	}
	
	public function action_put()
	{
		$part_data = json_decode($this->request->body(), TRUE);
		$part = Record::findByIdFrom('Model_Page_Part', (int) $part_data['id']);
		
		$part
			->setFromData($part_data, array('id'))
			->save();
		
		$this->json = $part->prepare_data();
	}
	
	public function action_post()
	{
		$part_data = json_decode($this->request->body(), TRUE);
		
		$part = new Model_Page_Part;
		
		$part
			->setFromData($part_data)
			->save();
		
		$this->json = $part->prepare_data();
	}
	
	public function action_delete()
	{
		$part_id = (int) $this->request->param('id');
		
		$part = Model_Page_Part::findByIdFrom( 'Model_Page_Part', $part_id );
		$part->delete();
	}
}