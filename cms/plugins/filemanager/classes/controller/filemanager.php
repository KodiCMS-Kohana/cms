<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_FileManager extends Controller_System_Plugin {
	
	public function action_index()
	{
		$this->template->scripts[] = ADMIN_RESOURCES . 'libs/jquery.uploader.js';

		$path = trim($this->request->param('path', '/'), '/');
		
		try
		{
			$filesystem = Model_FileSystem::factory(PUBLICPATH . $path);
		}
		catch(Exception $e)
		{
			Messages::success($e->getMessage());
			return $this->go('filemanager');
		}
		
		$this->_breadcrumbs($filesystem, $path );
		
		$this->template->content = View::factory('filemanager/index', array(
			'path' => $path,
			'filesystem' => $filesystem->iteratePaths()
		));
	}
	
	public function action_view()
	{
		$path = trim($this->request->param('path', '/'), '/');

		try
		{
			$filesystem = Model_FileSystem::factory(PUBLICPATH . $path);
		}
		catch(Exception $e)
		{
			Messages::success($e->getMessage());
			return $this->go('filemanager');
		}
		
		if($this->request->method() === Request::POST)
		{
			return $this->_save($filesystem);
		}
		
		$this->_breadcrumbs($filesystem, $path );

		$this->template->content = View::factory('filemanager/view', array(
			'filesystem' => $filesystem,
			'content' => $filesystem->getContent()
		));
	}
	
	private function _save( Model_FileSystem $filesystem )
	{
		$data = $_POST['file'];
		
		if($data['name'] != $filesystem->getFilename())
		{
			$filesystem = $filesystem->rename($data['name']);
		}
		
		if(isset($data['content']))
		{
			$filesystem->setContent($data['content']);
		}
		
		// save and quit or save and continue editing?
		if ( isset( $_POST['commit'] ) )
		{
			$this->go( 'filemanager/' . $filesystem->getParent()->getRelativePath() );
		}
		else
		{
			$this->go('filemanager/view/' . $filesystem->getRelativePath());
		}
	}
	
	public function action_delete()
	{
		$path = trim($this->request->param('path', '/'), '/');
		
		try
		{
			$filesystem = Model_FileSystem::factory(PUBLICPATH . $path);
		}
		catch(Exception $e)
		{
			Messages::success($e->getMessage());
			return $this->go('filemanager');
		}
		
		$path = $filesystem->getParent()->getRelativePath();

		if($filesystem->delete())
		{
			$this->go('filemanager/' . $path);
		}
	}
	
	public function action_upload()
	{
		$this->auto_render = FALSE;

		$path = PUBLICPATH . ltrim($this->request->param('path', ''), DIRECTORY_SEPARATOR);
		
		$filename = $_FILES['file']['name'];
		if(  file_exists( $path . DIRECTORY_SEPARATOR . $filename))
		{
			$filename = NULL;
		}

		$file = Upload::save($_FILES['file'], $filename, $path);
		
		$file = Model_FileSystem::factory($file);

		if($file)
		{
			echo View::factory('filemanager/item', array(
				'icon' => 'file', 'file' => $file
			));
		}	
	}
	
	public function action_folder()
	{
		$this->auto_render = FALSE;

		$name = Arr::get($_POST, 'name');
		$path = Arr::get($_POST, 'path');
		
		$this->json = array();
		
		try
		{
			$filesystem = Model_FileSystem::factory(PUBLICPATH . $path);
			
			$filesystem->createFolder($name);
			
			$this->json['status'] = FALSE;
			$this->json['message'] = __('Folder created');

			return;
		}
		catch(Exception $e)
		{
			$this->json['message'] = $e->getMessage();
			$this->json['status'] = FALSE;
		}
	}
	
	public function action_chmod()
	{
		$this->auto_render = FALSE;

		$chmod = Arr::get($_POST, 'chmod');
		$path = Arr::get($_POST, 'path');
		
		$this->json = array();
		
		try
		{
			$filesystem = Model_FileSystem::factory(PUBLICPATH . $path);
			
			$filesystem->setPerms($chmod);
			
			$this->json['status'] = FALSE;
			$this->json['message'] = __('File chmod updated');

			return;
		}
		catch(Exception $e)
		{
			$this->json['message'] = $e->getMessage();
			$this->json['status'] = FALSE;
		}
	}

	private function _breadcrumbs( Model_FileSystem $filesystem, $path )
	{
		$paths = array();
		$path_array = $filesystem->getPathArray();

		$i = 1;
		foreach ($path_array as $link => $name)
		{
			if($i < count($path_array))
			{
				$paths[] = HTML::anchor( 'filemanager/'.$link, $name);
			}
			else
			{
				$paths[] = $name;
			}

			$i++;
		}
		
		if(!empty($path))
		{
			$this->template->breadcrumbs = Arr::merge(array(
				HTML::anchor( 'filemanager', __('Filem anager')),
			), $paths);
		}
	}
}