<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	API
 * @author		ButscHSter
 */
class KodiCMS_Controller_API_Page_Parts extends Controller_System_Api {
	
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
		$part = ORM::factory('page_part', (int) $id);
		
		$response = $part
			->values($this->params())
			->save()
			->object();

		$response['is_developer'] = (int) Auth::has_permissions('administrator, developer');
		$this->response($response);
	}
	
	public function rest_post()
	{
		$part = ORM::factory('page_part');

		$part
			->values($this->params())
			->save()
			->as_array();

		$part['is_developer'] = (int) Auth::has_permissions('administrator, developer');
		$this->response($part);
	}
	
	public function rest_delete()
	{
		$id = $this->param('id', NULL, TRUE);
		
		$part = ORM::factory('page_part', (int) $id);
		$part->delete();
	}
}