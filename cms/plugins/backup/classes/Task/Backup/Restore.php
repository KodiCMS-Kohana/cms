<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Задача восстановления бэкапа
 *
 * It can accept the following options:
 *  - file: Путь до файла ( .sql, .zip), который необходимо восстановить
 * 
 * @package		KodiCMS/Backup
 * @category	Task
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Task_Backup_Restore extends Minion_Task
{
	protected $_options = array(
		'file' => NULL
	);

	protected function _execute(array $params)
	{
		if (file_exists($params['file']))
		{
			$file = $params['file'];
		}
		else if (file_exists(BACKUP_PLUGIN_FOLDER . $params['file']))
		{
			$file = BACKUP_PLUGIN_FOLDER . $params['file'];
		}
		else if (file_exists(DOCROOT . $params['file']))
		{
			$file = DOCROOT . $params['file'];
		}
		else
		{
			Minion_CLI::write(__('File :file not found', array(
				':file' => $params['file']
			)));

			exit();
		}

		try
		{
			$backup = Model_Backup::factory($file)->restore();

			Minion_CLI::write(__('Backup from file :file restored successfully', array(
				':file' => $file
			)));
		}
		catch (Exception $e)
		{
			Minion_CLI::write($e->getMessage());
		}
	}
}