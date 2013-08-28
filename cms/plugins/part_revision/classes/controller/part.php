<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Part extends Controller_System_Backend
{
	public function before()
	{
		parent::before();
		
		$part_id = (int) $this->request->param('id');
		$part = Record::findByIdFrom('Model_Page_Part', $part_id);
		
		if(empty($part->id)) 
		{
			Messages::errors( __( 'Part not found!' ) );
			$this->go(array(
				'controller' => 'page'
			));
		}

		$page = Record::findByIdFrom( 'Model_Page', $part->page_id );
		
		$this->breadcrumbs
			->add(__('Pages'), Route::url('backend', array('controller' => 'page')))
			->add($page->title, Route::url('backend', array(
				'controller' => 'page',
				'action' => 'edit',
				'id' => $page->id
			)))
			->add(__('Revision for part :name', array(':name' => $part->name)));
	}
	
	public function action_revision()
	{
		Assets::js('diff_text_tool', ADMIN_RESOURCES . 'libs/diff_match_patch.js', 'global');
		Assets::js('part_revision', ADMIN_RESOURCES . 'js/controller/part_revision.js', 'global');
		
		$part_id = (int) $this->request->param('id');
		$part = Record::findByIdFrom('Model_Page_Part', $part_id);
		
		$this->template->content = View::factory('part/revision', array(
			'part' => $part,
			'parts' => DB::select()
						->from('part_revision')
						->where('part_id', '=', $part_id)
						->order_by('created_on', 'desc')
						->as_object()
						->execute()
		));
	}
}