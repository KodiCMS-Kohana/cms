<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Database log writer. Stores log information in a database.
 *
 * @package    kohana-log-database
 * @category   Logging
 * @author     Nick Zahn
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
		foreach ($messages as $message)
		{
			$data = array(
				'created_on' => date('Y-m-d H:i:s'),
				'user_id' => AuthUser::getId(),
				'level' => Arr::get($message, 'level'),
				'message' => Arr::get($message, 'body'),
				'additional' => serialize(Arr::get($message, 'additional'))
			);

			// Write each message into the log database table
			DB::insert($this->_table, array_keys($data))->values($data)->execute();
		}
	}

}
