<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Cli database update
 * 
 * @package		KodiCMS/Update
 * @category	Task
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Task_Update_Database extends Minion_Task
{
	protected function _execute(array $params)
	{		
		$db_sql = Database_Helper::schema();
		$file_sql = Database_Helper::install_schema();

		$compare = new Database_Helper;
		$diff = $compare->get_updates($db_sql, $file_sql, TRUE);

		try
		{
			Database_Helper::insert_sql($diff);
			Cache::instance()->delete(Update::CACHE_KEY_DB_SHEMA);
			
			Minion_CLI::write('==============================================');
			Minion_CLI::write(__('Database schema updated successfully!'));
			Minion_CLI::write('==============================================');
		} 
		catch (Exception $ex)
		{
			Minion_CLI::write('==============================================');
			Minion_CLI::write(__('Something went wrong!'));
			Minion_CLI::write('==============================================');
			Minion_CLI::write(__('Error: :message', array(
				':message' => $ex->getMessage()
			)));
		}
	}
}