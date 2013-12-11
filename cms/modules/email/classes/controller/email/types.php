<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS
 * @category	Controller
 * @author		ButscHSter
 */
class Controller_Email_Types extends Controller_System_Backend {
	
	public function before()
	{
		parent::before();

		$this->breadcrumbs
			->add(__('Email'), Route::url('email_controllers', array('controller' => 'types')));
	}
	
	public function action_index()
	{
		$this->template->title = __('Email types');
		
		$types = ORM::factory('email_type');
		$pager = Pagination::factory(array(
			'total_items' => $types->reset(FALSE)->count_all(),
			'items_per_page' => 20
		));
		
		$this->template->content = View::factory( 'email/types/index', array(
			'templates' => $types->find_all(),
			'pager' => $pager
		));
	}
}