<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS
 * @category	Controller
 * @author		ButscHSter
 */
class Controller_Email_Templates extends Controller_System_Backend {
	
	public function before()
	{
		parent::before();

		$this->breadcrumbs
			->add(__('Email'), Route::url('email_controllers', array('controller' => 'templates')));
	}
	
	public function action_index()
	{
		$this->template->title = __('Email templates');
		
		$templates = ORM::factory('email_template');
		$pager = Pagination::factory(array(
			'total_items' => $templates->reset(FALSE)->count_all(),
			'items_per_page' => 20
		));
		
		$this->template->content = View::factory( 'email/templates/index', array(
			'templates' => $templates->with('email_type')->find_all(),
			'pager' => $pager
		));
	}
}