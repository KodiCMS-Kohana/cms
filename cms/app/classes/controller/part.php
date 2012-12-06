<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Part extends Controller_System_Backend {
	
	public function get_index()
	{
		$page_id = (int) $this->request->query('page_id');
		
		$parts = Model_Page_Part::findByPageId($page_id);
		
		$this->json['data'] = array();
		
		foreach ($parts as $part)
		{
			$this->json['data'][] = array(
				'id' => $part->id,
				'name' => $part->name,
				'filter_id' => $part->filter_id,
				'content' => $part->content,
				'is_protected' => $part->is_protected,
				'page_id' => $part->page_id
			);
		}
	}
	
	public function put_index()
	{
		$page_id = (int) $this->request->param('id');
		
		$part_data = json_decode($this->request->body(), TRUE);
		
		$part = Model_Page_Part::findByIdFrom('Model_Page_Part', (int) $part_data['id']);
		
		$part
			->setFromData($part_data, array('id'))
			->save();
	}
	
	public function post_index()
	{
		$part_data = json_decode($this->request->body(), TRUE);
		
		$part = new Model_Page_Part;
		
		$part
			->setFromData($part_data)
			->save();
		
		$this->json = array(
			'id' => $part->id,
			'name' => $part->name,
			'filter_id' => $part->filter_id,
			'content' => $part->content,
			'is_protected' => $part->is_protected,
			'page_id' => $part->page_id
		);
	}
	
	public function delete_index()
	{
		$part_id = (int) $this->request->param('id');
		
		$part = Model_Page_Part::findByIdFrom( 'Model_Page_Part', $part_id );
		$part->delete();
	}
}