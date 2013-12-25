<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Update extends Controller_System_Backend {
	
	public function before()
	{
		parent::before();

		$this->breadcrumbs
			->add(__('Update'), Route::url('backend', array('controller' => 'update')));
	}
	
	public function action_index() 
	{
		$this->template->title = __('Update');
		
		$db_sql = Install::schema();
		$file_sql = Install::install_schema();

		$compare = new Database_Helper;
		$diff = $compare->get_updates($db_sql, $file_sql, TRUE);
		
		$this->template->content = View::factory( 'update/index', array(
			'actions' => $diff,
		) );
	}
}