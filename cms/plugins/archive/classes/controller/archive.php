<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Archive extends Controller_System_Plugin
{
	public function action_index() 
	{
		$page_id = $this->request->param('id');
		$page = Record::findByIdFrom('Page', (int) $page_id);

		if ( ! $page)
		{
			Flash::set('error', __('Page not found!'));
			throw new HTTP_Exception_404('Page not found');
		}

		$pager = Pagination::factory(array(
			'total_items' => Record::countFrom('Page', 'parent_id = ' . (int) $page_id)
		));

		$items = $pages = Record::findAllFrom('Page', 'parent_id = ' . (int) $page_id . ' ORDER BY created_on DESC' . $pager->sql_limit);
		$this->template->content = View::factory('archive/index', array(
			'items' => $items,
			'page'	=> $page,
			'pager' => $pager
		));
	}

}