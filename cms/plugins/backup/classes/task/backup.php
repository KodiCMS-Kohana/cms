<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Задача создания бэкапа
 *
 * It can accept the following options:
 *  - folder: Папка, в которую необходимо поместить бэкапы (По умолчанию BACKUP_PLUGIN_FOLDER).
 *  - database: Делать бэкап БД. 0 или 1.
 *  - filesystem: Делать бэкап ФС. 0 или 1.
 * 
 * @package		KodiCMS/Backup
 * @category	Task
 * @author		ButscHSter
 */
class Task_Backup extends Minion_Task
{
	protected $_options = array(
		'database' => 1,
		'filesystem' => 0,
		'folder' => BACKUP_PLUGIN_FOLDER
	);
	
	public function build_validation(Validation $validation)
	{
		return parent::build_validation($validation)
			->rule('folder', 'is_dir')
			->rule('database', 'numeric')
			->rule('filesystem', 'numeric');
	}

	protected function _execute(array $params)
	{
		if($params['database'] > 0)
		{
			Model_Backup::factory($params['folder'] . 'db-'.date('YmdHis').'.sql')
				->create()
				->save();
			
			Minion_CLI::write(__('Database backup created successfully'));
		}
		
		if($params['filesystem'] > 0)
		{
			if(Model_Backup::factory($params['folder'] . 'filesystem-'.date('YmdHis').'.zip')
				->create())
			{
				Minion_CLI::write(__('Filesystem backup created successfully'));
			}
		}
	}
}