<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Update extends Controller_System_Backend {
	
	public function before()
	{
		parent::before();

		$this->breadcrumbs
			->add(__('Update'), Route::get('backend')->uri(array('controller' => 'update')));
	}
	
	public function action_index() 
	{
		$this->template->content = View::factory( 'update/index');
	}
	
	public function action_database() 
	{
		Assets::package('ace');
		
		$this->template->title = __('Update');
		
		$db_sql = Database_Helper::schema();
		$file_sql = Database_Helper::install_schema();

		$compare = new Database_Helper;
		$diff = $compare->get_updates($db_sql, $file_sql, TRUE);
		
		$this->template->content = View::factory( 'update/database', array(
			'actions' => $diff,
		));
	}
	
	public function action_patches()
	{
		if($this->request->method() === Request::POST)
		{
			return $this->_apply_patch();
		}

		$this->template->content = View::factory( 'update/patches', array(
			'patches' => array_flip(Patch::find_all()),
		));
	}
	
	private function _apply_patch()
	{
		$patch = $this->request->post('patch');
		
		try
		{
			Patch::apply($patch);
		} 
		catch (Validation_Exception $ex)
		{
			Messages::errors($ex->errors());
		}
		catch (Kohana_Exception $ex) 
		{
			Messages::errors($ex->getMessage());
		}
		
		$this->go_back();
	}
}