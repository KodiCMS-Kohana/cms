<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Jobs
 * @category	Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_Jobs extends Controller_System_Backend {

	public function before()
	{
		parent::before();
		
		$this->breadcrumbs
			->add(__('Jobs'), Route::get('backend')->uri(array('controller' => 'jobs')));
	}
	
	public function action_index()
	{
		$jobs = ORM::factory('job');
		
		$pager = Pagination::factory(array(
			'total_items' => $jobs->reset(FALSE)->count_all(),
			'items_per_page' => 20
		));
		
		$this->set_title(__('Jobs'), FALSE);
		
		$this->template->content = View::factory('jobs/index', array(
			'jobs' => $jobs
				->limit($pager->items_per_page)
				->offset($pager->offset)
				->find_all(),
			'pager' => $pager
		));
	}
	
	public function action_add()
	{
		$data = Flash::get('post_data', array());

		$job = ORM::factory('job')->values($data);

		// check if trying to save
		if (Request::current()->method() == Request::POST)
		{
			return $this->_add($job);
		}

		$this->set_title(__('Add job'));

		$this->template->content = View::factory('jobs/edit', array(
			'action' => 'add',
			'job' => $job
		));
	}
	
	private function _add($job)
	{
		$data = $this->request->post();
		$this->auto_render = FALSE;

		Flash::set('post_data', $data);

		$job->values($data);

		try
		{
			if ($job->create())
			{
				Kohana::$log->add(Log::INFO, 'Job :job has been added by :user', array(
					':job' => HTML::anchor(Route::get('backend')->uri(array(
						'controller' => 'scheduler',
						'action' => 'edit',
						'id' => $job->id
					)), $job->name),
				))->write();

				Flash::clear('post_data');
				Messages::success(__('Job has been saved!'));
			}
		} catch (ORM_Validation_Exception $e)
		{
			Messages::errors($e->errors('validation'));
			$this->go_back();
		}

		// save and quit or save and continue editing?
		if ($this->request->post('commit') !== NULL)
		{
			$this->go(Route::get('backend')->uri(array(
				'controller' => 'jobs'
			)));
		}
		else
		{
			$this->go(Route::get('backend')->uri(array(
				'controller' => 'jobs',
				'action' => 'edit',
				'id' => $job->id
			)));
		}
	}
	
	public function action_edit( )
	{
		$id = $this->request->param('id');

		$job = ORM::factory('job', $id);

		if (!$job->loaded())
		{
			Messages::errors(__('Job not found!'));
			$this->go_back();
		}

		// check if trying to save
		if (Request::current()->method() == Request::POST)
		{
			return $this->_edit($job);
		}

		$this->set_title(__('Edit job'));

		$this->template->content = View::factory('jobs/edit', array(
			'action' => 'edit',
			'job' => $job,
			'history' => View::factory('jobs/history', array(
				'history' => $job->logs->order_by('created_on', 'desc')->limit(30)->find_all()
			))
		));
	}
	
	private function _edit( $job )
	{
		$data = $this->request->post();
		$this->auto_render = FALSE;

		$job->values($data);

		try
		{
			if ($job->update())
			{
				Kohana::$log->add(Log::INFO, 'Job :job has been updated by :user', array(
					':job' => HTML::anchor(Route::get('backend')->uri(array(
						'controller' => 'scheduler',
						'action' => 'edit',
						'id' => $job->id
					)), $job->name),
				))->write();

				Flash::clear('post_data');

				Messages::success(__('Job has been saved!'));
			}
		} 
		catch (ORM_Validation_Exception $e)
		{
			Messages::errors($e->errors('validation'));
			$this->go_back();
		}

		// save and quit or save and continue editing?
		if ($this->request->post('commit') !== NULL)
		{
			$this->go(Route::get('backend')->uri(array(
				'controller' => 'jobs'
			)));
		}
		else
		{
			$this->go(Route::get('backend')->uri(array(
				'controller' => 'jobs',
				'action' => 'edit',
				'id' => $job->id
			)));
		}
	}
	
	public function action_delete()
	{
		$this->auto_render = FALSE;

		$id = (int) $this->request->param('id');

		$job = ORM::factory('job', $id);

		if (!$job->loaded())
		{
			Messages::errors(__('Job not found!'));
			$this->go_back();
		}

		try
		{
			$job->delete();
			Messages::success(__('Job has been deleted!'));
		} catch (Kohana_Exception $e)
		{
			Messages::errors(__('Something went wrong!'));
			$this->go_back();
		}

		$this->go(Route::get('backend')->uri(array(
			'controller' => 'jobs'
		)));
	}
	
	public function action_run()
	{
		$this->auto_render = FALSE;

		$id = (int) $this->request->param('id');

		$job = ORM::factory('job', $id);

		if (!$job->loaded())
		{
			Messages::errors(__('Job not found!'));
			$this->go_back();
		}

		$job->run();
		Messages::success(__('Job run success!'));
		$this->go_back();
	}
}
