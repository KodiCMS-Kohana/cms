<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Controller_API_Layout extends Controller_System_Api {
	
	public function before() 
	{
		parent::before();
	}

	public function rest_post()
	{
		$layout_name = $this->param('layout_name', NULL, TRUE);
		$layout = new Model_File_Layout($layout_name);

		if (!$layout->is_exists())
		{
			throw HTTP_API_Exception::factory(API::ERROR_PAGE_NOT_FOUND,
				'Layout not found!');
		}

		$layout->name = $this->param('name');
		$layout->content = $this->param('content', NULL);

		$status = $layout->save();
		
		if (!$status)
		{
			throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN,
				'Something went wrong!');
		}
		else
		{
			if ($layout->name != $layout_name)
			{
				$this->json_redirect('layout/edit/' . $layout->name);
			}

			$this->message('Layout has been saved!');
			Observer::notify('layout_after_edit', $layout);
		}
		
		$this->response($layout);
	}
	
	public function rest_put()
	{
		$layout = new Model_File_Layout($this->param('name', NULL, TRUE));
		$layout->content = $this->param('content', NULL);
		
		$status = $layout->save();
		
		if (!$status)
		{			
			throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN,
				'Something went wrong!');
		}
		else
		{
			$this->json_redirect('layout/edit/' . $layout->name);
			$this->message('Layout has been saved!');
			Observer::notify('layout_after_add', $layout);
		}
		
		$this->response($layout);
	}
	
	public function rest_delete()
	{
		$layout_name = $this->param('name', NULL, TRUE);

		$layout = new Model_File_Layout($layout_name);

		if (!$layout->is_exists())
		{
			throw HTTP_API_Exception::factory(API::ERROR_PAGE_NOT_FOUND,
				'Layout not found!',
				array(':name' => $layout_name)
			);
		}

		// find the user to delete
		if (!$layout->is_used())
		{
			if ($layout->delete())
			{
				$this->response($layout);
			}
			else
			{
				throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN,
					'Something went wrong!');
			}
		}
		else
		{
			throw HTTP_API_Exception::factory(API::ERROR_PERMISSIONS,
				'Layout is used! It CAN NOT be deleted!');
		}
	}
	
	public function post_rebuild()
	{
		$layouts = Model_File_Layout::find_all();

		$blocks = array();
		foreach ($layouts as $layout)
		{
			$blocks[$layout->name] = $layout->rebuild_blocks();
		}

		$this->response($blocks);
		$this->message('Layout blocks successfully update!');
	}
	
	public function get_blocks()
	{
		$layout_name = $this->param('layout', NULL);
		$blocks = Widget_Manager::get_blocks_by_layout($layout_name);

		$this->response($blocks);
	}
}