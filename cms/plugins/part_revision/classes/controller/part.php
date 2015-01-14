<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Part_Revision
 * @category	Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_Part extends Controller_System_Backend
{
	public function before()
	{
		parent::before();
		
		$this->breadcrumbs
			->add(__('Pages'), Route::get('backend')->uri(array('controller' => 'page')));
	}
	
	public function action_revision()
	{
		Assets::js('diff_text_tool', ADMIN_RESOURCES . 'libs/diff_match_patch.js', 'global');
		Assets::js('part_revision', ADMIN_RESOURCES . 'js/controller/part_revision.js', 'global');
		
		$part_id = (int) $this->request->param('id');
		$part = ORM::factory('page_part', $part_id);
		
		$parts = DB::select()
			->from('part_revision')
			->order_by('created_on', 'desc')
			->as_object();
		
	if (!empty($part->id))
		{
			$page = ORM::factory('page', $part->page_id);

			$this->breadcrumbs
				->add($page->title, Route::get('backend')->uri(array(
					'controller' => 'page',
					'action' => 'edit',
					'id' => $page->id
				)));

			$this->set_title(__('Revision for part :name', array(':name' => $part->name)));

			$parts->where('part_id', '=', $part_id);
		}
		else
		{
			$this->set_title(__('Parts revision'));
		}


		$this->template->content = View::factory('part/revision', array(
			'part' => $part,
			'parts' => $parts->execute()
		));
	}
	
	public function action_revert()
	{
		$revision_id = (int) $this->request->param('id');
		
		$revision = DB::select()
			->from('part_revision')
			->where('id', '=', $revision_id)
			->limit(1)
			->as_object()
			->execute()
			->current();

		$part = ORM::factory('page_part', $revision->part_id)
			->values(array('content' => $revision->content))
			->save();
		
		DB::delete('part_revision')->where('id', '=', $revision_id)->execute();

		$this->go_back();
	}
}