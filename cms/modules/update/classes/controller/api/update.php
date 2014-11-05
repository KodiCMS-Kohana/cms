<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Update
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_API_Update extends Controller_System_API {
	
	public function get_check()
	{
		$this->response(array(
			'version' => (Update::check_version() === Update::VERSION_OLD) ? Update::remote_version() : FALSE,
			'database' => strlen(Update::check_database())
		));
	}
	
	public function get_check_files()
	{
		$this->response((string) View::factory('update/files', array(
			'files' => Update::check_files()
		)));
	}
	
	public function get_database()
	{
		if (!ACL::check('update.database_apply'))
		{
			throw HTTP_API_Exception::factory(API::ERROR_PERMISSIONS, 'You dont hanve permissions to update database');
		}
			
		$db_sql = Database_Helper::schema();
		$file_sql = Database_Helper::install_schema();

		$compare = new Database_Helper;
		$diff = $compare->get_updates($db_sql, $file_sql, TRUE);

		try
		{
			Database_Helper::insert_sql($diff);
		
			$this->message('Database schema updated successfully!');
			
			Cache::instance()->delete(Update::CACHE_KEY_DB_SHEMA);
			$this->response(TRUE);
		} 
		catch (Exception $ex)
		{
			$this->message('Something went wrong!');
			$this->response(FALSE);
		}
		
		Kohana::$log->add(Log::INFO, ':user update database')->write();
	}
}