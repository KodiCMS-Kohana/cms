<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_Scheduler extends Controller_System_Backend {

	public function before()
	{
		parent::before();
		$this->breadcrumbs
			->add(__('scheduler'), $this->request->controller());
	}
	
	public function action_index()
	{
		$this->scripts[] = ADMIN_RESOURCES . 'libs/fullcalendar/fullcalendar.min.js';
		$this->styles[] = ADMIN_RESOURCES . 'libs/fullcalendar/fullcalendar.css';
		
		$this->template->content = View::factory( 'scheduler/index' );
	}
}