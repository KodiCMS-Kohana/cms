<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Scheduler
 * @category	Controller
 * @author		ButscHSter
 */
class Controller_Scheduler extends Controller_System_Backend {

	public function before()
	{
		parent::before();
		
		$this->template->title = __('Scheduler');
		$this->breadcrumbs
			->add($this->template->title, Route::url('backend', array('controller' => 'scheduler')));
	}
	
	public function action_index()
	{
		Assets::css('fullcalendar', ADMIN_RESOURCES . 'libs/fullcalendar/fullcalendar.css', 'global');
		Assets::js('fullcalendar', ADMIN_RESOURCES . 'libs/fullcalendar/fullcalendar.min.js', 'jquery');
		
		$this->template->content = View::factory( 'scheduler/index' );
	}
}