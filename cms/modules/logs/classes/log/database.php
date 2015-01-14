<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Database log writer. Stores log information in a database.
 *
 * @package		KodiCMS/Logs
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Log_Database extends Log_Writer {

	/**
	 * @var  string  Table name to write log data to
	 */
	protected $_table;

	/**
	 * Creates a new file logger. Checks that the directory exists and
	 * is writable.
	 *
	 *     $writer = new Log_File($directory);
	 *
	 * @param   string  $table  table name
	 * @return  void
	 */
	public function __construct($table)
	{
		if (is_string($table))
		{
			$this->_table = $table;
		}
	}

	/**
	 * Writes each of the messages into the database table.
	 *
	 *     $writer->write($messages);
	 *
	 * @param   array   $messages
	 * @return  void
	 */
	public function write(array $messages)
	{
		$user = Auth::get_record(ORM::factory('user'));
		$request = Request::initial();
		
		if ($user === NULL)
		{
			return;
		}

		$logs_level = (int) Config::get('site', 'log_level');

		foreach ($messages as $message)
		{
			if ($message['level'] < $logs_level)
			{
				continue;
			}

			$values = array(
				':user' => HTML::anchor(Route::get('backend')->uri(array(
					'controller' => 'users',
					'action' => 'profile',
					'id' => $user->id
				)), '@' . $user->username),
				':controller' => $request !== NULL ? $request->controller() : 'none'
			);

			$message['additional'][':url'] = $request !== NULL 
				? $request->url()
				: 'none';

			$message['additional'][':ip'] = Request::$client_ip;
			
			$message['body'] = strtr($message['body'], $values);
			
			$data = array(
				'created_on' => date('Y-m-d H:i:s'),
				'user_id' => Auth::get_id(),
				'level' => $message['level'],
				'message' => $message['body'],
				'additional' => json_encode($message['additional'])
			);

			// Write each message into the log database table
			DB::insert($this->_table, array_keys($data))->values($data)->execute();
		}
	}
}
