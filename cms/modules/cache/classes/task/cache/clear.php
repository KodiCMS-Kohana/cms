<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Задача очистки кеша
 * @package		KodiCMS/Cache
 * @category	Task
 * @author		butschster <butschster@gmail.com>
 */

/**
 * Cli cache for KodiCMS
 *
 * It can accept the following options:
 *  - type: Cache type for clear (default - all) (types - file, routes, profiler)
 */
class Task_Cache_Clear extends Minion_Task
{
	protected $_options = array(
		'type' => 'all'
	);

	protected function _execute(array $params)
	{
		if ($params['type'] === NULL)
		{
			$params['type'] = Minion_CLI::read(__('Please enter cache type foe clear (:types)', array(
				':types' => implode(', ', array('all', 'file', 'routes', 'profiler', CACHE_TYPE))
			)));
		}

		switch ($params['type'])
		{
			case 'file':
				Cache::clear_file();
				break;
			case 'routes':
				Cache::clear_routes();
				break;
			case 'profiler':
				Cache::clear_profiler();
				break;
			case CACHE_TYPE:
				Cache::instance()->delete_all();
				break;
			default:
				Cache::clear_all();
				break;
		}
		
		Minion_CLI::write('============ Cache '. $params['type'] .' cleared ==========');
	}
}