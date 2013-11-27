<?php defined('SYSPATH') OR die('No direct script access.');

class Log extends Kohana_Log {
	
	public static function level($level)
	{
		$levels = array(
			'EMERGENCY' => LOG_EMERG,    // 0
			'ALERT'     => LOG_ALERT,    // 1
			'CRITICAL'  => LOG_CRIT,     // 2
			'ERROR'     => LOG_ERR,      // 3
			'WARNING'   => LOG_WARNING,  // 4
			'NOTICE'    => LOG_NOTICE,   // 5
			'INFO'      => LOG_INFO,     // 6
			'DEBUG'     => LOG_DEBUG,    // 7
		);
		
		return Arr::get($levels, $level, 'INFO');
	}
}