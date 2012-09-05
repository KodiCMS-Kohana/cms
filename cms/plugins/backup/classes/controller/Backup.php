<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Backup extends Controller_System_Plugin {
	
	public function action_index() 
	{
		$files = array();

		$handle = opendir(BACKUP_PLUGIN_FOLDER);
		while (false !== ($file = readdir($handle))) 
		{
			if (preg_match("/^.+?\.sql$/", $file, $m)) 
			{
				$date_create = preg_replace('![^\d]*!', '', $file);
				$date_create = preg_replace('#^([\d]{4})([\d]{2})([\d]{2})([\d]{2})([\d]{2})([\d]{2})$#', '$3/$2/$1 $4:$5:$6', $date_create);
	
				$files[$file] = array(
					'size' => Text::bytes(filesize(BACKUP_PLUGIN_FOLDER.$file)),
					'path' => BACKUP_PLUGIN_FOLDER.$file,
					'date' => $date_create
				);	
			}
		}

		closedir($handle);
		
		$this->template->content = View::factory('backup/index', array(
			'files' => $files
		));
	}
	
	public function action_view()
	{
		$file = $this->request->param('id');
		$backup = Model_Backup::factory(BACKUP_PLUGIN_FOLDER . $file)
			->view();
		
		$this->template->content = View::factory('backup/view', array(
			'file' => $backup,
			'filename' => $file
		));
	}
	
	public function action_create()
	{
		$this->auto_render = FALSE;

		$backup = Model_Backup::factory(BACKUP_PLUGIN_FOLDER . 'db-'.date('Y-m-d-H-i-s').'.sql')
			->create()
			->save();
		
		$this->go_back();
	}
	
	public function action_restore()
	{
		$this->auto_render = FALSE;

		$file = $this->request->param('id');

		$backup = Model_Backup::factory(BACKUP_PLUGIN_FOLDER . $file)
			->restore();
		
		$this->go_back();
	}
	
	public function action_delete()
	{
		$this->auto_render = FALSE;

		$file = $this->request->param('id');

		if(!file_exists(BACKUP_PLUGIN_FOLDER.$file))
		{
			throw new Core_Exception('File :file not exist', array(':file' => $file));
		}
		
		unlink(BACKUP_PLUGIN_FOLDER.$file);
		$this->go_back();
	}
	
	public function action_upload()
	{
		$this->auto_render = FALSE;

		$errors = array();

		# Проверяем файл
		if(!isset($_FILES['file']))
		{
			$this->go_back();
		}
		
		$file = $_FILES['file'];
		
		if (!is_dir($this->dir))
		{
			$errors[] = 'Folder '.$this->dir.' not exist!';
		}
		
		if(!is_writable($this->dir))
		{
			$errors[] = 'Folder '.$this->dir.' mus be writable!';
		}

		# Проверяем на пустоту
		if(!Upload::not_empty($file))
		{
			$errors[] = 'File is not uploaded!';
		}

		# Проверяем на расширение
		if(!Upload::type($file, array('sql')))
		{
			$errors[] = 'Bad format of file!';
		}
		
		if(!empty($errors))
		{
			//Flash::set('upload_errors', $errors);
			$this->go_back();
		}

		# Имя файла (его нужно записать в базу!)
		$filename = 'uploaded-db-'.date('Y-m-d-H-i-s').'.sql';

		Upload::$default_directory = BACKUP_PLUGIN_FOLDER;
			
		# Cохраняем оригинал и продолжаем работать, если ок: 
		if ($file = Upload::save($file, $filename, NULL, 0777))
		{
			//Flash::set('success', __('File uploaded succefully'));
			$this->go_back();
		}
	}
}