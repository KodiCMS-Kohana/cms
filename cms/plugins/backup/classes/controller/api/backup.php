<?php defined('SYSPATH') or die('No direct access allowed.');

if(!defined('BACKUP_PLUGIN_FOLDER')) define('BACKUP_PLUGIN_FOLDER', DOCROOT . 'backups' . DIRECTORY_SEPARATOR);

/**
 * @package		KodiCMS/Backup
 * @category	API
 * @author		ButscHSter
 */
class Controller_API_Backup extends Controller_System_API {
	
	public function post_upload()
	{
		$file = $this->param('file', array(), TRUE);

		if (!is_dir(BACKUP_PLUGIN_FOLDER))
		{
			throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN, 'Folder (:folder) not exist!', array(
				':folder' => BACKUP_PLUGIN_FOLDER ));
		}
		
		if(!is_writable(BACKUP_PLUGIN_FOLDER))
		{
			throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN, 'Folder (:folder) must be writable!', array(
				':folder' => BACKUP_PLUGIN_FOLDER ));
		}

		# Проверяем на расширение
		if(!Upload::type($file, array('sql', 'zip')))
		{
			throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN, 'Bad format of file!');
		}

		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		# Имя файла
		$filename = 'uploaded-' . date('YmdHis') . '-' . $file['name'];

		Upload::$default_directory = BACKUP_PLUGIN_FOLDER;
			
		# Cохраняем оригинал и продолжаем работать, если ок: 
		if ($file = Upload::save($file, $filename, NULL, 0777))
		{
			$this->response(__('File :filename successfully uploaded', array(
				':filename' => $filename
			)));

			Kohana::$log->add(Log::ALERT, 'Backup file :filename uploaded by :user', array(
				':filename' => $filename
			))->write();
		}
	}
    
    public function get_list()
	{
        $files = array();
		$handle = opendir(BACKUP_PLUGIN_FOLDER);
		while (false !== ($file = readdir($handle))) 
		{
			if (!preg_match("/(sql|zip)/", $file, $m)) 
			{
				continue;
			}
			
			$date_create = preg_replace('![^\d]*!', '', $file);
			$date_create = preg_replace('#^([\d]{4})([\d]{2})([\d]{2})([\d]{2})([\d]{2})([\d]{2})$#', '$3/$2/$1 $4:$5:$6', $date_create);

			$files[$file] = array(
				'size' => Text::bytes(filesize(BACKUP_PLUGIN_FOLDER.$file)),
				'path' => BACKUP_PLUGIN_FOLDER.$file,
				'date' => $date_create
			);
		}

		closedir($handle);
 
        $this->response(View::factory('backup/list', array(
			'files' => $files
		))->render());
    }
}